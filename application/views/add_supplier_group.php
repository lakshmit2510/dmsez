<link href="<?php echo base_url();?>assets/css/multi-select.css" media="screen" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>assets/css/bootstrap-select.min.css" media="screen" rel="stylesheet" type="text/css">

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
                    <select class="supplier-group" data-users-id="'.$row->UserUID.'" name="SupplierGroup">
                        <option value="">-- ChooseSupplierGroup --</option>
                        <?php
                            foreach ($SupplierGroup as $key => $value) {
                                echo '<option value="' . $value->GroupID . '">' . $value->SupplierGroup . '</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
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
        $('#assign-btn').on('click',function(){
            var selectedSuppliers = $('#custom-headers').val();
            var selectedGroup = $('.supplier-group').val();

            if (selectedGroup.length == 0 && selectedSuppliers == null){
                alert('Please Select Suppliers and Supplier Group');
            }
            else if (selectedGroup.length == 0) {
                alert('Please Select the Supplier Group');

            }else if(selectedSuppliers == null) {
                alert('Please Select the Supplier to Assign Group');

            }else {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('Users/updateUsersGroup/')?>',
                    dataType: 'JSON',
                    data: {selectedSuppliers: selectedSuppliers, selectedGroup: selectedGroup},
                    success: function (data) {
                         location.reload();
                         alert(data.message);
                    },
                    error: function () {

                    }
                });
            }
        });

    });

</script>