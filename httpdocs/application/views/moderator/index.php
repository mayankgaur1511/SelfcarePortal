
<div class="card pd-20 pd-sm-40">

<div class="row">
		<div class="col-2" style="padding-right:0px;">
			<a href='/moderator/new' class="btn btn-default btn-block mg-b-10">
				<i class="fa fa-plus mg-r-10"></i>New</a>
		</div>	
</div>
	<div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-10p">Moderator Name</th>
                  <th class="wd-10p">Ref#</th>
                  <th class="wd-15p">Phone</th>
                  <th class="wd-10p">Email</th>
				  <th class="wd-10p">Status</th>                
                  <th class="wd-10p"></th>
                 
                </tr>
              </thead>
              <tbody>
            
			  <?php // print_r($clientArr);
			   foreach($clientArr as $client): ?>

              <tr>
                  <td><?php echo $client->ClientName ?></td>
                  <td><?php echo $client->ClientRef ?> </td>
                  <td><?php echo $client->ClientMainPhone   ?></td>
                  <td><?php echo $client->ClientEMail  ?></td>

				  <td><?php echo ($client->DisabledInd)?'Suspended':'Active';  ?></td>
                  
                  <td>
                      <a class="btn btn-secondary btn-icon mg-r-5 mg-b-10" href="/settings/bridge/update/<?php echo $client->ClientRef?>">
                          <div>
                              <i class="fa fa-pencil"></i>
                          </div>
                      </a>
                  </td>
                </tr>

              <?php endforeach ?>
              <?php if(!isset($clientArr) or !$clientArr): ?>
                <tr>
                    <td colspan="100%">No record found</td>
                </tr>
               <?php endif ?>

        
              
           
                </tr>
              </tbody>
            </table>
          </div><!-- table-wrapper -->

		
	
</div>





<script type="text/javascript">
		// Select2			
		$('#datatable1').DataTable({
		responsive: true,
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_ items/page',
		}
		});
	
		

</script>


