<?php

$config = array(
    'add_center' => array(
        array(
            'field' => 'center_name',
            'label' => 'Center Name',
            'rules' => 'trim|required|min_length[3]|is_unique[center_list.center_name]'
        ),
        array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'trim|required|min_length[3]'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'trim|required|min_length[3]'
        ),
        array(
            'field' => 'center_address',
            'label' => 'Center Address',
            'rules' => 'trim|min_length[3]'
        )
    ),
    'update_center' => array(
        array(
            'field' => 'center_name',
            'label' => 'Center Name',
            'rules' => 'trim|required|min_length[3]|is_unique[center_list.center_name]'
        ),
        array(
            'field' => 'center_address',
            'label' => 'Center Address',
            'rules' => 'trim|min_length[3]'
        )
    )
);
