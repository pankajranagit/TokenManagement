<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    public function index()
    {
        if (!empty($this->session->userdata('login_detail'))) {
            redirect(base_url('Login/logout'));
        }
        //echo $encode = $this->encrypt->encode('123456');
        //echo "<br>";
        //echo $this->encrypt->decode($encode);
        if (isset($_POST['submit'])) {
            $sessCaptcha = $this->session->userdata('captchaCode');
            $inputCaptcha = $this->input->post('captcha');
            //die;
            if ($inputCaptcha == $sessCaptcha) {
                $this->form_validation->set_rules('username', 'Username', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required', array('required' => 'You must provide a %s.'));
                if ($this->form_validation->run() == FALSE) {
                    // Captcha configuration
                    $config = $this->config->item('captcha');
                    $captcha = create_captcha($config);
                    // Unset previous captcha and set new captcha word
                    $this->session->unset_userdata('captchaCode');
                    $this->session->set_userdata('captchaCode', $captcha['word']);
                    // Pass captcha image to view
                    $data['sessCaptcha'] = $captcha['word'];
                    $data['captchaImg'] = $captcha['image'];
                    //redirect(base_url());
                    $this->load->view('login', $data);
                } else {
                    $username = $this->input->post('username');
                    $password = $this->input->post('password');

                    $this->load->model('Login_user');
                    $response = $this->Login_user->check_login($username, $password);
                    switch ($response['login_type']) {
                        case 'ADMIN':
                            $session['login_detail'] = $response;
                            $this->customlib->setUserLog($response['id'], $response['username'], $response['login_type']);

                            $this->session->set_userdata($session);
                            redirect(base_url('Admin/Dashboard'));
                            break;

                        case 'OPERATOR':

                            $session['login_detail'] = $response;
                            $this->customlib->setUserLog($response['id'], $response['username'], $response['login_type']);

                            $this->session->set_userdata($session);
                            if ($response['operator_type'] == 'HELPDESK') :
                                redirect(base_url('Token/Booking/'));
                            else :
                                $this->Login_user->startOperatorDay($response['id'], $response['center_id'], $response['operator_type']);
                                redirect(base_url('Operator/Dashboard'));
                            endif;
                            break;

                        case 'HELPDESK':
                            break;
                        case 'TMS_MANAGER':
                            break;
                        case 'MANAGER':
                            $session['login_detail'] = $response;
                            $this->customlib->setUserLog($response['id'], $response['username'], $response['login_type']);
                            $this->session->set_userdata($session);
                            redirect(base_url('Manager/Dashboard'));
                            break;
                        default:
                            $this->session->set_flashdata("msg", "<p class='text-danger'>Either Username or Password Mismatched !</p>");
                            redirect(base_url());
                    }
                }
            } else {
                // Captcha configuration
                $config = $this->config->item('captcha');
                $captcha = create_captcha($config);
                // Unset previous captcha and set new captcha word
                $this->session->unset_userdata('captchaCode');
                $this->session->set_userdata('captchaCode', $captcha['word']);
                // Pass captcha image to view
                $data['captchaImg'] = $captcha['image'];
                $data['sessCaptcha'] = $captcha['word'];
                $data['captcha_error'] = true;
                $this->load->view('login', $data);
            }
        } else {
            // Captcha configuration
            $config = $this->config->item('captcha');
            $captcha = create_captcha($config);
            // Unset previous captcha and set new captcha word
            $this->session->unset_userdata('captchaCode');
            $this->session->set_userdata('captchaCode', $captcha['word']);
            // Pass captcha image to view
            $data['captchaImg'] = $captcha['image'];
            $data['sessCaptcha'] = $captcha['word'];
            $data['captcha_error'] = false;
            $this->load->view('login', $data);
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('login_detail');
        unset($_SESSION['BOOKING']);
        redirect(base_url());
    }


    public function refresh()
    {
        // Captcha configuration
        $config = $this->config->item('captcha');
        $captcha = create_captcha($config);
        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        // Display captcha image
        echo $captcha['image'];
    }

    public function ask_login($username, $password)
    {
        $config = [
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|min_length[3]|alpha_dash',
                'errors' => [
                    'required' => 'We need both username and password',
                    'min_length' => 'Minimum Username length is 3 characters',
                    'alpha_dash' => 'You can only use a-z 0-9 _ . – characters for input',
                ],
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'You must provide a Password.',
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],
        ];

        $data = array(
            'username' => $username,
            'password' => $password
        );

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
            echo "<h2><div style='color:red; text-align: center'>";
            echo form_error('username');
            echo form_error('password');
            echo "</div></h2>";
        } else {
            $this->load->model('Login_user');
            $response = $this->Login_user->checkAmsLogin($username, $password);
            switch ($response['login_type']) {
                case 'ADMIN':
                    $session['login_detail'] = $response;
                    $this->customlib->setUserLog($response['id'], $response['username'], $response['login_type']);
                    $this->session->set_userdata($session);
                    redirect(base_url('Admin/Dashboard'));
                    break;
                case 'OPERATOR':
                    $session['login_detail'] = $response;
                    $this->customlib->setUserLog($response['id'], $response['username'], $response['login_type']);
                    $this->session->set_userdata($session);
                    if ($response['operator_type'] == 'HELPDESK') :
                        redirect(base_url('Token/Booking/'));
                    else :
                        $this->Login_user->startOperatorDay($response['id'], $response['center_id'], $response['operator_type']);
                        redirect(base_url('Operator/Dashboard'));
                    endif;
                    break;
                case 'MANAGER':
                    $session['login_detail'] = $response;
                    $this->customlib->setUserLog($response['id'], $response['username'], $response['login_type']);
                    $this->session->set_userdata($session);
                    redirect(base_url('Manager/Dashboard'));
                    break;
                default:
                    echo "<h2 style='color:red; text-align: center'>Either Username or Password Mismatched !</h2>";
            }
        }
    }


    public function getEmptyOperator_t($optype, $center_id)
    {
        $this->load->model('Operator');
        echo "<pre>";
        print_r($this->Operator->getEmptyOperator_test($optype, $center_id));
    }

    public function getEmptyOperator($optype, $center_id)
    {
        $this->load->model('Operator');
        echo "<pre>";

        print_r($this->Operator->getEmptyOperator($optype, $center_id));
    }
}
