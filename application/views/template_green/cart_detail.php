<?php 

// echo '<pre>';
// print_r($cart_content);
// echo '</pre>';

?>
<div id="right_col">
	<?php if (isset($coupons)): ?>
	<table>
		<?php foreach ($coupons as $key => $val): ?>
		<?php if ($val['_is_used'] == FALSE && $val['_is_expired'] == FALSE): ?>
		<tr>
			<td><a href="#" id="apply_coupon_<?php echo $val['id'] ?>"><?php echo $val['id'] ?></a></td>
			<td><?php echo $val['_display_name'] ?></td>
			<td><?php echo $val['_percent'] ?></td>
			<td><?php echo $this->cart->format_number($val['_amount']) ?></td>
		</tr>
		<?php endif ?>
		<?php endforeach ?>
	</table>
	<?php endif ?>
	
	<table>
		<thead>
			<th>取消</th>
			<th colspan="2">物品資訊</th>
			<th>價格</th>
			<th>數量</th>
			<th>小計</th>
		</thead>
		<?php if (isset($cart_content)): ?>
		<tbody>
			<?php foreach ($cart_content as $key => $val): ?>
			<tr id="<?php echo $val['rowid'] ?>">
				<td><a href="" class="del">取消</a></td>
				<td><img src="<?php echo CRV_PRD_IMG_URL . $val['image'] ?>" /></td>
				<td><?php echo $val['name'] ?></td>
				<td><?php echo $val['currency_symbol'] . $val['price'] ?></td>
				<td><input type="text" id="qty_<?php echo $val['rowid'] ?>" size="2" value="<?php echo $val['qty'] ?>" /></td>
				<td><?php echo $val['currency_symbol'] . $this->cart->format_number($val['qty'] * $val['price']) ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td><?php echo $total_items ?></td>
			</tr>
			<tr>
				<td></td>
				<td><?php echo $this->cart->format_number($cart_total_original) ?></td>
			</tr>
			<tr>
				<td></td>
				<td><?php echo $this->cart->format_number($total_discount) ?></td>
			</tr>
			<tr>
				<td></td>
				<td><?php echo $this->cart->format_number($cart_total) ?></td>
			</tr>
			<tr>
				<td><?php echo anchor(site_url('cart'), 'Reload') ?></td>
				<td><?php echo anchor(site_url('order'), 'Next') ?></td>
			</tr>
		</tfoot>
		<?php else: ?>
		<tbody>		
			<tr>
				<td colspan="6"><?php echo $this->lang->line('cart_empty') ?></td>
			</tr>
		</tbody>		
		<?php endif // cart_content ?>	
	</table>
</div>

<style>
table {width:100%; border-collapse:collapse; border-spacing:0; font-size:inherit;}
table thead th {background:#000000; color:#FFFFFF;}
table tbody td {border-bottom:1px solid #333333;}
</style>

<script>
$(function(){
	// update qty
	$('input[id^=qty_]').focusout(function(){
		var rowid = $(this).attr('id').split('_')[1];
		var qty = $(this).val();
		$.post(siteUrl('cart/update_qty_ajax'), {rowid:rowid, qty:qty}, function(data){
			if(isEmpty(data) == false) {
				$.each($.evalJSON(data), function(k, v) {
										
				});
			}
		})
	})
	
	// delete action
	$('a[class=del]').click(function(){
		var rowid = $(this).parents('tr').attr('id');
		$.post(siteUrl('cart/update_qty_ajax'), {rowid:rowid, qty:'0'}, function(data){
			$('#' + rowid).fadeOut('slow');
		})
	})
})
</script>