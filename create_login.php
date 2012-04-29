<?php
include_once "AppNimbus.php";
$appnimbus = new AppNimbus('sulitdito', '8eeceba8aca008cb9ec5e73d2b223e777de89606');

$user = $appnimbus->_restCall('user', 'authenticate', array(
	'email' => $_POST['email'],
	'password' => $_POST['password']	
));

if( $user['success'] == false && isset($user['errorcode']) && $user['errorcode'] == '100005' ) {
	$user = $appnimbus->_restCall('user', 'create', array(
		'email' => $_POST['email'],
		'password' => $_POST['password']
	));
	session_start();
	$_SESSION['logged_in'] = true;
	$_SESSION['user'] = $user;
	$_SESSION['user_id'] = $user['data']['id'];
	$_SESSION['username'] = $user['data']['properties']['email'];
	header('Location: index.php?create=true');
} else if( $user['success'] == true && isset($user['data']['auth']) && $user['data']['auth'] == false ) {
	header('Location: index.php?login=false');
} else if( $user['success'] == true && isset($user['data']['auth']) && $user['data']['auth'] == true ) {
	session_start();
	$_SESSION['logged_in'] = true;
	$_SESSION['user'] = $user['data']['user'];
	$_SESSION['user_id'] = $user['data']['user']['id'];
	$_SESSION['username'] = $user['data']['user']['properties']['email'];
	header('Location: index.php?login=true');
}
