<?php
// Include database configuration file
require_once('config.php');

// Start the session
session_start();

// Check if the email is set in the session
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {
    echo("Error");
    header("Location: ../html/logIn.html");
    exit(); 
}

if (isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];
} else {
    echo("Error");
}

$sql = "SELECT customer_id FROM Customers WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $customer_id = $row["customer_id"];
} else {
    echo "Error";
}

$sql = "INSERT INTO album (product_id, customer_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $product_id, $customer_id);

if ($stmt->execute()) {
    echo "Song added to album .";
} else {
    echo "Error: " . $stmt->error;
}
?>
