<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<!-- vendor css -->
	<link href="/assets/lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="/assets/lib/Ionicons/css/ionicons.css" rel="stylesheet" />
	<link href="/assets/lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="/assets/lib/select2/css/select2.min.css" rel="stylesheet">
	<link href="/assets/lib/highlightjs/github.css" rel="stylesheet" />
	<link href="/assets/lib/jquery.steps/jquery.steps.css" rel="stylesheet" />
	<link href="/assets/lib/datatables/jquery.dataTables.css" rel="stylesheet" />
	<script src="/assets/lib/jquery/jquery.js"></script>
	<script src="/assets/js/custom.js"></script>


	<!-- magen-iot-admin CSS -->

	<link rel="stylesheet" href="/assets/css/magen-iot-admin.css" />
	<link rel="stylesheet" href="/assets/css/custom.css" />
	<link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

	<title>
		<?php echo $this->lang->line('selfcare_portal') ?>
	</title>



</head>

<body>
	<div class="loading" style="display:none"></div>
	<!-- ##### SIDEBAR LOGO ##### -->
	<div class="kt-sideleft-header">
		<div class="kt-logo" style='margin-left:30px'>
			<a href="./index.html">
				<img src="/assets/img/Arkadin_logo.png" alt="" width='150px'>
			</a>
		</div>
		<div class="input-group kt-input-search">
			<input type="text" class="form-control" placeholder="Search..." />
			<span class="input-group-btn mg-0">
				<button class="btn">
					<i class="fa fa-search"></i>
				</button>
			</span>
		</div>
		<!-- input-group -->
	</div>
	<!-- kt-sideleft-header -->

	<!-- ##### SIDEBAR MENU ##### -->
	<div class="kt-sideleft">

		<?php
			$this->load->view("layout/layout_leftmenu");
		?>
	</div>
	<!-- kt-sideleft -->

	<!-- ##### HEAD PANEL ##### -->
	<div class="kt-headpanel">
		<div class="kt-headpanel-left">
			<a id="naviconMenu" href="./blank.html" class="kt-navicon d-none d-lg-flex">
				<img src="/assets/img/menu.svg" width="30" />
			</a>
			<a id="naviconMenuMobile" href="./blank.html" class="kt-navicon d-lg-none">
				<i class="icon ion-navicon-round"></i>
			</a>
		</div>
		<!-- kt-headpanel-left -->

		<div class="kt-headpanel-right">

			<!-- dropdown -->
			<div class="dropdown dropdown-profile">
				<a href="./blank.html" class="nav-link nav-link-profile" data-toggle="dropdown">

					<img src="/assets/img/user.png" class="wd-32 rounded-circle" alt="" />
					
					<span class="logged-name">
						<span class="hidden-xs-down"><?php echo $this->session->userdata('login')?></span>
						<i class="fa fa-angle-down mg-l-3"></i>
					</span>
				</a>
				<div class="dropdown-menu wd-200">
					<ul class="list-unstyled user-profile-nav">
						<li>
							<a href="/change-password">
								<i class="icon ion-ios-person-outline"></i> Change my password</a>
						</li>
						<li>
							<a href="/signout">
								<i class="icon ion-power"></i> Sign Out</a>
						</li>
					</ul>
				</div>
				<!-- dropdown-menu -->
			</div>
			<!-- dropdown -->
		</div>
		<!-- kt-headpanel-right -->
	</div>
	<!-- kt-headpanel -->

	<!-- ##### MAIN PANEL ##### -->
	<div class="kt-mainpanel" <?php if(isset($breadcrumbs)): ?>style="padding-top:130px;"<?php endif ?>>


		<div class="kt-pagetitle">
			<h5>
				<?php 
				if(isset($_title) and $_title){
					echo $_title;
				}
			?>
			</h5>

			<?php if(isset($_titleSub) and $_titleSub){ ?>
			<h5>
				<?php  echo $_titleSub; ?> </h5>
			<?php }  ?>
		</div>
		<!-- kt-pagetitle -->
		<?php if($this->session->flashdata('SuccessMessage')): ?>

		<div class="alert alert-success " role="alert">

			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>

			<div class="d-flex align-items-center justify-content-start">
				<i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
				<span>
					<?php echo $this->session->flashdata('SuccessMessage'); ?>
				</span>
			</div>
			<!-- d-flex -->
		</div>
		<!-- alert -->

		<?php endif; ?>
		<?php if($this->session->flashdata('ErrorMessage')): ?>

		<div class="alert alert-danger mg-b-0" role="alert">

			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>

			<div class="d-flex align-items-center justify-content-start">
				<i class="icon ion-ios-close alert-icon tx-24"></i>
				<span>
					<?php echo $this->session->flashdata('SuccessMessage'); ?>
				</span>
			</div>
			<!-- d-flex -->
		</div>
		<!-- alert -->

		<?php endif; ?>
		
		<?php if(isset($breadcrumbs)): ?>
			<div class="kt-breadcrumb">
				<nav class="breadcrumb">
					<?php foreach($breadcrumbs as $breadcrumb): ?>
						<?php if($breadcrumb['link']): ?>
							<a class="breadcrumb-item" href="<?php echo $breadcrumb['link']?>">
								<?php echo $breadcrumb['text']?>
							</a>
						<?php endif ?>
						<?php if(!$breadcrumb['link']): ?>
							<a class="breadcrumb-item" style="color:#17282b">
								<?php echo $breadcrumb['text']?>
							</a>
						<?php endif ?>
					<?php endforeach ?>
				</nav>
			</div>
		<?php endif ?>
	
		<div class="kt-pagebody">
			<?php

          if(isset($_view) and isset($_view)){
            $this->load->view($_view);
          }
        
        ?>

		</div>
		<!-- kt-pagebody -->
	</div>
	<!-- BASIC MODAL -->
