<form  class="d-block ajaxForm" target="_blank" method="POST"  action="<?php echo site_url ('Dataentry/marks_entry_form'); ?>">
   <div class="row">
     <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
     <div class="form-group col-md-4">
        <label for="course">Course</label>
        <select  name="course_group_id" readonly="readonly" name='course_group_id' id="course_group_id" class="form-control course" required>
            <option value="">Select course</option>
            <?php
            foreach($courses as $course)
            {
                ?>
                <option value="<?php echo $course['course_group_id']; ?>"   ><?php echo $course['course_name']; ?></option>
                <?php
            } 
            ?>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="course">Class</label>
        <select name="class_id" name='class_id' id="class_id" class="form-control class" required>
            <option value="0">Select Class</option>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="paper">Paper</label>
        <select name="paper_code" id="paper_id" class="form-control paper" required>
            <option value="0">Select Paper</option>
        </select>
    </div>
    <div class="form-group col-md-4">
            <label for="exam_center">Exam Center</label>
            <select name="exam_center"  id="exam_center" class="form-control exam_center">
                <option value="all">Select Center</option>
                <?php  
                $exam_centers= $this->Common_model->getRecordByWhere('exam_center',array());
                foreach($exam_centers as $exam_center){
                 ?>
                 <option value="<?php echo $exam_center->id; ?>"><?php echo  $exam_center->examcentercode .'-'. $exam_center->schoolcollegename; ?></option>
                 <?php
             } 
             ?>
        </select>
    </div>
    <div class="form-group col-md-4">
         <label for="class">Admission Mode</label>
         <select name="university_mode" id="university_mode" class="form-control" >	 
            <option value="REG">Regular </option> 
            <option value="PVT" >Private</option>
        </select>
    </div>
<!--     <div class="form-group col-md-4">
        <label for="class">Exam Session</label>
        <input name="session" readonly="readonly" placeholder='session'  class="form-control " type="text" value="Feb 2022"/>
    </div> -->
    </div>

    <div class="form-group text-center">
    	<input type="hidden" class="" name="action1" value="submit">
    	<button class="btn btn-md btn-primary" type="submit" id="submit_form">Search</button>
    </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        $('#class_id').change(function(){
          var class_id =   $('#class_id').val();
          var data = {
            class_id: class_id,
            [csrfName]: csrfHash,
            }; 
        $.ajax({
            url:BASE_URL+'admin/Dataentry/getPaperByClassId',
            type:'post',
            dataType : 'JSON',
            data: data,
            success:function(data)
            {    
                var html = '';
                html += ('<option value="0">Select Paper</option>');
                $.each(data.data, function (i, value) {
                    html += (
                        '<option value="' + value.paper_code + '">'+'('+ value.paper_code + ') '+value.paper_name + '</option>');
                });
                $("#paper_id").html(html);
            } 
        })
    });
    });
    $('#course_group_id').select2({
        placeholder : 'Search Course',
        allowClear: true
    })
    $('#course_group_id').change(function(){
        var value = $(this).val();
        $('#course_group_id').trigger('change');
    });   
</script>