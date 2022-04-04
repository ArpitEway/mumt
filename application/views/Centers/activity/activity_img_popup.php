<?php
	       $activity_file  = $this->Common_model->getRecordByWhere('activity_file',array('activity_id'=> $param1));
        //    echo "<pre>";
        //    print_r($activity_file);
 ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">


<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
 <style>
     .gallary {
         margin : 10px 50px ;
     }
     .gallary img {
       transition : 1s ;
       padding : 15px ;
       width : 200px ;
     }
     .gallary img:hover {
         filter : grayscale(100%);
         transform : scale(1.1);
     }
     /* .lb-data .lb-close {
    position: relative;
    top: 38px;
    left: 38px;
    z-index: 999; */
}
 </style>
<div class="gallary">
    <?php  
            foreach($activity_file as $image){
    ?>
     <a   href="<?=base_url('assets/activity/'.$image->activity_file)?>"  data-lightbox="photos" ><img   src="<?=base_url('assets/activity/'.$image->activity_file)?>" alt="" style="padding: 15px 10px;  width:  100px; object-fit: cover; height: 100px;"></a>
    <?php
            }
    ?>
</div>
<script>
    // lightbox.option({
    //   'resizeDuration': 200,
    //   'wrapAround': true
    // })
</script>