<div id="error_modal" class="modal fade" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?php echo $this->lang->line('error_happened');?></h6>
				<i class="fa fa-exclamation-triangle"></i>
			</div>
			<div class="modal-body pd-25">
				<h4 class="lh-3 mg-b-20">
					<a href="#" class="tx-inverse hover-primary"><?php echo $this->lang->line('request_not_completed');?></a>
				</h4>
				<p class="mg-b-5">We could not process your request, Please, try again later and if the issue persists, contact the customer service team
					for help.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary pd-x-20" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->

<!-- BASIC MODAL -->
<div id="formValidation" class="modal fade">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?php echo $this->lang->line('invalid_information');?></h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pd-25">
				<h4 class="lh-4 mg-b-20">Some of the information that you entered is invalid.</h4>
				<p class="mg-b-5">Fields in error are highlighted in red. Note that all mandatory fields are followed by an asterisk. For help, contact
					the customer service.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary pd-x-20" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->

<!-- BASIC MODAL -->
<div id="processingModal" class="modal fade" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?php echo $this->lang->line('request_submitted');?></h6>
					<i class="fa fa-circle-o-notch fa-spin" id="loadingSpin"></i>
			</div>
			<div class="modal-body pd-25">
				<h4 class="lh-4 mg-b-20"><?php echo $this->lang->line('request_submitted_long');?></h4>
				<p class="mg-b-5"><?php echo $this->lang->line('request_submitted_explain');?></p>
				<p class="mg-b-5"><?php echo $this->lang->line('request_reference');?> #<span id="requestReference"></span></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary pd-x-20" onclick="window.history.back();">Previous Page</button>
				<button type="button" class="btn btn-secondary pd-x-20" onclick="window.location.href='/orders'">My orders</button>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->

<!-- BASIC MODAL -->
<div id="requestCompletedModal" class="modal fade" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?php echo $this->lang->line('request_completed');?></h6>
				<i class="fa fa-check"></i>
			</div>
			<div class="modal-body pd-25">
				<h4 class="lh-4 mg-b-20"><?php echo $this->lang->line('you_request_request_completed');?></h4>
				<p class="mg-b-5"><?php echo $this->lang->line('request_completed_explain');?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary pd-x-20" onclick="window.history.back();">Previous Page</button>
				<button type="button" class="btn btn-secondary pd-x-20" onclick="window.location.href='/orders'">My orders</button>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->
	<!-- kt-mainpanel -->
	<script src="/assets/lib/jquery-ui/jquery-ui.js"></script>
	<script src="/assets/lib/select2/js/select2.min.js"></script>
	<script src="/assets/lib/popper.js/popper.js"></script>
	<script src="/assets/lib/bootstrap/bootstrap.js"></script>
	<script src="/assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
	<script src="/assets/lib/moment/moment.js"></script>
	<script src="/assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src="/assets/lib/spectrum/spectrum.js"></script>
	<script src="/assets/lib/highlightjs/highlight.pack.js"></script>
	<script src="/assets/js/magen-iot-admin.js"></script>
	<script src="/assets/lib/datatables/jquery.dataTables.js"></script>
    <script src="/assets/lib/datatables-responsive/dataTables.responsive.js"></script>
	<script src="/assets/lib/dataTables.buttons/dataTables.buttons.min.js"></script>

	<!-- for now I am calling it globaly, Please let me know how to call it for a specific page -->

	<script>
		$(function () {
			'use strict';
			
			$('.select2').select2(
				{
					width: '100%',
					minimumResultsForSearch: -1
				});

			$('.select2-show-search').select2({ width: '100%' });

			$('[data-toggle="tooltip"]').tooltip();
			$('[data-popover-color="default"]').popover();

			// By default, Bootstrap doesn't auto close popover after appearing in the page
			// resulting other popover overlap each other. Doing this will auto dismiss a popover
			// when clicking anywhere outside of it
			$(document).on('click', function (e) {
				$('[data-toggle="popover"],[data-original-title]').each(function () {
					//the 'is' for buttons that trigger popups
					//the 'has' for icons within a button that triggers a popup
					if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
						(($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false // fix for BS 3.3.6
					}

				});
			});

		});

	</script>

</body>

</html>
