<?php
// этот скрипт проводит подключение к БД в лучае
// обращния к файлу через AJAX
// проверка на обращение проходит во внешнем файле

session_start();

include('../class/sql.php');
$sql = new Sql('root', 'qwe', 'dynamic_links', 'localhost');

if(isset($_SESSION["login"]) and isset($_SESSION["pass"])){

    $login = htmlspecialchars($_SESSION["login"], ENT_QUOTES);
    $pass = htmlspecialchars($_SESSION["pass"], ENT_QUOTES);

    $query = $sql->query(
      "SELECT * FROM users WHERE login = '".$login."' AND pass = '".$pass."' "
    );

    if($sql->num($query) != 1){
        unset($_SESSION["login"]);
        unset($_SESSION["pass"]);
        return;
    }

    $row = $sql->row($query);

}else{
    return;
}

?>
