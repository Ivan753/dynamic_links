<?include('inc/settings.php');


if(isset($_SESSION["login"]) and isset($_SESSION["pass"])){
        
    $login = htmlspecialchars($_SESSION["login"], ENT_QUOTES);
    $pass = htmlspecialchars($_SESSION["pass"], ENT_QUOTES);
        
    $query = $sql->query("SELECT * FROM users WHERE login = '$login' AND pass = '$pass' ");
        
    if($sql->num($query) != 1){
        
        unset($_SESSION["login"]);
        unset($_SESSION["pass"]);
        
        ferror('error: access');
        return;
        
    }
    
    $row = $sql->row($query);
      
}else{
    ferror('error: singin');
    return;
}

if(isset($_REQUEST["link"]) AND $_REQUEST["link"] != NULL){
    
    $link = htmlspecialchars($_REQUEST["link"], ENT_QUOTES);
    
    $q_l = $sql->query("SELECT * FROM links WHERE id_user = ".$row['id']." AND url = '".$link."' ");
    
    if($sql->num($q_l) == 1){
        
        include('lib/phpqrcode/qrlib.php'); 
        
        QRcode::png($_SERVER["SERVER_NAME"].'/'.$link);
        
    }else{
        echo 'blya';
    }
    
}else{
    ferror('error: empty');
    return;
}
?>