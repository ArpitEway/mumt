<form id="radio_btn_select">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="form-group row">
                
                <label class=" col-form-label col-3" >Enrollment No. : </label> 
                <input type=hidden value="<?= $center_id ?>" id="center_id" name="center_id"/>
                <input class="form-control col-9" type = "text" id="search_text" name ="search_text" />	
            </div>
            
            <div class="form-group row">
                <button type="button" class="btn btn-primary btn-sm m-auto" onclick="search_student_data()">Submit</button>
            </div>
        </div>
    </div>
</form>
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>

<div class="" id="student_data_tbl" style="display:none;">
    
</div>

<script type="text/javascript">
    var site_url = "<?php echo base_url(); ?>"

    function search_student_data()
    {    

        $('#student_data_tbl').hide();
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        var text_val = $('#search_text').val();
        var center_id = $('#center_id').val();
        
        if(text_val =='')
        { 
            alert('Enrollment Number is required !');
        }
       
        else
        {
          
            let data = {
                    'text_val':text_val,
                    'center_id':center_id,
                    [csrfName]:csrfHash
                }
            $.ajax({
                url:site_url+'center/<?=$this->session->account_type; ?>/getStudentData',
                type:'post',
                dataType : 'JSON',
                data: data,
                beforeSend: function()
              {
                $("#myLoader").show();
               },
                success:function(resp)
                {
                    console.log(resp.data);
                    if( $("#myLoader").show()){
						$('#student_data_tbl').hide();
						 $('#student_data_tbl').html(resp.data);

					}if( $('#myLoader').hide()){
                       
						$('#student_data_tbl').show();
						
					}
                            
                }//success
                
            })//ajax
        }
    }
</script>