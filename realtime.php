<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value1 = $_POST['key1']; // Access the values using the keys
    $value2 = $_POST['key2'];
    // Process the data (e.g., save to the database)
    // echo "Received FormData: key1 = $value1, key2 = $value2";

$server = "localhost";
$username = "root";
$password = "";
$database = $value1;
$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}

// Fetch the latest messages from the table
$sql = "SELECT * FROM  $value2";  // Adjust as necessary
$result = mysqli_query($conn, $sql);

$messages = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = [
            'action' => $row['action'],
            'message' => $row['message']
        ];
    }
}

// Return data as JSON
echo json_encode($messages);
}else{

}
?>