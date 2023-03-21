<?php
  //Подключаемся к базе данных
  $mysqli = new mysqli("localhost", "root", "", "tz_bd");

  //Проверяем подключение
  if (mysqli_connect_errno()) {
    die('Ошибка подключения: ' . mysqli_connect_error()); 
  }
  $mysqli->query("SET NAMES 'utf8'");

?>