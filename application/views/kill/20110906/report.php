<base href="<?php echo base_url() ?>">
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
	body {font:12px "Helvetica Neue",Arial,sans-serif;}
	table {text-align:center; width:100%; font-size:12px;}
	th {background:#666; color:#FFF; width:200px;}
	tr:hover {background:#FFF;}
	td {border:1px solid #333;}
	
	.clear {clear:both;}
	
	#tool {background:#EEE; text-align:right; height:25px; line-height:25px; padding:0 5px 0 0;}
	#info {background:#DDD; float:left; width:100%; height:25px; line-height:25px;}
	#info #date {float:left; height:25px; line-height:25px; margin:0 0 0 10px;}
	#info #date div {float:left; margin:0 10px 0 0; font-size:13px;}
	#info #search {float:right; height:25px; line-height:25px;}
	</style>
</head>
<body>
	<div id="tool">
		<a href="20110906/report/logout">Logout</a>
	</div>
	<div id="info">
		<div id="date">
			<div>From&nbsp;<?php echo $from ?></div>
			<div>To&nbsp;<?php echo $to ?></div>
		</div>		
		<div id="search">
			<form method="get" action="">
				From:&nbsp;&nbsp;<input type="text" name="from" value="" />
				To:&nbsp;&nbsp;<input type="text" name="to" value="" />
				Answer:&nbsp;<?php echo form_dropdown('question', $question_opt, ''); ?>
				<input type="submit" value="搜尋" />
			</form>
		</div>
	</div>
	<div class="clear"></div>

	<?php echo $table ?>
	
	<?php echo $this->pagination->create_links() ?>
</body>
</html>