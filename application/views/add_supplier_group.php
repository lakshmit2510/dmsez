<link href="<?php echo base_url();?>assets/css/multi-select.css" media="screen" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>assets/css/bootstrap-select.min.css" media="screen" rel="stylesheet" type="text/css">
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

</style>
<div id="supplier-group-modal" class="modal colored-header colored-header-primary fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
                <h3 class="modal-title">Assign Group To Supplier</h3>
            </div>
            <div class="modal-body" style="display: flex;align-items: center;">
                <div>
                    <select id='custom-headers' multiple='multiple'>
                            <?php
                            foreach ($Users as $key => $value) {
                                echo '<option value="' . $value->UserUID . '">' . $value->UserName . '</option>';
                            }
                            ?>
                    </select>
                </div>
                <div style="margin-left: 10px;">
                    <div>
                        <label>Select Group</label>
                        <select class="supplier-group" data-users-id="'.$row->UserUID.'" name="SupplierGroup">
                            <option value="">-- ChooseSupplierGroup --</option>
                            <?php
                                foreach ($SupplierGroup as $key => $value) {
                                    echo '<option value="' . $value->GroupID . '">' . $value->SupplierGroup . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div style="margin-top: 10px">
                        <label class="control-label">Select Available Time</label>
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
                    <div class="form-group">
                        <label class="col-sm-9 control-label">Select Dock Type</label>
                        <div class="col-sm-9">
                            <select data-parsley-trigger="keyup" name="SlotType" id="DockType">
                                <option value="">--- Choose Docks Type ----</option>
                                <?php
                                foreach ($slottype as $key => $value)
                                {
                                    if(!in_array($this->session->userdata('Role'), array(1,3))) {
                                        if($value->Type == 'Parking') { continue; }
                                    }
                                    echo '<option value="'.$value->STypeID.'">'.$value->Type.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="assign-btn" type="button" class="btn btn-primary">Assign</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/bootstrap-select.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.multi-select.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.quicksearch.js" type="text/javascript"></script>

<script>

    $('#supplier-group-modal').on('show.bs.modal', function (e) {
        $('#custom-headers').multiSelect({
            selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search Supplier'>",
            selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search Supplier'>",
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                        if (e.which === 40){
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                        if (e.which == 40){
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }
        });
        
        $('.supplier-group').change(function(){
            var groupVal = $(this).val();
            if(groupVal!==''){
                $.ajax({
                    type: 'get',
                    url: '<?php echo base_url('Users/supplierGroupById/')?>'+groupVal,
                    dataType: 'JSON',
                    success: function (data) {
                        if(data.length>0){
                            var availableTimings = data[0].AvailableTimings;
                            var docktype = data[0].DockTypeID;
                            var availableTimingsArr = availableTimings.split(',');
                            if(docktype){
                                $("#DockType").val(docktype);

                            } else {
                                $("#DockType").val('');
                            }
                            $('.time-selection li').each(function(){
                            var dataVal = $(this).attr('data-time');
                            if(availableTimingsArr.indexOf(dataVal)>-1){
                                $(this).addClass('selected');
                            }else{
                                $(this).removeClass('selected');
                            }
                        });
                        }
                        //  console.log(date);
                    },
                    error: function () {

                    }
                });
            }else{
                $('.time-selection li').each(function(){
                    $(this).removeClass('selected');
                        });
            }
            
            
        });
        $('#assign-btn').on('click',function(){
            var selectedSuppliers = $('#custom-headers').val();
            var selectedGroup = $('.supplier-group').val();
            var dockType = $('#DockType').val();

            if (selectedGroup.length == 0 && selectedSuppliers == null){
                alert('Please Select Suppliers and Supplier Group');
            }
            else if (selectedGroup.length == 0) {
                alert('Please Select the Supplier Group');

            }else if(selectedSuppliers == null) {
                alert('Please Select the Supplier to Assign Group');

            }else {
                var availableTimings = [];
                $('.time-selection .selected').each(function(){
                    availableTimings.push($(this).attr('data-time'));
                        });
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('Users/updateUsersGroup/')?>',
                    dataType: 'JSON',
                    data: {
                        selectedSuppliers: selectedSuppliers, 
                        selectedGroup: selectedGroup,
                        dockType: dockType,
                        availableTimings:availableTimings.join(','),
                        },
                    success: function (data) {
                         location.reload();
                         alert(data.message);
                    },
                    error: function () {

                    }
                });
            }
        });
       $('.time-selection li').on('click',function(){
           $(this).toggleClass('selected');
       })

    });
    
</script>