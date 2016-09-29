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
        $this->proyectos_activos();
    }

    private function verificar_sesion() {
        if ($this->session->userdata('nombre_rol') == FALSE || $this->session->userdata('nombre_rol') != 'coordinador') {
            redirect(base_url() . 'login');
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

    public function ver_avances_hito_cuantitativo($id_institucion, $id_proyecto, $id_hito) {
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
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
        } else {
            if (isset($_POST['id_hito']) && isset($_POST['nombre_indicador']) && isset($_POST['tipo_indicador']) && isset($_POST['aceptable_indicador']) && isset($_POST['limitado_indicador']) && isset($_POST['no_aceptable_indicador'])) {
                $this->form_validation->set_rules('id_hito', 'id_hito', 'required|numeric');
                $this->form_validation->set_rules('nombre_indicador', 'nombre_indicador', 'required|trim|min_length[5]|max_length[128]');
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

    public function aprobar_avance_hito_cuantitativo($id_proyecto, $id_hito, $id_estado_avance) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_estado_avance)) {
            redirect(base_url() . 'coordinador');
        } else {
            $this->modelo_coordinador->modificar_estado_avance_hito_cuantitativo($id_estado_avance, true);
            redirect(base_url() . 'coordinador/ver_avances_hito_cuantitativo/' . $this->session->userdata('id_institucion') . '/' . $id_proyecto . '/' . $id_hito);
        }
    }

    public function no_aprobar_avance_hito_cuantitativo($id_proyecto, $id_hito, $id_estado_avance) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_estado_avance)) {
            redirect(base_url() . 'coordinador');
        } else {
            $this->modelo_coordinador->modificar_estado_avance_hito_cuantitativo($id_estado_avance, false);
            redirect(base_url() . 'coordinador/ver_avances_hito_cuantitativo/' . $this->session->userdata('id_institucion') . '/' . $id_proyecto . '/' . $id_hito);
        }
    }

    public function aprobar_avance_hito_cualitativo($id_proyecto, $id_hito, $id_estado_avance) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_estado_avance)) {
            redirect(base_url() . 'coordinador');
        } else {
            $this->modelo_coordinador->modificar_estado_avance_hito_cualitativo($id_estado_avance, true);
            redirect(base_url() . 'coordinador/ver_avances_hito_cualitativo/' . $this->session->userdata('id_institucion') . '/' . $id_proyecto . '/' . $id_hito);
        }
    }

    public function no_aprobar_avance_hito_cualitativo($id_proyecto, $id_hito, $id_estado_avance) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito) || !is_numeric($id_estado_avance)) {
            redirect(base_url() . 'coordinador');
        } else {
            $this->modelo_coordinador->modificar_estado_avance_hito_cualitativo($id_estado_avance, false);
            redirect(base_url() . 'coordinador/ver_avances_hito_cualitativo/' . $this->session->userdata('id_institucion') . '/' . $id_proyecto . '/' . $id_hito);
        }
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
