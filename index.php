<?php
define("HOST", 'localhost');
define("DATABASE", "classicmodels");
define("CHARSET", "utf8");
define("USER", "root");
define("PASSWORD", '');

function saveLogs($pdo)
{
    $idAction = 1;
    $dateTime = date("Y-m-d H:i:s");

    $params = array($idAction, $dateTime);
    $result = $pdo->prepare("INSERT INTO logs (idAction, DateTime) VALUES(?,?)");
    $result->execute($params);
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $salt = randString();
    $sql = "INSERT INTO users (salt, email, password, customerId) VALUES (?, ?, ?, ?)";
    $params = array($salt, $_POST['email'], $_POST['password'], $_POST['customerId']);

    $pdo = new PDO('mysql:host=' . HOST . ";dbname=" . DATABASE . ';charset=' . CHARSET, USER, PASSWORD);

    $result = $pdo->prepare($sql);
    $result->execute($params);
    $userId = $pdo->lastInsertId();
    saveLogs($pdo);
} else {
    // include 'addUser.html';
}

function randString($length = 32)
{
    $characters = '0123456789abcdefABCDEF';
    $randStr = "";
    for ($i = 0; $i < $length; $i++) {
        $randStr .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randStr;
}