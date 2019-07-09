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
        <div class="col-lg-4">
            <div class="ibox ">
                <div class="ibox-title">
                    <span class="label label-success float-right">Active</span>
                    <h5>Total Operator</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                        <?php
                        if (!empty($operator_list))
                            echo count($operator_list);
                        else
                            echo "0";
                        ?>
                    </h1>
                    <small>Number of all Operator</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox ">
                <div class="ibox-title">
                    <span class="label label-danger float-right">Lifetime</span>
                    <h5>Total Token Issued</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?= count($total_applicant) ?></h1>
                    <small>Token Issued Till Now</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox ">
                <div class="ibox-title">
                    <span class="label label-primary float-right">Today</span>
                    <h5>Token In-Queue</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?= $tokenInQueue ?></h1>
                    <small>Token In-Queue Today</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Time taken by each token on each counter</h5>
                    <div class="ibox-tools">
                        <table>
                            <tr>
                                <td>
                                    <h5 style="margin: 0">Showing Data for the Date : &nbsp;</h5>
                                </td>
                                <td><input min="2019-06-15" max="<?= date('Y-m-d') ?>" type="date" onchange="change_date(this.value)" value="<?= date('Y-m-d', strtotime($currDate)) ?>" class="form-control"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered dataTables-example">
                            <thead>
                                <tr>
                                    <th class="text-center">Token</th>
                                    <th class="text-center">Application Number</th>
                                    <th>Operator Username</th>
                                    <th>Operator Contact</th>
                                    <th>Operator Type</th>
                                    <th>In-Time</th>
                                    <th>Out-Time</th>
                                    <th>Serve Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $prevToken = 0;
                                $style_grey = "style='background-color: rgba(0,188,212,0.5)'";
                                $style_white = "style='background-color: rgba(0,0,0,0.0)'";
                                foreach ($tokenLogging as $value) {
                                    if ($prevToken != $value['token_number']) {
                                        if ($css == $style_white)
                                            $css = $style_grey;
                                        else
                                            $css = $style_white;
                                        $prevToken = $value['token_number'];
                                    }
                                    ?>
                                    <tr <?= $css ?>>
                                        <td class="text-center"><?= $value['token_number'] ?></td>
                                        <td class="text-center"><?= $value['application_id'] ?></td>
                                        <td><?= $value['operator_name'] ?> (<?= $value['username'] ?>)</td>
                                        <td>+91 <?= $value['operator_number'] ?></td>
                                        <td><?= $value['operator_type'] ?></td>
                                        <td><?= date('H:i:s A', strtotime($value['intime'])) ?></td>
                                        <td>
                                            <?php
                                            if ($value['outtime'] !== '0000-00-00 00:00:00')
                                                echo date('H:i:s A', strtotime($value['outtime']));
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $datetime1 = date_create($value['intime']);
                                            $datetime2 = date_create($value['outtime']);

                                            $interval = date_diff($datetime1, $datetime2);
                                            if ($value['outtime'] !== '0000-00-00 00:00:00')
                                                echo $interval->format('%i Min %s Sec');
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var base_url = '<?= base_url() ?>';

    function change_date(val) {
        val = Math.round(new Date(val).getTime() / 1000);
        var url = base_url + "Manager/Dashboard/index/" + val;
        // console.log('urlurl = ' + url);
        window.location.href = url;

    }
</script>