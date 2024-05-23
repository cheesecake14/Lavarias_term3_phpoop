<?php
require_once('classes/database.php');

if (isset($_POST['email'])) {
    $username = $_POST['email'];
    $con = new database();

    $query = $con->opencon()->prepare("SELECT email FROM users WHERE email = ?");
    $query->execute([$username]);
    $existingUser = $query->fetch();

    if ($existingUser) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
}