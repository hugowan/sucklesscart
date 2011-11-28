<a href="" class="cat_path">主頁</a>
<?php if ( ! empty($navi)): ?>
<?php foreach($navi as $key => $val): ?>
&nbsp;&gt;&nbsp;<a href="<?php echo site_url('category/browse/cat_id/' . $val['cat_id']) ?>" class="cat_path"><?php echo $val['cat_name'] ?></a>
<?php endforeach; ?>
<?php endif; ?>