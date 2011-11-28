<base href="<?php echo base_url() ?>">
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd"> 

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link type="text/css" rel="stylesheet" href="<? echo base_url() ?>resource/css/reset.css" />
	<title></title>
</head>
<body>
	<div id="fb-root"></div>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>	
	<script src="http://connect.facebook.net/en_US/all.js"></script>
	<script>
	var IS_LIKED	= '<?php echo $is_liked; ?>';
	var PAGE_ID  	= '<?php echo $page_id; ?>';
	var APP_ID   	= '<?php echo $app_id; ?>';

	window.fbAsyncInit = function() {
		FB.init({
			appId  : APP_ID,
			status : true, // check login status
			cookie : true, // enable cookies to allow the server to access the session
			xfbml  : true  // parse XFBML    
		}); 
	};	
		
	(function() {
		var e = document.createElement('script'); e.async = true;
		e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);
	}());    

	FB.Event.subscribe('auth.sessionChange', function(response) {
		//alert('The status of the session is: ' + response.status);
	});	
	
	FB.Event.subscribe('edge.create', function(response) {
		window.top.location.href = 'http://www.facebook.com/pages/' + PAGE_ID + '?sk=app_' + APP_ID;
	});
	
	FB.Event.subscribe('edge.remove', function(response) {
		window.top.location.href = 'http://www.facebook.com/pages/' + PAGE_ID + '?sk=app_' + APP_ID;
	});

	
	$(function(){
		//alert(isLiked);
		if(IS_LIKED == false) {
			$('#go_step2').click(function(e){
				e.preventDefault();
				alert('請先Like我哋Fans Page');
			})		
		} else {
			// request permission
			$('#go_step2').click(function(e){
				e.preventDefault();
				
				FB.login(function(response) {
					if (response.session) {
						access_token = response.session.access_token;
						window.top.location.href = 'http://www.facebook.com/pages/' + PAGE_ID + '?sk=app_' + APP_ID;
					}
				});
				
				/*
				FB.ui({method: 'permissions.request', perms: 'email', display: 'popup'},function(response) {
					//alert(response.toSource());
					
					if(response && response.perms) {
						// alert('Permissions granted: '+response.perms);
						window.top.location.href = 'http://www.facebook.com/pages/' + PAGE_ID + '?sk=app_' + APP_ID;						
					} else if (!response.perms){
						alert('請同意讀取權限');
					}
				});
				*/
			});
		}
	})
	</script>

	<!-- step1 content -->
	<div style="width:50px; height:25px; position:relative; overflow:hidden; display:none;">
		<fb:like href="http://www.facebook.com/crvmore" send="false" width="450" show_faces="false" font=""></fb:like>		
	</div>


	<table width="520" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td colspan="3"><img src="http://www.crvmore.com/sc_upload/templates/images/facebook_promotion_20110914/before_like_page_201109_1.jpg?1" /></td>
	  </tr>
	  <tr>
		<td width="160"><img src="http://www.crvmore.com/sc_upload/templates/images/facebook_promotion_20110914/before_like_page_201109_2_1.jpg" /></td>
		<td width="200"><img src="http://www.crvmore.com/sc_upload/templates/images/facebook_promotion_20110914/before_like_page_201109_2_2.gif" border="0" id="go_step2" style="cursor:pointer;"/></td>
		<td width="160"><img src="http://www.crvmore.com/sc_upload/templates/images/facebook_promotion_20110914/before_like_page_201109_2_3.jpg" /></td>
	  </tr>
	</table>	
	
	<!-- // step1 content -->
	
	<script type='text/javascript'>
	// Conversion Name: Facebook Campaign Sep 2011_First Page
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
	document.write('<scr'+'ipt src="HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?cn=as&amp;ActivityID=144657&amp;rnd=' + ebRand + '"></scr' + 'ipt>');
	//]]>
	</script>
	<noscript>
	<img width="1" height="1" style="border:0" src="HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?cn=as&amp;ActivityID=144657&amp;ns=1"/>
	</noscript>

</body>
</html>


