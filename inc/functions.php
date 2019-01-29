<?
function fake_hash($len){
	
	$characters =  'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
	$charactersLength = strlen($characters); 
	$randomString =  '' ; 

	for($i =  0; $i < $len; $i++){ 
		$randomString .= $characters[rand(0, $charactersLength-1)]; 
	}
 
	$p1 = time();
	$p2 = $randomString; 

	return $p1.'_'.$p2;
}




function d2shd($dec){
    
    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $big_letter = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    
    // первая - прописная (для mod_rewrite)
    $chars = $big_letter[rand(0, strlen($big_letter)-1)];
    
    while($dec > 0){
        
        $chars .= $alphabet{($dec%strlen($alphabet)) - 1};
        
        $dec = floor($dec / strlen($alphabet));
        
    }
    
    return $chars;
    
}

?>