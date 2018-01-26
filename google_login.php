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
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_array));
		$response = curl_exec($ch);
		$response = json_decode($response,true);
		curl_close($ch);
		if (isset($response["id_token"])) {
			$id_token = explode(".", $response["id_token"]);
			$id_token_body = json_decode(base64_decode($id_token[1]), true);
				$stmt = $db->prepare("SELECT EXISTS(SELECT * FROM users WHERE sub = ?)");
				$stmt->execute(array("google." . $id_token_body["sub"]));
				if (isset($id_token_body["email"]) && isset($id_token_body["name"])) {
					if ($stmt->fetch(PDO::FETCH_NUM)[0] == 0) {
						$insert_stmt = $db->prepare("INSERT INTO users (sub, email, name, given_name, ical_id) VALUES (?, ?, ?, ?, ?)");
						$insert_stmt->execute(array(
							"google." . $id_token_body["sub"],
							$id_token_body["email"],
							$id_token_body["name"],
							$id_token_body["given_name"],
                            md5($id_token_body["sub"] . (string) time() . (string) rand())
						));
					}
					$login_stmt = $db->prepare("INSERT INTO logins (hash, sub, expire_time) VALUES (?, ?, ?)");
					$login_pass = md5($id_token_body["sub"] . (string) (time()) . (string) (rand()));
					$login_stmt->execute(array(
						password_hash($login_pass, PASSWORD_BCRYPT),
						"google." . $id_token_body["sub"],
						time()+60*60*24*30
					));
					$login_id = $db->lastInsertId();
					$login = serialize(array(
						$login_id,
						$login_pass
					));
					if (isset($state[1])){
						setcookie("login", $login, time()+60*60*24*90);
					} else {
						setcookie("login", $login);
					}
					header("Location: " . GOOGLE_INDEX_URL);
				} else {
					echo "Not all scopes enabled";
				}
		} else {
			echo "No id_token";
		}
	} else {
		echo "state does not match";
	}
?>
