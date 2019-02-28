<?php
header('Content-type: application/json');
$json = array();
if(isset($_POST['word'])){
	$json['word'] = $_POST['word'];
}
exit(json_encode($json));