<div style="display:none" id="div_assign"></div>
<script src="<?= base_url('assets/js/jquery-3.3.1.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/custom.js') ?>"></script>
<!-- iCheck -->
<script src="<?= base_url('assets/js/plugins/iCheck/icheck.min.js') ?>"></script>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    $(document).ready(function() {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
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
                    window.location.href = "<?php echo base_url('Operator/Dashboard'); ?>";
                }
            });

        });
    });
</script>
<script>
    function disableThis(domid) {
        var link = domid.href;
        domid.style.display = "none";
        window.location.href = link;
        return false;
    }
</script>
<script>
    var base_url = '<?= base_url() ?>';
    var dailyLoginId = '<?= $daily_login['
    id '] ?>';
    var myVar = setInterval(checkAssignment, 1000);

    function checkAssignment() {
        if (parseInt($("#div_assign").text()) === 1) {
            window.location.href = base_url + "Operator/Dashboard";
        } else {
            $("#div_assign").load(base_url + "Operator/Dashboard/checkAssignment/" + dailyLoginId);
        }
    }

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

<input type="hidden" id="dailyId" value="">
<input type="hidden" id="status" value="">
</body>



</html>