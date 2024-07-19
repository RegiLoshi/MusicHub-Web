<?php
require_once('config.php');

function authenticateUser($email, $password, $conn) {
    $stmt = $conn->prepare("SELECT * FROM Customers WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        return 'user';
    }
    return false;
}

function authenticateAdmin($email, $password, $conn) {
    $stmt = $conn->prepare("SELECT * FROM Admins WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        return 'admin';
    }
    return false;
}

$email = $_POST["emailPHP"];
$password = $_POST["passwordPHP"];

$salt = "3P0K4!@##@!_7!r4n3";
$passwordWithSalt = md5($password . $salt);

$userRole = authenticateUser($email, $passwordWithSalt, $conn);

if (!$userRole) {
    $userRole = authenticateAdmin($email, $passwordWithSalt, $conn);
}

if ($userRole) {
    session_start();
    $_SESSION["email"] = $email;
    echo $userRole;
} else {
    echo "not_found";
}
?>


