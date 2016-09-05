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

class Modelo_socio extends CI_Model {

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
                    $datos_hitos_cuantitativos = $this->get_hitos_cuantitativos_actividad($datos_actividad->id_actividad);
                    $datos['datos_hitos_cuantitativos'][$datos_actividad->nombre_actividad] = $datos_hitos_cuantitativos;
                    $datos_hitos_cualitativos = $this->get_hitos_cualitativos_actividad($datos_actividad->id_actividad);
                    $datos['datos_hitos_cualitativos'][$datos_actividad->nombre_actividad] = $datos_hitos_cualitativos;
                }
            }
        } else {
            $this->session->set_flashdata('no_existe_proyecto', 'El proyecto al que intenta acceder no existe.');
            redirect(base_url() . 'socio');
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

    public function get_hitos_cuantitativos_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            $this->session->set_flashdata('acceso_no_autorizado', 'Operacion no permitida.');
            //TODO registrar intento de fallo
            redirect(base_url() . 'socio');
        }
        $sql = "SELECT
                    HITO_CUANTITATIVO.id_hito_cn,
                    HITO_CUANTITATIVO.id_actividad,
                    HITO_CUANTITATIVO.nombre_hito_cn,
                    HITO_CUANTITATIVO.descripcion_hito_cn,
                    HITO_CUANTITATIVO.meta_hito_cn,
                    HITO_CUANTITATIVO.unidad_hito_cn
                FROM
                    HITO_CUANTITATIVO
                WHERE
                    HITO_CUANTITATIVO.id_actividad = $id_actividad
                ORDER BY
                    HITO_CUANTITATIVO.nombre_hito_cn ASC
                ";
        $query_indicadores = $this->db->query($sql);
        return $query_indicadores->result();
    }

    public function get_hitos_cualitativos_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            $this->session->set_flashdata('acceso_no_autorizado', 'Operacion no permitida.');
            //TODO registrar intento de fallo
            redirect(base_url() . 'socio');
        }
        $sql = "SELECT
                    HITO_CUALITATIVO.id_hito_cl,
                    HITO_CUALITATIVO.id_actividad,
                    HITO_CUALITATIVO.nombre_hito_cl,
                    HITO_CUALITATIVO.descripcion_hito_cl
                FROM
                    HITO_CUALITATIVO
                WHERE
                    HITO_CUALITATIVO.id_actividad = $id_actividad
                ORDER BY
                    HITO_CUALITATIVO.nombre_hito_cl ASC
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

    public function get_hito_cuantitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "SELECT
                        HITO_CUANTITATIVO.id_hito_cn,
                        HITO_CUANTITATIVO.id_actividad,
                        HITO_CUANTITATIVO.nombre_hito_cn,
                        HITO_CUANTITATIVO.descripcion_hito_cn,
                        HITO_CUANTITATIVO.meta_hito_cn,
                        HITO_CUANTITATIVO.unidad_hito_cn
                    FROM
                        HITO_CUANTITATIVO
                    WHERE
                        HITO_CUANTITATIVO.id_hito_cn = $id_hito
                    ";
            $query = $this->db->query($sql);
            if (!$query) {
                return false;
            } else {
                if ($query->num_rows() != 1) {
                    return false;
                } else {
                    return $query->row();
                }
            }
        }
    }

    public function insert_hito_cuantitativo($id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "INSERT INTO HITO_CUANTITATIVO
                    (
                        HITO_CUANTITATIVO.id_actividad,
                        HITO_CUANTITATIVO.nombre_hito_cn,
                        HITO_CUANTITATIVO.descripcion_hito_cn,
                        HITO_CUANTITATIVO.meta_hito_cn,
                        HITO_CUANTITATIVO.unidad_hito_cn
                    )
                    VALUES
                    (
                        $id_actividad,
                        '$nombre_hito',
                        '$descripcion_hito',
                        $meta_hito,
                        '$unidad_hito'
                    )
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function update_hito_cuantitativo($id_hito, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "UPDATE HITO_CUANTITATIVO SET
                        HITO_CUANTITATIVO.nombre_hito_cn = '$nombre_hito',
                        HITO_CUANTITATIVO.descripcion_hito_cn = '$descripcion_hito',
                        HITO_CUANTITATIVO.meta_hito_cn = $meta_hito,
                        HITO_CUANTITATIVO.unidad_hito_cn = '$unidad_hito'
                    WHERE
                        HITO_CUANTITATIVO.id_hito_cn = $id_hito
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function delete_hito_cuantitativo($id_hito) {
        if(!is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "DELETE FROM HITO_CUANTITATIVO
                    WHERE
                        id_hito_cn = $id_hito
                    ";
            $query = $this->db->query($sql);
        }
    }
    
    public function registrar_avance_hito_cuantitativo_sin_documentos($id_hito, $cantidad_avance_hito, $fecha_avance_hito, $descripcion_avance_hito) {
        if(!is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "INSERT INTO AVANCE_HITO_CUANTITATIVO
                    (
                        AVANCE_HITO_CUANTITATIVO.id_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.fecha_avance_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.descripcion_avance_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn
                    )
                    VALUES
                    (
                        $id_hito,
                        $cantidad_avance_hito,
                        '$fecha_avance_hito',
                        '$descripcion_avance_hito',
                        false
                    )
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function get_hito_cualitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "SELECT
                        HITO_CUALITATIVO.id_hito_cl,
                        HITO_CUALITATIVO.id_actividad,
                        HITO_CUALITATIVO.nombre_hito_cl,
                        HITO_CUALITATIVO.descripcion_hito_cl
                    FROM
                        HITO_CUALITATIVO
                    WHERE
                        HITO_CUALITATIVO.id_hito_cl = $id_hito
                    ";
            $query = $this->db->query($sql);
            if (!$query) {
                return false;
            } else {
                if ($query->num_rows() != 1) {
                    return false;
                } else {
                    return $query->row();
                }
            }
        }
    }

    public function insert_hito_cualitativo($id_actividad, $nombre_hito, $descripcion_hito) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "INSERT INTO HITO_CUALITATIVO
                    (
                        HITO_CUALITATIVO.id_actividad,
                        HITO_CUALITATIVO.nombre_hito_cl,
                        HITO_CUALITATIVO.descripcion_hito_cl
                    )
                    VALUES
                    (
                        $id_actividad,
                        '$nombre_hito',
                        '$descripcion_hito'
                    )
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function update_hito_cualitativo($id_hito, $nombre_hito, $descripcion_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "UPDATE HITO_CUALITATIVO SET
                        HITO_CUALITATIVO.nombre_hito_cl = '$nombre_hito',
                        HITO_CUALITATIVO.descripcion_hito_cl = '$descripcion_hito'
                    WHERE
                        HITO_CUALITATIVO.id_hito_cl = $id_hito
                    ";
            $query = $this->db->query($sql);
        }
    }

    public function delete_hito_cualitativo($id_hito) {
        if(!is_numeric($id_hito)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "DELETE FROM HITO_CUALITATIVO
                    WHERE
                        id_hito_cl = $id_hito
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

}
