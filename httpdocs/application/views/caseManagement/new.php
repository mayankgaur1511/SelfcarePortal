<div class="container" style="padding-right:0px;padding-left:0px;">
	<div class="alert alert-danger" style="display:none" id="alert_warning">
		<table>
			<tr>
				<td style="width:1px;padding-right:10px;">
					<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
				</td>
				<td>
					<span id="errors"></span>
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="card pd-20 pd-sm-40">
	<div class="form-layout">
		<div class="row mg-b-25">
			<div class="col-lg-3">
				<div class="form-group">
					<label class="form-control-label">
						<?php echo $this->lang->line('impacted_country');?>
						<span class="tx-danger">*</span>
					</label>
					<select class="form-control select2-show-search" data-placeholder="<?php echo $this->lang->line('select_country');?>" id="T_Country_impacted__c"
					name="T_Country_impacted__c">
                        <option></option>
						<?php foreach($countries as $country): ?>
						<option value="<?php echo $country['country']?>">
							<?php echo $country['country']?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class="col-lg-3">
				<div class="form-group">
					<label>Product Type
						<span style="color:RED">*</span>
					</label>
					<select id="ProductType__c" class="select2" data-width="100%" data-placeholder="Select a product">
						<option></option>
						<option>UC&C</option>
						<option>IS</option>
					</select>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Sub Product
						<span style="color:RED">*</span>
					</label>
					<select id="Product__c" class="select2" data-width="100%" disabled>
					</select>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Request category
						<span style="color:RED">*</span>
					</label>
					<select id="Category_NEW__c" class="select2" data-width="100%" disabled>
					</select>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Impact</label>
					<select id="BusinessImpact__c" class="select2" data-width="100%" disabled>
					</select>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Urgency</label>
					<select id="Severity__c" class="select2" data-width="100%" disabled>
					</select>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Product L2</label>
					<select id="Product_L2__c" class="select2" data-width="100%" disabled>
					</select>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Symptom</label>
					<select id="Symptom_L1__c" class="select2" disabled data-width="100%">
					</select>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Symptom 2</label>
					<select id="Symptom_L2__c" class="select2" disabled data-width="100%">
					</select>
				</div>
			</div>
        </div>
        <div class="row mg-b-25">
			<div class="col-lg-7">
				<div class="form-group">
					<label>Subject
						<span style="color:RED">*</span>
					</label>
					<input id="Subject" type="text" class="form-control">
				</div>
				<div class="form-group">
					<label>Description
						<span style="color:RED">*</span>
					</label>
					<textarea id="Description" rows="4" class="form-control"></textarea>
				</div>
			</div>

			<div class="col-lg-5">
				<BR>
				<table class="table">
					<thead>
						<th style="font-weight:normal;">Attachments</th>
						<th style="text-align:right;">
							<i id="btnAttach" style="cursor:pointer" class="fa fa-plus" aria-hidden="true"></i>
						</th>
					</thead>
					<tbody id="attachments">
						<tr id="no_rows">
							<td colspan="2">No files attached</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-layout-footer" style="float: right" id="firstPage">
			<button type="button" id="btnCreateCase" class="btn btn-default" onclick="createCase()">
				<i class="fa fa-floppy-o" aria-hidden="true"></i> Save
			</button>
		</div>
	</div>

	<!-- BASIC MODAL -->
	<div id="maxFilesModal" class="modal fade">
		<div class="modal-dialog modal-dialog-vertical-center" role="document">
			<div class="modal-content bd-0 tx-14">
				<div class="modal-header pd-y-20 pd-x-25">
					<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">
						<?php echo $this->lang->line('max_files_attached');?>
					</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body pd-25">
					<p class="mg-b-5">
						<?php echo $this->lang->line('max_files_attached_5');?>
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary pd-x-20" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
		<!-- modal-dialog -->
	</div>
	<!-- modal -->

	<BR>
	<BR>

	<?php for($i = 1; $i <= 5; $i++): ?>
	<form id="form_attachment_<?php echo $i?>" method="POST" enctype="multipart/form-data">
		<input type="file" id="attachment_<?php echo $i?>" name="attachment_<?php echo $i?>" style="display: none" />
	</form>
	<?php endfor ?>

	<script>
		$("#ProductType__c").on("change", function () {
			set_Product__c();
			set_Category_NEW__c();
		});

		$("#Category_NEW__c").on("change", function () {
			set_BusinessImpact__c();
			set_Severity__c();
		});

		$("#Product__c").on("change", function () {
			set_Product_L2__c();
			set_Symptom_L1__c();
		});

		$("#Product_L2__c").on("change", function () {
			set_Symptom_L1__c();
		});

		$("#Symptom_L1__c").on("change", function () {
			set_Symptom_L2__c();
		});


		function set_Product__c() {
			$.get("/CaseManagement/Product__c/", {
					ProductType__c: $("#ProductType__c").val()
				}, function (result) {
					var empty = true;
					$('#Product__c').children('option').remove();
					jQuery.each(result, function (index, element) {
						empty = false;
						var option = $('<option value="' + element.Product__c + '">' + element.display + '</option>');
						$('#Product__c').append(option);
					});

					$('#Product__c').trigger("change");
					if (empty) {
						$('#Product__c').attr("disabled", "disabled");
					} else {
						$('#Product__c').removeAttr("disabled", "disabled");
					}
				})
				.fail(function (result) {
					console.log(result);
				});
		}

		function set_Category_NEW__c() {
			$.get("/CaseManagement/Category_NEW__c/", {
					ProductType__c: $("#ProductType__c").val()
				}, function (result) {
					$('#Category_NEW__c').children('option').remove();
					var empty = true;
					jQuery.each(result, function (index, element) {
						empty = false;
						var option = $('<option value="' + element.Category_NEW__c + '">' + element.Category_NEW__c + '</option>');
						$('#Category_NEW__c').append(option);
					});

					$('#Category_NEW__c').trigger("change");
					if (empty) {
						$('#Category_NEW__c').attr("disabled", "disabled");
					} else {
						$('#Category_NEW__c').removeAttr("disabled", "disabled");
					}
				})
				.fail(function (result) {
					console.log(result);
				});
		}

		function set_BusinessImpact__c() {
			$.get("/CaseManagement/BusinessImpact__c/", {
					Category_NEW__c: $("#Category_NEW__c").val()
				}, function (result) {
					$('#BusinessImpact__c').children('option').remove();
					empty = true;
					jQuery.each(result, function (index, element) {
						var option = $('<option value="' + element.BusinessImpact__c + '">' + element.BusinessImpact__c + '</option>');
						$('#BusinessImpact__c').append(option);
						empty = false;
					});

					$('#BusinessImpact__c').trigger("change");

					if (empty) {
						var option = $('<option value="">--None--</option>');
						$('#BusinessImpact__c').append(option);
						$('#BusinessImpact__c').attr("disabled", "disabled");
					} else {
						$('#BusinessImpact__c').removeAttr("disabled", "disabled");
					}


				})
				.fail(function (result) {
					console.log(result);
				});
		}

		function set_Severity__c() {
			$.get("/CaseManagement/Severity__c/", {
					Category_NEW__c: $("#Category_NEW__c").val()
				}, function (result) {
					$('#Severity__c').children('option').remove();
					empty = true;
					jQuery.each(result, function (index, element) {
						var option = $('<option value="' + element.Severity__c + '">' + element.Severity__c + '</option>');
						$('#Severity__c').append(option);
						empty = false;
					});

					$('#Severity__c').trigger("change");
					if (empty) {
						$('#Severity__c').attr("disabled", "disabled");
					} else {
						$('#Severity__c').removeAttr("disabled", "disabled");
					}

				})
				.fail(function (result) {
					console.log(result);
				});
		}

		function set_Product_L2__c() {
			$.get("/CaseManagement/Product_L2__c/", {
					Product__c: $("#Product__c").val()
				}, function (result) {
					$('#Product_L2__c').children('option').remove();

					empty = true;
					jQuery.each(result, function (index, element) {
						var option = $('<option value="' + element.Product_L2__c + '">' + element.Product_L2__c + '</option>');
						$('#Product_L2__c').append(option);
						empty = false;
					});

					$('#Product_L2__c').trigger("change");

					if (empty) {
						$('#Product_L2__c').attr("disabled", "disabled");
					} else {
						$('#Product_L2__c').removeAttr("disabled", "disabled");
						set_Symptom_L1__c();
					}

				})
				.fail(function (result) {
					console.log(result);
				});
		}

		function set_Symptom_L1__c() {
			$.get("/CaseManagement/Symptom_L1__c/", {
					Product__c: $("#Product__c").val()
				}, function (result) {
					$('#Symptom_L1__c').children('option').remove();

					empty = true;
					jQuery.each(result, function (index, element) {
						var option = $('<option value="' + element.Symptom_L1__c + '">' + element.Symptom_L1__c + '</option>');
						$('#Symptom_L1__c').append(option);
						empty = false;
					});

					$('#Symptom_L1__c').trigger("change");

					if (empty) {
						$('#Symptom_L1__c').attr("disabled", "disabled");
					} else {
						$('#Symptom_L1__c').removeAttr("disabled", "disabled");
					}

				})
				.fail(function (result) {
					console.log(result);
				});
		}

		function set_Symptom_L2__c() {
			$.get("/CaseManagement/Symptom_L2__c/", {
					Symptom_L1__c: $("#Symptom_L1__c").val()
				}, function (result) {
					$('#Symptom_L2__c').children('option').remove();

					empty = true;
					jQuery.each(result, function (index, element) {
						empty = false;
						var option = $('<option value="' + element.Symptom_L2__c + '">' + element.Symptom_L2__c + '</option>');
						$('#Symptom_L2__c').append(option);
					});

					$('#Symptom_L2__c').trigger("change");
					if (empty) {
						$('#Symptom_L2__c').attr("disabled", "disabled");
					} else {
						$('#Symptom_L2__c').removeAttr("disabled", "disabled");
					}

				})
				.fail(function (result) {
					console.log(result);
				});
		}

		// Create Case
		function createCase() {
            
			$("#btnCreateCase").html("<i class='fa fa-circle-o-notch fa-spin' aria-hidden='true'></i> Processing");
			var data = {
				ProductType__c: $("#ProductType__c").val(),
				Product__c: $("#Product__c").val(),
				Subject: $("#Subject").val(),
				Description: $("#Description").val(),
				Category_NEW__c: $("#Category_NEW__c").val(),
				BusinessImpact__c: $("#BusinessImpact__c").val(),
				Severity__c: $("#Severity__c").val(),
				Product_L2__c: $("#Product_L2__c").val(),
				Symptom_L1__c: $("#Symptom_L1__c").val(),
				T_Country_impacted__c: $("#T_Country_impacted__c").val()
			};

			if (!data.ProductType__c || !data.Product__c || !data.Subject || !data.Description) {
				$("#alert_warning").show();
				$("#errors").html("You must fill all required fields");
				$("#loading").hide();
			} else {
				$("#alert_warning").hide();
				$.post("/CaseManagement/createCase/", data, function (result) {
						if (!result.success) {
							errors = "";
							jQuery.each(result.errors, function (index, error) {
								errors = errors + " | " + error.message;
							});

							$("#alert_warning").show();

							$("#errors").html(errors.substr(3));
						} else {
							$("#alert_warning").hide();
							var caseId = result.id;
							// Atach files
							var file = false;
							for (i = 1; i <= 5; i++){
								if($("#attachment_" + i).val().length > 0){
									var file = true;
								}
							}

							console.log(file);
							if(file){
                            	attachFiles(caseId,1);
							}
							else{
								window.location.href = "/case/" + caseId;
							}
						}

						$("#loading").hide();
					})
					.fail(function (result) {
						console.log(result);
						$("#errors").html("Internal Error!");
						$("#btnCreateCase").html("<i class='fa fa-floppy-o' aria-hidden='true'></i> Save");
					});
			}
		}

		$("#btnAttach").click(function () {
			for (i = 1; i <= 5; i++) {
				if ($("#attachment_" + i).val().length == 0) {
					$("#attachment_" + i).trigger("click");
					return true;
				}
			}
			$("#maxFilesModal").modal("show");
		});

		$("input:file").change(function () {
			var template =
				`<tr id="row_{ID}">
                                <td>{FILE_NAME}</td>
                                <td><i style="cursot:pointer" onclick="removeAttachment('{ID}')" class="fa fa-trash"></i></td>
                            </tr>`;

			var id = $(this).attr('id').replace("attachment_", "");
			var row = template.replace("{FILE_NAME}", $(this).val().replace(/C:\\fakepath\\/i, ''));
			row = row.replace("{ID}", id);
			row = row.replace("{ID}", id);

			$("#attachments").append(row);
			$("#no_rows").remove();
		});

		function removeAttachment(id) {
			// Clean file field
			$("#attachment_" + id).val("");
			// Remove frow from table
			$("#row_" + id).remove();
			var files = false;
			for (i = 1; i <= 5; i++) {
				if ($("#attachment_" + i).val().length > 0) {
					files = true;
				}
			}
			if (files == false) {
				$("#attachments").html(`<tr id='no_rows'><td colspan="2">No files attached</td></tr>`);
			}
		}

		function attachFiles(caseId, index){
			if($("#attachment_" + index).val().length > 0) {
				var form_data = new FormData($('#form_attachment_' + index)[0]);
				$.ajax({
					type: 'POST',
					url: '/CaseManagement/addAttachment/' + caseId,
					processData: false,
					contentType: false,
					async: false,
					cache: false,
					data: form_data,
					success: function (response){
						if(index == 5)
						{
							window.location.href = "/case/" + caseId;
						}
						else{
							attachFiles(caseId, index + 1)
						}
						
					},
					fail: function (response) {
						console.log(response);
						if(index == 5)
						{
							window.location.href = "/case/" + caseId;
						}
						else{
							attachFiles(caseId, index + 1)
						}
					}
				});
			}
			else{
				if(index == 5)
				{
					window.location.href = "/case/" + caseId;
				}
				else{
					attachFiles(caseId, index + 1)
				}
			}
		};

		$(document).ready(function () {
			// Initial functions
		});

	</script>
