<div class="card pd-20 pd-sm-40">
	<?php echo form_open("/settings/translation",array("method"=>"GET")); ?>
	<div class="row">
		<div class="col-12">
			<div class="input-group search-div">
				<input class="form-control search-text" placeholder="<?php echo $this->lang->line('search_for')?>" type="text" name="search"
				value="<?php echo $this->input->get('search')?>">
				<span class="input-group-btn">
					<button class="btn btn-danger" type="submit">
						<i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>

	<div class="table-responsive">
		<table class="table mg-b-0">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('order_number')?></th>
					<th><?php echo $this->lang->line('date_n_time')?></th>
					<th><?php echo $this->lang->line('order_type')?></th>
					<th><?php echo $this->lang->line('status')?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($orders) and $orders):?>
				<?php foreach($orders as $order): ?>
				<tr>
					<td style="vertical-align: middle;">
						<?php echo $order->id?>
					</td>
					<td style="vertical-align: middle;">
						<?php echo date("d / m / Y - H:m:s", strtotime($order->datetime))?> (GMT)
					</td>
					<td style="vertical-align: middle;">
						<?php echo $this->lang->line($order->orderType); ?>
					</td>
					<td style="vertical-align: middle;" id="status_<?php echo $order->id?>">
						<?php if($order->status == "COMPLETED"):?>
						<span class="square-8 bg-success mg-r-5 rounded-circle"></span>
						<?php echo $this->lang->line('completed')?>
						<?php endif ?>

						<?php if($order->status == "ERROR"):?>
						<span class="square-8 bg-pink mg-r-5 rounded-circle"></span>
						<?php echo $this->lang->line('error')?>
						<?php endif ?>

						<?php if($order->status == "CREATED" or $order->status == "PENDING" or $order->status == "WAITING"):?>
						<i class="fa fa-circle-o-notch fa-spin"></i>
						<?php echo $this->lang->line('processiing')?>
						<?php endif ?>
					</td>
				</tr>
				<?php endforeach ?>
				<?php endif ?>
				<?php if(!isset($orders) or !$orders): ?>
				<tr>
					<td colspan="6"><?php echo $this->lang->line('no_results')?></td>
				</tr>
				<?php endif ?>
			</tbody>
		</table>
	</div>
	<div class="ht-80 bd d-flex align-items-center justify-content-center">
		<nav aria-label="Page navigation">
			<ul class="pagination pagination-basic mg-b-0">
				<?php echo $this->pagination->create_links(); ?>
			</ul>
		</nav>
	</div>
</div>

<script>
	<?php if(isset($orders) and $orders): ?>
		<?php foreach($orders as $order): ?>
			<?php if($order->status == "CREATED" or $order->status == "PENDING" or $order->status == "WAITING"):?>

				var status_<?php echo $order->id?> = setInterval(function () {
					try {
						getStatus(<?php echo $order->id?>);
					} catch (e) {
						console.log(e);
					}
				}, 5000);

			<?php endif ?>
		<?php endforeach ?>
	<?php endif ?>

	function getStatus(orderId)
	{
		$.get("/orderTracking/getStatus/" + orderId, function(result){
			if(result.status == "COMPLETED"){
				$("#status_" + orderId).html("<span class='square-8 bg-success mg-r-5 rounded-circle'></span>Completed");
				return false;
			}
			if(result.status == "ERROR"){
				$("#status_" + orderId).html("<span class='square-8 bg-pink mg-r-5 rounded-circle'></span>Error");
				return false;
			}
		})
		.fail(function(result){
			// Don't do anything, just wait for next loop
		});
	}
</script>
