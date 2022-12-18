<?php

// Роутинг, основная функция
function route($data) {

    // GET /photo
    if ($data['method'] === 'GET' && count($data['urlData']) === 1) {
        echo json_encode(getPhotos($_SESSION['user']['login']));
        exit;
    }
    // GET /photo/mary
    if ($data['method'] === 'GET' && count($data['urlData']) === 2) {
        echo json_encode(getPhotos($data['urlData'][1]));
        exit;
    }

    // POST /table  method delete
    if ($data['method'] === 'POST' && $data['formData']['method'] == 'delete') {
        echo json_encode(deleteNodes($data['formData']));
        exit;
    }

    // POST /photo
    if ($data['method'] === 'POST') {
        echo json_encode(addPhoto($data['formData']));
        exit;
    }

    // PUT /photo/5
    if ($data['method'] === 'PUT' && count($data['urlData']) === 2 && isset($data['formData']['title'])) {
        $id = (int)$data['urlData'][1];
        $title = $data['formData']['title'];

        echo json_encode(updatePhoto($id, $title));
        exit;
    }

    // DELETE /photo/5
    if ($data['method'] === 'DELETE' && count($data['urlData']) === 2) {
        $id = (int)$data['urlData'][1];

        echo json_encode(deletePhoto($id));
        exit;
    }
    print_r($data['formData']);
    // DELETE /photo/
    if ($data['method'] === 'DELETE' && count($data['urlData']) === 1) {
        // $id = (int)$data['urlData'][1];

        // echo json_encode(deleteNodes($id));
        exit;
    }


    // Если ни один роутер не отработал
    \Helpers\query\throwHttpError('invalid_parameters', 'invalid parameters');

}

function deleteNodes($fData) {
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    if(!is_array($fData['ids'])) {
        $fData['ids'] = [$fData['ids']];
    }

    foreach($fData['ids'] as $id) {
        if (!\Helpers\query\isExistsNodeById($pdo, $id, $fData['identityField'], $fData['table'])) {
            \Helpers\query\throwHttpError('node not exists', 'Запись с id не найдена', '404 node not exists');
            exit;
        }

        try {
            $query = 'DELETE FROM '.$fData['table'].' WHERE '.$fData['identityField'].'=:id';
    
            $data = $pdo->prepare($query);        
            $data->execute([
                'id' => $id
            ]);
        } catch (PDOException $e) {
            echo $e->getMessage();
            \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 error node delete');
            exit;
        }
    }

    return array(
        'ids' => $fData['ids']
    );
}