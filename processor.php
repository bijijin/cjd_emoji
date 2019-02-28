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
		if(!$this->inContext) {
            $this->beginContext();
            load()->func('communication'); 
            $response = ihttp_get('http://api.jiongxiao.com/game.php?random=1');
            $reply = json_decode($response['content'],true);
            $_SESSION['idiom'] = $reply['idiom'];
            $reply = $_SESSION['emoji'] = $reply['emoji'];
        } else {
            $context = tarnsferWords($this->message['content']);
            if ($context == '不玩了' || $context == 'done' || $context == 'stop' || $context == '退出') {
                unset($_SESSION['idiom']);
                $reply = '您成功退出看表情猜成语';
                $this->endContext();
            }else if(isset($_SESSION['idiom'])){
                if($context == $_SESSION['idiom']){
                  $reply = '恭喜您！答案是：'.$_SESSION['idiom'];
                }
            }else if($context == '答案' || $context == 'answer'){
                $reply = $_SESSION['emoji'] . ':' . $_SESSION['idiom'];
            }else{
              $reply = '答案不对哦！继续猜';
            }
        }
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