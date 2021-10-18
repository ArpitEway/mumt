<h4 class="mb-10 font-weight-bold text-dark">Select Your Subjects</h4>
						<input name="docLength" value="<?=$docLength?>" id="docLength" type="hidden">
						<div class="row border border-primary bg-primary text-custom p-2">
							<div class="col-md-2"><strong>#</strong></div>
							<div class="col-md-3"><strong>Paper code</strong></div>
							<div class="col-md-7"><strong>Subjects Name</strong></div>
						</div>
						<?php
							if($student['temp_exam_form']=='N'){
								$i=0;
							?>
							<input type="hidden" id="updatepaper" value='Y'>
							<?php
								foreach($compulsoryPapers as $paper){
									
								?>
								<div class="row border border-default p-2">
									<div class="col-md-2"><?=++$i; ?></div>
									<div class="col-md-3"><?=$paper['paper_code']; ?></div>
									<div class="col-md-7"><?=$paper['paper_name']?>
										<input name="papers[]" value="<?=$paper['id']; ?>" type="hidden" >
										<input name="paper_type[]" value="<?=$paper['type']; ?>" type="hidden" >
									<input name="paper_code[]" value="<?=$paper['paper_code']; ?>" type="hidden" ></div>
								</div>
								<?php } 
								if($class['class_group']=='Y'){
								?>
								<div class="row border border-primary bg-primary p-2 my-3 text-custom ">
									<div class="col-md-12 text-center "><strong>Select Any <?=$class['select_group']?> Group</strong></div>
								</div>
								<?php
									$group_name = '';
									foreach($groupPaper as $paper){
										if($group_name!=$paper->group_name){
											$group_name=$paper->group_name;
										?>
										<div class="row border border-primary bg-primary p-2 mt-3 text-custom">
											<div class="col-md-2">#</div>
											<div class="col-md-3">
												<label class="radio radio-success">
													<input name="group_id" class="" value="<?=$paper->group_id; ?>" type="radio" >
												<span></span></label>
												
											</div>
											<div class="col-md-7"><?=$paper->group_name; ?></div>
										</div>
									<?php } ?>
									<div class="row border border-default p-2">
										<div class="col-md-2"><?=++$i; ?></div>
										<div class="col-md-3"><?=$paper->paper_code; ?></div>
										<div class="col-md-7"><?=$paper->paper_name; ?></div>
										
									</div>
									
									<?php }
								} 
								}else if($exam_papers!=''){
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
							} 
						?>