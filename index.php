<?include('inc/settings.php');

$login = htmlspecialchars($_POST["login"], ENT_QUOTES);
$pass = md5(md5(htmlspecialchars($_POST["pass"], ENT_QUOTES)).'sQpwE');

if($login and $pass){
    
    $query = $sql->query("SELECT * FROM users WHERE login = '$login' AND pass = '$pass' ");
    
    if($sql->num($query) == 1){
        
        $_SESSION["login"] = $login;
        $_SESSION["pass"] = $pass;
        
    }
    
}



if(isset($_SESSION["login"]) and isset($_SESSION["pass"])){
    
    $login = htmlspecialchars($_SESSION["login"], ENT_QUOTES);
    $pass = htmlspecialchars($_SESSION["pass"], ENT_QUOTES);
    
    $query = $sql->query("SELECT * FROM users WHERE login = '$login' AND pass = '$pass' ");
    
    if($sql->num($query) == 1){
        
        $_SESSION["login"] = $login;
        $_SESSION["pass"] = $pass;
        
        echo '<meta http-equiv="refresh" content="0; url=mylinks">';
        return;
    
    }
    
}








//echo md5(md5('text').'sQpwE');
?>

<!doctype html>

<head>
    <title>Вход</title>
    <meta http-equiv = "Content-Type" content = "text-html; charset=utf-8">
	<meta name = "viewport" content = "width=device-width, user-scalable=no">
</head>

<style>
*{
    margin: 0;
    padding: 0;
    font-family: Arial;
}

#form_auth{
    max-width: 300px;
    margin: 100px auto;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 2px;
    background-color: #fff;
    box-shadow: 0 0 5px #ccc;
}

.form_title{
    border-bottom: 1px solid #ddd;
    color: #898989;
    padding-bottom: 2px;
    font-size: 15px;
}

form label{
    color: #676778;
    font-size: 16px;
    font-weight: bold;
    padding-top: 10px;
}

form input{
    width: 98%;
    padding: 2px 1%;
    margin-top: 3px;
    font-size: 15px;
    border: 1px solid #ddd;
    border-radius: 2px;
}

.form_item{
    width: 100%;
    margin: 10px 0;
}

form button{
    width: 150px;
    text-align: center;
    padding: 3px;
    font-size: 15px;
    background-color: #357;
    color: #fff;
    border: 0;
    border-radius: 2px;
    cursor: pointer;
    
    transition: background-color 0.2s
}

form button:hover{
    background-color: #579;
}




</style>

<body>
    
    <form id = "form_auth" action = "auth" method = "POST">
        
        <div class = "form_title">Авторизация</div>
        
        <div class = "form_item">
            <label for = "f_login">Логин</label><br>
            <input name = "login" id = "f_login" type = "text" placeholder = "Введите логин"><br>
        </div>
        
        <div class = "form_item">
            <label for = "f_pass">Пароль</label><br>
            <input name = "pass" id = "f_pass" type = "password" placeholder = "Введите пароль"><br>
        </div>
        
        
        <button type = "submit">Войти</button>
        
    </form>
    
</body>