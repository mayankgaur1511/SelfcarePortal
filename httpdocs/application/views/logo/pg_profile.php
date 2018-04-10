<div class="row row-sm mg-t-20">
	<div class="col-xl-8">

	<div class="col-md-12">
              <div class="card rounded-0">
                <div class="card-header card-header-default">
				Account Info
                </div><!-- card-header -->
                <div class="card-body ">
                  
				<div class="adv-table">
					<table class="display table table-bordered table-hover table-condensed" id="dynamic-table">
						<tbody>
							<tr>
								<th width="35%">PG Ref#</th>
								<td width="65%"><?php echo $hierarchy->PrimaryGroupId ?></td>
							</tr>
							<tr>
								<th>Billing Name/Account</th>
								<td><?php echo $hierarchy->ClientName ?> / <?php echo $hierarchy->ClientRef ?></td>
							</tr>
							<tr>
								<th>Logo Name/Ref</th>
								<td><?php echo $hierarchy->LogoName ?> / <?php echo $hierarchy->LogoRef ?></td>
							</tr>
							
							
						</tbody>
					 
					</table>
				</div>


                </div><!-- card-body -->
              </div><!-- card -->
            </div>

			<br style="clear:both;">

			<div class="col-md-12">
              <div class="card rounded-0">
                <div class="card-header card-header-default">
				Other Info
                </div><!-- card-header -->
                <div class="card-body ">
                  
				<div class="adv-table">
					<table class="display table table-bordered table-hover table-condensed" id="dynamic-table">
						<tbody>
							<tr>
								<th width="35%">Audio Bridge (ID) </th>
								<td width="65%"><?php echo $hierarchy->BridgeProvider ?> (<?php echo $hierarchy->AccessBridge ?>) </td>
							</tr>
						
							<tr>
								<th>Bridge Source </th>
								<td><?php echo $hierarchy->ClientSourceName ?></td>
							</tr>
							
							 
							
						</tbody>
						
					</table>
				</div>


                </div><!-- card-body -->
              </div><!-- card -->
            </div>


			

	</div>

	<div class="col-xl-4">

	<div class="card pd-20 pd-sm-40 form-layout form-layout-4">

	<div class="row">
            <div class="col-sm-6 col-md-12">
			<a href="/moderator/index/<?php echo $PgGroupID; ?>">  <button class="btn btn-primary btn-block mg-b-10"><i class="fa fa-users mg-r-10"></i>Moderator	</button></a>
             <a href="/moderator/new"> <button class="btn btn-danger btn-block"><i class="fa fa-plus mg-r-10"></i> New Moderator</button></a>
            </div><!-- col-sm -->
            
          </div>
	
	 </div>
     </div>
       
</div>

