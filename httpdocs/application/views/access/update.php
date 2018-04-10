<div class="card pd-20 pd-sm-40">
	<div class="form-layout" id="userDetails">
		<h6 class="card-body-title">Access details</h6>
		<hr/>
		<div class="row mg-b-25">
			<?php if($access['isScheduled']): ?>
				<div class="col-lg-4" id="startDateDiv">
					<div class="form-group">
						<label class="form-control-label">Start date & time
							<span class="tx-danger">*</span>
						</label>
						<div class="input-group">
							<input type="text" id="startDate" class="form-control form_datetime" value="<?php echo date('Y-m-d H:i',strtotime($access['startDate']))?>" placeholder="Enter the start time">
							<span class="input-group-addon">
								<i class="icon ion-calendar tx-16 lh-0 op-6"></i>
							</span>
						</div>
					</div>
				</div>

				<div class="col-lg-4 otp" id="durationDiv">
					<div class="form-group">
						<label class="form-control-label">Duration
							<span class="tx-danger">*</span>
						</label>
						<div class="input-group">
							<input type="text" id="duration" name="duration" value="<?php echo $access['duration']?>" class="form-control" placeholder="Enter the conference duration">
							<span class="input-group-addon">minutes</span>
						</div>
					</div>
				</div>
			<?php endif ?>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Conference Title
						<span class="tx-danger">*</span>
					</label>
					<input type="text" id="conferenceTitle" name="conferenceTitle" value="<?php echo $access['conferenceTitle']?>" class="form-control" placeholder="Enter the conference title">
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Billing Code/Cost Center
					</label>
					<input type="text" id="billingCode" name="billingCode" class="form-control" placeholder="Enter the the billing code">
				</div>
			</div>
		</div>

		<!-- row -->
		<div class="form-layout-footer" style="float: right" id="firstPage">
			<button type="button" onclick="window.history.back();" class="btn btn-secondary">Cancel</button>
			<button type="button" id="nextPage" class="btn btn-default">Next
				<i class="fa fa-arrow-right"></i>
			</button>
		</div>
		<!-- form-layout-footer -->
	</div>
	<!-- form-layout -->

	<div class="form-layout" id="conferenceSettings" style="display:none">
		<h6 class="card-body-title">Conference Settings</h6>
		<hr/>
		
		<div class="row mg-b-25">
			<?php foreach($audioOptions as $option): ?>
			<div class="col-lg-3">
				<div class="form-group">
					<label class="form-control-label">
						<?php echo $option->key?>
					</label>
					<?php if($option->OptionType == "CHECKBOX"):?>
					<select id="<?php echo $option->key?>" class="form-control select2">
						<option <?php echo ($option->DefaultValue == "true"? "Selected='true'":"") ?> value="true">Yes</option>
						<option <?php echo ($option->DefaultValue == "false"? "Selected='true'":"") ?>value="false">No</option>
					</select>
					<?php endif ?>
					<?php if($option->OptionType == "TEXTBOX"):?>
					<input id="<?php echo $option->key?>" type="number" value="<?php echo $option->DefaultValue ?>" class="form-control">
					<?php endif ?>
				</div>
			</div>
			<?php endforeach ?>
			<div class="col-lg-3">
				<div class="form-group">
					<label class="form-control-label">
						Number of participants
					</label>
					<input id="participants" type="number" value="50" class="form-control">
				</div>
			</div>
		</div>

		<!-- row -->
		<div class="form-layout-footer" style="float: right">
			<button type="button" id="backPage" class="btn btn-secondary">
				<i class="fa fa-arrow-left"></i> Back</button>
			<button id="finish" type="button" class="btn btn-default mg-r-5">Finish</button>
		</div>
		<!-- form-layout-footer -->
	</div>
	<!-- form-layout -->
</div>


<script>

	$("#nextPage").click(function () {
		if(validate()) {
			$("#userDetails").hide();
			$("#conferenceSettings").show();
		}
	});

	$("#backPage").click(function () {
		$("#userDetails").show();
		$("#conferenceSettings").hide();
	});

	function validate() {
		// Mandatory fields
		var data = {
			accessId: "<?php echo $this->input->get('Reference') ?>",
			conferenceTitle: $("#conferenceTitle").val(),
			primaryGroup: "<?php echo $primaryGroup ?>",
			billingSystem: "<?php echo $billingSystem ?>",
			conferenceType: "<?php echo ($access['isScheduled'] ? 'scheduled':'demand') ?>",
		};

		// Validate fields
		var invalidFields = [];
		for (var field in data) {
			if (!data[field]) {
				console.log(field);
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

		<?php if($access['isScheduled']): ?>
			data.duration = $("#duration").val();
			data.startDate = $("#startDate").val();

			if (Math.floor($("#duration").val()) != $("#duration").val() || $.isNumeric($("#duration").val()) != true) {
				invalidFields.push('duration');
			}

			// Check if Start Date is valid
			var expression = /(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/;
			if (!expression.test($("#startDate").val())) {
				console.log($("#startDate").val() + " Invalid date");
				invalidFields.push('startDate');
			}
		
		<?php endif ?>

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
		data.billingCode = $("#billingCode").val();
		data.participants = $("#participants").val();

		data.audioOptions = {
			<?php foreach($audioOptions as $option): ?>
			<?php echo $option->key?>: $("#<?php echo $option->key?>").val(),
			<?php endforeach ?>
		}

		// CREATE ORDER
		$("#finish").html("<i class='fa fa-circle-o-notch fa-spin' aria-hidden='true'></i> Processing");
		$.post("/OrderOrchestration/new/MODIFY-ACCESS", data, function (result) {
				console.log(result);
				$("#requestReference").html(result.orderId);
				$("#processingModal").modal("show");
				$("#finish").html("Finish");
				intervalId = setInterval(function () {
					try {
						getStatus(result.orderId);
					} catch (e) {
						console.log(e);
					}
				}, 5000);
			})
			.fail(function (result) {
				console.log(result);
				$("#finish").html("Finish");
			});
	});
	

</script>
