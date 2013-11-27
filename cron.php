<?php
/**
 *  CloudFlare-Dynamic-DNS-Updater
 *
 *
 * @author Nicholas Osborne <nicholas.osborne@gmail.com >
 * @copyright Nicholas Osborne 2013
 * @version 1.0
 */
#Change this
define('PUSHOVERKEY','PUSHOVER DEVICE KEY GOES HERE'); #Your pushoverkey
define('PUSHOVER','YES'); #Set to NO if you do not want to receive pushover notifications of changes
define('CLOUDFLAREEMAIL','EMAIL GOES HERE'); #Your CloudFlare account
define('CLOUDFLAREKEY','API KEY GOES HERE'); #Your CloudFlare API Key
define('DOMAIN','blah.com'); #Domain Eg. blah.com
define('SUBDOMAIN','ytz.blah.com'); #Subdomain Eg. hello for hello.blah.com 
define('RECORDTYPE','A'); #Record type Eg. A, CNAME, etc

#Don't change this
define('PUSHOVERAPP','ahbGTnkS54rbvFj72xCP7Vyz37RqS6');


function update_ip($email,$key,$domain,$sub,$value,$type){
	require_once('class_cloudflare.php');
	$cf = new cloudflare_api($email, $key);
	$response = $cf->rec_load_all($domain);
	foreach($response->response->recs->objs as $record){
		if($record->name == $sub.".".$domain){
			if($record->content != $value){
				$update = $cf->rec_edit($domain,$type,$record->rec_id,$sub,$value,1,0);
				return ($update->result == "success" ? true : false);
			}
		}
	}
	return false;
}

$ip = preg_replace( "/\r|\n/", "", file_get_contents('http://checkip.amazonaws.com/'));
if($ip != ""){
	if(update_ip(CLOUDFLAREEMAIL,CLOUDFLAREKEY,DOMAIN,SUBDOMAIN,$ip,RECORDTYPE) && (PUSHOVER == "YES")){
		curl_setopt_array($ch = curl_init(), array(CURLOPT_RETURNTRANSFER => true,CURLOPT_URL => "https://api.pushover.net/1/messages.json",CURLOPT_POSTFIELDS => array(
		"token" => PUSHOVERAPP,"user" => PUSHOVERKEY,"message" => "IP Updated: $ip")));
		curl_exec($ch);
		curl_close($ch);
	}
} 
?>
