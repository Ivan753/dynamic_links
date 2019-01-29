<?

if(!$sql){
    include('../inc/link_db.php');
}

$q_links = $sql->query("SELECT * FROM links WHERE id_user = ".$row['id']." ORDER BY date_add DESC ");
$links = $sql->row($q_links);

if($links){
    
    echo '<div class = "links_table">';
    
    do{
       
        echo '
    <div class = "links_table_item" id = "links_table_item_'.$links["id"].'">
        <div class = "links_table_item_links">
            <div class = "links_table_item_url b-r"><a href = "'.$links["content"].'" target = "_blank">'.$links["content"].'</a></div>
            <div class = "links_table_item_url"><a href = "'.$links["url"].'"target = "_blank">'.$_SERVER["SERVER_NAME"].'/'.$links["url"].'</a></div>
        </div>
        <div class = "links_table_item_controll">
            <a href = "qr?link='.$links["url"].'" target = "_blank"><img src = "/img/qr-32.png" alt = "QR-code" title = "QR-code"></a>
            <a href = "statistics?link='.$links["url"].'" onclick = "return nav.go(this, event)"><img src = "/img/stat.png" alt = "Статистика" title = "Статистика"></a>
            <a href = "change_link?link='.$links["url"].'" onclick = "return nav.go(this, event)"><img src = "/img/change.png" alt = "Изменить" title = "Изменить"></a>
            <a href = "delete_link?id_link='.$links["id"].'" onclick = "return nav.delete_link('.$links["id"].', event)"><img src = "/img/del.png" alt = "Удалить" title = "Удалить"></a>
        </div>
    </div>';
        
    }while($links = $sql->row($q_links));
    
    echo '</div>';
}else{
    echo '<p style = "margin: 20px 5%; color: #565656;">Пока пусто. <a href="add_link "onclick="return nav.go(this, event)" style = "color: #34456f"><span>Добавить ссылку</span></a></p>';
}
?>