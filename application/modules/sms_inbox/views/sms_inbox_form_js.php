<script>
 


	$(document).ready(function(){


 


		$("#fuckyouform").submit(function(){
		 


			
			$.ajax({
				url : '<?php echo site_url("$controller/kirimpesan"); ?>',
				dataType:'json',
				beforeSend : function(){
					 
				 

					$('#myPleaseWait').modal('show');
				},
				 
				type : 'post',
				data : $(this).serialize(),
				success : function(obj) {
					$('#myPleaseWait').modal('hide');
					 if(obj.error==true){

						  BootstrapDialog.alert({
			                type: BootstrapDialog.TYPE_DANGER,
			                title: 'Error',
			                message: obj.message,
			                 
			            }); 				
						
						

						

 
					 }
					 else {			
					 

					 $("#DestinationNumber").val('');
					 $("#TextDecoded").val('');

						
						BootstrapDialog.alert({
			                type: BootstrapDialog.TYPE_PRIMARY,
			                title: 'Informasi',
			                message: obj.message,
			                 
			            });  	 
						  
							 	 
						}
				}
			});
			
			return false;
		});









	 
	
	

		
		
	});
	 
</script>