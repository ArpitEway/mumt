<div class="dt-responsive">
<table id="kt_datatable_2" class="table table-striped" >
			<thead>
				<tr>
					<th>Form No.</th>
					<th>Course</th>
					<th>Class</th>
					<th>Student Name</th>
					<th>Father name</th>	
					<th>Document</th>
					<th>Action</th>
				</tr>
			</thead>
    		<tbody>
    		<?php 
    		$i = 1;
			foreach($students as $student){
			$docs = $this->Common_model->getAllRow("admission_document",'document_name,document_image',array("student_id" => $student['student_id']),'');
			?>
			
			<tr>
			<td><a target="_blank" href="<?php echo BASE_URL('admin/enrollment/show_form/'.$this->Common_model->encrypt_decrypt($student['student_id'],'encrypt')); ?>" ><?php echo $student["student_id"]; ?></a></td>
				<td><?php echo $student["course_name"]; ?></td>
				<td><?php echo $student["class_name"]; ?></td>
				<td><?php echo $student["name"]; ?></td>
				<td><?php echo $student["f_h_name"]; ?></td>
				<td><?php 

	$student_id = $this->Common_model->encrypt_decrypt($student["student_id"]); 
				foreach($docs as $doc){
				$ext = explode(".",$doc["document_image"]);
				
				if($ext[1] == "pdf")
				{ 
					 if($doc["document_name"] == 'Aadhaar Card'){ 
				?>

				<a href="<?php echo site_url('admin/enrollment/update_aadhar/'.$student_id); ?>">
				<?php echo $doc["document_name"]; ?>
				</a>
				<br>
				<?php }else{ ?>

				<a target="_blank" href="<?php echo BASE_URL('assets/documents/'.$doc["document_image"]); ?>">
				<?php echo $doc["document_name"]; 
					} ?> 
				</a><br>
				
				<?php }else{ ?>
				
				<a data-magnify="gallery" data-src="" data-caption="<?php echo $doc["document_name"] ?>" data-group="a" href="<?php echo BASE_URL('assets/documents/'.$doc["document_image"]); ?>">
					  <?php echo $doc["document_name"]; ?>  
				</a>
				<br>
			<?php 
				}
			}
			?> 
				</td>
				<td>
                <div style="display: inline-grid;">
					<?php if($student["approved"] != 'Y' || $student["approved"] == "" ){ ?>
					<a  style="margin:5px;" class="btn btn-success" data-id="<?php echo site_url('admin/enrollment/make_approved/'.$student_id); ?>" data-st_id="<?=$student_id?>" onclick="make_approved(this)"> Make Approved </a>
					<a href="javascript:void(0);" style="margin:5px;" class="btn btn-danger" onclick="rightModal('<?php echo site_url('admin/modal/student_popup/admin/student/update/remark_update/'.$student_id); ?>', '<?php echo 'Select Remark' ?>')"> Make Non approve
					</a>
					<span  class="remark_span_<?=$student["student_id"];?>" style="color:red;">
					<?php if($student["remark"] != "N" )
					{

					$remarkk = explode(",",$student["remark"]);

					foreach($remarkk as $rem)
					{
					$remark_text = $this->Common_model->getStudentRemarkNameById($rem);
					
					echo $remark_text; ?><br>
				<?php  
			}
				}
					echo "</span>";

				}else{ ?>

				<a style="margin:5px;" class="btn btn-success" > Approved </a>
				</a>   
				<?php } ?>		
				</div>
				</td>
				</tr>
			<?php
			$i++;
			} 
			?>
			</tbody>
</table>
</div>
<script src="https://cdn.bootcss.com/prettify/r298/prettify.min.js"></script>
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="<?=BASE_URL()?>assets/light_box/js/jquery.magnify.js"></script>
<script>

    window.prettyPrint && prettyPrint();

    var defaultOpts = {
      draggable: true,
      resizable: true,
      movable: true,
      keyboard: true,
      title: true,
      modalWidth: 320,
      modalHeight: 320,
      fixedContent: true,
      fixedModalSize: false,
      initMaximized: false,
      gapThreshold: 0.02,
      ratioThreshold: 0.1,
      minRatio: 0.05,
      maxRatio: 16,
      headToolbar: ['maximize', 'close'],
      footToolbar: ['zoomIn', 'zoomOut', 'prev', 'fullscreen', 'next', 'actualSize', 'rotateRight'],
      multiInstances: true,
      initEvent: 'click',
      initAnimation: true,
      fixedModalPos: false,
      zIndex: 1090,
      dragHandle: '.magnify-modal',
      progressiveLoading: true
    };

    var vm = new Vue({
      el: '#playground',
      data: {
        options: defaultOpts
      },
      methods: {
        changeTheme: function (e) {
          if (e.target.value === '0') {
            $('.magnify-theme').remove();
          } else if (e.target.value === '1') {
            $('.magnify-theme').remove();
            $('head').append('<link class="magnify-theme" href="css/magnify-bezelless-theme.css" rel="stylesheet">');
          } else if (e.target.value === '2') {
            $('.magnify-theme').remove();
            $('head').append('<link class="magnify-theme" href="css/magnify-white-theme.css" rel="stylesheet">');
          }
        }
      },
      updated: function () {
        $('[data-magnify]').magnify(this.options);
      }
    });

function make_approved(param){

	var html = $(param).html();
	var html = $(param).prop("onclick", null).off("click");
	var url  = $(param).attr('data-id');
	var rem = $(param).attr('data-st_id');
	
	if (confirm('Are you sure to make approved')) 
	{
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
				
				$(param).html("Approved");
				$(param).siblings('a').hide();
				$('.remark_span_'+rem).html("");
            }
        });
		
	} 
}



</script>