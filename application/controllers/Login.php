<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of login
 *
 * @author Jose
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('modelo_login');
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('url', 'form'));
        $this->load->database('default');
        $this->clear_cache();
    }

    private function clear_cache() {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }

    public function index() {
        switch ($this->session->userdata('nombre_rol')) {
            case '':
                $data['token'] = $this->token();
                $data['titulo'] = 'Inicio de sesión';
                $this->load->view('vista_login', $data);
                break;
            case 'financiador':
                redirect(base_url() . 'financiador');
                break;
            case 'socio':
                redirect(base_url() . 'socio');
                break;
            case 'administrador':
                redirect(base_url() . 'administrador');
                break;
            case 'coordinador':
                redirect(base_url() . 'coordinador');
                break;
            default:
                $data['titulo'] = 'Inicio de sesión';
                $this->load->view('vista_login', $data);
                break;
        }
    }

    public function iniciar_sesion() {
        if ($this->input->post('token') && $this->input->post('token') == $this->session->userdata('token')) {
            $this->form_validation->set_rules('login_usuario', 'login_usuario', 'required|trim|max_length[32]');
            $this->form_validation->set_rules('password_usuario', 'password_usuario', 'required|trim|max_length[32]');

            if ($this->form_validation->run() == FALSE) {
                $this->index();
            } else {
                $login_usuario = $this->input->post('login_usuario');
                $password_usuario = $this->input->post('password_usuario');
                $datos_usuario = $this->modelo_login->get_usuario($login_usuario, $password_usuario);
                if ($datos_usuario == TRUE) {
                    $datos = array(
                        'is_logued_in' => TRUE,
                        'id_usuario' => $datos_usuario->id_usuario,
                        'nombre_rol' => $datos_usuario->nombre_rol,
                        'login_usuario' => $datos_usuario->login_usuario,
                        'nombre_usuario' => $datos_usuario->nombre_usuario,
                        'apellido_usuario' => $datos_usuario->apellido_paterno_usuario . " " . $datos_usuario->apellido_materno_usuario,
                        'id_institucion' => $datos_usuario->id_institucion,
                        'nombre_institucion' => $datos_usuario->nombre_institucion,
                        'carpeta_institucion' => $datos_usuario->carpeta_institucion
                    );
                    $this->session->set_userdata($datos);
                    $this->index();
                }
            }
        } else {
            redirect(base_url() . 'login');
        }
    }

    public function token() {
        $token = md5(uniqid(rand(), true));
        $this->session->set_userdata('token', $token);
        return $token;
    }

    public function cerrar_sesion() {
        $this->session->sess_destroy();
        redirect(base_url());
    }

}
