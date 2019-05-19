<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('inc/settings.php');


if(isset($_POST["login"]) and isset($_POST["pass"])){

    $login = htmlspecialchars($_POST["login"], ENT_QUOTES);
    $pass = md5(md5(htmlspecialchars($_POST["pass"], ENT_QUOTES)).'sQpwE');

    $query = $sql->query(
      "SELECT * FROM users WHERE login = '$login' AND pass = '$pass' "
    );

    if($sql->num($query) == 1){
        $_SESSION["login"] = $login;
        $_SESSION["pass"] = $pass;
    }
}


if(isset($_SESSION["login"]) and isset($_SESSION["pass"])){

    $login = htmlspecialchars($_SESSION["login"], ENT_QUOTES);
    $pass = htmlspecialchars($_SESSION["pass"], ENT_QUOTES);

    $query = $sql->query(
      "SELECT * FROM users WHERE login = '$login' AND pass = '$pass' "
    );

    if($sql->num($query) == 1){

        $_SESSION["login"] = $login;
        $_SESSION["pass"] = $pass;

        echo '<meta http-equiv="refresh" content="0; url=mylinks">';
        return;
    }
}

?>

<!doctype html>

<html>
<head>
    <title>Вход</title>
    <meta http-equiv = "Content-Type" content = "text-html; charset=utf-8">
	  <meta name = "viewport" content = "width=device-width, user-scalable=no">
    <link href = "/inc/index_form.css" rel = "stylesheet">
</head>

<body>

    <form id = "form_auth" action = "auth" method = "POST">
        <div class = "form_title">Авторизация</div>
        <div class = "form_item">
            <label for = "f_login">Логин</label><br>
            <input name = "login" id = "f_login" type = "text" placeholder = "Введите логин"><br>
        </div>
        <div class = "form_item">
            <label for = "f_pass">Пароль</label><br>
            <input name = "pass" id = "f_pass" type = "password" placeholder = "Введите пароль"><br>
        </div>
        <button type = "submit">Войти</button>
    </form>

</body>
</html>
