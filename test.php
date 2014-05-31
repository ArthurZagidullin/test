<?php
/* Запускаем сессию и подключаем файл с классами */
session_start();
require_once('classes/server.php');

$uid = 10;
$user = new User($uid);
$text = new Text($user);
print_r($text->text);