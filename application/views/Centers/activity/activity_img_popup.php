<?php
	       $activity_file  = $this->Common_model->getRecordByWhere('activity_file',array('activity_id'=> $param1));
        //    echo "<pre>";
        //    print_r($activity_file);
 ?>
 <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<div class="">
  <div class=" w3-third" style="display:flex">
  <?php  
  foreach($activity_file as $image){
    ?>
   <img  onclick="onClick(this)"  src="<?=base_url('assets/activity/'.$image->activity_file)?>" alt=""  style=" cursor:pointer ;padding: 15px 10px;height: 100px;">
    <?php
    }
    ?>
  </div>
</div>

<div id="modal01" class="w3-modal" onclick="this.style.display='none'">
  <span class="w3-button w3-hover-red w3-xlarge w3-display-topright">&times;</span>
  <div style="width:50%;height:50%" class="w3-modal-content  w3-animate-zoom">
    <img id="img01" style="width:100%">
  </div>
</div>
<script>
function onClick(element) {
  document.getElementById("img01").src = element.src;
  document.getElementById("modal01").style.display = "block";
}
</script>
            