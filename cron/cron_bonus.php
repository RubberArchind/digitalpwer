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

$sql = "SELECT * FROM cashback WHERE day_left!=0";

$result = $conn->query($sql);

// Check if the query was successful
if ($result !== false && $result->num_rows > 0) {
    // Create an array to store the rows
    $rows = array();

    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        $dayleft = $row['day_left'];
        if ($dayleft > 0) {
            //update balance_bonus user   
            // $m = new \Moment\Moment($row['start_date'], 'CET');
            // $c = $m->cloning()->addDays($dayleft);

            // $sql2 = sprintf("UPDATE user SET balance_cashback = balance_cashback + %f WHERE user_id='%s'", $row['amount'] * 0.1, $row['user_id']);
            // $conn->query($sql2);
            // $sql3 = sprintf("INSERT INTO logs(user_id, type, amount) VALUES ('%s', '%s', %f)", $row['user_id'], 'CASHBACK', $row['amount'] * 0.1);
            // $conn->query($sql3);
            $sql5 = sprintf("UPDATE cashback SET day_left = day_left-1 WHERE id = '%s'", $row['id']);
            $conn->query($sql5);
            $sql3 = sprintf("INSERT INTO logs(user_id, type, amount, ref) VALUES ('%s', '%s', %f, '%s')", $row['user_id'], 'CASHBACK', $row['amount'] * 0.01, $row['id']);
            $conn->query($sql3);
            $sql4 = sprintf("INSERT INTO transaction(id, user_id, target_id, method, type, amount) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", "".rand(), $row['user_id'], $row['user_id'], 'Internal', 'CASHBACK', $row['amount'] * 0.01);
            $conn->query($sql4);
            $sql2 = sprintf("UPDATE user SET balance_cashback = balance_cashback + %f WHERE user_id='%s'", $row['amount']*0.01, $row['user_id']);
            $conn->query($sql2);
            $sql6 = sprintf("UPDATE user SET balance_deposit = balance_deposit - %f WHERE user_id='%s'", $row['amount']*0.01, $row['user_id']);
            $conn->query($sql6);

            $rows[] = array("day" => $dayleft, "user" => $row['user_id']);
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
