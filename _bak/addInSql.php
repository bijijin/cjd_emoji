<?php
require_once "db.php";
$db = new DB("cjd","root","","localhost");

header('Content-type: application/json');

if(isset($_POST['word'])){
	$db->select('cjd_idiom1',['name'=>$_POST['word']]);
	if($db->count() > 0){
		exit('此成语已经存在');
	}
	$db->insert('cjd_idiom1',array('name'=>$_POST['word'],'content'=>$_POST['content']));
	exit('插入成功');
}else{
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,'https://www.cilin.org/ajax/rand_chengyu.php?_='.time()*(rand(100,999)));
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_HEADER,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // 跳过检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 跳过检查
	$output = curl_exec($curl);
	if($output === FALSE ){
		echo "CURL Error:".curl_error($curl);
	}
	curl_close($curl);
	exit($output);
}