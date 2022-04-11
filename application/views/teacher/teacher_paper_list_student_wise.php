<form method="POST" class="d-block " >
   <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <div class="form-group col-md-6 m-auto">
            <label for="paper">Exam paper List</label>
            <select name="paper_code" id="paper" class="form-control paper" >
                <option value="">Select paper</option>


            <?php foreach ($assignAnsData as $papers): ?>
                <option value="<?=$papers->paper_code?>"> <?php

                    $papername=$this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$papers->paper_code));
                    echo ' ( '.$papers->paper_code.' ) '. $papername[0]->paper_name ;
                   // $this->Common_model->last_query();
                    ?> </option>
            <?php endforeach ?> 

                              
            </select>
        </div>
		
		
   <br>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" id="submit" type="submit">Submit</button>
	</div>
		

</form>
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>
<div class="row" >
    <div class="col-md-12 col-lg-12" id="student_data_tbl">
<!-- table by ajax append here -->
    </div>
</div>

<script type="text/javascript">
    var site_url = "<?php echo base_url(); ?>"

    $('#submit').on('click',function (e) {
        e.preventDefault();

       $('#student_data_tbl').hide();
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        var paper_code = $('#paper').val();
        if(paper_code ==''){ 
            alert('Select paper  is required !');
        }else{
            let data = {
                'paper_code':paper_code,
                [csrfName]:csrfHash
            }
            $.ajax({
                url:site_url+'Teacher/get_paper_details',
                type:'post',
                dataType : 'JSON',
                data: data,
              
       beforeSend: function(){
                $("#myLoader").show();
               },

               success:function(resp){
                 if($('#myLoader').hide()){
                    $('#student_data_tbl').html(resp.data);
                     $('#student_data_tbl').show();
                }
            }//success
        })//ajax
    }
});
</script>