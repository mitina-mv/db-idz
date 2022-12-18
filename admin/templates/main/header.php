<?php
foreach($config['menu'] as $menu){
    include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/menu/' . $menu . '.php');
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заголовок страницы</title>

    <link rel="stylesheet" href="/admin/templates/main/style.css">
    <script src="/admin/assets/js/script.js"></script>

</head>
<body>
