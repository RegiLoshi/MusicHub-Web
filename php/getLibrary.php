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
    $sql = "SELECT customer_id , username FROM Customers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row["customer_id"];
        $username = $row["username"];
    } else {
        echo "Error";
    }
    $stmt = $conn->prepare("SELECT product_id FROM album WHERE customer_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $product_ids = [];
    while ($row = $result->fetch_assoc()) {
        $product_ids[] = $row['product_id'];
    }
    $stmt->close();
?>