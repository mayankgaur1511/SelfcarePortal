<div class="card pd-20 pd-sm-40">
	<div class="table-wrapper">
		<table id="datatable1" class="table display responsive nowrap">
			<thead>
				<tr>
                    <th><?php echo $this->lang->line('account_name');?></th>
                    <th><?php echo $this->lang->line('account_reference');?></th>
                    <th><?php echo $this->lang->line('status');?></th>
                    <th></th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($billingAccounts) and $billingAccounts):?>
					<?php foreach($billingAccounts as $ba): ?>
					<tr>
						<td style="vertical-align: middle;">
							<?php echo $ba['Name']?>
						</td>
						<td style="vertical-align: middle;"> 
							<?php echo $ba['Reference']?>
						</td>
						<td style="vertical-align: middle;">
							<span class="square-8 bg-success mg-r-5 rounded-circle"></span> <?php echo $this->lang->line('active');?>
						</td>
						<td>
							<div class="col" style="padding-top:0px;">

								<?php echo form_open("/Hierarchy/PrimaryGroups",array('method'=>'get'))?>
									<input type="hidden" name="BillingSystem" value="<?php echo $this->encrypt->encode($ba['BillingSystem'])?>">
									<input type="hidden" name="Reference" value="<?php echo $this->encrypt->encode($ba['Reference'])?>">
									<input type="hidden" name="LocalLogoId" value="<?php echo $this->input->get('Id')?>">
									<input type="hidden" name="LocalLogoReference" value="<?php echo $this->input->get('Reference')?>">
									<input type="hidden" name="NodeId" value="<?php echo $this->encrypt->encode($ba['NodeId'])?>">

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