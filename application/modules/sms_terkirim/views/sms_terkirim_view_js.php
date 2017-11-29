
<script>
$(document).ready(function(){
	// var dt = $("#leasing").dataTable(
	// 	{
	// 		"order": [[ 0, "desc" ]],
	// 	 	"iDisplayLength": 50

	// 	});

	 var dt = $("#leasing").DataTable(
		 	{
		 		// "order": [[ 0, "desc" ]],
		 		// "iDisplayLength": 50,
				"columnDefs": [ { "targets": 0, "orderable": false } ],
				"processing": true,
		        "serverSide": true,
		        "ajax": '<?php echo site_url("$controller/get_data") ?>'
		 	});
 
 

});



 


</script>