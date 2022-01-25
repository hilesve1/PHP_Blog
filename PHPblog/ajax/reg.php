<?php
$username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
$email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
$login = trim(filter_var($_POST['login'], FILTER_SANITIZE_STRING));
$pass = trim(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));

$error = '';

if(strlen($username) <= 3)
    $error = 'Введите имя';
else if(strlen($email) <= 3)
    $error = 'Введите email'; 
else if(strlen($login) <= 3)
    $error = 'Введите login';  
else if(strlen($pass) <= 3)
    $error = 'Введите password';   
 
if($error != ''){
    echo $error;
    exit();
}    


$hash = "egvgetrlnfewklnlcdw";
$password = md5($pass . $hash);


require_once '../mysql_connect.php';

//$sql = 'SELECT count(*) AS count FROM `users` WHERE `login` = :login';
$sql = 'SELECT `id` FROM `users` WHERE `login` = :login || `email` = :email ';
$query = $pdo->prepare($sql);
$query->execute(['login' => $login, 'email' => $email]); 

$user = $query->fetch(PDO::FETCH_OBJ);
if($user->id== 0){
    $sql = 'INSERT INTO users(name, email, login, pass) VALUES(?, ?, ?, ?)';
    $query = $pdo->prepare($sql);
    $query->execute([$username, $email, $login, $pass]); 
    echo 'OK';
} else {
    echo 'Пользователь с таким логином или почтой уже есть на сайте';
}




  
    
