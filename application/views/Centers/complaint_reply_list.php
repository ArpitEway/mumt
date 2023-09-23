<table id="kt_datatable">
<thead>
			<tr>
				<th>S.No.</th>
				<th>Student Name</th>
				<th>Form No</th>
				<th>Course </th>
				<th>Class</th>
                <th>Type</th>
				<th>Detail</th>
                <th>Date</th>
				<th>Reply</th>
		    </tr>
		</thead>
        <tbody>
            <?php
            $i=1;
            foreach($complaints as $complaint){
               ?>
                <tr>
                     <td><?php echo $i; ?></td>
					<td><?php echo $complaint->name; ?></td>
					<td><?php echo $complaint->student_id; ?></td>
					<td><?php echo $complaint->course_name; ?></td>
					<td><?php echo $complaint->class_name; ?></td>
                    <td><?php echo $complaint->type; ?></td>
					<td><?php echo $complaint->details; ?></td>
					<td><?php echo $this->Common_model->viewDate($complaint->date); ?></td>
                    <td><?php echo $complaint->reply_text;?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
		<tbody>
</table>