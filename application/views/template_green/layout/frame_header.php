<div id="header">
	<div id="head_crvmore_logo">
		<a href="<?php echo site_url('main') ?>" title="Home" alt="Home"><img src="<?php echo CRV_IMG_URL ?>head_crvmore_logo.gif" width="260" height="100" border="0" /></a>
	</div>
	<div id="head_vanguard_logo">
		<a href="http://www.crvanguard.com.hk" title="CRVanguard" alt="CRVanguard"><img src="<?php echo CRV_IMG_URL ?>head_crvanguard_logo_top.gif" width="170" height="100" border="0" /></a>
	</div>
	<div id="head_login">
		<a href="<?php echo site_url('customer/login') ?>" title="Login" alt="Login"><img src="<?php echo CRV_IMG_URL ?>head_login_button_tc.gif" width="60" height="22" border="0" /></a>
		<a href="<?php echo site_url('customer/register') ?>" title="Register" alt="Register"><img src="<?php echo CRV_IMG_URL ?>head_register_button_tc.gif" width="90" height="22" border="0" /></a>
	</div>
	<div class="extensor"></div>
	<div id="nav">
		<ul>
			<li>
				<a href="/sc_webcat/ecat/cms_view.php?lang=2&web_id=15&curr_country=HK" class="nav_menu_link"><img src="<?php echo CRV_IMG_URL ?>menu_about_crvanguard_g.gif" name="menu_about_crvanguard" width="123" height="30" /></a>
			</li>
			<li class="seperator"></li>
			<li>
				<a href="/sc_webcat/ecat/product_browse_list.php?lang=2&curr_country=HK&cat=1375" class="nav_menu_link"><img src="<?php echo CRV_IMG_URL ?>menu_products_g.gif" name="menu_products" width="70" height="30" /></a>
			</li>
			<li class="seperator"></li>
			<li>
				<a href="/sc_webcat/ecat/product_browse_list.php?lang=2&curr_country=HK&cat=355" class="nav_menu_link"><img src="<?php echo CRV_IMG_URL ?>menu_price_g.gif" name="menu_price" width="100" height="30" /></a>
			</li>
			<li class="seperator"></li>
			<li>
				<a href="/sc_webcat/ecat/product_browse_list.php?lang=2&curr_country=HK&cat=356" class="nav_menu_link"><img src="<?php echo CRV_IMG_URL ?>menu_promotion_g.gif" name="menu_promotion" width="105" height="30" /></a>
			</li>
			<li class="seperator"></li>
			<li>
				<a href="/sc_webcat/ecat/cms_view.php?lang=2&web_id=14&curr_country=HK" class="nav_menu_link"><img src="<?php echo CRV_IMG_URL ?>menu_shopping_guide_g.gif" name="menu_shopping_guide" width="138" height="30" /></a>
			</li>
			<li class="seperator"></li>
			<li>
				<a href="/sc_webcat/email_forms/contact_us.php?lang=2&curr_country=HK" class="nav_menu_link"><img src="<?php echo CRV_IMG_URL ?>menu_contact_us_g.gif" name="menu_contact_us" width="104" height="30" /></a>
			</li>
		</ul>
	</div>
	<div id="lang_bar" align="right">
		<img src="<?php echo CRV_IMG_URL ?>lang_hongkong.gif" width="37" height="30" border="0" />
		<a href="<?php echo site_url($this->lang->switch_uri('tc')) ?>" title="繁" alt="繁"><img src="<?php echo CRV_IMG_URL ?>lang_chinese_lang_button.gif" width="40" height="30" border="0" /></a>
		<a href="<?php echo site_url($this->lang->switch_uri('sc')) ?>" title="简" alt="简"><img src="<?php echo CRV_IMG_URL ?>lang_sc_lang_button.gif" height="30" border="0" /></a>
		<a href="<?php echo site_url($this->lang->switch_uri('tc')) ?>" title="繁" alt="繁"><img src="<?php echo CRV_IMG_URL ?>lang_tc_lang_button.gif" height="30" border="0" /></a>
		<a href="<?php echo site_url($this->lang->switch_uri('en')) ?>" title="Eng" alt="Eng"><img src="<?php echo CRV_IMG_URL ?>lang_english_lang_button.gif" width="42" height="30" border="0" /></a>
	</div>
	<div class="extensor"></div>
	<div id="search_bar">
		<form name="search" method="post" action="<?php echo site_url('search') ?>" style="margin: 0px; padding: 0px;">
			<input type="text" id="keyword" name="keyword" value="" onFocus="this.value='';" />
			<input type="image" name='btnsearch' class="search_btn" src="<?php echo CRV_IMG_URL ?>search_button.gif" value="Search" />
		</form>
		<a href="<?php echo site_url('search/advance') ?>" class="adv_search" >進階搜尋</a>
	</div>
	<div id="cart_bar">
		<span><?php echo $this->cart->total_items() ?>件<?php echo $this->cart->currency_symbol() ?>&nbsp;<?php echo number_format($this->cart->total(), 2) ?></span>
		<a href="<?php echo site_url('cart') ?>">結帳</a>
	</div>
	<div class="extensor"></div>
</div><!-- // #header -->