<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

GLOBAL $arParams;
$arParams = [
    'fields' => [
        'org_id' => 'ID огранизации',
        'user_id' => 'ID пользователя',        
        'user_login' => 'Логин',
        'user_firstname' => 'Имя',
        'user_lastname' => 'Фамилия',
        'user_patronymic' => 'Отчество',
        'user_email' => 'Почта',
        'role_id' => 'ID группы прав',
        'studgroup_id' => 'ID группы студентов',
    ],
    'table_name' => 'user',
    'identity' => 'user_id',
    'order_by' => [
        'user_id' => 'ASC'
    ]
];

getComponent('autotable');
?>