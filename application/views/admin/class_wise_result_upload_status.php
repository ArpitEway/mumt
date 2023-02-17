<style>
.course_css{
color:red;
}
</style>
<div class="card">
  <div class="card-body">
    <table class="table">
        <thead>
            <th>#</th>
            <th style="width:40%;">Name</th>
            <th>Check Status</th>
           
        </thead>
        <tbody>
        <?php
        $j =1;
            foreach($course_group as $course_detail){
                ?>
                <tr>
                <td class="course_css"><?=$j++; ?></td>
                <td class="course_css">
                <?php echo $course_detail['course_name']; ?>
                </td>
                <td class="course_css">
			<!--<a href="<?php echo base_url("class_wise_result_upload_status_report")."/".$course_detail['id']; ?>" target="_blank" >Check Status</a>-->
			<!--<a href="<?php echo base_url("class_wise_result_upload_status_report")."/ALL/".$course_detail['course_group_id']; ?>" target="_blank" >Check Status</a> -->
		</td>
                      
            </tr>
                <?php 
                $this->db->order_by('mode','desc');
                $this->db->order_by('class_name','ASC');
                $this->db->where_in('id',array(262 , 222 , 155 , 224 , 228 , 107 , 182 , 159 , 214 , 256 , 202 , 196 , 212 , 206 , 268 , 287 , 285 , 181 , 154 , 168 , 104 , 171 , 293 , 119 , 291 , 128 , 134 , 110 , 208 , 165 , 200 , 299 , 125 , 198 , 194 , 131 , 226 , 283 , 101 , 183 , 297 , 295 , 204 , 116 , 180 , 289 , 177 , 137 , 210 , 274 , 163 , 175 , 172 , 273 , 173 , 191 , 178 , 169 , 164 , 170 , 174 , 160 , 162));
                $class_master = $this->db->get_where('class_master', array('course_group_id' => $course_detail['course_group_id']))->result_array();
                // ,'exam_form_permission' => 'Y'
                
                
                $i = 1;
               $account_type = ($this->session->account_type=='Admins') ? '' : $this->session->account_type.'/';
                foreach($class_master as $class){
                    $flag="";                
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $class['class_name']; ?></td>
                    <td>
                    <?php if($class['regular_class']=='Y' && $class['private_class']=='Y') { ?>    <a href="<?php echo base_url($account_type."class_wise_result_upload_status_report")."/ALL/".$course_detail['course_group_id']."/".$class['id']; ?>" target="_blank" > All Status</a>
                    <?php 
                    $flag="/";
                    } if($class['regular_class']=='Y') { echo $flag; ?>    
                    <a href="<?php echo base_url($account_type."class_wise_result_upload_status_report")."/REG/".$course_detail['course_group_id']."/".$class['id']; ?>" target="_blank" > Regular Status</a>
                    <?php } if($class['private_class']=='Y') { echo $flag; ?>
                    <a href="<?php echo base_url($account_type."class_wise_result_upload_status_report")."/PVT/".$course_detail['course_group_id']."/".$class['id']; ?>" target="_blank" > Private Status</a>
                     <?php } ?>   
                </td>
                </tr>
                <?php
                    $i++;
                } 
            }
                ?>
    </tbody>
    </table>  
  </div>
</div>
	
