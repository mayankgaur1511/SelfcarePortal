<div class="card pd-20 pd-sm-40">
	<div class="form-layout">
		<div class="row mg-b-25">
            <div class="col-lg-3">
				<div class="form-group">
					<label class="form-control-label">
						<?php echo $this->lang->line('status');?>
					</label>
					<input type="text" class="form-control" value="<?php echo $case->Status?>" disabled>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label class="form-control-label">
						<?php echo $this->lang->line('impacted_country');?>
					</label>
					<input type="text" class="form-control" value="<?php echo $case->T_Country_impacted__c?>" disabled>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Product Type</label>
					<input type="text" class="form-control" value="<?php echo $case->ProductType__c?>" disabled>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Sub Product</label>
					</select>
					<input type="text" class="form-control" value="<?php echo $case->Product__c?>" disabled>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Request category
					</label>
					<input type="text" class="form-control" value="<?php echo $case->Category_NEW__c?>" disabled>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Impact</label>
					<input type="text" class="form-control" value="<?php echo $case->BusinessImpact__c?>" disabled>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Urgency</label>
					<input type="text" class="form-control" value="<?php echo $case->Severity__c?>" disabled>
				</div>
			</div>
			<?php if(isset($case->Product_L2__c)):?>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Product L2</label>
					<input type="text" class="form-control" value="<?php echo $case->Product_L2__c?>" disabled>
				</div>
			</div>
			<?php endif ?>
			<?php if(isset($case->Symptom_L1__c)):?>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Symptom</label>
					<input type="text" class="form-control" value="<?php echo $case->Symptom_L1__c?>" disabled>
				</div>
			</div>
			<?php endif ?>
			<?php if(isset($case->Symptom_L2__c)):?>
			<div class="col-lg-3">
				<div class="form-group">
					<label>Symptom 2</label>
					<input type="text" class="form-control" value="<?php echo $case->Symptom_L2__c?>" disabled>
				</div>
			</div>
			<?php endif ?>
		</div>
		<div class="row mg-b-25">
			<div class="col-lg-7">
				<div class="form-group">
					<label>Subject
						<span style="color:RED">*</span>
					</label>
					<input type="text" class="form-control" value="<?php echo $case->Subject?>" disabled>
				</div>
				<div class="form-group">
					<label>Description
						<span style="color:RED">*</span>
					</label>
					<textarea rows="4" class="form-control" disabled><?php echo $case->Description?></textarea>
                </div>
                
				<table class="table table-hover" style="word-wrap: break-word;">
					<thead>
                        <th colspan="3" style="font-weight:normal">
                            Comments
                        </th>
					</thead>
					<tbody id="comments">
                        <?php if(isset($comments) and $comments):?>
                            <?php foreach($comments as $comment): ?>
                            <tr>
                                <td nowrap style="width:1%">
                                    <?php echo date("d/m/y H:i", strtotime($comment['CreatedDate']))?>
                                </td>
                                <td style="word-wrap: break-word;">
                                    <?php echo $comment['body']?>
                                </td>
                                <td nowrap style="text-align:right">
                                    <?php echo $comment['creator']?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        <?php endif ?>
						<?php if(!isset($comments) or !$comments):?>
						<tr id="no_rows">
							<td colspan="2">No comments added</td>
						</tr>
						<?php endif ?>
					</tbody>
                </table>
                <textarea id="CommentBody" rows="2" maxlength="220" class="form-control"></textarea>
                <div style="margin-top:5px;">
                    <button style="float:right" id="btn_submit" type="button" class="btn btn-default" data-dismiss="modal" onclick="addCaseComment()">Send</button>
                </div>
			</div>

			<div class="col-lg-5">
				<BR>
				<table class="table table-hover">
					<thead>
						<th colspan="2" style="font-weight:normal;">Attachments
                            <i id="btnAttach" style="cursor:pointer;float:right" class="fa fa-plus" aria-hidden="true"></i>
                        </th>
					</thead>
					<tbody id="attachments">
                        <?php if(isset($files) and $files): ?>
                            <?php foreach($files as $file): ?>
                            <tr>
                                <td>
                                    <?php echo $file['fileName']?>
                                </td>
                                <td>
                                    <?php if($file['objectType'] == "DOCUMENT"): ?>
                                        <a href="/casemanagement/downloadDocument/<?php echo $file['id']?>">
                                            <i style="cursot:pointer" class="fa fa-download"></i>
                                        </a>
                                    <?php endif ?>

                                    <?php if($file['objectType'] == "ATTACHMENT"): ?>
                                        <a href="/casemanagement/downloadAttachment/<?php echo $file['id']?>">
                                            <i style="cursot:pointer" class="fa fa-download"></i>
                                        </a>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        <?php endif ?>
						<?php if(!isset($files) or !$files):?>
                            <tr id="no_rows">
                                <td colspan="2">No files attached</td>
                            </tr>
						<?php endif ?>
					</tbody>
                </table>
                <form id="form_attachment" method="POST" enctype="multipart/form-data">
                    <input type="file" id="attachment" name="attachment" style="display:none">
                </form>
			</div>
		</div>
	</div>
