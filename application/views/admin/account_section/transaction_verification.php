<div style="min-height: 90vh; padding: 20px;">
<div class="card border-0">
  	<div class="card-body row text-center">
        <div class="form-group col-md-4 m-auto">
        <label for="center_id">Date Range</label>
        <input type="text" class="form-control" id="kt_daterangepicker_2" name="date_range" placeholder="Select Date Range" required>
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

<script>

$(document).on("click","#submit_btn",function(){
	$('#dt').hide();
    
	var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val();
    var daterange =  $("#kt_daterangepicker_2").val();
    var dates = daterange.split(" - ");

	var data = { 
		startDate : dates[0],
         endDate : dates[1], 
		[csrfName]:csrfHash
	};
	

	$.ajax({
		url:  BASE_URL+ 'admin/Account/get_payment_transactions',

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
              $('#kt_datatable').DataTable().destroy();

		KTDatatablesBasicBasic.init();
		 
});

 $(document).on("click", ".pay", function() {
 
            $('#uid').val($(this).attr("data-id"));
            $('#form_no').html($(this).attr("data-form"));
            $('#student_name').html($(this).attr("data-student"));
            $('#payment_date').html(moment($(this).attr("data-date")).format('DD-MM-YYYY'));
            $('#total_amount').html($(this).attr("data-amount"));
            $('#fees_head').html($(this).attr("data-head"));
        });

        $(document).on("click", "#payment_submit", function() {
            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val();
            var id = $('#uid').val();
            var data = {
                id: id,
                settle_date: $('#example-date-input').val(),
                [csrfName]: csrfHash
            };

            $.ajax({
                url: BASE_URL + 'admin/Account/verify_payment_transaction',
                type: 'post',
                dataType: 'JSON',
                data: data,
                beforeSend: function() {
                    $("#myLoader").show();
                },
                success: function(status) {
                  
                    if (status.status == 'success') {
                        toastr.success(status.message);
                        $("#row_" + id).next("tr").remove(); // extra detail row
                         $("#row_" + id).remove(); // s
                        // location.reload();
                    } else {
                        toastr.error(status.message);
                    }
                      $('#kt_datepicker_modal').modal('toggle');
                    $("#myLoader").hide();
                    
                }
            });
        });
	

</script>