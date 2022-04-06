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
   
   
   <a data-magnify="gallery" data-src="" data-caption="<?php echo $doc["document_name"] ?>" data-group="a" href="<?=base_url('assets/activity/'.$image->activity_file)?>">
   <img  src="<?=base_url('assets/activity/'.$image->activity_file)?>" alt=""  style=" cursor:pointer ;padding: 15px 10px;height: 150px;">
		</a>
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

</script>
            