</div>

<!-- BASIC MODAL -->
<div id="waitModal" class="modal fade">
	<div class="modal-dialog modal-dialog-vertical-center" role="document">
		<div class="modal-content bd-0 tx-14">
			<div class="modal-header pd-y-20 pd-x-25">
				<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?php echo $this->lang->line("please_wait"); ?></h6>
				<div style="float:right">
					<img src="/assets/img/loading_F8F9FA.gif"></img>
				</div>
			</div>
			<div class="modal-body pd-25">
				<p class="mg-b-5"><?php echo $this->lang->line("wait_request_processing"); ?></p>
			</div>
		</div>
	</div>
	<!-- modal-dialog -->
</div>
<!-- modal -->


<script>
	
	function addCaseComment(){
        $("#btn_submit").html('<i class="fa fa-circle-o-notch fa-spin"></i> Processing');
        $("#CommentBody").attr("disabled",true);
		var data = {
			'Id': "<?=$case->Id?>",
			'CommentBody': $("#CommentBody").val()
		};

		$.post("/CaseManagement/addComment", data, function (result){
				console.log(result);
                var row =   "<tr><td>Just now</td>" + 
                            "<td>" + $("#CommentBody").val() + "</td>" +
                            "<td nowrap style='text-align:right'>By you</td></tr>";
                
                $("#comments").prepend(row);
                $("#CommentBody").val("");
                $("#CommentBody").removeAttr("disabled");
                $("#btn_submit").html('Send');
                $("#no_rows").hide();
			})
			.fail(function (result) {
				alert("Error, please try again!");
				console.log(result);
			});
	}

	$("#btnAttach").click(function () {
		$("#attachment").trigger("click");
	});

    $("#btnComment").click(function () {
		$("#commentModal").modal("show");
	});

	$("#attachment").change(
		function() {
			if ($("#attachment").val().length > 0) {
                $("#waitModal").modal("show");
				var form_data = new FormData($('#form_attachment')[0]);
                console.log($('#form_attachment'));
				$.ajax({
					type: 'POST',
					url: '/CaseManagement/addAttachment/<?php echo $case->Id?>',
					processData: false,
					contentType: false,
					async: false,
					cache: false,
					data: form_data,
					success: function (response) {
                        var row = "<tr><td>" + $("#attachment").val().replace(/C:\\fakepath\\/i, '') + "</td>" +
                                  "<td>" + "<a href='/casemanagement/downloadAttachment/" + response.id + "'>" +
                                  "<i style='cursot:pointer' class='fa fa-download'></i></a></td></tr>";
						$("#attachments").append(row);
                        $("#waitModal").modal("hide");
					},
					fail: function (response) {
						console.log(response);
                        $("#waitModal").modal("hide");
						alert("Error! Please try again.");
					}
				});
			}
		});

</script>
