<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/api/helpers/query.php');

try{
    $pdo = \Helpers\query\connectDB();
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

$createrID = '1'; // создатель теста 

if($_POST['dateTest']) {
    try{
        $data = $pdo->prepare("CALL public.addanswerlogbytest(?, ?, ?, ?)");
        $data->execute([
            $_POST['student'],
            $_POST['test'],
            $createrID,
            $_POST['dateTest']
        ]);

        $success = "Назначено успешно!";        
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }

    unset($_POST['dateTest']);
} 


// запрос пользователей
$query = 'SELECT u.user_id, u.user_lastname, u.user_firstname, st.studgroup_name 
    FROM "user" as u
    JOIN studgroup as st
    ON u.studgroup_id = st.studgroup_id
    JOIN teaches as te
    ON te.user_id = ' . $createrID . '
    AND te.studgroup_id = st.studgroup_id
    ORDER BY user_id';

$data = $pdo->prepare($query);
$data->execute();
$users = $data->fetchAll();

// запрос тестов для организации 1
$query = 'SELECT test_id, test_name 
    FROM test
    WHERE org_id = 1
    ORDER BY test_id';

$data = $pdo->prepare($query);
$data->execute();
$tests = $data->fetchAll();

?>

<form action="" method="post" class='addtest'>
    <h4>Назначение тестирования</h4>

    <div class='error'><?=$error?></div>
    <div class='success'><?=$success?></div>

    <div class="form-item">
        <label for="students">Выберите студента</label>

        <select name='student' require>
            <?foreach($users as $user):?>
                <option value="<?=$user['user_id']?>">
                    <?= $user['user_firstname'] . " " . $user['user_lastname'] . " [" . $user['studgroup_name'] . "]"?>
                </option>
            <?endforeach;?>
        </select>
    </div>

    <div class="form-item">
        <label for="test">Выберите тест</label>

        <select name='test' require>
            <?foreach($tests as $test):?>
                <option value="<?=$test['test_id']?>">
                    <?= $test['test_name'] . " [" . $test['test_id'] . "]"?>
                </option>
            <?endforeach;?>
        </select>
    </div>

    <div class="form-item">
        <label for="dateTest">Дата тестирования</label>
        <input type="date" name="dateTest">
    </div>

    <button class='btn btn_success' type="submit">Добавить</button>
    <button class='btn btn_danger' type="reset">Сбросить</button>
</form>