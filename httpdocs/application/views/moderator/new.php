<div class="card pd-20 pd-sm-40">
	<div class="form-layout-footer" style="float: right">
		<button type="button" id="step1" class="btn btn-info">1. <?php echo $this->lang->line('user_details');?></button>
		<button type="button" id="step2" class="btn btn-info btn-info-disabled">2. <?php echo $this->lang->line('conference_settings');?></button>
		<BR>
	</div>
	<div class="form-layout" id="userDetails">
		<hr/>
		<div class="row mg-b-25">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('first_name');?>
						<span class="tx-danger">*</span>
					</label>
					<input type="text" id="firstName" name="firstName" class="form-control" placeholder="<?php echo $this->lang->line('enter_first_name');?>">
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('last_name');?>
						<span class="tx-danger">*</span>
					</label>
					<input type="text" id="lastName" name="lastName" class="form-control" placeholder="<?php echo $this->lang->line('enter_last_name');?>">
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('email_address');?>
						<span class="tx-danger">*</span>
					</label>
					<input type="text" id="email" name="email" class="form-control" placeholder="<?php echo $this->lang->line('enter_email');?>">
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('country');?>
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2-show-search" data-placeholder="<?php echo $this->lang->line('select_country');?>" id="countryId" name="countryId">
						<option></option>
						<?php foreach($countries as $country): ?>
						<option value="<?php echo $country->id?>">
							<?php echo $country->countryName?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('phone');?>
						<span class="tx-danger">*</span>
					</label>
					<div class="row">
						<div class="col-lg-3" style="padding-right:0px;">
							<input type="text" class="form-control" placeholder="" readonly="true" id="phoneCode">
						</div>
						<div class="col">
							<input type="text" id="phone" name="phone" class="form-control" placeholder="<?php echo $this->lang->line('phone_number');?>">
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('timezone');?>
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2-show-search" data-placeholder="<?php echo $this->lang->line('select_timezone');?>" id="timezoneId" name="timezoneId">
						<option></option>
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
							<?php echo $language->language?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label"><?php echo $this->lang->line('billing_code');?>
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
							<?php echo $option->OptionCode?>
						</label>
						<?php if($option->OptionType == "CHECKBOX"):?>
							<select id="<?php echo $option->key?>" class="form-control select2">
								<option <?php echo ($option->DefaultValue == "true"? "Selected='true'":"") ?> value="true"><?php echo $this->lang->line('Yes');?></option>
								<option <?php echo ($option->DefaultValue == "false"? "Selected='true'":"") ?>value="false"><?php echo $this->lang->line('No');?></option>
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
											<option <?php echo ($option->DefaultValue == "true"? "Selected='true'":"") ?> value="true"><?php echo $this->lang->line('Yes');?></option>
											<option <?php echo ($option->DefaultValue == "false"? "Selected='true'":"") ?>value="false"><?php echo $this->lang->line('No');?></option>
										</select>
									<?php endif ?>

									<?php if($option->key == "muteOnEntry"):?>
										<select id="sub_<?php echo $product['value']?>_<?php echo $option->key?>" class="form-control select2">
											<option <?php echo ($option->DefaultValue == "NotActive"? "Selected='true'":"") ?> value="NotActive"><?php echo $this->lang->line('NotActive');?></option>
											<option <?php echo ($option->DefaultValue == "SoftMute"? "Selected='true'":"") ?> value="SoftMute"><?php echo $this->lang->line('SoftMute');?></option>
											<option <?php echo ($option->DefaultValue == "HardMute"? "Selected='true'":"") ?> value="HardMute"><?php echo $this->lang->line('HardMute');?></option>
										</select>
									<?php endif ?>

									<?php if($option->key == "audioBillingCode"):?>
										<select id="sub_<?php echo $product['value']?>_<?php echo $option->key?>" class="form-control select2">
											<option <?php echo ($option->DefaultValue == "Mandatory"? "Selected='true'":"") ?> value="Mandatory"><?php echo $this->lang->line('Mandatory');?></option>
											<option <?php echo ($option->DefaultValue == "Optional"? "Selected='true'":"") ?> value="Optional"><?php echo $this->lang->line('Optional');?></option>
											<option <?php echo ($option->DefaultValue == "Not_Collected"? "Selected='true'":"") ?> value="Not_Collected"><?php echo $this->lang->line('Not_Collected');?></option>
										</select>
									<?php endif ?>

									<?php if($option->OptionType == "TEXTBOX" and $option->key != "muteOnEntry" and $option->key != "audioBillingCode"):?>
										<input id="sub_<?php echo $product['value']?>_<?php echo $option->key?>" type="number" value="<?php echo $option->DefaultValue ?>" class="form-control">
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

	$("#countryId").change(function () {
		$("#phoneCode").val("+" + countryCodes[$("#countryId").val()]);
	});

	$("#nextPage, #step2").click(function(){
		if(validate())
		{
			<?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
				$("#sub_" + $('#product').val()).show();
			<?php endif ?>

			$("#userDetails").hide();
			$("#conferenceSettings").show();

			$("#step1").addClass("btn-info-disabled");
			$("#step2").removeClass("btn-info-disabled");
		}
	});

	$("#backPage, #step1").click(function(){
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
			firstName: $("#firstName").val(),
			lastName: $("#lastName").val(),
			email: $("#email").val(),
			phone: $("#phone").val(),
			countryId: $("#countryId").val(),
			languageId: $("#languageId").val(),
			timezoneId: $("#timezoneId").val(),
			product: $("#product").val(),
			primaryGroup: "<?php echo $primaryGroup ?>",
			billingSystem: "<?php echo $billingSystem ?>",
			<?php if(isset($spiritId)):?>
			spirit: "<?php echo $spiritId ?>"
			<?php endif ?>
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

		if (invalidFields.length) {
			$("#formValidation").modal("show");
			return false;
		} else {
			return data;
		}
	}

	$("#finish").click(function createOrder()
	{
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
		$("#finish").html("<i class='fa fa-circle-o-notch fa-spin' aria-hidden='true'></i> Processing");

		// CREATE ORDER
		$.post("/OrderOrchestration/new/NEW-USER", data, function (result) {
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
