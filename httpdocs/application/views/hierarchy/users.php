	<div class="card pd-20 pd-sm-40">

	<div class="table-wrapper">
		<table id="datatable1" class="table display responsive nowrap">
			<thead>
				<tr>
					<!-- BlueSky API doesn't return the username, however Wise 2 does -->
					<?php if(isset($users) and $users): ?>
						<?php if(isset($users[0]['Name'])): ?>
							<th><?php echo $this->lang->line('name');?></th>
						<?php endif ?>
					<?php endif ?>
					<th><?php echo $this->lang->line('email_address');?></th>
					<th><?php echo $this->lang->line('status');?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($users) and $users): ?>
					<?php foreach($users as $user): ?>
					<tr>
						<?php if(isset($users) and $users): ?>
							<?php if(isset($users[0]['Name'])): ?>
								<td><?php echo $user['Name']?></td>
							<?php endif ?>
						<?php endif ?>
						<td style="vertical-align: middle;">
							<?php echo $user['Email']?>
						</td>
						
						<td style="vertical-align: middle;">
							<?php if($user['Status'] == "ACTIVE"):?>
							<span class="square-8 bg-success mg-r-5 rounded-circle"></span> <?php echo $this->lang->line('active');?>
							<?php endif ?>
							<?php if($user['Status'] == "SUSPENDED"):?>
							<span class="square-8 bg-warning mg-r-5 rounded-circle"></span> <?php echo $this->lang->line('inactive');?>
							<?php endif ?>
							<?php if($user['Status'] == "CLOSED"):?>
							<span class="square-8 bg-pink mg-r-5 rounded-circle"></span> <?php echo $this->lang->line('deleted');?>
							<?php endif ?>
						</td>
						<td style="text-align:right">
							<a class="btn-load btn btn-secondary" href="/moderatorOrder/update?BillingSystem=<?php echo $this->encrypt->encode($user['BillingSystem'])?>&
								Reference=<?php echo $this->encrypt->encode($user['Reference'])?>&
								Id=<?php echo $this->encrypt->encode($user['Id'])?>&
								primaryGroup=<?php echo $this->encrypt->encode($pgId)?>&
								Bridge=<?php echo (isset($user['Bridge'])? $this->encrypt->encode($user['Bridge']):'')?>&
								SiteId=<?php echo (isset($user['SiteId'])? $this->encrypt->encode($user['SiteId']):'')?>">
								<i class="fa fa-wrench"></i></a>
							
							<a class="btn-load btn btn-default" 
								href="/Hierarchy/Accesses?BillingSystem=<?php echo $this->encrypt->encode($user['BillingSystem'])?>&
								Reference=<?php echo $this->encrypt->encode($user['Reference'])?>&
								Id=<?php echo $this->encrypt->encode($user['Id'])?>&
								primaryGroup=<?php echo $this->encrypt->encode($pgId)?>&
								Bridge=<?php echo (isset($user['Bridge'])? $this->encrypt->encode($user['Bridge']):'')?>&
								SiteId=<?php echo (isset($user['SiteId'])? $this->encrypt->encode($user['SiteId']):'')?>">
								<i class="fa fa-arrow-right"></i>
							</a>
						</td>
					</tr>
					<?php endforeach ?>
				<?php endif ?>
				<?php if(!isset($users) or !$users): ?>
				<tr>
					<td colspan="6">No organizations found</td>
				</tr>
				<?php endif ?>
			</tbody>
		</table>
	</div>
</div>


<script>

	$(document).ready(function(){
	$('#datatable1').DataTable({
		dom: 'Bfrtip',
		bInfo : false,
		buttons: [
            {
                text: "<i class='fa fa-plus mg-r-10'></i><?php echo $this->lang->line('add_user');?>",
				className: 'btn btn-default',
                action: function ( e, dt, node, config ) {
                    window.location.href = "/ModeratorOrder/new?pg=<?php echo $this->encrypt->encode($pgId)?>&BillingSystem=<?php echo $this->input->get('BillingSystem')?>";
                }
            }
        ],
		 bLengthChange: false,
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

$(".btn-load").click(function(e){
	$('.btn-load').attr("disabled", true);
	$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
});


</script>