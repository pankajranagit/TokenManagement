<!-- <div class="row  border-bottom white-bg dashboard-header">
    <div class="col-md-12">
        <h2>Manage Center - Coming Soon !</h2>
    </div>
</div> -->
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Update Aadhaar Seva Kendra</h5>
                    <div class="ibox-tools">
                        <?php echo $this->session->flashdata('msg') ?>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- <form method="post" action=""> -->
                    <?php echo form_open(''); ?>
                    <input type="hidden" value="<?php echo $centerdata->id ?>" name="cid">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Center Name</label>
                                <input type="text" value="<?php echo $centerdata->center_name ?>" name="center_name" placeholder="Center Name" class="form-control">
                                <?php echo form_error('center_name', "<div class='text-danger'>", "</div>") ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Select State</label>
                                <select class="form-control" name="state" readonly>
                                    <option><?php echo $centerdata->state ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Select City</label>
                                <select class="form-control" name="city" readonly>
                                    <option><?php echo $centerdata->city ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Center Address</label>
                                <input type="text" value="<?php echo $centerdata->center_address ?>" name="center_address" placeholder="Center Address" class="form-control">
                                <?php echo form_error('center_address', "<div class='text-danger'>", "</div>") ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-right">
                                <label>&nbsp;</label><br>
                                <button class="btn btn-primary" type="submit" href="#modal-form">Update Detail</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>