<style>
    .ibox-tools {
        display: block;
        float: none;
        margin-top: 0;
        position: absolute;
        top: 5px !important;
        right: 15px;
        padding: 0;
        text-align: right;
    }
</style>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>List of Login Operators</h5>
                    <div class="ibox-tools">
                        <table>
                            <tr>
                                <td>
                                    <h5 style="margin: 0">Selected Date : &nbsp;</h5>
                                </td>
                                <td><input min="2019-06-15" max="<?= date('Y-m-d') ?>" type="date" onchange="change_date(this.value)" value="<?= date('Y-m-d', strtotime($currDate)) ?>" class="form-control"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Operator Detail</th>
                                    <th>Type</th>
                                    <th>Counter Lable</th>
                                    <th>Token Serving</th>
                                    <th>Change Status</th>
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
                                        <td>
                                            <?= $value->operator_name ?> (<?= $value->username ?>)<br>
                                            <?= $value->operator_number ?><br>
                                            <?= ($value->create_on ? "<p class='text-danger'>Login On : " . date('h:i:s A', strtotime($value->create_on)) . "</p>" : '') ?>
                                        </td>
                                        <td><?= $this->customlib->token_lable($value->operator_type) ?></td>
                                        <td><?= $value->counter_lable ?></td>
                                        <td><?= $value->token_number ?></td>
                                        <td>
                                            <?php
                                            if ($value->dailyId) {
                                                ?>
                                                <select class="form-control" onchange="change_status('<?= $value->dailyId ?>', this.value)">
                                                    <option value="NULL">Active</option>
                                                    <?php
                                                    $curr_status = $this->customlib->curr_status();
                                                    foreach ($curr_status as $key => $val) {
                                                        if ($value->curr_status == $key) {
                                                            echo "<option selected value='" . $key . "'>" . $val . "</option>";
                                                        } else {
                                                            echo "<option value='" . $key . "'>" . $val . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            <?php
                                            } else {
                                                echo "<h4 class='text-danger text-center'>No Login !</h4>";
                                            }
                                            ?>

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

<div class="modal inmodal" id="confirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header text-danger">
                <i class="text-danger fa fa-question-circle modal-icon"></i>
                <h5 id="confirm-info" class="modal-title">Confirm Status Change ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" id="confirmStatus" class="btn btn-danger">Confirm</button>
            </div>
        </div>
    </div>
</div>