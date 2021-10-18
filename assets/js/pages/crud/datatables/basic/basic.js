"use strict";
var KTDatatablesBasicBasic = function() {

	var initTable2 = function() {
		var table = $('#kt_datatable_2');

		// begin first table
		table.DataTable({
			responsive: true,

			lengthMenu: [10, 25, 50, 100 ],

			pageLength: 10,

			language: {
				'lengthMenu': 'Display _MENU_',
			},
			sortable: true,
			pagination: true,
			scroll: true,
			// Order settings
			order: [[0, 'asc']],
		});
	};
	
	var initTable = function() {
		var table = $('#kt_datatable');

		// begin first table
		table.DataTable({
			responsive: true,

			lengthMenu: [10, 25, 50, 100],
			pageLength: 10,

			language: {
				'lengthMenu': 'Display _MENU_',
			},
			sortable: true,
			pagination: true,
			scroll: true,
			// Order settings
			order: [[0, 'asc']],
			 dom: '<"row" <"col-md-4" l><"col-md-4 text-center" B> <"col-md-4" f>>rt<"bottom"ip>',
			buttons: [
					  {
						"extend": "colvis",
						"text": "<i class='fa fa-search bigger-110 text-custom'></i>",
						"className": "btn-custom",
						columns: ':not(:first)'
					  },
					  {
						"extend": "copy",
						"text": "<i class='fa fa-copy bigger-110 text-custom'></i> Copy",
						"className": "btn-custom"
					  },
					  {
						"extend": "excel",
						"text": "<i class='fa fa-file-excel bigger-110 text-custom'></i> Excel",
						"className": "btn-custom"
					  },
					  {
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 text-custom'></i> Print",
						"className": "btn-custom"
					  },
					], 
		});
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable2();
			initTable();
		}
	};
}();

jQuery(document).ready(function() {
	KTDatatablesBasicBasic.init();
});
