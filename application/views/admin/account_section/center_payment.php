<div class="mt-5" >
    <div class="text-right mb-5">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
        <a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/account/add_center_payment') ?>', 'Add Payment')"  >ADD PAYMENT</a>
    </div>
    <table id="memListTable" class="table table-striped dt-responsive" style="width:100%">
        <thead>
            <tr>
               <td>#</td>
               <td> Center Code</td>
               <td> Type</td>
               <td> Payment Date</td>
               <td> Amount</td>
               <td> Txn Id</td>
               <td> Bank</td>
               <td> Remark</td>
               <td> Enter Date</td>
               <td> Receipt Number</td>
               <td> Receipt Date</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        var csrfName = $('.csrfname').attr('name');
         var csrfHash = $('.csrfname').val(); 
         var data = {
		[csrfName]:csrfHash
	};
	
        var myTable =  $('#memListTable').DataTable({
            // Processing indicator
            "processing": true,
            // DataTables server-side processing mode
            "serverSide": true,
            // Initial no order.
            "order": [0],
            // Load data from an Ajax source
            "ajax": {
            "url": BASE_URL+'admin/account/getCenterPayment',
            "data": data,
            "type": "POST"
            },
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            //Set column definition initialisation properties
            "columnDefs": [{ 
            "targets": [0],
            "orderable": false
            }],
            dom: '<"row" <"col-md-4" l><"col-md-4 text-center" B> <"col-md-4 col-md-4" f>>rt<"bottom"ip>',
            buttons: [
            {
                "extend": "colvis",
                "text": "<i class='fa fa-search bigger-110 text-white'></i>",
                "className": "btn-custom",
                columns: ':not(:first)'
            },
            {
                "extend": "copy",
                "text": "<i class='fa fa-copy bigger-110 text-white'></i> Copy",
                "className": "btn-custom"
            },
            {
                "extend": "excel",
                "text": "<i class='fa fa-file-excel bigger-110 text-white'></i> Excel",
                "className": "btn-custom"
            },
            {
                "extend": "print",
                "text": "<i class='fa fa-print bigger-110 text-white'></i> Print",
                "className": "btn-custom"
            },
            ],
        });
    });

    function drow_table() {
        $('#memListTable').DataTable().draw();
    }
</script>