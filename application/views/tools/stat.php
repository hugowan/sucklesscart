<base href="<?php echo base_url() ?>">
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="resource/css/reset.css" />
		<link type="text/css" rel="stylesheet" href="resource/css/admin/style.css" />
		<link type="text/css" rel="stylesheet" href="resource/css/admin/invalid.css" />
	</head>
	<body>
		<div class="content-box">
			<div class="content-box-header">
				<h3 style="cursor: s-resize;">Stat</h3>
			</div>
			<!-- // .content-box-header -->
			<div class="content-box-content">
				<div class="tab-content default-tab">
					<table>
						<thead>
							<tr>
								<th>Times</th>
								<th>Number of Customer</th>
								<th>Total Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($result as $k => $v): ?>
							<tr>
								<td><?php echo $v['times'] ?></td>
								<td><?php echo $v['count'] ?></td>
								<td><?php echo $v['total'] ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- // .content-box-content -->
		</div>
		<!-- // .content-box -->
	</body>
</html>