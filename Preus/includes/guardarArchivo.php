<?php
$subdir = $_POST['subdir'];
$path = $_POST['path'];
$uploaddir = "../". $path ."". $subdir ."/";
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
  echo "ok";
} else {
   echo "Error subiendo el archivo";
}
?> 