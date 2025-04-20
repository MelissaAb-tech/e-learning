<?php
class EtudiantController extends Controller
{
    public function dashboard()
    {
        $cours = $this->model('Cours')->getAll();
        $this->view('etudiant/dashboard', ['cours' => $cours]);
    }
}
