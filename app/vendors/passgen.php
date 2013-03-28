<?

function random_readable_pwd($length=10){
 
    // the wordlist from which the password
    // gets generated: (adjust them here:)
    $words = 'dog,cat,sheep,sun,sky,red,ball,happy,ice';
    $words .= 'green,blue,music,movies,radio,green,turbo';
    $words .= 'mouse,computer,paper,water,fire,storm,chicken';
    $words .= 'boot,freedom,white,nice,player,small,eyes';
    $words .= 'path,kid,box,black,flower,ping,pong,smile';
    $words .= 'coffee,colors,rainbow,plus,king,tv,ring';
	$words .= 'hat,potion,tube,bubble,beaker,molecule,laboratory';
 	$words .= 'atom,atmosphere,physics,biology,reference,flask,plotting';
	$words .= 'glassware,gas,science,research,resource,distill,cylinder';
	$words .= 'blender,will,typo,shop,apache,tips,design,prevent,relief';
	$words .= 'nullify,antitoxin,agent,hope,look,see,sound,sight,leap';
	
    // explode by ",":
    $words = explode(',', $words);
    if (count($words) == 0){ die('Wordlist is empty!'); }
 
    mt_srand((double)microtime()*1000000);
 
    // while password is smaller than $length -> add
    // words:
    $pwd = '';
    while (strlen($pwd) < $length){
        $r = mt_rand(0, count($words)-1);
        $pwd .= $words[$r];
    }
 
 
    // append a number at the end if length > 2 and
    // reduce the password size to $length
    $num = mt_rand(100, 1000);
    if ($length > 2){ 
        $pwd = substr($pwd,0,$length-strlen($num)).$num;
    } else { 
        $pwd = substr($pwd, 0, $length);
    }
 
    return $pwd;
 
}

//echo random_readable_pwd(16);

?>