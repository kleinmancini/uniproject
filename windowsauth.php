<?php

if(isset($_GET['manuallogin'])){
	$username = $_POST['user'];
	$password = $_POST['pass'];
	if(windowsLogin($username, $password)){
		echo "Valid Login";
	}
	else{
		echo "Invalid Login";
	}
}

function windowsLogin($username, $password){

	//SSV12: 11-05-2015: Added check to prevent user submitting NULL Password since this
	//provides "unauthenticated authentication" against LDAP and allows access to application
	if ($username == "" OR $password == "") {
		return false;
	}

	$username = $username."@bskyb.com";

	$ds = ldap_connect("172.20.220.27");
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
	$e = ldap_bind($ds, $username, $password);

	if (!$e) {
		return false;
	}
	else{
		return true;
	}

}

?>