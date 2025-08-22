<?php

ob_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "error_log.txt");

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $server = "localhost";
    $username = "root";
    $password = "";

    $value1 = $_POST['k1'] ?? null;
    $value2 = $_POST['k2'] ?? null;
    $message = $_POST['message'] ?? null;

    if (!$value1 || !$value2 || !$message) {
        $response['errors'][] = 'Invalid input values.';
        echo json_encode($response);
        exit;
    }

    $conn1 = mysqli_connect($server, $username, $password, $value1);

    if (!$conn1) {
        $response['errors'][] = "Error connecting to database 1: " . mysqli_connect_error();
        error_log("Database connection error for DB: $value1 - " . mysqli_connect_error());
        echo json_encode($response);
        exit;
    }

    // Sanitize inputs
    $value2 = mysqli_real_escape_string($conn1, $value2);
    $message = mysqli_real_escape_string($conn1, $message);

    // Check if table exists
    $checkTable = $conn1->query("SHOW TABLES LIKE '$value2'");
    if ($checkTable->num_rows === 0) {
        $response['errors'][] = "Table $value2 does not exist in database $value1.";
        mysqli_close($conn1);
        echo json_encode($response);
        exit;
    }

    // Use prepared statement
    $stmt = $conn1->prepare("INSERT INTO `$value2` (`action`, `message`) VALUES (?, ?)");
    $action = '2';

    if ($stmt) {
        $stmt->bind_param('ss', $action, $message);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Message inserted successfully.";
        } else {
            $response['errors'][] = "Error in execution: " . $stmt->error;
            error_log("Prepared Statement Error: " . $stmt->error);
        }
        $stmt->close();
    } else {
        $response['errors'][] = "Error preparing statement: " . $conn1->error;
        error_log("Prepared Statement Error: " . $conn1->error);
    }

    mysqli_close($conn1);
}

echo json_encode($response);
?>
