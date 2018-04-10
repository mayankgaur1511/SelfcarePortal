<?php echo form_open('/authentication/index'); ?>

<?php if(validation_errors()): ?>
	<div class="alert alert-danger" role="alert">
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-ios-close alert-icon tx-24"></i>
			<span><?php echo $this->lang->line('user_or_pass_not_filled'); ?></span>
		</div>
	</div>
<?php endif ?>

<?php if(isset($user_pass_incorrect) and $user_pass_incorrect): ?>
	<div class="alert alert-danger" role="alert">
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-ios-close alert-icon tx-24"></i>
			<span><?php echo $this->lang->line('user_or_pass_incorrect'); ?></span>
		</div>
	</div>
<?php endif ?>

<?php if($this->session->flashdata('password_reset_link_sent')): ?>
	<div class="alert alert-success" role="alert">
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-checkmark alert-icon tx-24"></i>
			<span><?php echo $this->lang->line('reset_password_link_sent'); ?></span>
		</div>
	</div>
<?php endif ?>

<?php if($this->session->flashdata('password_reset_succesful')): ?>
	<div class="alert alert-success" role="alert">
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-checkmark alert-icon tx-24"></i>
			<span><?php echo $this->lang->line('reset_password_succesful'); ?></span>
		</div>
	</div>
<?php endif ?>

<div class="form-group">
	<label class="form-control-label">
		<?php echo $this->lang->line('username'); ?>:</label>
	<input type="text" name="username" id="username" placeholder="<?php echo $this->lang->line('enter_your_username'); ?>" class="form-control <?php echo form_error('username')? 'has-danger':''?>"
	/>
</div>
<!-- form-group -->
<div class="form-group">
	<label class="form-control-label">
		<?php echo $this->lang->line('password'); ?>:</label>
	<input type="password" name="password" id="password" placeholder="<?php echo $this->lang->line('enter_your_password'); ?>" class="form-control <?php echo form_error('password')? 'has-danger':''?>"
	/>
</div>
<!-- form-group -->
<div class="form-group">
	<a href="/reset-password">
		<?php echo $this->lang->line('forget_password_link'); ?>?</a>
</div>
<!-- form-group -->
<button id="signinBtn" class="btn btn-danger btn-block login-button">
	<i class="fa fa-circle-o-notch fa-spin" style="display:none" id="loadingSpin"></i> <?php echo $this->lang->line('sign_in'); ?>
</button>

<?php echo form_close(); ?>

<script>

$("form").submit(function(){
	$('#loadingSpin').show();
	$("#username").attr('readonly',true);
	$("#password").attr('readonly',true);

	$("#signinBtn").attr('disabled',true);
})

function loading()
{
	document.getElementById('signinLoading').style.display = 'inline';
	document.getElementById('signinBtn').disabled = true;
}

</script>