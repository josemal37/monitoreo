<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of socio
 *
 * @author Jose
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Socio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('modelo_socio', 'modelo_indicador', 'modelo_indicador_cualitativo', 'modelo_indicador_cuantitativo', 'modelo_indicador_acumulativo', 'modelo_indicador_promedio_menor_que', 'modelo_indicador_porcentaje'));
        $this->load->library(array('session', 'form_validation', 'encrypt', 'upload'));
        $this->load->helper(array('url', 'form', 'download'));
        $this->load->database('default');
    }

    public function index() {
        $this->inicio_sistema_socio();
    }

    private function verificar_sesion() {
        if ($this->session->userdata('nombre_rol') == FALSE || $this->session->userdata('nombre_rol') != 'socio') {
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
                $password_antiguo_verificado = $this->modelo_socio->verificar_password($id_usuario, $password_antiguo);
                if($password_nuevo == $password_confirmacion && $password_antiguo_verificado) {
                    $password_nuevo = sha1($password_nuevo);
                    $this->modelo_socio->update_password_usuario($id_usuario, $password_nuevo);
                    redirect(base_url() . 'socio');
                } else {
                    if(!$password_antiguo_verificado) {
                        $this->session->set_flashdata('error_password_antiguo', 'El password introducido no coincide con su password actual.');
                    } else {
                        $this->session->set_flashdata('error_password_confirmacion', 'El password introducido no coincide con el nuevo password.');
                    }
                    redirect(base_url() . 'socio/modificar_password/' . $id_usuario, 'refresh');
                }
            }
        } else {
            $datos = Array();
            $datos['id_usuario'] = $id_usuario;
            $datos['usuario'] = $this->modelo_socio->get_usuario($id_usuario);
            $this->load->view('socio/vista_modificar_password', $datos);
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
                $this->modelo_socio->update_datos_contacto_usuario($id_usuario, $telefono_usuario, $correo_usuario);
                $this->session->set_userdata('telefono_usuario', $telefono_usuario);
                $this->session->set_userdata('correo_usuario', $correo_usuario);
                redirect(base_url() . 'socio');
                
            }
        } else {
            $datos = Array();
            $datos['id_usuario'] = $id_usuario;
            $datos['usuario'] = $this->modelo_socio->get_usuario($id_usuario);
            $this->load->view('socio/vista_modificar_datos_contacto', $datos);
        }
    }

    public function inicio_sistema_socio() {
        $datos = Array();
        $datos['proyectos'] = $this->modelo_socio->get_proyectos_socio();
        $datos['proyecto_global'] = $this->modelo_socio->get_proyecto_global($this->session->userdata('id_institucion'));
        $datos['gestion_actual'] = $this->modelo_socio->get_gestion_actual();
        $this->load->view('socio/vista_inicio_sistema_socio', $datos);
    }

    public function ver_prodoc($id_prodoc) {
        $this->verificar_sesion();

        $datos = Array();
        $datos['prodoc'] = $this->modelo_socio->get_prodoc_completo($id_prodoc);
        $this->load->view('socio/vista_prodoc', $datos);
    }

    public function proyectos_activos() {
        $this->verificar_sesion();

        $datos = Array();
        $datos['proyectos'] = $this->modelo_socio->get_proyectos_socio();
        $datos['proyecto_global'] = $this->modelo_socio->get_proyecto_global($this->session->userdata('id_institucion'));
        $this->load->view('socio/vista_proyectos_activos', $datos);
    }

    public function ver_proyecto($id_proyecto) {
        $this->verificar_sesion();

        $datos = $this->modelo_socio->get_proyecto_completo_activo($id_proyecto);
        if(!isset($datos['error'])) {
            $this->load->view('socio/vista_proyecto', $datos);
        } else {
            if($datos['error'] == 'error_proyecto') {
                $this->session->set_flashdata('error_proyecto', 'No tiene registrado un POA activo para la gestión actual.');
                redirect(base_url() . 'socio/inicio_sistema_socio', 'refresh');
            } else {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function ver_proyecto_gestion_actual() {
        $id_proyecto = $this->modelo_socio->get_id_proyecto_completo_gestion_actual();
        if(!$id_proyecto) {
            $this->session->set_flashdata('error_proyecto', 'No tiene registrado un POA activo para la gestión actual.');
            redirect(base_url() . 'socio/inicio_sistema_socio', 'refresh');
        } else {
            redirect(base_url() . 'socio/ver_proyecto/' . $id_proyecto);
        }
    }

    public function proyectos_en_edicion() {
        $this->verificar_sesion();

        $datos['proyectos'] = $this->modelo_socio->get_proyectos_en_edicion();
        $this->load->view('socio/vista_proyectos_en_edicion', $datos);
    }
    
    public function proyectos_en_reformulacion() {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyectos'] = $this->modelo_socio->get_proyectos_en_reformulacion();
        $this->load->view('socio/vista_proyectos_en_reformulacion', $datos);
    }
    
    public function reformular_proyecto($id_proyecto) {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyecto'] = $this->modelo_socio->get_proyecto_completo_en_reformulacion($id_proyecto);
        $this->load->view('socio/vista_proyecto_en_reformulacion', $datos);
    }

    public function terminar_reformulacion_proyecto($id_proyecto) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->terminar_reformulacion_proyecto($id_proyecto);
            redirect(base_url() . 'socio');
        }
    }

    public function registrar_nuevo_proyecto() {
        $this->verificar_sesion();

        if (isset($_POST['nombre_proyecto']) && isset($_POST['presupuesto_proyecto']) && isset($_POST['descripcion_proyecto'])) {
            $this->form_validation->set_rules('nombre_proyecto', 'nombre_proyecto', 'required|trim|min_length[5]|max_length[1024]');
            $this->form_validation->set_rules('presupuesto_proyecto', 'presupuesto_proyecto', 'required|numeric');
            $this->form_validation->set_rules('descripcion_proyecto', 'descripcion_proyecto', 'required|trim|min_length[5]|max_length[1024]');

            if ($this->form_validation->run() == FALSE) {
                unset($_POST['nombre_proyecto']);
                unset($_POST['presupuesto_proyecto']);
                $this->registrar_nuevo_proyecto();
            } else {
                $nombre_proyecto = $this->input->post('nombre_proyecto');
                $descripcion_proyecto = $this->input->post('descripcion_proyecto');
                $presupuesto_proyecto = $this->input->post('presupuesto_proyecto');
                $id_anio = $this->input->post('id_anio');
                $id_proyecto = $this->modelo_socio->insert_proyecto($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_anio);
                //redirect(base_url() . 'socio/proyectos_en_edicion/');
                redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
            }
        } else {
            $datos = Array();
            $datos['proyecto_global'] = $this->modelo_socio->get_proyecto_global($this->session->userdata('id_institucion'));
            if (!$datos['proyecto_global']) {
                $this->session->set_flashdata('error_proyecto_global', 'El sistema no tiene registrado un proyecto para su institución, por favor contacte con el Coordinador del proyecto para solucionar el problema.');
                redirect(base_url() . 'socio/inicio_sistema_socio', 'refresh');
            } else {
                $datos['anios'] = $this->modelo_socio->get_anios();
                if(sizeof($datos['anios']) == 0) {
                    $this->session->set_flashdata('error_proyecto_global', 'El sistema no tiene registrado años de trabajo, por favor contacte con el Coordinador del proyecto para solucionar el problema.');
                    redirect(base_url() . 'socio/inicio_sistema_socio', 'refresh');
                } else {
                    $datos['presupuesto_disponible'] = $this->modelo_socio->get_presupuesto_disponible_institucion($this->session->userdata('id_institucion'));
                    $this->load->view('socio/vista_registrar_nuevo_proyecto', $datos);
                }
            }
        }
    }

    public function editar_proyecto($id_proyecto) {
        $this->verificar_sesion();
        $datos = Array();
        $datos = $this->modelo_socio->get_proyecto_completo_en_edicion($id_proyecto);
        $datos['presupuesto_disponible'] = $this->modelo_socio->get_presupuesto_disponible_proyecto($id_proyecto);
        $this->load->view('socio/vista_editar_proyecto', $datos);
    }

    public function terminar_edicion_proyecto($id_proyecto) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->terminar_edicion_proyecto($id_proyecto);
            redirect(base_url() . 'socio');
        }
    }

    public function registrar_nueva_actividad($id_proyecto) {
        $this->verificar_sesion();

        if (isset($_POST['id_proyecto']) && isset($_POST['nombre_actividad']) && isset($_POST['fecha_inicio_actividad']) && isset($_POST['fecha_fin_actividad']) && isset($_POST['presupuesto_actividad'])) {
            $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric|is_natural');
            $this->form_validation->set_rules('nombre_actividad', 'nombre_actividad', 'required|trim|min_length[2]|max_length[1024]');
            $this->form_validation->set_rules('fecha_inicio_actividad', 'fecha_inicio_actividad', 'required');
            $this->form_validation->set_rules('fecha_fin_actividad', 'fecha_fin_actividad', 'required');
            $this->form_validation->set_rules('descripcion_actividad', 'descripcion_actividad', 'required|trim|min_length[2]|max_length[1024]');
            $this->form_validation->set_rules('presupuesto_actividad', 'presupuesto_actividad', 'required|numeric');
            if(isset($_POST['id_producto'])) {
                $this->form_validation->set_rules('id_producto', 'id_producto', 'numeric|is_natural');
            }
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_proyecto']);
                $this->registrar_nueva_actividad($id_proyecto);
            } else {
                if ($id_proyecto == $this->input->post('id_proyecto')) {
                    $nombre_actividad = $this->input->post('nombre_actividad');
                    $descripcion_actividad = $this->input->post('descripcion_actividad');
                    $fecha_inicio_actividad = $this->input->post('fecha_inicio_actividad');
                    $fecha_fin_actividad = $this->input->post('fecha_fin_actividad');
                    $presupuesto_actividad = $this->input->post('presupuesto_actividad');
                    if(isset($_POST['id_producto'])) {
                        $id_producto = $this->input->post('id_producto');
                    } else {
                        $id_producto = false;
                    }
                    if(isset($_POST['contraparte'])) {
                        $contraparte_actividad = true;
                    } else {
                        $contraparte_actividad = false;
                    }
                    if ($this->comparar_fechas($fecha_inicio_actividad, $fecha_fin_actividad) <= 0) {
                        $this->modelo_socio->insert_actividad($id_proyecto, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad, $id_producto, $contraparte_actividad);
                        redirect(base_url() . 'socio/editar_proyecto/' . $this->input->post('id_proyecto'));
                    } else {
                        //fechas incoherentes
                        unset($_POST['id_proyecto']);
                        $this->registrar_nueva_actividad($id_actividad);
                    }
                } else {
                    redirect(base_url() . 'socio');
                }
            }
        } else {
            $datos = Array();
            $datos['productos'] = $this->modelo_socio->get_productos();
            if (sizeof($datos['productos']) == 0) {
                $this->session->set_flashdata('error_sin_productos', 'El PRODOC no tiene productos registrados actualmente, por favor contacte con el Coordinador del proyecto para solucionar el problema.');
                redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
            } else {
                $datos['id_proyecto'] = $id_proyecto;
                $datos['presupuesto_disponible'] = $this->modelo_socio->get_presupuesto_disponible_proyecto($id_proyecto);
                $this->load->view('socio/vista_registrar_nueva_actividad', $datos);
            }
        }
    }

    public function modificar_proyecto($id_proyecto) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto)) {
            $this->index(); //TODO controlar error
        } else {
            if (isset($_POST['nombre_proyecto']) && isset($_POST['descripcion_proyecto']) && isset($_POST['presupuesto_proyecto']) && isset($_POST['id_proyecto'])) {
                $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric');
                $this->form_validation->set_rules('nombre_proyecto', 'nombre_proyecto', 'required|trim|min_length[5]|max_length[1024]');
                $this->form_validation->set_rules('presupuesto_proyecto', 'presupuesto_proyecto', 'required|numeric');
                $this->form_validation->set_rules('descripcion_proyecto', 'descripcion_proyecto', 'required|trim|min_length[5]|max_length[1024]');
                $this->form_validation->set_rules('id_anio', 'id_anio', 'required|numeric');
                $this->form_validation->set_rules('id_anio_anterior', 'id_anio_anterior', 'required|numeric');
                if ($this->form_validation->run() == FALSE) {
                    unset($_POST['id_proyecto']);
                    $this->modificar_proyecto($id_proyecto);
                } else {
                    if ($id_proyecto == $this->input->post('id_proyecto')) {
                        $id_proyecto = $this->input->post('id_proyecto');
                        $nombre_proyecto = $this->input->post('nombre_proyecto');
                        $descripcion_proyecto = $this->input->post('descripcion_proyecto');
                        $presupuesto_proyecto = $this->input->post('presupuesto_proyecto');
                        $id_anio = $this->input->post('id_anio');
                        $id_anio_anterior = $this->input->post('id_anio_anterior');
                        $this->modelo_socio->update_proyecto($id_proyecto, $nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_anio, $id_anio_anterior);
                        redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
                    } else {
                        redirect(base_url() . 'socio/error');
                    }
                }
            } else {
                $datos = Array();
                $datos['presupuesto_disponible'] = $this->modelo_socio->get_presupuesto_disponible_institucion_con_id($this->session->userdata('id_institucion'), $id_proyecto);
                $datos['presupuesto_actividades'] = $this->modelo_socio->get_suma_presupuestos_actividades_proyecto($id_proyecto);
                $datos['proyecto'] = $this->modelo_socio->get_proyecto($id_proyecto);
                $datos['anios'] = $this->modelo_socio->get_anios();
                $this->load->view('socio/vista_modificar_proyecto', $datos);
            }
        }
    }

    public function modificar_actividad($id_actividad) {
        $this->verificar_sesion();

        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_proyecto']) && isset($_POST['id_actividad']) && isset($_POST['nombre_actividad']) && isset($_POST['descripcion_actividad']) && isset($_POST['fecha_inicio_actividad']) && isset($_POST['fecha_fin_actividad']) && isset($_POST['presupuesto_actividad'])) {
                $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric|is_natural');
                $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric|is_natural');
                $this->form_validation->set_rules('nombre_actividad', 'nombre_actividad', 'required|trim|min_length[5]|max_length[1024]');
                $this->form_validation->set_rules('fecha_inicio_actividad', 'fecha_inicio_actividad', 'required');
                $this->form_validation->set_rules('fecha_fin_actividad', 'fecha_fin_actividad', 'required');
                $this->form_validation->set_rules('descripcion_actividad', 'descripcion_actividad', 'required|trim|min_length[5]|max_length[1024]');
                $this->form_validation->set_rules('presupuesto_actividad', 'presupuesto_actividad', 'required|numeric');
                if(isset($_POST['id_producto'])) {
                    $this->form_validation->set_rules('id_producto', 'id_producto', 'required|numeric|is_natural');
                }
                if ($this->form_validation->run() == FALSE) {
                    unset($_POST['id_actividad']);
                    $this->modificar_actividad($id_actividad);
                } else {
                    if ($id_actividad == $this->input->post('id_actividad')) {
                        $id_actividad = $this->input->post('id_actividad');
                        $nombre_actividad = $this->input->post('nombre_actividad');
                        $descripcion_actividad = $this->input->post('descripcion_actividad');
                        $fecha_inicio_actividad = $this->input->post('fecha_inicio_actividad');
                        $fecha_fin_actividad = $this->input->post('fecha_fin_actividad');
                        $presupuesto_actividad = $this->input->post('presupuesto_actividad');
                        if(isset($_POST['id_producto'])){
                            $id_producto = $this->input->post('id_producto');
                        } else {
                            $id_producto = false;
                        }
                        if(isset($_POST['contraparte'])) {
                            $contraparte_actividad = true;
                        } else {
                            $contraparte_actividad = false;
                        }
                        if ($this->comparar_fechas($fecha_inicio_actividad, $fecha_fin_actividad) <= 0) {
                            $this->modelo_socio->update_actividad($id_actividad, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad, $id_producto, $contraparte_actividad);
                            redirect(base_url() . 'socio/editar_proyecto/' . $this->input->post('id_proyecto'));
                        } else {
                            //fechas incoherentes
                            $this->modificar_actividad($id_actividad);
                        }
                    } else {
                        redirect(base_url() . 'socio');
                    }
                }
            } else {
                $datos = Array();
                $datos['actividad'] = $this->modelo_socio->get_actividad($id_actividad);
                $datos['productos'] = $this->modelo_socio->get_productos();
                $datos['presupuesto_disponible'] = $this->modelo_socio->get_presupuesto_disponible_proyecto_con_id($datos['actividad']->id_proyecto, $id_actividad);
                $this->load->view('socio/vista_modificar_actividad', $datos);
            }
        }
    }

    public function reformular_actividad($id_actividad) {
        $this->verificar_sesion();

        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_proyecto']) && isset($_POST['id_actividad']) && isset($_POST['nombre_actividad']) && isset($_POST['descripcion_actividad']) && isset($_POST['fecha_inicio_actividad']) && isset($_POST['fecha_fin_actividad']) && isset($_POST['presupuesto_actividad'])) {
                $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric|is_natural');
                $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric|is_natural');
                $this->form_validation->set_rules('nombre_actividad', 'nombre_actividad', 'required|trim|min_length[5]|max_length[1024]');
                $this->form_validation->set_rules('fecha_inicio_actividad', 'fecha_inicio_actividad', 'required');
                $this->form_validation->set_rules('fecha_fin_actividad', 'fecha_fin_actividad', 'required');
                $this->form_validation->set_rules('descripcion_actividad', 'descripcion_actividad', 'required|trim|min_length[5]|max_length[1024]');
                $this->form_validation->set_rules('presupuesto_actividad', 'presupuesto_actividad', 'required|numeric');
                if(isset($_POST['id_producto'])) {
                    $this->form_validation->set_rules('id_producto', 'id_producto', 'required|numeric|is_natural');
                }
                if ($this->form_validation->run() == FALSE) {
                    unset($_POST['id_actividad']);
                    $this->reformular_actividad($id_actividad);
                } else {
                    if ($id_actividad == $this->input->post('id_actividad')) {
                        $id_actividad = $this->input->post('id_actividad');
                        $nombre_actividad = $this->input->post('nombre_actividad');
                        $descripcion_actividad = $this->input->post('descripcion_actividad');
                        $fecha_inicio_actividad = $this->input->post('fecha_inicio_actividad');
                        $fecha_fin_actividad = $this->input->post('fecha_fin_actividad');
                        $presupuesto_actividad = $this->input->post('presupuesto_actividad');
                        if(isset($_POST['id_producto'])){
                            $id_producto = $this->input->post('id_producto');
                        } else {
                            $id_producto = false;
                        }
                        if(isset($_POST['contraparte'])) {
                            $contraparte_actividad = true;
                        } else {
                            $contraparte_actividad = false;
                        }
                        if ($this->comparar_fechas($fecha_inicio_actividad, $fecha_fin_actividad) <= 0) {
                            $this->modelo_socio->update_actividad($id_actividad, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad, $id_producto, $contraparte_actividad);
                            redirect(base_url() . 'socio/reformular_proyecto/' . $this->input->post('id_proyecto'));
                        } else {
                            //fechas incoherentes
                            $this->reformular_actividad($id_actividad);
                        }
                    } else {
                        redirect(base_url() . 'socio');
                    }
                }
            } else {
                $datos = Array();
                $datos['actividad'] = $this->modelo_socio->get_actividad($id_actividad);
                $datos['productos'] = $this->modelo_socio->get_productos();
                $datos['presupuesto_disponible'] = $this->modelo_socio->get_presupuesto_disponible_proyecto_con_id($datos['actividad']->id_proyecto, $id_actividad);
                $this->load->view('socio/vista_reformular_actividad', $datos);
            }
        }
    }

    public function eliminar_proyecto($id_proyecto) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->delete_proyecto($id_proyecto);
            redirect(base_url() . 'socio/proyectos_en_edicion');
        }
    }

    public function eliminar_actividad($id_proyecto, $id_actividad) {
        $this->verificar_sesion();

        if (!is_numeric($id_actividad) || !is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->delete_actividad($id_actividad);
            redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
        }
    }

    public function registrar_nuevo_hito($id_proyecto, $id_actividad) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto) || !is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_actividad']) && isset($_POST['tipo_hito'])) {
                if ($this->input->post('id_actividad') == $id_actividad) {
                    $nombre_hito = $this->input->post('nombre_hito');
                    $descripcion_hito = $this->input->post('descripcion_hito');
                    $meta_hito = $this->input->post('meta_hito');
                    $unidad_hito = $this->input->post('unidad_hito');
                    $id_meta_producto = $this->input->post('id_meta_producto');
                    $aporta_producto = $this->input->post('aporta_producto');
                    switch ($this->input->post('tipo_hito')) {
                        case 'cuantitativo':
                            $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric');
                            $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[1024]');
                            $this->form_validation->set_rules('descripcion_hito', 'descripcion_hito', 'required|trim|min_length[5]|max_length[1024]');
                            $this->form_validation->set_rules('meta_hito', 'meta_hito', 'required|numeric');
                            $this->form_validation->set_rules('unidad_hito', 'unidad_hito', 'required|trim|min_length[1]|max_length[32]');
                            if (isset($_POST['id_meta_producto'])) {
                                $this->form_validation->set_rules('id_meta_producto', 'id_meta_producto', 'required|numeric');
                            }
                            if ($this->form_validation->run() == FALSE) {
                                unset($_POST['id_actividad']);
                                $this->registrar_nuevo_hito($id_proyecto, $id_actividad);
                            } else {
                                $this->registrar_hito_cuantitativo($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $id_meta_producto, $aporta_producto);
                            }
                            break;
                        case 'cualitativo':
                            $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric');
                            $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[1024]');
                            $this->form_validation->set_rules('descripcion_hito', 'descripcion_hito', 'required|trim|min_length[5]|max_length[1024]');
                            if ($this->form_validation->run() == FALSE) {
                                unset($_POST['id_actividad']);
                                $this->registrar_nuevo_hito($id_proyecto, $id_actividad);
                            } else {
                                $this->registrar_hito_cualitativo($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito);
                            }
                            break;
                        default :
                            redirect(base_url() . 'socio');
                            break;
                    }
                }
            } else {
                $datos = Array();
                $datos['actividad'] = $this->modelo_socio->get_actividad($id_actividad);
                $datos['id_proyecto'] = $id_proyecto;
                $datos['id_actividad'] = $id_actividad;
                if (isset($datos['actividad']->id_producto)) {
                    $datos['metas_cuantitativas'] = $this->modelo_socio->get_metas_cuantitativas_producto($datos['actividad']->id_producto);
                    $datos['metas_cualitativas'] = $this->modelo_socio->get_metas_cualitativas_producto($datos['actividad']->id_producto);
                }
                $this->load->view('socio/vista_registrar_nuevo_hito', $datos);
            }
        }
    }

    private function registrar_hito_cuantitativo($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $id_meta_producto, $aporta_producto) {
        $this->modelo_socio->insert_hito_cuantitativo($id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $id_meta_producto, $aporta_producto);
        redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
    }

    private function registrar_hito_cualitativo($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito) {
        $this->modelo_socio->insert_hito_cualitativo($id_actividad, $nombre_hito, $descripcion_hito);
        redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
    }

    public function modificar_hito_cuantitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_hito']) && isset($_POST['nombre_hito']) && isset($_POST['descripcion_hito']) && isset($_POST['meta_hito']) && isset($_POST['unidad_hito'])) {
                if ($id_hito == $this->input->post('id_hito')) {
                    $this->form_validation->set_rules('id_hito', 'id_hito', 'required|numeric');
                    $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[1024]');
                    $this->form_validation->set_rules('descripcion_hito', 'descripcion_hito', 'required|trim|min_length[5]|max_length[1024]');
                    $this->form_validation->set_rules('meta_hito', 'meta_hito', 'required|numeric');
                    $this->form_validation->set_rules('unidad_hito', 'unidad_hito', 'required|trim|min_length[1]|max_length[32]');
                    if ($this->form_validation->run() == FALSE) {
                        unset($_POST['id_hito']);
                        $this->modificar_hito_cuantitativo($id_actividad, $id_hito);
                    } else {
                        $nombre_hito = $this->input->post('nombre_hito');
                        $descripcion_hito = $this->input->post('descripcion_hito');
                        $meta_hito = $this->input->post('meta_hito');
                        $unidad_hito = $this->input->post('unidad_hito');
                        $aporta_producto = "indirecto";
                        $id_meta_producto = -1;
                        if (isset($_POST['aporta_producto'])) {
                            $aporta_producto = $this->input->post('aporta_producto');
                            $id_meta_producto = $this->input->post('id_meta_producto');
                        }
                        $this->modelo_socio->update_hito_cuantitativo($id_hito, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $aporta_producto, $id_meta_producto);
                        redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
                    }
                } else {
                    redirect(base_url() . 'socio');
                }
            } else {
                $datos = Array();
                $datos['id_hito'] = $id_hito;
                $datos['id_proyecto'] = $id_proyecto;
                $datos['hito'] = $this->modelo_socio->get_hito_cuantitativo($id_hito);
                $datos['actividad'] = $this->modelo_socio->get_actividad($datos['hito']->id_actividad);
                if (isset($datos['actividad']->id_producto)) {
                    $datos['metas_cuantitativas'] = $this->modelo_socio->get_metas_cuantitativas_producto($datos['actividad']->id_producto);
                    $datos['metas_cualitativas'] = $this->modelo_socio->get_metas_cualitativas_producto($datos['actividad']->id_producto);
                }
                $this->load->view('socio/vista_modificar_hito_cuantitativo', $datos);
            }
        }
    }

    public function modificar_hito_cualitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_hito']) && isset($_POST['nombre_hito']) && isset($_POST['descripcion_hito'])) {
                if ($id_hito == $this->input->post('id_hito')) {
                    $this->form_validation->set_rules('id_hito', 'id_hito', 'required|numeric');
                    $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[1024]');
                    $this->form_validation->set_rules('descripcion_hito', 'descripcion_hito', 'required|trim|min_length[5]|max_length[1024]');
                    if ($this->form_validation->run() == FALSE) {
                        unset($_POST['id_hito']);
                        $this->modificar_hito_cuantitativo($id_actividad, $id_hito);
                    } else {
                        $nombre_hito = $this->input->post('nombre_hito');
                        $descripcion_hito = $this->input->post('descripcion_hito');
                        $this->modelo_socio->update_hito_cualitativo($id_hito, $nombre_hito, $descripcion_hito);
                        redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
                    }
                } else {
                    redirect(base_url() . 'socio');
                }
            } else {
                $datos = Array();
                $datos['id_hito'] = $id_hito;
                $datos['id_proyecto'] = $id_proyecto;
                $datos['hito'] = $this->modelo_socio->get_hito_cualitativo($id_hito);
                $datos['actividad'] = $this->modelo_socio->get_actividad($datos['hito']->id_actividad);
                $this->load->view('socio/vista_modificar_hito_cualitativo', $datos);
            }
        }
    }

    public function eliminar_hito_cuantitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->delete_hito_cuantitativo($id_hito);
            redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
        }
    }

    public function eliminar_hito_cualitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->delete_hito_cualitativo($id_hito);
            redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
        }
    }

    public function registrar_nuevo_hito_reformulado($id_proyecto, $id_actividad) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto) || !is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_actividad']) && isset($_POST['tipo_hito'])) {
                if ($this->input->post('id_actividad') == $id_actividad) {
                    $nombre_hito = $this->input->post('nombre_hito');
                    $descripcion_hito = $this->input->post('descripcion_hito');
                    $meta_hito = $this->input->post('meta_hito');
                    $unidad_hito = $this->input->post('unidad_hito');
                    $id_meta_producto = $this->input->post('id_meta_producto');
                    $aporta_producto = $this->input->post('aporta_producto');
                    switch ($this->input->post('tipo_hito')) {
                        case 'cuantitativo':
                            $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric');
                            $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[1024]');
                            $this->form_validation->set_rules('descripcion_hito', 'descripcion_hito', 'required|trim|min_length[5]|max_length[1024]');
                            $this->form_validation->set_rules('meta_hito', 'meta_hito', 'required|numeric');
                            $this->form_validation->set_rules('unidad_hito', 'unidad_hito', 'required|trim|min_length[1]|max_length[32]');
                            if (isset($_POST['id_meta_producto'])) {
                                $this->form_validation->set_rules('id_meta_producto', 'id_meta_producto', 'required|numeric');
                            }
                            if ($this->form_validation->run() == FALSE) {
                                unset($_POST['id_actividad']);
                                $this->registrar_nuevo_hito_reformulado($id_proyecto, $id_actividad);
                            } else {
                                $this->registrar_hito_cuantitativo_reformulado($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $id_meta_producto, $aporta_producto);
                            }
                            break;
                        case 'cualitativo':
                            $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric');
                            $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[1024]');
                            $this->form_validation->set_rules('descripcion_hito', 'descripcion_hito', 'required|trim|min_length[5]|max_length[1024]');
                            if ($this->form_validation->run() == FALSE) {
                                unset($_POST['id_actividad']);
                                $this->registrar_nuevo_hito_reformulado($id_proyecto, $id_actividad);
                            } else {
                                $this->registrar_hito_cualitativo_reformulado($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito);
                            }
                            break;
                        default :
                            redirect(base_url() . 'socio');
                            break;
                    }
                }
            } else {
                $datos = Array();
                $datos['actividad'] = $this->modelo_socio->get_actividad($id_actividad);
                $datos['id_proyecto'] = $id_proyecto;
                $datos['id_actividad'] = $id_actividad;
                if (isset($datos['actividad']->id_producto)) {
                    $datos['metas_cuantitativas'] = $this->modelo_socio->get_metas_cuantitativas_producto($datos['actividad']->id_producto);
                    $datos['metas_cualitativas'] = $this->modelo_socio->get_metas_cualitativas_producto($datos['actividad']->id_producto);
                }
                $this->load->view('socio/vista_registrar_nuevo_hito_reformulado', $datos);
            }
        }
    }

    private function registrar_hito_cuantitativo_reformulado($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $id_meta_producto, $aporta_producto) {
        $this->modelo_socio->insert_hito_cuantitativo($id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $id_meta_producto, $aporta_producto);
        redirect(base_url() . 'socio/reformular_proyecto/' . $id_proyecto);
    }

    private function registrar_hito_cualitativo_reformulado($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito) {
        $this->modelo_socio->insert_hito_cualitativo($id_actividad, $nombre_hito, $descripcion_hito);
        redirect(base_url() . 'socio/reformular_proyecto/' . $id_proyecto);
    }

    public function reformular_hito_cuantitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_hito']) && isset($_POST['nombre_hito']) && isset($_POST['descripcion_hito']) && isset($_POST['meta_hito']) && isset($_POST['unidad_hito'])) {
                if ($id_hito == $this->input->post('id_hito')) {
                    $this->form_validation->set_rules('id_hito', 'id_hito', 'required|numeric');
                    $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[1024]');
                    $this->form_validation->set_rules('descripcion_hito', 'descripcion_hito', 'required|trim|min_length[5]|max_length[1024]');
                    $this->form_validation->set_rules('meta_hito', 'meta_hito', 'required|numeric');
                    $this->form_validation->set_rules('unidad_hito', 'unidad_hito', 'required|trim|min_length[1]|max_length[32]');
                    if ($this->form_validation->run() == FALSE) {
                        unset($_POST['id_hito']);
                        $this->reformular_hito_cuantitativo($id_actividad, $id_hito);
                    } else {
                        $nombre_hito = $this->input->post('nombre_hito');
                        $descripcion_hito = $this->input->post('descripcion_hito');
                        $meta_hito = $this->input->post('meta_hito');
                        $unidad_hito = $this->input->post('unidad_hito');
                        $aporta_producto = "indirecto";
                        $id_meta_producto = -1;
                        if (isset($_POST['aporta_producto'])) {
                            $aporta_producto = $this->input->post('aporta_producto');
                            $id_meta_producto = $this->input->post('id_meta_producto');
                        }
                        $this->modelo_socio->update_hito_cuantitativo($id_hito, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $aporta_producto, $id_meta_producto);
                        redirect(base_url() . 'socio/reformular_proyecto/' . $id_proyecto);
                    }
                } else {
                    redirect(base_url() . 'socio');
                }
            } else {
                $datos = Array();
                $datos['id_hito'] = $id_hito;
                $datos['id_proyecto'] = $id_proyecto;
                $datos['hito'] = $this->modelo_socio->get_hito_cuantitativo($id_hito);
                $datos['actividad'] = $this->modelo_socio->get_actividad($datos['hito']->id_actividad);
                if (isset($datos['actividad']->id_producto)) {
                    $datos['metas_cuantitativas'] = $this->modelo_socio->get_metas_cuantitativas_producto($datos['actividad']->id_producto);
                    $datos['metas_cualitativas'] = $this->modelo_socio->get_metas_cualitativas_producto($datos['actividad']->id_producto);
                }
                $this->load->view('socio/vista_reformular_hito_cuantitativo', $datos);
            }
        }
    }

    public function reformular_hito_cualitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_hito']) && isset($_POST['nombre_hito']) && isset($_POST['descripcion_hito'])) {
                if ($id_hito == $this->input->post('id_hito')) {
                    $this->form_validation->set_rules('id_hito', 'id_hito', 'required|numeric');
                    $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[1024]');
                    $this->form_validation->set_rules('descripcion_hito', 'descripcion_hito', 'required|trim|min_length[5]|max_length[1024]');
                    if ($this->form_validation->run() == FALSE) {
                        unset($_POST['id_hito']);
                        $this->reformular_hito_cuantitativo($id_actividad, $id_hito);
                    } else {
                        $nombre_hito = $this->input->post('nombre_hito');
                        $descripcion_hito = $this->input->post('descripcion_hito');
                        $this->modelo_socio->update_hito_cualitativo($id_hito, $nombre_hito, $descripcion_hito);
                        redirect(base_url() . 'socio/reformular_proyecto/' . $id_proyecto);
                    }
                } else {
                    redirect(base_url() . 'socio');
                }
            } else {
                $datos = Array();
                $datos['id_hito'] = $id_hito;
                $datos['id_proyecto'] = $id_proyecto;
                $datos['hito'] = $this->modelo_socio->get_hito_cualitativo($id_hito);
                $datos['actividad'] = $this->modelo_socio->get_actividad($datos['hito']->id_actividad);
                $this->load->view('socio/vista_reformular_hito_cualitativo', $datos);
            }
        }
    }

    public function eliminar_hito_cuantitativo_reformulado($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->delete_hito_cuantitativo($id_hito);
            redirect(base_url() . 'socio/reformular_proyecto/' . $id_proyecto);
        }
    }

    public function eliminar_hito_cualitativo_reformulado($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->delete_hito_cualitativo($id_hito);
            redirect(base_url() . 'socio/reformular_proyecto/' . $id_proyecto);
        }
    }

    public function ver_avances_hito_cuantitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $datos = Array();
            $datos['id_proyecto'] = $id_proyecto;
            $datos['id_hito'] = $id_hito;
            $datos['hito_cuantitativo'] = $this->modelo_socio->get_hito_cuantitativo($id_hito);
            $avances = $this->modelo_socio->get_avances_hito_cuantitativo($id_hito);
            $datos['avances_hito_cuantitativo'] = $avances['avances_hito_cuantitativo'];
            if (isset($avances['documentos'])) {
                $datos['documentos'] = $avances['documentos'];
            }
            $this->load->view('socio/vista_avances_hito_cuantitativo', $datos);
        }
    }

    public function registrar_avance_hito_cuantitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_hito']) && isset($_POST['cantidad_avance_hito']) && isset($_POST['fecha_avance_hito']) && isset($_POST['descripcion_avance_hito'])) {
                $this->form_validation->set_rules('id_hito', 'id_hito', 'required|numeric');
                $this->form_validation->set_rules('cantidad_avance_hito', 'cantidad_avance_hito', 'required|numeric');
                $this->form_validation->set_rules('fecha_avance_hito', 'fecha_avance_hito', 'required');
                $this->form_validation->set_rules('descripcion_avance_hito', 'descripcion_avance_hito', 'required|trim|min_length[5]|max_length[1024]');
                if (isset($_POST['con_respaldos'])) {
                    $this->form_validation->set_rules('titulo_documento_avance[]', 'titulo_documento_avance', 'required|trim|min_length[5]|max_length[64]');
                    $this->form_validation->set_rules('descripcion_documento_avance[]', 'descripcion_documento_avance', 'required|trim|min_length[5]|max_length[1024]');
                }
                if ($this->form_validation->run() == FALSE || $id_hito != $_POST['id_hito']) {
                    unset($_POST['id_hito']);
                    $this->registrar_avance_hito_cuantitativo($id_proyecto, $id_hito);
                } else {
                    $cantidad_avance_hito = $this->input->post('cantidad_avance_hito');
                    $fecha_avance_hito = $this->input->post('fecha_avance_hito');
                    $descripcion_avance_hito = $this->input->post('descripcion_avance_hito');
                    if (isset($_POST['con_respaldos'])) {
                        $titulo_documento_avance = $this->input->post('titulo_documento_avance');
                        $descripcion_documento_avance = $this->input->post('descripcion_documento_avance');
                        foreach ($_FILES as $clave => $archivo) {
                            $nombre = $archivo['name'];
                            $nombre = $this->modelo_socio->sanitizar_cadena($nombre);
                            unset($_FILES[$clave]['name']);
                            $_FILES[$clave]['name'] = $nombre;
                        }
                        $this->modelo_socio->insert_avance_hito_cuantitativo_con_documentos($id_hito, $cantidad_avance_hito, $fecha_avance_hito, $descripcion_avance_hito, $titulo_documento_avance, $descripcion_documento_avance);
                        redirect(base_url() . 'socio/ver_proyecto/' . $id_proyecto);
                    } else {
                        $this->modelo_socio->insert_avance_hito_cuantitativo_sin_documentos($id_hito, $cantidad_avance_hito, $fecha_avance_hito, $descripcion_avance_hito);
                        redirect(base_url() . 'socio/ver_proyecto/' . $id_proyecto);
                    }
                }
            } else {
                $datos = Array();
                $datos['id_proyecto'] = $id_proyecto;
                $datos['id_hito'] = $id_hito;
                $datos['hito'] = $this->modelo_socio->get_hito_cuantitativo($id_hito);
                $datos['actividad'] = $this->modelo_socio->get_actividad($datos['hito']->id_actividad);
                $this->load->view('socio/vista_registrar_avance_hito_cuantitativo', $datos);
            }
        }
    }

    public function ver_avances_hito_cualitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $datos = Array();
            $datos['id_proyecto'] = $id_proyecto;
            $datos['id_hito'] = $id_hito;
            $datos['hito_cualitativo'] = $this->modelo_socio->get_hito_cualitativo($id_hito);
            $avances = $this->modelo_socio->get_avances_hito_cualitativo($id_hito);
            $datos['avances_hito_cualitativo'] = $avances;
            $this->load->view('socio/vista_avances_hito_cualitativo', $datos);
        }
    }

    public function registrar_avance_hito_cualitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_hito']) && isset($_POST['titulo_avance_hito']) && isset($_POST['fecha_avance_hito']) && isset($_POST['descripcion_avance_hito'])) {
                $this->form_validation->set_rules('id_hito', 'id_hito', 'required|numeric');
                $this->form_validation->set_rules('titulo_avance_hito', 'titulo_avance_hito', 'required|trim|min_length[5]|max_length[1024]');
                $this->form_validation->set_rules('fecha_avance_hito', 'fecha_avance_hito', 'required');
                $this->form_validation->set_rules('descripcion_avance_hito', 'descripcion_avance_hito', 'required|trim|min_length[5]|max_length[1024]');
                if ($this->form_validation->run() == FALSE || $id_hito != $_POST['id_hito']) {
                    unset($_POST['id_hito']);
                    $this->registrar_avance_hito_cualitativo($id_proyecto, $id_hito);
                } else {
                    $titulo_avance_hito = $this->input->post('titulo_avance_hito');
                    $fecha_avance_hito = $this->input->post('fecha_avance_hito');
                    $descripcion_avance_hito = $this->input->post('descripcion_avance_hito');
                    foreach ($_FILES as $clave => $archivo) {
                        $nombre = $archivo['name'];
                        $nombre = $this->modelo_socio->sanitizar_cadena($nombre);
                        unset($_FILES[$clave]['name']);
                        $_FILES[$clave]['name'] = $nombre;
                    }
                    $this->modelo_socio->insert_avance_hito_cualitativo($id_hito, $titulo_avance_hito, $fecha_avance_hito, $descripcion_avance_hito);
                    redirect(base_url() . 'socio/ver_proyecto/' . $id_proyecto);
                }
            } else {
                $datos = Array();
                $datos['id_proyecto'] = $id_proyecto;
                $datos['id_hito'] = $id_hito;
                $datos['hito'] = $this->modelo_socio->get_hito_cualitativo($id_hito);
                $datos['actividad'] = $this->modelo_socio->get_actividad($datos['hito']->id_actividad);
                $this->load->view('socio/vista_registrar_avance_hito_cualitativo', $datos);
            }
        }
    }
    
    public function registrar_gasto_estimado_actividad($id_proyecto, $id_actividad) {
        $this->verificar_sesion();
        if(isset($_POST['gasto_actividad']) && isset($_POST['id_actividad'])) {
            $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric');
            $this->form_validation->set_rules('gasto_actividad', 'gasto_actividad', 'required|numeric');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_actividad']);
                $this->registrar_gasto_estimado_actividad($id_proyecto, $id_actividad);
            } else {
                $gasto_actividad = $this->input->post('gasto_actividad');
                $this->modelo_socio->update_gasto_actividad($id_actividad, $gasto_actividad);
                redirect(base_url() . 'socio/ver_proyecto/' . $id_proyecto);
            }
        } else {
            $datos = Array();
            $datos['id_proyecto'] = $id_proyecto;
            $datos['id_actividad'] = $id_actividad;
            $datos['actividad'] = $this->modelo_socio->get_actividad($id_actividad);
            $this->load->view('socio/vista_registrar_gasto_actividad', $datos);
        }
    }

    public function registrar_gastos_actividad($id_proyecto, $id_actividad) {
        if (!is_numeric($id_actividad) || !is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_actividad']) && isset($_POST['fecha_gasto']) && isset($_POST['importe_gasto']) && isset($_POST['concepto_gasto'])) {
                $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric');
                $this->form_validation->set_rules('fecha_gasto[]', 'fecha_gasto', 'required');
                $this->form_validation->set_rules('importe_gasto[]', 'importe_gasto', 'required|numeric');
                $this->form_validation->set_rules('concepto_gasto[]', 'concepto_gasto', 'required|trim|min_length[5]|max_length[1024]');
                if ($this->form_validation->run() == FALSE || $id_actividad != $_POST['id_actividad']) {
                    unset($_POST['id_actividad']);
                    $this->registrar_gastos_actividad($id_proyecto, $id_actividad);
                } else {
                    foreach ($_FILES as $clave => $archivo) {
                        $nombre = $archivo['name'];
                        $nombre = $this->modelo_socio->sanitizar_cadena($nombre);
                        unset($_FILES[$clave]['name']);
                        $_FILES[$clave]['name'] = $nombre;
                    }
                    $fecha_gasto = $this->input->post('fecha_gasto');
                    $importe_gasto = $this->input->post('importe_gasto');
                    $concepto_gasto = $this->input->post('concepto_gasto');
                    $this->modelo_socio->insert_gastos_actividad($id_actividad, $fecha_gasto, $importe_gasto, $concepto_gasto);
                    redirect(base_url() . 'socio/ver_proyecto/' . $id_proyecto);
                }
            } else {
                $datos = Array();
                $datos['id_proyecto'] = $id_proyecto;
                $datos['id_actividad'] = $id_actividad;
                $datos['actividad'] = $this->modelo_socio->get_actividad($id_actividad);
                $datos['gastos_actividad'] = $this->modelo_socio->get_gastos_actividad($id_actividad);
                $this->load->view('socio/vista_registrar_gastos_actividad', $datos);
            }
        }
    }

    public function ver_reportes() {
        $this->verificar_sesion();

        $this->load->view('socio/vista_reportes');
    }

    public function existe_nombre_proyecto_institucion_ajax() {
        if ($this->input->is_ajax_request() && isset($_POST['nombre_proyecto'])) {
            $existe = false;
            if (isset($_POST['id_proyecto'])) {
                $existe = $this->modelo_socio->existe_nombre_proyecto_institucion_con_id($this->session->userdata('id_institucion'), $_POST['id_proyecto'], $_POST['nombre_proyecto']);
            } else {
                $existe = $this->modelo_socio->existe_nombre_proyecto_institucion($this->session->userdata('id_institucion'), $_POST['nombre_proyecto']);
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
    
    public function ver_reporte_prodoc($id_prodoc) {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['prodoc'] = $this->modelo_socio->get_prodoc_completo($id_prodoc);
        $this->load->view('socio/vista_reporte_prodoc', $datos);
    }
    
    public function ver_reporte_gestion_actual() {
        $this->verificar_sesion();
        
        $id_proyecto = $this->modelo_socio->get_id_proyecto_completo_gestion_actual();
        if(!$id_proyecto) {
            $this->session->set_flashdata('error_proyecto', 'No tiene registrado un POA activo para la gestión actual.');
            redirect(base_url() . 'socio/inicio_sistema_socio', 'refresh');
        } else {
            redirect(base_url() . 'socio/ver_reporte_poa/' . $id_proyecto);
        }
    }
    
    public function ver_reporte_poa($id_proyecto) {
        $this->verificar_sesion();
        
        $datos['proyecto'] = $this->modelo_socio->get_reporte_proyecto_completo_activo($id_proyecto);
        if(!isset($datos['error'])) {
            $this->load->view('socio/vista_reporte_proyecto', $datos);
        } else {
            if($datos['error'] == 'error_proyecto') {
                $this->session->set_flashdata('error_proyecto', 'No tiene registrado un POA activo para la gestión actual.');
                redirect(base_url() . 'socio/inicio_sistema_socio', 'refresh');
            } else {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function ver_reportes_poa() {
        $this->verificar_sesion();
        
        $datos = Array();
        $datos['proyectos'] = $this->modelo_socio->get_proyectos_socio();
        $this->load->view('socio/vista_reporte_proyectos_activos', $datos);
    }

    public function error() {
        $this->verificar_sesion();

        $this->load->view('vista_error');
    }

    public function descarga($nombre) {
        $this->verificar_sesion();

        $data = file_get_contents('./files/' . $this->session->userdata('carpeta_institucion') . '/' . $nombre);
        force_download($nombre, $data);
    }

    private function comparar_fechas($primera, $segunda) {
        $valoresPrimera = explode("-", $primera);
        $valoresSegunda = explode("-", $segunda);
        $anioPrimera = $valoresPrimera[0];
        $mesPrimera = $valoresPrimera[1];
        $diaPrimera = $valoresPrimera[2];
        $anioSegunda = $valoresSegunda[0];
        $mesSegunda = $valoresSegunda[1];
        $diaSegunda = $valoresSegunda[2];
        if (!checkdate($mesPrimera, $diaPrimera, $anioPrimera) || !checkdate($mesSegunda, $diaSegunda, $anioSegunda)) {
            redirect(base_url() . 'socio');
        } else {
            $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anioPrimera);
            $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anioSegunda);
            return $diasPrimeraJuliano - $diasSegundaJuliano;
        }
    }

}
