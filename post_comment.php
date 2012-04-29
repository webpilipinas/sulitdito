<?php
session_start();
include_once "AppNimbus.php";
$appnimbus = new AppNimbus('sulitdito', '8eeceba8aca008cb9ec5e73d2b223e777de89606');

$comment = $appnimbus->_restCall('object', 'create', array(
	'name' => 'Comment',
	'properties' => array(
		'body' => $_POST['body']
	),
	'parents' => array(
		'comment_of' => $_SESSION['user_id'],
		'commented_on' => $_POST['item_id']
	)
));

header("Location: view_item.php?id={$_POST['item_id']}&comment=success");