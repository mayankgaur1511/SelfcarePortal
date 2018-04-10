<div class="card pd-20 pd-sm-40">

	          <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('weblogin');?></th>
					<th><?php echo $this->lang->line('mod_pin');?></th>
					<th><?php echo $this->lang->line('part_pin');?></th>
					<th><?php echo $this->lang->line('conf_type');?></th>
					<th><?php echo $this->lang->line('start_date');?></th>
					<th><?php echo $this->lang->line('duration');?></th>
					<th><?php echo $this->lang->line('status');?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($accesses) and $accesses): ?>
					<?php foreach($accesses as $access): ?>
					<tr>
						<td style="vertical-align: middle;">
							<?php echo (isset($extension) ? $extension:'') ?>
							<?php echo $access['Weblogin']?>
						</td>
						<td style="vertical-align: middle;">
							<?php echo $access['ModeratorPin']?>
						</td>
						<td style="vertical-align: middle;">
							<?php echo $access['ParticipantPin']?>
						</td>
						<td style="vertical-align: middle;">
							<?php if(!$access['IsScheduled'] and !$access['IsRecurring']):?>
								<?php echo $this->lang->line('demand');?>
							<?php endif ?>

							<?php if($access['IsScheduled']):?>
								<?php echo $this->lang->line('scheduled');?>
							<?php endif ?>

							<?php if($access['IsRecurring']):?>
								<?php echo $this->lang->line('recurring');?>
							<?php endif ?>
						</td>
						<td style="vertical-align: middle;">
							<?php echo $access['StartDate']?>
						</td>
						<td style="vertical-align: middle;">
							<?php echo $access['Duration']?>
						</td>
						<td style="vertical-align: middle;">

							
							<div id="status_en_<?php echo $access['Id']?>" style="display:<?php echo ($access['Status'] == "ACTIVE"? '':'none')?>">
								<span class="square-8 bg-success mg-r-5 rounded-circle"></span> <?php echo $this->lang->line('active');?>
							</div>

							<div id="status_dis_<?php echo $access['Id']?>" style="display:<?php echo ($access['Status'] == "SUSPENDED"? '':'none')?>">
								<span class="square-8 bg-warning mg-r-5 rounded-circle"></span> <?php echo $this->lang->line('inactive');?>
							</div>

							<div id="status_cl_<?php echo $access['Id']?>" style="display:<?php echo ($access['Status'] == "CLOSED"? '':'none')?>">
								<span class="square-8 bg-pink mg-r-5 rounded-circle"></span> <?php echo $this->lang->line('deleted');?>
							</div>

						</td>
						<td>
							<div class="col" style="padding-top:0px;color:#FFF">
									<a id="en_<?php echo $access['Id']?>" style="display: <? echo ($access['Status'] == "ACTIVE")? '':'none'?>" href="javascript:void(0);" class="btn btn-danger" title="Suspend access" onclick="confirmSuspend('<?php echo $access['Id']?>')"><i class="fa fa-ban"></i></a>
									<a id="dis_<?php echo $access['Id']?>" style="display: <? echo ($access['Status'] == "SUSPENDED")? '':'none'?>" href="javascript:void(0);" class="btn btn-success" title="Enable access" onclick="confirmReenable('<?php echo $access['Id']?>')"><i class="fa fa-check"></i></a>
									<a class="btn-load btn btn-default" href='/AccessOrder/update?
										Reference=<?php echo $this->encrypt->encode($access['Id'])?>&
										pg=<?php echo $pg?>&
										BillingSystem=<?php echo $this->input->get('BillingSystem')?>'>
										<i class="fa fa-wrench"></i>
									</a>
									<?php if($access['IsRecurring']): ?>
									<a class="btn-load btn btn-secondary" href='/hierarchy/occurrences?
										Reference=<?php echo $this->encrypt->encode($access['Id'])?>&
										pg=<?php echo $pg?>&
										BillingSystem=<?php echo $this->input->get('BillingSystem')?>'>
										<i class="fa fa-calendar"></i>
									</a>
									<?php endif ?>
							</div>
						</td>
					</tr>
					<?php endforeach ?>
				<?php endif ?>
				<?php if(!isset($accesses) or !$accesses): ?>
				<tr>
					<td colspan="6">No organizations found</td>
				</tr>
				<?php endif ?>
			</tbody>
            </table>
          </div><!-- table-wrapper -->
       

	<div class="table-responsive">
		<table class="table mg-b-0">
			
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


