<?header('Content-type: text/html; charset=utf-8');

include($_SERVER["DOCUMENT_ROOT"].'/class/sql.php');

$sql = new Sql('root', '', 'dynamic_links', 'localhost');

mysql_query("SET NAMES utf8");
mysql_query("SET CHARSET utf8");

/*
$sql->query("CREATE TABLE category (
    id integer not null primary key,
    parent_category_id integer references category(id),
    name varchar(100) not null
)");
*/
//$sql->query("SELECT * FROM category");
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