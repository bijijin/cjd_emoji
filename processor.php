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
            $_SESSION['emoji'] = $reply['emoji'];
            $reply = '猜成语：'.$reply['emoji'].'。您可以回复“退出”来退出游戏，回复“提示”查看部分答案';
        } else {
            $context = tarnsferWords($this->message['content']);
            if ($context == '不玩了' || $context == 'done' || $context == 'stop' || $context == '退出') {
                unset($_SESSION['idiom']);
                unset($_SESSION['emoji']);
                $reply = '您成功退出看表情猜成语';
                $this->endContext();
            }else if(isset($_SESSION['idiom'])){
                if($context == $_SESSION['idiom']){
                  $reply = '恭喜您！答对了，答案是：'.$_SESSION['idiom'].'';
                  unset($_SESSION['idiom']);
                  unset($_SESSION['emoji']);
                  $this->endContext();
                }else if($context == '答案' || $context == 'answer'){
                  $reply = $_SESSION['emoji'] . '-' . $_SESSION['idiom'];
                  unset($_SESSION['idiom']);
                  unset($_SESSION['emoji']);
                  $this->endContext();
                }else if($context == '提示'){
                  $words = str_split($_SESSION['idiom'],3);
                  $_SESSION['tips'] = isset($_SESSION['tips']) ? ++$_SESSION['tips'] : 1;
                  $reply = $words[0].'***';
                  if(isset($_SESSION['tips']) && $_SESSION['tips'] == 2){
                    $reply = $words[0].'**'.$words[3];
                  }else if(isset($_SESSION['tips']) && $_SESSION['tips'] == 3){
                    //$reply = '亲，送你答案：'.$_SESSION['emoji'] . '-' . $_SESSION['idiom'];
                    $reply = '猜不到吧，答案是：'. $_SESSION['emoji'] . '-' . $_SESSION['idiom'];
                    unset($_SESSION['idiom']);
                    unset($_SESSION['emoji']);
                    $this->endContext();
                  }
                }else{
                   $reply = '亲，答案不对哦！继续猜';
                }
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