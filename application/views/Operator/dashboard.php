<div class="container p-4">
    <div class="row">
        <div class="col-lg-4">
            <table class="table table-bordered table-sm" style="font-size: .9em;">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">Login Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Full Name</th>
                        <td><?= $operator['operator_name'] ?></td>
                    </tr>
                    <tr>
                        <th>Email-Id</th>
                        <td><?= $operator['operator_email'] ?></td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td><?= $operator['operator_number'] ?></td>
                    </tr>
                    <tr>
                        <th>Counter Lable</th>
                        <td>
                            <?php
                            if ($daily_login['counter_lable'] != NULL) {
                                echo "<b>" . $daily_login['counter_lable'] . "</b>";
                            } else {
                                echo form_open(base_url('Operator/Dashboard/setCounterNumber')); ?>
                                <div>
                                    <input type="hidden" value="<?= $daily_login['id'] ?>" name="dailyloginid">
                                    <input name="counter_lable" required type="text" style="padding: 2px; text-align: center; width: 100px">
                                    <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-save"></i></button>
                                </div>
                                <div>
                                    <label class="small text-danger"><?php echo form_error('counter_lable'); ?></label>
                                </div>
                                </form>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="statistics">
                <h1 style="font-weight: bold"><?php echo ($statistic['queue']) ?></h1>
                <p>Number of applicant in queue</p>
                <hr style="border: 2px solid #000046">
                <h1 style="font-weight: bold"><?php echo ($statistic['app_served'] == 0 ? 0 : $statistic['app_served'] - 1) ?></h1>
                <p>Number of Token Served</p>
            </div>
        </div>
        <div class="col-lg-8">
            <h4 style="font-weight: bold">Current Applicant</h4>
            <table class="table table-condensed" style="border-top: 4px solid #000046">
                <tr>
                    <th style="width: 30%">Token Number</th>
                    <td><?= $daily_login['token_number'] ?></td>
                </tr>
                <?php
                //if ($user['operator_type'] == 'PD' && $daily_login['application_id'] == NULL && $daily_login['counter_lable'] != NULL && $daily_login['curr_tokenid'] != NULL)
                if ($daily_login['application_id'] == NULL && $daily_login['counter_lable'] != NULL && $daily_login['curr_tokenid'] != NULL) { ?>
                    <tr>
                        <th>Applicant Number</th>
                        <td>
                            <!-- <form method="post" action="<?= base_url('Operator/Dashboard/setApplicatinId') ?>"> -->
                            <?php
                            //$attributes = array('class' => 'email', 'id' => 'myform');
                            echo form_open(base_url('Operator/Dashboard/setApplicatinId'));
                            ?>
                            <div>
                                <input type="hidden" value="<?= $daily_login['curr_tokenid'] ?>" name="curr_tokenid">
                                <input name="application_id" type="text" style="padding: 2px; text-align: center; width: 200px" required>
                                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-save"></i></button> or
                                <button class="btn btn-xs btn-dark"><i class="fa fa-qrcode"></i></button>
                            </div>
                            <div>
                                <label class="small text-danger"><?php echo form_error('application_id'); ?></label>
                            </div>
                            </form>
                        </td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <th>Applicant Number</th>
                        <td><?= $daily_login['application_id'] ?></td>
                    </tr>
                <?php
                } ?>
                <tr>
                    <th>Applicant Type</th>
                    <td>
                        <?php
                        $tags = explode(',', $daily_login['applicant_type']);
                        $resultstr = [];

                        foreach ($tags as $key) {
                            $category = get_Details('helpdesk', array('id' => $key), 'single');
                            $resultstr[] = $category->lable;
                        }

                        echo implode(" <i class='fa fa-arrow-right'></i> ", $resultstr);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Priority Type</th>
                    <td>
                        <?php
                        if ($daily_login['curr_tokenid'] != NULL)
                            echo "None";
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Remark</th>
                    <td><?= $remark ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
            </table>

            <div class="row">
                <?php
                if ($daily_login['counter_lable'] != NULL && $daily_login['application_id'] != NULL && $daily_login['curr_tokenid'] != NULL) {
                    ?>
                    <div class="col-lg-4"><button class="btn btn-warning btn-lg btn-block circle" data-toggle="modal" data-target="#reAssign">Reassign</button></div>

                    <div class="col-lg-4"><button class="btn btn-danger btn-lg btn-block circle" data-toggle="modal" data-target="#cancelToken">Reject Token</button></div>

                    <div class="col-lg-4">
                        <a onclick="return disableThis(this)" href="<?= base_url('Operator/Dashboard/callNextToken/' . $daily_login['curr_tokenid'] . '/' . $daily_login[id]); ?>" class="btn btn-success btn-lg btn-block circle">Next Token</a>
                    </div>
                <?php
                } else {
                    ?>
                    <!-- <div class="col-lg-4"><button disabled class="btn btn-warning btn-lg btn-block circle">Park Current Token</button></div> -->
                    <div class="col-lg-4"><button disabled class="btn btn-warning btn-lg btn-block circle">Reassign</button></div>
                    <div class="col-lg-4"><button disabled class="btn btn-danger btn-lg btn-block circle">Reject Token</button></div>
                    <div class="col-lg-4"><button disabled class="btn btn-success btn-lg btn-block circle">Next Token</button></div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!--- Re Assign token starts-->
<div class="modal inmodal" id="reAssign" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <!-- <form method="post" action="<?= base_url('Operator/Dashboard/Reassign') ?>"> -->
            <?php echo form_open(base_url('Operator/Dashboard/Reassign')); ?>
            <input type="hidden" name="curr_tokenid" value="<?= $daily_login['curr_tokenid'] ?>">
            <input type="hidden" value="<?= $daily_login['id'] ?>" name="dailyloginid">
            <div class="modal-body">
                <h5 class="text-center">Re-Assign : Select any previous counter</h5>
                <hr>
                <?php
                $opList = explode(',', $daily_login['op_list']);
                $opStatus = explode(',', $daily_login['op_status']);
                //print_r($daily_login);
                //print_r($opStatus);
                $operator_type = $daily_login['operator_type'];
                ?>
                <table>
                    <tr>
                        <?php
                        $opTxt = '';
                        foreach ($opList as $value) {
                            if ($operator_type == $value)
                                break;
                            echo "<td style='padding-right:40px; cursor: pointer'><div class='i-checks'><label> <input required type='radio' name='new_opstatus' value='" . $opTxt . "'> &nbsp;&nbsp;" . $value . " </label></div></td>";
                            $opArr[] = 1;
                            $opTxt = implode(',', $opArr);
                        }
                        ?>
                    </tr>
                </table>
                <hr>
                <label>Remark (Optional)</label>
                <textarea class="form-control" name="remark" placeholder="Type Any Remark"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Reassign</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!--- Re Assign token ends -->

<!--- Cancel Token Starts -->
<div class="modal inmodal" id="cancelToken" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <?php echo form_open(base_url('Operator/Dashboard/rejectoken')); ?>
            <input type="hidden" name="curr_tokenid" value="<?= $daily_login['curr_tokenid'] ?>">
            <input type="hidden" value="<?= $daily_login['id'] ?>" name="dailyloginid">
            <div class="modal-body">
                <h5 class="text-center text-danger">Reject the token</h5>
                <label>Reason of rejecting the token</label>
                <textarea class="form-control" required name="reject_reason" placeholder="Type Reason Here.."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
<!--- Cancel Token Ends -->

<div class="modal inmodal text-left" id="confirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog text-left">
        <div class="modal-content animated fadeIn">
            <div class="modal-body text-danger text-center">
                <br>
                <h5 id="confirm-info" class="modal-title">Confirm Status Change ?</h5>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" id="confirmStatus" class="btn btn-danger">Confirm</button>
            </div>
        </div>
    </div>
</div>