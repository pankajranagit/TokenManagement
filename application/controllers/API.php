<?php

defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

require APPPATH . '/libraries/API_Controller.php';

class API extends API_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->input_rs = json_decode(file_get_contents("php://input"), true);
    }

    public function index()
    {
        echo "<h1>Welcome to TMS API</h1>";
    }

    public function login()
    {

        // API Configuration
        $this->_apiConfig([
            'methods' => ['POST'],
        ]);

        // you user authentication code will go here, you can compare the user with the database or whatever
        $payload = [
            'id' => "Uidai@2019#",
            'key' => "SCL_API_KEY"
        ];

        $req_data = $this->input_rs;

        if ($req_data['id'] == $payload['id'] && $req_data['key'] == $payload['key']) {
            // Load Authorization Library or Load in autoload config file
            $this->load->library('authorization_token');

            // generte a token
            $token = $this->authorization_token->generateToken($payload);

            // return data
            $this->api_return(
                [
                    'status' => true,
                    "result" => [
                        'token' => $token,
                    ],

                ],
                200
            );
        } else {
            $this->api_return(
                [
                    'status' => true,
                    "result" => [
                        'error' => "Either Username or Password Mismatched !",
                    ],

                ],
                200
            );
        }
    }

    public function check_login()
    {

        $username = $this->input_rs['username'];
        $password = $this->input_rs['password'];


        $response = $this->Login_user->check_login($username, $password);
        $data = array();
        if ($response != 0) {
            if ($response['login_type'] == 'MANAGER') {
                $data['centerid'] = $response['center_id'];
                $status = "success";
                $code = "200";
                $message = "Login Successfull";
                $error = "NA";
                $data = $data;
            } else {
                $status = "failure";
                $code = "500";
                $message = "Login Failed";
                $error = "NA";
                $data = array('status' => false);
            }

            echo $this->response_json($status, $data, $code, $message, $error);
        } else {

            $status = "failure";
            $code = "500";
            $message = "Login Failed";
            $error = "NA";
            $data = array('status' => false);


            echo $this->response_json($status, $data, $code, $message, $error);
        }
    }


    public function add_operator()
    {

        // API Configuration
        $this->_apiConfig([
            'methods' => ['POST'],
            'requireAuthorization' => true,
        ]);

        $config = [
            [
                'field' => 'center_name',
                'label' => 'Center Name',
                'rules' => 'trim|required|min_length[3]|alpha_numeric_spaces',
                'errors' => [
                    'required' => 'You must provide a Center Name',
                    'min_length' => 'Minimum Center Name length is 3 characters',
                    'alpha_dash' => 'You can only use a-z 0-9 and Space for input',
                ],
            ],
            [
                'field' => 'center_id',
                'label' => 'Center Id',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'You must provide a Password.',
                    'min_length' => 'Only Integer Value is Accepted',
                ],
            ],
            [
                'field' => 'operator_name',
                'label' => 'Operator Name',
                'rules' => 'trim|required|min_length[3]|alpha_numeric_spaces',
                'errors' => [
                    'required' => 'You must provide a Operator Name',
                    'min_length' => 'Minimum Operator Name length is 3 characters',
                    'alpha_dash' => 'You can only use a-z 0-9 _ . – characters for input',
                ],
            ],
            [
                'field' => 'operator_number',
                'label' => 'Mobile Number',
                'rules' => 'trim|exact_length[10]|numeric',
                'errors' => [
                    'min_length' => 'Exact length of Mobile Number is 10 characters',
                    'numeric' => 'You can only use 0-9 characters for input',
                ],
            ],
            [
                'field' => 'operator_type',
                'label' => 'Operator Type',
                'rules' => 'required|in_list[PD,ECMP,VERIFIER,CASHIER,HELPDESK]',
                'errors' => [
                    'required' => 'You must provide a Operator Type',
                    'in_list' => 'Operator type should be only one from the list - [PD,ECMP,VERIFIER,CASHIER,HELPDESK]',
                ],
            ],
            [
                'field' => 'operator_email',
                'label' => 'Username',
                'rules' => 'trim|required|is_unique[operator_list.operator_email]',
                'errors' => [
                    'required' => 'You must provide a Username',
                    'min_length' => 'Exact length of Mobile Number is 10 characters',
                    'is_unique' => 'This username is already exist',
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
            ]
        ];



        $data = array(
            'center_name' => $this->input_rs['center_name'],
            'center_id' => $this->input_rs['center_id'],
            'operator_name' => $this->input_rs['operator_name'],
            'operator_number' => $this->input_rs['operator_mobile'],
            'operator_type' => $this->input_rs['operator_type'],
            'operator_email' => $this->input_rs['username'],
            'password' => $this->input_rs['password']
        );

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
            $status = "failure";
            $code = 500;
            $message = "Operator Addition Failed";
            $error = $this->form_validation->error_array();
            $res_data = array('status' => false);

            echo $this->response_json($status, $res_data, $code, $message, $error);
        } else {
            $status = "success";
            $code = 200;
            $message = "Operator Addition Success";
            $error = "NA";
            $res_data = array('status' => true);
            //http://192.168.1.101:84/index.php/Admin/ManageOperator/addoperator/NA==

            $form_data['operator_name'] = $data['operator_name'];
            $form_data['operator_number'] = $data['operator_number'];
            $form_data['operator_type'] = $data['operator_type'];
            $form_data['operator_email'] = $data['operator_email'];
            $form_data['center_id'] = $data['center_id'];
            $form_data['create_on'] = date('Y-m-d');

            $userDetails = $this->General->add_data('operator_list', $form_data);
            if ($userDetails > 0) {
                $password = "Uidai@2019#";
                $log_data['username'] = $data['operator_email'];
                $log_data['password'] = $this->encrypt->encode($password);
                $log_data['ams_password'] = $data['password'];

                $log_data['login_type'] = 'OPERATOR';
                $log_data['operator_type'] = $data['operator_type'];
                $log_data['create_on'] = date('Y-m-d h:i:s');
                $log_data['email_verify'] = 1;
                $log_data['center_id'] = $data['center_id'];

                $this->General->add_data('login_user', $log_data);
            }

            echo $this->response_json($status, $res_data, $code, $message, $error);
        }
    }


    public function operator_log()
    {

        // API Configuration
        $this->_apiConfig([
            'methods' => ['POST'],
            'requireAuthorization' => true,
        ]);

        $AppIds = $this->input_rs['AppointmentNO'];
        $optype = $this->getRole($this->input_rs['optype']);

        // SELECT token_list.token_number, token_list.id, token_list.application_id, login_user.username, token_log.intime, token_log.outtime FROM token_log LEFT JOIN login_user ON login_user.id = token_log.operator_id LEFT JOIN token_list ON token_list.id = token_log.token_id WHERE token_list.application_id IN('121214124533','211212','131313312')


        $this->db->select('token_list.token_number, token_list.id as tms_id, token_list.application_id, login_user.username, token_log.intime, token_log.outtime');
        $this->db->from('token_log');
        $this->db->join('login_user', 'login_user.id = token_log.operator_id', 'left');
        $this->db->join('token_list', 'token_list.id = token_log.token_id', 'left');

        if ($optype) {
            $this->db->where('login_user.operator_type', $optype);
        }

        $where = "(";
        foreach ($AppIds as $app_id) {
            $where  .= "token_list.application_id = '$app_id' OR ";
        }

        $where .= " 0)";
        $this->db->where($where);

        $query = $this->db->get();

        // $this->db->last_query();
        $data = $query->result_array();
        foreach ($data as $val) {
            $temp['token_number '] = $val['token_number'];
            $temp['tms_id'] = $val['tms_id'];
            $temp['application_id'] = $val['application_id'];
            $temp['username'] = $val['username'];
            $temp['intime'] = date('m/d/Y H:i:s', strtotime($val['intime']));
            $temp['outtime'] = date('m/d/Y H:i:s', strtotime($val['outtime']));

            $alldata[] = $temp;
        }

        $status = "success";
        $code = 200;
        $message = "Token Log List";
        $error = "NA";
        $res_data = $alldata;

        echo $this->response_json($status, $res_data, $code, $message, $error);
    }

    public function response_json($status, $data, $code, $message, $error, $appversion = "1.0", $apiname = "TMS API")
    {
        $this->success = array(
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'appversion' => $appversion,
            'apiname' => $apiname,
            'error' => $error,
            'data' => $data
        );

        return json_encode($this->success);
    }


    public function getRole($optype)
    {
        switch ($optype) {
            case "Cashier":
                return "CASHIER";
                break;
            case "Operator":
                return "ECMP";
                break;
            case "Admin":
                return "Admin";
                break;
            case "Verifier":
                return "VERIFIER";
                break;
            case "PortalDesk":
                return "PD";
                break;
        }
    }
}
