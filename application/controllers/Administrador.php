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

    public function ver_usuario($id_usuario) {
        $this->verificar_sesion();

        $datos['usuario'] = $this->modelo_administrador->get_usuario($id_usuario);
        $this->load->view('administrador/vista_ver_usuario', $datos);
    }

    public function nuevo_usuario() {
        $this->verificar_sesion();

        if (isset($_POST['nombre_usuario']) && isset($_POST['apellido_paterno_usuario']) && isset($_POST['apellido_materno_usuario']) && isset($_POST['id_institucion']) && isset($_POST['id_rol']) && isset($_POST['login_usuario']) && isset($_POST['password_usuario']) && isset($_POST['password_usuario_confirmacion'])) {
            $this->form_validation->set_rules('nombre_usuario', 'nombre_usuario', 'required|trim|min_length[1]|max_length[64]');
            $this->form_validation->set_rules('apellido_paterno_usuario', 'apellido_paterno_usuario', 'required|trim|min_length[1]|max_length[32]');
            $this->form_validation->set_rules('apellido_materno_usuario', 'apellido_materno_usuario', 'trim|min_length[1]|max_length[32]');
            $this->form_validation->set_rules('id_institucion', 'id_institucion', 'required|numeric');
            $this->form_validation->set_rules('id_rol', 'id_rol', 'required|numeric');
            $this->form_validation->set_rules('login_usuario', 'login_usuario', 'required|trim|min_length[5]|max_length[32]');
            $this->form_validation->set_rules('password_usuario', 'password_usuario', 'required|trim|min_length[5]|max_length[32]');
            $this->form_validation->set_rules('password_usuario_confirmacion', 'password_usuario_confirmacion', 'required|trim|min_length[5]|max_length[32]');
            if (isset($_POST['telefono_usuario'])) {
                $this->form_validation->set_rules('telefono_usuario', 'telefono_usuario', 'numeric');
            }
            if (isset($_POST['correo_usuario'])) {
                $this->form_validation->set_rules('correo_usuario', 'correo_usuario', 'trim|valid_email|min_length[5]|max_length[64]');
            }
            if ($this->form_validation->run() == FALSE || !($this->input->post('password_usuario') == $this->input->post('password_usuario_confirmacion'))) {
                unset($_POST['password_usuario']);
                unset($_POST['password_usuario_confirmacion']);
                $this->nuevo_usuario();
            } else {
                $id_institucion = $this->input->post('id_institucion');
                $id_rol = $this->input->post('id_rol');
                $nombre_usuario = $this->input->post('nombre_usuario');
                $apellido_paterno_usuario = $this->input->post('apellido_paterno_usuario');
                $apellido_materno_usuario = $this->input->post('apellido_materno_usuario');
                $login_usuario = $this->input->post('login_usuario');
                $password_usuario = sha1($this->input->post('password_usuario'));
                $telefono_usuario = $this->input->post('telefono_usuario');
                if ($telefono_usuario == "") {
                    $telefono_usuario = 0;
                }
                $correo_usuario = $this->input->post('correo_usuario');
                $this->modelo_administrador->insert_usuario($id_institucion, $id_rol, $nombre_usuario, $apellido_paterno_usuario, $apellido_materno_usuario, $login_usuario, $password_usuario, $telefono_usuario, $correo_usuario);
                redirect(base_url() . 'administrador/usuarios');
            }
        } else {
            $datos = Array();
            $datos['instituciones'] = $this->modelo_administrador->get_instituciones();
            $datos['roles'] = $this->modelo_administrador->get_roles();
            $this->load->view('administrador/vista_registrar_nuevo_usuario', $datos);
        }
    }

    public function modificar_usuario($id_usuario) {
        $this->verificar_sesion();

        if (isset($_POST['id_usuario']) && isset($_POST['nombre_usuario']) && isset($_POST['apellido_paterno_usuario']) && isset($_POST['apellido_materno_usuario']) && isset($_POST['id_institucion']) && isset($_POST['id_rol']) && isset($_POST['login_usuario'])) {
            $this->form_validation->set_rules('nombre_usuario', 'nombre_usuario', 'required|trim|min_length[1]|max_length[64]');
            $this->form_validation->set_rules('apellido_paterno_usuario', 'apellido_paterno_usuario', 'required|trim|min_length[1]|max_length[32]');
            $this->form_validation->set_rules('apellido_materno_usuario', 'apellido_materno_usuario', 'trim|min_length[1]|max_length[32]');
            $this->form_validation->set_rules('id_institucion', 'id_institucion', 'required|numeric');
            $this->form_validation->set_rules('id_rol', 'id_rol', 'required|numeric');
            $this->form_validation->set_rules('login_usuario', 'login_usuario', 'required|trim|min_length[5]|max_length[32]');
            if (isset($_POST['telefono_usuario'])) {
                $this->form_validation->set_rules('telefono_usuario', 'telefono_usuario', 'numeric');
            }
            if (isset($_POST['correo_usuario'])) {
                $this->form_validation->set_rules('correo_usuario', 'correo_usuario', 'trim|valid_email|min_length[5]|max_length[64]');
            }
            if ($id_usuario != $this->input->post('id_usuario')) {
                redirect(base_url() . 'administrador');
            }
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_usuario']);
                $this->modificar_usuario($id_usuario);
            } else {
                $id_usuario = $this->input->post('id_usuario');
                $id_institucion = $this->input->post('id_institucion');
                $id_rol = $this->input->post('id_rol');
                $nombre_usuario = $this->input->post('nombre_usuario');
                $apellido_paterno_usuario = $this->input->post('apellido_paterno_usuario');
                $apellido_materno_usuario = $this->input->post('apellido_materno_usuario');
                $login_usuario = $this->input->post('login_usuario');
                $telefono_usuario = $this->input->post('telefono_usuario');
                if ($telefono_usuario == "") {
                    $telefono_usuario = 0;
                }
                $correo_usuario = $this->input->post('correo_usuario');
                $this->modelo_administrador->update_usuario($id_usuario, $id_institucion, $id_rol, $nombre_usuario, $apellido_paterno_usuario, $apellido_materno_usuario, $login_usuario, $telefono_usuario, $correo_usuario);
                redirect(base_url() . 'administrador/usuarios');
            }
        } else {
            $datos = Array();
            $datos['usuario'] = $this->modelo_administrador->get_usuario($id_usuario);
            $datos['instituciones'] = $this->modelo_administrador->get_instituciones();
            $datos['roles'] = $this->modelo_administrador->get_roles();
            $this->load->view('administrador/vista_modificar_usuario', $datos);
        }
    }

    public function activar_usuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            redirect(base_url() . 'administrador');
        } else {
            $this->modelo_administrador->activar_usuario($id_usuario);
            redirect(base_url() . 'administrador/usuarios');
        }
    }

    public function desactivar_usuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            redirect(base_url() . 'administrador');
        } else {
            $this->modelo_administrador->desactivar_usuario($id_usuario);
            redirect(base_url() . 'administrador/usuarios');
        }
    }

    public function existe_correo_usuario_ajax() {
        if ($this->input->is_ajax_request() && isset($_POST['correo_usuario'])) {
            $existe = false;
            if (isset($_POST['id_usuario'])) {
                $existe = $this->modelo_administrador->existe_correo_usuario_con_id($_POST['id_usuario'], $_POST['correo_usuario']);
            } else {
                $existe = $this->modelo_administrador->existe_correo_usuario($_POST['correo_usuario']);
            }
            if ($existe) {
                echo('false');
            } else {
                echo('true');
            }
        } else {
            echo('true');
        }
    }

    public function existe_login_usuario_ajax() {
        if ($this->input->is_ajax_request() && isset($_POST['login_usuario'])) {
            $existe = false;
            if (isset($_POST['id_usuario'])) {
                $existe = $this->modelo_administrador->existe_login_usuario_con_id($_POST['id_usuario'], $_POST['login_usuario']);
            } else {
                $existe = $this->modelo_administrador->existe_login_usuario($_POST['login_usuario']);
            }
            if ($existe) {
                echo('false');
            } else {
                echo('true');
            }
        } else {
            echo('true');
        }
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
                $password_antiguo_verificado = $this->modelo_administrador->verificar_password($id_usuario, $password_antiguo);
                if($password_nuevo == $password_confirmacion && $password_antiguo_verificado) {
                    $password_nuevo = sha1($password_nuevo);
                    $this->modelo_administrador->update_password_usuario($id_usuario, $password_nuevo);
                    redirect(base_url() . 'administrador');
                } else {
                    if(!$password_antiguo_verificado) {
                        $this->session->set_flashdata('error_password_antiguo', 'El password introducido no coincide con su password actual.');
                    } else {
                        $this->session->set_flashdata('error_password_confirmacion', 'El password introducido no coincide con el nuevo password.');
                    }
                    redirect(base_url() . 'administrador/modificar_password/' . $id_usuario, 'refresh');
                }
            }
        } else {
            $datos = Array();
            $datos['id_usuario'] = $id_usuario;
            $datos['usuario'] = $this->modelo_administrador->get_usuario($id_usuario);
            $this->load->view('administrador/vista_modificar_password', $datos);
        }
    }
    
    public function modificar_password_usuario($id_usuario) {
        $this->verificar_sesion();
        
        if(isset($_POST['password_nuevo']) && isset($_POST['password_confirmacion'])) {
            $this->form_validation->set_rules('password_nuevo', 'password_nuevo', 'required|trim|min_length[5]|max_length[32]');
            $this->form_validation->set_rules('password_confirmacion', 'password_confirmacion', 'required|trim|min_length[5]|max_length[32]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['password_nuevo']);
                $this->modificar_password_usuario($id_usuario);
            } else {
                $password_nuevo = $this->input->post('password_nuevo');
                $password_confirmacion = $this->input->post('password_confirmacion');
                if($password_nuevo == $password_confirmacion) {
                    $password_nuevo = sha1($password_nuevo);
                    $this->modelo_administrador->update_password_usuario($id_usuario, $password_nuevo);
                    redirect(base_url() . 'administrador');
                } else {
                    $this->session->set_flashdata('error_password_confirmacion', 'El password introducido no coincide con el nuevo password.');
                    redirect(base_url() . 'administrador/modificar_password_usuario/' . $id_usuario, 'refresh');
                }
            }
        } else {
            $datos = Array();
            $datos['id_usuario'] = $id_usuario;
            $datos['usuario'] = $this->modelo_administrador->get_usuario($id_usuario);
            $this->load->view('administrador/vista_modificar_password_usuario', $datos);
        }
    }

    public function instituciones() {
        $this->verificar_sesion();

        $datos['instituciones'] = $this->modelo_administrador->get_instituciones();
        $this->load->view('administrador/vista_instituciones', $datos);
    }

    public function nueva_institucion() {
        $this->verificar_sesion();

        if (isset($_POST['nombre_institucion']) && isset($_POST['sigla_institucion'])) {
            $this->form_validation->set_rules('nombre_institucion', 'nombre_institucion', 'required|trim|min_length[2]|max_length[128]');
            $this->form_validation->set_rules('sigla_institucion', 'sigla_institucion', 'required|trim|alpha|min_length[2]|max_length[8]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['nombre_institucion']);
                $this->nueva_institucion();
            } else {
                $nombre_institucion = $this->input->post('nombre_institucion');
                $sigla_institucion = $this->input->post('sigla_institucion');
                $carpeta_institucion = strtolower($sigla_institucion);
                $this->modelo_administrador->insert_institucion($nombre_institucion, $sigla_institucion, $carpeta_institucion);
                redirect(base_url() . 'administrador/instituciones');
            }
        } else {
            $this->load->view('administrador/vista_registrar_nueva_institucion');
        }
    }

    public function modificar_institucion($id_institucion) {
        $this->verificar_sesion();

        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'administrador');
        } else {
            if (isset($_POST['id_institucion']) && isset($_POST['nombre_institucion']) && isset($_POST['sigla_institucion'])) {
                $this->form_validation->set_rules('id_institucion', 'id_institucion', 'required|numeric');
                $this->form_validation->set_rules('nombre_institucion', 'nombre_institucion', 'required|trim|min_length[3]|max_length[128]');
                $this->form_validation->set_rules('sigla_institucion', 'sigla_institucion', 'required|trim|alpha|min_length[3]|max_length[8]');
                if ($this->form_validation->run() == FALSE) {
                    unset($_POST['id_institucion']);
                    $this->modificar_institucion($id_institucion);
                } else {
                    if ($id_institucion == $this->input->post('id_institucion')) {
                        $id_institucion = $this->input->post('id_institucion');
                        $nombre_institucion = $this->input->post('nombre_institucion');
                        $sigla_institucion = $this->input->post('sigla_institucion');
                        $this->modelo_administrador->update_institucion($id_institucion, $nombre_institucion, $sigla_institucion);
                        redirect(base_url() . 'administrador/instituciones');
                    } else {
                        redirect(base_url() . 'administrador');
                    }
                }
            } else {
                $datos['institucion'] = $this->modelo_administrador->get_institucion($id_institucion);
                $this->load->view('administrador/vista_modificar_institucion', $datos);
            }
        }
    }

    public function activar_institucion($id_institucion) {
        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'administrador');
        } else {
            $this->modelo_administrador->activar_institucion($id_institucion);
            redirect(base_url() . 'administrador/instituciones');
        }
    }

    public function desactivar_institucion($id_institucion) {
        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'administrador');
        } else {
            $this->modelo_administrador->desactivar_institucion($id_institucion);
            redirect(base_url() . 'administrador/instituciones');
        }
    }

    public function existe_nombre_institucion_ajax() {
        if ($this->input->is_ajax_request() && isset($_POST['nombre_institucion'])) {
            $existe = false;
            if (isset($_POST['id_institucion'])) {
                $existe = $this->modelo_administrador->existe_nombre_institucion_con_id($_POST['id_institucion'], $_POST['nombre_institucion']);
            } else {
                $existe = $this->modelo_administrador->existe_nombre_institucion($_POST['nombre_institucion']);
            }
            if ($existe) {
                echo('false');
            } else {
                echo('true');
            }
        } else {
            echo('true');
        }
    }

    public function existe_sigla_institucion_ajax() {
        if ($this->input->is_ajax_request() && $_POST['sigla_institucion']) {
            $existe = false;
            if (isset($_POST['id_institucion'])) {
                $existe = $this->modelo_administrador->existe_sigla_institucion_con_id($_POST['id_institucion'], $_POST['sigla_institucion']);
            } else {
                $existe = $this->modelo_administrador->existe_sigla_institucion($_POST['sigla_institucion']);
            }
            if ($existe) {
                echo('false');
            } else {
                echo('true');
            }
        }
    }

    public function error() {
        $this->verificar_sesion();

        $this->load->view('vista_error');
    }

}
