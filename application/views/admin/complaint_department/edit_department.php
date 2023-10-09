<?php
$accounts = $this->db->get_where('department_complaint', array('id' => $param1))->result_array();
foreach($accounts as $account): ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo base_url('complaint_department/update/'.$param1); ?>">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="form-row">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input type="text" class="form-control" value="<?php echo $account['name']; ?>" id="name" name = "name" required placeholder="Enter Department Name">    
        </div>	
        <div class="form-group col-md-6">
            <label for="department">Choose Complaint Type:</label>
            <br>
            <select name="department[]" id="department" multiple style="width:100%">
                <?php
                 $ids = explode(',',$account['support_ids']);
                 $this->db->where_in('id',$ids);
                $departments = $this->Common_model->getRecordByWhere('support_system');
                foreach($departments as $depart){
                    ?>
                     <option value="<?= $depart->id?>" selected><?= $depart->name?></option>
                    <?php
                }
                $this->db->where_not_in('id',$ids);
                $departments_not = $this->Common_model->getRecordByWhere('support_system');
                foreach($departments_not as $depart){
                    ?>
                     <option value="<?= $depart->id?>"><?= $depart->name?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update Department</button>
	</div>
</form>

<?php endforeach; ?>
<style>
.plus_btn{
    color: #FFFFFF;
    background-color: #052C68;
    border-color: #052C68;
}
.minus_btn{
    color: #FFFFFF;
    background-color: #052C68;
    border-color: #052C68;
}
</style>
<script>
    //$(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });
</script>
