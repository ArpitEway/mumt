
<style>
  table, th, td {
    border: 1px solid black;
    padding: 5px;
    font-size: 18px;
  }
  .table-bordered td{
    border:0;
  }
  {
    height: 180px;
    width: auto;
    object-fit: cover;
  }

  .text-primary{
    color: #16447e !important;
  }

  .table thead th, .table thead td {
    font-size: 18px;
    vertical-align: middle;
    border: 1px solid #000;
  }
</style>
<style>
  @media print {
   .breakhere { page-break-before:always;  };
 }
 th.border.border-dark {
   vertical-align: middle;
 }
</style>
<div id="printarea" style="width:1000px; margin:auto">
 <div class="border border-dark border-bottom-0">
   <div class="text-center py-3">
    
      <!-- <img src="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png"  width="100px;" /> -->
      <h1 class="text-center p-5" style="font-size:34px; color: #781e19;">Maharishi Mahesh Yogi Vedic Vishwavidyalaya</h1>
    <!-- <img src="<?=base_url()?>assets/images/maskgroup/Group1.png" class="img2" alt=""> -->
    <h4 class="text-primary text-center mb-0">Examination Held In Aug 2022</h4>
  </div>
</div>
<table class="table mb-0">
  <tbody>
    <tr>
      <th class="border-top-0 text-primary pl-3">Enrollment No.</th>
      <th class="border-top-0"><?php  echo $student->enrollment_no ?></th>
      <th class="border-top-0 text-primary pl-3">Roll No.</th>
      <th class="border-top-0"><?php echo  $student->roll_no; ?></th>
      <th rowspan="3" class="border-top-0 text-center" width="120px"><img class="img img-thumbnail" src="<?=base_url('assets/student_image/').$student->session.'/'.$student->photo?>" ></th>
    </tr>
    <tr>
      <th class="border-top-0 text-primary pl-3">Name</th>
      <th class="border-top-0"><?php  echo $student->name ?></th>
      <th class="border-top-0 text-primary pl-3">F/H Name</th>
      <th class="border-top-0"><?php  echo $student->f_h_name ?></th>
    </tr>
    <tr>
      <th class="border-top-0 text-primary pl-3">Course</th>
      <th class="border-top-0"><?php  echo $student->course_name ?></th>
      <th class="border-top-0 text-primary pl-3">Year / Sem </th>
      <th class="border-top-0"><?php  echo 
      $this->Common_model->getClassNameByClassId($student->class_id);
       ?></th>
    </tr>
  </tbody>
</table>