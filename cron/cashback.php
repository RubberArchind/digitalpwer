<?php
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

$sql = "SELECT * FROM transaction WHERE type='DEPOSIT'";

$result = $conn->query($sql);

// Check if the query was successful
if ($result !== false && $result->num_rows > 0) {
    // Create an array to store the rows
    $rows = array();

    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        // Append each row to the array
        $datetime1 = new DateTime($row['time']);
        $datetime2 = new DateTime();
        $interval = $datetime1->diff($datetime2);
        $daysDifference = $interval->format('%a');
        $row['interval'] = $daysDifference;
        $row['bonusleft'] = 200 - $daysDifference;

        $sql2 = sprintf("INSERT INTO cashback VALUES ('%s', '%s', '%s', %f, %f, %f)", $row['id'], $row['user_id'], $row['time'], $row['amount'], $row['bonusleft'], (200 - $row['bonusleft']));
        $conn->query($sql2);
        $rows[] = $row;
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
