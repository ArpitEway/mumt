<form method="post" action="<?=base_url('admin/Admins/remove_student_result_permission')?>" class="mt-3" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
     <div class="text-center p-3">
		<button type="submit" class="btn btn-primary btn-lg" id="submit" name="submit" >Remove Result Permission</button>
	</div>
<hr>


    <table class="table table-striped" id="kt_datatable">
        <tbody>
            <tr>
                <th>#</th>
                <th>Roll No</th>
                <th>ICCode </th>
                <th>Student Name </th>
                <th>Course Name</th>
                <th>Class</th>
                <th>Paper(WH)</th>
                <th><input type="checkbox"  id="all_checked_permitt"></th>
            </tr>
            <?php $i=0;foreach($students as $student) {
                
                $i++;
                ?>
            <tr>
                <input type="hidden" name ="class_id" value = "<?php echo  $student->old_class_id  ?>">
                <input type="hidden" name ="course_group_id" value = "<?php echo  $student->course_group_id  ?>">
                <td><?= $i ?></td>
                <td><?= $student->roll_number ?></td>
                <td><?= $student->center_code ?></td>
                <td><?= $student->name ?></td>
                <td><?= $student->course_name ?></td>
                <td><?= $student->class_name ?></td>
                <td><?= $student->cnt ?></td>
                <td><input type="checkbox" name="permitted[]" value="<?=$student->student_id;?>"></td>
            </tr>
            <?php  }  ?>
        </tbody>
    </table>
</form>
<script>
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
