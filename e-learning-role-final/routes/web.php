<?php
$router->get('', 'AuthController@login');
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

$router->get('admin/chapitre/ajouter/([0-9]+)', 'ChapitreController@ajouter');
$router->post('admin/chapitre/ajouter/([0-9]+)', 'ChapitreController@ajouter');

$router->get('admin/chapitre/supprimer/([0-9]+)/([0-9]+)', 'ChapitreController@supprimer');
