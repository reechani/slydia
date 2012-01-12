<?php

/**
 * Description of CCtrl4Login
 *
 * @author Jane
 */
class CCtrl4Login implements IController {

	public function Index() {
		global $ly;
		session_destroy();
		$_SESSION = array();
		// some sort of login interface
		$html = <<<EOD
<fieldset>
	<legend>Login</legend>
	<form action="{$ly->cfg["baseurl"]}/admin/login" method="post">
		<label>Username</label><br/><input type="text" name="user" /><br/>
		<label>Password</label><br/><input type="password" name="password" /><br/>
		<input type="submit" value="Login" />
	</form>
</fieldset>
EOD;

		$ly->template->regions->main = $html;
	}

	public function Login() {
		global $ly;
		// post info and session start
		$html = "";
		// Take care of _GET/_POST variables. Store them in a variable (if they are set).
		$user = isset($_POST['user']) ? $_POST['user'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		// get user table from db
		$res = $ly->db->select(TBL_PREFIX . "Users", "username = '$user'");
		if ($res->num_rows > 0) {
			$row = $res->fetch_object();
			// is there a user with that name/pass
			if ($row->pass == sha1("{$row->username}$password")) {
				$html .= "<div class='success'>Login successful. Welcome $user.</div>";
			}
			// start session
			// save sessions vars
			$_SESSION["id"] = $row->id;
			$_SESSION["user"] = $row->username;
		} else {
			// else login failed
			$html .= "<div class='error'>User not found</div>";
		}

		$ly->template->regions->main = $html;
	}

}
