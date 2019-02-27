<?php
	require_once "db.php";
	$db = new DB("db_cjd","root","","localhost");
	$db->query("SELECT `name` FROM cjd_idiom WHERE LENGTH(`name`) = 12");
	$rows = $db->result();
	
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
	<title>emoji</title>
</head>
<body>
	<div id="emoji">
	<?php foreach ($rows as $key => $value) { ?>
		<span><?php echo $value->name; ?></span>
	<?php } ?>
	</div>
</body>
</html>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
<script type="text/javascript">
	function init(){
		$('#emoji span').each(function($index){
			if($index < 10){
				var word = $.trim($(this).text());
				insert(word);
				$(this).remove();
			}else{
				
			}
		})
	}
	init();
	function insert(word){
		$.ajax({
			url:'cb.php',
			data:{
				word:word
			},
			type:'post',
			dataType:'json'
		}).done(function(data){
			console.log(data.word)
		})
	}
</script>