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
        return;
    }

    $row = $sql->row($query);

}else{
    return;
}


if(isset($_GET["q"])){
    $q = $_GET["q"];
}else{
    $q = 1;
}

if($q == 5){
    if(isset($_GET["link"])){
        $link = $_GET["link"];
    }else{
        ferror('error: unset link');
    }
}

?>

<!doctype html>

<html>
<head>
    <title>Личный кабинет</title>
    <script src = "http://code.jquery.com/jquery-1.8.3.js"></script>
    <link href = "/inc/style.css?1" rel = "stylesheet">
    <script src = "/inc/nav.js?4"></script>
</head>
<body>

<header>
<nav>
    <ul><?php  // проводится проверка для отметки активной вкладки?>
        <a href = "mylinks" onclick = "return nav.go(this, event)" id = "a_mylinks"><li<?php echo ($_GET["q"] == 1)?(' class = active_menu'):'';?>>Мои ссылки</li></a>
        <a href = "add_link" onclick = "return nav.go(this, event)"><li<?php echo ($_GET["q"] == 2)?(' class = active_menu'):'';?>>Добавить ссылку</li></a>
        <a href = "change_link" onclick = "return nav.go(this, event)"><li<?php echo ($_GET["q"] == 3)?(' class = active_menu'):'';?>>Изменить ссылку</li></a>
        <a href = "person" onclick = "return nav.go(this, event)"><li<?php echo ($_GET["q"] == 4)?(' class = active_menu'):'';?>>Настройки</li></a>
    </ul>
</nav>
</header>

<?php
// проверка на ошибку или успешный результат
if(isset($_GET["error"]) AND $_GET["error"] != NULL){
    $error = htmlspecialchars($_GET["error"]);
    echo '<input type = "checkbox" id = "checkbox_error" class = "checkbox_error_res"><label for = "checkbox_error"><p error>'.$error.'</p></label>';
}

if(isset($_GET["res"]) AND $_GET["res"] != NULL){
    $res = htmlspecialchars($_GET["res"]);
    echo '<input type = "checkbox" id = "checkbox_res" class = "checkbox_error_res"><label for = "checkbox_res"><p res>'.$res.'</p></label>';
}
?>

<div id = "all">

<?php

switch($q){
    case 1:
        echo '<div id = "mylinks" class = "main_block" style = "display: block">'; include("nav/mylinks.php"); echo '</div>
            <div id = "add_link" class = "main_block"></div>
            <div id = "change_link" class = "main_block"></div>
            <div id = "statistics" class = "main_block"></div>
            <div id = "person" class = "main_block"></div>';
    break;

    case 2:
        echo '<div id = "mylinks" class = "main_block"></div>
            <div id = "add_link" class = "main_block" style = "display: block">'; include("nav/add_link.php"); echo '</div>
            <div id = "change_link" class = "main_block"></div>
            <div id = "statistics" class = "main_block"></div>
            <div id = "person" class = "main_block"></div>';
    break;

    case 3:
        echo '<div id = "mylinks" class = "main_block"></div>
            <div id = "add_link" class = "main_block"></div>
            <div id = "change_link" class = "main_block" style = "display: block">'; include("nav/change_link.php"); echo '</div>
            <div id = "statistics" class = "main_block"></div>
            <div id = "person" class = "main_block"></div>';
    break;

    case 4:
        echo '<div id = "mylinks" class = "main_block"></div>
            <div id = "add_link" class = "main_block"></div>
            <div id = "change_link" class = "main_block"></div>
            <div id = "statistics" class = "main_block"></div>
            <div id = "person" class = "main_block" style = "display: block">'; include("nav/person.php"); echo '</div>';
    break;

    case 5:
        echo '<div id = "mylinks" class = "main_block"></div>
            <div id = "add_link" class = "main_block"></div>
            <div id = "change_link" class = "main_block"></div>
            <div id = "statistics" class = "main_block" style = "display: block">'; include("nav/statistics.php"); echo '</div>
            <div id = "person" class = "main_block"></div>';
    break;
}
?>
</div>

</body>
</html>
