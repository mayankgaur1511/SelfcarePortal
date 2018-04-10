<div class="card pd-20 pd-sm-40">
	<div class="form-layout" id="userDetails">
		<hr/>
		<div class="row mg-b-25">
			<div class="col-lg-4" id="startDateDiv">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('start_date_n_time');?>
						<span class="tx-danger">*</span>
					</label>
					<div class="input-group">
						  <input type="text" id="startDate" class="form-control form_datetime" placeholder="Enter the start time">
						  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
					</div>
				</div>
			</div>

			<div class="col-lg-4" id="endDateDiv">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('end_date_n_time');?>
						<span class="tx-danger">*</span>
					</label>
					<div class="input-group">
						  <input type="text" id="endDate" class="form-control form_datetime" placeholder="<?php echo $this->lang->line('enter_end_date_n_time');?>">
						  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
					</div>
				</div>
			</div>

			<div class="col-lg-4" id="recurringTypeDiv">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('recurring_type');?>
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2" data-placeholder="Select the conference Type" id="recurringType">
						<option value="daily"><?php echo $this->lang->line('daily');?></option>
						<option value="weekly"><?php echo $this->lang->line('weekly');?></option>
						<option value="biweekly"><?php echo $this->lang->line('biweekly');?></option>
					</select>
				</div>
			</div>

			<div class="col-lg-4" id="weekDaysDiv" style="display: none;">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('week_days');?>
						<span class="tx-danger">*</span>
					</label>
					<select multiple class="form-control select2" id="weekdays" data-placeholder="Select the week days" id="recurringType">
						<option value="Sun"><?php echo $this->lang->line('sunday');?></option>
						<option value="Mon"><?php echo $this->lang->line('monday');?></option>
						<option value="Tue"><?php echo $this->lang->line('tuesday');?></option>
						<option value="Wed"><?php echo $this->lang->line('wednesday');?></option>
						<option value="Thu"><?php echo $this->lang->line('thursday');?></option>
						<option value="Fri"><?php echo $this->lang->line('friday');?></option>
						<option value="Sat"><?php echo $this->lang->line('saturday');?></option>
					</select>
				</div>
			</div>


			<div class="col-lg-4 otp" id="durationDiv">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('duration');?>
						<span class="tx-danger">*</span>
					</label>
					<div class="input-group">
						<input type="text" id="duration" name="duration" value="120" class="form-control" placeholder="<?php echo $this->lang->line('enter_conference_duration');?>">
						<span class="input-group-addon"><?php echo $this->lang->line('minutes');?></span>
					</div>
				</div>
			</div>
		</div>

		<!-- row -->
		<div class="form-layout-footer" style="float: right" id="firstPage">
			<button type="button" onclick="window.history.back();" class="btn btn-secondary"><?php echo $this->lang->line('cancel');?></button>
			<button type="button" id="finish" class="btn btn-default"><?php echo $this->lang->line('finish');?></button>
		</div>
		<!-- form-layout-footer -->
	</div>
	<!-- form-layout -->
</div>

<!-- BASIC MODAL -->
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
				<p class="mg-b-5">We could not process your request, Please, try again later and if the issue persists, contact the customer service team
					for help.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary pd-x-20" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->

<!-- BASIC MODAL -->
<div id="formValidation" class="modal fade">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?php echo $this->lang->line('invalid_information');?></h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pd-25">
				<h4 class="lh-4 mg-b-20">Some of the information that you entered is invalid.</h4>
				<p class="mg-b-5">Fields in error are highlighted in red. Note that all mandatory fields are followed by an asterisk. For help, contact
					the customer service.</p>
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
	$("#duration").on("change, keyup", function(){
		if(Math.floor($(this).val()) != $(this).val() || $.isNumeric($(this).val()) != true)
		{
			$(this).addClass("is-invalid");
		}
		else{
			$(this).removeClass("is-invalid");
		}
	});

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

	$("#recurringType").change(function(){
		if($(this).val() == 'weekly' || $(this).val() == 'biweekly'){
			$("#weekDaysDiv").show();
			
		}
		else{
			$("#weekDaysDiv").hide();
		}
	});


	$(function(){
		$(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
	});

	function validate() {
		// Mandatory fields
		var data = {
			billingSystem: "<?php echo $billingSystem ?>",
			accessId: "<?php echo $this->input->get('Reference')?>",
			startDate: $("#startDate").val(),
			endDate: $("#endDate").val(),
			duration: $("#duration").val(),
			recurringType: $("#recurringType").val(),
		};
			
		if( $("#recurringType").val() == "weekly" || $("#recurringType").val() == "biweekly"){
			data.weekdays = $("#weekdays").val();	
		}	

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
		if($("#confType").val() == "scheduled" || $("#confType").val() == "recurring"){
			data.duration = $("#duration").val();
			data.startDate = $("#startDate").val();

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
  				//now.setMinutes(now.getMinutes() + 15);
				
				
				if($("#startDate").val() < now) {
    				console.log("Selected date is in the past");
				}
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

	$("#finish").click(function createOrder() {
		var data = validate();
		// CREATE ORDER
		$("#finish").html("<i class='fa fa-circle-o-notch fa-spin' aria-hidden='true'></i> Processing");
		$.post("/OrderOrchestration/new/NEW-OCCURRENCES", data, function (result) {
				console.log(result);
				window.location.href = "/orderTracking/"
			})
			.fail(function (result) {
				console.log(result);
				$("#finish").html("finish");
			});
	});

</script>
