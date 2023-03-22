<?php
    //Подключаем файл подключения к базе данных
    require_once('connect_db.php');
    
    //Функция вывода сообщения
    function out_message($message = ''){
        header('Location: index.php?message='.$message);
    }
    //Обновление данных
    if(isset($_POST['btn_update'])){
        $result = $mysqli->query("SELECT * FROM `db_table`");
        for($i=0; $i <= mysqli_num_rows($result); $i++){
            $mysqli->query("UPDATE `db_table` SET `title` = '".$_POST['id_'.$i]."' WHERE `id` = '$i'");
        }
        header('Location: /');
    }
    //Экспорт таблицы в csv файл
    if(isset($_POST['btn_export'])){
        header('Content-Type: text/csv; charset=utf-8');  
        header('Content-Disposition: attachment; filename=export.csv');  
        $output = fopen("php://output", "w");  
        fputcsv($output, array('Код', 'Название', 'Error'));
        $result = $mysqli->query("SELECT * FROM `db_table` ORDER BY id");
        while($row = mysqli_fetch_assoc($result)){
            $row = array_slice($row, 1);
            if(strpos($row['title'], '%s')){
                $row['error'] = '%s';
            }else{
                $row['error'] = '';
            }
            fputcsv($output, $row);
        }
            fclose($output);
    }
    //Импорт файла в БД
    if(isset($_POST['btn_add'])){
        $uploadCSV = trim(strip_tags($_FILES['file']['name']));
        $extension = substr(strrchr($uploadCSV,'.'), 1);
        if($extension == 'csv'){
            if ($_FILES["file"]["error"] > 0) {
                out_message($message = "<br><hr>Возврат ошибки: " . $_FILES["file"]["error"] . "<br />");
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
                    $mysqli->query("INSERT INTO `db_table` (`code`,`title`) VALUES ('".$row['code']."','".$row['title']."')");
                    header('Location: /');

                }
            }
        }else out_message($message = '<br><hr>Неверный тип файла');
    }
    //Очистить таблицу
    if(isset($_POST['btn_truncate'])){
        $mysqli->query("TRUNCATE `db_table`");
        header('Location: /');
    }
?>   