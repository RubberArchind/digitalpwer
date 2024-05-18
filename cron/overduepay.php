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

$sql = "SELECT * FROM cashback";

$result = $conn->query($sql);

// Check if the query was successful
if ($result !== false && $result->num_rows > 0) {
    // Create an array to store the rows
    $rows = array();

    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        $dayleft = $row['overdue'];
        while ($dayleft != 0) {
            //update balance_bonus user   
            $m = new \Moment\Moment($row['start_date'], 'CET');
            $c = $m->cloning()->addDays($dayleft);

            $divider = 0.01;
            $sql3 = sprintf("INSERT INTO logs(user_id, type, amount, time, ref) VALUES ('%s', '%s', %f, '%s', '%s')", $row['user_id'], 'CASHBACK', $row['amount'] * $divider, $c->format("Y-m-d H:i:s"), $row['id']);
            $conn->query($sql3);
            $sql4 = sprintf("INSERT INTO transaction(id, user_id, target_id, method, type, amount, time) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", "" . rand(), $row['user_id'], $row['user_id'], 'Internal', 'CASHBACK', $row['amount'] * $divider, $c->format("Y-m-d H:i:s"));
            $conn->query($sql4);
            $sql2 = sprintf("UPDATE user SET balance_cashback = balance_cashback + %f WHERE user_id='%s'", $row['amount'] * $divider, $row['user_id']);
            $conn->query($sql2);

            $rows[] = array("day" => $dayleft, "date" => $c->format("Y-m-d H:i:s"));
            $dayleft--;
        }
        $sql4 = sprintf("UPDATE cashback SET overdue = 0 WHERE id = '%s'", $row['id']);
        $conn->query($sql4);
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
