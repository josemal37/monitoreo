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
        $this->load->library(array('session', 'form_validation', 'encrypt'));
        $this->load->helper(array('url', 'form', 'download'));
        $this->load->database('default');
        $config = Array();
        $config['upload_path'] = './files/';
        $config['allowed_types'] = 'gif|jpg|jpeg|jpe|png|pdf|doc|docx|rar|zip|xls|xlsx';
        $config['max_size'] = '800000000';
        $this->load->library('upload', $config);
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

        if (isset($_POST['nombre_proyecto']) && isset($_POST['presupuesto_proyecto']) && isset($_POST['descripcion_proyecto'])) {
            $this->form_validation->set_rules('nombre_proyecto', 'nombre_proyecto', 'required|trim|min_length[5]|max_length[128]');
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
        $this->verificar_sesion();
        
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->terminar_edicion_proyecto($id_proyecto);
            redirect(base_url() . 'socio/proyectos_en_edicion');
        }
    }

    public function registrar_nueva_actividad($id_proyecto) {
        $this->verificar_sesion();

        if (isset($_POST['id_proyecto']) && isset($_POST['nombre_actividad']) && isset($_POST['fecha_inicio_actividad']) && isset($_POST['fecha_fin_actividad']) && isset($_POST['presupuesto_actividad'])) {
            $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric|is_natural');
            $this->form_validation->set_rules('nombre_actividad', 'nombre_actividad', 'required|trim|min_length[2]|max_length[128]');
            $this->form_validation->set_rules('fecha_inicio_actividad', 'fecha_inicio_actividad', 'required');
            $this->form_validation->set_rules('fecha_fin_actividad', 'fecha_fin_actividad', 'required');
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

        if (isset($_POST['id_tipo_indicador_op']) && isset($_POST['id_actividad']) && isset($_POST['nombre_indicador_op']) && isset($_POST['fecha_limite_indicador_op']) && isset($_POST['meta_op']) && isset($_POST['aceptable_op']) && isset($_POST['limitado_op']) && isset($_POST['no_aceptable_op'])) {
            $this->form_validation->set_rules('id_tipo_indicador_op', 'id_tipo_indicador_op', 'required|numeric|is_natural');
            $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric|is_natural');
            $this->form_validation->set_rules('nombre_indicador_op', 'nombre_indicador_op', 'required|trim|min_length[2]|max_length[128]');
            $this->form_validation->set_rules('fecha_limite_indicador_op', 'fecha_limite_indicador_op', 'required');
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
        } else {
            $tipos_indicador_op = $this->modelo_socio->get_tipos_indicador_op();
            $datos = Array('id_proyecto' => $id_proyecto, 'id_actividad' => $id_actividad, 'tipos_indicador_op' => $tipos_indicador_op);
            $this->load->view('socio/vista_registrar_nuevo_indicador_op', $datos);
        }
    }

    public function modificar_proyecto($id_proyecto) {
        $this->verificar_sesion();

        if (!is_numeric($id_proyecto)) {
            $this->index(); //TODO controlar error
        } else {
            if (isset($_POST['nombre_proyecto']) && isset($_POST['descripcion_proyecto']) && isset($_POST['presupuesto_proyecto']) && isset($_POST['id_proyecto'])) {
                $this->form_validation->set_rules('id_proyecto', 'id_proyecto', 'required|numeric');
                $this->form_validation->set_rules('nombre_proyecto', 'nombre_proyecto', 'required|trim|min_length[5]|max_length[128]');
                $this->form_validation->set_rules('presupuesto_proyecto', 'presupuesto_proyecto', 'required|numeric');
                $this->form_validation->set_rules('descripcion_proyecto', 'descripcion_proyecto', 'required|trim|min_length[5]|max_length[1024]');
                if ($this->form_validation->run() == FALSE) {
                    unset($_POST['id_proyecto']);
                    $this->modificar_proyecto($id_proyecto);
                } else {
                    if ($id_proyecto == $this->input->post('id_proyecto')) {
                        $id_proyecto = $this->input->post('id_proyecto');
                        $nombre_proyecto = $this->input->post('nombre_proyecto');
                        $descripcion_proyecto = $this->input->post('descripcion_proyecto');
                        $presupuesto_proyecto = $this->input->post('presupuesto_proyecto');
                        $this->modelo_socio->update_proyecto($id_proyecto, $nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto);
                        redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
                    } else {
                        //TODO controlar error
                        redirect(base_url() . 'socio');
                    }
                }
            } else {
                $datos['proyecto'] = $this->modelo_socio->get_proyecto($id_proyecto);
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
                $this->form_validation->set_rules('nombre_actividad', 'nombre_actividad', 'required|trim|min_length[5]|max_length[128]');
                $this->form_validation->set_rules('fecha_inicio_actividad', 'fecha_inicio_actividad', 'required');
                $this->form_validation->set_rules('fecha_fin_actividad', 'fecha_fin_actividad', 'required');
                $this->form_validation->set_rules('descripcion_actividad', 'descripcion_actividad', 'required|trim|min_length[5]|max_length[1024]');
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
        $this->verificar_sesion();
        
        if (!is_numeric($id_proyecto) || !is_numeric($id_indicador)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_tipo_indicador_op']) && isset($_POST['id_indicador_op']) && isset($_POST['nombre_indicador_op']) && isset($_POST['fecha_limite_indicador_op']) && isset($_POST['meta_op']) && isset($_POST['aceptable_op']) && isset($_POST['limitado_op']) && isset($_POST['no_aceptable_op'])) {
                $this->form_validation->set_rules('id_tipo_indicador_op', 'id_tipo_indicador_op', 'required|numeric|is_natural');
                $this->form_validation->set_rules('id_indicador_op', 'id_indicador_op', 'required|numeric|is_natural');
                $this->form_validation->set_rules('nombre_indicador_op', 'nombre_indicador_op', 'required|trim|min_length[5]|max_length[128]');
                $this->form_validation->set_rules('fecha_limite_indicador_op', 'fecha_limite_indicador_op', 'required');
                $this->form_validation->set_rules('meta_op', 'meta_op', 'required|numeric|is_natural');
                $this->form_validation->set_rules('aceptable_op', 'aceptable_op', 'required|numeric|is_natural');
                $this->form_validation->set_rules('limitado_op', 'limitado_op', 'required|numeric|is_natural');
                $this->form_validation->set_rules('no_aceptable_op', 'no_aceptable_op', 'required|numeric|is_natural');
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

    public function eliminar_indicador_operativo($id_proyecto, $id_indicador) {
        $this->verificar_sesion();
        
        if (!is_numeric($id_indicador || !is_numeric($id_proyecto))) {
            redirect(base_url() . 'socio');
        } else {
            $this->modelo_socio->delete_indicador_operativo($id_indicador);
            redirect(base_url() . 'socio/editar_proyecto/' . $id_proyecto);
        }
    }

    public function registrar_avance_indicador_operativo($id_proyecto, $id_indicador) {
        $this->verificar_sesion();
        
        if (!is_numeric($id_proyecto) || !is_numeric($id_indicador)) {
            redirect(base_url() . 'socio');
        } else {
            if (isset($_POST['id_indicador_op']) && isset($_POST['avance_op']) && isset($_POST['descripcion_avance_op']) && isset($_POST['fecha_gasto_avance[]']) && isset($_POST['concepto_gasto_avance[]']) && isset($_POST['importe_gasto_avance[]'])) {
                $this->form_validation->set_rules('avance_op', 'avance_op', 'required|numeric');
                $this->form_validation->set_rules('descripcion_avance_op', 'descripcion_avance_op', 'required|trim|min_length[2]|max_length[512]');
                $this->form_validation->set_rules('fecha_gasto_avance[]', 'fecha_gasto_avance[]', 'required');
                $this->form_validation->set_rules('concepto_gasto_avance[]', 'concepto_gasto_avance[]', 'required|trim|min_length[2]|max_length[512]');
                $this->form_validation->set_rules('importe_gasto_avance[]', 'importe_gasto_avance[]', 'required|numeric');
                if ($this->form_validation->run() == FALSE) {
                    unset($_POST['avance_op']);
                    $this->registrar_avance_indicador_operativo($id_proyecto, $id_indicador);
                } else {
                    if ($id_indicador == $this->input->post('id_indicador_op')) {
                        $avance_op = $this->input->post('avance_op');
                        $descripcion_avance_op = $this->input->post('descripcion_avance_op');
                        $fecha_gasto_avance = $this->input->post('fecha_gasto_avance[]');
                        $concepto_gasto_avance = $this->input->post('concepto_gasto_avance[]');
                        $importe_gasto_avance = $this->input->post('importe_gasto_avance[]');
                        $respaldo_gasto_avance = Array();
                        $id_indicador_op = $this->input->post('id_indicador_op');
                        $archivos = Array();
                        $errores = FALSE;
                        $indice_respaldos = 0;
                        foreach ($_FILES as $key => $value) {
                            if (!empty($value['name'])) {
                                $nombre_archivo = $_FILES[$key]['name'];
                                unset($_FILES[$key]['name']);
                                $_FILES[$key]['name'] = $this->sanitizar_cadena($nombre_archivo);
                                if (!$this->upload->do_upload($key)) {
                                    $errores = TRUE;
                                } else {
                                    $archivos[$value['name']] = $this->upload->data();
                                    $respaldo_gasto_avance[$indice_respaldos] = $archivos[$value['name']]['file_name'];
                                    $indice_respaldos = $indice_respaldos + 1;
                                }
                            }
                        }
                        if ($errores) {
                            foreach ($archivos as $key => $file) {
                                @unlink($file['full_path']);
                            }
                        } else {
                            if (!empty($archivos)) {
                                //guardamos el la base de datos
                                $this->modelo_socio->guardar_avance_indicador_operativo($id_indicador_op, $avance_op, $descripcion_avance_op, $fecha_gasto_avance, $concepto_gasto_avance, $importe_gasto_avance, $respaldo_gasto_avance);
                                redirect(base_url() . 'socio/ver_proyecto/' . $id_proyecto);
                            } else {
                                //error en los archivos
                                redirect(base_url() . 'socio');
                            }
                        }
                    } else {
                        redirect(base_url() . 'socio');
                    }
                }
            } else {
                $datos_indicador = $this->modelo_socio->get_indicador_operativo($id_indicador);
                $avances_indicador = $this->modelo_socio->get_avances_indicador_operativo($id_indicador);
                $gastos_avances = $this->modelo_socio->get_gastos_avance($id_indicador);
                $datos = Array();
                $datos['id_proyecto'] = $id_proyecto;
                $datos['id_indicador'] = $id_indicador;
                $datos['indicador'] = $datos_indicador;
                $datos['avances_indicador'] = $avances_indicador;
                $datos['gastos_avances'] = $gastos_avances;
                $this->load->view('socio/vista_registrar_avance_indicador_op', $datos);
            }
        }
    }

    public function descarga($nombre) {
        $this->verificar_sesion();

        $data = file_get_contents('./files/'.$nombre);
        force_download($name, $data);
    }

    private function sanitizar_cadena($cadena) {
        $cadena = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $cadena);
        $cadena = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $cadena);
        $cadena = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $cadena);
        $cadena = str_replace(array('í', 'ì', 'î', 'ï'), "i", $cadena);
        $cadena = str_replace(array('é', 'è', 'ê', 'ë'), "e", $cadena);
        $cadena = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $cadena);
        $cadena = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $cadena);
        $cadena = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $cadena);
        $cadena = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $cadena);
        $cadena = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $cadena);
        $cadena = str_replace(array('[', '^', '´', '`', '¨', '~', ']', ',', '+', '=', '&'), "", $cadena);
        $cadena = str_replace("ç", "c", $cadena);
        $cadena = str_replace("Ç", "C", $cadena);
        $cadena = str_replace("ñ", "n", $cadena);
        $cadena = str_replace("Ñ", "N", $cadena);
        $cadena = str_replace("Ý", "Y", $cadena);
        $cadena = str_replace("ý", "y", $cadena);
        return $cadena;
    }

}
