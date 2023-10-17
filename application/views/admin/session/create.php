<?php
$this->db->select('*');
$this->db->order_by(' id DESC');
$oldSession = $this->db->get('session')->result();
//print_r( $oldSession[0]);

$enrollment_code_reg =  $oldSession[0]->enrollment_code_reg;
$enrollment_code_reg++;
$enrollment_code_pvt =  $oldSession[0]->enrollment_code_pvt;
$enrollment_code_pvt++;

?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/session/create'); ?>">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="name">Session Name</label>
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <input type="text" class="form-control" id="session" name = "session" required placeholder="Enter name of session">
        </div>
        <div class="form-group col-md-4">
            <label for="name">Regular Enrollment Code</label>
            <input type="text" class="form-control" id="enrollment_reg" name="enrollment_reg" required placeholder="Enter Enrollment Code" value="<?=$enrollment_code_reg?>" readonly>
        </div>
        <div class="form-group col-md-4">
            <label for="name">Private Enrollment Code</label>
            <input type="text" class="form-control" id="enrollment_pvt" name="enrollment_pvt" required placeholder="Enter Enrollment Code" value="<?=$enrollment_code_pvt?>" readonly>
        </div>
    </div>
    <div class="form-group text-center">
        <button class="btn btn-md btn-primary" type="submit">Submit</button>
    </div>
</form>

<script>
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });
</script>