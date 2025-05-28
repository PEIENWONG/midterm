<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests

if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die;
}

include_once("dbconnect.php");

$user_id = $_POST['user_id'];

$sql = "SELECT * FROM tbl_works WHERE assigned_to = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $works = array();
    while ($row = $result->fetch_assoc()) {
        $work = array(
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'assigned_to' => $row['assigned_to'],
            'date_assigned' => $row['date_assigned'],
            'due_date' => $row['due_date'],
            'status' => $row['status']
        );
        array_push($works, $work);
    }
    $response = array('status' => 'success', 'data' => $works);
    sendJsonResponse($response);
} else {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}
?>