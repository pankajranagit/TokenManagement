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
                    <h5>Add Priority</h5>
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
                                <label>Priority Type</label>
                                <input type="text" required placeholder="Priority Type" name="priority_type" class="form-control" value="<?= set_value('priority_type') ?>">
                                <?php echo form_error('priority_type', "<div class='text-danger'>", "</div>") ?>
                            </div>
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
                                    <th>Priority Type</th>
                                    <th>Priority</th>
                                    <th>Rule <small>(Next after the number of applicant)</small></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr = 1;
                                $sl_count = count($operator_list);
                                foreach ($operator_list as $value) {
                                    $sid = base64_encode($value->id);
                                    if ($value->status == 1)
                                        $rowcolor = '#999999';
                                    else
                                        $rowcolor = '#000000';
                                    ?>
                                    <tr style="color: <?php echo $rowcolor; ?>">
                                        <td><?= $sr++ ?></td>
                                        <td><?= $value->priority_type ?></td>
                                        <td>
                                            <select class="form-control set_priority" name="set_priority" slider-id="<?php echo $value->id; ?>" center_id="<?php echo $value->center_id; ?>">
                                                <option value="">Select Priority</option>
                                                <?php for ($x = 1; $x <= $sl_count; $x++) { ?>
                                                    <option <?php if ($value->priority == $x) echo "selected"; ?> value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td><?= $this->customlib->token_lable($value->operator_type) ?></td>
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