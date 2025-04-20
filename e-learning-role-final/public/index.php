<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../core/Router.php';
require_once '../core/Controller.php';
require_once '../core/Database.php';
session_start();
$router = new Router();
require_once '../routes/web.php';
$router->dispatch($_GET['url'] ?? '');
