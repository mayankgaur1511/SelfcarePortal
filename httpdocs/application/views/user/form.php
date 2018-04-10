<?php if(isset($user->id)): ?>
	<?php echo form_open('/user/update/' . $user->id); ?>
<?php endif ?>

<?php if(!isset($user->id)): ?>
	<?php echo form_open('/user/add'); ?>
<?endif ?>

<div class="card pd-20 pd-sm-40">

	<div class="form-layout">
		<div class="row mg-b-25">

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">First name:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control <?php echo form_error('firstName')? 'has-danger':''?>" name="firstName" placeholder="Enter the first name" type="text" 
						value="<?php if(set_value('firstName')){ echo set_value('firstName'); }else{ if(isset($user->firstName)){echo $user->firstName;} }?>">
				</div>
            </div>
            
            <div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Last name:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control <?php echo form_error('lastName')? 'has-danger':''?>" name="lastName" placeholder="Enter the last name" type="text" 
						value="<?php if(set_value('lastName')){ echo set_value('lastName'); }else{ if(isset($user->lastName)){echo $user->lastName;} }?>">
				</div>
            </div>
            
            <div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">E-mail address:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control <?php echo form_error('email')? 'has-danger':''?>" name="email" placeholder="Enter the e-mail address" type="text" 
						value="<?php if(set_value('email')){ echo set_value('email'); }else{ if(isset($user->email)){echo $user->email;} }?>">
				</div>
            </div>
            
            <div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Username:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control <?php echo form_error('username')? 'has-danger':''?>" name="username" placeholder="Enter the username" type="text" 
						value="<?php if(set_value('username')){ echo set_value('username'); }else{ if(isset($user->username)){echo $user->username;} }?>">
				</div>
            </div>
            
            <div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Password:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control <?php echo form_error('password')? 'has-danger':''?>" name="password" placeholder="Enter the password" type="text" 
						value="<?php if(set_value('password')){ echo set_value('password'); }else{ if(isset($user->password)){echo $user->password;} }?>">
				</div>
            </div>
            
            <div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Phone:
						<span class="tx-danger"></span>
					</label>
					<input class="form-control <?php echo form_error('phone')? 'has-danger':''?>" name="phone" placeholder="Enter the phone number" type="text" 
						value="<?php if(set_value('phone')){ echo set_value('phone'); }else{ if(isset($user->phone)){echo $user->phone;} }?>">
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">User type:
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2" data-placeholder="Choose user type" id="userTypeId" name="userTypeId">
						<?php foreach($userTypes as $userType): ?>
							<option <?php if(set_value('userTypeId') == $userType->id){ echo 'selected'; }else{ if(isset($user->userTypeId) and $user->userTypeId == $userType->id){echo 'selected';} }?> value="<?php echo $userType->id?>"><?php echo $userType->type?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
		<!-- row -->
		<div class="form-layout-footer">
			<button type="submit" class="btn btn-success mg-r-5">Submit Form</button>
			<a href='/settings/user' class="btn btn-secondary">Cancel</a>
		</div>
		<!-- form-layout-footer -->
	</div>
	<!-- form-layout -->
</div>
<?php echo form_close(); ?>
