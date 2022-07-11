<form  class="d-block ajaxForm">
    <div class="row  text-center">
       <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
       <div class="form-group col-md-6">
        <label for="course">Exam Center</label>
        <select  name="exam_center" readonly="readonly" id="exam_center" class="form-control course" required>
            <option value="">Select Exam Center</option>
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
<!--<div class="form-group text-center">
	<input type="hidden" class="" name="action1" value="submit">

	<button class="btn btn-md btn-primary" type="button" id="submit_form">Submit</button>
</div>-->
</form>
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>
<div id="dt">
</div>

<form method="post"  action="<?=base_url('admin/ExamController/show_counter_folio');?>" class="mt-3 answersheet" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="dt-responsive">
		<table id=""  class="table table-striped" >
			<thead>
				<tr>
					<th>Sno.</th>
                    <th>Center Code</th>
                    <th>Center Name</th>
					<th>Contact persons</th>
					<th>Address</th>
					<th>City</th>
					<th>Mobile</th>
					<th><input type="checkbox" id="allAssign_answersheet"></th>
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
						<td><input type="checkbox" class="checkbox" name="teacher_id[]" value="<?=$teacher->teacher_id;?>"></td>
					</tr>
					<?php 
				}
				?>
			</tbody>
			<tfoot>
		<!--	<tr>
		
			<td></td>
			<td><?php //echo "Total"; ?></td>
			<td><?php //echo $total_paper ?></td>
			<td><?php //echo $available  ?></td>
			<td><?php //echo $checked ?></td>
			<td></td>
			</tr>-->
			<tfoot>
		</table>
	</div>
	<div class="text-center p-3">
		<input type="hidden" name="action" value="assign_answersheet">
	<!--	<input type="hidden" name="teacher_id" value="<?php echo $teacher_id ; ?>">-->
		<input type="hidden" name="class_id" value="<?php echo $class_id ; ?>">
		<input type="hidden" name="course_group_id" value="<?php  echo $course_group_id ;  ?>">
		<input type="hidden" name="paper_code" value="<?php echo  $paper_code ; ?>">
		<button type="submit" class="btn btn-primary" id="submit" name="submit" >submit</button>
	</div>
</form>

<script>
		$('#allAssign_answersheet').on('change', function() {
			if($('#allAssign_answersheet').is(":checked")){
				setCheckboxes3(1);
			}else{
				setCheckboxes3(2);
			}
		});

		
function setCheckboxes3(act)
  {
  elts = document.getElementsByName("teacher_id[]");
  var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;
  if (elts_cnt)
    {
    for (var i = 0; i < elts_cnt; i++)
      {
      elts[i].checked = (act == 1 || act == 0) ? act : (elts[i].checked ? 0 : 1);
      }
    }
  }
</script>



<!-- select2 cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    /* $(document).ready(function() {

        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        $('#class_id').change(function(){
          var class_id =   $('#class_id').val();

          var data = {
            class_id: class_id,
            [csrfName]: csrfHash,
        }; 

        $.ajax({
            url:BASE_URL+'admin/ExamController/getPaperByClassId',
            type:'post',
            dataType : 'JSON',
            data: data,
           
            success:function(data)
            {    
                console.log(data);

                var html = '';
                html += ('<option value="0">Select Paper</option>');
                $.each(data.data, function (i, value) {
                    html += (
                        '<option value="' + value.paper_code + '">'+'('+ value.paper_code + ') '+value.paper_name + '</option>');

                });
                $("#paper_id").html(html);

            }
           
        })
    });
    });

    $('#course_group_id').select2({
        placeholder : 'Search Course',
        allowClear: true
    })
   



   $(document).on('click', '#submit_form', function() {
        $('#dt').hide();
        var course = document.getElementById('course_group_id').value;
        var class_id = document.getElementById('class_id').value;
        var teacher = document.getElementById('teacher').value;
        var paper_id = document.getElementById('paper_id').value;

        if (course== "" || class_id =="0" || teacher =="" || paper_id=="0") {
           toastr.error("PLease select all fields");
           return false;
       }

       var frm = $('.ajaxForm').serialize();
       var url = BASE_URL + "admin/ExamController/assign_answersheet_sub";

       $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: frm,
        beforeSend: function()
			{
				$("#myLoader").show();
			},
        success: function (data) {
            if( $("#myLoader").show()){
					$('#dt').hide();
					// $table = $('#dt').html(status.data);
					}if( $('#myLoader').hide()){
                        $table = $('#dt').html(data.data);
						$('#dt').show();
						
					}
            KTDatatablesBasicBasic.init();
        },
        complete: function()
				{
					$('#myLoader').hide();
				},
    });
});

        function Reset() {
        var paper_id = document.getElementById("paper_id");
        paper_id.selectedIndex = 0;
        $('#teacher').val('').trigger('change');
        }

        $(document).on('click', '#submit', function(e) {	

        if($("input:checkbox").filter(":checked").length<1){
        toastr.error("PLease Select atleast one");
        return false ;
        }
var frm = $('.answersheet').serialize();
    $.ajax({
    	url: '<?php echo site_url('admin/ExamController/assign_answersheet_sub'); ?>',
    	type: 'POST',
    	dataType : 'json',
    	data: frm,
    	success: function (data) {
            if(data){
                $('#dt').empty();
                Reset();
                toastr.success("Assign Answershet Successfully");	
            }else{
                toastr.error("Something wrong");
            }
        },
    });
});		*/
</script>