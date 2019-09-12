<?php $this->load->view('template/header'); ?>
<style type="text/css">
 table tr th,td {
  font-size: 16px;
 } 
 td .label {
  font-size: 14px;
  font-weight: 400;
 }
 /*table.dataTable, .DTFC_RightBodyLiner td { background-color: #fff; }
 .DTFC_RightHeadWrapper, .DTFC_RightBodyWrapper { width: 125px; }
 .DTFC_RightBodyLiner { overflow: hidden !important; }*/
</style>
<div class="be-content">
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-border-color panel-border-color-primary">
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
                        <th>Booking Mode</th> 
                        <th>Company</th> 
                        <th>Vehicle Type</th>
                        <th>Driver Name</th>
                        <th>Vehicle No</th>
                        <th>Dock Type</th> 
                        <th>Booked</th>
                        <th>Booked Time</th>
                        <th>Actual In/Out Time</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if($booking!=0)
                      {
                        foreach($booking as $row)
                        {
                          $CheckIn = $CheckOut = '';
                          if(!empty($row->ActualCheckIn))
                          {
                            $CheckIn = date('H:i',strtotime($row->ActualCheckIn));
                          }

                          if(!empty($row->ActualCheckIn))
                          {
                            $CheckOut = date('H:i',strtotime($row->ActualCheckOut));
                          } 

                          echo '<tr>
                            <td>'.$row->BookingRefNo.'</td>
                            <td>'.$row->Mode.'</td>
                            <td>'.$row->CompanyName.'</td> 
                            <td>'.$row->Type.'</td>
                            <td>'.$row->DriverName.'</td>
                            <td>'.$row->VehicleNo.'</td>
                            <td>'.$row->SlotType.'</td> 
                            <td class="center">'.date('d/m/Y',strtotime($row->CheckIn)).'</td>
                            <td class="center">'.date('H:i',strtotime($row->CheckIn)).' - '.date('H:i',strtotime($row->CheckOut)).'</td> 
                            <td class="center" align="center">'.$CheckIn.' - '.$CheckOut.'</td>
                            <td><span class="label label-warning" style="background-color: '.$row->StatusColor.'">'.$row->StatusName.'</span></td>'; ?>
                          </tr>
                        <?php
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                 </div>
                </div>
              </div>
            </div>
          </div>
    <?php $this->load->view('template/footer'); ?>
    <script src="<?php echo base_url();?>assets/lib/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/datatable.js');?>" type="text/javascript"></script>
    <!-- <script type="text/javascript" src="<?php echo base_url('assets/js/dataTables.fixedColumns.min.js');?>"></script> -->
    <script src="<?php echo base_url();?>assets/lib/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/lib/datatables/plugins/buttons/js/dataTables.buttons.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $("#table3").dataTable({
          buttons:["copy", 
          {
            extend: 'excel',
            className: 'btn btn-default',
            exportOptions: {
              // columns: ['th:not(:last-child)']
            }
          },"pdf"],
          lengthMenu:[[10,25,50,-1],[6,10,25,50,"All"]],
          dom:"Bfrtip",
          "order": [], //Initial no order.
          "bSorting": [],
          "pageLength": 15
        });
        $('.buttons-html5').addClass('btn btn-default');
     });
    </script>