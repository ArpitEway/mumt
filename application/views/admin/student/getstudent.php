<div class="dt-responsive">
<table id="kt_datatable_2" class="table table-striped" >
			<thead>
				<tr>
					<th>Form No.</th>
					<th>Course</th>
					<th>Class</th>
					<th>Student Name</th>
					<th>Father name</th>	
					<th>Document</th>
					<th>Action</th>
				</tr>
			</thead>
    		<tbody>
    		<?php 
    		$i = 1;
			foreach($students as $student){
			$docs = $this->Common_model->getAllRow("admission_document",'document_name,document_image',array("student_id" => $student['student_id']),'');
			?>
			<tr>
			<td><a target="_blank" href="<?php echo BASE_URL('show_form/'.$this->Common_model->encrypt_decrypt($student['student_id'],'encrypt')); ?>" ><?php echo $student["student_id"]; ?></a></td>
				<td><?php echo $student["course_name"]; ?></td>
				<td><?php echo $student["class_name"]; ?></td>
				<td><?php echo $student["name"]; ?></td>
				<td><?php echo $student["f_h_name"]; ?></td>
				<td><?php 
				$student_id = $this->Common_model->encrypt_decrypt($student["student_id"]); 
				foreach($docs as $doc){
					$ext = explode(".",$doc["document_image"]);

					if($ext[1] == "pdf")
					{
						if($doc["document_name"] == 'Aadhaar Card'){
							?>

							<a href="<?php echo site_url('Enrollment/update_aadhar/'.$student_id); ?>" target="_blank">
								<?php echo $doc["document_name"]; ?>
							</a>
							<br>
						<?php }else{ ?>

							<a target="_blank" href="<?php echo BASE_URL('assets/documents/'.$doc["document_image"]); ?>">
								<?php echo $doc["document_name"]; 
							} ?> 
						</a><br>

					<?php }else{
						if($doc["document_name"] == 'Aadhaar Card' || $doc["document_name"]=='Aadhaar Card Not Found'){
							?>

							<a href="<?php echo site_url('Enrollment/update_aadhar/'.$student_id); ?>" target="_blank">
								<?php echo $doc["document_name"]; ?>
							</a>
							<br>
						<?php }else{ ?>

							<a data-magnify="gallery" data-src="" data-caption="<?php echo $doc["document_name"] ?>" data-group="a" href="<?php echo BASE_URL('assets/documents/'.$doc["document_image"]); ?>">
								<?php echo $doc["document_name"]; ?>  
							</a>
							<br>
							<?php 
						}
					}
				}
				?> 
				</td>
				<td>
					<div style="display: inline-grid;">
					<?php if($student["approved"] != 'Y' || $student["approved"] == "" ){ ?>
					<a  style="margin:5px;" class="btn btn-success" data-id="<?php echo site_url('admin/Enrollment/make_approved/'.$student_id); ?>" data-st_id="<?=$student_id?>" onclick="make_approved(this)"> Make Approved </a>
					<a href="javascript:void(0);" style="margin:5px;" class="btn btn-danger" onclick="rightModal('<?php echo site_url('admin/modal/student_popup/admin/student/update/remark_update/'.$student_id); ?>', '<?php echo 'Select Remark' ?>')"> Make Non approve
					</a>
					<span  class="remark_span_<?=$student["student_id"];?>" style="color:red;">
					<?php if($student["remark"] != "N" )
					{

					$remarkk = explode(",",$student["remark"]);

					foreach($remarkk as $rem)
					{
					$remark_text = $this->Common_model->getStudentRemarkNameById($rem);
					
					echo $remark_text; ?><br>
				<?php  
			}
			echo " ".$student["remark_detail"];
				}
					echo "</span>";

				}else{ ?>

				<a style="margin:5px;" class="btn btn-success" > Approved </a>
				</a>   
				<?php } ?>		
				</div>
				</td>
				</tr>
			<?php
			$i++;
			} 
			?>
			</tbody>
</table>
</div>
<script>
function make_approved(param){

	var html = $(param).html();
	var html = $(param).prop("onclick", null).off("click");
	var url  = $(param).attr('data-id');
	var rem  = $(param).attr('data-st_id');
	
	if (confirm('Are you sure to make approved')) 
	{
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
				
				$(param).html("Approved");
				$(param).siblings('a').hide();
				$('.remark_span_'+rem).html("");
            }
        });
		
	} 
}



</script>