<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

GLOBAL $arParams;
$arParams = [
    'table_name' => $_GET['table'] ?: 'user',
    'identity' => $_GET['identity'] ?: 'user_id',
    'order_by' => [
        $_GET['identity'] => 'ASC'
    ]
];

getComponent('autotable');
?>