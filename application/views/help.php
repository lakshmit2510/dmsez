  <?php $this->load->view('template/header'); ?>

<div class="be-content">
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-9">
              <div class="panel panel-default panel-border-color panel-border-color-primary be-loading">
                <div class="panel-heading panel-heading-divider"><i class="icon mdi mdi-help-outline"></i> <?php echo $Title;?></div>
                <div class="panel-body">
                 <h4>Click to view the user manual for dock booking and to go through features of DMS : <a href="<?php echo base_url('Dashboard/Manual')?>" target="_blank">Open link <i class="mdi mdi-open-in-new"></i></a></h4><hr>
                 <h4>For other enquiries/support:</h4>
                 <h4>Please contact : <a href="mailto:support@satsez.com">support@satsez.com</a></h4>
              </div>
            </div>
          </div>
    
    <?php $this->load->view('template/footer'); ?>
    <script src="<?php echo base_url();?>assets/lib/parsley/parsley.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){ 
        $('form').parsley();

        $('select').select2();

        $('form').submit(function(){
          $('.be-loading').addClass('be-loading-active');
        }); 

       });
    </script>