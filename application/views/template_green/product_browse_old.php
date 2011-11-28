<div id="right_col">
	<span class="arrow"><?php $this->load->view(CURR_TEMPLATE . '/_product_navi'); ?></span>
	<div class="cat_promotion_desc" id="promotion_rules"></div>
	<div id="cat_feature_section">
		<img src="/sc_upload/templates/images/cat_page_feature_heading.gif">
		<div id="cat_feature">
			<table width="100%" cellspacing="0" cellpadding="1" border="0" align="justify"></table>
		</div>
		<img src="/sc_upload/templates/images/cat_page_feature_bottom.gif">
	</div>
	<div id="cat_product_list_warp">
		<div id="cat_product_list_heading">
			<div class="cat_title"><?php echo $title ?></div>
			<div class="page_list_div">
				<?php if (!empty($pagination)): ?>
				<?php echo $pagination ?>
				<?php endif; ?>
			</div>
		</div>
		<div id="cat_product_list">
			<?php if (!empty($products)): ?>
			<?php foreach ($products as $k => $v): ?>
			<div class="product_item">
				<div class="product_item_img">
					<a href="<?php echo site_url('product/detail/' . $v['id']) ?>" target="_self">
						<img src="<?php echo $v['product_image']['s'] ?>" height="100" border="0" alt="" />
					</a>
				</div>
				<div class="product_item_name"><?php echo $v['product_name'] ?></div>
				<div class="product_item_price">
					<span style="text-decoration:line-through; color:#808080;"><?php echo $v['currency_symbol'] . $v['original_price'] ?></span>
					<span style="color:#FF0000;"><?php echo $v['currency_symbol'] . $v['price'] ?></span>
				</div>
				<div class="product_item_buy_btn">
					<form method="get" action="<?php echo CRV_CODE_URL ?>ecat/product_view.php">
						<input type="hidden" name="curr_country" value="<?php echo $this->session->userdata('lang_id') ?>" />
						<input type="hidden" name="lang" value="<?php echo $this->session->userdata('lang_code') ?>" />
						<input type="hidden" name="cat" value="" />
						<input type="hidden" name="product_unique_key" value="<?php echo $v['product_unique_key'] ?>" />
						<input type="hidden" name="trans_id" value="6709689" />
						<input type="hidden" name="normal_shopping_cart_status" value="on" />
						<input type="text" name="qty" class="buy_box" maxlength="4" size="5" value="1" />
						<input type="image" name="shopping_cart" border="0" src="<?php echo CRV_IMG_URL ?>btn_add_tc.gif">
					</form>
				</div>
			</div>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="extensor"></div>
		<div id="cat_product_list_bottom"></div>
		<div class="page_list_div">
			<?php if (!empty($pagination)): ?>
			<?php echo $pagination ?>
			<?php endif; ?>			
		</div>
	</div>
</div>