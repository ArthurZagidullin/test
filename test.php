<?php
/* Запускаем сессию и подключаем файл с классами */
session_start();
require_once('classes/MysqliDb.php');
require_once('classes/server.php');
$db = new Mysqlidb('localhost', 'root', '', 'readspeed');
$db->where('id_text',4);
$q = $db->get('questions');

$a = array(8 => 11);

$b = array(array(1=>2),array(2=>3),array(8=>11));

$c = array_search($a, $b);
//Lib::Debug($b);


 $d[] = Array(
            'id' => 28,
            'id_text' => 10,
            'text_question' => 'Как называется текст?',
            'answer' => 64
        );

  $d[] = Array(
            'id' => 28,
            'id_text' => 10,
            'text_question' => 'Как называется текст?',
            'answer' => 767
        );

 $z[] = array('answer' => 64);

 $j = array_search($z,$d);

 Lib::Debug( $d);


echo search($array,'best_admin'); 