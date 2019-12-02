<?php $this->load->view('template/header'); ?>
<style type="text/css">
/*table.dataTable, .DTFC_RightBodyLiner td { background-color: #fff; }
.DTFC_RightHeadWrapper, .DTFC_RightBodyWrapper { width: 100%; }
.DTFC_RightBodyLiner { overflow: hidden !important; }
.DTFC_RightWrapper{ right: -10px !important; }*/
</style>
<div class="be-content">
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-border-color panel-border-color-primary be-loading">
                <div class="panel-heading panel-heading-divider"><?php echo $Title;?></div>
                <div class="panel-body">
                  <?php if($this->session->flashdata('done')) { ?>
                  <div role="alert" class="alert alert-success alert-icon alert-icon-border alert-dismissible">
                    <div class="icon"><span class="mdi mdi-check"></span></div>
                    <div class="message">
                      <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="mdi mdi-close"></span></button><strong>Success!</strong> <?php echo $this->session->flashdata('done'); ?>.
                    </div>
                  </div>
                  <?php } ?>

                  <?php if($this->session->flashdata('error')) { ?>
                  <div role="alert" class="alert alert-danger alert-icon alert-icon-border alert-dismissible">
                    <div class="icon"><span class="mdi mdi-close"></span></div>
                    <div class="message">
                      <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="mdi mdi-close"></span></button><strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>.
                    </div>
                  </div>
                  <?php } ?>
                  <div class="table-responsive">
                  <table id="table3" class="table table-striped table-hover table-fw-widget nowrap">
                    <thead>
                      <tr>
                        <th width="90"># Job Order No</th>
                        <th>Supplier Name</th> 
                        <th>Booking Mode</th> 
                        <th>Vehicle Type</th>
                        <th>Vehicle Number</th>
                        <th>Dock Type</th> 
                        <th>Dock Number</th>
                        <th>Booked Date</th>
                        <th>Booked Time</th>
                        <th>Status</th>
                        <th width="230">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if($booking!=0)
                      {
                        foreach($booking as $row)
                        {
                          echo '<tr>
                            <td>'.$row->BookingRefNo.'</td>
                            <td>'.$row->UserName.'</td> 
                            <td>'.$row->Mode.'</td>
                            <td>'.$row->Type.'</td>
                            <td>'.$row->VehicleNo.'</td>
                            <td>'.$row->SlotType.'</td> 
                            <td>'.$row->SlotName.'</td>
                            <td class="center">'.date('m/d/Y',strtotime($row->CheckIn)).'</td>
                            <td class="center">'.date('H:i',strtotime($row->CheckIn)).' - '.date('H:i',strtotime($row->CheckOut)).'</td> 
                            <td><span class="label label-warning" style="background-color: '.$row->StatusColor.'">'.$row->StatusName.'</span></td>'; ?>
                            <td class="center">
                            <?php if(!in_array($this->session->userdata('Role'), array(5,6))) { ?>  
                            <a href="<?php echo base_url('Booking/')?>cancel/<?php echo $row->BookingID;?>" class="btn btn-space btn-danger" onclick="return confirm('Are you sure to Cancel Booking ?')"><i class="icon icon-left mdi mdi-close"></i> Cancel</a>
                            <a href="<?php echo base_url('Booking/Sendmail/'.$row->BookingID);?>" class="btn btn-space btn-primary btn-loader"><i class="icon icon-left mdi mdi-email"></i> Email</a>
                            <?php } ?>
                            <a href="<?php echo base_url('Booking/BPrint/'.$row->BookingRefNo)?>" class="btn btn-space btn-success btn-loader"><i class="icon icon-left mdi mdi-print"></i> Print</a>
                            <a href="<?php echo base_url('Booking/editBooking/'.$row->BookingID);?>" class="btn btn-space btn-primary btn-loader"><i class="icon icon-left mdi mdi-edit"></i> Edit</a>
                            </td>
                          </tr>
                        <?php
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                 </div>
                </div>
                <div class="be-spinner">
                  <svg width="50px" height="50px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                    <circle fill="none" stroke-width="5" stroke-linecap="round" cx="33" cy="33" r="30" class="circle"></circle>
                  </svg>
                </div>

              </div>
            </div>
          </div>
    <?php $this->load->view('template/footer'); ?>
    <script src="<?php echo base_url();?>assets/lib/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/datatable.js');?>" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/lib/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/lib/datatables/plugins/buttons/js/dataTables.buttons.js" type="text/javascript"></script>
    <!-- <script type="text/javascript" src="<?php echo base_url('assets/js/dataTables.fixedColumns.min.js');?>"></script> -->
    <script type="text/javascript">
      $(document).ready(function()
      {

        $('.btn-loader').click(function(){
          $('.be-loading').addClass('be-loading-active');
        });

        $("#table3").dataTable({
          buttons:["copy", 
          {
            extend: 'excel',
            className: 'btn btn-default',
            exportOptions: {
              columns: ['th:not(:last-child)']
            }
          },"pdf"],
          lengthMenu:[[10,25,50,-1],[6,10,25,50,"All"]],
          dom:"Bfrtip",
          'scrollX': '500px',
          'scrollCollapse': true,
          'fixedColumns':   {
            'leftColumns': 1,
            'rightColumns': 1
          },
          "order": [], //Initial no order.
          "bSorting": [],
          "pageLength": 20
        });

        $('.buttons-html5').addClass('btn btn-default');
      });
    </script>