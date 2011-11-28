<?php echo $message ?>
<div id="content_warp">
	<?php echo $body_left ?>
	<?php echo $body_right ?>
	<div class="extensor"></div>
</div>
<div class="extensor"></div>
<!-- shxt -->

<!-- Start Pop up box [ Images must provide width in order to work in IE6!]-->
<div id="popup_bg"></div>
<div id="popup">
<div id="popup_content">
	<TABLE cellSpacing=0 cellPadding=0 width=610 align=center border=0>
		<TBODY>
			<TR>
				<TD width=390><A href="http://www.crvmore.com/sc_webcat/ecat/cms_view.php?lang=2&amp;curr_country=HK&amp;web_id=90090"><IMG src="http://www.crvmore.com/sc_upload/templates/images/popup_banner_20111007.jpg" border=0></A></TD>
			</TR>
			<TR>
				<TD bgColor=#ffffff height=20>
				<DIV align=right>
					<A class=popup_close href="#"><IMG src="http://www.crvmore.com/sc_upload/templates/images/layer_close_banner_20110512.gif" border=0></A>
				</DIV></TD>
			</TR>
		</TBODY>
	</TABLE>
</div>
</div>
<!-- End Pop up box -->
<script language="javascript">
$(document).ready(function() {
	// Nav Menu
	$(".nav_menu_link").hover(function() {
		tar = $(this).children("img");
		tar.attr("src", "/sc_upload/templates/images/" + tar.attr("name") + "_o.gif")
	}, function() {
		tar = $(this).children("img")
		tar.attr("src", "/sc_upload/templates/images/" + tar.attr("name") + "_g.gif")
	})
	
	// Sliding Banner
	featuredcontentslider.init({
		id : "home_slider1", //id of main slider DIV
		contentsource : ["inline", ""], //Valid values: ["inline", ""] or ["ajax", "path_to_file"]
		toc : ["1", "2", "3", "4", "5"], //Valid values: "#increment", "markup", ["label1", "label2", etc]
		nextprev : ["", ""], //labels for "prev" and "next" links. Set to "" to hide.
		revealtype : "click", //Behavior of pagination links to reveal the slides: "click" or "mouseover"
		enablefade : [true, 0.2], //[true/false, fadedegree]
		autorotate : [true, 3000], //[true/false, pausetime]
		onChange : function(previndex, curindex) {  //event handler fired whenever script changes slide
			//previndex holds index of last slide viewed b4 current (1=1st slide, 2nd=2nd etc)
			//curindex holds index of currently shown slide (1=1st slide, 2nd=2nd etc)
		}
	})

	// Pop up banner / Advert
	if(false) {
		$("#popup_bg").width($(window).width());
		$("#popup_bg").height($(window).height());
		width = ($("#popup_bg").width() - $("#popup").width()) / 2;
		height = ($("#popup_bg").height() - $("#popup").height()) / 2;

		$("#popup").css({
			"top" : parseInt(height),
			"left" : parseInt(width)
		})

		$(".popup_close, #popup_bg").click(function() {
			$("#popup_bg, #popup").hide();
			return false;
		})

		$("#popup_bg").height($("#popup_bg").height() * 5)

		$("#popup_bg, #popup").show()
	}
})
</script>