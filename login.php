<?php
	include("oidc.php");
	/*if($_COOKIE["state"]==$_GET["state"]){
		echo "state variables match. ok<br>";
		echo "auth code: ".$_GET["code"];
	}else{
		echo "state variables do not match";
	}*/
	$session=unserialize($_COOKIE["session"]);
	$post=array(
		"grant_type"=>"authorization_code",
		"code"=>$_GET["code"],
		"redirect_uri"=>"https://jungj.scripts.mit.edu:444/dtp/login.php"
	);
	if($session["state"]==$_GET["state"]){
		echo "<p>Step 1 OK</p>";
		$curl_req=curl_init("https://oidc.mit.edu/token");
		curl_setopt($curl_req,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl_req,CURLOPT_USERPWD,CLIENT_ID.":".CLIENT_SECRET);
		curl_setopt($curl_req,CURLOPT_POSTFIELDS,http_build_query($post));
		$response=curl_exec($curl_req);
		$response=json_decode($response,true);
		curl_close($curl_req);
		echo "<p>Step 2 OK</p>";
		if(isset($response["id_token"])){
			$id_token=explode(".",$response["id_token"]);
			$id_token_body=json_decode(base64_decode($id_token[1]),true);
			if($session["nonce"]==$id_token_body["nonce"]){
				$curl_req=curl_init("https://oidc.mit.edu/userinfo");
				curl_setopt($curl_req,CURLOPT_RETURNTRANSFER,true);
				//echo $response["access_token"];
				curl_setopt($curl_req,CURLOPT_HTTPHEADER,array("Authorization: Bearer ".$response["access_token"]));
				$userinfo=curl_exec($curl_req);
				$userinfo=json_decode($userinfo,true);
				curl_close($curl_req);
				echo "User info:<br>";
				var_dump($userinfo);
			}else{
				echo "<p>nonce variables do not match</p>";
			}
		}else{
			print "<p>No ID token</p>";
		}
	}else{
		echo "state variables do not match";
	}

?>
