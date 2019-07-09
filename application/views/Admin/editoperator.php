<!-- <div class="row  border-bottom white-bg dashboard-header">
    <div class="col-md-12">
        <h2>Manage Center - Coming Soon !</h2>
    </div>
</div> -->
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <b><?php echo $cebter_data->center_name . " | " . $cebter_data->city . " | " . $cebter_data->state . " | " . $cebter_data->center_address; ?></b>
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Edit Operator</h5>
                    <div class="ibox-tools">
                        <?php echo $this->session->flashdata('msg') ?>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- <form method="post" action="<?= current_url() ?>"> -->
                    <?php echo form_open(current_url()); ?>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Operator Name</label>
                                <input type="text" required placeholder="Operator Name" name="operator_name" class="form-control" value="<?= $op_data->operator_name ?>">
                                <?php echo form_error('operator_name', "<div class='text-danger'>", "</div>") ?>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="text" required placeholder="Mobile Number" name="operator_number" class="form-control" value="<?= $op_data->operator_number ?>">
                                <?php echo form_error('operator_number', "<div class='text-danger'>", "</div>") ?>
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Operator Type</label>
                                <select class="form-control" name="operator_type" required>
                                    <option value="">Select Operator Type</option>


                                    <?php if (!empty($opt_typ)) {
                                        foreach ($opt_typ as $stlist) { ?>

                                            <option <?php if ($op_data->operator_type == $stlist) echo "selected"; ?> value="<?php echo $stlist ?>"><?php echo $this->customlib->token_lable($stlist); ?></option>

                                        <?php }
                                } ?>



                                </select>
                                <?php echo form_error('operator_type', "<div class='text-danger'>", "</div>") ?>
                            </div>
                        </div>





                        <div class="col-sm-1">
                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <input type="submit" value="Update" class="btn btn-primary">
                            </div>
                        </div>



                    </div>

                    </form>





                </div>
            </div>
        </div>
    </div>




</div>