<style>
.btn.btn-primary i {
    color: #FFFFFF !important;
}
</style>
<div class="text-right mt-3">
<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/teacher/create'); ?>', 'Create Teacher')"  >Create Teacher</a>
</div>

<div class=" mt-5" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
			<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Address</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Subject</th>
						<th>Clg Name</th>
						<th>Status</th>
             <th>Action </th>
					</tr>
				</thead>
			<tbody>
    		<?php
    		$i = 1;
            foreach($teachers as $teacher){

    		?>
					<tr>
					<td><?php echo $i; ?></td>
				

					<td><?php echo $teacher['name']; ?> </td>	
					<td><?php echo $teacher['address']; ?> </td>	
					<td><?php echo $teacher['email']; ?> </td>	
					<td><?php echo $teacher['phone']; ?> </td>	
					<td><?php echo $teacher['subject']; ?> </td>	
					<td><?php echo $teacher['clg_name']; ?> </td>	
				
             <td>
                <button id="btn_<?php echo  $teacher['id']?>" <?php if($teacher['status']=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="statusChange(<?php echo $teacher['id'];  ?>,'<?php echo $teacher['status'];?>')">
                <?php if($teacher['status']=='Y' ){echo "Yes" ;}else{
                  echo " No";
                } ?></button>
            </td>
            <td>
              <div style="display: inline-flex;">
                <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/teacher/edit/'.$teacher['id']); ?>', '<?php echo 'Update Teacher' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
                <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/ExamController/teachers/delete/'.$teacher['id']); ?>', showAllTeachers )"><i class="mdi mdi-delete delete-icon"></i></a>
              </div>
            </td>

</tr>
				
<?php $i++; } ?>

</tbody>
</table>

</div>

<script>
  function statusChange(id,status){
        var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
      $.ajax({
       url: BASE_URL+"admin/ExamController/update_teacher_status",
        type:"post",
        dataType: 'json',
        data:{"id":id,"status":status,[csrfName]:csrfHash},
        success: function(response){
          if(response.success==true){
          $("#btn_"+id).removeClass("btn btn-success");
          $("#btn_"+id).addClass("btn btn-danger");
          $("#btn_"+id).html("No");
           var s="statusChange("+ id +",'N')";
          $("#btn_"+id).attr("onclick",s);
        }else  if(response.error==false){
          $("#btn_"+id).removeClass("btn btn-danger");
          $("#btn_"+id).addClass("btn btn-success");
          $("#btn_"+id).html("Yes");
           var s="statusChange("+ id +",'Y')";
          $("#btn_"+id).attr("onclick",s);
        }
      }
    });
  }


  var showAllTeachers = function () 
    {
        var url = '<?php echo site_url('admin/ExamController/teacher'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                initDataTable('basic-datatable');
            }
        });
    }
 </script>    
