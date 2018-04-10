<div class="card pd-20 pd-sm-40">
	<div class="form-layout" id="userDetails">
		<h6 class="card-body-title">User details</h6>
		<hr/>
		<div class="row mg-b-25">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">First name
						<span class="tx-danger">*</span>
					</label>
					<input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $user['firstName']?>" placeholder="Enter the first name">
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Last name
						<span class="tx-danger">*</span>
					</label>
					<input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $user['lastName']?>" placeholder="Enter the last name">
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">E-mail address
						<span class="tx-danger">*</span>
					</label>
					<input type="text" id="email" name="email" class="form-control" value="<?php echo $user['email']?>" placeholder="Enter the e-mail">
				</div>
            </div>
            
            <?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Country
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2-show-search" data-placeholder="Select the country" id="countryId" name="countryId">
						<?php foreach($countries as $country): ?>
						<option value="<?php echo $country->id?>" <?php echo ($country->id == $user['country']? 'selected':'') ?>>
							<?php echo $country->countryName?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
            </div>
            <?php endif ?>
            
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Phone
						<span class="tx-danger">*</span>
					</label>
					<div class="row">
                        <?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
                            <div class="col-lg-3" style="padding-right:0px;">
                                <input type="text" class="form-control" placeholder="" readonly="true" id="phoneCode">
                            </div>
                        <?php endif ?>
						<div class="col">
							<input type="text" id="phone" name="phone" class="form-control" value="<?php echo $user['phone']?>" placeholder="Phone number">
						</div>
					</div>
				</div>
            </div>
            
            <?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Timezone
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2-show-search" data-placeholder="Select the timezone" id="timezoneId" name="timezoneId">
						<?php foreach($tmzs as $tmz): ?>
						<option value="<?php echo $tmz->id?>" <?php echo ($tmz->id == $user['timezone']? 'selected':'') ?>>
							<?php echo $tmz->timeZoneName?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group" id="fg_languageId">
					<label class="form-control-label">Language
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2-show-search" data-placeholder="Select the language" id="languageId" name="languageId">
						<?php foreach($languages as $language): ?>
						<option value="<?php echo $language->id?>" <?php echo ($language->id == $user['language']? 'selected':'') ?>>
							<?php echo $language->language?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
            </div>
            <?php endif ?>
		</div>

		<!-- row -->
		<div class="form-layout-footer" style="float: right" id="firstPage">
			<button type="button" onclick="window.history.back();" class="btn btn-secondary">Cancel</button>
			<button type="button" id="finish" class="btn btn-default">Submit</button>
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

    <?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
        $(document).ready(function(){
            $("#phoneCode").val("+" + countryCodes[$("#countryId").val()]);
        });
    <?php endif ?>

	function validate() {
		// Mandatory fields
		var data = {
            userId: "<?php echo $userId ?>",
			firstName: $("#firstName").val(),
			lastName: $("#lastName").val(),
			email: $("#email").val(),
            phone: $("#phone").val(),
            <?php if($this->encrypt->decode($billingSystem) == "BlueSky"): ?>
			countryId: $("#countryId").val(),
			languageId: $("#languageId").val(),
            timezoneId: $("#timezoneId").val(),
            <?php endif ?>
			primaryGroup: "<?php echo $primaryGroup ?>",
			billingSystem: "<?php echo $billingSystem ?>",
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
		// CREATE ORDER
		$.post("/OrderOrchestration/new/MODIFY-USER", data, function (result) {
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
		});
	});

</script>
