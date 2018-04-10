<div class="card pd-20 pd-sm-40">
	<div class="table-wrapper">
		<table id="datatable1" class="table display responsive nowrap">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('department_cc_name');?></th>
					<th><?php echo $this->lang->line('status');?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($primaryGroups) and $primaryGroups): ?>
				<?php foreach($primaryGroups as $pg): ?>
				<tr>
					<td style="vertical-align: middle;">
						<?php echo $pg['Name']?>
					</td>
					<td style="vertical-align: middle;">
						<span class="square-8 bg-success mg-r-5 rounded-circle"></span> <?php echo $this->lang->line('active');?>
					</td>
					<td>
						<div class="col" style="padding-top:0px;">

							<?php echo form_open("/Hierarchy/Users", array('method'=>'get')); ?>
							<input type="hidden" name="BillingSystem" value="<?php echo $this->encrypt->encode($pg['BillingSystem'])?>">
							<input type="hidden" name="Reference" value="<?php echo $this->encrypt->encode($pg['Reference'])?>">
							<input type="hidden" name="SiteId" value="<?php echo (isset($pg['SiteId'])? $this->encrypt->encode($pg['SiteId']):'')?>">
							<input type="hidden" name="Bridge" value="<?php echo (isset($pg['Bridge'])? $this->encrypt->encode($pg['Bridge']):'')?>">
							<input type="hidden" name="Product" value="<?php echo (isset($pg['Product'])? $this->encrypt->encode($pg['Product']):'')?>">
							<button class="btn-load btn btn-default">
								<i class="fa fa-arrow-right"></i>
								</a>

								<?php echo form_close(); ?>

						</div>
					</td>
				</tr>
				<?php endforeach ?>
				<?php endif ?>
			</tbody>
		</table>
	</div>
</div>

<script>
	$(document).ready(function(){
	$('#datatable1').DataTable({
		 bLengthChange: false,
		 bInfo : false,
          responsive: true,
          language: {
			infoEmpty: "<?php echo $this->lang->line('no_results');?>",
            searchPlaceholder: '<?php echo $this->lang->line('search...');?>',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
			paginate: {
      			previous: "<?php echo $this->lang->line('previous');?>",
				next: "<?php echo $this->lang->line('next');?>",
				first: "<?php echo $this->lang->line('first');?>",
				last: "<?php echo $this->lang->line('last');?>"
    		}
          },
		  
        });
});

$("form").submit(function(e){
	$('.btn-load').attr("disabled", true);
	var button = $(this).find(".btn-load");
	button.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
});

</script>
