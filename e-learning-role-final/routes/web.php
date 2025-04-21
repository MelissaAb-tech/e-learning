<?php
// Route pour la page d'accueil
$router->get('', 'HomeController@index');

// Routes pour l'authentification
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@loginPost');
$router->get('register', 'AuthController@register');
$router->post('register', 'AuthController@registerPost');

// Routes pour l'espace admin
$router->get('admin/dashboard', 'AdminController@dashboard');
$router->get('admin/ajouter', 'AdminController@ajouter');
$router->post('admin/ajouter', 'AdminController@ajouter');
$router->get('admin/modifier/([0-9]+)', 'AdminController@modifier');
$router->post('admin/modifier/([0-9]+)', 'AdminController@modifier');
$router->get('admin/supprimer/([0-9]+)', 'AdminController@supprimer');

// Routes pour l'espace étudiant
$router->get('etudiant/dashboard', 'EtudiantController@dashboard');

// Routes pour les cours
$router->get('cours/voir/([0-9]+)', 'CoursController@voir');
$router->post('cours/voir/([0-9]+)', 'CoursController@voir');

// Routes pour les chapitres
$router->get('admin/chapitre/ajouter/([0-9]+)', 'ChapitreController@ajouter');
$router->post('admin/chapitre/ajouter/([0-9]+)', 'ChapitreController@ajouter');
$router->get('admin/chapitre/supprimer/([0-9]+)/([0-9]+)', 'ChapitreController@supprimer');