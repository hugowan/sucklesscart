<div id="right_col">
	<div class="page_basket">
		<img src="<?php echo CRV_IMG_URL ?>top_ShoppingCart_tc.gif">
		<br />
		閣下可選擇直接送貨及/或提貨點自取服務作為送貨方式，有關資料可在商品資料頁查閱。
		<br />
		<a href="<?php echo site_url('cart') ?>"><img src="<?php echo CRV_IMG_URL ?>basket_step1_off_hk_td.gif"></a>
		<img src="<?php echo CRV_IMG_URL ?>basket_step2_on_hk_td.gif">
		<img src="<?php echo CRV_IMG_URL ?>basket_step3_off_hk_td.gif">
		<img src="<?php echo CRV_IMG_URL ?>basket_step4_off_hk_td.gif">			
	</div>
	
	<!-- basic infomation -->
	<form method="POST" id="frm_basic" action="<?php echo site_url('order/save') ?>">
	<fieldset>
		<legend>收件人/提貨人資料</legend>
		<table>
			<tr>
				<td><?php echo $this->lang->line('order_last_name') ?></td>
				<td><input type="text" name="last_name" value="<?php echo $customer['last_name'] ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line('order_first_name') ?></td>
				<td><input type="text" name="first_name" value="<?php echo $customer['first_name'] ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line('order_tel') ?></td>
				<td><input type="text" name="tel_1" value="<?php echo $customer['tel_1'] ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line('order_email') ?></td>
				<td><input type="text" name="email" value="<?php echo $customer['email'] ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line('order_remark') ?></td>
				<td><textarea name="remark"></textarea></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line('order_shipment_method') ?></td>
				<td>
					<input type="radio" name="delivery_method" value="0" />提貨點自取
					<input type="radio" name="delivery_method" value="1" />直接送貨
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="submit" id="btn_basic" value="繼續" />
		
	<fieldset>
		<legend>直接送貨</legend>
		<table cellspacing="0" cellpadding="0" border="0">
			<tbody>
				<tr>
					<td colspan="2">
						<?php echo form_dropdown('shipment_method', $shipment_method, NULL) ?>
					</td>
				</tr>
				<tr>
					<td>送貨地址:</td>
					<td><input type="text" size="35" value="" id="address_1" name="address_1"></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="text" size="35" value="" id="address_2" name="address_2"></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="text" size="35" value="" id="address_3" name="address_3"></td>
				</tr>
				<tr>
					<td>城市:</td>
					<td><input type="text" value="" id="city" name="city"></td>
				</tr>
				<tr>
					<td>州份:</td>
					<td><input type="text" value="" id="state" name="state"></td>
				</tr>
				<tr>
					<td>郵遞區號:</td>
					<td><input type="text" value="" id="zip_code" name="zip_code"></td>
				</tr>
				<tr>
					<td>國家/地區:</td>
					<td><?php echo form_dropdown('country', $country, strtoupper($this->session->userdata('country_code'))) ?></td>
				</tr>
			</tbody>
		</table>			
	</fieldset>

	<fieldset>
		<legend>提貨點自取</legend>	
		(提貨人名稱必須跟你的身份証/護照持有人相同。) <br />
		身份證 / 護照號碼 (用以身份確認):<br />
		收貨人名稱將會與你身份證/護照頭4個字母或數字在提貨時進行核對。<br />
		<input type="text" size="5" maxlength="4" name="receiver_credential"><br />				
		(身份證/護照頭4個字母或數字) 
		<?php echo form_dropdown('pickup_city', $pickup_city, NULL, '') ?>
		<?php echo form_dropdown('pickup_shop', array()) ?>
	</fieldset>
	<input type="submit" value="submit" />
	</form>
</div><!-- // right_col -->

<script type="text/javascript" src="<?php echo base_url()?>resource/js/libs/jquery.validate.min.js"></script>
<script>
$(function(){
	$('select[name=pickup_city]').change(function(){
		var city_id = $(this).val();
		$.post(siteUrl('order/pickup_shop_ajax'), {city_id:city_id}, function(data){
			$pickup_shop = $('select[name=pickup_shop]');
			$pickup_shop.html('');
			$.each($.evalJSON($.trim(data)), function(k, v) {
				$pickup_shop.append('<option value="' + k + '">' + v + '</option>');
			})
		})
	})
})
</script>