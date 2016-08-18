<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modelo_socio_inicio
 *
 * @author Jose
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class modelo_socio extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_proyectos_socio() {
        $id_institucion = $this->session->userdata('id_institucion');
        $sql = "SELECT 
                    PROYECTO.id_proyecto, 
                    PROYECTO.nombre_proyecto, 
                    PROYECTO.descripcion_proyecto,
                    PROYECTO.presupuesto_proyecto
                FROM 
                    PROYECTO, 
                    INSTITUCION
                WHERE 
                    PROYECTO.id_institucion = INSTITUCION.id_institucion AND
                    PROYECTO.id_institucion = $id_institucion AND
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
            $this->session->set_flashdata('acceso_no_autorizado', 'Operacion no permitida.');
            //TODO registrar intento de fallo
            redirect(base_url() . 'socio');
        }
        $id_institucion = $this->session->userdata('id_institucion');
        $datos = Array();
        $sql = "SELECT 
                    PROYECTO.id_proyecto, 
                    PROYECTO.nombre_proyecto, 
                    PROYECTO.descripcion_proyecto,
                    PROYECTO.presupuesto_proyecto
                FROM 
                    PROYECTO, 
                    INSTITUCION
                WHERE 
                    PROYECTO.id_institucion = INSTITUCION.id_institucion AND
                    PROYECTO.id_institucion = $id_institucion AND
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
            redirect(base_url() . 'socio/ver_proyectos', 'refresh');
        }

        return $datos;
    }

    public function get_actividades_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            $this->session->set_flashdata('acceso_no_autorizado', 'Operacion no permitida.');
            //TODO registrar intento de fallo
            redirect(base_url() . 'socio');
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
            $this->session->set_flashdata('acceso_no_autorizado', 'Operacion no permitida.');
            //TODO registrar intento de fallo
            redirect(base_url() . 'socio', 'refresh');
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

    public function get_proyectos_en_edicion() {
        $id_institucion = $this->session->userdata('id_institucion');
        $sql = "SELECT 
                    PROYECTO.id_proyecto, 
                    PROYECTO.nombre_proyecto, 
                    PROYECTO.descripcion_proyecto
                FROM 
                    PROYECTO, 
                    INSTITUCION
                WHERE 
                    PROYECTO.id_institucion = INSTITUCION.id_institucion AND
                    PROYECTO.id_institucion = $id_institucion AND
                    PROYECTO.en_edicion = true
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

    public function get_proyecto($id_proyecto) {
        $id_institucion = $this->session->userdata('id_institucion');
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "SELECT
                        PROYECTO.id_proyecto,
                        PROYECTO.nombre_proyecto,
                        PROYECTO.descripcion_proyecto,
                        PROYECTO.presupuesto_proyecto
                    FROM
                        PROYECTO,
                        INSTITUCION
                    WHERE
                        PROYECTO.id_institucion = INSTITUCION.id_institucion AND
                        PROYECTO.id_institucion = $id_institucion AND
                        PROYECTO.id_proyecto = $id_proyecto
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

    public function insert_proyecto($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto) {
        $id_institucion = $this->session->userdata('id_institucion');
        $sql = "INSERT INTO PROYECTO
                (
                    nombre_proyecto,
                    descripcion_proyecto,
                    presupuesto_proyecto,
                    id_institucion,
                    en_edicion
                )
                VALUES
                (
                    '$nombre_proyecto',
                    '$descripcion_proyecto',
                    '$presupuesto_proyecto',
                    $id_institucion,
                    true
                )
                ";
        $query = $this->db->query($sql);
    }

    public function update_proyecto($id_proyecto, $nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "UPDATE PROYECTO SET
                        PROYECTO.nombre_proyecto = '$nombre_proyecto',
                        PROYECTO.descripcion_proyecto = '$descripcion_proyecto',
                        PROYECTO.presupuesto_proyecto = $presupuesto_proyecto 
                    WHERE
                        PROYECTO.id_proyecto = $id_proyecto
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function get_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "SELECT
                        ACTIVIDAD.id_proyecto,
                        ACTIVIDAD.id_actividad,
                        ACTIVIDAD.nombre_actividad,
                        ACTIVIDAD.descripcion_actividad,
                        ACTIVIDAD.fecha_inicio_actividad,
                        ACTIVIDAD.fecha_fin_actividad,
                        ACTIVIDAD.presupuesto_actividad
                    FROM
                        ACTIVIDAD
                    WHERE
                        ACTIVIDAD.id_actividad = $id_actividad
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

    public function insert_actividad($id_proyecto, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "INSERT INTO ACTIVIDAD
                    (
                        id_proyecto,
                        nombre_actividad,
                        descripcion_actividad,
                        fecha_inicio_actividad,
                        fecha_fin_actividad,
                        presupuesto_actividad
                    )
                    VALUES
                    (
                        $id_proyecto,
                        '$nombre_actividad',
                        '$descripcion_actividad',
                        '$fecha_inicio_actividad',
                        '$fecha_fin_actividad',
                        '$presupuesto_actividad'
                    )
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function update_actividad($id_actividad, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "UPDATE ACTIVIDAD SET
                        ACTIVIDAD.nombre_actividad = '$nombre_actividad',
                        ACTIVIDAD.descripcion_actividad = '$descripcion_actividad',
                        ACTIVIDAD.fecha_inicio_actividad = '$fecha_inicio_actividad',
                        ACTIVIDAD.fecha_fin_actividad = '$fecha_fin_actividad',
                        ACTIVIDAD.presupuesto_actividad = $presupuesto_actividad
                    WHERE
                        ACTIVIDAD.id_actividad = $id_actividad
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function get_indicador_operativo($id_indicador) {
        if (!is_numeric($id_indicador)) {
            redirect(base_url() . 'socio');
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

    public function get_tipos_indicador_op() {
        $sql = "SELECT
                    TIPO_INDICADOR_OPERATIVO.id_tipo_indicador_op,
                    TIPO_INDICADOR_OPERATIVO.nombre_tipo_indicador_op
                FROM
                    TIPO_INDICADOR_OPERATIVO";
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

    public function insert_indicador_op($id_tipo_indicador_op, $id_actividad, $nombre_indicador_op, $fecha_limite_indicador_op, $meta_op, $aceptable_op, $limitado_op, $no_aceptable_op) {
        if (!is_numeric($id_tipo_indicador_op) || !is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "INSERT INTO INDICADOR_OPERATIVO
                    (
                        id_tipo_indicador_op,
                        id_actividad,
                        nombre_indicador_op,
                        fecha_limite_indicador_op,
                        meta_op,
                        aceptable_op,
                        limitado_op,
                        no_aceptable_op
                    )
                    VALUES
                    (
                        $id_tipo_indicador_op,
                        $id_actividad,
                        '$nombre_indicador_op',
                        '$fecha_limite_indicador_op',
                        $meta_op,
                        $aceptable_op,
                        $limitado_op,
                        $no_aceptable_op
                    )
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function update_indicador_operativo($id_tipo_indicador_op, $id_indicador_op, $nombre_indicador_op, $fecha_limite_indicador_op, $meta_op, $aceptable_op, $limitado_op, $no_aceptable_op) {
        if (!is_numeric($id_indicador_op) || !is_numeric($id_tipo_indicador_op)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "UPDATE INDICADOR_OPERATIVO SET
                        id_tipo_indicador_op = $id_tipo_indicador_op,
                        nombre_indicador_op = '$nombre_indicador_op',
                        fecha_limite_indicador_op = '$fecha_limite_indicador_op', 
                        meta_op = $meta_op, 
                        aceptable_op = $aceptable_op, 
                        limitado_op = $limitado_op, 
                        no_aceptable_op = $no_aceptable_op
                    WHERE
                        id_indicador_op = $id_indicador_op
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function delete_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "DELETE FROM PROYECTO
                    WHERE
                        id_proyecto = $id_proyecto
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function delete_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "DELETE FROM ACTIVIDAD
                    WHERE
                        id_actividad = $id_actividad
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function delete_indicador_operativo($id_indicador) {
        if (!is_numeric($id_indicador)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "DELETE FROM INDICADOR_OPERATIVO
                    WHERE
                        id_indicador_op = $id_indicador
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function terminar_edicion_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "UPDATE PROYECTO SET
                        PROYECTO.en_edicion = false
                    WHERE
                        PROYECTO.id_proyecto = $id_proyecto
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function get_avances_indicador_operativo($id_indicador) {
        if (!is_numeric($id_indicador)) {
            redirect(base_url() . 'socio');
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

    public function guardar_avance_indicador_operativo($id_indicador_op, $avance_op, $descripcion_avance_op, $fecha_gasto_avance, $concepto_gasto_avance, $importe_gasto_avance, $respaldo_gasto_avance) {
        if (!is_numeric($id_indicador_op)) {
            redirect(base_url() . 'socio');
        } else {
            $this->db->trans_start();
            $query = $this->db->query('SET foreign_key_checks = 0;');
            $sql = "INSERT INTO AVANCE_INDICADOR_OPERATIVO
                    (
                        AVANCE_INDICADOR_OPERATIVO.id_indicador_op,
                        AVANCE_INDICADOR_OPERATIVO.avance_op,
                        AVANCE_INDICADOR_OPERATIVO.fecha_avance_op,
                        AVANCE_INDICADOR_OPERATIVO.descripcion_avance_op
                    )
                    VALUES
                    (
                        $id_indicador_op,
                        $avance_op,
                        curdate(),
                        '$descripcion_avance_op'
                    )
                    ";
            $query = $this->db->query($sql);
            if ($query) {
                $sql = "SELECT
                            AVANCE_INDICADOR_OPERATIVO.id_avance_indicador_op,
                            AVANCE_INDICADOR_OPERATIVO.id_indicador_op,
                            AVANCE_INDICADOR_OPERATIVO.avance_op,
                            AVANCE_INDICADOR_OPERATIVO.fecha_avance_op,
                            AVANCE_INDICADOR_OPERATIVO.descripcion_avance_op
                        FROM
                            AVANCE_INDICADOR_OPERATIVO
                        WHERE
                            AVANCE_INDICADOR_OPERATIVO.avance_op = $avance_op AND
                            AVANCE_INDICADOR_OPERATIVO.fecha_avance_op = curdate() AND
                            AVANCE_INDICADOR_OPERATIVO.descripcion_avance_op = '$descripcion_avance_op'
                        ";
                $query = $this->db->query($sql);
                if ($query->num_rows() == 1) {
                    $datos_avance = $query->row();
                    $id_avance = $datos_avance->id_avance_indicador_op;
                    $valores_gasto_avance = "";
                    $num_gastos = count($fecha_gasto_avance);
                    for ($i = 0; $i < $num_gastos; $i = $i + 1) {
                        $valores_gasto_avance = $valores_gasto_avance .
                                "($id_avance, '$fecha_gasto_avance[$i]', '$concepto_gasto_avance[$i]', $importe_gasto_avance[$i], '$respaldo_gasto_avance[$i]')";
                        if ($i < $num_gastos - 1) {
                            $valores_gasto_avance = $valores_gasto_avance . ",";
                        }
                    }

                    $sql = "INSERT INTO GASTO_AVANCE
                            (
                                GASTO_AVANCE.id_avance_indicador_op,
                                GASTO_AVANCE.fecha_gasto_avance,
                                GASTO_AVANCE.concepto_gasto_avance,
                                GASTO_AVANCE.importe_gasto_avance,
                                GASTO_AVANCE.respaldo_gasto_avance
                            )
                            VALUES
                                $valores_gasto_avance
                            ";
                    $query = $this->db->query($sql);
                }
            } else {
                //error
            }
            $query = $this->db->query('SET foreign_key_checks = 0;');
            $this->db->trans_complete();
        }
    }

    public function get_gastos_avance($id_indicador_op) {
        if (!is_numeric($id_indicador_op)) {
            redirect(base_url() . 'socio');
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
