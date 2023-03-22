<?php
  //Подключаемся к базе данных
  $host = 'localhost';
  $db_user = 'root';
  $db_password = '';
  $db_name = 'tz_bd';
  $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

  //Проверяем подключение
  if (mysqli_connect_errno()) {
    die('Ошибка подключения: ' . mysqli_connect_error()); 
  }
  $mysqli->query("SET NAMES 'utf8'");

?>