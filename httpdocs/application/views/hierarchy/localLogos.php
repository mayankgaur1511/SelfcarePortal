<div class="card pd-20 pd-sm-40">
	<div class="table-wrapper">
		<table id="datatable1" class="table display responsive nowrap">
			<thead>
				<tr>
                    <th><?php echo $this->lang->line('organization_name');?></th>
                    <th><?php echo $this->lang->line('regional_company_Reference');?></th>
                    <th><?php echo $this->lang->line('status');?></th>
                    <th></th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($localLogos) and $localLogos):?>
					<?php foreach($localLogos as $localLogo): ?>
					<tr>
						<td style="vertical-align: middle;">
							<?php echo $localLogo['Name']?>
						</td>
						<td style="vertical-align: middle;"> 
							<?php echo $localLogo['Reference']?>
						</td>
						<td style="vertical-align: middle;">
							<span class="square-8 bg-success mg-r-5 rounded-circle"></span> <?php echo $this->lang->line('active');?>
						</td>
						<td>
							<div class="col" style="padding-top:0px;">

								<?php echo form_open(($localLogo['BillingSystem'] == "BlueSky"? "/Hierarchy/PrimaryGroups":"/Hierarchy/BillingAccounts"),array('method'=>'get')); ?>

									<input type="hidden" name="BillingSystem" value="<?php echo $this->encrypt->encode($localLogo['BillingSystem'])?>">
									<input type="hidden" name="Reference" value="<?php echo $this->encrypt->encode($localLogo['Reference'])?>">
									<input type="hidden" name="Id" value="<?php echo $this->encrypt->encode($localLogo['Id'])?>">

									<button type="submit" class="btn-load btn btn-default">
										<i class="fa fa-arrow-right"></i>
									</button>

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