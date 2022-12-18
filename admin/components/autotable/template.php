<?php 
    // require_once(__DIR__ . '/sctipt.js');

    $arBadNames = ['user', 'order'];
    GLOBAL $arParams;

    if(!$arParams['table_name']){
        echo 'нет таблицы!';
        exit;
    }

    // TODO убрать запрос в нормальное располложение (?)
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/api/helpers/query.php');

    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }

    try {
        if($arParams['fields']){
            $selectFields = implode(', ', array_keys($arParams['fields']));

        } else {
            $selectFields = "*";
            $arParams['fields'] = TABLE_FIELDS_MANUAL[$arParams['table_name']];
        }

        if(in_array($arParams['table_name'], $arBadNames)) {
            $arParams['table_name'] = '"' . $arParams['table_name'] . '"';
        }

        $query = 'SELECT ' . $selectFields . ' FROM ' . $arParams['table_name'];
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
?>

<div class="table-control-btns">
    <button class='btn btn_success' id='btn-addElement'>Добавить</button>
    <button class='btn btn_danger' id='btn-delElements'>Удалить</button>
</div>

<table 
    class="main-table" 
    data-table='<?=$arParams['table_name']?>' 
    data-identity='<?=$arParams['identity']?>'
>

    <thead class="main-table__head">
        <tr>
            <th></th>
            <?foreach($arParams['fields'] as $key => $field):?>
                <th class="main-table__th">
                    <b><?echo $field ?: $key?></b></br>
                    <span><?echo $field ? $key : ''?></span>
                </th>
            <?endforeach;?>
            <th></th>
        </tr>
    </thead>

    <tbody class="main-table__body">
        <?php
        foreach($pdo->query($query) as $row) {?>            
            <tr class="main-table__item">
                <td class="main-table__tr">
                    <input type="checkbox" name="check_row" id="" data-id='<?=$row[$arParams['identity']]?>'>
                </td>

                <?php foreach($arParams['fields'] as $key => $field):?>
                    <td class="main-table__tr">
                        <?= $row[$key] ?: 'NULL'?>
                    </td>
                <?endforeach;?> 

                <td class="main-table__tr" data-id='<?=$row[$arParams['identity']]?>'>
                    ред.
                </td>           
            </tr>
        <?}?>
    </tbody>
</table>

<script src="/admin/components/autotable/script.js"></script>