<div class="footer">
    <div class="float-right">
        <?= $this->lang->line('organisation'); ?> | <?= $this->lang->line('project_name'); ?>
    </div>
    <div>
        <strong>Copyright</strong> <?= $this->lang->line('company'); ?> &copy; <?= $this->lang->line('copyright_year'); ?>
    </div>
</div>
</div>
</div>

<script src="<?= base_url('assets/js/jquery-3.1.1.min.js') ?>"></script>
<script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/metisMenu/jquery.metisMenu.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/slimscroll/jquery.slimscroll.min.js') ?>"></script>

<!-- Custom and plugin javascript -->
<script src="<?= base_url('assets/js/inspinia.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/pace/pace.min.js') ?>"></script>

<!-- jQuery UI -->
<script src="<?= base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>

<script src="<?= base_url('assets/js/plugins/dataTables/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/dataTables/dataTables.bootstrap4.min.js') ?>"></script>

<script src="<?= base_url('assets/js/custom.js') ?>"></script>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function() {
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [{
                    extend: 'copy'
                },
                {
                    extend: 'csv'
                },
                {
                    extend: 'excel',
                    title: 'ASK_TMS_DATA'
                },
                {
                    extend: 'pdf',
                    title: 'ASK_TMS_DATA'
                },

                {
                    extend: 'print',
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]

        });



 var x = 2; 

        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = '<div class="row"><div class="col-sm-5"><br><label>Step '+x+'</label><select class="form-control col-sm-9" name="operator_type[]" required><option value="">Select Operator Type</option><?php if(!empty($opt_typ)){ foreach ($opt_typ as $stlist) { ?><option <?php echo set_select('operator_type', '<?php echo $stlist ?>') ?> value="<?php echo $stlist ?>"><?php echo $this->customlib->token_lable($stlist); ?></option><?php } } ?></select><a href="javascript:void(0);" class="pull-right remove_button btn btn-danger" style="margin-top:-33px; ma">Remove</a></div></div>'; //New input field html 
       //Initial field counter is 1
        
        //Once add button is clicked
        $(addButton).click(function(){
           
            //Check maximum number of input fields
            
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            
        });
        
        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });


    });
</script>
</body>

</html>