<?php
class QuizController extends Controller
{
    // Afficher tous les quiz d'un cours
    public function index($cours_id)
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($cours_id);

        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }

        $quizModel = $this->model('Quiz');
        $quizzes = $quizModel->getByCoursId($cours_id);

        $this->view('admin/quiz/index', [
            'cours' => $cours,
            'quizzes' => $quizzes
        ]);
    }

    // Créer un nouveau quiz
    public function create($cours_id)
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($cours_id);

        if (!$cours) {
            echo "Cours introuvable.";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre']);
            $description = trim($_POST['description']);

            if (empty($titre)) {
                $this->view('admin/quiz/create', [
                    'cours' => $cours,
                    'error' => 'Le titre est obligatoire'
                ]);
                return;
            }

            $quizModel = $this->model('Quiz');
            if ($quizModel->create($cours_id, $titre, $description)) {
                header("Location: /e-learning-role-final/public/quiz/index/{$cours_id}");
                exit;
            } else {
                $this->view('admin/quiz/create', [
                    'cours' => $cours,
                    'error' => 'Erreur lors de la création du quiz'
                ]);
            }
        } else {
            $this->view('admin/quiz/create', [
                'cours' => $cours
            ]);
        }
    }

    // Modifier un quiz existant
    public function edit($id)
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $quizModel = $this->model('Quiz');
        $quiz = $quizModel->getById($id);

        if (!$quiz) {
            echo "Quiz introuvable.";
            exit;
        }

        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($quiz['cours_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre']);
            $description = trim($_POST['description']);

            if (empty($titre)) {
                $this->view('admin/quiz/edit', [
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Le titre est obligatoire'
                ]);
                return;
            }

            if ($quizModel->update($id, $titre, $description)) {
                header("Location: /e-learning-role-final/public/quiz/index/{$quiz['cours_id']}");
                exit;
            } else {
                $this->view('admin/quiz/edit', [
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Erreur lors de la modification du quiz'
                ]);
            }
        } else {
            $this->view('admin/quiz/edit', [
                'quiz' => $quiz,
                'cours' => $cours
            ]);
        }
    }

    // Supprimer un quiz
    public function delete($id)
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $quizModel = $this->model('Quiz');
        $quiz = $quizModel->getById($id);

        if (!$quiz) {
            echo "Quiz introuvable.";
            exit;
        }

        $cours_id = $quiz['cours_id'];

        if ($quizModel->delete($id)) {
            header("Location: /e-learning-role-final/public/quiz/index/{$cours_id}");
            exit;
        } else {
            echo "Erreur lors de la suppression du quiz.";
        }
    }

    // Gérer les questions d'un quiz
    public function questions($quiz_id)
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $quizModel = $this->model('Quiz');
        $quiz = $quizModel->getById($quiz_id);

        if (!$quiz) {
            echo "Quiz introuvable.";
            exit;
        }

        $questionModel = $this->model('Question');
        $questions = $questionModel->getByQuizId($quiz_id);

        // Pour chaque question, récupérer ses options
        $optionModel = $this->model('Option');
        foreach ($questions as &$question) {
            $question['options'] = $optionModel->getByQuestionId($question['id']);
        }

        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($quiz['cours_id']);

        $this->view('admin/quiz/questions', [
            'quiz' => $quiz,
            'cours' => $cours,
            'questions' => $questions
        ]);
    }

    // Ajouter une question à un quiz
    public function addQuestion($quiz_id)
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $quizModel = $this->model('Quiz');
        $quiz = $quizModel->getById($quiz_id);

        if (!$quiz) {
            echo "Quiz introuvable.";
            exit;
        }

        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($quiz['cours_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $texte = trim($_POST['texte']);
            $type = $_POST['type'];
            $options = $_POST['options'] ?? [];
            $correctes = $_POST['correctes'] ?? [];

            // Validation
            if (empty($texte)) {
                $this->view('admin/quiz/add_question', [
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Le texte de la question est obligatoire'
                ]);
                return;
            }

            if (count($options) < 2) {
                $this->view('admin/quiz/add_question', [
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Une question doit avoir au moins 2 options'
                ]);
                return;
            }

            if (empty($correctes)) {
                $this->view('admin/quiz/add_question', [
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Vous devez sélectionner au moins une réponse correcte'
                ]);
                return;
            }

            // Si type unique, s'assurer qu'une seule réponse est correcte
            if ($type === 'unique' && count($correctes) > 1) {
                $this->view('admin/quiz/add_question', [
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Une question à choix unique ne peut avoir qu\'une seule réponse correcte'
                ]);
                return;
            }

            // Obtenir l'ordre le plus élevé actuel pour incrémenter
            $questionModel = $this->model('Question');
            $ordre = $questionModel->getHighestOrder($quiz_id) + 1;

            // Créer la question
            $question_id = $questionModel->create($quiz_id, $texte, $type, $ordre);

            if ($question_id) {
                // Créer les options pour cette question
                $optionModel = $this->model('Option');
                $ordre_option = 1;

                foreach ($options as $key => $texte_option) {
                    $texte_option = trim($texte_option);
                    if (!empty($texte_option)) {
                        $est_correcte = in_array($key, $correctes) ? 1 : 0;
                        $optionModel->create($question_id, $texte_option, $est_correcte, $ordre_option);
                        $ordre_option++;
                    }
                }

                header("Location: /e-learning-role-final/public/quiz/questions/{$quiz_id}");
                exit;
            } else {
                $this->view('admin/quiz/add_question', [
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Erreur lors de la création de la question'
                ]);
            }
        } else {
            $this->view('admin/quiz/add_question', [
                'quiz' => $quiz,
                'cours' => $cours
            ]);
        }
    }

    // Modifier une question existante
    public function editQuestion($id)
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $questionModel = $this->model('Question');
        $question = $questionModel->getById($id);

        if (!$question) {
            echo "Question introuvable.";
            exit;
        }

        $quizModel = $this->model('Quiz');
        $quiz = $quizModel->getById($question['quiz_id']);

        $optionModel = $this->model('Option');
        $options = $optionModel->getByQuestionId($id);

        $coursModel = $this->model('Cours');
        $cours = $coursModel->getById($quiz['cours_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $texte = trim($_POST['texte']);
            $type = $_POST['type'];
            $optionsTexte = $_POST['options'] ?? [];
            $optionsId = $_POST['option_ids'] ?? [];
            $correctes = $_POST['correctes'] ?? [];

            // Validation
            if (empty($texte)) {
                $this->view('admin/quiz/edit_question', [
                    'question' => $question,
                    'options' => $options,
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Le texte de la question est obligatoire'
                ]);
                return;
            }

            // Valider que nous avons au moins 2 options non vides
            $optionsNonVides = 0;
            foreach ($optionsTexte as $opt) {
                if (!empty(trim($opt))) {
                    $optionsNonVides++;
                }
            }

            if ($optionsNonVides < 2) {
                $this->view('admin/quiz/edit_question', [
                    'question' => $question,
                    'options' => $options,
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Une question doit avoir au moins 2 options'
                ]);
                return;
            }

            if (empty($correctes)) {
                $this->view('admin/quiz/edit_question', [
                    'question' => $question,
                    'options' => $options,
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Vous devez sélectionner au moins une réponse correcte'
                ]);
                return;
            }

            // Si type unique, s'assurer qu'une seule réponse est correcte
            if ($type === 'unique' && count($correctes) > 1) {
                $this->view('admin/quiz/edit_question', [
                    'question' => $question,
                    'options' => $options,
                    'quiz' => $quiz,
                    'cours' => $cours,
                    'error' => 'Une question à choix unique ne peut avoir qu\'une seule réponse correcte'
                ]);
                return;
            }

            // Mettre à jour la question
            $questionModel->update($id, $texte, $type, $question['ordre']);

            // Supprimer toutes les options existantes et en créer de nouvelles
            $optionModel->deleteByQuestionId($id);
            $ordre_option = 1;

            foreach ($optionsTexte as $key => $texte_option) {
                $texte_option = trim($texte_option);
                if (!empty($texte_option)) {
                    $est_correcte = in_array($key, $correctes) ? 1 : 0;
                    $optionModel->create($id, $texte_option, $est_correcte, $ordre_option);
                    $ordre_option++;
                }
            }

            header("Location: /e-learning-role-final/public/quiz/questions/{$question['quiz_id']}");
            exit;
        } else {
            $this->view('admin/quiz/edit_question', [
                'question' => $question,
                'options' => $options,
                'quiz' => $quiz,
                'cours' => $cours
            ]);
        }
    }

    // Supprimer une question
    public function deleteQuestion($id)
    {
        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /e-learning-role-final/public/login');
            exit;
        }

        $questionModel = $this->model('Question');
        $question = $questionModel->getById($id);

        if (!$question) {
            echo "Question introuvable.";
            exit;
        }

        $quiz_id = $question['quiz_id'];

        if ($questionModel->delete($id)) {
            header("Location: /e-learning-role-final/public/quiz/questions/{$quiz_id}");
            exit;
        } else {
            echo "Erreur lors de la suppression de la question.";
        }
    }
}