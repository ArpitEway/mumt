

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/enrollment/update_unpaid_student'); ?>">




    <div class="form-row">
        <div class="form-group col-md-6">
      <label for="name">Payment date</label>
      <input type="date" class="form-control" readonly placeholder="Select date"    name="payment_date" id="example-date-input"  />
	  <input type="hidden" value="<?=$param1?>" name="student_id" id="student_id">
	  
     
            <!--<small id="" class="form-text text-muted">provide department name</small>-->
        </div>
        
      
    </div>
<div class="form-group text-center">
	<button class="btn btn-md btn-primary" id="payment_submit" type="button">Submit</button>
</div>
</form>

<script>
 $("#payment_submit").on('click',function (e){
	var student_id = <?php echo $param1 ?>;
		var frm = $('.ajaxForm').serialize();
		
   
		$.ajax({
		url: '<?php echo site_url('admin/enrollment/update_unpaid_student'); ?>',
		type: 'POST',
		dataType : 'json',
		data: frm,
		success: function (data) {
		if(data){
			console.log(data);
			
			$('#student_tr_'+student_id).remove();
			
			$('#right-modal').modal('toggle');
			
			student_tr_
			location.reload();
			toastr.success("Submitted");
			
			
			
		}else{
			toastr.error("Something wrong");
		}
			},
		});	
	
});	
    
$(function() {
  $('[data-toggle="datepicker"]').datepicker({
    autoHide: true,
    zIndex: 2048,
  });
});

</script>

