<?include('inc/settings.php');

$link = htmlspecialchars($_GET["q"], ENT_QUOTES);

$q_link = $sql->query("SELECT * FROM links WHERE url = '".$link."' AND flag = 1 ");

if($sql->num($q_link) > 0){

$r_link = $sql->row($q_link);



// записываем посетителя
$create = 0;    // флаг для создания пользователя

if(isset($_COOKIE["visitor_hash"])){
    
    $cookie_hash = htmlspecialchars($_COOKIE["visitor_hash"], ENT_QUOTES);
    
    $q_visit = $sql->query("SELECT * FROM visitors WHERE hash = '".$cookie_hash."' ");
    
    if($sql->num($q_visit) > 0){
        
        // отлично, такой есть
        $r_visit = $sql->row($q_visit);
        $id_visitor = $r_visit["id"];
        
        // добавляем просмотр
        $add_visit = $sql->query("INSERT INTO visits (id_link, id_visitor, ip, date_add) VALUE ('".$r_link['id']."', '".$id_visitor."', '".$_SERVER['REMOTE_ADDR']."', ".(time()).")");
        
        $update = $sql->query("UPDATE visitors SET last_change = '".(time())."' WHERE id = ".$id_visitor."");
        
    }else{
        $create = 1;
    }
    
}else{
    $create = 1;
}


if($create == 1){
    
    // то есть нет такого
    
    include('inc/functions.php');
    
    // используем тот же механизм, что и при создании ссылки
    $hash = fake_hash(10);
    
    $time = time();

    $insert = $sql->query("INSERT INTO visitors (hash, last_change, date_add) VALUE ('".$hash."', '".($time)."', '".($time)."')");

    if($insert){
        
        $select = $sql->query("SELECT * FROM visitors WHERE hash = '".$hash."' ");
        
        if($sql->num($select) > 0){
            
            $s_r = $sql->row($select);
            $reafl_hash = d2shd($s_r['id']);
            $reafl_hash = substr($reafl_hash, 1, strlen($reafl_hash));
            $update = $sql->query("UPDATE visitors SET hash = '".$reafl_hash."' WHERE id = ".$s_r['id']."");
            setcookie("visitor_hash", $reafl_hash, time()+5*365*24*3600, "/");
            
            // добавляем просмотр
            $add_visit = $sql->query("INSERT INTO visits (id_link, id_visitor, ip, date_add) VALUE ('".$r_link['id']."', '".$s_r['id']."', '".$_SERVER['REMOTE_ADDR']."', ".(time()).")");
            
        }
    
    }
}



switch($r_link["type"]){
    
    case 1:
        header('Location: '.$r_link["content"].'');
        return;
        // echo '<meta http-equiv="refresh" content="0; url='..'">';
    break;
    
}

}
?>
<!doctype>
<html>
<head>
    <title>Ссылка недоступна</title>
</head>
<body style = "display: flex; justify-content: center">
<p>По неизвестной причине данная ссылка недоступна. Пожалуйста, обратитесь в службу поддержки</p>
</body>
</html>