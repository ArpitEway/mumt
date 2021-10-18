
<form method="POST" class="d-block ajaxForm" >
    <div class="form-row">
	
	<div class="form-group col-md-6">
	<label for="class">center Code</label>
	<input type="text" class="form-control" id="center_code" name="center_code" required="required" placeholder="Enter center code" readonly value="<?= $param1; ?>">
	</div>
	
	<div class="form-group col-md-6">
	<label for="class">center Name</label>
	<input type="text" required="required" class="form-control" id="center_name" name="center_name" placeholder="Enter center name"    >
	</div>
	
<!-- 	<div class="form-group col-md-12">
	<label for="class">Address</label>
	<textarea class="form-control" id="address" name="address"  required ></textarea>
	</div> -->
	
	<div class="form-group col-md-4">
		<label for="session">State</label>
		<select name="state"  required="required" id="state" class="form-control" data-target="#district"  >
		<option value="">Select State</option>
		<?php
		$states = $this->db->get_where('state', array())->result_array();
		
				foreach($states as $state)
                    {
                    ?>
					
           <option value="<?php echo $state['state_id']; ?>" ><?php echo $state['name']; ?></option>
            <?php
             } 
			?>
			
		</select>
	</div>
	
	<div class="form-group col-md-4">
		<label for="session">District</label>
		<select name="district"  required="required" id="district" class="form-control" >
		
		</select>
	</div> 

	<div class="form-group col-md-4">
		<label for="session">City</label>
		<input type="text" required="required" class="form-control" id="city" name="city" placeholder="Enter city"   >
		
    </div>
	
	<div class="form-group col-md-4">
		<label for="class">Contact Person</label>
		<input type="text" required="required" class="form-control" id="contact_person" name="contact_person"  placeholder="Enter contact person" required >
	</div>
	<div class="form-group col-md-4">
		<label for="class">Mobile no</label>
		<input type="text" required="required" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter mobile no"   >
	</div>
	<div class="form-group col-md-4">
		<label for="class">Mobile no 2</label>
		<input type="text" class="form-control" id="mobile_no_2" name="mobile_no_2" placeholder="Enter mobile no"   >
	</div>
		<div class="form-group col-md-4">
		<label for="class">Other Contact Person</label>
		<input type="text" required="required" class="form-control" id="contact_person_2" name="contact_person_2"  placeholder="Enter contact person" required >
	</div>
	<div class="form-group col-md-4">
		<label for="class">Other Mobile no.</label>
		<input type="text" required="required" class="form-control" id="other_mobile_no" name="other_mobile_no" placeholder="Enter mobile no"   >
	</div>
	<div class="form-group col-md-4">
		<label for="class">Other Mobile no. 2</label>
		<input type="text" class="form-control" id="other_mobile_no_2" name="other_mobile_no_2" placeholder="Enter mobile no">
	</div>
	<div class="form-group col-md-4">
		<label for="class">Email</label>
		<input type="text" required="required" class="form-control" id="email" name="email" placeholder="Enter email"   >
	</div>
	<div class="form-group col-md-4">
		<label for="class">Password</label>
		<input type="password" class="form-control" value="123456" id="password" name="password" placeholder="Enter password"  >
	</div>
	
	<div class="form-group col-md-4">
		<label for="session">Status</label>
		<select name="status" required="required" id="status" class="form-control" >
			<option value="Y" >Yes</option>
			<option value="N" >No</option>
		</select>
	</div>	
	
	<br>
	
</div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" id="center_submit" type="button" >Submit</button>
	</div>
</form>



<script>
$(document).on("change", "#state", function() {
	
		var type = $('option:selected', this).attr('data-type');
		
		var data = {
			state: $(this).val()
		};
		
		var target = $(this).attr("data-target");
		var url = BASE_URL + "admin/Admins/get_dist_by_state";
		var response = call_ajax(data, url);
		
		if(response.status == true) {
		$(target).html('<option value="">Select District</option>');
			
		for(var i = 0; i < response.data.length; i++) {
		$(target).append('<option value="' + response.data[i].distt_id + '">' + response.data[i].name + '</option>');
		}
		
		} 
});


$("#center_submit").on('click',function (e){
	
	var frm = $('.ajaxForm').serialize();
	
	$.ajax({
	url: '<?php echo site_url('admin/admins/centers/create'); ?>',
	type: 'POST',
	dataType : 'json',
	data: frm,
	success: function (data) {
	if(data){
		console.log(data);
			$('#right-modal').modal('toggle');
			toastr.success("Create Successfully");	
			location.reload();
			}else{
				toastr.error("Something wrong");
			}
		},
	});	
	
});	
</script>