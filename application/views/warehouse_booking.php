<?php $this->load->view('template/header'); ?>

<style type="text/css">
 #table3 tr th,td {
  font-size: 16px;
 } 
 #table3 .label {
  font-size: 14px;
  font-weight: 400;
 }
 .modal-effect-11 .modal-header {
  padding-bottom: 0;
 }
 .modal-effect-11 .modal-body .mdi {
  font-size: 95px;
 }
 .modal-effect-11 h3 {
  font-size: 30px;
  margin-bottom: 20px;
 }
 .modal-effect-11 p {
  font-size: 18px;
  line-height: 30px;
 }
 .modal-main-icon { width: 95px; }
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
                        <?php }
                        if($this->session->flashdata('ErrorCheckIn')) {
                        ?>
                        <div role="alert" class="alert alert-warning alert-icon alert-icon-border alert-dismissible">
                            <div class="icon"><span class="mdi mdi-info"></span></div>
                            <div class="message">
                                <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="mdi mdi-close"></span></button><strong>Warning : &nbsp;</strong> <?php echo $this->session->flashdata('MsgCheckIn');?>
                            </div>
                        </div>
                        <?php } if($this->session->flashdata('error')) { ?>
                            <div role="alert" class="alert alert-danger alert-icon alert-icon-border alert-dismissible">
                                <div class="icon"><span class="mdi mdi-close"></span></div>
                                <div class="message">
                                    <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="mdi mdi-close"></span></button><strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>.
                                </div>
                            </div>
                        <?php } ?>
                        <div role="alert" id="Noticebox" style="display: none;" class="alert alert-warning alert-icon alert-icon-border alert-dismissible">
                            <div class="icon"><span class="mdi mdi-info"></span></div>
                            <div class="message">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="mdi mdi-close"></span></button><strong>Warning : &nbsp;</strong> <span id="Noticemsg"></span></div>
                        </div>
                        <div class="table-responsive">
                            <table id="table3" class="table table-striped table-hover table-fw-widget nowrap">
                                <thead>
                                <tr>
                                    <th width="90"># Job Order No</th>
                                    <th>Supplier Name</th>
                                    <th>Driver Name</th>
                                    <th>Vehicle Number</th>
                                    <th>Dock Number</th>
                                    <th>Booked Time</th>
                                    <th>Security CheckIn Time</th>
                                    <th>Warehouse Checked-In/Out Time</th>
                                    <th>Status</th>
                                    <th width="180">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if($booking!=0)
                                    {
                                        foreach($booking as $row)
                                        {
                                            $CheckIn = $CheckOut = '';
                                            if(!empty($row->WarehouseCheckIn))
                                            {
                                                $CheckIn = date('H:i',strtotime($row->WarehouseCheckIn));
                                            }

                                            if(!empty($row->WarehouseCheckIn))
                                            {
                                                $CheckOut = date('H:i',strtotime($row->WarehouseCheckOut));
                                            }

                                            echo '<tr>
                                            <td>'.$row->BookingRefNo.'</td>
                                            <td>'.$row->UserName.'</td>
                                            <td>'.$row->DriverName.'</td> 
                                            <td>'.$row->VehicleNo.'</td>
                                            <td>'.$row->SlotName.'</td>
                                            <td class="center">'.date('H:i',strtotime($row->CheckIn)).' - '.date('H:i',strtotime($row->CheckOut)).'</td> 
                                            <td class="center">'.date('H:i',strtotime($row->ActualCheckIn)).'</td>
                                            <td class="center" align="center">'.$CheckIn.' - '.$CheckOut.'</td>
                                            <td><span class="label label-warning" style="background-color: '.$row->StatusColor.'">'.$row->StatusName.'</span></td>'; ?>
                                            <td class="center">
                                                <?php if(empty($row->WarehouseCheckIn)) { ?>
                                                    <a href="<?php echo base_url('Booking/warehouseCheckIn/'.$row->BookingID)?>" class="btn btn-space btn-success"><i class="icon icon-left mdi mdi-check"></i> W/H Check-In</a>
                                                <?php } else { ?>
                                                    <a href="<?php echo base_url('Booking/warehouseCheckOut/'.$row->BookingID)?>" class="btn btn-space btn-danger"><i class="icon icon-left mdi mdi-close"></i> W/H Check-Out</a>
                                                <?php } ?>
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
                </div>
            </div>
        </div>
        
        <div id="warehouseqrscan-success" class="modal-container modal-effect-11">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <div class="text-success"><span class="modal-main-icon mdi mdi-shield-check"></span></div>
            <h3>Scanned QR Code matched</h3>
            <p>Your scanned QR Code value for <span class="qrscanned_val"><b>SATS20190007</b></span>. <br>Please wait we are verifying our system...</p>
            <div class="xs-mt-20"></div>
          </div>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>

        <div id="warehouseqrscan-info" class="modal-container modal-effect-11">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="text-primary"><span class="modal-main-icon mdi mdi-info-outline"></span></div>
                        <h3>Checking QR Code</h3>
                        <p>Your scanned QR Code for checking our system. <br>Please wait we are verifying your request...</p>
                        <div class="xs-mt-20"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="warehouseqrscan-error" class="modal-container modal-effect-11">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="text-danger"><span class="modal-main-icon mdi mdi-close-circle-o"></span></div>
                        <h3>Scanned QR Code not matched</h3>
                        <p>Your scanned QR Code for not matched in our system. <br>Please try again!.</p>
                        <div class="xs-mt-20"><button type="button" data-dismiss="modal" class="btn btn-primary btn-space modal-close">Try again</button></div>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->load->view('template/footer'); ?>
        <script src="<?php echo base_url();?>assets/lib/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/js/datatable.js');?>" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/lib/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/lib/datatables/plugins/buttons/js/dataTables.buttons.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/lib/jquery.niftymodals/dist/jquery.niftymodals.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.scannerdetection.js')?>"></script>

        <script type="text/javascript">

            $.fn.niftyModal('setDefaults',{
                overlaySelector: '.modal-overlay',
                contentSelector: '.modal-content',
                closeSelector: '.modal-close',
                classAddAfterOpen: 'modal-show'
            });
            $(document).ready(function(){

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
                    "order": [], //Initial no order.
                    "bSorting": [],
                    "pageLength": 20
                });
                $('.buttons-html5').addClass('btn btn-default');

                $(document).scannerDetection({
                timeBeforeScanTest: 200, // wait for the next character for upto 200ms
                avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
                preventDefault: true,
                endChar: [13],
                onComplete: function(barcode, qty){
                validScan = true;
                qrvalue = /:(.+)/.exec(barcode)[1];
                jobno = $.trim(qrvalue);
                $('.qrscanned_val').val(jobno);
                $('#warehouseqrscan-info').niftyModal('show');
                checkQRCode(jobno);
                },
                onError: function(string, qty) {
                    $('#warehouseqrscan-success').niftyModal('hide');
                    $('#warehouseqrscan-info').niftyModal('hide');
                    $('#warehouseqrscan-error').niftyModal('hide');
                }
                });
                
                function checkQRCode(qrcode)
                {
                    $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('Booking/warehouseQRCheck/')?>'+qrcode,
                    dataType: 'JSON',
                    data: {'RefNo': qrcode},
                    success: function(data) 
                    {
                        $('#warehouseqrscan-info').niftyModal('hide');
                        if(data.error == 0)
                        {
                        $('#warehouseqrscan-success').niftyModal('show');
                        setTimeout(function(){ window.location.href = "<?php echo base_url('Booking/Verified/')?>"+qrcode+'/'+data.status;},2500);
                        } else if(data.error == 100) {
                        $('.alert-warning').hide();     
                        $('#Noticebox').show();     
                        $('#Noticemsg').html('');     
                        $('#Noticemsg').html(data.Msg);     
                        } else {
                        $('#warehouseqrscan-error').niftyModal('show');
                        }
                    },
                    error: function() {
                        $('#warehouseqrscan-info').niftyModal('hide');
                    }
                    });
                }
            });
  </script>