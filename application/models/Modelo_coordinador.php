<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modelo_coordinador
 *
 * @author Jose
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Modelo_coordinador extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_proyectos_activos() {
        $sql = "SELECT
                    PROYECTO.id_proyecto,
                    PROYECTO.nombre_proyecto,
                    PROYECTO.descripcion_proyecto,
                    PROYECTO.presupuesto_proyecto,
                    INSTITUCION.id_institucion,
                    INSTITUCION.nombre_institucion,
                    INSTITUCION.sigla_institucion
                FROM
                    PROYECTO,
                    INSTITUCION
                WHERE
                    PROYECTO.id_institucion = INSTITUCION.id_institucion AND
                    PROYECTO.en_edicion = false
                ";
        $query = $this->db->query($sql);
        if (!$query) {
            return false;
        } else {
            if ($query->num_rows() == 0) {
                return false;
            } else {
                return $query->result();
            }
        }
    }

    public function get_proyecto_completo($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'coordinador');
        }
        $datos = Array();
        $sql = "SELECT 
                    PROYECTO.id_proyecto, 
                    PROYECTO.nombre_proyecto, 
                    PROYECTO.descripcion_proyecto,
                    PROYECTO.presupuesto_proyecto,
                    INSTITUCION.id_institucion,
                    INSTITUCION.nombre_institucion,
                    INSTITUCION.sigla_institucion
                FROM 
                    PROYECTO, 
                    INSTITUCION
                WHERE 
                    PROYECTO.id_institucion = INSTITUCION.id_institucion AND
                    PROYECTO.id_proyecto = $id_proyecto
                ";
        $query_proyecto = $this->db->query($sql);
        if ($query_proyecto->num_rows() == 1) {
            $datos_proyecto = $query_proyecto->row();
            $datos['datos_proyecto'] = $datos_proyecto;

            $datos_actividades = $this->get_actividades_proyecto($id_proyecto);
            $datos['datos_actividades'] = $datos_actividades;

            if (sizeof($datos_actividades) > 0) {
                foreach ($datos_actividades as $datos_actividad) {
                    $datos_indicador = $this->get_indicadores_actividad($datos_actividad->id_actividad);
                    $datos['datos_indicadores'][$datos_actividad->nombre_actividad] = $datos_indicador;
                }
            }
        } else {
            $this->session->set_flashdata('no_existe_proyecto', 'El proyecto al que intenta acceder no existe.');
            redirect(base_url() . 'coordinador/proyectos_activos');
        }

        return $datos;
    }

    public function get_actividades_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'coordinador');
        }
        $sql = "SELECT
                    ACTIVIDAD.id_actividad,
                    ACTIVIDAD.nombre_actividad,
                    ACTIVIDAD.descripcion_actividad,
                    ACTIVIDAD.fecha_inicio_actividad,
                    ACTIVIDAD.fecha_fin_actividad,
                    ACTIVIDAD.presupuesto_actividad
                FROM
                    ACTIVIDAD
                WHERE
                    ACTIVIDAD.id_proyecto = $id_proyecto
                ORDER BY
                    ACTIVIDAD.fecha_inicio_actividad ASC
                ";
        $query_actividades = $this->db->query($sql);
        return $query_actividades->result();
    }

    public function get_indicadores_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'coordinador');
        }
        $sql = "SELECT
                        INDICADOR_OPERATIVO.id_indicador_op,
                        INDICADOR_OPERATIVO.id_actividad,
                        INDICADOR_OPERATIVO.nombre_indicador_op,
                        INDICADOR_OPERATIVO.fecha_limite_indicador_op,
                        INDICADOR_OPERATIVO.meta_op,
                        INDICADOR_OPERATIVO.aceptable_op,
                        INDICADOR_OPERATIVO.limitado_op,
                        INDICADOR_OPERATIVO.no_aceptable_op,
                        TIPO_INDICADOR_OPERATIVO.nombre_tipo_indicador_op
                    FROM
                        INDICADOR_OPERATIVO,
                        TIPO_INDICADOR_OPERATIVO,
                        ACTIVIDAD
                    WHERE
                        INDICADOR_OPERATIVO.id_tipo_indicador_op = TIPO_INDICADOR_OPERATIVO.id_tipo_indicador_op AND
                        INDICADOR_OPERATIVO.id_actividad = ACTIVIDAD.id_actividad AND
                        ACTIVIDAD.id_actividad = $id_actividad
                    ORDER BY
                        INDICADOR_OPERATIVO.fecha_limite_indicador_op ASC
                ";
        $query_indicadores = $this->db->query($sql);
        return $query_indicadores->result();
    }
    
    public function get_indicador_operativo($id_indicador) {
        if (!is_numeric($id_indicador)) {
            redirect(base_url() . 'coordinador');
        } else {
            $sql = "SELECT
                        INDICADOR_OPERATIVO.id_indicador_op,
                        INDICADOR_OPERATIVO.id_tipo_indicador_op,
                        INDICADOR_OPERATIVO.id_actividad,
                        INDICADOR_OPERATIVO.nombre_indicador_op,
                        INDICADOR_OPERATIVO.fecha_limite_indicador_op,
                        INDICADOR_OPERATIVO.meta_op,
                        INDICADOR_OPERATIVO.aceptable_op,
                        INDICADOR_OPERATIVO.limitado_op,
                        INDICADOR_OPERATIVO.no_aceptable_op
                    FROM
                        INDICADOR_OPERATIVO
                    WHERE
                        INDICADOR_OPERATIVO.id_indicador_op = $id_indicador
                    ";
            $query = $this->db->query($sql);
            if (!$query) {
                return false;
            } else {
                if ($query->num_rows() == 1) {
                    return $query->row();
                } else {
                    return false;
                }
            }
        }
    }
    
    public function get_avances_indicador_operativo($id_indicador) {
        if (!is_numeric($id_indicador)) {
            redirect(base_url() . 'coordinador');
        } else {
            $sql = "SELECT
                        AVANCE_INDICADOR_OPERATIVO.id_avance_indicador_op,
                        AVANCE_INDICADOR_OPERATIVO.id_indicador_op,
                        AVANCE_INDICADOR_OPERATIVO.avance_op,
                        AVANCE_INDICADOR_OPERATIVO.fecha_avance_op,
                        AVANCE_INDICADOR_OPERATIVO.descripcion_avance_op
                    FROM
                        AVANCE_INDICADOR_OPERATIVO
                    WHERE
                        AVANCE_INDICADOR_OPERATIVO.id_indicador_op = $id_indicador
                    ORDER BY
                        AVANCE_INDICADOR_OPERATIVO.fecha_avance_op ASC
                    ";
            $query = $this->db->query($sql);
            if (!$query) {
                return false;
            } else {
                if ($query->num_rows() == 0) {
                    return false;
                } else {
                    return $query->result();
                }
            }
        }
    }
    
    public function get_gastos_avance($id_indicador_op) {
        if (!is_numeric($id_indicador_op)) {
            redirect(base_url() . 'coordinador');
        } else {
            $sql = "SELECT
                        GASTO_AVANCE.id_gasto_avance,
                        GASTO_AVANCE.id_avance_indicador_op,
                        GASTO_AVANCE.fecha_gasto_avance,
                        GASTO_AVANCE.concepto_gasto_avance,
                        GASTO_AVANCE.importe_gasto_avance,
                        GASTO_AVANCE.respaldo_gasto_avance
                    FROM
                        GASTO_AVANCE,
                        AVANCE_INDICADOR_OPERATIVO
                    WHERE
                        GASTO_AVANCE.id_avance_indicador_op = AVANCE_INDICADOR_OPERATIVO.id_avance_indicador_op AND
                        AVANCE_INDICADOR_OPERATIVO.id_indicador_op = $id_indicador_op
                    ";
            $query = $this->db->query($sql);
            if (!$query) {
                return false;
            } else {
                if ($query->num_rows() == 0) {
                    return false;
                } else {
                    return $query->result();
                }
            }
        }
    }

}
