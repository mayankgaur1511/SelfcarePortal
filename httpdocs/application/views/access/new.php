<div class="card pd-20 pd-sm-40">

	<div class="form-layout-footer" style="float: right">
		<button type="button" id="step1" class="btn btn-info">1. <?php echo $this->lang->line('access_details');?></button>
		<button type="button" id="step2" class="btn btn-info btn-info-disabled">2. <?php echo $this->lang->line('conference_settings');?></button>
		<BR>
	</div>

	<div class="form-layout" id="userDetails">
		<hr/>
		<div class="row mg-b-25">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('conference_type');?>
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2" data-placeholder="Select the conference Type" id="confType">
						<option value="demand"><?php echo $this->lang->line('demand');?></option>
						
						<?php if(($this->encrypt->decode($billingSystem) == "BlueSky" and $this->session->userdata('demandAccesses')) or 
							$this->encrypt->decode($billingSystem) == "Wise2"):?>
							<option value="scheduled"><?php echo $this->lang->line('scheduled_conference');?></option>
						<?php endif ?>

						<?php if($this->encrypt->decode($billingSystem) == "BlueSky"):?>
						<option value="recurring"><?php echo $this->lang->line('recurring_conference');?></option>
						<?php endif ?>
					</select>
				</div>
			</div>

			<div class="col-lg-4" id="demandAccessDiv" style="display: none;">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('demand_conference');?>
						<span class="tx-danger">*</span>
					</label>
					<div class="input-group">
						<select class="form-control select2" data-placeholder="Select the demand access" id="demandAccess">
							<?php foreach($this->session->userdata('demandAccesses') as $access): ?>
								<option value="<?php echo $this->encrypt->encode($access['Id'])?>"><?php echo $access['conferenceRef']?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
			</div>

			<div class="col-lg-4" id="startDateDiv" style="display: none;">
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

			<div class="col-lg-4" id="endDateDiv" style="display: none;">
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

			<div class="col-lg-4" id="recurringTypeDiv" style="display: none;">
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


			<div class="col-lg-4 otp" id="durationDiv" style="display: none;">
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
			
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('conference_title');?>
						<span class="tx-danger">*</span>
					</label>
					<input type="text" id="conferenceTitle" name="conferenceTitle" class="form-control" placeholder="<?php echo $this->lang->line('enter_conference_title');?>">
				</div>
			</div>

			<?php if($this->encrypt->decode($billingSystem) == "Wise2"):?>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('country');?>
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2-show-search" data-placeholder="Select the country" id="countryId" name="countryId">
						<?php foreach($countries as $country): ?>
						<option value="<?php echo $country->id?>">
							<?php echo $this->lang->line($country->countryName)?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('timezone');?>
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2-show-search" data-placeholder="<?php echo $this->lang->line('select_timezone');?>" id="timezoneId" name="timezoneId">
						<?php foreach($tmzs as $tmz): ?>
						<option value="<?php echo $tmz->id?>">
							<?php echo $tmz->timeZoneName?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group" id="fg_languageId">
					<label class="form-control-label"><?php echo $this->lang->line('language');?>
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2-show-search" data-placeholder="<?php echo $this->lang->line('select_language');?>" id="languageId" name="languageId">
						<?php foreach($languages as $language): ?>
						<option value="<?php echo $language->id?>">
							<?php echo $this->lang->line($language->language)?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<?php endif ?>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('billing_code_cc');?>
					</label>
					<input type="text" id="billingCode" name="billingCode" class="form-control" placeholder="<?php echo $this->lang->line('enter_billing_code');?>">
				</div>
			</div>

			<?php if(isset($products) and $products): ?>
			<div class="col-lg-4">
				<div class="form-group" id="fg_languageId">
					<label class="form-control-label"><?php echo $this->lang->line('conferencing_services');?>
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2" data-placeholder="Select a service" id="product" name="product">
						<?php foreach($products as $product): ?>
						<option value="<?php echo $product['value']?>">
							<?php echo $product['name']?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<?php endif ?>
		</div>

		<!-- row -->
		<div class="form-layout-footer" style="float: right" id="firstPage">
			<button type="button" onclick="window.history.back();" class="btn btn-secondary"><?php echo $this->lang->line('cancel');?></button>
			<button type="button" id="nextPage" class="btn btn-default"><?php echo $this->lang->line('next');?>
				<i class="fa fa-arrow-right"></i>
			</button>
		</div>
		<!-- form-layout-footer -->
	</div>
	<!-- form-layout -->

	<div class="form-layout" id="conferenceSettings" style="display:none">
		<hr/>
		<?php if($this->encrypt->decode($billingSystem) == "Wise2"): ?>
		<div class="row mg-b-25">
			<?php foreach($audioOptions as $option): ?>
			<div class="col-lg-3">
				<div class="form-group">
					<label class="form-control-label">
						<?php echo $this->lang->line($option->OptionCode);?>
					</label>
					<?php if($option->OptionType == "CHECKBOX"):?>
					<select id="<?php echo $option->key?>" class="form-control select2">
						<option <?php echo ($option->DefaultValue == "true"? "Selected='true'":"") ?> value="true"><?php echo $this->lang->line('yes');?></option>
						<option <?php echo ($option->DefaultValue == "false"? "Selected='true'":"") ?>value="false"><?php echo $this->lang->line('no');?></option>
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
						<?php echo $this->lang->line("number_participants");?>
					</label>
					<input id="participants" type="number" value="50" class="form-control">
				</div>
			</div>
		</div>
		<?php endif ?>

		<?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
		<?php foreach($products as $product): ?>
		<div class="row mg-b-25" id="sub_<?php echo $product['value']?>" style="display:none">
			<?php foreach($product['options'] as $options): ?>
			<?php foreach($options as $option): ?>
			<div class="col-lg-3">
				<div class="form-group">
					<label class="form-control-label">
						<?php echo $option->key?>
					</label>

					<?php if($option->OptionType == "CHECKBOX"):?>
					<select id="sub_<?php echo $product['value']?>_<?php echo $option->key?>" class="form-control select2">
						<option <?php echo ($option->DefaultValue == "true"? "Selected='true'":"") ?> value="true"><?php echo $this->lang->line('yes');?></option>
						<option <?php echo ($option->DefaultValue == "false"? "Selected='true'":"") ?>value="false"><?php echo $this->lang->line('no');?></option>
					</select>
					<?php endif ?>

					<?php if($option->key == "muteOnEntry"):?>
					<select id="sub_<?php echo $product['value']?>_<?php echo $option->key?>" class="form-control select2">
						<option <?php echo ($option->DefaultValue == "NotActive"? "Selected":"") ?> value="NotActive"><?php echo $this->lang->line('NotActive');?></option>
						<option <?php echo ($option->DefaultValue == "SoftMute"? "Selected":"") ?> value="SoftMute"><?php echo $this->lang->line('SoftMute');?></option>
						<option <?php echo ($option->DefaultValue == "HardMute"? "Selected":"") ?> value="HardMute"><?php echo $this->lang->line('HardMute');?></option>
					</select>
					<?php endif ?>

					<?php if($option->key == "audioBillingCode"):?>
					<select id="sub_<?php echo $product['value']?>_<?php echo $option->key?>" class="form-control select2">
						<option <?php echo ($option->DefaultValue == "Mandatory"? "Selected":"") ?> value="Mandatory"><?php echo $this->lang->line('Mandatory');?></option>
						<option <?php echo ($option->DefaultValue == "Optional"? "Selected":"") ?> value="Optional"><?php echo $this->lang->line('Optional');?></option>
						<option <?php echo ($option->DefaultValue == "Not_Collected"? "Selected":"") ?> value="Not_Collected"><?php echo $this->lang->line('Not_Collected');?></option>
					</select>
					<?php endif ?>

					<?php if($option->OptionType == "TEXTBOX" and $option->key != "muteOnEntry" and $option->key != "audioBillingCode"):?>
					<input id="sub_<?php echo $product['value']?>_<?php echo $option->key?>" type="number" value="<?php echo $option->DefaultValue ?>"
					class="form-control">
					<?php endif ?>
				</div>
			</div>
			<?php endforeach ?>
			<?php endforeach ?>
		</div>
		<?php endforeach ?>
		<?php endif ?>

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
	var countryCodes = {
		<?php foreach($countries as $country): ?>
		<?php echo $country->id;?>: "<?php echo $country->phoneCode;?>",
		<?php endforeach ?>
	};

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

	$("#confType").change(function(){
		if($(this).val() == 'scheduled' || $(this).val() == 'recurring'){
			$("#startDateDiv").show();
			$("#durationDiv").show();
		}
		else{
			$("#startDateDiv").hide();
			$("#durationDiv").hide();
		}

		<?php if($this->encrypt->decode($billingSystem) == "BlueSky" and $this->session->userdata('demandAccesses') 
			and sizeof($this->session->userdata('demandAccesses')) > 1): ?>
			if($(this).val() == 'scheduled'){
				$("#demandAccessDiv").show();
			}
			else{
				$("#demandAccessDiv").hide();
			}
		<?php endif ?>

		if($(this).val() == 'recurring'){
			$("#recurringTypeDiv").show();
			$("#endDateDiv").show();
		}
		else{
			$("#recurringTypeDiv").hide();
			$("#weekDaysDiv").hide();
			$("#endDateDiv").hide();
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

	$("#countryId").change(function () {
		$("#phoneCode").val("+" + countryCodes[$("#countryId").val()]);
	});

	$("#nextPage, #step2").click(function () {
		if (validate()) {
			<?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
			$("#sub_" + $('#product').val()).show();
			<?php endif ?>

			$("#step1").addClass("btn-info-disabled");
			$("#step2").removeClass("btn-info-disabled");
			
			$("#userDetails").hide();
			$("#conferenceSettings").show();
		}
	});

	$("#backPage, #step1").click(function () {
		$("#userDetails").show();
		$("#conferenceSettings").hide();
		<?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
		$("#sub_" + $('#product').val()).show();
		<?php endif ?>
		$("#step2").addClass("btn-info-disabled");
		$("#step1").removeClass("btn-info-disabled");
	});

	function validate() {
		// Mandatory fields
		var data = {
			conferenceType: $("#confType").val(),
			conferenceTitle: $("#conferenceTitle").val(),

			<?php if($this->encrypt->decode($billingSystem) == "Wise2"): ?>
				countryId: $("#countryId").val(),
				languageId: $("#languageId").val(),
				timezoneId: $("#timezoneId").val(),
			<?php endif ?>

			product: $("#product").val(),
			userId: "<?php echo $userId ?>",
			primaryGroup: "<?php echo $primaryGroup ?>",
			billingSystem: "<?php echo $billingSystem ?>",
			<?php if(isset($spiritId) and $spiritId):?>
			spirit: "<?php echo $spiritId ?>"
			<?php endif ?>
		};

		<?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
			if($("#confType").val() == "scheduled"){
				data.demandAccess = $("#demandAccess").val();
			}	
			
			if($("#confType").val() == "recurring" && ($("#recurringType").val() == "weekly" || $("#recurringType").val() == "biweekly")){
				data.weekdays = $("#weekdays").val();
				data.recurringType = $("#recurringType").val();
			}
			
			if($("#confType").val() == "recurring"){
				data.endDate = $("#endDate").val();
			}
			
		<?php endif ?>

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
		data.billingCode = $("#billingCode").val();
		data.participants = $("#participants").val();

		<?php if($this->encrypt->decode($billingSystem) == "Wise2"): ?>
		data.audioOptions = {
			<?php foreach($audioOptions as $option): ?>
			<?php echo $option->key?>: $("#<?php echo $option->key?>").val(),
			<?php endforeach ?>
		}
		<?php endif ?>


		<?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
		data.audioOptions = {
			<?php foreach($products as $product): ?>
			<?php foreach($product['options'] as $options): ?>
			<?php foreach($options as $option): ?>
			<?php echo $option->key?>: $("#sub_" + $('#product').val() + "_<?php echo $option->key?>").val(),
			<?php endforeach ?>
			<?php break; ?>
			<?php endforeach ?>
			<?php endforeach ?>
		}
		<?php endif ?>

		// CREATE ORDER
		$.post("/OrderOrchestration/new/NEW-ACCESS", data, function (result) {
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
