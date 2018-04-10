<div class="card pd-20 pd-sm-40">
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
					<th>First name</th>
					<th>Last name</th>
                    <th>E-mail Address</th>
                    <th>Username</th>
					<th>Creation Datetime</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
                <?php foreach($users as $user): ?>
				<tr>
					<td>
						<label class="ckbox mg-b-0">
							<input type="checkbox">
							<span></span>
						</label>
					</td>
					<td><?php echo $user->id?></td>
                    <td><?php echo $user->firstName?></td>
                    <td><?php echo $user->lastName?></td>
                    <td><?php echo $user->email?></td>
					<td><?php echo $user->username?></td>
					<td><?php echo $user->creationDate?></td>
					<td>
						<a class="btn btn-secondary btn-icon mg-r-5 mg-b-10" href="/settings/user/update/<?php echo $user->id?>">
							<div>
								<i class="fa fa-pencil"></i>
							</div>
						</a>
					</td>
				</tr>
                <?php endforeach ?>
                <?php if(!isset($users) or !$users): ?>
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

