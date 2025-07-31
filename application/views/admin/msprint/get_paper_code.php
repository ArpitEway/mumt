 <script src="<?=base_url()?>assets/js/pages/crud/forms/widgets/bootstrap-select.min.js"></script> 
<script src="<?=base_url()?>assets/js/pages/crud/forms/widgets/bootstrap-select.js"></script>
<select class="form-control kt-selectpicker" name="paper_codes[]" multiple="multiple">
    <?php
        foreach($exam_data as $exam){
            echo '<option value="'.$exam->paper_code.'">'.$exam->paper_code.'</option>';
        }
    
    
    ?>
</select>