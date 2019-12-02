<?php $this->load->view('template/header'); ?>

<div class="be-content">
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-border-color panel-border-color-primary be-loading">
                <div class="panel-heading panel-heading-divider"><i class="icon mdi mdi-car"></i> <?php echo $Title;?></div>
                <div class="panel-body">
                  <form action="<?php echo base_url('Booking/editBookingPost/').$Booking_id;?>" class="form-horizontal" method="post">
                     <input type="hidden" value="<?php echo $Booking_id ?>"   name="booking_id">
                      <div class="step-content">
                          <input type="hidden" name="VType" id="pVType">
                          <div data-step="1" class="step-pane active">
                          <div class="form-group">
                              <label class="col-sm-3 control-label">Vehicle Number</label>
                              <div class="col-sm-6">
                                  <select class="form-control required" data-parsley-trigger="keyup" id="VNumber" name="VNumber">
                                      <option value="">--- Choose Vehicle Number ----</option>
                                      <?php
                                      foreach ($vnumber as $key => $value) {
                                          if($booking->ID === $value->ID){
                                              echo '<option value="'.$value->ID.'" selected>'.$value->VehicleNo.'</option>';
                                          }else{
                                              echo '<option value="'.$value->ID.'">'.$value->VehicleNo.'</option>';
                                          }

                                      }
                                      ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-3 control-label">Vehicle Type</label>
                              <div class="col-sm-6">
                                  <input type="text" id="VType" class="form-control"  value="<?php echo $booking->Type;?>" readonly="true">
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-3 control-label">Driver Name</label>
                              <div class="col-sm-6">
                                  <select class="form-control required" id="Driver" name="Driver">
                                      <option value="">--- Choose Driver ----</option>
                                      <?php
                                      foreach ($vnumber as $key => $value) {
                                          if($value->ID === $booking->ID){

                                              echo '<option value="'.$value->ID.'" selected>'.$value->DriverName.'</option>';
                                          }else {
                                              echo '<option value="' . $value->ID . '">' . $value->DriverName . '</option>';
                                          }
                                      }
                                      ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-3 control-label">Driver NRIC/FIN</label>
                              <div class="col-sm-6">
                                  <input type="text" name="NRIC" id="NRIC" value="<?php echo $booking->DriverNRIC;?>" readonly="true" class="form-control">
                              </div>
                          </div>
                        <div class="form-group">
                          <div class="col-sm-3"></div>
                          <div class="col-sm-6">
                            <button type="submit" class="btn btn-space btn-primary">Submit</button>
                            <a href="<?php echo base_url('Booking');?>" class="btn btn-space btn-default">Cancel</a>
                          </div>
                        </div>
                        </div>
                      </div>
                  </form>
              </div>

              <div class="be-spinner">
                <svg width="50px" height="50px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                  <circle fill="none" stroke-width="5" stroke-linecap="round" cx="33" cy="33" r="30" class="circle"></circle>
                </svg>
              </div>

            </div>
          </div>

    <?php $this->load->view('template/footer'); ?>
    <script src="<?php echo base_url();?>assets/lib/parsley/parsley.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/lib/bootstrap-slider/js/bootstrap-slider.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/lib/jquery.gritter/js/jquery.gritter.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('form').parsley();

        // $('select').select2();

        $('#VNumber').change(function() {
              datas = new FormData();
              datas.append('VNumber', $(this).val());
              $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url();?>Booking/getVehicleInfo',
                  data: datas,
                  dataType: 'JSON',
                  contentType: false,
                  processData: false,
                  beforeSend: function() {
                       $('.be-loading').addClass('be-loading-active');
                  },
                  success: function(data)
                  {
                      $('.be-loading').removeClass('be-loading-active');
                      $('#pVType').empty();
                      $('#VType').empty();
                      $('#pVType').val(data.VehicleTypeID);
                      $('#VType').val(data.Type);
                  }
              });
          });

          $('#Driver').change(function() {
              datas = new FormData();
              datas.append('Driver', $(this).val());
              $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url();?>Booking/getDriverInfo',
                  data: datas,
                  dataType: 'JSON',
                  contentType: false,
                  processData: false,
                  beforeSend: function () {
                      $('.be-loading').addClass('be-loading-active');
                  },
                  success: function (data) {
                      $('.be-loading').removeClass('be-loading-active');
                      $('#NRIC').empty();
                      $('#NRIC').val(data.DriverNRIC);
                  }
              });
          });

          $('form').submit(function(){
              $('.be-loading').addClass('be-loading-active');
          });
       });
    </script>