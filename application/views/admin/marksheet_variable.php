<style>
.notifi_no{
    width:200px;
}
</style>
<div class="card">
  <div class="card-body">
  <form>
  <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">

  <div class="row text-center">
    <div class="form-group col-md-6">
        <label for="exam_session">Exam Session</label>
        <input type="text" class="exam_session" id="exam_session" name="exam_session" value="<?php echo $course_detail[0]['exam_session']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label for="bar_code_no">Bar Code Number</label>
        <input type="text" class="bar_code_no" id="bar_code_no" name="bar_code_no" value="<?php echo $course_detail[0]['bar_code_no']; ?>">
    </div>
  </div>

    <table class="table" >
 
            <thead>
                <tr>
                    <th rowspan="2" class="text-center align-middle">#</th>
                    <th rowspan="2" class="text-center align-middle">Course</th>
                    <th rowspan="2" class="text-center align-middle">Class</th>
                    <th rowspan="2" class="text-center align-middle">Class ID</th>
                    <td colspan="2" class="text-center align-middle">Main</td>
                    <td colspan="2" class="text-center align-middle">Backlog</td>
                </tr>
                <tr>
                    <th>Notification No.</th>
                    <th>Result Date</th>
                    <th>Notification No.</th>
                    <th>Result Date</th>
                </tr>
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
                    <input type="text"  class="form-control result_date" value="<?php echo $course['result_date']; ?>" data-inputmask="'alias': 'dd/mm/yyyy'" name="result_date[]" id="result_date" placeholder="dd-mm-yyyy">
                </td>

                <td>
                    <input type="text" class="notifi_no" id="backlog_notifi_no" name="backlog_notifi_no[]" value="<?php echo $course['backlog_notification_no']; ?>">
                </td>
                <td>
                    <input type="text"  class="form-control backlog_result_date" value="<?php echo $course['backlog_result_date']; ?>" data-inputmask="'alias': 'dd/mm/yyyy'"  name="backlog_result_date[]" id="backlog_result_date" placeholder="dd-mm-yyyy">
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
    $(".result_date").inputmask();
    $(".backlog_result_date").inputmask();

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
