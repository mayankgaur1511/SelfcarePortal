<div class="card pd-20 pd-sm-40">



	<div class="table-responsive">
		<table class="table mg-b-0">
			<thead>
				<tr>
				
					<th>Primary group Name</th>
					<th>grimary Group Ref</th>
					<th>More..</th>
					
				</tr>
			</thead>
			<tbody>
                <?php foreach($primaryGroups as $primaryGroup): ?>
				<tr>
				
					
                    <td><?php echo $primaryGroup->PrimaryGroupName ?></td>
                    <td><?php echo $primaryGroup->PrimaryGroupRef ?></td>
					<td>
						<a class="btn btn-secondary btn-icon mg-r-5 mg-b-10" href="/logo/pgProfile/<?php echo $primaryGroup->PrimaryGroupRef?>">
							<div>
								
								<i class="icon ion-more"></i>
							</div>
						</a>
					</td>
					
				</tr>
                <?php endforeach ?>
                <?php if(!isset($primaryGroup) or !$primaryGroup): ?>
                <tr>
                    <td colspan="100%">No Record found!</td>
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

