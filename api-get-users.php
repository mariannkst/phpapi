<?php

header("Content-Type:application/json");

// Check access

$accessToken = "12345";

if (!isset($_GET['accessToken'])){
    header('HTTP/1.0 401 Unauthorized');
    echo '{"statusCode":"'. http_response_code() .'", "message": "Unauthorized"}';
    exit();

} else {

    $userAccessToken = $_GET['accessToken'];

    if ($accessToken != $userAccessToken) {

        header('HTTP/1.0 401 Unauthorized');
        echo '{"statusCode":"'. http_response_code() .'", "message": "Unauthorized"}';
        exit();
    }
}

// Connect to database
require_once 'database.php';

// Get the data from the database
try{
    $sQuery = $db->prepare('SELECT * FROM users');
    $sQuery->execute();
    // Get the array of users 
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

// Error handling
}catch(PDOException $ex){

    http_response_code(500);
    echo '{"statusCode":"'. http_response_code() .'", "message": "Internal server error"}';
    exit();

}
