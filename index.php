<?php

ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);

if (phpversion() < '5.2.0') {
    die('Twój server musi obsługiwać PHP w wersji 5.2.0 lub większej!');
}

function __autoload($className) {
    if (preg_match('/^[a-z][0-9a-z]*(_[0-9a-z]+)*$/i', $className)) {
        require 'core/' . $className . '.class.php';
    }
}

$core = new core;

function connection() {
    $db = new PDO(core::$config['db']['driver'] . 'dbname=' . core::$config['db']['db_name'] . ';host=' . core::$config['db']['host'], core::$config['db']['user'], core::$config['db']['password']);
    if (!$db) {
        return 'Błąd podczas łączenia się z bazą danych.';
    } else {
        return $db;
    }
}

$pdo = connection();
$session = new session;
require 'template/header.php';
require 'template/front.php';
require 'template/footer.php';
ob_end_flush();
?>