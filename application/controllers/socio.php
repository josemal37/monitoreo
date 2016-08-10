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

class socio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('modelo_socio');
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('url', 'form'));
        $this->load->database('default');
    }

    public function index() {
        $this->proyectos_activos();
    }

    private function verificar_sesion() {
        if ($this->session->userdata('nombre_rol') == FALSE || $this->session->userdata('nombre_rol') != 'socio') {
            redirect(base_url() . 'login');
        }
    }

    public function proyectos_activos() {
        $this->verificar_sesion();

        $datos['proyectos'] = $this->modelo_socio->get_proyectos_socio();
        $this->load->view('socio/vista_proyectos_activos', $datos);
    }

    public function ver_proyecto($id_proyecto) {
        $this->verificar_sesion();

        $datos = $this->modelo_socio->get_proyecto_completo($id_proyecto);
        $this->load->view('socio/vista_proyecto', $datos);
    }

    public function proyectos_en_edicion() {
        $this->verificar_sesion();

        $datos['proyectos'] = $this->modelo_socio->get_proyectos_en_edicion();
        $this->load->view('socio/vista_proyectos_en_edicion', $datos);
    }

    public function registrar_nuevo_proyecto() {
        $this->verificar_sesion();

        if ($this->input->post('nombre_proyecto') && $this->input->post('presupuesto_proyecto')) {
            $this->form_validation->set_rules('nombre_proyecto', 'nombre_proyecto', 'required|trim|min_length[2]|max_length[128]');
            $this->form_validation->set_rules('presupuesto_proyecto', 'presupuesto_proyecto', 'required|numeric');
            $this->form_validation->set_rules('descripcion_proyecto', 'descripcion_proyecto', 'required|trim|min_length[2]|max_length[1024]');

            if ($this->form_validation->run() == FALSE) {
                unset($_POST['nombre_proyecto']);
                unset($_POST['presupuesto_proyecto']);
                $this->registrar_nuevo_proyecto();
            } else {
                $nombre_proyecto = $this->input->post('nombre_proyecto');
                $descripcion_proyecto = $this->input->post('descripcion_proyecto');
                $presupuesto_proyecto = $this->input->post('presupuesto_proyecto');
                $this->modelo_socio->insert_proyecto($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto);
                redirect(base_url() . 'socio/proyectos_en_edicion');
            }
        } else {
            $datos = NULL;
            $this->load->view('socio/vista_registrar_nuevo_proyecto', $datos);
        }
    }

    public function editar_proyecto($id_proyecto) {
        $this->verificar_sesion();

        $datos = $this->modelo_socio->get_proyecto_completo($id_proyecto);
        $this->load->view('socio/vista_editar_proyecto', $datos);
    }
    
    public function terminar_edicion_proyecto($id_proyecto) {
        if(!is_numeric($id_proyecto)) {
            redirect(base_url().'socio');
        } else {
            $this->modelo_socio->terminar_edicion_proyecto($id_proyecto);
            redirect(base_url().'socio/proyectos_en_edicion');
        }
    }

    public function registrar_nueva_actividad($id_proyecto) {
        $this->verificar_sesion();

        if ($this->input->post('id_proyecto') && $this->input->post('nombre_actividad') && $this->input->post('fecha_inicio_actividad') && $this->input->post('fecha_fin_actividad') && $this->input->post('presupuesto_actividad')) {
            $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric|is_natural');
            $this->form_validation->set_rules('nombre_actividad', 'nombre_actividad', 'required|trim|min_length[2]|max_length[128]');
            $this->form_validation->set_rules('presupuesto_actividad', 'presupuesto_actividad', 'required|numeric');
            $this->form_validation->set_rules('descripcion_actividad', 'descripcion_actividad', 'required|trim|min_length[2]|max_length[1024]');
            $this->form_validation->set_rules('presupuesto_actividad', 'presupuesto_actividad', 'required|numeric');
            
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_proyecto']);
                $this->registrar_nueva_actividad();
            } else {
                if ($id_proyecto == $this->input->post('id_proyecto')) {
                    $nombre_actividad = $this->input->post('nombre_actividad');
                    $descripcion_actividad = $this->input->post('descripcion_actividad');
                    $fecha_inicio_actividad = $this->input->post('fecha_inicio_actividad');
                    $fecha_fin_actividad = $this->input->post('fecha_fin_actividad');
                    $presupuesto_actividad = $this->input->post('presupuesto_actividad');
                    $this->modelo_socio->insert_actividad($id_proyecto, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad);
                    redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
                } else {
                    redirect(base_url() . 'socio');
                }
            }
        } else {
            $datos = Array('id_proyecto' => $id_proyecto);
            $this->load->view('socio/vista_registrar_nueva_actividad', $datos);
        }
    }
    
    public function registrar_nuevo_indicador($id_proyecto, $id_actividad) {
        $this->verificar_sesion();
        
        if($this->input->post('id_tipo_indicador_op') && $this->input->post('id_actividad') && $this->input->post('nombre_indicador_op') && $this->input->post('fecha_limite_indicador_op') && $this->input->post('meta_op') && $this->input->post('aceptable_op') && $this->input->post('limitado_op') && $this->input->post('no_aceptable_op')) {
            $this->form_validation->set_rules('id_tipo_indicador_op', 'id_tipo_indicador_op', 'required|numeric|is_natural');
            $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric|is_natural');
            $this->form_validation->set_rules('nombre_indicador_op', 'nombre_indicador_op', 'required|trim|min_length[2]|max_length[128]');
            $this->form_validation->set_rules('meta_op', 'meta_op', 'required|numeric');
            $this->form_validation->set_rules('aceptable_op', 'aceptable_op', 'required|numeric');
            $this->form_validation->set_rules('limitado_op', 'limitado_op', 'required|numeric');
            $this->form_validation->set_rules('no_aceptable_op', 'no_aceptable_op', 'required|numeric');
            if ($this->form_validation->run() == FALSE) {
                unset($_POST['id_actividad']);
                $this->registrar_nuevo_indicador();
            } else {
                if ($id_actividad == $this->input->post('id_actividad')) {
                    $id_tipo_indicador_op = $this->input->post('id_tipo_indicador_op');
                    $id_actividad = $this->input->post('id_actividad');
                    $nombre_indicador_op = $this->input->post('nombre_indicador_op');
                    $fecha_limite_indicador_op = $this->input->post('fecha_limite_indicador_op');
                    $meta_op = $this->input->post('meta_op');
                    $aceptable_op = $this->input->post('aceptable_op');
                    $limitado_op = $this->input->post('limitado_op');
                    $no_aceptable_op = $this->input->post('no_aceptable_op');
                    $this->modelo_socio->insert_indicador_op($id_tipo_indicador_op, $id_actividad, $nombre_indicador_op, $fecha_limite_indicador_op, $meta_op, $aceptable_op, $limitado_op, $no_aceptable_op);
                    redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
                } else {
                    redirect(base_url() . 'socio');
                }
            }
        }
        else {
            $tipos_indicador_op = $this->modelo_socio->get_tipos_indicador_op();
            $datos = Array('id_proyecto' => $id_proyecto, 'id_actividad' => $id_actividad, 'tipos_indicador_op' => $tipos_indicador_op);
            $this->load->view('socio/vista_registrar_nuevo_indicador_op', $datos);
        }
    }
    
    public function modificar_proyecto($id_proyecto) {
        $this->verificar_sesion();
        
        if(!is_numeric($id_proyecto)) {
            $this->index();//TODO controlar error
        } else {
            if($this->input->post('nombre_proyecto') && $this->input->post('descripcion_proyecto') && $this->input->post('presupuesto_proyecto') && $this->input->post('id_proyecto')) {
                $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric');
                $this->form_validation->set_rules('nombre_proyecto', 'nombre_proyecto', 'required|trim|min_length[2]|max_length[128]');
                $this->form_validation->set_rules('presupuesto_proyecto', 'presupuesto_proyecto', 'required|numeric');
                $this->form_validation->set_rules('descripcion_proyecto', 'descripcion_proyecto', 'required|trim|min_length[2]|max_length[1024]');
                if ($this->form_validation->run() == FALSE) {
                    unset($_POST['id_proyecto']);
                    $this->modificar_proyecto($id_proyecto);
                } else {
                    if($id_proyecto == $this->input->post('id_proyecto')) {
                        $id_proyecto = $this->input->post('id_proyecto');
                        $nombre_proyecto = $this->input->post('nombre_proyecto');
                        $descripcion_proyecto = $this->input->post('descripcion_proyecto');
                        $presupuesto_proyecto = $this->input->post('presupuesto_proyecto');
                        $this->modelo_socio->update_proyecto($id_proyecto, $nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto);
                        redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
                    }
                    else {
                        //TODO controlar error
                        redirect(base_url().'socio');
                    }
                }
            }
            else {
                $datos['proyecto'] = $this->modelo_socio->get_proyecto($id_proyecto);
                $this->load->view('socio/vista_modificar_proyecto', $datos);
            }
        }
    }
    
    public function modificar_actividad($id_actividad) {
        $this->verificar_sesion();
        
        if(!is_numeric($id_actividad)) {
            redirect(base_url().'socio');
        } else {
            if($this->input->post('id_proyecto') && $this->input->post('id_actividad') && $this->input->post('nombre_actividad') && $this->input->post('descripcion_actividad') && $this->input->post('fecha_inicio_actividad') && $this->input->post('fecha_fin_actividad') && $this->input->post('presupuesto_actividad')) {
                $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric|is_natural');
                $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric|is_natural');
                $this->form_validation->set_rules('nombre_actividad', 'nombre_actividad', 'required|trim|min_length[2]|max_length[128]');
                $this->form_validation->set_rules('presupuesto_actividad', 'presupuesto_actividad', 'required|numeric');
                $this->form_validation->set_rules('descripcion_actividad', 'descripcion_actividad', 'required|trim|min_length[2]|max_length[1024]');
                $this->form_validation->set_rules('presupuesto_actividad', 'presupuesto_actividad', 'required|numeric');

                if ($this->form_validation->run() == FALSE) {
                    unset($_POST['id_actividad']);
                    $this->modificar_actividad();
                } else {
                    if ($id_actividad == $this->input->post('id_actividad')) {
                        $id_actividad = $this->input->post('id_actividad');
                        $nombre_actividad = $this->input->post('nombre_actividad');
                        $descripcion_actividad = $this->input->post('descripcion_actividad');
                        $fecha_inicio_actividad = $this->input->post('fecha_inicio_actividad');
                        $fecha_fin_actividad = $this->input->post('fecha_fin_actividad');
                        $presupuesto_actividad = $this->input->post('presupuesto_actividad');
                        $this->modelo_socio->update_actividad($id_actividad, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad);
                        redirect(base_url() . 'socio/editar_proyecto/' . $this->input->post('id_proyecto'));
                    } else {
                        redirect(base_url() . 'socio');
                    }
                }
            } else {
                $datos['actividad'] = $this->modelo_socio->get_actividad($id_actividad);
                $this->load->view('socio/vista_modificar_actividad', $datos);
            }
        }
    }
    
    public function modificar_indicador_operativo($id_proyecto, $id_indicador) {
        if(!is_numeric($id_proyecto) || !is_numeric($id_indicador)) {
            redirect(base_url().'socio');
        } else {
            if($this->input->post('id_tipo_indicador_op') && $this->input->post('id_indicador_op') && $this->input->post('nombre_indicador_op') && $this->input->post('fecha_limite_indicador_op') && $this->input->post('meta_op') && $this->input->post('aceptable_op') && $this->input->post('limitado_op') && $this->input->post('no_aceptable_op')) {
                $this->form_validation->set_rules('id_tipo_indicador_op', 'id_tipo_indicador_op', 'required|numeric|is_natural');
                $this->form_validation->set_rules('id_indicador_op', 'id_indicador_op', 'required|numeric|is_natural');
                $this->form_validation->set_rules('nombre_indicador_op', 'nombre_indicador_op', 'required|trim|min_length[2]|max_length[128]');
                $this->form_validation->set_rules('meta_op', 'meta_op', 'required|numeric');
                $this->form_validation->set_rules('aceptable_op', 'aceptable_op', 'required|numeric');
                $this->form_validation->set_rules('limitado_op', 'limitado_op', 'required|numeric');
                $this->form_validation->set_rules('no_aceptable_op', 'no_aceptable_op', 'required|numeric');
                if ($this->form_validation->run() == FALSE) {
                    unset($_POST['id_indicador_op']);
                    $this->modificar_indicador_operativo($id_proyecto, $id_indicador);
                } else {
                    if ($id_indicador == $this->input->post('id_indicador_op')) {
                        $id_tipo_indicador_op = $this->input->post('id_tipo_indicador_op');
                        $id_indicador_op = $this->input->post('id_indicador_op');
                        $nombre_indicador_op = $this->input->post('nombre_indicador_op');
                        $fecha_limite_indicador_op = $this->input->post('fecha_limite_indicador_op');
                        $meta_op = $this->input->post('meta_op');
                        $aceptable_op = $this->input->post('aceptable_op');
                        $limitado_op = $this->input->post('limitado_op');
                        $no_aceptable_op = $this->input->post('no_aceptable_op');
                        $this->modelo_socio->update_indicador_operativo($id_tipo_indicador_op, $id_indicador_op, $nombre_indicador_op, $fecha_limite_indicador_op, $meta_op, $aceptable_op, $limitado_op, $no_aceptable_op);
                        redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
                    } else {
                        redirect(base_url() . 'socio');
                    }
                }
            } else {
                $datos = Array();
                $datos['tipos_indicador_op'] = $this->modelo_socio->get_tipos_indicador_op();
                $datos['id_proyecto'] = $id_proyecto;
                $datos['indicador'] = $this->modelo_socio->get_indicador_operativo($id_indicador);
                $this->load->view('socio/vista_modificar_indicador_op', $datos);
            }
        }
    }
    
    public function eliminar_proyecto($id_proyecto) {
        if(!is_numeric($id_proyecto)) {
            redirect(base_url().'socio');
        } else {
            $this->modelo_socio->delete_proyecto($id_proyecto);
            redirect(base_url() . 'socio/proyectos_en_edicion');
        }
    }
    
    public function eliminar_actividad($id_proyecto, $id_actividad) {
        if(!is_numeric($id_actividad) || !is_numeric($id_proyecto)) {
            redirect(base_url().'socio');
        } else {
            $this->modelo_socio->delete_actividad($id_actividad);
            redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
        }
    }
    
    public function eliminar_indicador_operativo($id_proyecto, $id_indicador) {
        if(!is_numeric($id_indicador || !is_numeric($id_proyecto))) {
            redirect(base_url().'socio');
        } else {
            $this->modelo_socio->delete_indicador_operativo($id_indicador);
            redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
        }
    }
    
    public function registrar_avance_indicador_operativo($id_proyecto, $id_indicador) {
        if(!is_numeric($id_proyecto) || !is_numeric($id_indicador)) {
            redirect(base_url().'socio');
        } else {
            $datos_indicador = $this->modelo_socio->get_indicador_operativo($id_indicador);
            $datos = Array();
            $datos['id_proyecto'] = $id_proyecto;
            $datos['indicador'] = $datos_indicador;
            $this->load->view('socio/vista_registrar_avance_indicador_op', $datos);
        }
    }

}
