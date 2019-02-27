<?php
/**
 * 语音回复处理类
 *
 * @author css9
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Css9_emojiModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$res = ihttp_get('http://api.jiongxiao.com/idiom.php?getIdimo=1');
		$response = json_decode($res['content'],true);
		$idiom = $response['msg'];

		//$reply = json_decode($response['content'],true);
        //$reply = $_SESSION['idiom'] = $reply['msg'];
        return $this->respText($reply);
    }
}
function getIdiom(){
	$res = ihttp_get('http://api.jiongxiao.com/idiom.php?first=1');
	$response = json_decode($res['content'],true);

}
function tarnsferWords($str){
  if(empty($str)){
    return $str;
  }
  $obj = json_decode('{"str":"'.$str.'"}',ture);
  return $obj['str'];
}