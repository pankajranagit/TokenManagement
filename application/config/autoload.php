<?php

defined('BASEPATH') or exit('No direct script access allowed');

$autoload['packages'] = array();
$autoload['libraries'] = array('session', 'form_validation', 'database', 'encrypt', 'Customlib', 'image_lib');
$autoload['drivers'] = array();
$autoload['helper'] = array('url', 'captcha', 'form', 'string', 'myhelper_helper');
$autoload['config'] = array();
$autoload['language'] = array('system_lang');
$autoload['model'] = array('Login_user', 'Userlog', 'Center_list', 'TokenList', 'General');
