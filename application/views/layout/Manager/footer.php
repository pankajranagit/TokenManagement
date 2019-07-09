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

<input type="hidden" id="dailyId" value="">
<input type="hidden" id="status" value="">

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
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

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


        $("#confirmStatus").click(function() {
            var dailyId = $('#dailyId').val();
            var status = $('#status').val();

            var dataJson = {
                [csrfName]: csrfHash,
                dailyId: dailyId,
                status: status
            };

            $('#confirm').modal('hide');

            $.ajax({
                url: "<?php echo base_url('Manager/ManageOperator/changeStatusOperator'); ?>",
                type: 'post',
                data: dataJson,
                success: function(data) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    console.log(data);
                    window.location.href = "<?php echo base_url('Manager/ManageOperator'); ?>";
                }
            });

        });

    });

    function change_status(dailyId, status) {
        // console.log(dailyId+" == "+status);
        $('#dailyId').val(dailyId);
        $('#status').val(status);

        if (status === 'NULL') {
            $('#confirm-info').html('Counter is now active for serving token');
        } else {
            $('#confirm-info').html('Counter is now disable ?<br> <b>Operator Status :</b> ' + status);
        }

        $('#confirm').modal({
            backdrop: 'static',
            keyboard: false
        })
    }
</script>
</body>

</html>