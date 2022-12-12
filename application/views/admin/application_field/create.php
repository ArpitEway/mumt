<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/application_field/create'); ?>">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="field">Field Name</label>
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <input type="text" class="form-control" id="field" name = "field" required placeholder="Enter name of Field">
        </div>
        <div class="form-group col-md-6">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" id="amount" name="amount" required placeholder="Enter Amount">
        </div>
        
    </div>
    <div class="form-group text-center p-3">
        <button class="btn btn-md btn-primary" type="submit">Submit</button>
    </div>
</form>

<script>
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });
</script>