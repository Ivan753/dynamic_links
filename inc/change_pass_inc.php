<?php
include('settings.php');

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

    $query = $sql->query("SELECT * FROM users WHERE login = '$login'
			                   AND pass = '$pass' ");

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

$_REQUEST["pass"] = trim($_REQUEST["pass"]);
$_REQUEST["pass_repeat"] = trim($_REQUEST["pass_repeat"]);

if(!isset($_REQUEST["pass"]) OR $_REQUEST["pass"] == NULL
   OR !isset($_REQUEST["pass_repeat"]) OR $_REQUEST["pass_repeat"] == NULL
	 OR !isset($_REQUEST["old_pass"]) OR $_REQUEST["old_pass"] == NULL){
    ferror('error: empty');
    return;
}

$pass = $_REQUEST["pass"];

if(preg_match("/^[<>'\"]+/", $pass)){
    ferror('error: letters');
    return;
}

$old_pass = htmlspecialchars($_REQUEST["old_pass"], ENT_QUOTES);
$pass = htmlspecialchars($_REQUEST["pass"], ENT_QUOTES);
$pass_repeat = htmlspecialchars($_REQUEST["pass_repeat"], ENT_QUOTES);

if($pass != $pass_repeat){
    ferror('error: repeat');
    return;
}

$old_pass = md5(md5(htmlspecialchars($old_pass, ENT_QUOTES)).'sQpwE');
$pass = md5(md5(htmlspecialchars($pass, ENT_QUOTES)).'sQpwE');

if($old_pass == $_SESSION["pass"]){

    $update = $sql->query("UPDATE users SET pass = '".$pass."'
			                    WHERE id = ".$row['id']."");

    if($update){
        $_SESSION["pass"] = $pass;
        suc('success');
    }else{
        ferror('error: update');
        return;
    }

}else{
    ferror('error: pass');
    return;
}

?>
