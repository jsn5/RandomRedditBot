<?php 
define('BOT_TOKEN', 'BOT_TOKEN_GOES_HERE');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
	
// read incoming info and grab the chatID
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chatID = $update["message"]["chat"]["id"];
$command = $update["message"]["text"];	
// compose reply
$reply =  sendMessage();

// send reply
if($command=="/get" || $command=="/get@RandomRedditBot") {
	$sendto =API_URL."sendmessage?chat_id=".$chatID."&parse_mode=HTML&text=".$reply;
}
else if($command=="/start"){
	$sendto =API_URL."sendmessage?chat_id=".$chatID."&parse_mode=HTML&text=Welcome to Funny Reddit Bot! use /get to fetch a /r/funny random post from Reddit.";
}
else if($command=="/rate"  || $command=="/rate@RandomRedditBot"){
	$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=Rate this bot https://telegram.me/storebot?start=randomredditbot";
}
else{
	$sendto =API_URL."sendmessage?chat_id=".$chatID."&text="."try /get";
}

file_get_contents($sendto);

function sendMessage(){
	$type=array('','top/','new/');
	$sub=array('funny/','StartledCats/','gifs/','gif/','CucumbersScaringCats/','bestgifsofalltime/','reactiongifs/','Memes/');
	$randtype=rand(0,2);
	$randsub=rand(0,7);
	echo $type[$randtype]." ".$sub[$randsub];
	$string_reddit = file_get_contents("http://reddit.com/r/".$sub[$randsub]."".$type[$randtype].".json?limit=20&sort=top&t=all");
	$json = json_decode($string_reddit, true);  
	$message="";
	$children = $json['data']['children'];
	$count=count($children);
	echo $count;
	$r=rand(0,$count-1);
	$url = $children[$r]['data']['url'];
	$title = $children[$r]['data']['title'];
	$permalink="http://reddit.com".$children[$r]['data']['permalink'];
	if($url=='' || !isset($url))
		$message="";
	else
		$message=" <a href='".$url."'>".$title."</a>
		<a href='".$permalink."'>Reddit link</a>";
	
    return $message;
}


	function checkJSON($chatID,$update){	
		$myFile = "log.txt";
		$updateArray = print_r($update,TRUE);
		$fh = fopen($myFile, 'a') or die("can't open file");
		fwrite($fh, $updateArray."\n\n");
		fclose($fh);
	}
checkJSON($chatID,$update);

?>