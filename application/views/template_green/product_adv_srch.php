<form method="post" action="<?php echo site_url('search') ?>" name="form1">
<!-- 	<input type="hidden" value="2" id="lang" name="lang"> -->
<!-- 	<input type="hidden" value="1" name="quick_search_value"> -->
	<table width="690" cellspacing="0" cellpadding="0" border="0" align="center">
		<tbody>
			<tr>
				<td height="20" colspan="3"><img src="<?php echo CRV_IMG_URL ?>txt_advanced_search_tc.gif"></td>
			</tr>
			<tr>
				<td width="690" valign="bottom" height="17" align="left" colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td width="629" valign="top" align="left">
				<table width="100%" cellspacing="0" cellpadding="5" border="0">
					<tbody>
						<tr>
							<td align="right"><span class="form_question">產品目錄:</span></td>
							<td>
								<select name="category" id="category">
					                <option value="">所有產品目錄</option>
					                <option value="1375">超市雜貨</option>
									<option value="1895">世界名酒</option>
									<option value="1376">健康、美容</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right"><span class="form_question">產品名稱 :</span></td>
							<td>
								<input type="text" size="40" value="" id="keyword" name="keyword">
							</td>
						</tr>
						<tr>
							<td align="right"><span class="form_question">品牌:</span></td>
							<td>
								<input type="text" value="" id="brand" name="brand">
							</td>
						</tr>
						<tr>
							<td align="right"><span class="form_question"> 價格 :</span></td>
							<td>
								<select name="price" id="price">
									<option value=""> -- </option>
									<option value="0-49">$ 0 - 49</option>
									<option value="50-99">$ 50 - 99</option>
									<option value="100-199">$ 100 - 199</option>
									<option value="200-499">$ 200 - 499</option>
									<option value="500-999">$ 500 - 999</option>
									<option value="1000-1999">$ 1,000 - 1,999</option>
									<option value="2000-4999">$ 2,000 - 4,999</option>
									<option value="5000-9999">$ 5,000 - 9,999</option>
									<option value="10000-19999">$ 10,000 - 19,999</option>
									<option value="20000-100000"> $ 19999</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<button style="vertical-align:middle;width:119px " class="image_button" id="Send " name="send" type="submit"><img border="0" align="left" src="<?php echo CRV_IMG_URL ?>b_basket_search_tc.gif"></button>
								<button style="vertical-align:middle;width:119px " class="image_button" id="reset" name="reset" type="reset"><img border="0" align="left" src="<?php echo CRV_IMG_URL ?>btn_reset_td.gif"></button>
							</td>
						</tr>
					</tbody>
				</table></td>
			</tr>
		</tbody>
	</table>
</form>