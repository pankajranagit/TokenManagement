<!-- <div class="row  border-bottom white-bg dashboard-header">
    <div class="col-md-12">
        <h2>Manage Center - Coming Soon !</h2>
    </div>
</div> -->
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <b><?php echo $cebter_data->center_name." | ".$cebter_data->city." | ".$cebter_data->state." | ".$cebter_data->center_address;?></b>
            
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Total Operator</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                    <?php  
                        if(!empty($operator_list))
                            echo count($operator_list);
                        else
                            echo "0";

                    ?>
                    </h1>
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
                        $averge = $total_applicants/$no_days;
                        $averge = $averge;
                        ?>
                    <h1 class="no-margins"><?php echo $averge;?></h1>
                    <small>Average Applicant</small>
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
                    <h1 class="no-margins"><?php echo $this->customlib->inr_format(count($today_applicant));?></h1>
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
                    <h1 class="no-margins"><?php echo $this->customlib->inr_format(count($total_applicant));?></h1>
                    <small>Applicant Served Till Now</small>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr = 1;
                                foreach ($operator_list as $value) {
                                    $sid= base64_encode($value->id);  
                                    if($value->status==1)
                                        $rowcolor = '#999999';
                                    else
                                        $rowcolor = '#000000';
                                    ?>
                                    <tr style="color: <?php echo $rowcolor;?>">
                                        <td><?= $sr++ ?></td>
                                        <td><?= $value->operator_name ?></td>
                                        <td><?= $value->operator_number ?></td>
                                        <td><?= $this->customlib->token_lable($value->operator_type) ?></td>
                                        <td><?= $value->operator_email ?></td>
                                        
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