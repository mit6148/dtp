<?php
	include("php/google_oidc.php");
	include("php/db.php");

	if (!(isset($_COOKIE["state"]) && isset($_COOKIE["nonce"]))) {
		echo("cookies not set");
	}
	$post_array = array(
		"grant_type" => "authorization_code",
		"code" => $_GET["code"],
		"redirect_uri" => GOOGLE_LOGIN_PAGE_URL,
		"client_id" => GOOGLE_CLIENT_ID,
		"client_secret" => GOOGLE_CLIENT_SECRET
	);
	$state = explode(".", $_GET["state"]);
	if ($state[0] == $_COOKIE["state"]) {
		$ch = curl_init("https://www.googleapis.com/oauth2/v4/token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_USERPWD,CLIENT_ID . ":" . CLIENT_SECRET);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_array));
		$response = curl_exec($ch);
		$response = json_decode($response,true);
		curl_close($ch);
		if (isset($response["id_token"])) {
			$id_token = explode(".", $response["id_token"]);
			$id_token_body = json_decode(base64_decode($id_token[1]), true);
			/*if ($_COOKIE["nonce"] == $id_token_body["nonce"]) {
				$ch = curl_init("https://oidc.mit.edu/userinfo");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$response["access_token"]));
				$userinfo = curl_exec($ch);
				$userinfo = json_decode($userinfo,true);*/
				$stmt = $db->prepare("SELECT * FROM users WHERE sub = ?");
				$stmt->bindParam(1, $id_token_body["sub"]);
				$stmt->execute();
				$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
				if (isset($id_token_body["preferred_username"]) && isset($id_token_body["email"]) && isset($id_token_body["name"])) {
					if ($stmt->rowCount() == 0) {
						$insert_stmt = $db->prepare("INSERT INTO users (sub, email, name, given_name) VALUES (?, ?, ?, ?)");
						$insert_stmt->execute(array(
							$id_token_body["sub"],
							$id_token_body["email"],
							$id_token_body["name"],
							$id_token_body["name"]
						));
					}
					$login_stmt = $db->prepare("INSERT INTO logins (uid, sub, expire_time) VALUES (?, ?, ?)");
					$login_uid = md5($userinfo["sub"] . (string) (time()) . (string) (rand()));
					$login_stmt->execute(array(
						$login_uid,
						$userinfo["sub"],
						time()+60*60*24*30
					));
					if (isset($state[1])){
						setcookie("login_uid", $login_uid, time()+60*60*24*90);
					} else {
						setcookie("login_uid", $login_uid);
					}
					header("Location: " . GOOGLE_INDEX_URL);
				} else {
					echo "Not all scopes enabled";
				}
			/*} else {
				echo "nonce does not match";
			}*/
		} else {
			echo "No id_token";
		}
	} else {
		echo "state does not match";
	}
?>
