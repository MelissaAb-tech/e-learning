<?php
class HomeController extends Controller
{
    public function index()
    {
        $cours = $this->model('Cours')->getAll();
        $this->view('home/index', ['cours' => $cours]);
    }
}