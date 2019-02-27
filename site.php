<?php
/**
 * 成语接龙模块微站定义
 *
 * @author 欧阳
 * @url
 */
defined('IN_IA') or exit('Access Denied');

session_start();

class Css9_emojiModuleSite extends WeModuleSite {
	public $settings;

	public function __construct() {
		global $_W;
		$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
		$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'css9_emoji'));
		$this->settings = iunserializer($settings);
	}
	public function doWebEmojiSetting(){
		$emoji1 = pdo_fetchAll('SELECT id,`code`,`words` FROM '.tablename('cjd_emoji').' WHERE isCode=1');
		$emoji2 = pdo_fetchAll('SELECT id,`code`,`name`,`words` FROM '.tablename('cjd_emoji').' WHERE isCode=0');
		include $this->template('emojiWords');
	}
	public function doWebUpdate(){
		global $_GPC, $_W;
		//$json = array();
		$id = intval($_GPC['id']);
		$word = $_GPC['word'];
        $update_date = array(
		    'words' => $word,
		);
		if($_W['ispost']) {
			$result = pdo_update('cjd_emoji', $update_date, array('id' => $id));
			if (!empty($result)) {
			    message('更新成功'); 
			}
			message('更新成功'); 
		}
	}
	public function doWebEmoji(){

		$words = $this->getIdiom();
		var_dump($words);
		include $this->template('emoji');
	}
	function getIdiom(){
		$res = ihttp_get('http://api.jiongxiao.com/idiom.php?first=1');
		$response = json_decode($res['content'],true);
		$words = $response['msg'];
		if(strlen($words) > 12){
			$this->getIdiom();
		}else{
			$words = str_split($words,3);
			var_dump($words);
			foreach ($words as $key => $value) {
				$result = pdo_fetch('SELECT * FROM '.tablename('cjd_emoji').' WHERE `words` LIKE "%'.$value.'%"');
				if(!$result){
					$this->getIdiom();
				}
			}
		}
	}
}