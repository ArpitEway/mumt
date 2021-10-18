			<form id="radio_btn_select">
<div class="row  m-auto">
		

                    <div class="col-lg-6">
<div class="form-group row">
				  <label class=" col-form-label col-3" >Enter Value : </label> 
				  <input class="form-control col-9" type = "text" id="search_text" name ="search_text" />	
				</div>
                </div>
       <div class="col-lg-12">
           <div class="form-group row">
        <label class="col-1 col-form-label" >Search By : </label>
        <div class="col-11 col-form-label">

            <div class="radio-inline">
             
             <label class="radio radio-success">
                    <input type = "radio"
                 name = "radio_stduent_search"
                 id = "radio_name"
                 value = "student_name" 
                 checked = "checked" />
                    <span></span>
                   Name : 
                </label>

               <label class="radio radio-success">
                    <input type = "radio"
                 name = "radio_stduent_search"
                 id = "radio_roll_no"
                 value = "roll_num" 
                  />
                    <span></span>
                   Roll No : 
                </label>

                <label class="radio radio-success">
                     <input type = "radio"
                 name = "radio_stduent_search"
                 id = "radio_enroll_no"
                 value = "enroll_num" />
                    <span></span>
                    Enrollment No.
                </label>

                <label class="radio radio-success">
                     <input type = "radio"
                 name = "radio_stduent_search"
                 id = "radio_form_id"
                 value = "form_num" />
                    <span></span>
                    Form Id
                </label>
              </div>
            </div>
          </div> <!-- from-group row -->

            
         
            <br>

          	<div class="form-group">
          		<button type="button" class="btn btn-primary btn-sm" onclick="search_student_data()">Submit</button>
          	</div>
         
		</div>
        </div>
    </form>


       <div class="row" >
        <div class="col-md-12 col-lg-12" id="student_data_tbl">
       <!-- 
       table by ajax append here -->

        </div>	
        </div>  

<script type="text/javascript">

	var site_url = "<?php echo BASE_URL(); ?>"

function search_student_data()
	{
	  var text_val = $('#search_text').val();
	//var radio_val =  $('input[name="radio_stduent_search"][type="radio"]').val();
	  var radio_val = $('input[name="radio_stduent_search"]:checked').val();


	 
	   if(text_val =='' && radio_val == 'enroll_num')
	   {
	   	alert('Enrollment Number is required !');
	   }
	   else if(text_val==''&& radio_val == 'form_num')
	   {
	   	alert('Form/Student Id is required !');
	   }
	   else if(text_val==''&& radio_val == 'roll_num')
	   {
	   	alert('Roll Number is required !');
	   }
       else if(text_val==''&& radio_val == 'student_name')
       {
        alert('Enter Student Name !');
       }

	   else
	   {
	   	  $.ajax({
     url:site_url+'admin/master/getStudentData',

                type:'post',

                dataType : 'JSON',

                data:{'text_val':text_val,'radio_val':radio_val},

                success:function(resp)
                {
                 $('#student_data_tbl').html(resp.data);
                     KTDatatablesBasicBasic.init();
                 

                 
                }//success
            })//ajax
	   }
	 



	}

</script>