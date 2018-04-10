<div class="card pd-20 pd-sm-40">
	<?php echo form_open("/settings/translation",array("method"=>"GET")); ?>
	<div class="row">
		<div class="col" style="padding-right:0px;">
			<a href='/settings/translation/add' class="btn btn-default btn-block mg-b-10">
				<i class="fa fa-plus mg-r-10"></i>New</a>
		</div>
		<div class="col-10">
			<div class="input-group search-div">
				<input class="form-control search-text" placeholder="<?php echo $this->lang->line('search_for')?>" type="text" name="search"
				value="<?php echo $this->input->get('search')?>">
				<span class="input-group-btn">
					<button class="btn btn-danger" type="submit">
						<i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>


	<div class="table-responsive">
		<table class="table mg-b-0">
			<thead>
				<tr>
					<th>
						<label class="ckbox mg-b-0">
							<input type="checkbox">
							<span></span>
						</label>
					</th>
					<th>#Id</th>
					<th>Term</th>
					<?php foreach($languages as $language):?>
					<th>
						<?php echo $language->language; ?>
					</th>
					<?php endforeach ?>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($translations as $translation): ?>
				<tr>
					<td>
						<label class="ckbox mg-b-0">
							<input type="checkbox">
							<span></span>
						</label>
					</td>
					<td>
						<?php echo $translation->id?>
					</td>
					<td>
						<?php echo $translation->term?>
					</td>
					<?php foreach($languages as $language):?>
					<td>
						<?php echo $translation->{$language->iso}?>
					</td>
					<?php endforeach ?>
					<td>
						<a class="btn btn-secondary btn-icon mg-r-5 mg-b-10" href="/translation/update/<?php echo $translation->id?>">
							<div>
								<i class="fa fa-pencil"></i>
							</div>
						</a>
					</td>
				</tr>
				<?php endforeach ?>
				<?php if(!isset($translations) or !$translations): ?>
				<tr>
					<td colspan="6">No translations found</td>
				</tr>
				<?php endif ?>
			</tbody>
		</table>
	</div>
	<div class="ht-80 bd d-flex align-items-center justify-content-center">
		<nav aria-label="Page navigation">
			<ul class="pagination pagination-basic mg-b-0">
				<?php echo $this->pagination->create_links(); ?>
			</ul>
		</nav>
	</div>
</div>
