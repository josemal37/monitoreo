<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of administrador
 *
 * @author Jose
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Administrador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('modelo_administrador'));
        $this->load->library(array('session', 'form_validation', 'encrypt'));
        $this->load->helper(array('url', 'form', 'download'));
        $this->load->database('default');
    }

    public function index() {
        $this->usuarios();
    }
    
    private function verificar_sesion() {
        if ($this->session->userdata('nombre_rol') == FALSE || $this->session->userdata('nombre_rol') != 'administrador') {
            redirect(base_url() . 'login');
        }
    }
    
    public function usuarios() {
        $this->verificar_sesion();
        
        $datos['usuarios'] = $this->modelo_administrador->get_usuarios();
        $this->load->view('administrador/vista_usuarios', $datos);
    }
    
    public function nuevo_usuario() {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['instituciones'] = $this->modelo_administrador->get_instituciones();
        $datos['roles'] = $this->modelo_administrador->get_roles();
        $this->load->view('administrador/vista_registrar_nuevo_usuario', $datos);
    }

}
