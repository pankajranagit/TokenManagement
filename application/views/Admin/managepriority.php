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
                                <label>Center Name</label>
                                <input type="text" value="<?= set_value('center_name') ?>" name="center_name" placeholder="Center Name" class="form-control" required>
                                <?php echo form_error('center_name', "<div class='text-danger'>", "</div>") ?>
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <div class="text-right">
                                <label>&nbsp;</label><br>
                                <button class="btn btn-primary" type="submit" href="#modal-form">Save Detail</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>List : Aadhaar Seva Kendra</h5>
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
                                    <th>Center Name</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr = 1;
                                foreach ($center_list as $value) {
                                    $sid = base64_encode($value['id']);
                                    if ($value['status'] == 1)
                                        $rowcolor = '#999999';
                                    else
                                        $rowcolor = '#000000';
                                    ?>
                                    <tr style="color: <?php echo $rowcolor; ?>">
                                        <td><?= $sr++ ?></td>
                                        <td><?= $value['center_name'] ?></td>
                                        <td><?= $value['state'] ?></td>
                                        <td><?= $value['city'] ?></td>
                                        <td><?= $value['center_address'] ?></td>
                                        <td>
                                            <a href="<?php echo base_url('Admin/Center/edit_center/' . $sid); ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit</a>

                                            <?php if ($value['status'] == 1) { ?>
                                                <a href="<?php echo base_url('Admin/Center/enable/' . $sid); ?>" class="btn btn-success btn-xs" onclick="return confirm('Are You Sure to Enable ?')"><i class="fa fa-cog"></i> Enable</a>
                                            <?php } else { ?>
                                                <a href="<?php echo base_url('Admin/Center/remove/' . $sid); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are You Sure to Disable ?')"><i class="fa fa-ban"></i> Disable</a>
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