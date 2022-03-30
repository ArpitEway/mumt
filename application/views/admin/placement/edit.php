<?php

$companies = $this->db->get_where('company', array('id' => $param1))->result_array();

?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Placement/company/update/'.$param1); ?>">
    <div class="form-row" >
	    
        <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        
		<div class="form-group col-md-6">
            <label for="name">Company Name</label>
            <input type="text" class="form-control" value="<?php echo $companies[0]['company_name']; ?>" id="company_name" name="company_name" placeholder="Enter Company Name" required >        
        </div>
        <div class="form-group col-md-6">
            <label for="name">Job Title</label>
            <input type="text" class="form-control" value="<?php echo $companies[0]['job_title']; ?>" id="job_title" name="job_title" placeholder="Enter Job Title" required >        
        </div>
        <div class="form-group col-md-12">
            <label for="name">Minimum Qualification</label>
            <input type="text" class="form-control" value="<?php echo $companies[0]['min_qualification']; ?>" id="min_qualification" name="min_qualification" placeholder="Enter Min Qualification" required >        
        </div>
        <div class="form-group col-md-6">
            <label class="col-form-label">Description</label>
            <textarea class="form-control description" placeholder="Description" id="kt_autosize_2" rows="4" name="description"  ><?php echo $companies[0]['description']; ?></textarea>
        </div>
        <div class="form-group col-md-6">
            <label class="col-form-label">Other detail</label>
            <textarea class="form-control description" placeholder="Other Detail" id="kt_autosize_2" rows="4" name="other_detail"><?php echo $companies[0]['other_detail']; ?></textarea>
        </div>

        <div class="form-group col-md-12 text-center">
            <button class="btn btn-md btn-primary" type="submit">Submit</button>
        </div>
		
</div>
</form>



<script>

$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAlldepartment);
});
</script>

