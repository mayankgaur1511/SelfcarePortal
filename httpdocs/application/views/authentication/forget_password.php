<?php echo form_open('/reset-password'); ?>

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

<?php if(isset($invalid_login) and $invalid_login): ?>
<div class="alert alert-danger" role="alert">
	<div class="d-flex align-items-center justify-content-start">
		<i class="icon ion-ios-close alert-icon tx-24"></i>
		<span>
			<?php echo $this->lang->line('invalid_login'); ?>
		</span>
	</div>
</div>
<?php endif ?>


<div class="form-group">
	<label class="form-control-label">Username:</label>
	<input type="text" name="username" id="username" placeholder="Enter your username" class="form-control <?php echo form_error('username')? 'has-danger':''?>"
	/>
</div>

<button id="ButtonResetPassword" class="btn btn-danger login-button btn-block">
	<i class="fa fa-circle-o-notch fa-spin" style="display:none" id="loadingSpin"></i> Reset password
</button>

<!-- form-group -->
<div class="form-group" style="text-align:right;margin-top:6px;">
	<a href="/">Back to Sign In</a>
</div>
<!-- form-group -->


<?php echo form_close(); ?>

<script>

$('form').submit(function () {
    $('#loadingSpin').show();
	$("#username").attr('readonly',true);
	$("#ButtonResetPassword").attr('disabled',true);
});

</script>