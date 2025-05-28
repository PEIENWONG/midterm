<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *"); // running as crome app

if (!isset($_POST)) {
	$response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die;
}

include_once("dbconnect.php");

$name = $_POST['user_name'];
$email = $_POST['user_email'];
$password = sha1($_POST['user_password']);
$phone = $_POST['user_phone'];
$address = $_POST['user_address'];

$sqlinsert="INSERT INTO `tbl_users`(`user_name`, `user_email`, `user_password`, `user_phone`, `user_address`) VALUES ('$name','$email','$password','$phone','$address')";

try{
    if ($conn->query($sqlinsert) === TRUE) {
        $response = array('status' => 'success', 'data' => null);
        sendJsonResponse($response);
    } else {
        $response = array('status' => 'failed', 'data' => null);
        sendJsonResponse($response);
    }   
}catch (Exception $e) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die;
}
	

function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}

?>