<?php

function login($user_id){
	$_SESSION['SBUser'] = $user_id;

	global $db; //accessing the database here
    $date = date("Y-m-d H:i:s"); 
    //$db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");

    $_SESSION['success_flash'] = 'You are now logged in!';
    header('Location: index.php');
}

function is_logged_in(){
 if (isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0) {
 	return true;
 }
 return false;
}

function permission_error_redirect($url = 'login.php'){
	$_SESSION['error_flash'] = 'You do not have permission to access that page';
	header('Location:'.$url);
}

