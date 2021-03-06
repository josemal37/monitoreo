<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of coordinador
 *
 * @author Jose
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Coordinador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(Array('modelo_coordinador', 'modelo_indicador', 'modelo_indicador_cualitativo', 'modelo_indicador_cuantitativo', 'modelo_indicador_acumulativo', 'modelo_indicador_promedio_menor_que', 'modelo_indicador_porcentaje'));
        $this->load->library(array('session', 'form_validation', 'encrypt'));
        $this->load->helper(array('url', 'form', 'download'));
        $this->load->database('default');
    }

    public function index() {
        $this->ver_proyectos();
    }

    private function verificar_sesion() {
        if ($this->session->userdata('nombre_rol') == FALSE || $this->session->userdata('nombre_rol') != 'coordinador') {
            redirect(base_url() . 'login');
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
                $password_antiguo_verificado = $this->modelo_coordinador->verificar_password($id_usuario, $password_antiguo);
                if($password_nuevo == $password_confirmacion && $password_antiguo_verificado) {
                    $password_nuevo = sha1($password_nuevo);
                    $this->modelo_coordinador->update_password_usuario($id_usuario, $password_nuevo);
                    redirect(base_url() . 'coordinador');
                } else {
                    if(!$password_antiguo_verificado) {
                        $this->session->set_flashdata('error_password_antiguo', 'El password introducido no coincide con su password actual.');
                    } else {
                        $this->session->set_flashdata('error_password_confirmacion', 'El password introducido no coincide con el nuevo password.');
                    }
                    redirect(base_url() . 'coordinador/modificar_password/' . $id_usuario, 'refresh');
                }
            }
        } else {
            $datos = Array();
            $datos['id_usuario'] = $id_usuario;
            $datos['usuario'] = $this->modelo_coordinador->get_usuario($id_usuario);
            $this->load->view('coordinador/vista_modificar_password', $datos);
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
                $this->modelo_coordinador->update_datos_contacto_usuario($id_usuario, $telefono_usuario, $correo_usuario);
                $this->session->set_userdata('telefono_usuario', $telefono_usuario);
                $this->session->set_userdata('correo_usuario', $correo_usuario);
                redirect(base_url() . 'coordinador');
                
            }
        } else {
            $datos = Array();
            $datos['id_usuario'] = $id_usuario;
            $datos['usuario'] = $this->modelo_coordinador->get_usuario($id_usuario);
            $this->load->view('coordinador/vista_modificar_datos_contacto', $datos);
        }
    }

    public function ver_prodoc($id_prodoc) {
        $this->verificar_sesion();

        $datos = Array();
        $datos['prodoc'] = $this->modelo_coordinador->get_prodoc_completo($id_prodoc);
        $this->load->view('coordinador/vista_prodoc', $datos);
    }

    public function editar_prodoc($id_prodoc) {
        $this->verificar_sesion();

        $datos = Array();
        $datos['prodoc'] = $this->modelo_coordinador->get_prodoc_completo($id_prodoc);
        $this->load->view('coordinador/vista_editar_prodoc', $datos);
    }

    public function registrar_prodoc() {
        $this->verificar_sesion();

        if (isset($_POST['nombre_prodoc']) && isset($_POST['descripcion_prodoc']) && isset($_POST['objetivo_global_prodoc']) && isset($_POST['objetivo_proyecto_prodoc'])) {
            $this->form_validation->set_rules('nombre_prodoc', 'nombre_prodoc', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('descripcion_prodoc', 'descripcion_prodoc', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('objetivo_global_prodoc', 'objetivo_global_prodoc', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('objetivo_proyecto_prodoc', 'objetivo_proyecto_prodoc', 'required|trim|min_length[5]|max_length[1024]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['nombre_prodoc']);
                $this->registrar_prodoc();
            } else {
                $nombre_prodoc = $this->input->post('nombre_prodoc');
                $descripcion_prodoc = $this->input->post('descripcion_prodoc');
                $objetivo_global_prodoc = $this->input->post('objetivo_global_prodoc');
                $objetivo_proyecto_prodoc = $this->input->post('objetivo_proyecto_prodoc');
                $id_prodoc = $this->modelo_coordinador->insert_prodoc($nombre_prodoc, $descripcion_prodoc, $objetivo_global_prodoc, $objetivo_proyecto_prodoc);
                redirect(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc);
            }
        } else {
            $datos = Array();
            $id_prodoc = $this->modelo_coordinador->get_id_prodoc();
            if (!$id_prodoc) {
                $this->load->view('coordinador/vista_registrar_prodoc', $datos);
            } else {
                $this->session->set_flashdata('advertencia_existe_prodoc', 'Usted ya tiene registrado un PRODOC en el sistema.');
                redirect(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc, 'refresh');
            }
        }
    }

    public function modificar_prodoc($id_prodoc) {
        $this->verificar_sesion();

        if (isset($_POST['id_prodoc']) && isset($_POST['nombre_prodoc']) && isset($_POST['descripcion_prodoc']) && isset($_POST['objetivo_global_prodoc']) && isset($_POST['objetivo_proyecto_prodoc'])) {
            $this->form_validation->set_rules('id_prodoc', 'id_prodoc', 'required|numeric');
            $this->form_validation->set_rules('nombre_prodoc', 'nombre_prodoc', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('descripcion_prodoc', 'descripcion_prodoc', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('objetivo_global_prodoc', 'objetivo_global_prodoc', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('objetivo_proyecto_prodoc', 'objetivo_proyecto_prodoc', 'required|trim|min_length[5]|max_length[1024]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_prodoc']);
                $this->modificar_prodoc($id_prodoc);
            } else {
                $nombre_prodoc = $this->input->post('nombre_prodoc');
                $descripcion_prodoc = $this->input->post('descripcion_prodoc');
                $objetivo_global_prodoc = $this->input->post('objetivo_global_prodoc');
                $objetivo_proyecto_prodoc = $this->input->post('objetivo_proyecto_prodoc');
                $this->modelo_coordinador->update_prodoc($id_prodoc, $nombre_prodoc, $descripcion_prodoc, $objetivo_global_prodoc, $objetivo_proyecto_prodoc);
                redirect(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc);
            }
        } else {
            $datos = Array();
            $datos['id_prodoc'] = $id_prodoc;
            $datos['prodoc'] = $this->modelo_coordinador->get_prodoc($id_prodoc);
            $this->load->view('coordinador/vista_modificar_prodoc', $datos);
        }
    }

    public function registrar_efecto($id_prodoc) {
        $this->verificar_sesion();

        if (isset($_POST['nombre_efecto']) && isset($_POST['descripcion_efecto'])) {
            $this->form_validation->set_rules('nombre_efecto', 'nombre_efecto', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('descripcion_efecto', 'descripcion_efecto', 'required|trim|min_length[5]|max_length[1024]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['nombre_efecto']);
                $this->registrar_efecto($id_prodoc);
            } else {
                $nombre_efecto = $this->input->post('nombre_efecto');
                $descripcion_efecto = $this->input->post('descripcion_efecto');
                $this->modelo_coordinador->insert_efecto($id_prodoc, $nombre_efecto, $descripcion_efecto);
                redirect(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc);
            }
        } else {
            $datos = Array();
            $datos['id_prodoc'] = $id_prodoc;
            $datos['prodoc'] = $this->modelo_coordinador->get_prodoc($id_prodoc);
            $this->load->view('coordinador/vista_registrar_efecto', $datos);
        }
    }

    public function modificar_efecto($id_prodoc, $id_efecto) {
        $this->verificar_sesion();

        if (isset($_POST['id_efecto']) && isset($_POST['nombre_efecto']) && isset($_POST['descripcion_efecto'])) {
            $this->form_validation->set_rules('id_efecto', 'id_efecto', 'required|numeric');
            $this->form_validation->set_rules('nombre_efecto', 'nombre_efecto', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('descripcion_efecto', 'descripcion_efecto', 'required|trim|min_length[5]|max_length[1024]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_efecto']);
                $this->registrar_efecto($id_prodoc);
            } else {
                $nombre_efecto = $this->input->post('nombre_efecto');
                $descripcion_efecto = $this->input->post('descripcion_efecto');
                $this->modelo_coordinador->update_efecto($id_efecto, $nombre_efecto, $descripcion_efecto);
                redirect(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc);
            }
        } else {
            $datos = Array();
            $datos['id_prodoc'] = $id_prodoc;
            $datos['prodoc'] = $this->modelo_coordinador->get_prodoc($id_prodoc);
            $datos['efecto'] = $this->modelo_coordinador->get_efecto($id_efecto);
            $this->load->view('coordinador/vista_modificar_efecto', $datos);
        }
    }

    public function registrar_producto($id_prodoc, $id_efecto) {
        $this->verificar_sesion();

        if (isset($_POST['id_efecto']) && isset($_POST['nombre_producto']) && isset($_POST['descripcion_producto'])) {
            $this->form_validation->set_rules('id_efecto', 'id_efecto', 'required|numeric');
            $this->form_validation->set_rules('nombre_producto', 'nombre_producto', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('descripcion_producto', 'descripcion_producto', 'required|trim|min_length[5]|max_length[1024]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_efecto']);
                $this->registrar_producto($id_prodoc, $id_efecto);
            } else {
                $nombre_producto = $this->input->post('nombre_producto');
                $descripcion_producto = $this->input->post('descripcion_producto');
                $this->modelo_coordinador->insert_producto($id_efecto, $nombre_producto, $descripcion_producto);
                redirect(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc);
            }
        } else {
            $datos = Array();
            $datos['efecto'] = $this->modelo_coordinador->get_efecto($id_efecto);
            $datos['id_prodoc'] = $id_prodoc;
            $datos['id_efecto'] = $id_efecto;
            $this->load->view('coordinador/vista_registrar_producto', $datos);
        }
    }

    public function modificar_producto($id_prodoc, $id_producto) {
        $this->verificar_sesion();

        if (isset($_POST['id_producto']) && isset($_POST['nombre_producto']) && isset($_POST['descripcion_producto'])) {
            $this->form_validation->set_rules('id_producto', 'id_producto', 'required|numeric');
            $this->form_validation->set_rules('nombre_producto', 'nombre_producto', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('descripcion_producto', 'descripcion_producto', 'required|trim|min_length[5]|max_length[1024]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_producto']);
                $this->registrar_producto($id_prodoc, $id_producto);
            } else {
                $nombre_producto = $this->input->post('nombre_producto');
                $descripcion_producto = $this->input->post('descripcion_producto');
                $this->modelo_coordinador->update_producto($id_producto, $nombre_producto, $descripcion_producto);
                redirect(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc);
            }
        } else {
            $datos = Array();
            $datos['producto'] = $this->modelo_coordinador->get_producto($id_producto);
            $datos['efecto'] = $this->modelo_coordinador->get_efecto($datos['producto']->id_efecto);
            $datos['id_prodoc'] = $id_prodoc;
            $this->load->view('coordinador/vista_modificar_producto', $datos);
        }
    }

    public function registrar_meta_producto_cuantitativa($id_prodoc, $id_producto) {
        $this->verificar_sesion();

        if (isset($_POST['id_producto']) && isset($_POST['cantidad_meta_producto_cuantitativa']) && isset($_POST['unidad_meta_producto_cuantitativa']) && isset($_POST['nombre_meta_producto_cuantitativa']) && isset($_POST['descripcion_meta_producto_cuantitativa'])) {
            $this->form_validation->set_rules('id_producto', 'id_producto', 'required|numeric');
            $this->form_validation->set_rules('cantidad_meta_producto_cuantitativa', 'cantidad_meta_producto_cuantitativa', 'required|numeric');
            $this->form_validation->set_rules('unidad_meta_producto_cuantitativa', 'unidad_meta_producto_cuantitativa', 'required|trim|min_length[5]|max_length[128]');
            $this->form_validation->set_rules('nombre_meta_producto_cuantitativa', 'nombre_meta_producto_cuantitativa', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('descripcion_meta_producto_cuantitativa', 'descripcion_meta_producto_cuantitativa', 'required|trim|min_length[5]|max_length[1024]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_producto']);
                $this->registrar_meta_cuantitativa($id_prodoc, $id_producto);
            } else {
                $cantidad_meta_producto_cuantitativa = $this->input->post('cantidad_meta_producto_cuantitativa');
                $unidad_meta_producto_cuantitativa = $this->input->post('unidad_meta_producto_cuantitativa');
                $nombre_meta_producto_cuantitativa = $this->input->post('nombre_meta_producto_cuantitativa');
                $descripcion_meta_producto_cuantitativa = $this->input->post('descripcion_meta_producto_cuantitativa');
                $this->modelo_coordinador->insert_meta_producto_cuantitativa($id_producto, $nombre_meta_producto_cuantitativa, $descripcion_meta_producto_cuantitativa, $cantidad_meta_producto_cuantitativa, $unidad_meta_producto_cuantitativa);
                redirect(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc);
            }
        } else {
            $datos = Array();
            $datos['id_prodoc'] = $id_prodoc;
            $datos['id_producto'] = $id_producto;
            $datos['producto'] = $this->modelo_coordinador->get_producto($id_producto);
            $this->load->view('coordinador/vista_registrar_meta_producto_cuantitativa', $datos);
        }
    }

    public function modificar_meta_producto_cuantitativa($id_prodoc, $id_meta_producto_cuantitativa) {
        $this->verificar_sesion();

        if (isset($_POST['id_meta_producto_cuantitativa']) && isset($_POST['cantidad_meta_producto_cuantitativa']) && isset($_POST['unidad_meta_producto_cuantitativa']) && isset($_POST['nombre_meta_producto_cuantitativa']) && isset($_POST['descripcion_meta_producto_cuantitativa'])) {
            $this->form_validation->set_rules('id_meta_producto_cuantitativa', 'id_meta_producto_cuantitativa', 'required|numeric');
            $this->form_validation->set_rules('cantidad_meta_producto_cuantitativa', 'cantidad_meta_producto_cuantitativa', 'required|numeric');
            $this->form_validation->set_rules('unidad_meta_producto_cuantitativa', 'unidad_meta_producto_cuantitativa', 'required|trim|min_length[5]|max_length[128]');
            $this->form_validation->set_rules('nombre_meta_producto_cuantitativa', 'nombre_meta_producto_cuantitativa', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('descripcion_meta_producto_cuantitativa', 'descripcion_meta_producto_cuantitativa', 'required|trim|min_length[5]|max_length[1024]');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_meta_producto_cuantitativa']);
                $this->registrar_meta_cuantitativa($id_prodoc, $id_meta_producto_cuantitativa);
            } else {
                $cantidad_meta_producto_cuantitativa = $this->input->post('cantidad_meta_producto_cuantitativa');
                $unidad_meta_producto_cuantitativa = $this->input->post('unidad_meta_producto_cuantitativa');
                $nombre_meta_producto_cuantitativa = $this->input->post('nombre_meta_producto_cuantitativa');
                $descripcion_meta_producto_cuantitativa = $this->input->post('descripcion_meta_producto_cuantitativa');
                $this->modelo_coordinador->update_meta_producto_cuantitativa($id_meta_producto_cuantitativa, $nombre_meta_producto_cuantitativa, $descripcion_meta_producto_cuantitativa, $cantidad_meta_producto_cuantitativa, $unidad_meta_producto_cuantitativa);
                redirect(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc);
            }
        } else {
            $datos = Array();
            $datos['id_prodoc'] = $id_prodoc;
            $datos['id_meta_producto_cuantitativa'] = $id_meta_producto_cuantitativa;
            $datos['meta_producto_cuantitativa'] = $this->modelo_coordinador->get_meta_producto_cuantitativa($id_meta_producto_cuantitativa);
            $datos['producto'] = $this->modelo_coordinador->get_producto($datos['meta_producto_cuantitativa']->id_producto);
            $this->load->view('coordinador/vista_modificar_meta_producto_cuantitativa', $datos);
        }
    }

    public function ver_proyectos() {
        $this->verificar_sesion();

        $datos['proyectos'] = $this->modelo_coordinador->get_proyectos();
        $this->load->view('coordinador/vista_proyectos', $datos);
    }

    public function registrar_proyecto() {
        $this->verificar_sesion();

        if (isset($_POST['nombre_proyecto']) && isset($_POST['descripcion_proyecto']) && isset($_POST['presupuesto_proyecto']) && isset($_POST['id_institucion'])) {
            $this->form_validation->set_rules('nombre_proyecto', 'nombre_proyecto', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('presupuesto_proyecto', 'presupuesto_proyecto', 'required|numeric');
            $this->form_validation->set_rules('descripcion_proyecto', 'descripcion_proyecto', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('id_institucion', 'id_institucion', 'required|numeric');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['nombre_proyecto']);
                $this->registrar_proyecto();
            } else {
                $nombre_proyecto = $this->input->post('nombre_proyecto');
                $descripcion_proyecto = $this->input->post('descripcion_proyecto');
                $presupuesto_proyecto = $this->input->post('presupuesto_proyecto');
                $id_institucion = $this->input->post('id_institucion');
                $proyecto = $this->modelo_coordinador->get_proyecto_global_institucion($id_institucion, 0);
                if(!$proyecto) {
                    $this->modelo_coordinador->insert_proyecto($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_institucion);
                    redirect(base_url() . 'coordinador/ver_proyectos');
                } else {
                    $this->session->set_flashdata('error_proyecto_global', 'La institución que seleccionó anteriormente ya tiene registrado un proyecto.');
                    redirect(base_url() . 'coordinador/registrar_proyecto', 'refresh');
                }
                
            }
        } else {
            $datos = Array();
            $datos['instituciones'] = $this->modelo_coordinador->get_instituciones();
            $this->load->view('coordinador/vista_registrar_proyecto', $datos);
        }
    }

    public function modificar_proyecto($id_proyecto) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'coordinador/error');
        }
        if (isset($_POST['id_proyecto']) && isset($_POST['nombre_proyecto']) && isset($_POST['descripcion_proyecto']) && isset($_POST['presupuesto_proyecto']) && isset($_POST['id_institucion'])) {
            $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric');
            $this->form_validation->set_rules('nombre_proyecto', 'nombre_proyecto', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('presupuesto_proyecto', 'presupuesto_proyecto', 'required|numeric');
            $this->form_validation->set_rules('descripcion_proyecto', 'descripcion_proyecto', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('id_institucion', 'id_institucion', 'required|numeric');
            if ($this->form_validation->run() == FALSE || $id_proyecto != $this->input->post('id_proyecto')) {
                unset($_POST['nombre_proyecto']);
                $this->registrar_proyecto();
            } else {
                $nombre_proyecto = $this->input->post('nombre_proyecto');
                $descripcion_proyecto = $this->input->post('descripcion_proyecto');
                $presupuesto_proyecto = $this->input->post('presupuesto_proyecto');
                $id_institucion = $this->input->post('id_institucion');
                $id_institucion_antiguo = $this->input->post('id_institucion_antiguo');
                $proyecto = $this->modelo_coordinador->get_proyecto_global_institucion($id_institucion, $id_institucion_antiguo);
                if(!$proyecto) {
                    $this->modelo_coordinador->update_proyecto($id_proyecto, $nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_institucion);
                    redirect(base_url() . 'coordinador/ver_proyectos');
                } else {
                    $this->session->set_flashdata('error_proyecto_global', 'La institución que seleccionó anteriormente ya tiene registrado un proyecto.');
                    redirect(base_url() . 'coordinador/modificar_proyecto/' . $id_proyecto, 'refresh');
                }
            }
        } else {
            $datos = Array();
            $datos['instituciones'] = $this->modelo_coordinador->get_instituciones();
            $datos['proyecto'] = $this->modelo_coordinador->get_proyecto_global($id_proyecto);
            $this->load->view('coordinador/vista_modificar_proyecto', $datos);
        }
    }

    public function eliminar_proyecto($id_proyecto) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            $this->modelo_coordinador->delete_proyecto($id_proyecto);
            redirect(base_url() . 'coordinador/ver_proyectos');
        }
    }

    public function gestion_actual() {
        $this->verificar_sesion();

        $datos = Array();
        $datos['gestion_actual'] = $this->modelo_coordinador->get_gestion_actual();
        $datos['proyectos'] = $this->modelo_coordinador->get_proyectos_activos_gestion_actual();
        $this->load->view('coordinador/vista_gestion_actual', $datos);
    }

    public function gestiones_registradas() {
        $this->verificar_sesion();

        $datos = Array();
        $datos['anios'] = $this->modelo_coordinador->get_anios();
        $this->load->view('coordinador/vista_gestiones_registradas', $datos);
    }

    public function activar_anio($id_anio) {
        $this->verificar_sesion();

        $this->modelo_coordinador->activar_anio($id_anio);
        redirect(base_url() . 'coordinador/gestiones_registradas');
    }

    public function habilitar_registro_poa_gestion() {
        $this->verificar_sesion();
        if (isset($_POST['valor_anio'])) {
            $this->form_validation->set_rules('valor_anio', 'valor_anio', 'required|numeric');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['valor_anio']);
                $this->habilitar_registro_poa_gestion();
            } else {
                $valor_anio = $this->input->post('valor_anio');
                $this->modelo_coordinador->insert_anio($valor_anio);
                redirect(base_url() . 'coordinador/gestiones_registradas');
            }
        } else {
            $datos = Array();
            $datos['anios'] = $this->modelo_coordinador->get_anios();
            $this->load->view('coordinador/vista_habilitar_registro_poa_gestion', $datos);
        }
    }

    public function proyectos_activos() {
        $this->verificar_sesion();

        $datos['proyectos'] = $this->modelo_coordinador->get_proyectos_activos();
        $this->load->view('coordinador/vista_proyectos_activos', $datos);
    }

    public function ver_proyecto($id_proyecto) {
        $this->verificar_sesion();
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'coordinador');
        } else {
            $datos = $this->modelo_coordinador->get_proyecto_completo($id_proyecto);
            $this->load->view('coordinador/vista_proyecto', $datos);
        }
    }

    public function ver_proyecto_global($id_proyecto_global) {
        $this->verificar_sesion();
        $datos = Array();
        $datos['proyecto_global'] = $this->modelo_coordinador->get_proyecto_global_completo($id_proyecto_global);
        if ($datos['proyecto_global']) {
            $this->load->view('coordinador/vista_proyecto_global', $datos);
        } else {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function ver_avances_hito_cuantitativo($id_institucion, $id_proyecto, $id_hito) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_institucion)) {
            redirect(base_url() . 'coordinador');
        } else {
            $datos = Array();
            $datos['id_proyecto'] = $id_proyecto;
            $datos['id_hito'] = $id_hito;
            $datos['id_institucion'] = $id_institucion;
            $datos['hito_cuantitativo'] = $this->modelo_coordinador->get_hito_cuantitativo($id_hito);
            $avances = $this->modelo_coordinador->get_avances_hito_cuantitativo($id_hito);
            $datos['avances_hito_cuantitativo'] = $avances['avances_hito_cuantitativo'];
            if (isset($avances['documentos'])) {
                $datos['documentos'] = $avances['documentos'];
            }
            $this->load->view('coordinador/vista_avances_hito_cuantitativo', $datos);
        }
    }

    public function ver_avances_hito_cualitativo($id_institucion, $id_proyecto, $id_hito) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_institucion)) {
            redirect(base_url() . 'coordinador');
        } else {
            $datos = Array();
            $datos['id_proyecto'] = $id_proyecto;
            $datos['id_hito'] = $id_hito;
            $datos['id_institucion'] = $id_institucion;
            $datos['hito_cualitativo'] = $this->modelo_coordinador->get_hito_cualitativo($id_hito);
            $avances = $this->modelo_coordinador->get_avances_hito_cualitativo($id_hito);
            $datos['avances_hito_cualitativo'] = $avances;
            $this->load->view('coordinador/vista_avances_hito_cualitativo', $datos);
        }
    }

    public function registrar_indicador_cuantitativo($id_proyecto, $id_hito) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
        } else {
            if (isset($_POST['id_hito']) && isset($_POST['nombre_indicador']) && isset($_POST['tipo_indicador']) && isset($_POST['aceptable_indicador']) && isset($_POST['limitado_indicador']) && isset($_POST['no_aceptable_indicador'])) {
                $this->form_validation->set_rules('id_hito', 'id_hito', 'required|numeric');
                $this->form_validation->set_rules('nombre_indicador', 'nombre_indicador', 'required|trim|min_length[5]|max_length[1024]');
                $this->form_validation->set_rules('tipo_indicador', 'tipo_indicador', 'required|numeric');
                $this->form_validation->set_rules('aceptable_indicador', 'aceptable_indicador', 'required|numeric');
                $this->form_validation->set_rules('limitado_indicador', 'limitado_indicador', 'required|numeric');
                $this->form_validation->set_rules('no_aceptable_indicador', 'no_aceptable_indicador', 'required|numeric');
                if ($this->form_validation->run() == FALSE || $id_hito != $this->input->post('id_hito')) {
                    unset($_POST['id_hito']);
                    $this->registrar_indicador_cuantitativo($id_proyecto, $id_hito);
                } else {
                    $nombre_indicador = $this->input->post('nombre_indicador');
                    $tipo_indicador = $this->input->post('tipo_indicador');
                    $aceptable_indicador = $this->input->post('aceptable_indicador');
                    $limitado_indicador = $this->input->post('limitado_indicador');
                    $no_aceptable_indicador = $this->input->post('no_aceptable_indicador');
                    $this->modelo_coordinador->insert_indicador_cuantitativo($id_hito, $nombre_indicador, $tipo_indicador, $aceptable_indicador, $limitado_indicador, $no_aceptable_indicador);
                    redirect(base_url() . 'coordinador/ver_proyecto/' . $id_proyecto);
                }
            } else {
                $datos = Array();
                $datos['id_proyecto'] = $id_proyecto;
                $datos['id_hito'] = $id_hito;
                $datos['hito'] = $this->modelo_coordinador->get_hito_cuantitativo($id_hito);
                $datos['actividad'] = $this->modelo_coordinador->get_actividad($datos['hito']->id_actividad);
                $datos['tipos_indicador_cuantitativo'] = $this->modelo_coordinador->get_tipos_indicador_cuantitativo();
                $this->load->view('coordinador/vista_registrar_indicador_cuantitativo', $datos);
            }
        }
    }

    public function revisar_avance_hito_cuantitativo($id_proyecto, $id_hito, $id_estado_avance) {
        $this->verificar_sesion();

        if (isset($_POST['estado'])) {
            $estado = $this->input->post('estado');
            if ($estado == 'aprobado') {
                $this->aprobar_avance_hito_cuantitativo($id_proyecto, $id_hito, $id_estado_avance);
            } else {
                if ($estado = 'no_aprobado') {
                    $this->no_aprobar_avance_hito_cuantitativo($id_proyecto, $id_hito, $id_estado_avance);
                }
            }
        } else {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function aprobar_avance_hito_cuantitativo($id_proyecto, $id_hito, $id_estado_avance) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_estado_avance)) {
            redirect(base_url() . 'coordinador');
        } else {
            $this->modelo_coordinador->modificar_estado_avance_hito_cuantitativo($id_estado_avance, true);
            redirect(base_url() . 'coordinador/ver_avances_hito_cuantitativo/' . $this->session->userdata('id_institucion') . '/' . $id_proyecto . '/' . $id_hito);
        }
    }

    public function no_aprobar_avance_hito_cuantitativo($id_proyecto, $id_hito, $id_estado_avance) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_estado_avance)) {
            redirect(base_url() . 'coordinador');
        } else {
            $this->modelo_coordinador->modificar_estado_avance_hito_cuantitativo($id_estado_avance, false);
            redirect(base_url() . 'coordinador/ver_avances_hito_cuantitativo/' . $this->session->userdata('id_institucion') . '/' . $id_proyecto . '/' . $id_hito);
        }
    }

    public function revisar_avance_hito_cualitativo($id_proyecto, $id_hito, $id_estado_avance) {
        $this->verificar_sesion();

        if (isset($_POST['estado'])) {
            $estado = $this->input->post('estado');
            if ($estado == 'aprobado') {
                $this->aprobar_avance_hito_cualitativo($id_proyecto, $id_hito, $id_estado_avance);
            } else {
                if ($estado = 'no_aprobado') {
                    $this->no_aprobar_avance_hito_cualitativo($id_proyecto, $id_hito, $id_estado_avance);
                }
            }
        } else {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function aprobar_avance_hito_cualitativo($id_proyecto, $id_hito, $id_estado_avance) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_estado_avance)) {
            redirect(base_url() . 'coordinador');
        } else {
            $this->modelo_coordinador->modificar_estado_avance_hito_cualitativo($id_estado_avance, true);
            redirect(base_url() . 'coordinador/ver_avances_hito_cualitativo/' . $this->session->userdata('id_institucion') . '/' . $id_proyecto . '/' . $id_hito);
        }
    }

    public function no_aprobar_avance_hito_cualitativo($id_proyecto, $id_hito, $id_estado_avance) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_estado_avance)) {
            redirect(base_url() . 'coordinador');
        } else {
            $this->modelo_coordinador->modificar_estado_avance_hito_cualitativo($id_estado_avance, false);
            redirect(base_url() . 'coordinador/ver_avances_hito_cualitativo/' . $this->session->userdata('id_institucion') . '/' . $id_proyecto . '/' . $id_hito);
        }
    }
    
    public function registrar_nueva_actividad($id_proyecto) {
        $this->verificar_sesion();
        
        $this->modelo_coordinador->insert_actividad_en_reformulacion($id_proyecto);
        redirect(base_url() . 'coordinador/ver_proyecto/' . $id_proyecto);
    }
    
    public function activar_reformulacion_actividad($id_proyecto, $id_actividad) {
        $this->verificar_sesion();
        
        $this->modelo_coordinador->modificar_estado_reformulacion_actividad($id_actividad, true);
        redirect(base_url() . 'coordinador/ver_proyecto/' . $id_proyecto);
    }
    
    public function desactivar_reformulacion_actividad($id_proyecto, $id_actividad) {
        $this->verificar_sesion();
        
        $this->modelo_coordinador->modificar_estado_reformulacion_actividad($id_actividad, false);
        redirect(base_url() . 'coordinador/ver_proyecto/' . $id_proyecto);
    }
    
    public function ver_reporte_prodoc() {
        $this->verificar_sesion();
        
        $datos = Array();
        $id_prodoc = $this->modelo_coordinador->get_id_prodoc();
        $datos['prodoc'] = $this->modelo_coordinador->get_prodoc_completo($id_prodoc);
        $this->load->view('coordinador/vista_reporte_prodoc', $datos);
    }
    
    public function ver_reportes_gestion_actual() {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyectos'] = $this->modelo_coordinador->get_proyectos_activos_gestion_actual();
        $this->load->view('coordinador/vista_reporte_proyectos_activos_gestion_actual', $datos);
    }
    
    public function ver_reporte_poa($id_poa) {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyecto'] = $this->modelo_coordinador->get_reporte_proyecto_completo_activo($id_poa);
        if($datos['proyecto']) {
            $this->load->view('coordinador/vista_reporte_proyecto', $datos);
        } else {
            redirect('coordinador/error');
        }
    }
    
    public function ver_reportes_proyectos() {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyectos'] = $this->modelo_coordinador->get_proyectos();
        $this->load->view('coordinador/vista_reportes_proyectos', $datos);
    }
    
    public function ver_reporte_proyecto_global($id_proyecto_global) {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyecto_global'] = $this->modelo_coordinador->get_proyecto_global_completo($id_proyecto_global);
        $this->load->view('coordinador/vista_reporte_proyecto_global', $datos);
    }

    public function error() {
        $this->verificar_sesion();

        $this->load->view('vista_error');
    }

    public function descarga($id_institucion, $nombre) {
        $this->verificar_sesion();

        $institucion = $this->modelo_coordinador->get_institucion($id_institucion);
        $data = file_get_contents('./files/' . $institucion->carpeta_institucion . '/' . $nombre);
        force_download($nombre, $data);
    }

}
