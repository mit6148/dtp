<?php
	//event functions library

	function signup($db, $user_sub, $event_id) {
		if (!is_signed_up($db, $user_sub, $event_id)){
			$stmt = $db->prepare("INSERT INTO signups (user_sub, event_id) VALUES (?, ?)");
			$stmt->execute(array(
				$user_sub,
				$event_id
			));
		}
	}
	function cancel_signup($db, $user_sub, $event_id) {
		$stmt = $db->prepare("DELETE FROM signups WHERE user_sub = ? AND event_id = ?");
		$stmt->execute(array(
			$user_sub,
			$event_id
		));
	}
	function get_eventinfo($db, $event_id) {
		$stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
		$stmt->execute(array(
			$event_id
		));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	function is_signed_up($db, $user_sub, $event_id) {
		$stmt = $db->prepare("SELECT EXISTS(SELECT * FROM signups WHERE user_sub = ? AND event_id = ?)");
		$stmt->execute(array(
			$user_sub,
			$event_id
		));
		return $stmt->fetch(PDO::FETCH_NUM)[0];
	}
	function get_signedup_events($db, $user_sub) {
		$stmt = $db->prepare("SELECT event_id FROM signups WHERE user_sub = ?");
		$stmt->execute(array(
			$user_sub
		));
		$results = [];
		$event_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($event_ids as $event_id) {
			$results[] = get_eventinfo($db, $event_id[0]);
		}
		return $results;
	}
	function event_cleanup($db) {
		$stmt = $db->query("SELECT id FROM events WHERE end_time < UNIX_TIMESTAMP()");
		$rows = $stmt->fetchAll(PDO::FETCH_NUM);
		foreach ($rows as $row) {
			$signup_del_stmt = $db->prepare("DELETE FROM signups WHERE event_id = ?");
			$signup_del_stmt->execute($row);
			$event_del_stmt = $db->prepare("DELETE FROM events WHERE id = ?");
			$event_del_stmt->execute($row);
		}
	}
	function num_attending_event($db, $event_id) {
		$stmt = $db->prepare("SELECT COUNT(*) FROM `signups` WHERE event_id = ?");
		$stmt->execute(array(
			$event_id
		));
		return $stmt->fetch(PDO::FETCH_NUM)[0];
	}
	function append_events_details($db, $results, $requester_sub = false) {
		foreach ($results as $key => $row){
			$owner_userinfo = get_userinfo($db, $row["owner_sub"]);
			$results[$key]["owner_name"] = $owner_userinfo["name"];
			$results[$key]["owner_email"] = $owner_userinfo["email"];
			if ($requester_sub){
				$results[$key]["is_signed_up"] = (is_signed_up($db, $requester_sub, $row["id"]));
			}
			$results[$key]["num_attending_event"] = num_attending_event($db, $row["id"]);
		}
		return $results;
	}
	function delete_event($db, $event_id, $sub) {
		$stmt = $db->prepare("DELETE FROM events WHERE owner_sub = ? AND id = ?");
		$stmt->execute(array(
			$sub,
			$event_id
		));
		$delete_signups_stmt = $db->prepare("DELETE FROM signups WHERE event_id = ?");
		$delete_signups_stmt->execute(array(
			$event_id
		));
		$delete_invitations_stmt = $db->prepare("DELETE FROM invitations WHERE event_id = ?");
		$delete_invitations_stmt->execute(array(
			$event_id
		));
	}
	function get_invitations($db, $email) {
		$stmt = $db->prepare("SELECT * FROM invitations WHERE email = ? AND dismissed = 0");
		$stmt->execute(array(
			$email
		));
		$invitations = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($invitations as $i => $invitation) {
			$invitations[$i]["event"] = get_eventinfo($db, $invitation["event_id"]);
			$inviter_info = get_userinfo($db, $invitation["inviter_sub"]);
			$invitations[$i]["inviter"]["given_name"] = $inviter_info["given_name"];
			$invitations[$i]["inviter"]["name"] = $inviter_info["name"];
			$owner_userinfo = get_userinfo($db, $invitations[$i]["event"]["owner_sub"]);
                        $invitations[$i]["event"]["owner_name"] = $owner_userinfo["name"];
                        $invitations[$i]["event"]["owner_email"] = $owner_userinfo["email"];
		}
		return $invitations;
	}
	function dismiss_invitation($db, $event_id, $sub) {
		$get_kerb_stmt = $db->prepare("SELECT email FROM users WHERE sub = ?");
		$get_kerb_stmt->execute(array(
			$sub
		));
		$email = $get_kerb_stmt->fetch(PDO::FETCH_ASSOC)["email"];
		$stmt = $db->prepare("UPDATE invitations SET dismissed = 1 WHERE email = ? AND event_id = ?");
		$stmt->execute(array(
			$email,
			$event_id
		));
	}
?>
