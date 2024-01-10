
<form id="radio_btn_select">
  <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="row  ">
		
<div class="col-lg-6 m-auto">
<div class="form-group row">
				  <label class=" col-form-label col-3" >Enter Form No : </label> 
				  <input class="form-control col-9" type = "text" id="search_text" name ="search_text" />	
				</div>
         
            <br>

          	<div class="form-group text-center">
          		<button type="button" class="btn btn-primary btn-sm" onclick="search_student_data()">Search</button>
          	</div>
         
		</div>
        </div>
    </form>
   <!-- 
       table by ajax append here -->
         <!-- loader -->
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
<svg>
<circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
<circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
<circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
</svg>
</div>

<div class="row" >
<div class="col-md-12 col-lg-12" id="student_data_tbl">
</div>	
</div>  

<script type="text/javascript">

	var site_url = "<?php echo base_url(); ?>"

function search_student_data()
	{
    $('#student_data_tbl').hide()
	  var text_val = $('#search_text').val();
    var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val();
	   if(text_val =='')
	   {
	   	alert('Student Id is required !');
	   }
	   else
	   {
	   	  $.ajax({
                url:site_url+'admin/Enrollment/get_student_session_update',

                type:'post',

                dataType : 'JSON',

                data:{'student_id':text_val,[csrfName]:csrfHash},

              beforeSend: function()
              {
                $("#myLoader").show();
               },
 
                success:function(resp)
                {if( $("#myLoader").show()){
               $('#student_data_tbl').hide();
           
                }if( $('#myLoader').hide()){
                $('#student_data_tbl').html(resp.data);
               $('#student_data_tbl').show();
            
               }
              KTDatatablesBasicBasic.init();            
                }
            })//ajax
	   }
	 



	}

</script>