<!-- CONFIRM SUSPEND MODAL -->
<div id="confirm_disable" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Are you sure?</h6>
				<div style="float:right">
					<i class="fa fa-exclamation"></i>
				</div>
			</div>
			<div class="modal-body pd-25">
				<p class="mg-b-5">Are you sure that you want to suspend this access?</p>
				<input type='hidden' id="disableAccessId">
			</div>
			<div class="modal-footer">
				<button onclick="suspend()" class="btn btn-danger">
					<i class="loadingSpin fa fa-circle-o-notch fa-spin" style="display:none"></i>
					<span class="yesProcessing">Yes</span>
				</button>
				<button class="btn btn-default" data-dismiss="modal">No</cutton>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->

<!-- CONFIRM RE-ENABLE MODAL -->
<div id="confirm_reenable" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Are you sure?</h6>
				<div style="float:right">
					<i class="fa fa-exclamation"></i>
				</div>
			</div>
			<div class="modal-body pd-25">
				<p class="mg-b-5">Are you sure that you want to re-enable this access?</p>
				<input type='hidden' id="reenableAccessId">
			</div>
			<div class="modal-footer">
				<button onclick="suspend()" class="btn btn-success">
					<i class="fa fa-circle-o-notch fa-spin" style="display:none"></i>Yes
				</button>
				<button class="btn btn-default" data-dismiss="modal">No</button>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->

<!-- ERROR MODAL -->
<div id="error_modal" class="modal fade">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?php echo $this->lang->line('error_happened');?></h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pd-25">
				<h4 class="lh-3 mg-b-20">
					<a href="./modal.html" class="tx-inverse hover-primary"><?php echo $this->lang->line('request_not_completed');?></a>
				</h4>
				<p class="mg-b-5">Sory, we could not process your request. Please, contact the customer service team for help.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary pd-x-20" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->

<script>

$(document).ready(function(){
	$('#datatable1').DataTable({
		dom: 'Bfrtip',
		bInfo : false,
		buttons: [
            {
                text: "<i class='fa fa-plus mg-r-10'></i><?php echo $this->lang->line('add_access');?>",
				className: 'btn btn-default',
                action: function ( e, dt, node, config ) {
                    window.location.href = "/AccessOrder/new?Reference=<?php echo $this->encrypt->encode($userId)?>&pg=<?php echo $pg?>&BillingSystem=<?php echo $this->input->get('BillingSystem')?>";
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

function confirmSuspend(accessId){
	$("#disableAccessId").val(accessId);
	$("#confirm_disable").modal("show");
}

function confirmReenable(accessId){
	$("#reenableAccessId").val(accessId);
	$("#confirm_disable").modal("show");
}

var intervalId;
var checks = 0;

function suspend(){
	// DISABLE ALL BUTTONS AND SHOW LOADING SPINS
	$(".btn").attr("disabled",true);
	$(".loadingSpin").show();
	$(".yesProcessing").html(" Processing");

	var data = {
		accessId: $("#disableAccessId").val(),
		billingSystem: '<?php echo $this->input->get('BillingSystem')?>', 
		primaryGroup: '<?php echo $this->input->get('primaryGroup')?>'
	};

	$.post("/OrderOrchestration/new/DISABLE-ACCESS", data, function(result){
		intervalId = setInterval(function () {
            try {
                getStatusDisable(result.orderId, $("#disableAccessId").val());
            } catch (e) {
                console.log(e);
            }
        }, 5000);
	})
	.fail(function(result){
		console.log(result);
	});
}

function getStatusDisable(orderId, accessId, type){
	checks = checks + 1;
	console.log(checks);
	$.get("/OrderOrchestration/getOrderStatus/" + orderId, function(result){
		if(result.status == "COMPLETED")
		{
			clearInterval(intervalId);			
			location.reload(); 
			checks = 0;
		}

		if(result.status == "ERROR" || checks > 20)
		{
			console.log("Timeout");
			$("#confirm_disable").modal("hide");
			$("#error_modal").modal("show");
			clearInterval(intervalId);
			// ENABLE ALL BUTTONS AND HIDE LOADING SPINS
			$(".yesProcessing").html("Yes");
			$(".btn").removeAttr("disabled");
			$(".loadingSpin").hide();
			checks = 0;
		}
	})
	.fail(function(result){
		if(checks > 20)
		{
			$("#confirm_disable").modal("hide");
			$("#error_modal").modal("show");
			clearInterval(intervalId);
			// ENABLE ALL BUTTONS AND HIDE LOADING SPINS
			$(".yesProcessing").html("Yes");
			$(".btn").removeAttr("disabled");
			$(".loadingSpin").hide();
			checks = 0;
		}
	});
}

</script>