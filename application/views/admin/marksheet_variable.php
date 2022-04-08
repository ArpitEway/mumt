<style>

.notifi_no{
    width:200px;
}

</style>
<div class="card">
  <div class="card-body">
  <form>
  <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <table class="table">
        <thead>
            <th>#</th>
            <th>Course Name</th>
            <th>Class Name</th>
            <th>Class ID</th>
            <th>Notification No.</th>
            <th>Result Date</th>
        </thead>
        <tbody>
        <?php
        $i = 1;
        foreach($course_detail as $course){
        ?>

        <tr>
        <td>
                <?php echo $i; ?>
                </td>
                <td>
                <?php echo $course['course_name']; ?>
                </td>
                <td>
                <?php echo $course['class_name']; ?>
                </td>
                <td>
                <?php echo $course['class_id']; ?>
                </td>
                <td>
                <input type="hidden"  name="class_id[]" value="<?php echo $course['class_id']; ?>">
                <input type="text" class="notifi_no" id="notifi_no" name="notifi_no[]" value="<?php echo $course['notification_no']; ?>">
                </td>
                <td>
                <input type="text"  class="form-control" value="<?php echo $course['result_date']; ?>" data-inputmask="'alias': 'dd-mm-yyyy'" name="result_date[]" id="result_date" placeholder="dd-mm-yyyy">
                </td>
        </tr>
    <?php
    $i++; 
    }
    ?>
    </tbody>
    </table>  
    <div class="text-center">
    <a class="btn btn-success" id="submit" >Submit</a>
    </div>
  
</form>

</div>
</div>

<script>
    $("#result_date").inputmask();

    $(document).on("click","#submit",function(){
	$('#dt').hide();
    var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val();

	var data = $("form").serialize(); 
    console.log(data);

        $.ajax({
	    url: "<?=base_url('admin/Admins/update_marksheet_variable');?>",  
        type:'post',
		dataType : 'JSON',
		data:data,
		success:function(data){
            if(data.success)
            {
                toastr.success(data.success);
                }else{
                toastr.error(data.error);
            }
        }
		});   
		 
});

</script>
