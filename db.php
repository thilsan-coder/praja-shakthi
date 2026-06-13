<?php
$conn = new mysqli("localhost", "root", "", "praja_shakthi_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>