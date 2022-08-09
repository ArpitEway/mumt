<div class="  text-center p-3 ">
       <input type="hidden" id="multiple"  value="<?= $multiple; ?>">
       <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
       <div class="form-group col-md-3 mx-auto">
        <label for="course">Test ID</label>
        <select  name="test_id" readonly="readonly" id="test_id" class="form-control course" required>
            <option value="" selected >Select Test ID</option>
            <?php 

            foreach($list as $testid)
            {
                ?>
                <option value="<?php echo $testid->test_id; ?>"   ><?php echo $testid->test_id; ?></option>
                <?php
            } 
            ?>
        </select>
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
<div class="dt-responsive">

</div>  

<script>
   
   
  $("#test_id").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var test_id = $(this).val();
  $("#headerTitle").html(test_id);
  $('.dt-responsive').html("");
  console.log($("#multiple").val());
  var multiple=$("#multiple").val();
  $("#myLoader").show();
		$.ajax({
			method: "POST",
			url: BASE_URL+"ExamController/getEnvelope",
			data: { test_id : test_id,multiple:multiple,
					[csrfName]:csrfHash
					},
		})
		.done(function( msg ) {
            $("#myLoader").hide();
            $('.dt-responsive').html(msg);
		});
	});
</script>