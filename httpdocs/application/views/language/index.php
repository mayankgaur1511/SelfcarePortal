<div class="card pd-20 pd-sm-40">
	<?php echo form_open("/settings/language",array("method"=>"GET")); ?>
	<div class="row">
		<div class="col" style="padding-right:0px;">
			<a href='/settings/language/add' class="btn btn-default btn-block mg-b-10">
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
					<th></th>
					<th>#Id</th>
					<th>Language</th>
					<th>ISO</th>
					<th>Interface</th>
					<th>Wise2</th>
					<th>BlueSky</th>
					<th>CMB</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($languages as $language): ?>
				<tr>
					<td></td>
					<td>
						<?php echo $language->id?>
					</td>
					<td>
						<?php echo $language->language?>
					</td>
					<td>
						<?php echo $language->iso?>
					</td>
					<td>
						<?php echo $language->interface? 'Yes':'No'?>
					</td>
					<td>
						<?php echo $language->wise2? $language->wise2:'No'?>
					</td>
					<td>
						<?php echo $language->bluesky? $language->bluesky:'No'?>
					</td>
					<td>
						<?php echo $language->cmb? $language->cmb:'No'?>
					</td>
					
					<td>
						<a class="btn btn-secondary btn-icon mg-r-5 mg-b-10" href="/language/update/<?php echo $language->id?>">
							<div>
								<i class="fa fa-pencil"></i>
							</div>
						</a>
					</td>
				</tr>
				<?php endforeach ?>
				<?php if(!isset($languages) or !$languages): ?>
				<tr>
					<td colspan="6">No languages found</td>
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
