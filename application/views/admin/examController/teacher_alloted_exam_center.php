
<style>
	.form-block {
		background: hsl(228deg 100% 99%);
		border: 1px solid hsl(216deg 84% 83%);
		border-radius: 10px;
		padding: 10px;
	}
	.label_form{
		font-size: 14px !important;
		font-weight: 500 !important;
	}
	.student_form_img {
		width: 115px;
		margin: auto;
		height: 140px;
		object-fit: cover;
	}
	.m-auto{
		margin:auto;
	}
	hr.new2 {
		border-top: 1px dashed gray;
	}
	.text-primary {
		color: #052c68!important;
	}
	.f-heading-1{
		padding: 10px 0px 10px 0px;
		font-size: 28px;
		font-weight: 500;
	}
	.f-heading-2 {
		padding: 0px 0px 12px 0px;
		font-size: 20px;
		font-weight: 400;
	}
	.f-heading-3 {
		padding: 0px 0px 8px 0px;
		font-size: 23px;
		font-weight: 500;
	}
	.form-text-color {
		font-weight: 600;
		color: #635050c2;
	}
	.form-group.col-md-5.text-left.m-auto,
	.form-group.col-md-4.text-left.m-auto ,
	.form-group.col-md-2.text-left.m-auto,
	.form-group.col-md-9.text-left.m-auto,
	.form-group.col-md-3.text-left.m-auto {
		padding: 5px;
		text-transform: uppercase;
		font-size: 14px;
	}


	@media print{
		body * { visibility: hidden; margin: 0.5cm !importent;}
		#printThisDivIdOnButtonClick * {  visibility: visible; }
		#printThisDivIdOnButtonClick * {  margin:0px  !important; font-size:14px; }
		#printThisDivIdOnButtonClick   {  width:100%; position: absolute; left:-20px !important; padding-top: 1.0cm;margin-top:-260px !important; }
		#printHeaderdiv * {
			font-size:20px !important; 
		}
		.label_heading {
			padding: 5px 0px 5px 0px !important;
		}
		.signature{
			padding-top:30px !important;
		}
		.cls {
			padding-left:25px !important;
		}
		.form-text-color{
			font-weight: 500;
			color:#635050c2;
		}
		h1 {page-break-before: always !important;}
		th{
			width:auto !important;
		}
		.label_form{
			font-size: 12px !important;
			font-weight: 400 !important;
		}
		.bg-primary{ background-color: #052c68 !important; color:#000 !important; }
		.print-logo{
			display:block;
		}
		.h2{
			font-size:25px !important;
			padding-top: 0.5cm;
			padding-bottom: 0.5cm;
		}
		.confirmation{
			padding-top: 20px;
		}
		#buttonId{
			visibility: hidden;
		}
		td,th{
			padding: 3px !important;
		}
		table{
			margin-bottom: 0 !important;
		}

	}


</style>
<div id="printablediv">
		<div class="mt-5">
			<label class="label_form label_heading "><b>Teacher details</b></label>
			<div class="form-block row text-center">
				<div class="row col-md-10 m-auto">
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Course :</label>

					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo $course_name; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Class :</label>
					</div>
					<div class=" form-text-color form-group col-md-3 text-left m-auto">
						<?php echo $class; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Paper:</label>
					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo $paper_name; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Teacher:</label>
					</div>
					<div class="form-text-color form-group col-md-3 text-left m-auto">
						<?php echo $teacher_name; ?>
					</div>

				

				

				</div>
				
			</div>
			
		</div>
	</div>
<form method="post"   class="mt-3 answersheet" >

<div class=" mt-5" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
			<table id="" class="table table-striped dt-responsive nowrap" width="100%" >
				<thead>
					<tr>
						<th>#</th>
                        <th>Center Code</th>		
                        <th>Paper Count</th>		
                        <th>Checked</th>		
                        <th><input type="checkbox" id="remove_centers"></th>
					</tr>
				</thead>
			<tbody>
    		<?php
    		$i = 1;
			$total_paper = 0 ;
			$total_checked = 0 ;
            foreach($paper_count as $paper_counts){
		    $count_for_checked =  $this->Common_model->getCountByWhere('upload_exam_ans_sheet',array( "file_exist"=>'Y',"paper_code"=>$paper_code,"class_id"=>$class_id,'center_id'=>$paper_counts->center_id,'teacher_id!='=>''));
			$total_paper = $total_paper+ $paper_counts->cnt  ;
			$total_checked = $total_checked + $count_for_checked ;
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
<tfoot>
			<tr>
		
			<td></td>
			<td><?php echo "Total"; ?></td>
			<td><?php echo $total_paper ?></td>
			<td><?php echo $total_checked  ?></td>
			
			<td></td>
			</tr>
			<tfoot>
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
