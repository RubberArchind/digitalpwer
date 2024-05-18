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

$sql = "SELECT * FROM logs WHERE type='CASHBACK'";

$result = $conn->query($sql);

// Check if the query was successful
if ($result !== false && $result->num_rows > 0) {
    // Create an array to store the rows
    $rows = array();

    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        // $dayleft = $row['day_left'];
        //update balance_bonus user   
        // $m = new \Moment\Moment($row['start_date'], 'CET');
        // $c = $m->cloning()->addDays($dayleft);
        $sql2 = sprintf("UPDATE user SET balance_bonus = balance_bonus - %f WHERE user_id='%s'", $row['amount'], $row['user_id']);
        $conn->query($sql2);
        // $sql3 = sprintf("INSERT INTO logs(user_id, type, amount) VALUES ('%s', '%s', %f)", $row['user_id'], 'CASHBACK', $row['amount'] * 0.1);
        // $conn->query($sql3);
        // $sql4 = sprintf("UPDATE cashback SET day_left = day_left-1 WHERE id = '%s'", $row['id']);
        // $conn->query($sql4);
        $rows[] = array("user" => $row['user_id'], "amount" => $row['amount']);
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
