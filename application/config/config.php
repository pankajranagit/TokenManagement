<?php

defined('BASEPATH') or exit('No direct script access allowed');


$config['base_url'] = 'http://192.168.0.107:81/TMS_FIXED_25JUNE19/';
$config['ams_base_url'] = 'http://192.168.0.107:90/';

$config['index_page'] = 'index.php';
$config['uri_protocol'] = 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language'] = 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = "./vendor/autoload.php";
//$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-@\=';
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['allow_get_array'] = TRUE;
$config['log_threshold'] = 0;
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;
$config['encryption_key'] = 'Uidai@2019#';
// $config['sess_driver'] = 'files';
// $config['sess_cookie_name'] = 'ci_session';
// $config['sess_expiration'] = 7200;
// $config['sess_save_path'] = sys_get_temp_dir();
// $config['sess_match_ip'] = FALSE;
// $config['sess_time_to_update'] = 300;
// $config['sess_regenerate_destroy'] = FALSE;

$config['sess_driver'] = 'database';
$config['sess_cookie_name'] = 'mycookie';
$config['sess_expiration'] = 0;
$config['sess_save_path'] = 'ci_sessions';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;
$config['sess_use_database'] = TRUE;
$config['sess_expire_on_close'] = TRUE;
$config['sess_table_name'] = 'ci_session';

$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;

$config['standardize_newlines'] = FALSE;

$config['global_xss_filtering'] = TRUE;

$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'CsRfUiDaiNaMe';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array('Admin/ManageOperator/getcenterinfo', 'Admin/ManageServices/getservicesinfo', 'Admin/ManageServices/getchildinfo', 'API/add_operator', 'API/login', 'API/operator_log', 'Admin/ManageServices/getchildinfo');

$config['compress_output'] = FALSE;

$config['time_reference'] = 'local';

$config['rewrite_short_tags'] = FALSE;

$config['proxy_ips'] = '';

$config['captcha'] = array(
    'img_path' => './captcha/',
    'img_url' => $config['base_url'] . 'captcha/',
    'font_path' => 'fonts/verdana.ttf',
    'font_path' => FCPATH . 'system/fonts/Merriweather-Bold.ttf',
    'img_width' => '160',
    'img_height' => 50,
    'word_length' => 4,
    'font_size' => 20,
    'colors' => array(
        'background' => array(255, 255, 255),
        'border' => array(255, 255, 255),
        'text' => array(0, 0, 0),
        'grid' => array(192, 192, 192)
    )
);
