<?

if(!$sql){
    include('../inc/link_db.php');
}

?>

<section>
    <div class = "header" style = "margin-left: 5%; margin-top: 20px">Имя пользователя</div>
    <div class = "settings_name"><?echo $row["first_name"].' '.$row["last_name"]?></div>
</section>
<section>
    <form id = "change_pass" action = "/inc/change_pass_inc.php" method = "GET">
        <div class = "header">Смена пароля</div>
        <label for = "change_pass_o_p">Введите Ваш текущий пароль</label><br>
        <input name = "old_pass" type = "password" id = "change_pass_o_p" placeholder = "Текущий пароль"><br>
        <label for = "change_pass_p">Новый пароль</label><br>
        <input name = "pass" type = "password" id = "change_pass_p" placeholder = "Новый пароль"><br>
        <label for = "change_pass_p_r">Повторите новый пароль</label><br>
        <input name = "pass_repeat" type = "password" id = "change_pass_p_r" placeholder = "Повторите новый пароль"><br>
        <a href = "" onclick = "return nav.change_pass(event)"><input type = "submit" id = "change_pass_butt" value = "Сменить пароль"></a>
    </form>
</section>
<section>
    <a href = "/inc/settings.php?logout"><div id = "logout">Выйти</div></a>
</section>