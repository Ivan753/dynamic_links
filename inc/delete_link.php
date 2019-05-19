<?php
include('settings.php');
session_start();

// делаем задержку в работе
$time = $_SESSION["time"];
$_SESSION["time"] = time();
if((time() - $time) < 0.5){
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



if(!isset($_REQUEST["id_link"]) OR $_REQUEST["id_link"] == NULL){
    ferror('error: empty');
    return;
}

$id_link = htmlspecialchars($_REQUEST["id_link"], ENT_QUOTES);

$select = $sql->query("SELECT * FROM links WHERE id_user = ".$row['id']."
                      AND id = ".$id_link."");

if($sql->num($select) == 1){

    // подтверждение удаления
    if(!isset($_SESSION["delete_link"])
		   OR $_SESSION["delete_link"] != md5($row['id'].$id_link)){

        $_SESSION["delete_link"] = md5($row['id'].$id_link);
        suc('Нажмите второй раз, чтобы подтвердить удаление ссылки');
        return;
    }
    unset($_SESSION["delete_link"]);

    // удалим все визиты ссылки

    $delete_visit = $sql->query(
			"DELETE FROM visits WHERE id_link = ".$id_link.""
		);

    if($delete_visit){

        $delete = $sql->query(
					"UPDATE links SET flag = 0, id_user = 0, content = '0'
					WHERE id = ".$id_link.""
				);

        if($delete){
            suc('success');
        }else{
            ferror('error: del link');
            return;
        }

    }else{
        ferror('error: del rel');
        return;
    }

}else{
    ferror('error: exist');
    return;
}


?>
