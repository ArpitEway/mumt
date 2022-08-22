
<?php $paper = $this->Common_model->getRecordById('paper_master','id',$param1);
 ?>
<form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/paper/update/'.$param1); ?>">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
<div class="row">
		<div class="form-group col-md-6">
			<label for="name">Paper name</label>
			<input type="text" class="form-control" id="paper_name" name="paper_name" value="<?=$paper->paper_name?>" placeholder="Enter paper name">
			<input type="hidden" name="paper_id" value="<?=$param1?>">
		</div>
		
    <!--    <div class="form-group col-md-6"> 
				<label>Exam date:</label>
				<input type="date" id="exam_date" name="exam_date" class="form-control" placeholder="Enter exam date" data-inputmask="'alias': 'dd-mm-yyyy'" value="<?=$paper->exam_date?>">
				<span class="form-text text-danger"></span>
		</div>  -->

		<!-- <div class="form-group col-md-6">
			<label>Exam Day:</label>
			<select name="exam_day" id="exam_day" class="form-control"
			value="">

			<option value="Select Day">Select Day</option>

			<option <?=(isset($paper->exam_day) && $paper->exam_day=='Monday') ? 'selected' : '';?>>Monday</option>
			<option <?=(isset($paper->exam_day) && $paper->exam_day=='Tuesday') ? 'selected' : '';?>>Tuesday</option>			
			<option <?=(isset($paper->exam_day) && $paper->exam_day=='Wednesday') ? 'selected' : '';?>>Wednesday</option>
			<option <?=(isset($paper->exam_day) && $paper->exam_day=='Thursday') ? 'selected' : '';?>>Thursday</option>
            <option <?=(isset($paper->exam_day) && $paper->exam_day=='Friday') ? 'selected' : '';?>>Friday</option>	
			<option <?=(isset($paper->exam_day) && $paper->exam_day=='Saturday') ? 'selected' : '';?>>Saturday</option>				
			
		</select>
		<span class="text-danger"></span>
	</div> -->
       <!-- <div class="form-group col-md-6">
			<label for="code">Exam Time</label>
			<input type="text" class="form-control" id="exam_time" name="exam_time" value="<?=$paper->exam_shift?>" placeholder="Enter Exam time ">        
		</div> -->

      <div class="form-group col-md-6">
			<label for="code">Max Theory Marks</label>
			<input type="text" class="form-control" id="max_theory" name="max_theory" value="<?=$paper->max_theory_marks?>" placeholder="Enter Max Theory Marks ">        
		</div>

      <div class="form-group col-md-6">
			<label for="code">Min Theory Marks</label>
			<input type="text" class="form-control" id="min_theory" name="min_theory" value="<?=$paper->min_theory_marks?>" placeholder="Enter Min Theory Marks ">        
		</div>

     <div class="form-group col-md-6">
			<label for="code">Max Internal Marks</label>
			<input type="text" class="form-control" id="max_int" name="max_int" value="<?=$paper->max_internal_marks?>" placeholder="Enter Max Internal Marks">        
		</div>

		<div class="form-group col-md-6">
			<label for="code">Min Internal Marks</label>
			<input type="text" class="form-control" id="min_int" name="min_int" value="<?=$paper->min_internal_marks?>" placeholder="Enter Min Internal Marks">        
		</div>    
           <div class="form-group col-md-6">
            <label for="name">Uplode Paper</label>
          <input type="file" class="form-control"  id="file" name="file">
        </div>  
	</div>
	<div class="form-group text-center">
		<button class="btn btn-md btn-primary" type="submit">Submit</button>
	</div>
</form>
