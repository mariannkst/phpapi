<?php

header("Content-Type:application/json");
//ini_set('display_errors', FALSE);

// Check access

$accessToken = "12345";

if (!isset($_GET['accessToken'])){
    http_response_code(401);
    echo '{"statusCode":"'. http_response_code() .'", "message": "Unauthorized"}';
    exit();

} else {

    $userAccessToken = $_GET['accessToken'];

    if ($accessToken != $userAccessToken) {

        http_response_code(401);
        echo '{"statusCode":"'. http_response_code() .'", "message": "Unauthorized"}';
        exit();
    }
}

if (!isset($_POST['name']) || 
    !isset($_POST['email']) || 
    !isset($_POST['phoneNumber']) || 
    !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)  // validate email
    ){

        http_response_code(400);
        echo '{"statusCode":"'. http_response_code() .'", "message": "Bad Request"}';
        exit();
}

$name = $_POST['name'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];

// Connect to database
require_once 'database.php';

// Prepare statement and add user
try{
    $sQuery = $db->prepare('INSERT INTO users VALUES(null, :sName, :sEmail, :sPhoneNumber)');

    $sQuery->bindValue(':sName', $name);
    $sQuery->bindValue(':sEmail', $email);
    $sQuery->bindValue(':sPhoneNumber', $phoneNumber);

    $sQuery->execute();

    if( $sQuery->rowCount() ){
        echo '{ "statusCode":"'. http_response_code() .'",
                "message": "User saved",
                "user": {
                        "name": "'.$name.'",
                        "email": "'.$email.'",
                        "phoneNumber": "'.$phoneNumber.'"
                        }';
        
        exit();
    }

    http_response_code(400);
    echo '{"statusCode":"'. http_response_code() .'", "message": "Bad Request"}';

    $sQuery = null;
    $db = null;

// Error handling
}catch(PDOException $ex){

    http_response_code(500);
    echo '{"statusCode":"'. http_response_code() .'", "message": "Internal Server Error"}';
    exit();

}
