<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Live TV - Token Number Display</title>
    <!-- CSS Code: Place this code in the document's head (between the <head> -- </head> tags) -->
    <style>
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 1.5em;
        }

        .text-bold {
            font-weight: bold;
        }

        #customers td,
        #customers th {
            border: 1px solid #000046;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #fff;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background: #1CB5E0;
            color: white;
        }

        .text-warning {
            color: #000046 !important;
        }

        ul#horizontal-list {
            list-style: none;
            padding: 0px;
            margin: 0px;
        }

        ul#horizontal-list li {
            display: inline;
        }
    </style>

</head>
<!-- center id, perRow, numCol -->

<body style="padding:10px;">
    <?php
    // echo "<pre>";
    // print_r($livedata);
    // die;
    $totalData =  sizeof($livedata);

    $dataPerPage = $perRow * $numCol;
    $totScreen = floor($totalData / $dataPerPage) + 1;
    $x = 0;
    $index = 0;
    while ($x <  $totScreen) {
        $temp[$x]['from'] = $index;
        $temp[$x]['to'] = $index + ($dataPerPage - 1);

        if (($x + 1) == $totScreen) {
            $temp[$x]['from'] = $index;
            $temp[$x]['to'] = $totalData - 1;
        }
        $index = $index + $dataPerPage;
        $x++;
    }
    ?>

    <div class="carousel slide" id="slideshow-carousel-1" data-ride="carousel" data-interval="15000">
        <div class="carousel-inner">
            <?php
            $x = 0;
            while ($x <  $totScreen) {
                $from = $temp[$x]['from'];
                $to = $temp[$x]['to'];
                ?>
                <div class="carousel-item <?= ($x == 0 ? 'active' : '') ?>">
                    <table id="customers">
                        <thead>
                            <tr>
                                <?php
                                $col = 1;
                                while ($col <= $numCol) {
                                    ?>
                                    <th style="text-align:left; <?= ($col > 1 ? 'border-left: 5px #000046 solid' : '') ?>">Counter</th>
                                    <th class="text-center">Token Number</th>
                                    <?php
                                    $col++;
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody id="itemid_<?php echo $x; ?>">
                            <?php
                            while ($from <= $to) {
                                ?>
                                <tr>
                                    <?php
                                    $col = 1;
                                    while ($col <= $numCol) {
                                        ?>
                                        <td class='text-bold' style="text-align:left; <?= ($col > 1 ? 'border-left: 5px #000046 solid' : '') ?>">
                                            <ul id="horizontal-list">
                                                <li><?= strtoupper($livedata[$from]['counter_lable']) ?></li>
                                                <li>
                                                    <?php
                                                    if ($livedata[$from]['operator_type']) {
                                                        echo "(" . $this->customlib->token_lable($livedata[$from]['operator_type']) . ")";
                                                    }
                                                    ?>
                                                </li>
                                            </ul>
                                        </td>
                                        <td class='text-bold text-center'><?= $livedata[$from]['curr_tokenid'] ?></td>
                                        <?php
                                        $col++;
                                        $from++;
                                    }
                                    ?>
                                </tr>
                            <?php
                        }

                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
                $x++;
            }
            ?>
        </div>
    </div>
    <div style="display:none" id="checkRefresh"></div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
        var base_url = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url('assets/js/jquery-3.3.1.min.js') ?>"></script>

    <script>
        setInterval(checkUpdated, 10000);

        function checkUpdated() {
            $('#checkRefresh').load(base_url + "Operator/TV/checkRefresh/<?= $center_id ?>/<?= $totalData ?>");

            if (parseInt($("#checkRefresh").text()) === 1) {
                window.location.href = base_url + "Operator/TV/livedata/<?= $center_id ?>/<?= $perRow ?>/<?= $numCol ?>";
            }
            <?php
            $x = 0;
            while ($x <  $totScreen) {
                $from = $temp[$x]['from'];
                $to = $temp[$x]['to'];
                ?>
                $('#itemid_<?php echo $x; ?>').load(base_url + "Operator/TV/getUpdatedData/<?= $center_id ?>/<?= $from ?>/<?= $to ?>/<?= $numCol ?>");
                <?php
                $x++;
            } ?>
        }
    </script>
</body>

</html>