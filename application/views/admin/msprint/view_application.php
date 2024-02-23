
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap');
.form-block {
    background: hsl(53deg 100% 95% / 61%);
    border: 1px solid #f6d459;
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
    height: 150px;
    object-fit: unset;
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
	body * { visibility: hidden; margin: 0.5cm !importent; font-family: 'Roboto', sans-serif !important;}
	#printThisDivIdOnButtonClick * {  visibility: visible; }
	#printThisDivIdOnButtonClick * {  margin:0px  !important; font-size:14px; }
	#printThisDivIdOnButtonClick   {  
		width:1000px;
		position: absolute;
		padding-top: 1.0cm;
		margin-top:-240px !important; 
	}
	#printHeaderdiv * {
		font-size:20px !important; 
	}
	.label_heading {
		padding: 15px 0px 15px 0px !important;
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
	.mt-30{
		padding-top: 50px !important;
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
<script src="<?=BASE_URL()?>assets/light_box/js/jquery.magnify.js"></script>
<a  style="float:right;margin-top:-35px;font-size:16px;color: #781e19!important; " href="<?=BASE_URL()?><?=$this->session->account_type?>/view_application_request/<?=$data[0]->center_id ?>"><i class="fa fa-arrow-left "></i> Back</a>
<div id="printThisDivIdOnButtonClick" class="mt-10">
	<div id="printablediv">
		<div class="form-block row text-center d-block" id="printHeaderdiv">
			<div class="f-heading-1 text-primary">
				MAHARISHI MAHESH YOGI VEDIC VISHWAVIDYALAYA
			</div><div class="f-heading-2 text-primary">
				Brahmsthan Karoundi, Post Mahner-Umariyapaan, Distt – Katni (Madhya Pradesh)
			</div>
			
		</div>
       
		<div class="mt-5">
			<label class="label_form label_heading "><b>Student details</b></label>
			<div class="form-block row text-center">
				<div class="row col-md-10 m-auto">
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Form Number :</label>
					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo $data[0]->student_uid; ?>
					</div>
					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Enrollment :</label>
					</div>
					<div class=" form-text-color form-group col-md-3 text-left m-auto">
						<?php echo $data[0]->enrollment; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Session :</label>
					</div>
					<div class="form-group col-md-4 text-left m-auto form-text-color">
						<?php echo $data[0]->session; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Apply For : </label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $data[0]->apply_for; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Course Name:</label>
					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo $data[0]->course; ?>
					</div>
					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Class Name:</label>
					</div>
					<div class="form-text-color form-group col-md-3 text-left m-auto">
						<?php if(is_null($data[0]->class)){ echo  $data[0]->class_name;}else{
                            echo $this->Common_model->getClassNameByClassId($data[0]->class);
                        }; ?>
					</div>
					
					
				</div>
				<div class="row col-md-2">
					<img class="student_form_img" src="<?php echo base_url('/assets/student_image/').$data[0]->session.'/'.$data[0]->photo;?>"></img>
				</div>
			</div>
		</div>
		<label class="label_form mt-5 label_heading"><b>Personal Information</b></label>
		<div class="form-block row text-center">
            <div class="row col-md-12 m-auto">
                     <div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Student Name :</label>
					</div>
					<div class="form-group form-text-color col-md-4 text-left m-auto">
						<?php echo $data[0]->name_eng; ?>
					</div>
                    <div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Student Name Hindi:</label>
					</div>
					<div class="form-group form-text-color col-md-4 text-left m-auto">
						<?php echo $data[0]->name_hindi; ?>
					</div>
            </div>
			<div class="row col-md-12 m-auto">
                
				<div class="form-group col-md-2 text-left m-auto">
					<label class="label_form">Father/Husband Name :</label>
				</div>
				<div class="form-group col-md-4 form-text-color text-left m-auto">
					<?php echo $data[0]->fname_eng; ?>
				</div>
                <div class="form-group col-md-2 text-left m-auto">
					<label class="label_form">Father Name Hindi:</label>
				</div>
				<div class="form-group col-md-4 text-left m-auto form-text-color">
					<?php echo $data[0]->fname_hindi; ?>
				</div>
               
</div>
            <div class="row col-md-12 m-auto">
            <div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">DOB:</label>
					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo date("d-m-Y", strtotime($data[0]->dob));?>
					</div>
                <div class="form-group col-md-2 text-left m-auto"> 
					<label class="label_form">phone :</label>
				</div>
				<div class="form-group col-md-4 text-left m-auto form-text-color">
					<?php echo $data[0]->phone ?>
			</div>
			</div>
            <div class="row col-md-12 m-auto">
				<div class="form-group col-md-2 text-left m-auto">
					<label class="label_form">Address :</label>
				</div>
				<div class="form-group col-md-10 text-left m-auto form-text-color">
					<?php 
					echo $data[0]->address; ?>
				</div>
				
		</div>
		
	</div>

	<label class="label_form mt-5 label_heading"><b>Document Attached</b></label>
    <div class="form-block  text-center">
       
            <div class="row col-md-6 m-auto p-5" >
			<?php $cc=6; if($data[0]->apply_for=="DUPLICATE-MARKSHEET"){ $cc=3; } ?>      
                <div class="col-md-<?=$cc?>" >
						<a data-magnify="gallery" data-src="" data-caption="Adhar Card" data-group="a" href="<?php echo BASE_URL('assets/center_degree/'.$data[0]->session.'/'.$data[0]->adhar); ?>">
								Adhar Card
							</a>
					
                </div>
                <div class="col-md-<?=$cc?>">
					<?php if($data[0]->marksheet!=""){ ?>
					<a data-magnify="gallery" data-src="" data-caption="MarkSheet" data-group="a" href="<?php echo BASE_URL('assets/center_degree/'.$data[0]->session.'/'.$data[0]->marksheet); ?>">
								MarkSheet
							</a>
                    <?php } ?>
                </div>
				<?php if($data[0]->apply_for=="DUPLICATE-MARKSHEET"){ ?>
					<div class="col-md-3">
						<?php if($data[0]->policecomplaint!=""){ ?>
						<a data-magnify="gallery" data-src="" data-caption="Police Complaint" data-group="a" href="<?php echo BASE_URL('assets/center_degree/'.$data[0]->session.'/'.$data[0]->policecomplaint); ?>">
						Police Complaint
								</a>
						<?php } ?>
					</div>
					<div class="col-md-3">
						<?php if($data[0]->affidavit!=""){ ?>
						<a data-magnify="gallery" data-src="" data-caption="Affidavit" data-group="a" href="<?php echo BASE_URL('assets/center_degree/'.$data[0]->session.'/'.$data[0]->affidavit); ?>">
						Affidavit
								</a>
						<?php } ?>
					</div>
				<?php } ?>
                        
            </div>
        
    </div>

    <label class="label_form mt-5 label_heading"><b>Upload File</b></label>
    <div class="form-block  text-center">
        <form  method="post" action="<?= base_url('MsPrint/store_file')?>"  id="data" name="sub" enctype="multipart/form-data">
            <div class="row col-md-6 m-auto p-5" >
                       
                <div class="col-md-8" >
                <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
					<input type="hidden" name="center_id" value="<?=$data[0]->center_id?>"/>	
                    <input type="hidden" name="id" value="<?=$data[0]->id?>"/>
                    <input type="hidden" name="student_id" value="<?=$data[0]->student_uid?>"/>
                    <input type="hidden" name="session" value="<?=$data[0]->session?>"/>
                    <input type="hidden" name="apply_for" value="<?=$data[0]->apply_for?>"/>
                    <input type="file" class="form-control" id="photo" name="doc"/>
                    <span id="errPhoto" class="text-danger"></span>
                </div>
                <div class="col-md-4">
                    
                    <button class="btn btn-primary font-weight-bold" name="submit" type="submit">Upload</button>
                </div>
                        
            </div>
        </form>
    </div>
	
	
	
</div>

</div>
</div>
</div>
<script>
    
	$('#buttonId').on('click', function () {
		window.print();
	});
    $("#photo").on('change', function(){
        
	var file = $(this);
    var fileExtensions = file[0].files[0].name.split(".")[1];
	var validFileExtensions = ["jpg", "JPG", "JPEG", "jpeg", "png", "PNG", 'PDF','pdf'];
   
	if(!validFileExtensions.includes(fileExtensions)){
		$('#errPhoto').text('Please Select Valid file');
		$(this).val('');
		return false;
	}else{
		$('#errPhoto').text('');
	}
	var filesize = parseFloat(file[0].files[0].size / 1024).toFixed(2);
	if(filesize>1024){
		$('#errPhoto').text('Document size must be less than 1Mb');
		$(this).val('');
		return false;
	}else{
		$('#errPhoto').text('');
	}
});
</script>