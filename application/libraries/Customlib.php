<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customlib
{

    var $CI;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->library('user_agent');
        $this->CI->load->library('email');
    }

    public function userdetail()
    {
        return $this->CI->session->userdata('login_detail');
    }

    public function setUserLog($login_id, $username, $role)
    {
        if ($this->CI->agent->is_browser()) {
            $agent = $this->CI->agent->browser() . ' ' . $this->CI->agent->version();
        } elseif ($this->CI->agent->is_robot()) {
            $agent = $this->CI->agent->robot();
        } elseif ($this->CI->agent->is_mobile()) {
            $agent = $this->CI->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $data = array(
            'login_id' => $login_id,
            'username' => $username,
            'role' => $role,
            'ipaddress' => $this->CI->input->ip_address(),
            'user_agent' => $agent . ", " . $this->CI->agent->platform(),
        );

        $this->CI->Userlog->add($data);
    }

    function getGender()
    {
        $gender = array();
        $gender['Male'] = $this->CI->lang->line('male');
        $gender['Female'] = $this->CI->lang->line('female');
        return $gender;
    }

    function getMonthDropdown()
    {
        $array = array();
        for ($m = 1; $m <= 12; $m++) {
            $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
            $array[$month] = $month;
        }
        return $array;
    }

    function getMonthList()
    {
        $months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'Decmber');
        return $months;
    }


    function getOperatorType()
    {
        $opt_typ = array('HELPDESK', 'PD', 'VERIFIER', 'ECMP', 'CASHIER', 'SCREENER');
        return $opt_typ;
    }

    function curr_status()
    {
        $status = array('Lunch' => 'Lunch', 'TeaBreak' => 'Tea Break', 'Washroom' => 'Washroom', 'DayLeave' => 'Day Leave', 'Other' => 'Other');
        return $status;
    }

    function getOperator()
    {
        $opt_typ = array('PD', 'VERIFIER', 'ECMP', 'CASHIER', 'SCREENER');
        return $opt_typ;
    }

    public function token_lable($key)
    {
        switch ($key) {
            case 'online':
                $value = 'Online Payment';
                break;
            case 'walkin':
                $value = 'Walkin';
                break;
            case 'category':
                $value = 'Special Category';
                break;
            case 'topay':
                $value = 'To Pay';
                break;
            case 'enrollment':
                $value = 'New Enrollment';
                break;
            case 'pregnant_woman':
                $value = 'Pregnant Woman';
                break;
            case 'update':
                $value = 'Update';
                break;
            case 'senior_citizen':
                $value = 'Senior Citizen';
                break;
            case 'none':
                $value = 'None';
                break;
            case 'VIP':
                $value = 'VIP';
                break;
            case 'handicapped':
                $value = 'Physically Handicapped';
                break;
            case 'paid':
                $value = 'Paid';
                break;
            case 'PD':
                $value = 'Portal Desk';
                break;
            case 'HELPDESK':
                $value = 'Token Issuer';
                break;
            case 'ECMP':
                $value = 'ECMP Operators';
                break;
            case 'CASHIER':
                $value = 'Cashier';
                break;
            default:
                $value = $key;
        }
        return $value;
    }

    public function inr_rupees_format($figure)
    {
        if ($figure > 99000 && $figure <= 9900000) {
            $figure = (float)($figure / 100000);
            return number_format(round($figure, 2), 2) . " Lakh";
        } elseif ($figure > 9900000) {
            $figure = (float)($figure / 10000000);
            return number_format(round($figure, 2), 2) . " Cr";
        } else {
            return number_format(round($figure, 2), 2);
        }
    }

    public function inr_format($num)
    {
        if ($num !== '') {
            $explrestunits = "";
            if (strlen($num) > 3) {
                $lastthree = substr($num, strlen($num) - 3, strlen($num));
                $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
                $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
                $expunit = str_split($restunits, 2);
                for ($i = 0; $i < sizeof($expunit); $i++) {
                    // creates each of the 2's group and adds a comma to the end
                    if ($i == 0) {
                        $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer
                    } else {
                        $explrestunits .= $expunit[$i] . ",";
                    }
                }
                $thecash = $explrestunits . $lastthree;
            } else {
                $thecash = $num;
            }
        } else {
            $thecash = '0';
        }
        return $thecash; // writes the final format where $currency is the currency symbol.
    }

    public function mail_body($content)
    {
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
    <title>' . html_escape($subject) . '</title>
    <style type="text/css">
        body {
            font-family: Arial, Verdana, Helvetica, sans-serif;
            font-size: 18px;
        }
    </style>
</head>
<body>
' . $content . '
</body>
</html>';

        return $body;
    }

    public function send_mail($to, $subject, $body)
    {
        $this->CI->email
            ->from('admin@aadhar.in', 'ASK Admin')
            ->to($to)
            ->subject($subject)
            ->message($body)
            ->send();
    }

    public function latlongtoAddress($latlong)
    {
        $api_key = "AIzaSyAePZPUdoiiJCHnc7lQxPGfpVGHQnQ_fl0";
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latlong . "&key=" . $api_key;
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
        echo "<pre>";
        print_r($response);
        echo "</pre>";
    }
}
