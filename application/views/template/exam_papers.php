<div class="pb-5" data-wizard-type="step-content">
	<h4 class="mb-10 font-weight-bold text-dark">Your Subjects</h4>
	<div class="row border border-primary bg-primary text-custom p-2">
		<div class="col-md-2"><strong>#</strong></div>
		<div class="col-md-3"><strong>Paper Code</strong></div>
		<div class="col-md-7"><strong>Subjects Name</strong></div>
	</div>
	<?php
		$i=0;
		foreach($exam_papers as $paper){
		?>
		<input type="hidden" id="updatepaper" value='N'>
		<div class="row border border-default p-2">
			<div class="col-md-2"><?=++$i; ?></div>
			<div class="col-md-3"><?=$paper['paper_code']?></div>
			<div class="col-md-7"><?php echo $this->Common_model->getPaperNameById($paper['paper_id']); ?></div>
		</div>
		<?php
		}
		
	?>
</div>