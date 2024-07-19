<?php
session_start();
require("config.php");
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $delete_query = "DELETE FROM Products WHERE product_id = ?";
        $statement = $conn->prepare($delete_query);
        $statement->bind_param('i', $id);
        $statement->execute();
        if ($statement->affected_rows > 0)
            echo "Music deleted successfully";
    } else {
        echo "Error";
    }
} else {
    header("Location: ../html/logIn.html");
    exit();
}
$statement->close();
$conn->close();
?>
