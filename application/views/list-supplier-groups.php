<?php $this->load->view('template/header'); ?>

<div class="be-content">
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-border-color panel-border-color-primary">
                <div class="panel-heading panel-heading-divider"><?PHP echo $Title;?></div>
                <div class="panel-body">
                  <div class="text-right">
                    <!-- <a href="<?php echo base_url('Users/')?>" class="btn btn-space btn-success"> Add Record</a> -->
                  </div>
                  <table id="table3" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th>GroupId</th>
                        <th>Supplier Group</th>
                        <th>Available Timings</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if($suppliergrouplist!=0)
                      {
                        $i=1;
                        foreach($suppliergrouplist as $row)
                        {
                          echo '<tr>
                            <td>'.$row->GroupID.'</td>
                            <td>'.$row->SupplierGroup.'</td>
                            <td>'.$row->AvailableTimings.'</td>
                            <td class="center">
                            <a href="'.base_url('Users/editSupplierGroup/'.$row->GroupID).'" class="btn btn-space btn-warning"><i class="icon icon-left mdi mdi-edit"></i> Edit</a>
                            </td>';
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
    <?php $this->load->view('template/footer'); ?>
    <script src="<?php echo base_url();?>assets/lib/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/datatable.js');?>" type="text/javascript"></script>
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
              columns: ['th:not(:last-child)']
            }
          },"pdf"],
          lengthMenu:[[10,25,50,-1],[6,10,25,50,"All"]],
          dom:"Bfrtip"
        });

        $('.buttons-html5').addClass('btn btn-default');
      });
    </script>