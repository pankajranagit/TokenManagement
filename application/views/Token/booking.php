<div class="container">
    <section class="booking-info">
        <div class="row">
            <div class="col-lg-12 text-center" style="padding-top: 60px">
                <?php
                if ($parents) {
                    foreach ($parents as $value) {
                        echo "<a href='" . base_url('Token/Booking/index/' . $value['id']) . "' class='btn btn-primary btn-lg custom-btn'>" . $value['lable'] . "</a><br>";
                    }
                } else {
                    echo "<h1 style='font-weight:bold; font-size:5em' class='text-danger'>" . $token_num . "<br></h1><h1 style='font-weight:bold; font-size:1em' class='text-danger'>Token Number</h1><br>";

                    ?>
                    <div class="panel panel-default ">
                        <div class="panel-body">
                            <input type="hidden" class="form-control" value="<?= $token_base64 ?>" placeholder="Enter the Image data here.." id="txt_ImageData" />
                            <!-- <button type="button" class="btn btn-danger btn-lg custom-print" onclick="PrintImage()">Print Token</button> -->
                            <button onclick="PrintHtml()" type="button" class="btn btn-danger btn-lg custom-print">Print Token</button>
                        </div>
                    </div>
                    <?php

                    echo "<a href='" . base_url('Token/Booking/print_data') . "' class='btn btn-primary btn-lg custom-print'>Next Token</a>";
                }

                ?>
                <hr>
                <?php
                //echo $lable;
                //print_r($_SESSION['BOOKING']);
                //print_r($_SESSION['SESSIONID']);
                if ($lable > 0) {
                    ?>
                    <div class="text-left">
                        <a href="<?= base_url('Token/Booking/home') ?>" class="btn btn-warning btn-sm"><i class="fas fa-times"></i> &nbsp;Cancel your current selections</a>
                    </div>
                <?php
            }
            ?>
            </div>
        </div>
    </section>
</div>