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
                    <h5>Manage Services</h5>
                    <div class="ibox-tools">
                        <?php echo $this->session->flashdata('msg') ?>
                    </div>
                </div>
                <div class="ibox-content">
                    <?php echo form_open(current_url()); ?>
                    <div class="row">
                        <div id="centerinfo" class="col-sm-6">
                            <div class="form-group">
                                <label>Select Label</label>
                                <select class="form-control" id="labeloneselect" onchange="getchildinfo(this.value,1)" name="lable[]" required>
                                    <option value="">Select</option>
                                    <?php

                                    foreach ($helpdesk_list as $value) { ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['lable']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <input type="button" value="Define Workflow" onclick="defineworkflow()" class="btn btn-success" name="">
                            </div>
                        </div>
                        <div class="col-sm-3 text-right">
                            <label>&nbsp;</label><br>
                            <a href="<?php echo current_url(); ?>" class="btn btn-danger">Reset</a>
                        </div>
                    </div>
                    <div id="field_wrapper" style="display: none;">
                        <div class="field_wrapper">
                            <div class="row">
                                <div class="col-sm-5">
                                    <label>Step 1 </label>
                                    <select class="form-control col-sm-9" name="operator_type[]" required>
                                        <option value="">Select Operator Type</option>
                                        <?php if (!empty($opt_typ)) {
                                                  foreach ($opt_typ as $stlist) { ?>

                                                <option <?php echo set_select('operator_type', '<?php echo $stlist ?>') ?> value="<?php echo $stlist ?>"><?php echo $this->customlib->token_lable($stlist); ?></option>

                                            <?php }
                                              } ?>


                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-sm-7">
                                <a href="javascript:void(0);" class="add_button btn btn-primary" title="Add field">Add More</a>
                                <input type="submit" name="" class="btn btn-success" value="Finish">
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>List : Services</h5>
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
                                    <th>Applicant Selection</th>
                                    <th>Counter</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr = 1;
                                foreach ($services as $value) {
                                    $sid = base64_encode($value->id);

                                    ?>
                                    <tr>
                                        <td><?= $sr++ ?></td>
                                        <td><?php
                                            $resultstr = array();
                                            $tags = explode(',', $value->applicant_type);

                                            foreach ($tags as $key) {
                                                $category = get_Details('helpdesk', array('id' => $key), 'single');
                                                $resultstr[] = $category->lable;
                                            }
                                            echo implode(" => ", $resultstr);


                                            ?></td>
                                        <td><?= str_replace(',', ' => ', $value->counter) ?></td>
                                        <td><a href="<?php echo base_url('Admin/ManageServices/deleteservices/' . base64_encode($value->center_id) . '/' . base64_encode($value->id)); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are You Sure to Delete ?')">Delete</a></td>
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

<script type="text/javascript">
    function getchildinfo(str, id) {
        var catg_id = str;
        var id = id;
        $.ajax({
            type: "POST",
            url: base_url + "Admin/ManageServices/getchildinfo",
            data: {
                'catg_id': catg_id,
                'id': id
            },
            success: function(response) {
                if (response == "NORECORD") {

                } else {
                    $('#centerinfo').html(response);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function defineworkflow() {
        if (!$('#labeloneselect').val()) {
            alert("Please Select Label");
        } else {
            $("#field_wrapper").show();
        }
    }
</script>