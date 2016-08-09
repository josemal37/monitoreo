<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of financiador
 *
 * @author Jose
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class financiador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->helper(array('url'));
    }

    public function index() {
        if ($this->session->userdata('nombre_rol') == FALSE || $this->session->userdata('nombre_rol') != 'financiador') {
            redirect(base_url() . 'login');
        }
        $data['titulo'] = 'Bienvenido Financiador';
        $this->load->view('vista_financiador', $data);
    }

}
