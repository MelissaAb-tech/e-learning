<?php
$router->get('', 'AuthController@home');
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@loginPost');
$router->get('register', 'AuthController@register');
$router->post('register', 'AuthController@registerPost');
$router->get('admin/dashboard', 'AdminController@dashboard');
$router->get('etudiant/dashboard', 'EtudiantController@dashboard');

$router->get('admin/dashboard', 'AdminController@dashboard');
$router->get('admin/ajouter', 'AdminController@ajouter');
$router->post('admin/ajouter', 'AdminController@ajouter');
$router->get('admin/modifier/([0-9]+)', 'AdminController@modifier');
$router->post('admin/modifier/([0-9]+)', 'AdminController@modifier');
$router->get('admin/supprimer/([0-9]+)', 'AdminController@supprimer');

$router->get('cours/voir/([0-9]+)', 'CoursController@voir');
$router->post('cours/voir/([0-9]+)', 'CoursController@voir');
// Nouvelle route pour réinitialiser un cours
$router->get('cours/reinitialiser/([0-9]+)', 'CoursController@reinitialiser');

$router->get('admin/chapitre/ajouter/([0-9]+)', 'ChapitreController@ajouter');
$router->post('admin/chapitre/ajouter/([0-9]+)', 'ChapitreController@ajouter');

$router->get('admin/chapitre/supprimer/([0-9]+)/([0-9]+)', 'ChapitreController@supprimer');
$router->post('chapitre/valider', 'ChapitreController@valider');

$router->get('admin/dashboard', 'AdminController@dashboard');

$router->get('admin/etudiant/ajouter', 'AdminController@etudiantAjouter');
$router->post('admin/etudiant/ajouter', 'AdminController@etudiantAjouter');
$router->get('admin/etudiant/supprimer/([0-9]+)', 'AdminController@etudiantSupprimer');

$router->get('etudiant/mon-compte', 'EtudiantController@monCompte');
$router->post('etudiant/mon-compte', 'EtudiantController@monComptePost');
$router->post('etudiant/mon-compte/modifier', 'EtudiantController@monComptePost');
$router->get('etudiant/inscrire/([0-9]+)', 'EtudiantController@inscrire');
$router->get('etudiant/desinscrire/([0-9]+)', 'EtudiantController@desinscrire');

// Routes pour le forum
$router->get('forum/cours/([0-9]+)', 'ForumController@index');
$router->get('forum/creer/([0-9]+)', 'ForumController@creer');
$router->post('forum/creer/([0-9]+)', 'ForumController@creer');
$router->get('forum/voir/([0-9]+)', 'ForumController@voir');
$router->post('forum/repondre/([0-9]+)', 'ForumController@repondre');
$router->get('forum/supprimer_topic/([0-9]+)', 'ForumController@supprimer_topic');
$router->get('forum/supprimer_reponse/([0-9]+)', 'ForumController@supprimer_reponse');

// Routes pour les quiz côté admin
$router->get('quiz/index/([0-9]+)', 'QuizController@index');
$router->get('quiz/create/([0-9]+)', 'QuizController@create');
$router->post('quiz/create/([0-9]+)', 'QuizController@create');
$router->get('quiz/edit/([0-9]+)', 'QuizController@edit');
$router->post('quiz/edit/([0-9]+)', 'QuizController@edit');
$router->get('quiz/delete/([0-9]+)', 'QuizController@delete');

// Routes pour les questions des quiz
$router->get('quiz/questions/([0-9]+)', 'QuizController@questions');
$router->get('quiz/addQuestion/([0-9]+)', 'QuizController@addQuestion');
$router->post('quiz/addQuestion/([0-9]+)', 'QuizController@addQuestion');
$router->get('quiz/editQuestion/([0-9]+)', 'QuizController@editQuestion');
$router->post('quiz/editQuestion/([0-9]+)', 'QuizController@editQuestion');
$router->get('quiz/deleteQuestion/([0-9]+)', 'QuizController@deleteQuestion');

// Routes pour les quiz côté étudiant
$router->get('quiz/etudiant/tenter/([0-9]+)', 'QuizController@tenter');
$router->post('quiz/etudiant/soumettre/([0-9]+)', 'QuizController@soumettre');
$router->get('quiz/etudiant/resultats/([0-9]+)', 'QuizController@resultats');
$router->get('quiz/etudiant/liste/([0-9]+)', 'QuizController@listeQuiz');


// Route pour le certificat (à implémenter plus tard)
$router->get('certificat/generer/([0-9]+)', 'CertificatController@generer');

// Routes pour la déconnexion
$router->get('logout', 'EtudiantController@logout');
$router->get('logout', 'AdminController@logout');

// Routes pour la modif des infos etudiant par l'admin
$router->get('admin/etudiant/modifier/([0-9]+)', 'AdminController@etudiantModifier');
$router->post('admin/etudiant/modifier/([0-9]+)', 'AdminController@etudiantModifier');

$router->post('/etudiant/feedback', 'EtudiantController@feedback');
