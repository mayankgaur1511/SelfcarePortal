<?php echo form_open('/reset-password-request'); ?>

<?php if(validation_errors()): ?>
<div class="alert alert-danger" role="alert">
	<div class="d-flex align-items-center justify-content-start">
		<i class="icon ion-ios-close alert-icon tx-24"></i>
		<span>
			<?php echo $this->lang->line('user_not_filled'); ?>
		</span>
	</div>
</div>
<?php endif ?>


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


<div class="form-group">
	<label class="form-control-label">New password:</label>
	<input type="password" name="password" id="password" placeholder="Enter your new password" class="form-control <?php echo form_error('password')? 'has-danger':''?>"/>
</div>
<div class="form-group">
	<label class="form-control-label">Confirm password:</label>
	<input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm your new password" class="form-control <?php echo form_error('confirmPassword')? 'has-danger':''?>"/>
</div>
<button id="btnResetPassword" class="btn btn-danger login-button btn-block"><i class="fa fa-circle-o-notch fa-spin" style="display:none" id="loadingSpin"></i> Reset password</button>

<!-- form-group -->
<div class="form-group" style="text-align:right;margin-top:6px;">
	<a href="/">Back to Sign In</a>
</div>
<!-- form-group -->

<input type="hidden" name="token" value="<?php echo $request->token?>">
<input type="hidden" name="login" value="<?php echo $request->login?>">

<!-- Added to allow browsers remember password function-->
<input type="hidden" name="username" value="<?php echo $request->login?>">

<?php echo form_close(); ?>

<script>

	$("#password, #confirmPassword").on("keyup",function(){
		if($("#confirmPassword").val() == $("#password").val()){
			$("#passwordMismatch").hide();
		}
	});


	$("#form").submit(function(){
		if($("#confirmPassword").val() != $("#password").val()){
			$("#passwordMismatch").show();
			return false;
		}
		else{
			$("#passwordMismatch").hide();
			$('#loadingSpin').show();
			$("#username").attr('readonly',true);
			$("#btnResetPassword").attr('disabled',true);
		}
	});

</script>