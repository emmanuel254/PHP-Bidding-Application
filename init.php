<?php
$db = mysqli_connect('127.0.0.1', 'root', '', 'bidding system');

if (mysqli_connect_errno()) {
	echo 'Database connection failed with the following errors'.mysql_connect_errno();
	die();
}
include 'helpers/helpers.php';
session_start();

define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/bidding-system/');

if (isset($_SESSION['SBUser'])) {
	$user_id = $_SESSION['SBUser'];
	$query = $db->query("SELECT * FROM account WHERE account_id = '$user_id'");
	$user_data = mysqli_fetch_assoc($query);
}

function display_errors($errors){
	$display = '<ul style = "background-color: #f2dede;">';
	foreach ($errors as $error) {
	 $display .='<ul class= "text-danger" style="font-size: 15px;">'.$error.'</ul>';
	}

	$display .= '</ul>';
	return $display;
}


