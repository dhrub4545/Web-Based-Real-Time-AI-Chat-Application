<?php
// ob_start();

// // Enable error reporting and log errors instead of displaying them
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// ini_set("log_errors", 1);
// ini_set("error_log", "error_log.txt");  // Log errors to a file

// header('Content-Type: application/json');  // Ensure JSON header is set early
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $value1 = $_POST['k1']; // Access the values using the keys
//     $value2 = $_POST['k2'];
//     $message = $_POST['message'];
//     $server = "localhost";
//     $username = "root";
//     $password = "";

//     // Create connections
//     $conn1 = mysqli_connect($server, $username, $password, $value1);
//     $conn2 = mysqli_connect($server, $username, $password, $value2);

//     // Check connections
//     if (!$conn1) {
//         die("Error connecting to database 1: " . mysqli_connect_error());
//     }
//     if (!$conn2) {
//         die("Error connecting to database 2: " . mysqli_connect_error());
        
        
//     }

//     // Prepare and execute SQL queries
//     $sql1 = "INSERT INTO `$value2` (`action`, `message`) VALUES ('1', '$message')";
//     $sql2 = "INSERT INTO `$value1` (`action`, `message`) VALUES ('2', '$message')";

//     if (!mysqli_query($conn1, $sql1)) {
        
//         echo "Error in database 1 query: " . mysqli_error($conn1);
//     }
//     if (!mysqli_query($conn2, $sql2)) {
        
//         // mysqli_query($conn2,$sql2);
        
//         echo "Error in database 2 query: " . mysqli_error($conn2);
//     }

//     // Close connections
//     mysqli_close($conn1);
//     mysqli_close($conn2);
//     ob_clean();
// }

ob_start();

// Enable error reporting and log errors instead of displaying them
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "error_log.txt");  // Log errors to a file

header('Content-Type: application/json');  // Set JSON header early

$response = []; // Initialize a response array to store success or error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $value1 = $_POST['k1'] ?? null;
    $value2 = $_POST['k2'] ?? null;
    $message = $_POST['message'] ?? null;

    if (!$value1 || !$value2 || !$message) {
        $response['error'] = 'Invalid input values.';
        echo json_encode($response);
        exit;
    }

    // Database credentials
    $server = "localhost";
    $username = "root";
    $password = "";

    // Create connections
    $conn1 = mysqli_connect($server, $username, $password, $value1);
    $conn2 = mysqli_connect($server, $username, $password, $value2);

    // Check connections and add errors to response if failed
    if (!$conn1) {
        $response['error'] = "Error connecting to database 1: " . mysqli_connect_error();
    }
    if (!$conn2) {
        $response['error'] = "Error connecting to database 2: " . mysqli_connect_error();
    }

    if (isset($response['error'])) {
        // If any connection failed, return the error response immediately
        ob_clean(); // Clear any previous output
        echo json_encode($response);
        exit;
    }

    // Check if the table exists in conn2
    $tableCheckQuery = "SHOW TABLES LIKE '$value1'";
    $tableCheckResult = mysqli_query($conn2, $tableCheckQuery);

    // If the table does not exist, create it
    if (mysqli_num_rows($tableCheckResult) == 0) {
        $createTableQuery = "CREATE TABLE `$value1` (
            sno INT AUTO_INCREMENT PRIMARY KEY,
            action INT,
            message VARCHAR(200)
            )";

        if (!mysqli_query($conn2, $createTableQuery)) {
            $response['error'] = "Error creating table in database 2: " . mysqli_error($conn2);
            ob_clean();
            echo json_encode($response);
            exit;
        } else {
            $response['success'][] = "Table `$value2` created successfully in database 2.";
        }
    }

    // Prepare and execute SQL queries
    $sql1 = "INSERT INTO `$value2` (`action`, `message`) VALUES ('1', '$message')";
    $sql2 = "INSERT INTO `$value1` (`action`, `message`) VALUES ('2', '$message')";

    if (!mysqli_query($conn1, $sql1)) {
        $response['error'] = "Error in database 1 query: " . mysqli_error($conn1);
    } else {
        $response['success'][] = "Message inserted successfully into database 1.";
    }

    if (!mysqli_query($conn2, $sql2)) {
        $response['error'] .= " Error in database 2 query: " . mysqli_error($conn2);
    } else {
        $response['success'][] = "Message inserted successfully into database 2.";
    }

    // Close connections
    mysqli_close($conn1);
    mysqli_close($conn2);

    // Clear output buffer and return the JSON response
    ob_clean();
    echo json_encode($response);
}


?>