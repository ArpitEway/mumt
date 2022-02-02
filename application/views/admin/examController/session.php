
<form method='POST' action="<?=base_url('admin/ExamController/generate_enrollment');?>">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="col-sm-4 m-auto">
		<fieldset class="form-group">
			<label for="exampleSelect1">Action</label>
			<select class="form-control" name="session" id="session" class="session">
            <option value="" >select </option>

            <?php
		$i = 1;
        $sessions = $this->db->get_where('session', array())->result_array();
        foreach ($sessions as $row) { ?>
            <option value="<?=$row['session'];?>" ><?=$row['session'];?></option>
<?php } ?>
			</select>
      
		</fieldset>
		<fieldset class="form-group text-center">
			<button type="submit" class="btn btn-primary">Submit</button>
		</fieldset>
</div>

</form>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
   $("#session").on('change',function (){		
     var session = $('#session').val();
     var session = {
                  session: $('#session').val(),
				  [csrfName]:csrfHash,
			}; 
   
		var url = BASE_URL+"admin/ExamController/generate_enrollment"; 
		var response = call_ajax(data,url);
		console.log(response);
		
		$('#dt').html(response.data);
});
    </script>