<style>
	.select-group{
		font-size: 15px;
	}
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
<div class="mt-5">
			<label class="label_form label_heading "><b>Student details</b></label>
			<div class="form-block row text-center">
				<div class="row col-md-10 m-auto">
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Form Number :</label>
						<input type="hidden"  name="student_id" id="student_id" value="<?php echo $student['student_id']  ?>">

					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo $student['form_no']; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Enrollment :</label>
					</div>
					<div class=" form-text-color form-group col-md-3 text-left m-auto">
						<?php echo $student['enrollment_no']; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Course Name:</label>
					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo $student['course_name']; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Class Name:</label>
					</div>
					<div class="form-text-color form-group col-md-3 text-left m-auto">
						<?php echo $student['class_name']; ?>
					</div>

					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Student Name :</label>
					</div>
					<div class="form-group form-text-color col-md-4 text-left m-auto">
						<?php echo $student['name']; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">DOB:</label>
					</div>
					<div class="form-text-color form-group col-md-3 text-left m-auto">
						<?php echo date("d-m-Y", strtotime($student['dob']));?>
					</div>

					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Email id :</label>
					</div>
					<div class="form-group col-md-4 text-left m-auto form-text-color">
						<?php echo $student['p_email']; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Mobile No : </label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $student['p_mobile_no']; ?>
					</div>

				</div>
				<div class="row col-md-2">
					<img class="student_form_img" src="<?php echo base_url('/assets/student_image/').$student['photo'];?>"></img>
				</div>
			</div>
			
            </div>
<form>
    <div class="row border border-primary bg-primary text-white p-2 mt-5">
        <div class="col-2"><strong>#</strong></div>
        <div class="col-3"><strong>Paper code</strong></div>
        <div class="col-7"><strong>Subjects Name</strong></div>
    </div>
    <?php

            $i=0;
        ?>
        <input type="hidden" id="updatepaper" value='Y'>
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <?php
            foreach($compulsoryPapers as $paper){
                
            ?>
            <div class="row border border-default p-2">
                <div class="col-2"><?=++$i; ?></div>
                <div class="col-3"><?=$paper['paper_code']; ?></div>
                <div class="col-7"><?=$paper['paper_name']?>  </div>
                <input type="hidden"  name="compulsary_paper_id[]<?php echo  $paper['id'] ;?>" id="" value="<?php echo $paper['id'];  ?>">
				<input type="hidden"  name="student_id" id="student_id" value="<?php echo $student['student_id']  ?>">

            </div>
            <?php } 
            ?>
            <div class="row border border-primary bg-primary p-2 my-3 text-white ">
                <div class="col-md-12 text-center select-group">Please Select <?=$class_group[0]->select_group ?> Paper Group</div>
            </div>
            <?php
                $group_name = '';
				if($class_group[0]->class_group=='Y'){
                foreach($groupPaper as $paper){
                    if($group_name!=$paper->group_name){
                        $group_name=$paper->group_name;
                    ?>
                    <div class="row border border-primary bg-primary p-2 mt-3 text-white">
                        <div class="col-2">#</div>
                        <div class="col-3">
                            <label class="checkbox checkbox-dark">
                                <input name="group_id[]" class="checkbox" value="<?=$paper->group_id; ?>" type="checkbox" >

                            <span></span></label>
                            
                        </div>
                        <div class="col-7"><?=$paper->group_name; ?></div>
                    </div>
                <?php } ?>
                <div class="row border border-default p-2">
                    <div class="col-2"><?=++$i; ?></div>
                    <div class="col-3"><?=$paper->paper_code; ?></div>
                    <div class="col-7"><?=$paper->paper_name; ?></div>
                </div>
                <?php }    }
    ?>
    </form>
    <div class="d-flex justify-content-center mt-10">
        <button type="reset" class="btn btn-primary btn-block col-3 submit" id="group_submit">Submit</button>
    </div>
    </div>

<script>
	var select_group = <?php echo $class_group[0]->select_group; ?>;
   // alert(select_group);
$('#group_submit').on('click', function (e) {

	var selectedGroups = $("input[name='group_id[]']:checked").length;

	if(selectedGroups==0){
       
		alert('Please Select Paper Group');
		return false;
	}else if(selectedGroups != select_group){
       // alert(selectedGroups);
		alert('Please Select '+select_group+' Paper Group');
		return false;
	}
	

	var data = $("form").serialize(); 

    console.log(data);
    
	 $.ajax({
	        	url: "<?=base_url('center/center/submit_group');?>",  
              
                type:'post',
                dataType : 'JSON',
                data:data,
				success:function(data){
					console.log(data);

					if(data.status=='true'){
				
					window.location.href = BASE_URL+"center/center/get_student_data";
					return false;
					}else{
					
					return false;
				}
            }
	 });   
});
</script>