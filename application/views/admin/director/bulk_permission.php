<?php 

$centers = $this->db->get_where('center', array("status" => 'Y'))->result_array();


$center1 = $this->db->get_where('center', array("admission_permission" => 'Y'))->result_array();
$center2 = $this->db->get_where('center', array("exam_form_permission" => 'Y'))->result_array();
$center3 = $this->db->get_where('center', array("admit_card_permission" => 'Y'))->result_array();
$center4 = $this->db->get_where('center', array("result_permission" => 'Y'))->result_array();
$center5 = $this->db->get_where('center', array("admission_permission_private" => 'Y'))->result_array();


$admission_permission_count = count($center1);
$exam_form_permission_count = count($center2);
$admit_card_permission_count = count($center3);
$result_permission_count = count($center4);
$admission_permission_private_count = count($center5);
$late_exam_fees_privte = $this->Common_model->getRecordByWhere('master');

?>

<div class="container">
    <div class="card">
        <table class="table table-striped dt-responsive nowrap" width="100%" >

            <tr class="text-center">
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
                <td class="pt-5">Admission Permission</td>
                <td id="admission_permission">
                <?php if($admission_permission_count > 500)
                { ?>
                
                <a class="btn btn-primary" onclick="update_permission('admission_permission','N')">All Yes</a>
    
                <?php }else{ ?>

                <a class="btn btn-danger" onclick="update_permission('admission_permission','Y')">All No</a>
             
             <?php } ?> 
            </td>
            </tr>


            <tr class="text-center">
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
                <td class="pt-5">Admission Permission Private</td>
                <td id="admission_permission_private">
                <?php if($admission_permission_private_count > 500)
                { ?>
                
                <a class="btn btn-primary" onclick="update_permission('admission_permission_private','N')">All Yes</a>
    
                <?php }else{ ?>

                <a class="btn btn-danger" onclick="update_permission('admission_permission_private','Y')">All No</a>
             
             <?php } ?> 
            </td>
            </tr>
         <?php   $accessFrom="director";
          if($this->session->account_type=='Admins'){ 
            $accessFrom="Admins";
              ?>
             <tr class="text-center">
                <td class="pt-5">Exam Form Permission</td>
                <td id="exam_form_permission">
                <?php if($exam_form_permission_count > 500) { ?>
                
                <a class="btn btn-primary" onclick="update_permission('exam_form_permission','N')">All Yes</a>
                
                <?php }else{ ?>
                
                <a class="btn btn-danger" onclick="update_permission('exam_form_permission','Y')">All No</a>

                <?php } ?>

                </td>
            </tr> 

             <tr class="text-center">
                <td class="pt-5">Admit Card Permission</td>
                <td id="admit_card_permission">
                <?php if($admit_card_permission_count > 500) { ?>
                
                <a class="btn btn-primary" onclick="update_permission('admit_card_permission','N')">All Yes</a>
                
                <?php }else{ ?>

                <a class="btn btn-danger" onclick="update_permission('admit_card_permission','Y')">All No</a>
                
    
                <?php } ?>
                </td>
            </tr> 
 
            <tr class="text-center">
                <td class="pt-5">Result Permission</td>
                <td id="result_permission">

                <?php if($result_permission_count > 500) { ?>

                    <a class="btn btn-primary" onclick="update_permission('result_permission','N')">All Yes</a>
                
                <?php }else{ ?>
                
                    <a class="btn btn-danger" onclick="update_permission('result_permission','Y')">All No</a>
    
                <?php } ?>

                </td>
            </tr> 
            <tr class="text-center">
                <td class="pt-5">Late Admission Fees Private</td>
                <td id="late_fees">

                <?php if($late_exam_fees_privte[0]->p_late_fee_status == 'Y') { ?>

                    <a class="btn btn-primary" onclick="update_late_fees('p_late_fee_status','N')">All Yes</a>
                
                <?php }else{ ?>
                
                    <a class="btn btn-danger" onclick="update_late_fees('p_late_fee_status','Y')">All No</a>
    
                <?php } ?>

                </td>
            </tr> 
            <?php } ?>
        </table>
    </div>
</div>

<script>
    
function update_permission(param1,param2){

    var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 

    var url = '<?php echo site_url('admin/'.$accessFrom.'/update_center_permission'); ?>';
        $.ajax({

            type : 'POST',
            url: url,
            data: {[csrfName]: csrfHash,param_name:param1,permission:param2},
            dataType: 'JSON',
            success : function(response) {
           console.log(response);
                $('#'+param1).html("");
                console.log();
                $('#'+param1).html(response.sts_btn);
                toastr.success(response.msg);
           
                
            }
        });

}
function update_late_fees(param1,param2){

var csrfName = $('.csrfname').attr('name');
var csrfHash = $('.csrfname').val(); 

var url = '<?php echo site_url('admin/'.$accessFrom.'/update_late_fees'); ?>';
    $.ajax({

        type : 'POST',
        url: url,
        data: {[csrfName]: csrfHash,param_name:param1,permission:param2},
        dataType: 'JSON',
        success : function(response) {
      $("#late_fees").html(response.sts_btn);
            toastr.success(response.msg);
       
            
        }
    });

}
</script>