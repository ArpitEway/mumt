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
<div class="row" >
    <div class="col-md-12 col-lg-12" id="student_data_tbl">
<!--     table by ajax append here -->
</div>	
</div>
<script type="text/javascript">
    function search_student_data(){
       
        $('#student_data_tbl').html('');
        var roll_no = $('#roll_no').val();
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        if(roll_no==''){
            alert('Roll Number is required !');
        }else{
            $.ajax({
                url: BASE_URL+'admin/<?=$this->session->account_type;?>/getEditStudentMarksData',
                type:'post',
                dataType : 'JSON',
                data:{'roll_no':roll_no,[csrfName]: csrfHash},
                success:function(resp)
                {
                    $('#student_data_tbl').html(resp.data);
                    KTDatatablesBasicBasic.init();            
                } //success
            }); //ajax
        }
    }
</script>