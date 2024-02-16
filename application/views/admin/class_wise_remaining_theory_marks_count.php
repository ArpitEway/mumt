<style>
.course_css{
color:red;
}
</style>
<div class="card">
  <div class="card-body">
    <table  id="kt_datatable" class="table table-striped nowrap">
        <thead>
            <th>#</th>
            <th style="width:20%;">Course</th>
            <th>Class Id</th>
            <th style="width:20%;">Class</th>
            <th>REGULAR(Remaining)</th>
            <th>PRIVATE(Remaining)</th>
        </thead>
        <tbody>
        <?php
        $j =1; $i = 1;
            foreach($course_group as $course_detail){
            
               $mode=array("REG","PVT");
               $count=array();
               foreach($mode as $key=>$classMode){  
                    $set = array_column($class['id'],'id');
                    $this->db->select('count(*) as total');
                    $this->db->from('new_exam_form');
                    $this->db->join('student', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.old_class_id');
                    $this->db->where('student.exam_form','Y');
                    $this->db->where('student.university_mode',$classMode);                  
                    $this->db->where_in('new_exam_form.class_id',$course_detail['old_class_id']);
                    $this->db->where('new_exam_form.theory_marks','');
                    $this->db->where('new_exam_form.paper_type',"theory");
                   
                   
			        $count[$classMode]= $this->db->get()->result();
                  
               }
           
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td class="course_css">
                    <?php echo $course_detail['course_name']; ?>
                    </td>
                    <td><?php  echo $course_detail['old_class_id']; ?></td>
                    <td><?php  echo $course_detail['class_name']; ?></td>
                    <td>
                       
                        <?= $count['REG'][0]->total?>
                    </td>
                    <td>
                        <?= $count['PVT'][0]->total?>
                    </td>
                </tr>
                <?php
                    $i++;
              
            }
                ?>
    </tbody>
    </table>  
  </div>
</div>
	
