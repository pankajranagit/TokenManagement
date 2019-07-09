<!-- <div class="row  border-bottom white-bg dashboard-header">
    <div class="col-md-12">
        <h2>Manage Center - Coming Soon !</h2>
    </div>
</div> -->
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <b><?php echo $cebter_data->center_name." | ".$cebter_data->city." | ".$cebter_data->state." | ".$cebter_data->center_address;?></b>
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Map Counter</h5>
                    <div class="ibox-tools">
                        <?php echo $this->session->flashdata('msg') ?>
                    </div>
                     <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Operator Type</th>
                                    <th>Number of Counter</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr = 1;
                                foreach ($count_data as $value) {
                                    $sid= base64_encode($value->id);  
                                    
                                    ?>
                                    <tr style="color: <?php echo $rowcolor;?>">
                                        <td><?= $sr++ ?></td>
                                        <td><?= $this->customlib->token_lable($value->operator_type) ?></td>
                                        <td><?= $value->cnt ?></td>
                                        <td><a href="#">Add Label</td>
                                       
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


   
</div>