<div class="card border-0">
  	<div class="card-body row text-center" >
        <div class="form-group col-md-3 m-auto">
        <label for="center_id">Center</label>
        <select name="center_id" id="center_id" class="form-control" required >
            <option value="">Select</option>
            <?php
            foreach($centers as $center)
            {
            ?>            
           		<option value="<?php echo $center['center_id']; ?>"><?php echo $this->Common_model->getSinglefield("center","center_code",array("id" => $center['center_id'] ))." (".$center['count'].")"; ?></option>
            <?php
            } 
            ?> 
        </select>       
        </div>	
		<div class="form-group col-md-12">
			<label for="class"></label>
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
			<button type="button" class="btn btn-primary mt-4" style="margin-top: 24px !important;" id="submit_btn">Submit</button>
		</div>
	</div>
</div>

<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>
<div id="dt">
    
</div>

</div>
<div class="modal" id="bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none">
  <div class="modal-dialog modal-md">
    <form id="remarkset">
      <div class="modal-content">
        <div class="modal-header"><label>Remark</label></div>
        <div class="container">
          <input type="hidden" name="complaint_id" id="complaint_id">
		  <textarea id="remark" name="remarks" class="form-control m-3"></textarea>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-danger mx-2" id="close">Close<button type="button" class="btn btn-primary" id="sub_btn">Submit</button></div>
      </div>
    </form>
  </div>
</div>

<script>

$(document).on("click","#submit_btn",function(){
	$('#dt').hide();

	var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 
	
	var data = {
		center_id : $("#center_id").val(),
		[csrfName]:csrfHash
	};
	

	$.ajax({
		url:  BASE_URL+ 'admin/admins/get_center_wise_complaints',

                type:'post',
                dataType : 'JSON',
                data:data,
                 beforeSend: function()
              {
                $("#myLoader").show();
               },
                success:function(status)
                {
					if( $("#myLoader").show()){
						$('#dt').hide();
						// $table = $('#dt').html(status.data);

					}if( $('#myLoader').hide()){
						$table = $('#dt').html(status.data);
						$('#dt').show();
						
					}
				
	               KTDatatablesBasicBasic.init();
                },
                   complete: function()
              {
                $('#myLoader').hide();
              },
            })

		KTDatatablesBasicBasic.init();
		 
});
function forward(i){

var dept = $("#Complaint_"+i).val();
var id = $("#com_"+i).val();
var csrfName = $('.csrfname').attr('name');
var csrfHash = $('.csrfname').val();

if(dept == ""){
    alert("Please Select Department");
}else{

    var url = BASE_URL + "admin/admins/forward_complaint";
    var data = {
    id: id,
    dept: dept,
    [csrfName]: csrfHash,
};

$.ajax({
    url: url,
    type: 'POST',
    dataType: 'json',
    data: data,
    success: function (data) {
        
$('#row_'+i).hide();
        
    }
});
}

}

</script>
<script>
		
				$(document).on('click', '.req_check', function() {

					var val = $(this).val();
					var csrfName = $('.csrfname').attr('name');
					var csrfHash = $('.csrfname').val();
					var self = this;

					var status = (val=='Done') ? 'Pending' : 'Done';

					var data = {
						id: $(this).attr('data-id'),
						status: status,
						[csrfName]: csrfHash,
					}; 

					var url = BASE_URL + "admin/Admins/update_center_wise_complaint_status";

					$.ajax({
						url: url,
						type: 'POST',
						dataType: 'json',
						data: data,
						success: function (data) {

							$(self).parent().html(data.data);

						}
					});

				});

		$(document).on('click', '.remark_check', function() 
		{

			var val = $(this).val();
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			var self = this;

			var remark = (val=='Set') ? 'Invalid' : 'Set';

			var data = {
				id: $(this).attr('data-id'),
				remark: remark,
				[csrfName]: csrfHash,
			};
			var url = BASE_URL + "admin/admins/update_center_wise_complaint_remark";

			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function (data) {
					$(self).parent().prev().html(data.statusBtn);
					$(self).parent().html(data.remarkBtn);
				}
			});
		});

		$(document).on('click', '#sub_btn', function() 
		{

			var val = $(this).val();
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			var self = this;
			var complaint_id = $("#complaint_id").val();
			var remark = $("#remark").val();

			var data = {
				complaint_id: complaint_id,
				remark: remark,
				[csrfName]: csrfHash,
			};
			var url = BASE_URL + "admin/admins/complaint_reply";

			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function (data) {
					
					$("#sta_"+complaint_id).html(data.statusBtn);
					$("#rem_"+complaint_id).html(data.remarkBtn);
					$("#rep_"+complaint_id).html(data.replyBtn);
          			$("#complaint_id").val('');
			   		 $("#ctr_id").val('');
			    	$("#remark").val('');
					$('#bd-example-modal').hide();
				}
			});
		});
	</script>