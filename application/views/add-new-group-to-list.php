<?php $this->load->view('template/header'); ?>
<style>
.group-time{
    display:flex;
}
.group-time > span{
    display:block;
    width:30px;
}
.time-selection{
    border: 1px solid #cccccc;
    padding:5px;
    list-style: none;
    display: flex;
    align-content: flex-start;
    height: 150px;
    width: 220px;
    overflow: auto;
    flex-wrap: wrap;
}
.time-selection li{
    padding: 5px;
    margin-right: 5px;
    margin-bottom: 5px;
    border-radius: 10px;
    cursor: pointer;
    height: 30px;
    background-color: #cccccc;
}
.time-selection li.selected{
    background-color: #3C5A99;
    color:#ffffff;
}

.group-name-exists{
color:red;
margin-top: 15px;
}

</style>
<div class="be-content">
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <?php if($this->session->flashdata('type') == 'done') { ?>
              <div role="alert" class="alert alert-success alert-icon alert-icon-border alert-dismissible">
                <div class="icon"><span class="mdi mdi-check"></span></div>
                <div class="message">
                  <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="mdi mdi-close"></span></button><strong>Success!</strong> <?php echo $this->session->flashdata('msg'); ?>.
                </div>
              </div>
              <?php } ?> 
              <div class="panel panel-default panel-border-color panel-border-color-primary be-loading">
                <div class="panel-heading panel-heading-divider"><i class="icon mdi mdi-layers"></i> Create New Group</div>
                <div class="panel-body">
                  <form action="<?php echo base_url('Users/saveGroup');?>" class="form-horizontal" method="post">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Group Name</label>
                      <div class="col-sm-6">
                        <input type="text"  required="true" placeholder="Group Name" name="SupplierGroup" class="form-control">
                      </div>
                      <div class="group-name-exists" style="display:none;">Group already exists.</div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Select Time</label>
                      <div class="col-sm-6">
                      <div style="margin-top: 10px">
                        <ul class="time-selection">
                        <?php
                        $i = 0;
                        while ($i <= 24) {
                            if($i<10){
                                echo '<li data-time="'.$i.'">0'.$i.':00</li>';
                            }else{
                                echo '<li data-time="'.$i.'">'.$i.':00</li>';
                            }
                            
                            $i++;
                        }
                        ?>
                        
                        </ul>
                      </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Dock Type</label>
                      <div class="col-sm-6">
                        <select class="form-control" name="SlotType" id="SlotType">
                          <option value="">--- Choose Dock Type ----</option>
                          <?php 
                          foreach ($slottype as $key => $value) {
                            echo '<option value="'.$value->STypeID.'">'.$value->Type.'</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-3"></div>
                      <div class="col-sm-6 m-2">
                        <button type="submit" class="btn btn-space btn-primary">Submit</button>
                        <a href="<?php echo base_url('Users/supplierGroupDetails');?>" class="btn btn-space btn-default">Cancel</a>
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
    <script type="text/javascript">
      $(document).ready(function(){ 
        $('[name="SupplierGroup"]').on('keyup',function(){
          var groupName = $(this).val();
          if(groupName !== ''){
            $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: "<?php echo base_url();?>Users/validateGroupName/" + groupName,
            success:function(res){
              if(res.isExists){
                $('.group-name-exists').show();
                $('[type="submit"]').prop('disabled', true);
              } else {
                $('.group-name-exists').hide();
                $('[type="submit"]').prop('disabled', false);
              }
            }
          });
          }
          
        });

        $('[type="submit"]').on('click',function(evt){
          evt.preventDefault();
          var groupName = $('[name="SupplierGroup"]').val();
          var availableTimings = [];

          $('.time-selection .selected').each(function(){
            availableTimings.push($(this).attr('data-time'));
          });

          var dockType = $('[name="SlotType"]').val();
          $.ajax({
            type: 'POST',
            url: '<?php echo base_url('Users/saveGroup')?>',
            dataType: 'JSON',
            data: { 
                supplierGroup: groupName,
                dockType: dockType,
                availableTimings:availableTimings.join(','),
                },
            success: function (data) {
                if(data.isSaved){
                  window.location = "<?php echo base_url();?>Users/supplierGroupDetails"
                }
            },
            error: function () {

            }
          });

        });

        $('form').parsley();

        $('select').select2();

        $('.time-selection li').on('click',function(){
           $(this).toggleClass('selected');
        })
      });
    </script>