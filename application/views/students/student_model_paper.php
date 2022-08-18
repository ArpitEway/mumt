<table class="table">
	<thead>
		<tr>
			<th>S.no</th>
			<th>Paper Code</th>
			<?php if ($students[0]->sub_group_id!=0): ?> 
			<th>Sub Group</th>
			<?php endif ?> 
			<th>Paper Name</th>	
			<th>Paper</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		foreach($students as $student){   
	        $paper_name =  str_replace('/', ' ', $student->test_id); 
            $url = './assets/model_paper/'.$paper_name.'.pdf';
			?>
			<tr>
				<?php if(file_exists($url)) { ?>
				<td><?php echo $i++; ?></td> 
				<td><?php echo $student->paper_code ?></td>
				<?php if ($student->sub_group_id!=0): ?> 
					<td><?php echo $this->Common_model->getSubGroupNameById($student->sub_group_id); ?></td>
				<?php endif ?> 
				 <td><?php echo $student->paper_name ?></td>
				<td class="m-auto">
                <?php
                    ?>
                    <a href="<?php echo site_url($url);?>" download="<?php echo $student->paper_name ;?>"><img src="<?=base_url('assets/images/')?>pdf.png" width="30"></a>
                <?php } ?>

                </td>
			</tr>		
			<?php 
		}				
		?>
	</tbody>
</table>
 
