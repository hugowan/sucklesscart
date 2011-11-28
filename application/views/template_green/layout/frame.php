<base href="<?php echo base_url() ?>">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $head ?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="y_key" content="566bf8cdcb5228cb" />
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="image_src" href="resource/images/1_v.jpg" />
		<link rel="stylesheet" href="resource/css/style.css" />
		<link rel="stylesheet" type="text/css" href="resource/css/jquery-ui.css"/>
		<link rel="stylesheet" type="text/css" href="resource/css/contentslider_home.css" />
		<!--[if IE]><link type="text/css" rel="stylesheet" href="resource/css/ie.css" /><![endif]-->

		<script type="text/javascript">
		var lang = '<?php echo $this->lang->lang() ?>';
		var langId = '<?php echo $this->lang->lang_id() ?>';
		var prdImgUrl = '<?php echo CRV_PRD_IMG_URL ?>';
		var siteUrl = function(str) {
			var url = '<?php echo site_url() ?>';
			return url + lang + '/' + str;
		}
		</script>		
		<script type="text/javascript" src="resource/js/jQuery1.6.4.min.js"></script>
		<script type="text/javascript" src="resource/js/jquery.effects.core.js"></script>
		<script type="text/javascript" src="resource/js/jquery.effects.slide.js"></script>
		<script type="text/javascript" src="resource/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="resource/js/contentslider.js"></script>
		<script type="text/javascript" src="resource/js/js_common.js"></script>
		<script type="text/javascript" src="resource/js/libs/jquery.json-2.3.min.js"></script>
		
		<script type="text/javascript" src="resource/js/init.js"></script>
	</head>
	<body>
		<div id="body_warp">
			<?php echo $header ?>
			<?php echo $body ?>
			<?php echo $footer ?>
		</div><!-- // #body_warp -->
		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>		
	</body>
</html>