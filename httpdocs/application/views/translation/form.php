<?php if(isset($translation->id)): ?>
	<?php echo form_open('/translation/update/' . $translation->id); ?>
<?php endif ?>

<?php if(!isset($translation->id)): ?>
	<?php echo form_open('/translation/add'); ?>
<?endif ?>

<div class="card pd-20 pd-sm-40">

	<div class="form-layout">
		<div class="row mg-b-25">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="form-control-label">Term:
						<span class="tx-danger">*</span>
					</label>
					<input class="form-control" name="term" placeholder="Enter the term" type="text" 
						value="<?php if(isset($translation->term)):?><?php echo $translation->term ?><?php endif ?>">
				</div>
			</div>

			<?php foreach($languages as $language):?>
				<div class="col-lg-4">
					<div class="form-group">
						<label class="form-control-label"><?php echo $language->language?>
							<span class="tx-danger">*</span>
						</label>
						<input class="form-control" name="<?php echo $language->iso?>" placeholder="Enter the translation" type="text"
							value="<?php if(isset($translation->{$language->iso})):?><?php echo $translation->{$language->iso} ?><?php endif ?>">
					</div>
				</div>
			<?php endforeach ?>
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