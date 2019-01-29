<?
if(!$sql){
    include('../inc/link_db.php');
}



if(isset($_REQUEST["link"])){
    $link = htmlspecialchars($_REQUEST["link"]);
}

if(!$link){
    $link = "";
}

$q_links = $sql->query("SELECT * FROM links WHERE id_user = ".$row['id']." AND url = '".$link."' ");

if($sql->num($q_links) == 0){
    echo '<p>Такой ссылки не существует либо Вам закрыт к ней доступ</p>';
    return;
}


?>

<div class = "statistic_info">
<?
    // определим активный пункт меню
    
    // характеристики периода времени
    $time_ago = 0;
    $time_interval = 1;
    $coef_per = 600;        // коэффициент для перевода секунд в нужную единицу, по умолчанию - 10 минут
    $dis_date = "H:i";      // отображение горизонтальной оси, по умолчанию - чесы:минуты
    $time_text = "сегодня"; 
    $period = 0;            // номер пункта меню
    
    if(isset($_REQUEST["period"])){
        switch($_REQUEST["period"]){
            case 'yesterday': $time_ago = 86400; $time_text= "вчера"; $period = 1; break;
            case 'month': $time_ago = 2678400; $time_interval = 32; $coef_per = 3600*24/4; $dis_date = "d.m"; $time_text= "месяц"; $period = 2; break;
            case 'half_year': $time_ago = 15811200; $time_interval = 184; $coef_per = 3600*24*31/10; $dis_date = "m.Y"; $time_text= "полгода"; $period = 3; break;
        }
    }
    
    
    
    
