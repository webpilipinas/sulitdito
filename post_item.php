<?php
session_start();
include_once "AppNimbus.php";
$appnimbus = new AppNimbus('sulitdito', '8eeceba8aca008cb9ec5e73d2b223e777de89606');

$filename = $_FILES['itemphoto']['name'];
$extension = substr(strrchr($filename,'.'),1);

$new_filename = sha1(time()).'.'.$extension;
move_uploaded_file($_FILES["itemphoto"]["tmp_name"], "upload/" . $new_filename);
$photo_url = "http://{$_SERVER['HTTP_HOST']}/upload/{$new_filename}";

$item = $appnimbus->_restCall('object', 'create', array(
	'name' => 'Item',
	'properties' => array(
		'name' => $_POST['name'],
		'description' => $_POST['description'],
		'price' => $_POST['price'],
		'photo' => $photo_url
	),
	'parents' => array(
		'item_of' => $_SESSION['user_id']
	)
));
header("Location: view_item.php?id={$item['data']['id']}");