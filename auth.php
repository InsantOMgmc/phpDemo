<?php
define("HOST", 'localhost');
define("DATABASE", "classicmodels");
define("CHARSET", "utf8");
define("USER", "root");
define("PASSWORD", '');

function saveLogs($pdo)
{
    $idAction = 2;
    $dateTime = date("Y-m-d H:i:s");

    $params = array($idAction, $dateTime);
    $result = $pdo->prepare("INSERT INTO logs (idAction, DateTime) VALUES(?,?)");
    $result->execute($params);
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $pdo = new PDO('mysql:host=' . HOST . ";dbname=" . DATABASE . ';charset=' . CHARSET, USER, PASSWORD);

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT salt FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $salt = $user['salt'];
        $hashedPassword = sha1($salt . md5($password));
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->execute([$email, $hashedPassword]);
        $authenticatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($authenticatedUser) {
            echo "Успешная авторизация";
            saveLogs($pdo);

        } else {
            echo "Неудалось авторизоватбся. Неверный пароль или почта.";
        }
    } else {
        echo "Пользователь не найден.";
    }
} else {
    "Неверный запрос";
}



?>