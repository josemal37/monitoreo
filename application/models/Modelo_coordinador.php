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
                    PROYECTO.presupuesto_proyecto,
                    INSTITUCION.id_institucion,
                    INSTITUCION.nombre_institucion,
                    INSTITUCION.sigla_institucion,
                    INSTITUCION.carpeta_institucion
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
                    $datos_hitos_cuantitativos = $this->get_hitos_cuantitativos_actividad($datos_actividad->id_actividad);
                    $datos['datos_hitos_cuantitativos'][$datos_actividad->nombre_actividad] = $datos_hitos_cuantitativos;
                    $datos_hitos_cualitativos = $this->get_hitos_cualitativos_actividad($datos_actividad->id_actividad);
                    $datos['datos_hitos_cualitativos'][$datos_actividad->nombre_actividad] = $datos_hitos_cualitativos;
                    $datos_indicadores_cuantitativos = $this->get_indicadores_cuantitativos($datos_actividad->id_actividad);
                    $datos['datos_indicadores_cuantitativos'][$datos_actividad->nombre_actividad] = $datos_indicadores_cuantitativos;
                    $datos_indicadores_cualitativos = $this->get_indicadores_cualitativos($datos_hitos_cualitativos);
                    $datos['datos_indicadores_cualitativos'][$datos_actividad->nombre_actividad] = $datos_indicadores_cualitativos;
                    $datos_gastos_actividad = $this->get_gastos_actividad($datos_actividad->id_actividad);
                    $datos['datos_gastos_actividad'][$datos_actividad->nombre_actividad] = $datos_gastos_actividad;
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
    
    public function get_indicadores_cuantitativos($id_actividad) {
        if(!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio');
        } else {
            $sql = "SELECT
                        INDICADOR_CUANTITATIVO.id_indicador_cn,
                        INDICADOR_CUANTITATIVO.id_tipo_indicador_cn,
                        INDICADOR_CUANTITATIVO.id_hito_cn,
                        INDICADOR_CUANTITATIVO.nombre_indicador_cn,
                        INDICADOR_CUANTITATIVO.aceptable_cn,
                        INDICADOR_CUANTITATIVO.limitado_cn,
                        INDICADOR_CUANTITATIVO.no_aceptable_cn,
                        TIPO_INDICADOR_CUANTITATIVO.nombre_tipo_indicador_cn,
                        TIPO_INDICADOR_CUANTITATIVO.descripcion_tipo_indicador_cn,
                        HITO_CUANTITATIVO.nombre_hito_cn
                    FROM
                        INDICADOR_CUANTITATIVO,
                        TIPO_INDICADOR_CUANTITATIVO,
                        HITO_CUANTITATIVO
                    WHERE
                        INDICADOR_CUANTITATIVO.id_tipo_indicador_cn = TIPO_INDICADOR_CUANTITATIVO.id_tipo_indicador_cn AND
                        INDICADOR_CUANTITATIVO.id_hito_cn = HITO_CUANTITATIVO.id_hito_cn AND
                        HITO_CUANTITATIVO.id_actividad = $id_actividad
                    GROUP BY
                        INDICADOR_CUANTITATIVO.id_indicador_cn
                    ";
            $query = $this->db->query($sql);
            if(!$query) {
                $datos = Array();
                return $datos;
            } else {
                if($query->num_rows() == 0) {
                    return $query->result();
                } else {
                    $indicadores = $query->result();
                    $i = 0;
                    foreach($indicadores as $indicador) {
                        $estado = 'Indefinido';
                        switch($indicador->nombre_tipo_indicador_cn) {
                            case 'Acumulativo':
                                $estado = $this->modelo_indicador_acumulativo->get_estado_indicador($indicador);
                                break;
                            case 'Porcentaje':
                                $estado = $this->modelo_indicador_porcentaje->get_estado_indicador($indicador);
                                break;
                            case 'promedio menor que':
                                $estado = $this->modelo_indicador_promedio_menor_que->get_estado_indicador($indicador);
                                break;
                        }
                        $indicadores[$i]->estado_indicador_cn = $estado;
                        $i = $i + 1;
                    }
                    return $indicadores;
                }
            }
        }
    }

    public function get_indicadores_cualitativos($datos_hitos_cualitativos) {
        $datos = Array();
        if (sizeof($datos_hitos_cualitativos) > 0) {
            foreach ($datos_hitos_cualitativos as $hito_cualitativo) {
                $estado = $this->modelo_indicador_cualitativo->get_estado_indicador($hito_cualitativo->id_hito_cl);
                $datos[$hito_cualitativo->nombre_hito_cl] = Array('nombre_indicador_cualitativo' => $hito_cualitativo->nombre_hito_cl, 'estado_indicador_cualitativo' => $estado);
            }
        } else {
            $datos = Array();
        }
        return $datos;
    }
    
    public function get_gastos_actividad($id_actividad) {
        if(!is_numeric($id_actividad)) {
            redirect(base_url() . 'coordinador');
        } else {
            $sql = "SELECT
                        GASTO_ACTIVIDAD.id_gasto_actividad,
                        GASTO_ACTIVIDAD.id_actividad,
                        GASTO_ACTIVIDAD.fecha_gasto_actividad,
                        GASTO_ACTIVIDAD.concepto_gasto_actividad,
                        GASTO_ACTIVIDAD.importe_gasto_actividad,
                        GASTO_ACTIVIDAD.respaldo_gasto_actividad
                    FROM
                        GASTO_ACTIVIDAD
                    WHERE
                        GASTO_ACTIVIDAD.id_actividad = $id_actividad
                    ";
            $query = $this->db->query($sql);
            if(!$query) {
                return false;
            } else {
                if($query->num_rows() == 0) {
                    return false;
                } else {
                    return $query->result();
                }
            }
        }
    }
    
    public function get_hito_cuantitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
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
    
    public function get_avances_hito_cuantitativo($id_hito) {
        if(!is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
        } else {
            $datos = Array();
            $this->db->trans_start();
            $sql = "SELECT
                        AVANCE_HITO_CUANTITATIVO.id_avance_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.id_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.fecha_avance_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.descripcion_avance_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn,
                        AVANCE_HITO_CUANTITATIVO.en_revision_avance_hito_cn
                    FROM
                        AVANCE_HITO_CUANTITATIVO
                    WHERE
                        AVANCE_HITO_CUANTITATIVO.id_hito_cn = $id_hito
                    ";
            $query = $this->db->query($sql);
            $datos['avances_hito_cuantitativo'] = $query->result();
            $i = 0;
            foreach ($datos['avances_hito_cuantitativo'] as $clave => $avance) {
                $id_avance = $avance->id_avance_hito_cn;
                $sql = "SELECT
                            DOCUMENTO_AVANCE_HITO_CUANTITATIVO.id_documento_avance_hito_cn,
                            DOCUMENTO_AVANCE_HITO_CUANTITATIVO.id_avance_hito_cn,
                            DOCUMENTO_AVANCE_HITO_CUANTITATIVO.titulo_documento_avance_hito_cn,
                            DOCUMENTO_AVANCE_HITO_CUANTITATIVO.descripcion_documento_avance_hito_cn,
                            DOCUMENTO_AVANCE_HITO_CUANTITATIVO.archivo_documento_avance_hito_cn
                        FROM
                            DOCUMENTO_AVANCE_HITO_CUANTITATIVO
                        WHERE
                            DOCUMENTO_AVANCE_HITO_CUANTITATIVO.id_avance_hito_cn = $id_avance
                        ";
                $query = $this->db->query($sql);
                $datos['documentos'][$i] = $query->result();
                $i = $i + 1;
            }
            $this->db->trans_complete();
            return $datos;
        }
    }
    
    public function get_hito_cualitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
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
    
    public function get_avances_hito_cualitativo($id_hito) {
        if(!is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
        } else {
            $sql = "SELECT
                        AVANCE_HITO_CUALITATIVO.id_avance_hito_cl,
                        AVANCE_HITO_CUALITATIVO.id_hito_cl,
                        AVANCE_HITO_CUALITATIVO.fecha_avance_hito_cl,
                        AVANCE_HITO_CUALITATIVO.titulo_avance_hito_cl,
                        AVANCE_HITO_CUALITATIVO.descripcion_avance_hito_cl,
                        AVANCE_HITO_CUALITATIVO.documento_avance_hito_cl,
                        AVANCE_HITO_CUALITATIVO.aprobado_avance_hito_cl,
                        AVANCE_HITO_CUALITATIVO.en_revision_avance_hito_cl
                    FROM
                        AVANCE_HITO_CUALITATIVO
                    WHERE
                        AVANCE_HITO_CUALITATIVO.id_hito_cl = $id_hito
                    ";
            $query = $this->db->query($sql);
            if(!$query) {
                return false;
            } else {
                if($query->num_rows() == 0) {
                    return false;
                } else {
                    return $query->result();
                }
            }
        }
    }
    
    public function get_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'coordinador');
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
    
    public function insert_indicador_cuantitativo($id_hito, $nombre_indicador, $tipo_indicador, $aceptable_indicador, $limitado_indicador, $no_aceptable_indicador) {
        if(!is_numeric($id_hito) || !is_numeric($tipo_indicador)) {
            redirect(base_url() . 'coordinador');
        } else {
            $sql = "INSERT INTO INDICADOR_CUANTITATIVO
                    (
                        INDICADOR_CUANTITATIVO.id_hito_cn,
                        INDICADOR_CUANTITATIVO.id_tipo_indicador_cn,
                        INDICADOR_CUANTITATIVO.nombre_indicador_cn,
                        INDICADOR_CUANTITATIVO.aceptable_cn,
                        INDICADOR_CUANTITATIVO.limitado_cn,
                        INDICADOR_CUANTITATIVO.no_aceptable_cn
                    )
                    VALUES
                    (
                        $id_hito,
                        $tipo_indicador,
                        '$nombre_indicador',
                        $aceptable_indicador,
                        $limitado_indicador,
                        $no_aceptable_indicador
                    )
                    ";
            $query = $this->db->query($sql);
        }
    }
    
    public function get_tipos_indicador_cuantitativo() {
        $sql = "SELECT
                    TIPO_INDICADOR_CUANTITATIVO.id_tipo_indicador_cn,
                    TIPO_INDICADOR_CUANTITATIVO.nombre_tipo_indicador_cn,
                    TIPO_INDICADOR_CUANTITATIVO.descripcion_tipo_indicador_cn
                FROM
                    TIPO_INDICADOR_CUANTITATIVO
                ";
        $query = $this->db->query($sql);
        if(!$query) {
            return false;
        } else {
            if($query->num_rows() == 0) {
                return false;
            } else {
                return $query->result();
            }
        }
    }
    
    public function modificar_estado_avance_hito_cuantitativo($id_avance_hito, $estado) {
        if(!is_numeric($id_avance_hito) ||!is_bool($estado)) {
            redirect(base_url() . 'coordinador');
        } else {
            $estado = ($estado) ? 'true' : 'false';
            $sql = "UPDATE AVANCE_HITO_CUANTITATIVO SET
                        AVANCE_HITO_CUANTITATIVO.en_revision_avance_hito_cn = false,
                        AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn = $estado
                    WHERE
                        AVANCE_HITO_CUANTITATIVO.id_avance_hito_cn = $id_avance_hito
                    ";
            $query = $this->db->query($sql);
        }
    }
    
    public function modificar_estado_avance_hito_cualitativo($id_avance_hito, $estado) {
        if(!is_numeric($id_avance_hito) ||!is_bool($estado)) {
            redirect(base_url() . 'coordinador');
        } else {
            $estado = ($estado) ? 'true' : 'false';
            $sql = "UPDATE AVANCE_HITO_CUALITATIVO SET
                        AVANCE_HITO_CUALITATIVO.en_revision_avance_hito_cl = false,
                        AVANCE_HITO_CUALITATIVO.aprobado_avance_hito_cl = $estado
                    WHERE
                        AVANCE_HITO_CUALITATIVO.id_avance_hito_cl = $id_avance_hito
                    ";
            $query = $this->db->query($sql);
        }
    }
    
    public function get_institucion($id_institucion) {
        if(!is_numeric($id_institucion)) {
            redirect(base_url() . 'coordinador');
        } else {
            $sql = "SELECT
                        INSTITUCION.id_institucion,
                        INSTITUCION.nombre_institucion,
                        INSTITUCION.sigla_institucion,
                        INSTITUCION.presupuesto_institucion,
                        INSTITUCION.carpeta_institucion,
                        INSTITUCIOn.activa_institucion
                    FROM
                        INSTITUCION
                    WHERE
                        INSTITUCION.id_institucion = $id_institucion
                    ";
            $query = $this->db->query($sql);
            if(!$query) {
                return false;
            } else {
                if($query->num_rows() != 1) {
                    return false;
                } else {
                    return $query->row();
                }
            }
        }
    }

}
