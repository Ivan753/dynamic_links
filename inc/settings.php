<?php
include($_SERVER["DOCUMENT_ROOT"].'/class/sql.php');

$sql = new Sql('root', 'qwe', 'dynamic_links', 'localhost');

session_start();


if(isset($_GET["logout"])){
    
    unset($_SESSION["login"]);
    unset($_SESSION["pass"]);
    
    echo '<meta http-equiv="refresh" content="0; url=/auth">';
}


function ferror($text){
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        header("Location: /mylinks?error=".$text);
    }else{
        echo $text;
    }
    
}

function suc($text){
 
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        header("Location: /mylinks?res=".$text);
    }else{
        echo $text;
    } 
    
}

?>
