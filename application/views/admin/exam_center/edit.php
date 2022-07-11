<?php

$exam_centers = $this->db->get_where('exam_center', array('id' => $param1))->result_array();

foreach($exam_centers as $exam_center): ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/ExamController/exam_center/update/'.$param1); ?>">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="form-row">
    <div class="form-group col-md-6">
            <label for="examcentercode">Exam Center Code*</label>
            <input type="text" class="form-control" id="examcentercode" name = "examcentercode" value="<?php echo $exam_center['examcentercode']; ?>" required placeholder="Enter Exam Center Code">
        </div>

        <div class="form-group col-md-6">
            <label for="schoolcollegename">School/College Name*</label>
            <input type="text" class="form-control" id="schoolcollegename" name = "schoolcollegename" value="<?php echo $exam_center['schoolcollegename']; ?>"  required placeholder="Enter School/College Name">
        </div>

        <div class="form-group col-md-12">
            <label for="examcenteraddress">Exam Center Address*</label>
            <textarea type="text" class="form-control" id="examcenteraddress" name = "examcenteraddress"   placeholder="Enter Exam Center Address"><?php echo $exam_center['examcenteraddress']; ?></textarea>
        </div>
		
		<div class="form-group col-md-4">
		<label for="session">City*</label>
		<input type="text" required="required" class="form-control" id="city" name="city" value="<?php echo $exam_center['city']; ?>" required placeholder="Enter city"   >
        </div>
            
        <div class="form-group col-md-4">
            <label for="session">District*</label>
            <input type="text" required="required" class="form-control" id="district" name="district" value="<?php echo $exam_center['district']; ?>" required placeholder="Enter district"   >
        </div>

        <div class="form-group col-md-3">
            <label for="class">Pin Code*</label>
            <input type="text" required="required" class="form-control" id="pin_code" name="pin_code"  value="<?php echo $exam_center['pincode']; ?>" required placeholder="Enter pin code" required >
        </div>
        

        <div class="form-group col-md-8">
            <label for="name">Super Intendent*</label>
            <input type="text" class="form-control" id="superintendent" name = "superintendent" value="<?php echo $exam_center['superintendent']; ?>" required placeholder="Enter Super Intendent">
        </div>
        <div class="form-group col-md-4">
		<label for="class">Phone Number*</label>
		<input type="text" required="required" class="form-control" id="phonenumber" name="phonenumber" value="<?php echo $exam_center['phonenumber']; ?>" required placeholder="Enter Phone number" >
	    </div>

        <div class="form-group col-md-12" style="margin-bottom: 5px;">
            <label for="name"><strong>Bank Detail</strong></label>
        </div>

		<div class="form-group col-md-3">
            <label for="name">Bank Account Number*</label>
            <input type="text" class="form-control" id="bankaccountnumber" name = "bankaccountnumber" value="<?php echo $exam_center['bankaccountnumber']; ?>" required  placeholder="Enter Bank Account Number" >
        </div>
        <div class="form-group col-md-3">
            <label for="name">Bank Name*</label>
            <input type="text" class="form-control" id="bankname" name = "bankname" value="<?php echo $exam_center['bankname']; ?>" required placeholder="Enter Bank name">
        </div>
        
		<div class="form-group col-md-3">
            <label for="name">Bank Branch*</label>
            <input type="text" class="form-control" id="bankbranch" name = "bankbranch" value="<?php echo $exam_center['bankbranch']; ?>" required   placeholder="Enter Bank branch" >
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Bank ISFC*</label>
            <input type="text" class="form-control" id="bankisfc" name = "bankisfc" value="<?php echo $exam_center['bankisfc']; ?>" required placeholder="Enter Bank ISFC">
        </div>

        <div class="form-group col-md-12" style="margin-bottom: 5px;">
            <label for="name"><strong>Center Supervisor </strong></label>
        </div>
        <div class="form-group col-md-4">
            <label for="name">Center Supervisor Name*</label>
            <input type="text" class="form-control" id="csname"  name = "csname" value="<?php echo $exam_center['csname']; ?>" required placeholder="Enter Center Supervisor Name" >
           
        </div>
        <div class="form-group col-md-4">
            <label for="name">Center Supervisor Number-1*</label>
            <input type="text" class="form-control" id="csnumber_1"  name = "csnumber_1" value="<?php echo $exam_center['csnumber_1']; ?>" required placeholder="Enter Center Supervisor Number-1*">
           
        </div>
		<div class="form-group col-md-4">
            <label for="name">Center Supervisor Number-2*</label>
            <input type="text" class="form-control" id="csnumber_2"  name = "csnumber_2" value="<?php echo $exam_center['csnumber_2']; ?>" required placeholder="Enter Center Supervisor Number-2*" >
           
        </div>
	
        
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update</button>
	</div>
</form>


<?php endforeach; ?>

<script>
    $(".ajaxForm").validate({}); 
    $(".ajaxForm").submit(function(e) {
      e.preventDefault();
      var form = $(this);
      ajaxSubmit(e, form, showAllcourse);
    });
</script>
