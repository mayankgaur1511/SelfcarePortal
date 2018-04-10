

<!-- WARNING IF PASSWORDS DO NOT MATCH -->
<div class="alert alert-danger" role="alert" id="passwordMismatch" style="display:none">
	<div class="d-flex align-items-center justify-content-start">
		<i class="icon ion-ios-close alert-icon tx-24"></i>
		<span>
			<?php echo $this->lang->line('passwords_do_not_match'); ?>
		</span>
	</div>
</div>
<!-- END OF WARNING IF PASSWORDS DO NOT MATCH -->


<!-- WARNING IF PASSWORDS IS WRONG -->
<?php if(isset($wrong_password) and $wrong_password): ?>
    <div class="alert-danger alert" role="alert" id="passwordMismatch">
        <div class="d-flex align-items-center justify-content-start">
            <i class="icon ion-ios-close alert-icon tx-24"></i>
            <span>
                <?php echo $this->lang->line('passwords_do_not_match'); ?>
            </span>
        </div>
    </div>
<?php endif ?>
<!-- END OF WARNING IF PASSWORDS IS WRONG -->

<!-- SUCCESS IF PASSWORDS IS CHANGED -->
<?php if(isset($password_changed) and $password_changed): ?>
    <div class="alert-success alert" role="alert" id="passwordMismatch">
        <div class="d-flex align-items-center justify-content-start">
            <i class="icon ion-checkmark alert-icon tx-24"></i>
            <span>
                <?php echo $this->lang->line('password_changed'); ?>
            </span>
        </div>
    </div>
<?php endif ?>
<!-- END SUCCESS IF PASSWORDS IS CHANGED -->

<?php echo form_open('/authentication/changePassword'); ?>

<div class="card pd-20 pd-sm-40">

	<div class="form-layout">
		<div class="row mg-b-25">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Current password:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control" id="old_password" name="old_password" placeholder="Enter your current password" type="password">
				</div>
                <div class="form-group">
					<label class="form-control-label">New password:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control" id="new_password" name="new_password" placeholder="Enter your new password" type="password">
				</div>
                <div class="form-group">
					<label class="form-control-label">Confirm password:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control" id="confirm_new_password" placeholder="Enter your current password" type="password">
				</div>
                <button type="submit" id="btnChangePassword" class="btn btn-default btn-block">
                    <i class="fa fa-circle-o-notch fa-spin" style="display:none" id="loadingSpin"></i> Change password
                </button>
			</div>

			
		</div>
		<!-- row -->
	</div>
	<!-- form-layout -->
</div>
<?php echo form_close(); ?>

<script>

	$("#new_password, #confirm_new_password").on("keyup",function(){
		if($("#confirm_new_password").val() == $("#new_password").val()){
			$("#passwordMismatch").hide();
		}
	});


	$("#btnChangePassword").on("click",function(){
		if($("#confirm_new_password").val() != $("#new_password").val()){
			$("#passwordMismatch").show();
			return false;
		}
		else{
			$("#passwordMismatch").hide();
			$('#loadingSpin').show();
			$("input:password").attr('readonly',true);
			$(this).attr('disabled',true);
		}
	});

</script>