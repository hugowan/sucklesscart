<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd"> 

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<!-- <link type="text/css" rel="stylesheet" href="<? echo base_url() ?>resource/css/reset.css" /> -->
		<style>
		body {padding:0; margin:0;}
		.style1 {color: #FFFFFF}
		.style2 {font-size: 12px}		
		</style>
	</head>
	<body>
		<div id="fb-root"></div>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>	
		<script src="http://connect.facebook.net/en_US/all.js"></script>		
		<script>
		var USER_ID	= '<?php echo $user_id; ?>'; 
		var PAGE_ID = '<?php echo $page_id; ?>';
		var APP_ID  = '<?php echo $app_id; ?>';
		
		window.fbAsyncInit = function() {
			FB.init({
				appId  : APP_ID,
				status : true, // check login status
				cookie : true, // enable cookies to allow the server to access the session
				xfbml  : true  // parse XFBML    
			});
			
			FB.Canvas.setSize({ height: '970' });
		};			

		$(function(){
			$('#submit').click(function(){
				var user_id		= $('#user_id').val();
				var real_name	= $('#real_name').val();
				var email		= $('#email').val();
				var question	= $('input[name=question]:checked').val();
				var notice		= $('#notice:checked').val() || 0;
				
				if (isEmpty(real_name)) {
					alert('必須填上用戶名稱');
					return false;
				}
				
				var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
				if (reg.test(email) == false) {
					alert('必須填寫電郵地址');
					return false;								
				}
				
				if (isEmpty(question)) {
					alert('必須回答問題');
					return false;
				}
			
				FB.ui(
					{
						method: 'feed',
						name: '華潤萬家摩爾CRVmore.com日日送$100華潤萬家超市現金劵',
						link: '<?php echo base_url() ?>20110906/redirect/?fbuid=' + USER_ID,
						picture: 'http://www.crvmore.com/sc_upload/templates/images/crvmore_logo_top.gif',
						// caption: '',
						description: '只需Like我哋Fans Page，答啱簡單問題，即有機會得到$100華潤萬家超市現金劵！分享俾越多朋友知，越大機會得獎！'
					},
					function(response) {
						if (response && response.post_id) {
							alert('謝謝您的參與');
							
							$.post('<?php echo base_url() ?>20110906/fb/save_callback/', {user_id:user_id, real_name:real_name, email:email, question:question, notice:notice}, function(data){
								window.top.location.href = 'http://www.facebook.com/pages/' + PAGE_ID + '?sk=app_' + APP_ID;
								// window.location = '<?php echo base_url() ?>event/20110906/fb/step3/';
							});
						} else {
							alert('請按分享，越多朋友知，越大機會中獎！');
						}
					}
				);			
			});
		});
	
		function isEmpty(obj) {
			if (typeof obj == 'undefined' || obj === null || obj === '') return true;
			if (typeof obj == 'number' && isNaN(obj)) return true;
			if (obj instanceof Date && isNaN(Number(obj))) return true;
			return false;
		}	
		</script>
		
		<!-- step2 content -->
		<table width="520" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="520"><img src="http://www.crvmore.com/sc_upload/templates/images/facebook_promotion_20110914/form_page_201109_1.jpg?1" /></td>
		  </tr>
		  <tr>
			<td><table width="520" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="30" valign="top"><img src="http://www.crvmore.com/sc_upload/templates/images/facebook_promotion_20110914/form_page_201109_2_1.jpg" /></td>
				<td width="455" valign="top" background="http://www.crvmore.com/sc_upload/templates/images/facebook_promotion_20110914/form_page_201109_bg.jpg"><div align="center">
				  <form method="POST" id="frm" action="step3.php">
				    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>" />
					<table width="453" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td height="30" colspan="2"><div align="left" class="style2"><span class="style1">Facebook用戶名稱：
						  <input type="text" name="real_name" id="real_name" />
						</span></div></td>
					  </tr>
					  <tr>
						<td height="30" colspan="2" valign="top"><div align="left" class="style2"><span class="style1">(請填寫真實的Facebook用戶名稱以確認參加者身份)</span></div></td>
					  </tr>
					  <tr>
						<td height="20" colspan="2"><div align="left" class="style2"><span class="style1">Facebook電郵地址：
						  <input type="text" name="email" id="email" />
						</span></div></td>
					  </tr>
					  <tr>
						<td height="30" colspan="2" valign="top"><div align="left" class="style2"><span class="style1">(請填寫真實的Facebook電郵地址，如得獎後將以電郵通知)</span></div></td>
					  </tr>
					  <tr>
						<td height="20" colspan="2"><div align="left" class="style2">
						  <input type="checkbox" name="notice" id="notice" value="1" checked="checked" />
						  <span class="style1">本人我願意接收萬家摩爾CRVmore.com的推廣資訊</span></div></td>
					  </tr>
					  <tr>
						<td height="20" colspan="2"><div align="left"><span class="style2"></span></div></td>
					  </tr>
					  <tr>
						<td height="20" colspan="2"><div align="left" class="style2"><span class="style1">萬家摩爾CRVmore.com是一個什麼類型的網站？</span></div></td>
					  </tr>
					  <tr>
						<td width="180" height="30"><div align="left" class="style2">
						  <input type="radio" name="question" id="question" value="a" />
						  <span class="style1">a. 網上購物商城</span></div></td>
						<td width="273" height="30"><div align="left" class="style2">
						  <input type="radio" name="question" id="question" value="b" />
						  <span class="style1">b. 拍賣網站</span></div></td>
					  </tr>
					  <tr>
						<td height="30"><div align="left" class="style2">
						  <input type="radio" name="question" id="question" value="c" />
						  <span class="style1">c. 視頻網站</span></div></td>
						<td height="30"><div align="left" class="style2">
						  <input type="radio" name="question" id="question" value="d" />
						  <span class="style1">d. 社交網站</span></div></td>
					  </tr>
					  <tr>
						<td height="20" colspan="2"><div align="left" class="style2"><span class="style1">(醒您小tips：睇下CRVmore.com係咩網站先作答。<a href="http://www.crvmore.com/" target="_blank">請按此</a>)</span></div></td>
					  </tr>
					  <tr>
						<td height="45" colspan="2" valign="middle"><table width="453" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="338">&nbsp;</td>
							<td width="115"><div align="right" class="style2"><a href="<?php echo base_url() ?>20110906/fb/tc" target="_blank">條款及細則</a></div></td>
						  </tr>
						</table></td>
					  </tr>
					  <tr>
						<td height="50" colspan="2"><div align="center"><img src="http://www.crvmore.com/sc_upload/templates/images/facebook_promotion_20110914/form_page_201109_4_2.gif" border="0" id="submit" /></div></td>
					  </tr>
					</table>
				  </form>
				</div></td>
				<td width="35" valign="top"><img src="http://www.crvmore.com/sc_upload/templates/images/facebook_promotion_20110914/form_page_201109_2_3.jpg" /></td>
			  </tr>
			</table></td>
		  </tr>
		</table>		
		<!-- // step2 content -->
		
		<script type='text/javascript'>
		// Conversion Name: Facebook Campaign Sep 2011_Form Page
		// INSTRUCTIONS 
		// The Conversion Tags should be placed at the top of the <BODY> section of the HTML page. 
		// In case you want to ensure that the full page loads as a prerequisite for a conversion 
		// being recorded, place the tag at the bottom of the page. Note, however, that this may 
		// skew the data in the case of slow-loading pages and in general not recommended. 
		//
		// NOTE: It is possible to test if the tags are working correctly before campaign launch 
		// as follows:  Browse to http://bs.serving-sys.com/BurstingPipe/adServer.bs?cn=at, which is 
		// a page that lets you set your local machine to 'testing' mode.  In this mode, when 
		// visiting a page that includes an conversion tag, a new window will open, showing you 
		// the data sent by the conversion tag to the MediaMind servers. 
		// 
		// END of instructions (These instruction lines can be deleted from the actual HTML)
		var ebRand = Math.random()+'';
		ebRand = ebRand * 1000000;
		//<![CDATA[ 
		document.write('<scr'+'ipt src="HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?cn=as&amp;ActivityID=144658&amp;rnd=' + ebRand + '"></scr' + 'ipt>');
		//]]>
		</script>
		<noscript>
		<img width="1" height="1" style="border:0" src="HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?cn=as&amp;ActivityID=144658&amp;ns=1"/>
		</noscript>
	</body>
</html>