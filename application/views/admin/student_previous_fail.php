<!--  -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo (isset($title)) ? $title : ''; ?></title>
</head>
<body>
<form method="post" action="<?=base_url('admin/Admins/remove_student_result_permission_previous')?>" class="mt-3" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
     <div class="text-center p-3">
		<button type="submit" class="btn btn-primary btn-lg" id="submit" name="submit" >Remove Result Permission</button>
	</div>
     <table class="table table-striped" id="kt_datatable">
        <thead>
        <tr>
                <th>#</th>
                <th>IC Code </th>
                <th>Roll No</th>
                <th>Enrollment No</th>
                <th>Student Name </th>
                <th>Course Name</th>
                <th>Class</th>
               
                <th><input type="checkbox"  id="all_checked_permitt"></th>
            </tr>
        </thead>
        <tbody>
           
            <?php 
            // echo count($students);die;
            $i=1;foreach($students as $student) {
                 $this->db->where_in('exam_result',array('FAIL'));
                 $this->db->order_by('id', 'desc');
                 $this->db->limit(1);
                 $results = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student->student_id,'class_id<'=>$student->class_id));
                //  $this->db->where_in('exam_result',array('PASS', 'PASS BY GRACE'));
                //  $results2 = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student->student_id,'class_id<'=>$student->old_class_id));
                //  $this->Common_model->last_query();
                //  echo count($results);die;
                 
                 if(count($results)!=0){

                    
               
                foreach($results as $res){
                    $this->db->where_in('exam_result',array('PASS', 'PASS BY GRACE'));
                 $results2 = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student->student_id,'class_id'=>$res->class_id));
                 if(count($results2) !=0){
                    continue;
                 }
                ?>
            <tr>
                <input type="hidden" name ="class_id" value = "<?php echo  $student->class_id  ?>">
                <input type="hidden" name ="course_group_id" value = "<?php echo  $student->course_group_id  ?>">
                <td><?= $i++ ?></td>
                <td><?= $student->center_code ?></td>
                <td><?= $student->roll_no ?></td>
                <td><?= $student->enrollment_no ?></td>
                <td><?= $student->name ?></td>
                <td><?= $student->course_name ?></td>
                <td><?= $this->Common_model->getClassNameByClassId($res->class_id) ?></td>
               
                <td><input type="checkbox" name="permitted[]" value="<?=$student->student_id;?>"></td>
            </tr>
            <?php 
                }    
            }
        }  ?>
        </tbody>
    </table>
</form>
    </body>
</html>
<script>
     $("#headerTitle").html('<?= $title?>');
    $(document).on('click', '#submit', function(e) {
    if($("input:checkbox").filter(":checked").length<1){
        toastr.error("PLease Select atleast one");
        return false ;
    }
    })

    $('#all_checked_permitt').on('change', function() {
        if($('#all_checked_permitt').is(":checked")){
            setCheckboxes3(1 ,'permitted');
        }else{
            setCheckboxes3(2 ,'permitted');
        }
    });

    function setCheckboxes3(act , not_permitted)
    {
        if(not_permitted=='not_permitted'){
            elts = document.getElementsByName("not_permitted[]");
        }else{
            elts = document.getElementsByName("permitted[]");
        }
        var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;
        if (elts_cnt)
        {
            for (var i = 0; i < elts_cnt; i++)
                {
                    elts[i].checked = (act == 1 || act == 0) ? act : (elts[i].checked ? 0 : 1);
                }
        }
     }
</script>
