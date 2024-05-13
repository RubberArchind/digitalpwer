<?php
$servername = "localhost";
$username = "digy5127_digitalpweradmin";
$password = "djtLu[e8&%t^";
$database = "digy5127_digitalpwer";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE user
        SET balance_bonus = balance_bonus + (balance_deposit * 0.01), balance_deposit = balance_deposit - (balance_deposit * 0.01)
        WHERE signup_time > DATE_SUB(NOW(), INTERVAL 6 MONTH)";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Bonus updated successfully";
} else {
    echo "Error updating bonus: " . $conn->error;
}

// Close connection
$conn->close();
?>
