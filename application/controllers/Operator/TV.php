<?php

class TV extends CI_Controller
{
    public $user;
    public function __construct()
    {
        parent::__construct();
        $this->User = $this->customlib->userdetail();
        $this->load->model('Operator');
    }

    public function livedata($center_id, $perRow = 8, $numCol = 2)
    {

        $data['perRow'] = $perRow;
        $data['numCol'] = $numCol;
        $data['center_id'] = $center_id;

        $data['livedata'] = $this->Operator->counterToken($center_id);
        $this->load->view('Operator/livedata', $data);
    }

    public function getUpdatedData($center_id, $from, $to, $numCol)
    {

        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
            // whitelist of safe domains
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }

        $livedata = $this->Operator->counterToken($center_id);

        $html = "";

        while ($from <= $to) {
            $html .= "<tr>";
            $col = 1;
            while ($col <= $numCol) {

                $css_border = "";
                if ($col > 1) {
                    $css_border = "border-left: 5px #000046 solid;";
                }

                if ($livedata[$from]['operator_type']) {
                    $html .= "<td class='text-bold' style='text-align:left; " . $css_border . "'><ul id='horizontal-list'><li>" . strtoupper($livedata[$from]['counter_lable']) . " </li><li> (" . $this->customlib->token_lable($livedata[$from]['operator_type']) . ")</li></td>";
                    $diff = time() - strtotime($livedata[$from]['lastupdate']);
                } else {
                    $html .= "<td class='text-bold' style='text-align:left; " . $css_border . "'><ul id='horizontal-list'><li>" . strtoupper($livedata[$from]['counter_lable']) . " </li><li> " . $this->customlib->token_lable($livedata[$from]['operator_type']) . "</li></td>";
                    $diff = time() - strtotime($livedata[$from]['lastupdate']);
                }



                $css = "background-color: red; color: #fff";

                if ($diff <= 90 && $livedata[$from]['curr_tokenid'] != '') {
                    $html .= "<td class='text-bold text-center' style='" . $css . "'>" . $livedata[$from]['curr_tokenid'] . "</td>";
                } else {
                    $html .= "<td class='text-bold text-center'>" . $livedata[$from]['curr_tokenid'] . "</td>";
                }

                $col++;
                $from++;
            }
            $html .= "</tr>";
        }

        echo $html;
    }

    public function test()
    {
        echo "<pre>";
        print_r($_SESSION['datakey']);
        //unset($_SESSION['datakey']);
    }

    public function checkRefresh($center_id, $totalData)
    {
        $livedata = $this->Operator->counterToken($center_id);
        //print_r($livedata);

        foreach ($livedata as $val) {
            if (is_null($val['dltoken']) && !is_null($val['counter_lable']) && is_null($val['curr_status'])){
                $this->Operator->assignToken($val['id'], $val['center_id'], $val['operator_type'], $val['opid']);
            }
        }

        if ($totalData != sizeof($livedata)) {
            $refresh = 1;
        } else {
            $refresh = 0;
        }

        echo $refresh;
    }
}
