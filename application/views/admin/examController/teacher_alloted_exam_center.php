
<style>
.btn.btn-primary i {
    color: #FFFFFF !important;
}
</style>
<form method="post"   class="mt-3 answersheet" >

<div class=" mt-5" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
			<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
				<thead>
					<tr>
						<th>#</th>
                        <th>Exam Center Code</th>		
                        <th>Paper Count</th>		
                        <th>Checked</th>		
                        <th><input type="checkbox" id="remove_centers"></th>
					</tr>
				</thead>
			<tbody>
    		<?php
    		$i = 1;
            foreach($paper_count as $paper_counts){
		    $count_for_checked =  $this->Common_model->getCountByWhere('upload_exam_ans_sheet',array( "file_exist"=>'Y',"paper_code"=>$paper_code,"class_id"=>$class_id,'center_id'=>$paper_counts->center_id,'teacher_id!='=>''));
                
    		?>
					<tr>
					<td><?php echo $i ; ?> </td>			
					<td><?php echo $paper_counts->center_code ; ?> </td>			
					<td><?php echo $paper_counts->cnt ; ?> </td>			
					<td><?php echo   $count_for_checked ; ?> </td>			
                    <td><input type="checkbox" class="checkbox" name="center_id[]" value="<?=$paper_counts->center_id;?>"></td>
					<input type="hidden" name="action" value="remove_centers">
					<input type="hidden" name="assign_answersheet_id" value="<?php  echo $assign_answer_sheet_id ; ?>">

                   </tr>
<?php $i++; } ?>

</tbody>
</table>
<div class="text-center p-3">
		<button type="button" class="btn btn-primary" id="submit" name="submit" >submit</button>
</div>
</div>
</form>
<script>
	$(document).ready(function() {
		// Check All
		$('#remove_centers').on('change', function() {
			if($('#remove_centers').is(":checked")){
				$(":checkbox").attr("checked", true);
				}else{
				$(":checkbox").attr("checked", false);
			}
		});
	});


$(document).on('click', '#submit', function(e) {
    if($("input:checkbox").filter(":checked").length<1){
        toastr.error("PLease Select atleast one");
        return false ;
    }
var frm = $('.answersheet').serialize();
$.ajax({
url: '<?php echo site_url('admin/ExamController/remove_centers_from_assign_answersheet'); ?>',
type: 'POST',
dataType : 'json',
data: frm,
success: function (data) {
if(data){
            toastr.success("Center removed successfully");	
            window.location.href = BASE_URL+"admin/ExamController/view_assign_answersheet";
        }else{
            toastr.error("Something wrong");
        }
    },
});	

});		
	
</script>
