<form id = "form_add_link" action = "/inc/add_link_inc.php" method = "GET">

    <label for = "form_add_link_inp">Введите ссылку (с http(s):// или без)</label><br>
    <p>Ссылка сгенерируется автоматически</p>
    <input name = "link" id = "form_add_link_inp" placeholder = "Ссылающийся документ"><br>
    
    <a href = "" onclick = "return nav.add_link(event)"><input type = "submit" id = "form_add_link_but" value = "Добавить ссылку"></a>

</form>
