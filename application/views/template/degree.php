<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/admit_card.css?token=?token='.date('dmyhis'))?>">
    <link rel="shortcut icon" href="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png" />
    <title>Admit Card</title>
	    <!--[CSS/JS Files - Start]-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
    <script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
  
    <style>
		.table-bordered td, .table-bordered th, .table thead th {
    	font-size: 16px;
		}
        #container_content{
           
            top: 0;
             width: 100%;
            height: 100%; 
            background: url("<?=base_url()?>assets/images/maskgroup/degree.jpg") no-repeat center; 
            
            background-repeat:no-repeat;
            background-size:cover;
          
            background-size: 100%;
             width:1150px;height:1580px;
              
          
        }
       #container_content img{
            z-index: -1;
            position: absolute;
            left: 0px;
            top: 0px;
        }
        .labelFont{
            font-size:15px;
            color:#32367A;
        }
        .labelBodyFont{
            font-size:17px;
            color:#32367A;
        }
        .table tr td{
            border:none;
        }
        .dataShow
        {
            font-size:18px;
            font-weight:600;
            border-bottom: 2px dotted #000;
                        
        }
        input { 
            
            outline: 0; 
            border-width: 0 0 2px; 
            border-bottom: 2px dotted #000 !important;
            background: transparent;
            text-align: center;
        } 
	</style>
  </head>
  <body >
<section >
	<div class=""><?php 
    $arr=explode(' ',$old_exam_data[0]->exam_year);
     $passing_year=end($arr);
     $total_marks=$obtain_marks=0;
     foreach($old_exam_data as $old){
        
         $total_marks+=$old->total_marks;
         $obtain_marks+=$old->obtain_marks;
     }
     $division ="";
      $percentage=($obtain_marks/$total_marks)*100;
     if($percentage>=60){
        $division = "First";
        $division_hindi="प्रथम";
      }elseif($percentage<60 && $percentage>=40){
        $division  = "Second";
        $division_hindi="द्वितीय";
      }else{
        $division = "Third";
        $division_hindi="तृतीया";
      }
    
      $course_name_hindi = $this->Common_model->getSinglefield('course_group','course_name_hindi',array('id' => $student[0]->course_group_id));
     // echo $this->db->last_query(); die;
    ?> 

	<div  id="container_content"  style="margin: auto; ">
		<div class="admit-card" style="border:none !important; "> 
			
            <input type="hidden" value="<?=$student->student_id?>" id="student_id">
            <h2 style="text-align:center;margin-top:220px;"><?=$course_name_hindi?></h2>
            <table class="table " style="margin-top:110px;border:none">
                <tbody style="border:none">
                    <tr style="border:none">
                    <td>&nbsp;</td>
                                    <td ><b class="labelFont">अनुक्रमांक  </b><span class='dataShow'><?=$application[0]->roll_no?></span></td>
                                    
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style="float:right;"><b class="labelFont">पंजीयन क्रमांक </b> <span class='dataShow'><?=$student[0]->enrollment_no?></span></td>
                     </tr>           
                  </tbody>
            </table>
            
            <p style="margin-top:200px;"><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;                  प्रमाणित किया जाता है कि श्री/श्रीमती/कुमारी </b> <input type="text" class="dataShow" value="<?=$application[0]->name_hindi?>" style="width:550px" readonly></pre></p>
            <p ><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp;        पिता/पति श्री </b> <input type="text" class="dataShow" value="<?=$application[0]->fname_hindi?>" style="width:695px" readonly /><b class="labelBodyFont">ने इस विश्वविद्यालय</b></pre></p>
		
            <p ><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp;        द्वारा </b> <input type="text" class="dataShow" value="<?=$passing_year?>" style="width:336px" readonly /><b class="labelBodyFont">में आयोजित</b><input type="text" class="dataShow" value="<?=$course_name_hindi?>" style="width:362px" readonly /><b class="labelBodyFont">(दूरस्थ शिक्षा)</b></pre></p>
    

            <p ><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp;        परीक्षा</b><input type="text" class="dataShow" value="<?=$division_hindi?>" style="width:315px" readonly /><b class="labelBodyFont">श्रेणी में उत्तीर्ण की।</b></pre></p>
            <p ><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;                  इन्हें</b> <input type="text" class="dataShow" value="<?=$course_name_hindi?>" style="width:336px" readonly /><b class="labelBodyFont">की उपाधि प्रदान की जाती है।</b></pre></p>
				<!-- <strong>This is a computer-generated document. No signature is required</strong> -->
			<!-- English -->
            <h2 style="text-align:center;margin-top:90px;"><?=$student[0]->course_name?></h2>
                <table class="table " style="margin-top:80px;border:none">
                <tbody style="border:none">
                    <tr style="border:none">
                                    <td>&nbsp;</td>
                                    <td ><b class="labelFont">Roll No.  </b><span class='dataShow'><?=$application[0]->roll_no?></span></td>
                                    
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style="float:right;"><b class="labelFont">Registration No. </b> <span class='dataShow'><?=$student[0]->enrollment_no?></span></td>
                     </tr>           
                  </tbody>
            </table>
            
            <p style="margin-top:200px;"><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;                  This is to certify that Shri/Smt./Ku. </b> <input type="text" class="dataShow" value="<?=$student[0]->name?>" style="width:494px" readonly /> </pre></p>
            <p ><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp;        S/o, D/o, W/o Shri </b> <input type="text" class="dataShow" value="<?=$student[0]->f_h_name?>" style="width:609px" readonly /><b class="labelBodyFont">has passed</b></pre></p>
		
            <p ><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp;        the examination for the Degree of</b> <input type="text" class="dataShow" value="<?=$student[0]->course_name?>" style="width:336px" readonly /><b class="labelBodyFont">(Distance Education) of this</b></pre></p>
    

            <p ><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp;        University held in</b> <input type="text" class="dataShow" value="<?=$passing_year?>" style="width:315px" readonly /><b class="labelBodyFont">in</b><input type="text" class="dataShow" value="<?=$division?>" style="width:315px" readonly /><b class="labelBodyFont">division.</b></pre></p>
            <p ><pre><b class="labelBodyFont">&nbsp;&nbsp;&nbsp;&nbsp;        The Degree of</b> <input type="text" class="dataShow" value="<?=$student[0]->course_name?>" style="width:336px" readonly /><b class="labelBodyFont">is being awarded to him/her.</b></pre></p>    
			<p style="margin-top:50px;">
                <span style="margin-left:50px;"><b class="labelFont" style="font-style: italic;">Date </b>................</span>
                <span><b class="labelFont" style="font-style: italic;float: right;  margin-right: 50px;">Vice Chancellor </b></span>
            </p>
		</div>
	</div>
<div class="text-center" style="margin-top:10px;">
    <input type="button" id="rep" value="Download" class="btn btn-primary btn_print mb-5">
</div>
</section>
    
 

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
	<script>
	$(document).ready(function($) 
    { 

        $(document).on('click', '.btn_print', function(event) 
        {
            event.preventDefault();
            var element = document.getElementById('container_content'); 
            var student_id = document.getElementById('student_id').value;
            //more custom settings
            var opt = 
            {
             
              filename:     'degree_'+student_id+'.pdf',
              image:        { type: 'jpeg', quality: 0.98 },
              html2canvas:  { scale: 2, width: 1150,height: 1620 },
              jsPDF:        { unit: 'in', format: 'A4', orientation: 'portrait' }
            };
            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();  
        });
	});
	</script>
  </body>
</html>