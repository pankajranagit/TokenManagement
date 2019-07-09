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
                    <h5>Add Operator</h5>
                    <div class="ibox-tools">
                        <?php echo $this->session->flashdata('msg') ?>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- <form method="post" action="<?= current_url() ?>"> -->
                    <?php
                    //$attributes = array('class' => 'email', 'id' => 'myform');
                    echo form_open(current_url());
                    ?>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Operator Name</label>
                                <input type="text" required placeholder="Operator Name" name="operator_name" class="form-control" value="<?= set_value('operator_name') ?>">
                                <?php echo form_error('operator_name', "<div class='text-danger'>", "</div>") ?>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="text" required placeholder="Mobile Number" name="operator_number" class="form-control" value="<?= set_value('operator_number') ?>">
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

                                            <option <?php echo set_select('operator_type', '<?php echo $stlist ?>') ?> value="<?php echo $stlist ?>"><?php echo $this->customlib->token_lable($stlist); ?></option>

                                        <?php }
                                } ?>


                                </select>
                                <?php echo form_error('operator_type', "<div class='text-danger'>", "</div>") ?>
                            </div>
                        </div>



                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Operator Email ID</label>
                                <input type="email" required placeholder="Operator Email ID" name="operator_email" class="form-control" value="<?= set_value('operator_email') ?>">
                            </div>
                            <?php echo form_error('operator_email', "<div class='text-danger'>", "</div>") ?>
                        </div>

                        <div class="col-sm-1">
                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <input type="submit" value="ADD" class="btn btn-primary">
                            </div>
                        </div>



                    </div>

                    </form>



                    <div id="centerinfo"></div>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>List : Operator</h5>
                    <div class="ibox-tools">
                        <?php echo $this->session->flashdata('msg') ?>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Operator Name</th>
                                    <th>Mobile Number</th>
                                    <th>Type</th>
                                    <th>Email ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr = 1;
                                foreach ($operator_list as $value) {
                                    $sid = base64_encode($value->id);
                                    if ($value->status == 1)
                                        $rowcolor = '#999999';
                                    else
                                        $rowcolor = '#000000';
                                    ?>
                                    <tr style="color: <?php echo $rowcolor; ?>">
                                        <td><?= $sr++ ?></td>
                                        <td><?= $value->operator_name ?></td>
                                        <td><?= $value->operator_number ?></td>
                                        <td><?= $this->customlib->token_lable($value->operator_type) ?></td>
                                        <td><?= $value->operator_email ?></td>
                                        <td>
                                            <a href="<?php echo base_url('Admin/ManageOperator/edit_operator/' . $sid); ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit</a>

                                            <?php if ($value->status == 1) { ?>
                                                <a href="<?php echo base_url('Admin/ManageOperator/enable/' . $sid); ?>" class="btn btn-success btn-xs" onclick="return confirm('Are You Sure to Enable ?')"><i class="fa fa-cog"></i> Enable</a>
                                            <?php } else { ?>
                                                <a href="<?php echo base_url('Admin/ManageOperator/remove/' . $sid); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are You Sure to Disable ?')"><i class="fa fa-ban"></i> Disable</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>