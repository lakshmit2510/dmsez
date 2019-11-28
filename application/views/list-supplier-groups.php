<?php $this->load->view('template/header'); ?>

<div class="be-content">
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-border-color panel-border-color-primary">
                <div class="panel-heading panel-heading-divider"><?PHP echo $Title;?></div>
                <div class="panel-body">
                  <div class="text-right">
                    <a href="<?php echo base_url('Users/addNewSupplierGroup')?>" class="btn btn-space btn-success"> Add Record</a>
                  </div>
                  <table id="table3" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th>GroupId</th>
                        <th>Supplier Group</th>
                        <th>Available Timings</th>
                        <th>Dock Type</th>
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
                            <td>'.$row->Type.'</td>
                            <td class="center">
                            <a href="'.base_url('Users/editSupplierGroup/'.$row->GroupID).'" class="btn btn-space btn-warning"><i class="icon icon-left mdi mdi-edit"></i> Edit</a>
                            <a href="javascript:void(0);" class="btn btn-space btn-danger delete-group" data-userId="'.$row->GroupID.'"><i class="icon icon-left mdi mdi-delete"></i> Delete</a>
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
    <script src="<?php echo base_url()?>assets/lib/notify/notify.min.js" type="text/javascript"></script>

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

        $('.delete-group').on('click',function(){
          var table3 = $('#table3').DataTable();

          $.ajax ({
            type:'POST',
            url:'<?php echo base_url();?>Users/deleteGroup/'+$(this).attr('data-userId'),
            dataType: 'JSON',
            data: {},
            beforeSend: function() {
                // $('.be-loading').addClass('be-loading be-loading-active');

            },
            success:function(data) {
              if (data.success) {
                table3.row($(this).parents('tr')[0]).remove().draw(false);
                
                $.notify({
                    message: data.message,
                }, {
                    element: 'body',
                    type: "success",
                    placement: {
                      from: "top",
                      align: "right"
                    }
                  });
                  
              }
              location.reload();
            }
          });
        });
      });
    </script>