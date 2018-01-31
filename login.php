<?php
	include("php/oidc.php");
	include("php/db.php");

	if (!isset($_COOKIE["session"])) {
		die("session cookie not set");
	}
	$session = unserialize($_COOKIE["session"]);
	$post_array = array(
		"grant_type" => "authorization_code",
		"code" => $_GET["code"],
		"redirect_uri" => LOGIN_PAGE_URL
	);
	$state = explode(".", $_GET["state"]);
	if ($state[0] == $session["state"]) {
		$ch = curl_init("https://oidc.mit.edu/token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD,CLIENT_ID . ":" . CLIENT_SECRET);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_array));
		$response = curl_exec($ch);
		$response = json_decode($response,true);
		curl_close($ch);
		if (isset($response["id_token"])) {
			$id_token = explode(".", $response["id_token"]);
			$id_token_body = json_decode(base64_decode($id_token[1]), true);
			if ($session["nonce"] == $id_token_body["nonce"]) {
				$ch = curl_init("https://oidc.mit.edu/userinfo");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$response["access_token"]));
				$userinfo = curl_exec($ch);
				$userinfo = json_decode($userinfo,true);
				$stmt = $db->prepare("SELECT EXISTS(SELECT * FROM users WHERE sub = ?)");
				$stmt->execute(array($userinfo["sub"]));
				if (isset($userinfo["preferred_username"]) && isset($userinfo["email"])) {
					if ($stmt->fetch(PDO::FETCH_NUM)[0] == 0) {
						$insert_stmt = $db->prepare("INSERT INTO users (sub, kerberos, email, name, given_name, ical_id) VALUES (?, ?, ?, ?, ?, ?)");
						$insert_stmt->execute(array(
							$userinfo["sub"],
							$userinfo["preferred_username"],
							$userinfo["email"],
							$userinfo["name"],
							$userinfo["given_name"],
							md5($userinfo["sub"] . (string) time() . (string) rand())
						));
					}
					$login_stmt = $db->prepare("INSERT INTO logins (hash, sub, expire_time) VALUES (?, ?, ?)");
					$login_pass = md5($userinfo["sub"] . (string) (time()) . (string) (rand()));
					$login_stmt->execute(array(
						password_hash($login_pass, PASSWORD_BCRYPT),
						$userinfo["sub"],
						time()+60*60*24*30
					));
					$login_id = $db->lastInsertId();
					$login = serialize(array(
						"id" => $login_id,
						"pass" => $login_pass
					));
					if (isset($state[1])){
						setcookie("login", $login, time()+60*60*24*90);
					} else {
						setcookie("login", $login);
					}
					header("Location: " . INDEX_URL);
				} else {
					//echo "Not all scopes enabled";
					echo "Please go back and enable all permissions.";
				}
			} else {
				echo "nonce does not match";
			}
		} else {
			//echo "No id_token";
			echo "Please go back and enable all permissions.";
		}
	} else {
		echo "state does not match";
	}
?>
