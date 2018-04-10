<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />



	<!-- Meta -->
	<meta name="description" content="SelfCare - Arkadin Cloud Communications." />
	<meta name="author" content="Arkadin" />


	<title>SelfCare - Arkadin Cloud Communications</title>

	<!-- vendor css -->
	<link href="/assets/lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="/assets/lib/Ionicons/css/ionicons.css" rel="stylesheet" />
	<link href="/assets/lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />

	<!-- magen-iot-admin CSS -->
	<link rel="stylesheet" href="/assets/css/magen-iot-admin.css" />
	<link rel="stylesheet" href="/assets/css/custom.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="/assets/lib/jquery/jquery.js"></script>
</head>

<body class="login-body" ng-app="loginApp" ng-controller="mainController" style="background-size:cover">

	<div class="signpanel-wrapper">
		<div class="signbox">
			<div class="signbox-header">
				<img src="/assets/img/Arkadin_logo_white.png" alt="" width='150px'>
			</div>
			<!-- signbox-header -->
			<div class="signbox-body">
				<?php
					if(isset($_view) and isset($_view)){

						$this->load->view($_view);
					}
       			 ?>
			</div>
			<!-- signbox-body -->
		</div>
		<!-- signbox -->
	</div>
	<!-- signpanel-wrapper -->	
	<script src="/assets/lib/popper.js/popper.js"></script>
	<script src="/assets/lib/bootstrap/bootstrap.js"></script>
</body>

</html>
