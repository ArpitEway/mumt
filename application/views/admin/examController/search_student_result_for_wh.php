<form id="radio_btn_select">
    <div class="row  ">
        <div class="col-lg-6 m-auto">
            <div class="form-group row">
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
                <label class=" col-form-label col-3" >Enter Roll No : </label> 
                <input class="form-control col-9" type = "text" id="roll_no" name ="roll_no" />	
            </div>     
            
        </div>
    </div>
    <div class="row d-flex justify-content-center">
            <div class="form-group">
                <button type="button" class="btn btn-primary" onclick="search_student_data()">Submit</button>
            </div>
            
    </div>
</form>
    <div class="row d-flex justify-content-center">
           
            <div id="student_dt" class="p-3"></div>
    </div>
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>
<div class="row" >
    <div class="col-md-12 col-lg-12 " id="student_data_tbl">
<!--     table by ajax append here -->
</div>
</div>
<script type="text/javascript">
    function search_student_data(){
       
        $('#student_data_tbl').html('');
        $('#student_dt').html('');
        var roll_no = $('#roll_no').val();
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        if(roll_no==''){
            alert('Roll Number is required !');
        }else{
            $.ajax({
                url: BASE_URL+'admin/<?=$this->session->account_type;?>/getEditStudentMarksDataWH',
                type:'post',
                dataType : 'JSON',
                data:{'roll_no':roll_no,[csrfName]: csrfHash},
                beforeSend: function()
              {
                $("#myLoader").show();
               },
                success:function(resp)
                {
                        if(resp.data == "Student Not Found"){
                            $('#myLoader').hide();
                            $('#student_dt').html(resp.data);
                        }else{
                            $('#myLoader').hide();
                             $('#student_data_tbl').html(resp.data);
                             KTDatatablesBasicBasic.init();
                        }
                                
                } //success
            }); //ajax
        }
    }
</script>