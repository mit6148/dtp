<?php
	include("oidc.php");
	/*if($_COOKIE["state"]==$_GET["state"]){
		echo "state variables match. ok<br>";
		echo "auth code: ".$_GET["code"];
	}else{
		echo "state variables do not match";
	}*/
	$post=array(
		"grant_type"=>"authorization_code",
		"code"=>$_GET["code"],
		"redirect_uri"=>"https://jungj.scripts.mit.edu:444/dtp/login.php"
	);
	if($_COOKIE["state"]==$_GET["state"]){
		$curl_req=curl_init("https://oidc.mit.edu/token");
		curl_setopt($curl_req,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl_req,CURLOPT_USERPWD,CLIENT_ID.":".CLIENT_SECRET);
		curl_setopt($curl_req,CURLOPT_POSTFIELDS,http_build_query($post));
		$response=curl_exec($curl_req);
		curl_close($curl_req);
		//var_dump($response);
	}else{
		echo "state variables do not match";
	}

?>
