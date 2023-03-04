<div class="row text-center p-3">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    
    <div class="form-group col-md-3">
        <label for="course">Date Of Paper</label>
        <select  name="exam_date" readonly="readonly" id="exam_date" class="form-control course" required>
        <option >Select Date</option>
            <!-- <option value="All" selected="selected" >All</option> -->
            <?php 

            foreach($examDate as $edate)
            {
                ?>
                <option value="<?php echo  date("d-m-Y", strtotime($edate->exam_date)); ?>"   ><?php echo  date("d-m-Y", strtotime($edate->exam_date)); ?></option>
                <?php
            } 
            ?>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="Shift">Shift</label>
        <select  name="shift" readonly="readonly" id="shift" class="form-control Shift" required>

            <option value="Morning" >Morning</option>
            <option value="Afternoon">Afternoon</option>


        </select>
    </div>
    <div class="form-group col-md-3">
    <label for="course">Category</label>
        <select name="category" id="category" class="form-control course" required>
            <option value="Uniqe">Unique</option>
            <option value="All">All</option>
        </select>
    </div>
    <div class="form-group col-md-3">
        <button type="button" style="margin-top: 25px;" class="btn btn-primary" id="search" name="search" >Search</button>
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


<script>
   
    $("#search").on('click', function(){
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val(); 
        var exam_date = $("#exam_date").val();
        var category = $("#category").val();
        var shift = $("#shift").val();
        console.log($("#exam_date option:selected").text());
        $("#headerTitle").html($("#exam_date option:selected").text());
        $("#exam_date").css("border",""); 
        $('#dt').html("");

        if(exam_date){
            $("#myLoader").show();
            $.ajax({
             method: "POST",
             url: BASE_URL+"ExamController/get_date_wise_paper_calculation",
             data: { category : category,
                exam_date : exam_date,
                shift : shift,
                [csrfName]:csrfHash
            },
        })
            .done(function( msg ) {
                $("#myLoader").hide();
                if(msg)
                    $('#dt').html(msg);
                else
                    $('#dt').html("No Data Found!");
            });
        }
        else{
            $("#exam_date").css("border","solid 1px red"); 

        }

    });
</script>