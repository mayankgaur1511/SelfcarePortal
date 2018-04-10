<div class="card pd-20 pd-sm-40">
    <div class="table-wrapper">
		<table id="datatable1" class="table display responsive nowrap">
			<thead>
				<tr>
					<th class="wd-10p">Case #</th>
					<th class="wd-10p">Subject</th>
					<th class="wd-10p">Status</th>
					<th class="wd-10p">Category</th>
					<th class="wd-10p">Sub category</th>
					<th class="wd-15p">Creation Date</th>
					<th class="wd-10p">Last Mofified date</th>
					<th></th>

				</tr>
			</thead>
			<tbody>

				<?php 
			   foreach($cases as $case): ?>

				<tr>
					<td>
						<?php echo $case->CaseNumber ?>
					</td>
					<td>
						<?php echo (strlen($case->Subject) < 20 ? $case->Subject : substr($case->Subject,0,21) . '...') ?>
					</td>
					<td>
						<?php echo $case->Status ?>
					</td>
					<td>
						<?php echo $case->ProductType__c ?>
					</td>
					<td>
						<?php echo $case->Product__c ?>
					</td>
					<td>
						<?php echo date("d/m/y H:i", strtotime($case->CreatedDate)) ?>
					</td>
					<td>
                        <?php echo date("d/m/y H:i", strtotime($case->LastModifiedDate)) ?>
					</td>

					<td style="text-align:center">
						<a class="btn btn-default btn-icon mg-r-5 mg-b-10" target="_blank" href="/case/<?php echo $case->Id?>">
							<div>
								<i class="fa fa-arrow-right"></i>
							</div>
						</a>
					</td>

				</tr>

				<?php endforeach ?>
				<?php if(!isset($case) or !$case): ?>
				<tr>
					<td colspan="100%">No records found</td>
				</tr>
				<?php endif ?>
			</tbody>
		</table>
	</div>
	<!-- table-wrapper -->
</div>





<script>
	$(document).ready(function(){
		$('#datatable1').DataTable({
			dom: 'Bfrtip',
			bInfo : false,
			buttons: [
				{
					text: "<i class='fa fa-plus mg-r-10'></i><?php echo $this->lang->line('new_case');?>",
					className: 'btn btn-default',
					action: function ( e, dt, node, config ) {
						window.location.href = "/CaseManagement/new";
					}
				}
			],
			bLengthChange: false,
			responsive: true,
			language: {
				infoEmpty: "<?php echo $this->lang->line('no_results');?>",
				searchPlaceholder: '<?php echo $this->lang->line('search...');?>',
				sSearch: '',
				lengthMenu: '_MENU_ items/page',
				paginate: {
					previous: "<?php echo $this->lang->line('previous');?>",
					next: "<?php echo $this->lang->line('next');?>",
					first: "<?php echo $this->lang->line('first');?>",
					last: "<?php echo $this->lang->line('last');?>"
				}
			},

            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 },
                { responsivePriority: 3, targets: 1 }
            ]
			
			});
		});

</script>
