<?php if(isset($language->id)): ?>
	<?php echo form_open('/language/update/' . $language->id); ?>
<?php endif ?>

<?php if(!isset($language->id)): ?>
	<?php echo form_open('/language/add'); ?>
<?endif ?>

<div class="card pd-20 pd-sm-40">

	<div class="form-layout">
		<div class="row mg-b-25">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Language:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control <?php echo form_error('language')? 'has-danger':''?>" name="language" placeholder="Enter the language" type="text" 
						value="<?php if(set_value('language')){ echo set_value('language'); }else{ if(isset($language->language)){echo $language->language;} }?>">
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">ISO:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control <?php echo form_error('iso')? 'has-danger':''?>" name="iso" placeholder="Enter the ISO code" type="text" 
						value="<?php if(set_value('iso')){ echo set_value('iso'); }else{ if(isset($language->iso)){echo $language->iso;} }?>">
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label <?php echo form_error('interface')? 'has-danger':''?>">Interface:
						<span class="tx-danger">*</span>
					</label>

					<select name="interface" class="form-control select2">
						<option value="1">Yes</option>
						<option value="0" <?php if(set_value('interface') === 0){ echo 'selected'; }else{ if(isset($language->interface) and $language->interface === 0){echo 'selected';} }?>>No</option>
					<select>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Wise2:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control" name="wise2" placeholder="Enter the Wise2 language code" type="text" 
						value="<?php if(set_value('wise2')){ echo set_value('wise2'); }else{ if(isset($language->wise2)){echo $language->wise2;} }?>">
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">BlueSky:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control" name="bluesky" placeholder="Enter the BlueSky language code" type="text" 
						value="<?php if(set_value('bluesky')){ echo set_value('bluesky'); }else{ if(isset($language->bluesky)){echo $language->bluesky;} }?>">
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">CMB:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control" name="cmb" placeholder="Enter the CMB language code" type="text" 
						value="<?php if(set_value('cmb')){ echo set_value('cmb'); }else{ if(isset($language->cmb)){echo $language->cmb;} }?>">
				</div>
			</div>
		</div>
		<!-- row -->
		<div class="form-layout-footer">
			<button type="submit" class="btn btn-default mg-r-5">Submit Form</button>
			<button type="reset" class="btn btn-secondary">Cancel</button>
		</div>
		<!-- form-layout-footer -->
	</div>
	<!-- form-layout -->
</div>
<?php echo form_close(); ?>