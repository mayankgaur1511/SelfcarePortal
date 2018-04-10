<div class="card pd-20 pd-sm-40">



	<div class="table-responsive">
		<table class="table mg-b-0">
			<thead>
				<tr>
				
					<th>Logo Name</th>
					<th>Logo Ref</th>
                   
					<th></th>
				</tr>
			</thead>
			<tbody>
                <?php $count=1;foreach($localLogos as $logo): ?>
				<tr id="test<?php echo $count;?>" data-account-level="master" data-value="<?php echo $logo['Id'] ?>">
				
					
                    <td><?php echo $logo['Name'] ?></td>
                    <td><?php echo $logo['Reference']?></td>
                 
					<td style="text-align:right">
						<a class="btn btn-secondary btn-icon mg-r-5 mg-b-5 pa-dt-toggle" href="#">
							<div>								
								<i class="icon ion-plus"></i>
							</div>
						</a>
					</td>
				</tr>

				<tr ><td colspan="100%" class="pa-dt-group-account-level" data-owner="test<?php echo $count;?>" ><table class="table mg-b-0 ">
				<thead>
					<tr data-account-level="group">
						<th>BA Name</th>
						<th>BA Ref</th>                   
						<th></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
				</table>
				</td>
				</tr>
                <?php $count++; endforeach ?>
                <?php if(!isset($localLogos) or !$localLogos): ?>
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



<script>

// Hide all group and account level
$('.pa-dt-group-account-level').hide();
$('.pa-dt-account-level').hide();

// Define our button for toggling
var button = $( "table.table a.pa-dt-toggle" );

button.click(function() {
	// get the parent ID
	var parentId = $(this).parents('tr').attr("id");
	// get the parent account level
	var accountType = $(this).parents('tr').attr("data-account-level");
	
	console.log(parentId);
	console.log(accountType);
	
	if ($(this).hasClass("pa-dt-toggle-active")) {
		$(this).removeClass('pa-dt-toggle-active');
		$('[data-owner="' + parentId +'"]').hide();
		
	} else {
		$(this).addClass('pa-dt-toggle-active');
		$('[data-owner="' + parentId +'"]').show();
		
		$(".loading").show();
		$.get('/salesforce/getBillingAccounts?ref=' + $("#"+parentId).data("value"), function (result) {
				
				empty = true;
				var option='';i=1
				jQuery.each(result, function (index, element) {					
				
					option +=  '<tr id="testBA'+ i +'" data-value="'+element.nodeId+'"><td>' + element.Name + '</td><td>' + element.AccountReference + '</td><td style="text-align:right"><a class="btn btn-secondary btn-icon mg-r-5 mg-b-10 pa-dt-toggle" href="#" onclick=showPGToggle(this)><div><i class="icon ion-plus"></i></div></a></td></tr><tr ><td data-owner="testBA'+ i +'" colspan="100%"></td></tr>';				
					empty = false;
					i++;
				});

				if (empty) {
					var option = '<tr ><td colspan="100%">--No record found--</td></tr>';
					$('[data-owner="' + parentId +'"]').html(option);
				} else {
					
					$(".loading").hide();							
					$('[data-owner="' + parentId +'"] tbody').html(option);
				}
			})
			.fail(function (result) {
				
				$(".loading").hide();							
				console.log(result);
			})

	}
});

function showPGToggle(ele){
	var parentId = $(ele).parents('tr').attr("id");
	// get the parent account level
	var accountType = $(ele).parents('tr').attr("data-account-level");
	
	console.log(parentId);
	console.log(accountType);

	if ($(ele).hasClass("pa-dt-toggle-active")) {
		$(ele).removeClass('pa-dt-toggle-active');
		$('[data-owner="' + parentId +'"]').hide();
		
	} else {
		$(ele).addClass('pa-dt-toggle-active');
		$('[data-owner="' + parentId +'"]').show();
		$('[data-owner="' + parentId +'"]').html();
		$('[data-owner="' + parentId +'"]').html('<table class="table mg-b-0 "><thead><tr data-account-level="group"><th>PG Name</th><th>PG Ref</th><th></th></tr></thead><tbody class="pgContent"></tbody></table>');

		$(".loading").show();
		$.get('/salesforce/getDepartments?ref=' + $("#"+parentId).data("value"), function (result) {
				
				empty = true;
				var option='';i=1
				jQuery.each(result, function (index, element) {					
				
					option +=  '<tr><td><a href="/moderator/index/'+element.Id+'">' + element.Name + '</a></td><td>' + element.Reference + '</td><td style="text-align:right"><a class="btn btn-secondary btn-icon mg-r-5 mg-b-10 pa-dt-toggle" href="#" onclick=showPGToggle(this)><div><i class="icon ion-plus"></i></div></a></td></tr><tr ></tr>';				
					empty = false;
					i++;
				});

				if (empty) {
					var option = '<tr ><td colspan="100%">--No record found--</td></tr>';
					$('[data-owner="' + parentId +'"] .pgContent').html(option);
				} else {
						
					$(".loading").hide();													
					$('[data-owner="' + parentId +'"] .pgContent').html(option);
				}
				$(".loading").hide();
			})
			.fail(function (result) {
				
				$(".loading").hide();							
				console.log(result);
			})

	

	}
}

button.click(function(e){
	//e.stopPropagation();
});
</script>

