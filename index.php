<?php
//Подключаем файл подключения к базе данных
require_once('connect_db.php');
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TZ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="w-50 m-auto">
    <div class="text-center p-20">
        <h4>Форма загрузки файла и его изменения</h4>
        <form enctype="multipart/form-data" action="uploaded.php" method="POST">
<?
$result = $mysqli->query("SELECT * FROM `db_table`");
if(mysqli_num_rows($result) >= 1){
    while($row = mysqli_fetch_array($result)){ 
         $id_name = 'id_'.$row['id'];
?>
        <span>№-<?=$row['code']?> <input type="text" value="<?=$row['title'];?>" name="<?=$id_name;?>" class="w-50"><br>
<?
    }
?> 
        <br><input type="submit" name="btn_update" value="Обновить колонки">
        <br><br><input type="submit" name="btn_export" value="Экспортировать">
        <br><br><input type="submit" name="btn_truncate" value="Сбросить таблицу">
<?}else {
    echo '
        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
        <input type="file" name="file">
        <input type="submit" name="btn_add" value="Загрузить">
    ';
    echo $_GET['message'];
}
?>
        </form>
    </div>
</body>
</html>
