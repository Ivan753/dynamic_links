<?include('settings.php');

/*
 * Ссылки хранятся в хеш-таблице с открытой адресацией
 * поэтому сначала проверяем, нет ли свободного индекса
 * если нет, то создаём новый
 * Делается для минимизации длины
 * Операция является неприоритетной, поэтому при возникновении непридвиденной ошибки
 * переходим к созданию ключ
 *
 * Данную возможность можно отключить, удалив код под комементрием !
 * или изменив код в файле delete_link.php
*/



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


if(!isset($_REQUEST["link"]) OR $_REQUEST["link"] == NULL){
    ferror('error: empty');
    return;
}

$link = htmlspecialchars($_REQUEST["link"], ENT_QUOTES);

if(!preg_match("/^http(s){0,1}[^\s]/", $link)){
    $link = "http://".$link;
}


// ! проверяем хеш-таблицу на свободный ключи
$_free = $sql->query("SELECT * FROM links WHERE flag = 0 ORDER BY id LIMIT 0,1");
if($sql->num($_free) == 1){
    $free_r = $sql->row($_free);
    $update = $sql->query("UPDATE links SET id_user = '".$row['id']."', content = '".$link."', type = 1  WHERE id = ".$free_r['id']."");
    if($update){
        suc('success');
        return;
    }
}
// !- конец разрешения коллизий переполнения



// вставляем с врменным хешем, выбираем по нему и id, изменяем хеш на представление
// id в 62ричной системе и случайной заглавное буквы вначале

$hash = fake_hash(10);

$insert = $sql->query("INSERT INTO links (id_user, url, content, type) VALUE ('".$row['id']."', '".$hash."', '".$link."', 1)");

if($insert){
    
    $select = $sql->query("SELECT * FROM links WHERE id_user = '".$row['id']."' AND url = '".$hash."' ");
    
    if($sql->num($select) > 0){
        
        $s_r = $sql->row($select);
        
        $url = d2shd($s_r['id']);
        
        $update = $sql->query("UPDATE links SET url = '".$url."' WHERE id = ".$s_r['id']."");
        
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
    
}else{
    ferror('error: insert');
    return;
}

?>