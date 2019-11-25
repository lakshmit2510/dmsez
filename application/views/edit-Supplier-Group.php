<?php $this->load->view('template/header'); ?>

<div class="be-content">
  <div class="main-content container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary be-loading">
          <div class="panel-heading panel-heading-divider"><i class="icon mdi mdi-car"></i> <?php echo $Title;?></div>
          <div class="panel-body">
            <form action="<?php echo base_url('Users/updateSupplierGroupTime');?>" class="form-horizontal" method="post">
                <!-- <input type="hidden" value="<?php //echo $suppliergrouplist->GroupID ?>"   name="group_Id"> -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Supplier Group</label>
                <div class="col-sm-6">
                  <input type="text" readonly="true" required="true" value="" name="SupplierGroup" placeholder="Supplier Group" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Available Timings</label>
                <div class="col-sm-6">
                  <input type="text" required="" name="availableTimings" value="" placeholder="Available timings" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Dock Type</label>
                <div class="col-sm-9">
                  <select data-parsley-trigger="keyup" name="dockType" id="DockType">
                    <option value="">--- Choose Docks Type ----</option>
                    <?php
                    foreach ($slottype as $key => $value)
                    {
                        if(!in_array($this->session->userdata('Role'), array(1,3))) {
                            if($value->Type == 'Parking') { continue; }
                        }if($suppliergrouplist!=0){
                          if($suppliergrouplist->DockTypeID===$value->STypeID){
                            echo '<option selected value="'.$value->STypeID.'">'.$value->Type.'</option>';
                          }
                        }else{
                          echo '<option value="'.$value->STypeID.'">'.$value->Type.'</option>';
                        }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-space btn-primary">Submit</button>
                  <a href="<?php echo base_url('Users/supplierGroupDetails');?>" class="btn btn-space btn-default">Cancel</a>
                </div>
              </div>
            </form>
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