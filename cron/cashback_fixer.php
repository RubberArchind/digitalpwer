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

$sql = "SELECT * FROM user";

$result = $conn->query($sql);

// Check if the query was successful
if ($result !== false && $result->num_rows > 0) {
    $rows = array();

    while ($row = $result->fetch_assoc()) {
        $sql2 = sprintf("SELECT * FROM cashback WHERE user_id='%s'", $row['user_id']);
        $result2 = $conn->query($sql2);
        $base = 0;
        $cashback = 0;
        $isCorrected = false;
        while ($row2 = $result2->fetch_assoc()) {
            $base = ($row2['amount'] * 0.01);
            $cashback += (200 - $row2['day_left']) * $base;
        }
        if ($row['balance_cashback'] != $cashback) {
            $sql_update = sprintf("UPDATE user SET balance_cashback=%f WHERE  user_id='%s'", $cashback, $row['user_id']);
            $query3 = $conn->query($sql_update);
            if ($query3) {
                $isCorrected = true;
            }
        }

        $rows[] = array("user" => $row['full_name'], "cashback" => $cashback, 'user_balance' => $row['balance_cashback'], 'isCorrected' => $isCorrected);
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
