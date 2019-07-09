<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Booking extends CI_Controller
{
    public $User;

    public function __construct()
    {
        parent::__construct();
        $this->User = $this->customlib->userdetail();
        if ($this->User['operator_type'] != 'HELPDESK') :
            redirect(base_url('Login/logout'));
        endif;

        $this->load->model('Helpdesk');
        $this->load->model('TokenList');
        $this->load->model('Operator');
    }

    public function index($parentid = NULL)
    {
        $email = $this->User['username'];
        $data['user'] = $this->User;
        $opid = $data['user']['id'];
        $centerid = $this->User['center_id'];

        $this->TokenList->getFreshToken($centerid, date('Y-m-d'));

        $data['operator'] = $this->Operator->OperatorByEmail($email)[0];

        $token_num = NULL;
        $data['user'] = $this->User;
        //unset($_SESSION['BOOKING']);
        //unset($_SESSION['SESSIONID']);
        if (empty($_SESSION['BOOKING'])) {
            $_SESSION['SESSIONID'] = time() . "/" . $opid . "/" . rand(1000, 9999);
            $_SESSION['BOOKING'][] = $this->Helpdesk->getInitial();
        }

        if ($parentid == NULL) {
            $lable = 0;
        } else {
            $lable = count($_SESSION['BOOKING']) - 1;
        }

        $parents = $this->Helpdesk->getParents($parentid);
        if (empty($parents)) {
            $token_num = $this->TokenList->getNewToken($_SESSION['SESSIONID'], $data['operator']['center_id']);

            $app_type = implode(",", $_SESSION['BOOKING']);
            $isvalid = $this->TokenList->ifAppTypexist($app_type);

            if ($isvalid <= 0) {
                redirect(base_url('Token/Booking/home'));
            }
        }

        $data['lable'] = $lable;
        $lastId = $_SESSION['BOOKING'][$lable];

        if ($parentid != NULL && $parentid != $lastId) {
            $_SESSION['BOOKING'][$lable] = $parentid;
        }

        if (!empty($parents) && $parentid != NULL) {
            $_SESSION['BOOKING'][$lable + 1] = $this->Helpdesk->getInitial($parentid);
        }

        $data['token_num'] = $token_num;
        $data['parents'] = $parents;

        if ($token_num != NULL) {
            $tags = $_SESSION['BOOKING'];
            //$resultstr = [];

            foreach ($tags as $key) {
                $category = get_Details('helpdesk', array('id' => $key), 'single');
                $resultstr[] = $category->lable;
            }

            $services = implode(" => ", $resultstr);

            $_SESSION['TOKEN']['Service'] = $data['Service'] = $services;
            $_SESSION['TOKEN']['TokenNumber'] = $data['TokenNumber'] = $token_num;
            $_SESSION['TOKEN']['Address'] = $data['Address'] = $data['operator']['center_address'];

            $data['TokenHtml'] = $this->load->view('token_view', $data, true);

            //$img_name = sha1($_SESSION['SESSIONID']);

            //$this->create_image($img_name, $token_num, $services, $data['operator']['center_address']);

            //image to base64 encode
            //$path = base_url("assets/Token/$img_name.bmp");
            //die;
            //$token_base64 = file_get_contents($path);
            //$data['token_base64'] = base64_encode($token_base64);
            //image to base64
        }

        $this->load->view('layout/Token/header', $data);
        $this->load->view('Token/booking', $data);
        $this->load->view('layout/Token/footer', $data);
    }

    public function print_data()
    {
        $token_id = $this->TokenList->updateApplicantType($_SESSION['SESSIONID'], $_SESSION['BOOKING']);

        //Assigning operator if any operator is available
        $this->Operator->assignOperatorToken($token_id, $this->User['center_id']);

        redirect(base_url('Token/Booking/home'));
    }

    public function home()
    {
        unset($_SESSION['BOOKING']);
        unset($_SESSION['SESSIONID']);
        unset($_SESSION['TOKEN']);
        redirect(base_url('Token/Booking'));
    }

    public function set_tokenSession($key, $value)
    {
        $_SESSION['BOOKING'][$key] = $value;
        //print_r($_SESSION['BOOKING']);
    }

    public function create_image($img_name, $tokenNumber, $services, $address)
    {
        $im = imagecreatefrombmp('assets/Token/token.bmp');
        $black = imagecolorallocate($im, 0, 0, 0);
        // First we create our stamp image manually from GD
        $stamp = imagecreatetruecolor(280, 190);
        $dir = explode('application', __FILE__)[0];
        $font = $dir . 'assets\fonts\times.ttf';
        // Add the text

        imagettftext($im, 16, 0, 320, 30, $black, $font, date('d-m-Y h:i A'));
        imagettftext($im, 120, 0, 200, 250, $black, $font, $tokenNumber);

        imagettftext($im, 20, 0, 30, 300, $black, $font, "Service opted for : " . $services);
        imagettftext($im, 20, 0, 155, 330, $black, $font, $address);

        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 0);

        // Save the image to file and free memory
        imagebmp($im, "assets/Token/$img_name.bmp");
        imagedestroy($im);
    }


    function generatePDF()
    {
        $this->load->library('pdfgenerator');

        $data['TokenNumber'] = $_SESSION['TOKEN']['TokenNumber'];
        $data['Service'] = $_SESSION['TOKEN']['Service'];
        $data['Address'] = $_SESSION['TOKEN']['Address'];

        $html = $this->load->view('token_view', $data, true);
        $filename = 'Token_' . time();
        $this->pdfgenerator->generateToken($html, $filename, true, 'A4', 'portrait');
    }
}
