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
                    <h5>Manage Priority</h5>
                    <div class="ibox-tools">
                        <?php echo $this->session->flashdata('msg') ?>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- <form method="post" action="<?= base_url('Admin/Center/') ?>"> -->
                    <?php echo form_open(base_url('Admin/Center/')); ?>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Select Center</label>
                                <select class="form-control" name="state" required id="getpropertyinfo">


                                    <option value="">Select Center</option>

                                    <?php if (!empty($center_list)) {
                                        foreach ($center_list as $stlist) { ?>

                                            <option value="<?php echo $stlist->id; ?>"><?php echo $stlist->center_name; ?></option>

                                        <?php }
                                } ?>


                                </select>
                                <?php echo form_error('state', "<div class='text-danger'>", "</div>") ?>
                            </div>
                        </div>

                    </div>

                    </form>



                    <div id="centerinfo"></div>

                </div>
            </div>
        </div>
    </div>

</div>