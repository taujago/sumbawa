<?php 
$userdata = $_SESSION['userdata'];
// show_array($userdata);
?>

<link href="<?php echo base_url("assets") ?>/css/datepicker.css" rel="stylesheet">
<script src="<?php echo base_url("assets") ?>/js/bootstrap-datepicker.js"></script>

<link href="<?php echo base_url("assets") ?>/css/select2.min.css" rel="stylesheet">
<script src="<?php echo base_url("assets") ?>/js/select2.full.min.js"></script>
<style>
#frm td {
	padding:5px;
}
</style>

  
 <div class="row">
  <div id="salah" class="col-lg-12" style="display:none">
            <div class="alert alert-danger" role="alert" id="message">
            	
            </div>
        </div>
    </div>
    
  <div class="row">
  <div id="benar" class="col-lg-12" style="display:none">
            <div class="alert alert-success" role="alert" id="message2">
            	
            </div>
        </div>
    </div> 


 
<div class="row">
<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">KIRIM PESAN</div>
            <div class="panel-body">
            
            <form id="fuckyouform">
 
  <div class="form-group">
    <label for="DestinationNumber">Nomor Tujuan : </label>
    <input type="text" class="form-control" id="DestinationNumber" name="DestinationNumber" placeholder="Nomor Tujuan">
  </div>


    <div class="form-group">
    <label for="TextDecoded">Isi Pesan : </label>
    <textarea  class="form-control" id="TextDecoded" name="TextDecoded" placeholder="Isi Pesan"></textarea>
  </div>

  <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                      
            </form>
            </div>
        </div>
</div>
</div>

 

<?php $this->load->view($controller."_form_js"); ?>