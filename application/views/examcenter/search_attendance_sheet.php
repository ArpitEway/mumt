<form id="radio_btn_select">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="form-group row">
                <label class=" col-form-label col-3" >Enter Value : </label> 
                <input class="form-control col-9" type = "text" id="search_text" name ="search_text" />	
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label" >Search By : </label>
                <div class="col-9 col-form-label">
                    <div class="radio-inline">
                        <!-- <label class="radio radio-success">
                            <input type = "radio"
                            name = "radio_stduent_search"
                            id = "radio_name"
                            value = "student_name" 
                            checked = "checked" />
                            <span></span>
                            Name : 
                        </label> -->
                        <label class="radio radio-success">
                            <input type = "radio"
                            name = "radio_stduent_search"
                            id = "radio_roll_no"
                            value = "roll_no" 
                            checked = "checked"
                            />
                            <span></span>
                            Roll No : 
                        </label>
                        <label class="radio radio-success">
                            <input type = "radio"
                            name = "radio_stduent_search"
                            id = "radio_enroll_no"
                            value = "enrollment_no" />
                            <span></span>
                            Enrollment No.
                        </label>
                    </div>
                </div>
            </div> <!-- from-group row -->
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

<div class="dt-responsive" >

</div> 
<script type="text/javascript">
    var site_url = "<?php echo base_url(); ?>"

    function search_student_data()
    {    

        $('#student_data_tbl').hide();
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        var text_val = $('#search_text').val();
        var radio_val = "";
        radio_val = $('input[name="radio_stduent_search"]:checked').val();
        
        if(text_val =='' && radio_val == 'enrollment_no')
        { 
            alert('Enrollment Number is required !');
        }
         else if(text_val==''&& radio_val == 'roll_no')
        {
            alert('Roll Number is required !');
        }
        // else if(text_val==''&& radio_val == 'student_name')
        // {
        //     alert('Enter Student Name !');
        // }else if(text_val==''&& radio_val == 'adhar_no')
        // {
        //     alert('Enter Aadhaar No Name !');
        // }
        else if(text_val!=''&& radio_val != '')
        {
          
            let data = {
                    'text_val':text_val,
                    'radio_val':radio_val,
                    [csrfName]:csrfHash
                }
            $.ajax({
                url:site_url+'Examcenter/get_search_student_attendance_sheet',
                type:'post',
                
                data: data,
                beforeSend: function()
              {
                $('.dt-responsive').html("");
                $("#myLoader").show();
               },
                success:function(msg)
                {
                    $("#myLoader").hide();
                    $('.dt-responsive').html(msg);
                   
                   
                       
                }//success
                
            })//ajax
        }
        else{
            alert('Please enter student data & select relavent match !');
        }
    }

//     function PrintDiv() {
//     var title="Attendance Sheet";
//     var contents = document.getElementById('ss').innerHTML;
//     var frame1 = document.createElement('iframe');
//     frame1.name = "frame1";
//     frame1.style.position = "absolute";
//     frame1.style.top = "-1000000px";
//     document.body.appendChild(frame1);
//     var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
//     frameDoc.document.open();
//      frameDoc.document.write(`<style>@page{size:portrait;}@tr{border: solid 1px #000;background-color:#E8F6FF;}</style><html><head><title>${title}</title>`);
//     frameDoc.document.write('</head><body>');
//     frameDoc.document.write(contents);
//     frameDoc.document.write('</body></html>');
//     frameDoc.document.close();
//     setTimeout(function () {
//         window.frames["frame1"].focus();
//         window.frames["frame1"].print();
//         document.body.removeChild(frame1);
//     }, 500);
//     return false;
// }
</script>