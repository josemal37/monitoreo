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

class Observador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('modelo_observador', 'modelo_indicador', 'modelo_indicador_cualitativo', 'modelo_indicador_cuantitativo', 'modelo_indicador_acumulativo', 'modelo_indicador_promedio_menor_que', 'modelo_indicador_porcentaje'));
        $this->load->library(array('session', 'form_validation', 'encrypt', 'upload'));
        $this->load->helper(array('url', 'form', 'download'));
        $this->load->database('default');
    }
    
    private function verificar_sesion() {
        if ($this->session->userdata('nombre_rol') == FALSE || $this->session->userdata('nombre_rol') != 'socio observador') {
            redirect(base_url() . 'login');
        }
    }

    public function index() {
        redirect(base_url() . 'observador/ver_proyecto');
    }
    
    public function ver_reporte_prodoc() {
        $this->verificar_sesion();
        
        $datos = Array();
        $id_prodoc = $this->modelo_observador->get_id_prodoc();
        $datos['prodoc'] = $this->modelo_observador->get_prodoc_completo($id_prodoc);
        $this->load->view('observador/vista_reporte_prodoc', $datos);
    }
    
    public function ver_proyectos() {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyectos'] = $this->modelo_observador->get_proyectos();
        $this->load->view('observador/vista_proyectos', $datos);
    }
    
    public function ver_proyecto() {
        $this->verificar_sesion();
        
        $datos = Array();
        $id_proyecto = $this->modelo_observador->get_id_proyecto_global();
        if($id_proyecto) {
            $datos['proyecto_global'] = $this->modelo_observador->get_proyecto_global_completo($id_proyecto);
        } else {
            $datos['proyecto_global'] = false;
        }
        $this->load->view('observador/vista_proyecto', $datos);
    }
    
    public function ver_poa_gestion_actual() {
        $this->verificar_sesion();
        
        $datos = Array();
        $id_poa_gestion_actual = $this->modelo_observador->get_id_poa_gestion_actual();
        $datos['activo'] = 'Gestion actual';
        if($id_poa_gestion_actual) {
            $datos['proyecto'] = $this->modelo_observador->get_reporte_proyecto_completo_activo($id_poa_gestion_actual);
        } else {
            $datos['proyecto'] = false;
        }
        $this->load->view('observador/vista_reporte_proyecto', $datos);
    }
    
    public function ver_poa($id_poa) {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyecto'] = $this->modelo_observador->get_reporte_proyecto_completo_activo($id_poa);
        $datos['activo'] = 'Proyectos';
        if($datos['proyecto']) {
            $this->load->view('observador/vista_reporte_proyecto', $datos);
        } else {
            redirect('observador/error');
        }
    }
    
    public function ver_poas_gestion_actual() {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyectos'] = $this->modelo_observador->get_proyectos_activos_gestion_actual();
        $this->load->view('observador/vista_reporte_proyectos_activos_gestion_actual', $datos);
    }
    
    public function modificar_password($id_usuario) {
        $this->verificar_sesion();
        
        if(isset($_POST['password_antiguo']) && isset($_POST['password_nuevo']) && isset($_POST['password_confirmacion'])) {
            $this->form_validation->set_rules('password_antiguo', 'password_antiguo', 'required|trim|min_length[5]|max_length[32]');
            $this->form_validation->set_rules('password_nuevo', 'password_nuevo', 'required|trim|min_length[5]|max_length[32]');
            $this->form_validation->set_rules('password_confirmacion', 'password_confirmacion', 'required|trim|min_length[5]|max_length[32]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['password_antiguo']);
                $this->modificar_password($id_usuario);
            } else {
                $password_antiguo = sha1($this->input->post('password_antiguo'));
                $password_nuevo = $this->input->post('password_nuevo');
                $password_confirmacion = $this->input->post('password_confirmacion');
                $password_antiguo_verificado = $this->modelo_observador->verificar_password($id_usuario, $password_antiguo);
                if($password_nuevo == $password_confirmacion && $password_antiguo_verificado) {
                    $password_nuevo = sha1($password_nuevo);
                    $this->modelo_observador->update_password_usuario($id_usuario, $password_nuevo);
                    redirect(base_url() . 'observador');
                } else {
                    if(!$password_antiguo_verificado) {
                        $this->session->set_flashdata('error_password_antiguo', 'El password introducido no coincide con su password actual.');
                    } else {
                        $this->session->set_flashdata('error_password_confirmacion', 'El password introducido no coincide con el nuevo password.');
                    }
                    redirect(base_url() . 'observador/modificar_password/' . $id_usuario, 'refresh');
                }
            }
        } else {
            $datos = Array();
            $datos['id_usuario'] = $id_usuario;
            $datos['usuario'] = $this->modelo_observador->get_usuario($id_usuario);
            $this->load->view('observador/vista_modificar_password', $datos);
        }
    }
    
    public function modificar_datos_contacto($id_usuario) {
        $this->verificar_sesion();
        
        if(isset($_POST['id_usuario']) && isset($_POST['telefono_usuario']) && isset($_POST['correo_usuario'])) {
            $this->form_validation->set_rules('telefono_usuario', 'telefono_usuario', 'numeric');
            $this->form_validation->set_rules('correo_usuario', 'correo_usuario', 'trim|valid_email|min_length[5]|max_length[64]');
            if($this->form_validation->run() == false) {
                unset($_POST['id_usuario']);
                $this->modificar_datos_contacto($id_usuario);
            } else {
                $telefono_usuario = 0;
                if($this->input->post('telefono_usuario') != "") {
                    $telefono_usuario = $this->input->post('telefono_usuario');
                }
                $correo_usuario = $this->input->post('correo_usuario');
                $this->modelo_observador->update_datos_contacto_usuario($id_usuario, $telefono_usuario, $correo_usuario);
                $this->session->set_userdata('telefono_usuario', $telefono_usuario);
                $this->session->set_userdata('correo_usuario', $correo_usuario);
                redirect(base_url() . 'observador');
                
            }
        } else {
            $datos = Array();
            $datos['id_usuario'] = $id_usuario;
            $datos['usuario'] = $this->modelo_observador->get_usuario($id_usuario);
            $this->load->view('observador/vista_modificar_datos_contacto', $datos);
        }
    }

    public function error() {
        $this->load->view('vista_error');
    }
}