?>
    <section class = "s_time_interval">
        <a href = "statistics?link=<? echo $link ?>" onclick = "return nav.go(this, event)"><div class = "s_time_interval_butt <?echo ($period==0)?'s_t_i_b_active':'' ?>">Сегодня</div></a>
        <a href = "statistics?link=<? echo $link ?>&period=yesterday" onclick = "return nav.go(this, event)"><div class = "s_time_interval_butt <?echo ($period==1)?'s_t_i_b_active':'' ?>">Вчера</div></a>
        <a href = "statistics?link=<? echo $link ?>&period=month" onclick = "return nav.go(this, event)"><div class = "s_time_interval_butt <?echo ($period==2)?'s_t_i_b_active':'' ?>">Месяц</div></a>
        <a href = "statistics?link=<? echo $link ?>&period=half_year" onclick = "return nav.go(this, event)"><div class = "s_time_interval_butt <?echo ($period==3)?'s_t_i_b_active':'' ?>">Полгода</div></a>
    </section>
    
    <section class = "s_tiles">
        
        <div class = "s_tile">
        
 <? 

    // деление по 10 минут, 144 ячейки по 5px
   
    
    
    $all_today = 0;
    $new_today = 0;
    $all_visit = 0;
    
    $links = $sql->row($q_links);
    
    $date = date("d.m.Y", time()); // сегодняшний день в строке
    
    $datetime_today = strtotime($date) - $time_ago; // сегоднешний день в секундах
    $datetime_today_end = $datetime_today + 3600*24*$time_interval;
    
    $q_visits = $sql->query("SELECT * FROM visits WHERE id_link = ".$links['id']." AND date_add >= ".$datetime_today." AND date_add < ".$datetime_today_end." ORDER BY date_add");
    
    if($sql->num($q_visits) > 0){
        

        $all_today = $sql->num($q_visits);
        $visits = $sql->row($q_visits);
        
        $new = 0;
        
        $last_item = 0;     // считаем ячейки
        $per = 1;           // человек перешло
        
        $points = array();

        do{
            // Определяем, новый ли посетитель
            $q_new = $sql->query("SELECT id FROM visits WHERE id_link = ".$links['id']." AND id_visitor = ".$visits['id_visitor']." AND date_add < ".$datetime_today." ");
            
            if($sql->num($q_new) == 0){
                $new++;
            }

            $this_time = $visits["date_add"];
            
            $dif = round(($this_time - ($datetime_today + $last_item*$coef_per))/($coef_per));
            
            
            if($dif == 0){
                $per++;
                $points[$last_item] = $per;
            }else{
                $last_item += $dif;
                $per = 1;
                $points[$last_item] = $per;
            }
            
         
        }while($visits = $sql->row($q_visits));
        
        $points[$last_item] = $per;
        
        $new_today = $new;
        
    }
    
    $q_all = $sql->query("SELECT id FROM visits WHERE id_link = ".$links['id']."");
    $all_visit = $sql->num($q_all);

    
    ?>

    <svg height="145" width="576">
    
    <?
    
    $m_t = 10; // отсутп сверху
    
    $svg_height = 120 + $m_t;
    $svg_width = 576;
    
    $x1 = 0;
    $x2 = 0;
    $y1 = $svg_height;
    $y2 = $svg_height;
    
    if($points){
        $max_points = max($points);
    }else{
        $max_points = 3;
    }
    
    $hight = ($svg_height - $m_t)/$max_points;
    
    
    
    // отрисовываем вертикальную шкалу
    
    for($i = 1; $i <= $max_points; $i++){
        
        if($max_points > 9){
            $t = 0;
            for($j = 2; $j < round($max_points/9 + 1); $j++){
                if($i%$j == 0) $t = 1;
            }
            if($t) continue;
        }
        
        $x1 = 0;
        $x2 = $svg_width;
        $y = $svg_height - $hight*$i;
        echo '<line x1="'.($x1).'" y1="'.($y).'" x2 = "'.$x2.'" y2 = "'.($y).'" stroke-width="1" style="stroke:rgb(220,220,220);stroke-width:1" />';
        echo '<text x="'.($x1+1).'" y="'.($y-2).'" style = "font-size: 8px;" fill = "#ababab">'.$i.'</text>';
    }
    
    
    
    
    $grad = 4;
    
    $last_key = 0;
    
    
    if($points){
    // отрисовываем горизонтальную шкалу
    foreach($points as $key => $value){
        
        
        // отрисовываем прямые линии
        $x1 = ($last_key)*$grad;
        $x2 = ($key-1)*$grad;
        $y1 = $svg_height;
        $y2 = $svg_height;
        echo '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" style="stroke:rgb(200,100,50);stroke-width:1" />';
        $last_key = $key;
        
        
        
        // отрисовываем скачок
        $x1 = ($key-1)*$grad;
        $x2 = ($key-0.5)*$grad;
        $y1 = $svg_height;
        $y2 = $svg_height - $value*$hight;
        
        echo '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" style="stroke:rgb(200,100,50);stroke-width:1" />';
        
        $x1 = ($key-0.5)*$grad;
        $x2 = ($key)*$grad;
        $y2 = $svg_height;
        $y1 = $svg_height - $value*$hight;
        
        echo '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" style="stroke:rgb(200,100,50);stroke-width:1" />';
        
        
        
    }
    
    }
    
    // дорисовываем прямую до конца
    $x1 = ($last_key)*$grad;
    $x2 = $svg_width;
    $y1 = $svg_height;
    $y2 = $svg_height;

    echo '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" style="stroke:rgb(200,100,50);stroke-width:1" />';
    
    
    $coef = $svg_width/12;
    for($i = 0; $i < 12; $i++){
        
        $x = $i*$coef;
        $y = $svg_height+2;
        echo '<circle cx="'.($x).'" cy="'.($y-3).'" r="3" stroke-width="2" fill="#13aa89" />';
        echo '<text x="'.$x.'" y="'.($y+11).'" style = "font-size: 10px;" fill = "#898989">'.(date($dis_date, ($datetime_today + ($x/$grad)*$coef_per))).'</text>';
    }
    

    
    ?>
    
    </svg>
    <br>
    <div class = "dates"><span><?echo date("d.m H:i", $datetime_today)?></span><span><?echo date("d.m H:i", $datetime_today_end-1)?></span></div>
        <p>Все просмотры за <? echo $time_text ?>: <span><? echo $all_today ?></span></p>
        <p style = "color:#898900">Новые посетители за <? echo $time_text ?>: <span><? echo $new_today ?></span></p>
        <p style = "color:#125656">Всего посещений: <span><? echo $all_visit ?></span></p>
        </div>
    </section>

</div>
