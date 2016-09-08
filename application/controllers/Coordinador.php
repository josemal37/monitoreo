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
        $this->load->model(Array('modelo_coordinador', 'modelo_indicador_operativo', 'modelo_indicador_acumulativo', 'modelo_indicador_promedio_menor_que', 'modelo_indicador_porcentaje'));
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
            /*
              foreach ($datos['datos_indicadores'] as $key => $indicadores) {
              for ($i = 0; $i < sizeof($indicadores); $i = $i + 1) {
              switch ($datos['datos_indicadores'][$key][$i]->nombre_tipo_indicador_op) {
              case 'Acumulativo':
              $datos['datos_indicadores'][$key][$i]->estado_indicador_op = $this->modelo_indicador_acumulativo->get_estado_indicador($datos['datos_indicadores'][$key][$i]->id_indicador_op);
              break;
              case 'Promedio (menor que)':
              $datos['datos_indicadores'][$key][$i]->estado_indicador_op = $this->modelo_indicador_promedio_menor_que->get_estado_indicador($datos['datos_indicadores'][$key][$i]->id_indicador_op);
              break;
              case 'Porcentaje':
              $datos['datos_indicadores'][$key][$i]->estado_indicador_op = $this->modelo_indicador_porcentaje->get_estado_indicador($datos['datos_indicadores'][$key][$i]->id_indicador_op);
              break;
              default :
              $datos['datos_indicadores'][$key][$i]->estado_indicador_op = 'Indicador no definido';
              break;
              }
              }
              } */
            $this->load->view('coordinador/vista_proyecto', $datos);
        }
    }

    public function ver_avances_hito_cuantitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
        } else {
            $datos = Array();
            $datos['id_proyecto'] = $id_proyecto;
            $datos['id_hito'] = $id_hito;
            $datos['hito_cuantitativo'] = $this->modelo_coordinador->get_hito_cuantitativo($id_hito);
            $avances = $this->modelo_coordinador->get_avances_hito_cuantitativo($id_hito);
            $datos['avances_hito_cuantitativo'] = $avances['avances_hito_cuantitativo'];
            if (isset($avances['documentos'])) {
                $datos['documentos'] = $avances['documentos'];
            }
            $this->load->view('coordinador/vista_avances_hito_cuantitativo', $datos);
        }
    }

    public function ver_avances_hito_cualitativo($id_proyecto, $id_hito) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $datos = Array();
            $datos['id_proyecto'] = $id_proyecto;
            $datos['id_hito'] = $id_hito;
            $datos['hito_cualitativo'] = $this->modelo_coordinador->get_hito_cualitativo($id_hito);
            $avances = $this->modelo_coordinador->get_avances_hito_cualitativo($id_hito);
            $datos['avances_hito_cualitativo'] = $avances;
            $this->load->view('coordinador/vista_avances_hito_cualitativo', $datos);
        }
    }
    
    public function registrar_indicador_cuantitativo($id_proyecto, $id_hito) {
        if(!is_numeric($id_proyecto) || !is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
        } else {
            echo('en proceso');
        }
    }

    public function descarga($nombre) {
        $this->verificar_sesion();

        $data = file_get_contents('./files/' . $this->session->userdata('carpeta_institucion') . '/' . $nombre);
        force_download($nombre, $data);
    }

}
