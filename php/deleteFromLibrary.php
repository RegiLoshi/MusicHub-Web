<?php
session_start();
require_once('config.php');

if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {
    echo("Error");
    header("Location: ../html/logIn.html");
    exit(); 
}
$song = $_POST["songId"];
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
$sql = "DELETE FROM album WHERE product_id = ? AND customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $song, $customer_id);

if ($stmt->execute()) {
    echo "Song removed from album.";
} else {
    echo "Error: " . $stmt->error;
}
?>
