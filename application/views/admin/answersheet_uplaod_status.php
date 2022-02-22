<table class="table " style="text-transform: uppercase;">
				<thead>
					<tr>
					
						<th>Total</th>
						<th>Uploaded</th>
						<th>Rremaining</th>
						<th>Checked</th>
					</tr>
				</thead>
                <tbody>
        <?php
            ?>
            <tr>
           
            <td><?php echo  $total_paper_count ; ?></td>
            <td><?php echo $uploaded ; ?></td>
            <td><?php  echo $total_paper_count-$uploaded ; ?></td>
            <td><?php echo $checked ; ?></td>
            </tr>
            <?php
      
         ?>
    </tbody>
			</table>