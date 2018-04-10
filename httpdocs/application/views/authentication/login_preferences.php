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


<div class="form-group">
	<label class="form-control-label">First name<span class="tx-danger">*</span></label>
	<input type="text" name="firstName" id="firstName" placeholder="Enter your first name" class="form-control <?php echo form_error('firstName')? 'has-danger':''?>"/>
</div>
<div class="form-group">
	<label class="form-control-label">Last name<span class="tx-danger">*</span></label>
	<input type="text" name="lastName" id="lastName" placeholder="Enter your last name" class="form-control <?php echo form_error('lastName')? 'has-danger':''?>"/>
</div>

<div class="form-group">
	<label class="form-control-label">Country<span class="tx-danger">*</span></label>
	<select class="form-control select2-show-search" data-placeholder="Select the country" id="countryId" name="countryId">
		<?php foreach($countries as $country): ?>
			<option value="<?php echo $country->id?>">
				<?php echo $country->countryName?>
			</option>
		<?php endforeach ?>
	</select>
</div>

<div class="form-group">
	<label class="form-control-label">Country<span class="tx-danger">*</span></label>
	<select class="form-control select2-show-search" data-placeholder="Select the country" id="countryId" name="countryId">
		<?php foreach($countries as $country): ?>
			<option value="<?php echo $country->id?>">
				<?php echo $country->countryName?>
			</option>
		<?php endforeach ?>
	</select>
</div>

<div class="form-group">
	<label class="form-control-label">Country<span class="tx-danger">*</span></label>
	<select class="form-control select2-show-search" data-placeholder="Select the country" id="countryId" name="countryId">
		<?php foreach($countries as $country): ?>
			<option value="<?php echo $country->id?>">
				<?php echo $country->countryName?>
			</option>
		<?php endforeach ?>
	</select>
</div>

<button id="btnResetPassword" class="btn btn-danger login-button btn-block"><i class="fa fa-circle-o-notch fa-spin" style="display:none" id="loadingSpin"></i> Update preferences</button>

<?php echo form_close(); ?>