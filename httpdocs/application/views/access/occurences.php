<div class="card pd-20 pd-sm-40">
	<div class="table-wrapper">
		<table id="datatable1" class="table display responsive nowrap">
			<thead>
				<tr>
					<th><?php echo $this->lang->line('date_n_time')?></th>
					<th><?php echo $this->lang->line('duration')?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
                <?php foreach($occurences as $occurence): ?>
				<tr>
					<td>
						<?php echo date("d/m/Y H:i:s",strtotime($occurence['startDate']))?>
						(<?php echo date("D",strtotime($occurence['startDate']))?>)
					</td>
                    <td><?php echo $occurence['duration']?> <?php echo $this->lang->line('minutes')?></td>
					<td style="text-align:right">
						<button class="btn btn-secondary btn-icon mg-r-5 mg-b-10" 
							onclick="modifyModal('<?php echo $occurence['recurringId']?>',
												 '<?php echo date("Y-m-d H:i",strtotime($occurence['startDate']))?>',
												 '<?php echo $occurence['duration']?>')">
							<div>
								<i class="fa fa-pencil"></i>
							</div>
						</button>
					</td>
				</tr>
                <?php endforeach ?>
                <?php if(!isset($occurences) or !$occurences): ?>
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

<!-- BASIC MODAL -->
<div id="updateOccurrence_modal" class="modal fade">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?php echo $this->lang->line('update_recurring_instance');?></h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pd-25">
				
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('start_date_n_time');?>
						<span class="tx-danger">*</span>
					</label>
					<div class="input-group">
						  <input type="text" id="startDate" class="form-control form_datetime" placeholder="Enter the start time">
						  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
					</div>
				</div>
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('duration');?>
						<span class="tx-danger">*</span>
					</label>
					<div class="input-group">
						<input type="text" id="duration" name="duration" value="120" class="form-control" placeholder="<?php echo $this->lang->line('enter_conference_duration');?>">
						<span class="input-group-addon"><?php echo $this->lang->line('minutes');?></span>
					</div>
					<input type="hidden" id="instanceId" value="" class="form-control">
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary pd-x-20" data-dismiss="modal">Cancel</button>
				<button type="button" id="finishModify" class="btn btn-default pd-x-20" data-dismiss="modal">Confirm</button>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->


<script>

	$("#startDate").on("change, keyup", function(){
		// Check if Start Date is valid
		var expression = /(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/;
		if(!expression.test($("#startDate").val()))
		{
			$(this).addClass("is-invalid");
		}
		else{
			$(this).removeClass("is-invalid");
		}
	});

	function modifyModal(instanceId, startDate, duration)
	{
		$("#instanceId").val(instanceId);
		$("#startDate").val(startDate);
		$("#duration").val(duration);
		$("#updateOccurrence_modal").modal("show");
	}

	$(function(){
		$(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
	});

	$(document).ready(function () {
		$('#datatable1').DataTable({
			dom: 'Bfrtip',
			bInfo: false,
			buttons: [{
				text: "<i class='fa fa-plus mg-r-10'></i><?php echo $this->lang->line('add_occurrence');?>",
				className: 'btn btn-default',
				action: function (e, dt, node, config) {
					window.location.href =
						"/AccessOrder/newOccurences?Reference=<?php echo $this->input->get('Reference')?>&BillingSystem=<?php echo $this->input->get('BillingSystem')?>";
				}
			}],
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

	function validate() {
		// Mandatory fields
		var data = {
			instanceId: $("#instanceId").val(),
			billingSystem: "<?php echo $this->input->get('BillingSystem')?>",
			accessId: "<?php echo $this->input->get('Reference')?>",
			startDate: $("#startDate").val(),
			duration: $("#duration").val(),
		};
			
		
		// Validate fields
		var invalidFields = [];
		for (var field in data) {
			if (!data[field]) {
				if ($("#" + field).prop("nodeName") == "SELECT") {
					$("#" + field).parent().addClass("has-danger");
				} else {
					$("#" + field).addClass("has-danger");
				}
				invalidFields.push(field);
			} else {
				if ($("#" + field).prop("nodeName") == "SELECT") {
					$("#" + field).parent().removeClass("has-danger");
				} else {
					$("#" + field).removeClass("has-danger");
				}
			}
		}
		
		// Check if Duration is INT type
		if(Math.floor($("#duration").val()) != $("#duration").val() || $.isNumeric($("#duration").val()) != true)
		{
			invalidFields.push('duration');
		}

		// Check if Start Date is valid
		var expression = /(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/;
		if(!expression.test($("#startDate").val()))
		{	
			console.log($("#startDate").val() + " Invalid date");
			invalidFields.push('startDate');
		}
		else{
			var now = new Date();
			console.log(now);
			if($("#startDate").val() < now) {
				console.log("Selected date is in the past");
			}
		}

		if (invalidFields.length) {
			console.log(invalidFields);
			$("#formValidation").modal("show");
			return false;
		} else {
			return data;
		}
	}

	$("#finishModify").click(function createOrder() {
		var data = validate();
		// CREATE ORDER
		$("#finish").html("<i class='fa fa-circle-o-notch fa-spin' aria-hidden='true'></i> Processing");
		$.post("/OrderOrchestration/new/MODIFY-OCCURRENCE", data, function (result) {
				console.log(result);
				//window.location.href = "/orderTracking/";
			})
			.fail(function (result) {
				console.log(result);
				$("#finish").html("finish");
			});
	});

</script>