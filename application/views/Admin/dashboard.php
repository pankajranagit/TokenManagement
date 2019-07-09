<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Total Center</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo count($center_list); ?></h1>
                    <small>Number of all center</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <span class="label label-info float-right">Daily</span>
                    <h5>Average Applicant</h5>
                </div>
                <div class="ibox-content">
                    <?php $total_applicants = count($total_applicant);
                    $no_days = array_unique(array_column($total_applicant, 'curr_date'));
                    $no_days = count($no_days);
                    $averge = $total_applicants / $no_days;
                    $averge = $averge;
                    ?>
                    <h1 class="no-margins"><?php echo $averge; ?></h1>
                    <div class="stat-percent font-bold text-info">0% <i class="fa fa-level-up"></i></div>
                    <small>Average Applicant Per Center</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <span class="label label-primary float-right">Today</span>
                    <h5>Applicant Served</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $this->customlib->inr_format(count($today_applicant)); ?></h1>
                    <small>Applicant Today's Served</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <span class="label label-danger float-right">Lifetime</span>
                    <h5>Applicant Served</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $this->customlib->inr_format(count($total_applicant)); ?></h1>
                    <small>Applicant Served Till Now</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>List of all Aadhaar Sewa Kendra</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Center Detail</th>
                                    <th>Total Applicant</th>
                                    <th>Total Counter</th>
                                    <th>Today's Applicant</th>
                                    <?php
                                    ?>
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
                                        $rowcolor = '#000000'; ?>
                                    <tr style="color: <?php echo $rowcolor; ?>">
                                        <td><?= $sr++ ?></td>
                                        <td><?= $value['center_name'] ?> | <?= $value['city'] ?> | <?= $value['state'] ?> | <?= $value['center_address'] ?></td>
                                        <td>
                                            <?php
                                            $total_applicant = $this->TokenList->totalApplicant($value['id']);
                                            echo count($total_applicant);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $total_counter = $this->TokenList->todayCounter($value['id']);
                                            echo count($total_counter);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $today_applicant = $this->TokenList->todayApplicant($value['id']);
                                            echo count($today_applicant);
                                            ?>
                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>