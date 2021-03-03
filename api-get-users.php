<?php
/*
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} else {
    echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
    echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
}*/



header("Content-Type:application/json");

require_once 'database.php';

try{
    $sQuery = $db->prepare('SELECT * FROM users');
    $sQuery->execute();
    // Array 
    $aUsers = $sQuery->fetchAll();

    if (empty($aUsers)) {

        echo '{"statusCode":"'. http_response_code() .'", "message": "There are no users"}';

        $sQuery = null;
        $db = null;

        exit();

    } else if (!empty($aUsers)){
        echo json_encode($aUsers);
    }

    $sQuery = null;
    $db = null;

}catch(PDOException $ex){

    http_response_code(500);
    echo '{"statusCode":"'. http_response_code() .'", "message": "Internal server error"}';
    exit();

}
