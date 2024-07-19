<?php
    #Create database constants.
    define("HOST", "localhost");
    define("USERNAME", "root");
    define("PASSWORD", "root");
    define("DATABASE", "MusicShop");

    #Create Connection
    $conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
