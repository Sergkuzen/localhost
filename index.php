<?php
//Подключаем файл подключения к базе данных
require_once('connect_db.php');
?>
<html>
<head>
    <title>TZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    .text-center{
        text-align: center;
    }
    .border{
        border: 1px solid lightblue;
    }
    .m-auto{
        margin: 0 auto;
    }
    .p-10{
        padding: 10px;
    }
    .p-20{
        padding: 20px;
    }
    .w-50{
        width: 50%;
    }
    .w-auto{
        width: auto;
    }
    @media (max-width: 540px) { 
        .w-50{
            width: 100%;
        }
    }
</style>
</head>
<body class="w-50 m-auto">
    <div class="text-center p-20">
        <h4>Форма загрузки файла и его изменения</h4>
        <form enctype="multipart/form-data" action="/" method="POST">
<?
$result = $mysqli->query("SELECT * FROM `db_table`");
if(mysqli_num_rows($result) >= 1){
    if(isset($_POST['btn_update'])){
        for($i=0; $i <= mysqli_num_rows($result); $i++){
            $mysqli->query("UPDATE `db_table` SET `title` = '".$_POST['id_'.$i]."' WHERE `id` = '$i'");
        }
        header('Location: /');
    }
    if(isset($_POST['btn_export'])){
        header('Content-Type: text/csv; charset=utf-8');  
        header('Content-Disposition: attachment; filename=data.csv');  
        $output = fopen("php://output", "w");  
        fputcsv($output, array('Код', 'Название'));  
        $result = $mysqli->query("SELECT * FROM `db_table` ORDER BY id DESC");  
        while($row = mysqli_fetch_assoc($result))  
        {  
            fputcsv($output, $row); 
        }  
            fclose($output);
    }
    while($row = mysqli_fetch_array($result)){ 
         $id_name = 'id_'.$row['id'];
?>
        <span>№-<?=$row['code']?> <input type="text" value="<?=$row['title'];?>" name="<?=$id_name;?>" class="w-50"><br>
<?
    }
?> 
        <br><input type="submit" name="btn_update" value="Обновить колонки">
        <br><br><input type="submit" name="btn_export" value="Экспортировать">
<?}else {
    $message = '';
    if(isset($_POST['btn_add'])){
        $uploadCSV = trim(strip_tags($_FILES['file']['name']));
        $extension = substr(strrchr($uploadCSV,'.'), 1);
        if($extension == 'csv'){
            if ($_FILES["file"]["error"] > 0) {
                $message = "<br><hr>Возврат ошибки: " . $_FILES["file"]["error"] . "<br />";
            }else{
                move_uploaded_file($_FILES["file"]["tmp_name"], 'csv.csv');
                $fh = fopen('csv.csv', "r");
                fgetcsv($fh, 0, ',');

                $data = [];
                while (($row = fgetcsv($fh, 0, ',')) !== false) {
                    list($code, $title) = $row;

                    $data[] = [
                        'code' => $code,
                        'title' => $title
                    ];
                }
                foreach ($data as $row) {
                    $mysqli->query("INSERT INTO db_table (`code`,`title`) VALUES ('".$row['code']."','".$row['title']."')");
                    header('Location: /');
                }
            }
        }else $message = '<br><hr>Неверный тип файла';
    }
    echo '
        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
        <input type="file" name="file">
        <input type="submit" name="btn_add" value="Загрузить">
    ';
    echo $message;
}
?>
        </form>
    </div>
</body>
</html>
