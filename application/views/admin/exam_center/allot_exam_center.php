<form  class="d-block ajaxForm" method="post"  action="<?=base_url('admin/ExamController/allot_exam_center_sub');?>" class="mt-3 answersheet">
    <div class="  text-center p-3">
       <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
       <div class="form-group col-md-6 mx-auto">
        <label for="course">Exam Center</label>
        <select  name="exam_center" readonly="readonly" id="exam_center" class="form-control course" required>
            <option value="" selected >Select Exam Center</option>
            <?php 

            foreach($exam_center as $ecenter)
            {
                ?>
                <option value="<?php echo $ecenter['id']; ?>"   ><?php echo $ecenter['examcentercode'].' ('.$ecenter['schoolcollegename'].')'; ?></option>
                <?php
            } 
            ?>
        </select>
    </div>
   
   
   

</div>


<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>
<div id="dt">
</div>


<div class="text-center p-3">
        <input type="hidden" class="" name="action1" value="allot_exam_center">
        <button type="submit" class="btn btn-primary" id="submit" name="submit" >submit</button>
</div>        	
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="dt-responsive">
		<table id="kt_datatable"  class="table table-striped" >
			<thead>
				<tr>
					<th>Sno.</th>
                    <th>Center Code</th>
                    <th>Center Name</th>
					<th>Contact persons</th>
					<th>Address</th>
					<th>City</th>
					<th>Mobile</th>
					<th><!--<input type="checkbox" id="allAssign_answersheet">--></th>
				</tr>
			</thead>
			<tbody>      
				<?php
				$i=1;
				$total_paper = 0 ;
				$available=0 ;
				$checked = 0 ;
				foreach($centers as $center)
				{  
					//print_r($center); die;
					?>
					<tr>
						<td><?php echo $i++; ?></td>
                        <td><?php echo $center['center_code'];?></td>
                        <td><?php echo $center['center_name'];?></td>
						<td><?php echo $center['contactpersonname'];?></td>
                        <td><?php echo $center['address'];?></td>
                        <td><?php echo $center['city'];?></td>
                        <td><?php echo $center['mobile_no_1'];?></td>
						<td><input type="checkbox" class="checkbox" name="center_id[]" value="<?=$center['id'];?>"></td>
					</tr>
					<?php 
				}
				?>
			</tbody>
			<tfoot>
		
			<tfoot>
		</table>
	</div>
	
</form>





<!-- select2 cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
