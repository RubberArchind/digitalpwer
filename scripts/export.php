<?php

require __DIR__ . '/../vendor/autoload.php';

$servername = "127.0.0.1";
$username = "digy5127_digitalpweradmin";
$password = "djtLu[e8&%t^";
$database = "digy5127_digitalpwer";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user";

$result = $conn->query($sql);

// Check if the query was successful
if ($result !== false && $result->num_rows > 0) {
    // Create an array to store the rows
    $rows = array();

    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        $sql2 = sprintf("SELECT SUM(amount) FROM logs WHERE type='BONUS' AND user_id='%s'", $row['user_id']);
        $result2 = $conn->query($sql2);
        if ($result2 !== false && $result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $sql3 = sprintf("SELECT SUM(amount) FROM logs WHERE type='CASHBACK' AND user_id='%s'", $row['user_id']);
            $result3 = $conn->query($sql3);
            $row3 = $result3->fetch_assoc();
            $rows[] = array("name"=>$row['full_name'], "bonus"=>$row2['SUM(amount)'], "cashback"=>$row3['SUM(amount)']);
        }        
    }

    header('Content-Type: application/json');
    // Encode the array to JSON format
    $json_result = json_encode(($rows));

    // Print the JSON result
    echo $json_result;
} else {
    echo "Error executing query: " . $conn->error;
}


// Close connection
$conn->close();
