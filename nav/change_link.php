<?php

if(!$sql){
    include('../inc/link_db.php');
}


$link = htmlspecialchars($_REQUEST["link"]);

$q_links = $sql->query(
  "SELECT * FROM links WHERE id_user = ".$row['id']." AND url = '".$link."' "
);

if($sql->num($q_links) == 0){
    


    echo <<<METKA
<form id = "form_change_link" action = "/change_link" method = "GET">

    <label for = "form_change_link_inp">Введите часть ссылки, сгенерируемую программой</label><br>
    <input name = "link" id = "form_change_link_inp" placeholder = "Например: Kk"><br>

    <a href onclick = "this.setAttribute('href', 'change_link?link='+document.querySelector('#form_change_link_inp').value); nav.go(this, event)"><input type = "submit" id = "form_change_link_but" value = "Найти"></a>

</form>
METKA;

}else{

    $links = $sql->row($q_links);

    echo '
<form id = "form_change_link" action = "/inc/change_link_inc.php" method = "GET">

    <label for = "form_add_link_inp">Введите ссылку (с http(s):// или без)</label><br>
    <p>Изменение ссылки <b>'.$links["url"].'</b></p>
    <input name = "content" id = "form_add_link_inp" placeholder = "Ссылающийся документ" value = "'.$links["content"].'">
    <input name = "link" type = "hidden" value = "'.$links["url"].'"><br>

    <a href = "" onclick = "return nav.change_link(event)"><input type = "submit" id = "form_change_link_but" value = "Изменить ссылку" ></a>

</form>
';
}
