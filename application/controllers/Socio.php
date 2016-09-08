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
        $this->load->model(array('modelo_socio', 'modelo_indicador_operativo', 'modelo_indicador_acumulativo', 'modelo_indicador_promedio_menor_que', 'modelo_indicador_porcentaje'));
        $this->load->library(array('session', 'form_validation', 'encrypt', 'upload'));
        $this->load->helper(array('url', 'form', 'download'));
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

        $datos = $this->modelo_socio->get_proyecto_completo_activo($id_proyecto);
        /* foreach ($datos['datos_indicadores'] as $key => $indicadores) {
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

        $datos = $this->modelo_socio->get_proyecto_completo_en_edicion($id_proyecto);
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
                $this->registrar_nueva_actividad($id_proyecto);
            } else {
                if ($id_proyecto == $this->input->post('id_proyecto')) {
                    $nombre_actividad = $this->input->post('nombre_actividad');
                    $descripcion_actividad = $this->input->post('descripcion_actividad');
                    $fecha_inicio_actividad = $this->input->post('fecha_inicio_actividad');
                    $fecha_fin_actividad = $this->input->post('fecha_fin_actividad');
                    $presupuesto_actividad = $this->input->post('presupuesto_actividad');
                    if ($this->comparar_fechas($fecha_inicio_actividad, $fecha_fin_actividad) <= 0) {
                        $this->modelo_socio->insert_actividad($id_proyecto, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad);
                        redirect(base_url() . 'socio/editar_proyecto/' . $this->input->post('id_proyecto'));
                    } else {
                        //fechas incoherentes
                        $this->registrar_nueva_actividad($id_actividad);
                    }
                } else {
                    redirect(base_url() . 'socio');
                }
            }
        } else {
            $datos = Array('id_proyecto' => $id_proyecto);
            $this->load->view('socio/vista_registrar_nueva_actividad', $datos);
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
                    $this->modificar_actividad($id_actividad);
                } else {
                    if ($id_actividad == $this->input->post('id_actividad')) {
                        $id_actividad = $this->input->post('id_actividad');
                        $nombre_actividad = $this->input->post('nombre_actividad');
                        $descripcion_actividad = $this->input->post('descripcion_actividad');
                        $fecha_inicio_actividad = $this->input->post('fecha_inicio_actividad');
                        $fecha_fin_actividad = $this->input->post('fecha_fin_actividad');
                        $presupuesto_actividad = $this->input->post('presupuesto_actividad');
                        if ($this->comparar_fechas($fecha_inicio_actividad, $fecha_fin_actividad) <= 0) {
                            $this->modelo_socio->update_actividad($id_actividad, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad);
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
                $datos['actividad'] = $this->modelo_socio->get_actividad($id_actividad);
                $this->load->view('socio/vista_modificar_actividad', $datos);
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
                    switch ($this->input->post('tipo_hito')) {
                        case 'cuantitativo':
                            $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric');
                            $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[128]');
                            $this->form_validation->set_rules('descripcion_hito', 'descripcion_hito', 'required|trim|min_length[5]|max_length[1024]');
                            $this->form_validation->set_rules('meta_hito', 'meta_hito', 'required|numeric');
                            $this->form_validation->set_rules('unidad_hito', 'unidad_hito', 'required|trim|min_length[1]|max_length[32]');
                            if ($this->form_validation->run() == FALSE) {
                                unset($_POST['id_actividad']);
                                $this->registrar_nuevo_hito($id_proyecto, $id_actividad);
                            } else {
                                $this->registrar_hito_cuantitativo($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito);
                            }
                            break;
                        case 'cualitativo':
                            $this->form_validation->set_rules('id_actividad', 'id_actividad', 'required|numeric');
                            $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[128]');
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
                $this->load->view('socio/vista_registrar_nuevo_hito', $datos);
            }
        }
    }

    private function registrar_hito_cuantitativo($id_proyecto, $id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito) {
        $this->modelo_socio->insert_hito_cuantitativo($id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito);
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
                    $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[128]');
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
                        $this->modelo_socio->update_hito_cuantitativo($id_hito, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito);
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
                    $this->form_validation->set_rules('nombre_hito', 'nombre_hito', 'required|trim|min_length[5]|max_length[128]');
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
            if (isset($_POST['id_hito']) && isset($_POST['titulo_avance_hito']) && isset($_POST['fecha_avance_hito']) && isset($_POST['descripcion_avance_hito']) && isset($_POST['nombre_documento_avance_hito'])) {
                $this->form_validation->set_rules('id_hito', 'id_hito', 'required|numeric');
                $this->form_validation->set_rules('titulo_avance_hito', 'titulo_avance_hito', 'required|trim|min_length[5]|max_length[128]');
                $this->form_validation->set_rules('fecha_avance_hito', 'fecha_avance_hito', 'required');
                $this->form_validation->set_rules('descripcion_avance_hito', 'descripcion_avance_hito', 'required|trim|min_length[5]|max_length[1024]');
                $this->form_validation->set_rules('nombre_documento_avance_hito', 'nombre_documento_avance_hito', 'required|trim|max_length[128]');
                if ($this->form_validation->run() == FALSE || $id_hito != $_POST['id_hito']) {
                    unset($_POST['id_hito']);
                    $this->registrar_avance_hito_cualtitativo($id_proyecto, $id_hito);
                } else {
                    $titulo_avance_hito = $this->input->post('titulo_avance_hito');
                    $fecha_avance_hito = $this->input->post('fecha_avance_hito');
                    $descripcion_avance_hito = $this->input->post('descripcion_avance_hito');
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
