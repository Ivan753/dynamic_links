<?include('settings.php');

// делаем задержку в работе
$time = $_SESSION["time"];
$_SESSION["time"] = time();
if((time() - $time) < 1){
	ferror('error: time');
	return;
}

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


include('functions.php');

$_REQUEST["link"] = trim($_REQUEST["link"]);
$_REQUEST["content"] = trim($_REQUEST["content"]);

if(!isset($_REQUEST["link"]) OR $_REQUEST["link"] == NULL OR !isset($_REQUEST["content"]) OR $_REQUEST["content"] == NULL){
    ferror('error: empty');
    return;
}

$link = htmlspecialchars($_REQUEST["link"], ENT_QUOTES);
$content = htmlspecialchars($_REQUEST["content"], ENT_QUOTES);

if(!preg_match("/^http(s){0,1}[^\s]/", $content)){
    $content = "http://".$content;
}


$select = $sql->query("SELECT * FROM links WHERE id_user = '".$row['id']."' AND url = '".$link."' ");
    
if($sql->num($select) > 0){
        
    $s_r = $sql->row($select);
  
    $update = $sql->query("UPDATE links SET content = '".$content."' WHERE id = ".$s_r['id']."");
        
    if($update){
        suc('success');
    }else{
        ferror('error: update');
        return;
    }
        
}else{
    ferror('error: select');
    return;
}

?>