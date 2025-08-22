<?php
// Start output buffering to prevent accidental output
ob_start();

// Enable error reporting and log errors instead of displaying them
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "error_log.txt");  // Log errors to a file

header('Content-Type: application/json');  // Ensure JSON header is set early

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize POST data
    $value1 = isset($_POST['key1']) ? $_POST['key1'] : null;
    $value2 = isset($_POST['key2']) ? $_POST['key2'] : null;

    // if (!$value1) {
    //     echo json_encode(['error' => 'Invalid input values.']);
    //     exit;
    // }

    // Database connection details
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = $value1;

    // Establish a database connection
    $conn = mysqli_connect($server, $username, $password, $database);

    // Check connection
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
        exit;
    }

    // Prepare the SQL query with escaping
    // $value2 = mysqli_real_escape_string($conn, $value2);
    $sql = "CREATE TABLE `$value2` (
        sno INT AUTO_INCREMENT PRIMARY KEY,
        action INT,
        message VARCHAR(200)
        )";
  

    $result = mysqli_query($conn, $sql);
    

    // Initialize an empty array to hold the messages
    

    mysqli_close($conn);  // Close the database connection

    // Clear output buffer before sending JSON
    ob_clean();

    // Output JSON data
    // echo json_encode($messages);
} else {
    // Invalid request method
    ob_clean();
    echo json_encode(['error' => 'Invalid request method']);
}
?>
