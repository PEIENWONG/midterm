<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include database connection
require_once("dbconnect.php");

// Initialize response array
$response = [
    "status" => "",
    "message" => ""
];

try {
    // Validate required POST parameters
    if (!isset($_POST['work_id']) || !isset($_POST['user_id']) || !isset($_POST['submission_text'])) {
        throw new Exception("Missing required parameters");
    }

    // Sanitize and validate input
    $work_id = filter_var($_POST['work_id'], FILTER_VALIDATE_INT);
    $user_id = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
    $submission_text = trim($_POST['submission_text']);

    if (!$work_id || !$user_id) {
        throw new Exception("Invalid work ID or user ID");
    }

    if (empty($submission_text)) {
        throw new Exception("Submission text cannot be empty");
    }

    // Prepare SQL statement
    $sql = "INSERT INTO tbl_submissions (work_id, user_id, submission_text, submitted_at) 
            VALUES (?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $stmt->bind_param("iis", $work_id, $user_id, $submission_text);

    if ($stmt->execute()) {
        $response["status"] = "success";
        $response["message"] = "Submission successful";
        $response["submission_id"] = $stmt->insert_id;
    } else {
        throw new Exception("Database execution failed: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    $response["status"] = "error";
    $response["message"] = $e->getMessage();
    
    // Log the error for debugging (in a real app, log to file)
    error_log("Submission Error: " . $e->getMessage());
} finally {
    // Ensure database connection is closed
    if (isset($conn)) {
        $conn->close();
    }

    // Send JSON response
    echo json_encode($response);
    exit();
}