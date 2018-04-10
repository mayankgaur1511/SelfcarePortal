<div class="card pd-20 pd-sm-40">



	<div class="table-responsive">
		<table class="table mg-b-0">
			<thead>
				<tr>
				
					<th>BillingAccount Name</th>
					<th>BillingAccount Ref</th>
                   
					<th>More..</th>
				</tr>
			</thead>
			<tbody>
                <?php foreach($billingAccounts as $billingAccount): ?>
				<tr>
				
					
                    <td><?php echo $billingAccount->ClientName ?></td>
                    <td><?php echo $billingAccount->ClientRef ?></td>
                 
					<td>
						<a class="btn btn-secondary btn-icon mg-r-5 mg-b-10" href="/logo/pg/<?php echo $billingAccount->ClientRef?>">
							<div>
								
								<i class="icon ion-more"></i>
							</div>
						</a>
					</td>
				</tr>
                <?php endforeach ?>
                <?php if(!isset($billingAccount) or !$billingAccount): ?>
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

