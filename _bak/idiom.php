<?php
require_once "db.php";
$db = new DB("cjd","root","","localhost");
header('Content-type: application/json');
session_start();
$json = array();
if(isset($_GET['first'])){
	$db->select('cjd_idiom1');
	$count = $db->count();
	$ram = rand(0,$count-1);
	$db->query("SELECT `name` FROM cjd_idiom1 LIMIT ".$ram.",1");
	$json['code'] = 1;
	$json['msg'] = $_SESSION['name'] = trim($db->row(0)->name);
}else if(isset($_GET['end'])){
	unset($_SESSION['name']);
	$json['code'] = 1;
	$json['msg'] = '您已退出成语接龙';
}else{
	if(isset($_GET['t']) && isset($_SESSION['name'])){

		$sessionTitle = $_SESSION['name'];
		$sessionLen = strlen($sessionTitle);

		$sessionLastChar = substr($sessionTitle, $sessionLen-3,5);
		$title = $_GET['t'];
		$len = strlen($title);
		$firstChar = substr($title,0,3);
		$lastChar = substr($title, $len-3,3);
		var_dump($_SESSION['name']);
		echo $sessionLastChar;
		echo $firstChar;
		if($sessionLastChar == $firstChar){
			$db->select('cjd_idiom1',['name'=>$title]);
			if($db->count() > 0){
				unset($_SESSION['name']);
				$db->query("SELECT `name` FROM cjd_idiom1 WHERE `name` LIKE '".$lastChar."%'");
				$index = 0;
				if($db->count() > 0){
					if($db->count() > 1){
						$index = rand(0,$db->count()-1);
					}
					$json['code'] = 1;
					$json['msg'] =  $_SESSION['name']  = trim($db->row($index)->name);
				}else{
					$json['code'] = 0;
					$json['msg'] = '亲，您赢了';
				}
			}else{
				$json['code'] = 0;
				$json['msg'] = '亲，您确定你输入的是成语？';
			}
		}else{
			$json['code'] = 0;
			$json['msg'] = '亲，您的成语没有接上我的哦';
		}
	}else{
		$json['code'] = 0;
		$json['msg'] = '亲，您有输入吗？';
	}
}
//$json = $result;
//var_dump($json);
die(json_encode($json));