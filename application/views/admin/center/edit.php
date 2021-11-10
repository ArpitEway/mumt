<?php 

$centers = $this->db->get_where('center', array('id' => $param1))->result_array();

foreach($centers as $center): 

?>
<form method="POST" class="d-block ajaxForm" >
    <div class="form-row">
	<div class="form-group col-md-4">
    	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<label for="class">Center Code</label>
	<input type="text" class="form-control" id="center_code" name="center_code" placeholder="Enter center code" value="<?php echo $center['center_code']; ?>"  >
	</div>
	
	<div class="form-group col-md-8">
	<label for="class">Center Name</label>
	<input type="text" class="form-control" id="center_name" name="center_name" placeholder="Enter center name" value="<?php echo $center['center_name']; ?>"   >
	</div>
	
	<div class="form-group col-md-12">
	<label for="class">Address</label>
	<textarea class="form-control" id="address" name="address"  required ><?php echo $center['address']; ?></textarea>
	</div>
	
	<div class="form-group col-md-4">
		<label for="session">State</label>
		<select name="state" id="state" class="form-control" data-target="#district"  >
		<option value="">Select State</option>
		<?php
		$states = $this->db->get_where('state', array())->result_array();
		
			foreach($states as $state)
            {
            ?>
					
           <option value="<?php echo $state['state_id']; ?>" <?php  if($center['state_id'] == $state['state_id']){ echo "selected"; } ?> ><?php echo $state['name']; ?></option>
        	
			<?php
             } 
			?>
			
		</select>
	</div>
	
	<div class="form-group col-md-4">
		<label for="session">District</label>
		<select name="district" id="district" class="form-control" >
		<option value="">Select District</option>
		<?php
		$Distt = $this->db->get_where('distt', array("state_id" => $center['state_id']))->result_array();
		foreach($Distt as $dist)
        {
    	?>
					
        <option value="<?php echo $center['distt_id']; ?>" <?php  if($center['distt_id'] == $dist['distt_id']){ echo "selected"; } ?>   ><?php echo $dist['name']; ?></option>
                    
		<?php
        } 
         ?>
		</select>
	</div> 

	<div class="form-group col-md-4">
		<label for="session">City</label>
		<input type="text" class="form-control" id="city" name="city" placeholder="Enter city" value="<?php echo $center['city']; ?>"  >
		
    </div>
	<div class="form-group col-md-4">
		<label for="class">Contact Person</label>
		<input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo $center['contactpersonname']; ?>" placeholder="Enter contact person" required >
	</div>
	<div class="form-group col-md-4">
		<label for="class">Mobile no.</label>
		<input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter mobile no" value="<?php echo $center['mobile_no_1']; ?>"  >
	</div>
	
	<div class="form-group col-md-4">
		<label for="class">Mobile no 2</label>
		<input type="text" value="<?php echo $center['mobile_no_2']; ?>" class="form-control" id="other_mobile_no" name="mobile_no_2" placeholder="Enter mobile no"  >
	</div>

	<div class="form-group col-md-4">
		<label for="class">Email</label>
		<input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo $center['email']; ?>"  >
	</div>
	<div class="form-group col-md-3">
		<label for="class">Pin Code</label>
		<input type="text" value="<?php echo $center['pin_code']; ?>" required="required" class="form-control" id="pin_code" name="pin_code"  placeholder="Enter pin code" required >
	</div>
	<div class="form-group col-md-3">
		<label for="class">Password</label>
		<input type="text" value="<?php echo $center['password']; ?>" class="form-control" id="password" name="password" placeholder="Enter password"  >
	</div>
	<div class="form-group col-md-2">
		<label for="session">Status</label>
		<select name="status" id="status" class="form-control" >
			<option value="Y" <?php  if($center['status'] == "Y"){ echo "selected"; } ?> >Yes</option>
			<option value="N"  <?php  if($center['status'] == "N"){ echo "selected"; } ?>>No</option>
		</select>
	</div>	

</div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" id="center_submit" type="button" >Submit</button>
	</div>
</form>
<?php endforeach; ?>


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
	url: '<?php echo site_url('admin/admins/centers/update/'.$param1); ?>',
	type: 'POST',
	dataType : 'json',
	data: frm,
	success: function (data) {
	if(data){
		console.log(data);
			$('#right-modal').modal('toggle');
			toastr.success("Updated Successfully");	
			location.reload();
			}else{
				toastr.error("Something wrong");
			}
		},
	});	
	
});	
</script>