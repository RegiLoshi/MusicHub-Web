<?php
require('config.php');

$name = $_POST["namePHP"];
$surname = $_POST["surnamePHP"];
$username = $_POST["usernamePHP"];
$email = $_POST["emailPHP"];
$password = $_POST["passwordPHP"];
$address = $_POST["addressPHP"];
$number = $_POST["numberPHP"];
$gender = $_POST["genderPHP"];

$salt = "3P0K4!@##@!_7!r4n3";
$password .= $salt;
$password = md5($password);

try {
    $stmt = $conn->prepare("SELECT * FROM Customers WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['username'] == $username) {
                echo "Account with this username already exists.";
                return;
            }
            if ($row['email'] == $email) {
                echo "Account with this email already exists.";
                return;
            }
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO Customers (name, surname, username, email, password, address, phone, gender) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $name, $surname, $username, $email, $password, $address, $number, $gender);
        $stmt->execute();
        echo "Account created successfully.";
    }
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>


