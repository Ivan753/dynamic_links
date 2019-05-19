<?php
include('inc/settings.php');

if(isset($_SESSION["login"]) and isset($_SESSION["pass"])){

    $login = htmlspecialchars($_SESSION["login"], ENT_QUOTES);
    $pass = htmlspecialchars($_SESSION["pass"], ENT_QUOTES);

    $query = $sql->query(
      "SELECT * FROM users WHERE login = '$login' AND pass = '$pass' "
    );

    if($sql->num($query) != 1){
        unset($_SESSION["login"]);
        unset($_SESSION["pass"]);
        header("HTTP/1.0 404 Not Found");
        return;
    }

    $row = $sql->row($query);

    // проверка прав
    $q_access = $sql->query("SELECT * FROM access WHERE id_user = ".$row['id']."");
    if($sql->num($q_access) > 0){
        $access = $sql->row($q_access);
        if($access["access"]&128 != 1){
            header("HTTP/1.0 404 Not Found");
            return;
        }
    }else{
        header("HTTP/1.0 404 Not Found");
        return;
    }


}else{
    header("HTTP/1.0 404 Not Found");
    return;
}



if(isset($_POST["login"]) and isset($_POST["first_name"])
   and isset($_POST["last_name"]) and isset($_POST["pass"]) and isset($_POST["rpass"])){

    $login = trim(htmlspecialchars($_POST["login"]), ENT_QUOTES);
    $first_name = trim(htmlspecialchars($_POST["first_name"]), ENT_QUOTES);
    $last_name = trim(htmlspecialchars($_POST["last_name"]), ENT_QUOTES);
    $middle_name = trim(htmlspecialchars($_POST["middle_name"]), ENT_QUOTES);
    $pass = trim(htmlspecialchars($_POST["pass"]), ENT_QUOTES);
    $rpass = trim(htmlspecialchars($_POST["rpass"]), ENT_QUOTES);

    try{
        if(!$login or !$first_name or !$last_name or !$pass or !$rpass){
            throw new Exception('Заполните все поля');
        }

        if($pass != $rpass){
            throw new Exception('Пароли не совпадают');
        }

        $pass = md5(md5($pass).'sQpwE');

        $insert = $sql->query(
          "INSERT INTO users (first_name, last_name, middle_name, login, pass)
          VALUES ('$first_name', '$last_name', '$middle_name', '$login', '$pass')"
        );

        if(!$insert){
            throw new Exception('Произошла неизвестная ошибка');
        }

    }catch(Exception $e){
      echo '<p error>'.$e->getMessage().'</p>';
    }

}

?>

<!doctype html>

<html>
<head>
    <title>Добавление пользователя</title>
    <link href = "/inc/style.css?1" rel = "stylesheet">
    <link href = "/inc/index_form.css" rel = "stylesheet">
</head>
<body>

  <form id = "form_auth" method = "POST">
      <div class = "form_title">Добавление пользователя</div>
      <div class = "form_item">
          <label for = "f_login">Логин</label><br>
          <input name = "login" id = "f_login" type = "text" placeholder = "Введите логин"><br>
      </div>
      <div class = "form_item">
          <label for = "f_first_name">Имя</label><br>
          <input name = "first_name" id = "f_first_name" type = "text" placeholder = "Имя"><br>
      </div>
      <div class = "form_item">
          <label for = "f_last_name">Фамилия</label><br>
          <input name = "last_name" id = "f_last_name" type = "text" placeholder = "Фамилия"><br>
      </div>
      <div class = "form_item">
          <label for = "f_middle_name">Отчество</label><br>
          <input name = "middle_name" id = "f_middle_name" type = "text" placeholder = "Отчество (если есть)"><br>
      </div>
      <div class = "form_item">
          <label for = "f_pass">Пароль</label><br>
          <input name = "pass" id = "f_pass" type = "password" placeholder = "Введите пароль"><br>
      </div>
      <div class = "form_item">
          <label for = "f_rpass">Повторите пароль</label><br>
          <input name = "rpass" id = "f_rpass" type = "password" placeholder = "Повторите пароль"><br>
      </div>
      <button type = "submit">Зарегистрировать</button>
  </form>

</body>
</html>
