<form id="radio_btn_select">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="form-group row">
                <label class=" col-form-label col-3" >Select Year : </label> 
                <Select class="form-control col-9"  id="search_year" name ="search_year" onchange="search_student_data()" >
                    <option value="All">All</option>
                    <?php 
               
                      foreach($years[0] as $key=>$y){
                    ?>
                   
                    <option value="<?php echo $y;?>"><?php echo $y;?></option>
                    <?php
                    }
                    ?>
                </Select>   
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
<div class="row" >
    <div class="col-md-12 col-lg-12" id="student_data_tbl">
<!-- table by ajax append here -->
    </div>
</div>
<script type="text/javascript">
    var site_url = "<?php echo base_url(); ?>"

    $(function() {
        search_student_data();
    });               
    function search_student_data()
    {    

        $('#student_data_tbl').hide();
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        var text_val = $('#search_year').val();
        
            let data = {
                    'text_val':text_val,
                  
                    [csrfName]:csrfHash
                }
            $.ajax({
                url:site_url+'admin/<?=$this->session->account_type; ?>/getTCStudentData',
                type:'post',
                dataType : 'JSON',
                data: data,
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
                }//success
                
            })//ajax
        
    }
</script>