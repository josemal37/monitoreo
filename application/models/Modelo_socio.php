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

    public function get_prodoc_completo($id_prodoc) {
        try {
            $sql = "SELECT
                        PRODOC.id_prodoc,
                        PRODOC.nombre_prodoc,
                        PRODOC.descripcion_prodoc,
                        PRODOC.objetivo_global_prodoc,
                        PRODOC.objetivo_proyecto_prodoc
                    FROM
                        PRODOC
                    WHERE
                        PRODOC.id_prodoc = ?
                    ";
            $query = $this->db->query($sql, Array($id_prodoc));
            if (!$query) {
                return false;
            } else {
                if ($query->num_rows() == 0) {
                    return false;
                } else {
                    $prodoc = $query->row();
                    $id_prodoc = $prodoc->id_prodoc;
                    $prodoc->efectos = $this->get_efectos_prodoc($id_prodoc);
                    if($prodoc->efectos) {
                        $i = 0;
                        foreach($prodoc->efectos as $efecto) {
                            $prodoc->efectos[$i]->productos = $this->get_productos_efecto($efecto->id_efecto);
                            if($prodoc->efectos[$i]->productos) {
                                $j = 0;
                                foreach($prodoc->efectos[$i]->productos as $producto) {
                                    $prodoc->efectos[$i]->productos[$j]->metas_cuantitativas = $this->get_metas_producto_cuantitativa($producto->id_producto);
                                    $prodoc->efectos[$i]->productos[$j]->metas_cualitativas = $this->get_metas_producto_cuantitativa($producto->id_producto);
                                    $j += 1;
                                }
                            }
                            $i += 1;
                        }
                    }
                    return $prodoc;
                }
            }
        } catch (Exception $ex) {
            
        }
    }
    
    public function get_efectos_prodoc($id_prodoc) {
        if(!is_numeric($id_prodoc)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            EFECTO.id_efecto,
                            EFECTO.id_prodoc,
                            EFECTO.nombre_efecto,
                            EFECTO.descripcion_efecto
                        FROM
                            EFECTO
                        WHERE
                            EFECTO.id_prodoc = ?
                        ";
                $query = $this->db->query($sql, Array($id_prodoc));
                if(!$query) {
                    return false;
                } else {
                    if($query->num_rows() == 0) {
                        return false;
                    } else {
                        return $query->result();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function get_productos_efecto($id_efecto) {
        if(!is_numeric($id_efecto)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            PRODUCTO.id_producto,
                            PRODUCTO.id_efecto, 
                            PRODUCTO.nombre_producto,
                            PRODUCTO.descripcion_producto
                        FROM
                            PRODUCTO
                        WHERE
                            PRODUCTO.id_efecto = ?
                        ";
                $query = $this->db->query($sql, Array($id_efecto));
                if(!$query) {
                    return false;
                } else {
                    if($query->num_rows() == 0) {
                        return false;
                    } else {
                        return $query->result();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function get_metas_producto_cuantitativa($id_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.id_producto,
                            META_PRODUCTO_CUANTITATIVA.cantidad_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.unidad_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.nombre_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.descripcion_meta_producto_cuantitativa,
                            COALESCE(SUM(AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn), 0) as avance_meta_producto_cuantitativa
                        FROM
                            META_PRODUCTO_CUANTITATIVA
                        LEFT JOIN META_ACTIVIDAD_APORTA_META_PRODUCTO_CN ON
                            META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_meta_producto_cuantitativa = META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa
                        LEFT JOIN HITO_CUANTITATIVO ON
                            META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn = HITO_CUANTITATIVO.id_hito_cn
                        LEFT JOIN AVANCE_HITO_CUANTITATIVO ON
                            HITO_CUANTITATIVO.id_hito_cn = AVANCE_HITO_CUANTITATIVO.id_hito_cn AND
                            AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn = true
                        WHERE
                            META_PRODUCTO_CUANTITATIVA.id_producto = ?
                        GROUP BY META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa
                        ";
                $query = $this->db->query($sql, Array($id_producto));
                if(!$query) {
                    return false;
                } else {
                    if($query->num_rows() == 0) {
                        return false;
                    } else {
                        return $query->result();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function get_metas_producto_cualitativa($id_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            META_PRODUCTO_CUALITATIVA.id_meta_producto_cualitativa,
                            META_PRODUCTO_CUALITATIVA.id_producto,
                            META_PRODUCTO_CUALITATIVA.nombre_meta_producto_cualitativa,
                            META_PRODUCTO_CUALITATIVA.descripcion_meta_producto_cualitativa
                        FROM
                            META_PRODUCTO_CUALITATIVA
                        WHERE
                            META_PRODUCTO_CUALITATIVA.id_producto = ?
                        ";
                $query = $this->db->query($sql, Array($id_producto));
                if(!$query) {
                    return false;
                } else {
                    if($query->num_rows() == 0) {
                        return false;
                    } else {
                        return $query->result();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_id_prodoc() {
        try {
            $sql = "SELECT
                        PRODOC.id_prodoc,
                        PRODOC.nombre_prodoc,
                        PRODOC.descripcion_prodoc,
                        PRODOC.objetivo_global_prodoc,
                        PRODOC.objetivo_proyecto_prodoc
                    FROM
                        PRODOC
                    ";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                $prodoc = $query->row();
                return $prodoc->id_prodoc;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    public function get_proyectos_socio() {
        try {
            $this->db->trans_start();
            $id_institucion = $this->session->userdata('id_institucion');
            $sql = "SELECT 
                        PROYECTO.id_proyecto, 
                        PROYECTO.nombre_proyecto, 
                        PROYECTO.descripcion_proyecto,
                        PROYECTO.presupuesto_proyecto,
                        ANIO.valor_anio
                    FROM 
                        PROYECTO, 
                        PROYECTO_GLOBAL,
                        INSTITUCION,
                        ANIO,
                        PROYECTO_TIENE_ANIO
                    WHERE 
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion AND
                        PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto AND
                        PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio AND
                        PROYECTO_GLOBAL.id_institucion = ? AND
                        PROYECTO.en_edicion = false
                        ";
            $query = $this->db->query($sql, Array($id_institucion));
            $this->db->trans_complete();
            if (!$query) {
                return false;
            } else {
                if ($query->num_rows() == 0) {
                    return false;
                } else {
                    return $query->result();
                }
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            redirect(base_url() . 'socio/error');
        }
    }
    
    public function get_proyecto_global($id_institucion) {
        if(!is_numeric($id_institucion)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            PROYECTO_GLOBAL.id_proyecto_global,
                            PROYECTO_GLOBAL.id_institucion,
                            PROYECTO_GLOBAL.nombre_proyecto_global,
                            PROYECTO_GLOBAL.descripcion_proyecto_global,
                            PROYECTO_GLOBAL.presupuesto_proyecto_global
                        FROM
                            PROYECTO_GLOBAL
                        WHERE
                            PROYECTO_GLOBAL.id_institucion = ?
                        ";
                $query = $this->db->query($sql, Array($id_institucion));
                if(!$query) {
                    return false;
                } else {
                    if($query->num_rows() != 1) {
                        return false;
                    } else {
                        return $query->row();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_proyecto_completo_activo($id_proyecto) {
        try {
            if (!is_numeric($id_proyecto)) {
                redirect(base_url() . 'socio/error');
            }
            $id_institucion = $this->session->userdata('id_institucion');
            $datos = Array();
            $this->db->trans_start();
            $sql = "SELECT 
                        PROYECTO.id_proyecto, 
                        PROYECTO.nombre_proyecto, 
                        PROYECTO.descripcion_proyecto,
                        PROYECTO.presupuesto_proyecto,
                        ANIO.id_anio,
                        ANIO.valor_anio
                    FROM 
                        PROYECTO, 
                        PROYECTO_GLOBAL,
                        INSTITUCION,
                        PROYECTO_TIENE_ANIO,
                        ANIO
                    WHERE 
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion AND
                        PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto AND
                        PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio AND
                        PROYECTO.en_edicion = false AND
                        PROYECTO_GLOBAL.id_institucion = ? AND
                        PROYECTO.id_proyecto = ?
                    ";
            $query_proyecto = $this->db->query($sql, Array($id_institucion, $id_proyecto));
            if ($query_proyecto->num_rows() == 1) {
                $datos_proyecto = $query_proyecto->row();
                $datos['datos_proyecto'] = $datos_proyecto;

                $datos_actividades = $this->get_actividades_activas_proyecto($id_proyecto);
                $datos['datos_actividades'] = $datos_actividades;

                if (sizeof($datos_actividades) > 0) {
                    foreach ($datos_actividades as $datos_actividad) {
                        $datos_hitos_cuantitativos = $this->get_hitos_cuantitativos_actividad_con_avance($datos_actividad->id_actividad);
                        $datos['datos_hitos_cuantitativos'][$datos_actividad->nombre_actividad] = $datos_hitos_cuantitativos;
                        $datos_hitos_cualitativos = $this->get_hitos_cualitativos_actividad($datos_actividad->id_actividad);
                        $datos['datos_hitos_cualitativos'][$datos_actividad->nombre_actividad] = $datos_hitos_cualitativos;
                        $datos_indicadores_cuantitativos = $this->get_indicadores_cuantitativos($datos_actividad->id_actividad);
                        $datos['datos_indicadores_cuantitativos'][$datos_actividad->nombre_actividad] = $datos_indicadores_cuantitativos;
                        $datos_indicadores_cualitativos = $this->get_indicadores_cualitativos($datos_hitos_cualitativos);
                        $datos['datos_indicadores_cualitativos'][$datos_actividad->nombre_actividad] = $datos_indicadores_cualitativos;
                        //$datos_gastos_actividad = $this->get_gastos_actividad($datos_actividad->id_actividad);
                        //$datos['datos_gastos_actividad'][$datos_actividad->nombre_actividad] = $datos_gastos_actividad;
                        $datos['datos_gastos_actividad'][$datos_actividad->nombre_actividad] = false;
                    }
                }
            } else {
                $datos['error'] = 'error_proyecto';
            }
            $this->db->trans_complete();
            return $datos;
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            redirect(base_url() . 'socio/error');
        }
    }
    
    public function get_id_proyecto_completo_gestion_actual() {
        $gestion_actual = $this->get_gestion_actual();
        if($gestion_actual) {
            $id_anio_actual = $gestion_actual->id_anio;
            try {
                $sql = "SELECT
                            PROYECTO.id_proyecto
                        FROM
                            PROYECTO
                        LEFT JOIN PROYECTO_TIENE_ANIO ON
                            PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto
                        LEFT JOIN ANIO ON
                            PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio
                        LEFT JOIN PROYECTO_GLOBAL ON
                            PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global
                        WHERE
                            PROYECTO_GLOBAL.id_institucion = ? AND
                            ANIO.id_anio = ?
                        ";
                $id_institucion = $this->session->userdata('id_institucion');
                $query = $this->db->query($sql, Array($id_institucion, $id_anio_actual));
                if(!$query) {
                    return false;
                } else {
                    if($query->num_rows() == 0) {
                        return false;
                    } else {
                        return $query->row()->id_proyecto;
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        } else {
            return false;
        }
    }

    public function get_proyecto_completo_en_edicion($id_proyecto) {
        try {
            if (!is_numeric($id_proyecto)) {
                redirect(base_url() . 'socio/error');
            }
            $id_institucion = $this->session->userdata('id_institucion');
            $datos = Array();
            $this->db->trans_start();
            $sql = "SELECT 
                        PROYECTO.id_proyecto, 
                        PROYECTO.nombre_proyecto, 
                        PROYECTO.descripcion_proyecto,
                        PROYECTO.presupuesto_proyecto,
                        ANIO.id_anio,
                        ANIO.valor_anio
                    FROM 
                        PROYECTO, 
                        PROYECTO_GLOBAL,
                        INSTITUCION,
                        PROYECTO_TIENE_ANIO,
                        ANIO
                    WHERE 
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion AND
                        PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto AND
                        PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio AND
                        PROYECTO_GLOBAL.id_institucion = ? AND
                        PROYECTO.id_proyecto = ?
                    ";
            $query_proyecto = $this->db->query($sql, Array($id_institucion, $id_proyecto));
            if ($query_proyecto->num_rows() == 1) {
                $datos_proyecto = $query_proyecto->row();
                $datos['datos_proyecto'] = $datos_proyecto;

                $datos_actividades = $this->get_actividades_en_edicion_proyecto($id_proyecto);
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
                $this->db->trans_rollback();
                redirect(base_url() . 'socio/error');
            }
            $this->db->trans_complete();
            return $datos;
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            redirect(base_url() . 'socio/error');
        }
    }
    
    public function get_proyecto_completo_en_reformulacion($id_proyecto) {
        if(!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $this->db->trans_start();
                $proyecto = $this->get_proyecto($id_proyecto);
                if($proyecto) {
                    $actividades = $this->get_actividades_en_reformulacion_proyecto($id_proyecto);
                    $proyecto->actividades = $actividades;
                    if(sizeof($actividades) > 0) {
                        $i = 0;
                        foreach ($actividades as $actividad) {
                            $proyecto->actividades[$i]->hitos_cuantitativos = $this->get_hitos_cuantitativos_actividad_en_reformulacion($actividad->id_actividad);
                            $proyecto->actividades[$i]->hitos_cualitativos = $this->get_hitos_cualitativos_actividad_en_reformulacion($actividad->id_actividad);
                            $i += 1;
                        }
                    }
                } else {
                    redirect(base_url() . 'socio/error');
                }
                $this->db->trans_complete();
                return $proyecto;
                $this->db->trans_complete();
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    private function get_actividades_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        }
        try {
            $sql = "SELECT
                        ACTIVIDAD.id_actividad,
                        ACTIVIDAD.nombre_actividad,
                        ACTIVIDAD.descripcion_actividad,
                        ACTIVIDAD.fecha_inicio_actividad,
                        ACTIVIDAD.fecha_fin_actividad,
                        ACTIVIDAD.presupuesto_actividad,
                        ACTIVIDAD.en_edicion_actividad,
                        ACTIVIDAD.contraparte_actividad,
                        PRODUCTO.id_producto,
                        PRODUCTO.nombre_producto
                    FROM
                        ACTIVIDAD
                    LEFT JOIN PRODUCTO_RECIBE_ACTIVIDAD ON PRODUCTO_RECIBE_ACTIVIDAD.id_actividad = ACTIVIDAD.id_actividad
                    LEFT JOIN PRODUCTO ON PRODUCTO_RECIBE_ACTIVIDAD.id_producto = PRODUCTO.id_producto
                    WHERE
                        ACTIVIDAD.id_proyecto = ?
                    ORDER BY
                        ACTIVIDAD.nombre_actividad ASC
                    ";
            $query_actividades = $this->db->query($sql, Array($id_proyecto));
            return $query_actividades->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    private function get_actividades_activas_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        }
        try {
            $sql = "SELECT
                        ACTIVIDAD.id_actividad,
                        ACTIVIDAD.nombre_actividad,
                        ACTIVIDAD.descripcion_actividad,
                        ACTIVIDAD.fecha_inicio_actividad,
                        ACTIVIDAD.fecha_fin_actividad,
                        ACTIVIDAD.presupuesto_actividad,
                        ACTIVIDAD.en_edicion_actividad,
                        ACTIVIDAD.contraparte_actividad,
                        ACTIVIDAD.gasto_actividad,
                        PRODUCTO.id_producto,
                        PRODUCTO.nombre_producto
                    FROM
                        ACTIVIDAD
                    LEFT JOIN PRODUCTO_RECIBE_ACTIVIDAD ON PRODUCTO_RECIBE_ACTIVIDAD.id_actividad = ACTIVIDAD.id_actividad
                    LEFT JOIN PRODUCTO ON PRODUCTO_RECIBE_ACTIVIDAD.id_producto = PRODUCTO.id_producto
                    WHERE
                        ACTIVIDAD.en_edicion_actividad = false AND
                        ACTIVIDAD.en_reformulacion_actividad = false AND
                        ACTIVIDAD.id_proyecto = ?
                    ORDER BY
                        ACTIVIDAD.nombre_actividad ASC
                    ";
            $query_actividades = $this->db->query($sql, Array($id_proyecto));
            return $query_actividades->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    private function get_actividades_en_edicion_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        }
        try {
            $sql = "SELECT
                        ACTIVIDAD.id_actividad,
                        ACTIVIDAD.nombre_actividad,
                        ACTIVIDAD.descripcion_actividad,
                        ACTIVIDAD.fecha_inicio_actividad,
                        ACTIVIDAD.fecha_fin_actividad,
                        ACTIVIDAD.presupuesto_actividad,
                        ACTIVIDAD.en_edicion_actividad,
                        ACTIVIDAD.contraparte_actividad,
                        PRODUCTO.id_producto,
                        PRODUCTO.nombre_producto
                    FROM
                        ACTIVIDAD
                    LEFT JOIN PRODUCTO_RECIBE_ACTIVIDAD ON PRODUCTO_RECIBE_ACTIVIDAD.id_actividad = ACTIVIDAD.id_actividad
                    LEFT JOIN PRODUCTO ON PRODUCTO_RECIBE_ACTIVIDAD.id_producto = PRODUCTO.id_producto
                    WHERE
                        ACTIVIDAD.en_edicion_actividad = true AND
                        ACTIVIDAD.en_reformulacion_actividad = false AND
                        ACTIVIDAD.id_proyecto = ?
                    ORDER BY
                        ACTIVIDAD.nombre_actividad ASC
                    ";
            $query_actividades = $this->db->query($sql, Array($id_proyecto));
            return $query_actividades->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    private function get_actividades_en_reformulacion_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        }
        try {
            $sql = "SELECT
                        ACTIVIDAD.id_actividad,
                        ACTIVIDAD.nombre_actividad,
                        ACTIVIDAD.descripcion_actividad,
                        ACTIVIDAD.fecha_inicio_actividad,
                        ACTIVIDAD.fecha_fin_actividad,
                        ACTIVIDAD.presupuesto_actividad,
                        ACTIVIDAD.en_edicion_actividad,
                        ACTIVIDAD.contraparte_actividad,
                        PRODUCTO.id_producto,
                        PRODUCTO.nombre_producto
                    FROM
                        ACTIVIDAD
                    LEFT JOIN PRODUCTO_RECIBE_ACTIVIDAD ON PRODUCTO_RECIBE_ACTIVIDAD.id_actividad = ACTIVIDAD.id_actividad
                    LEFT JOIN PRODUCTO ON PRODUCTO_RECIBE_ACTIVIDAD.id_producto = PRODUCTO.id_producto
                    WHERE
                        ACTIVIDAD.en_edicion_actividad = false AND
                        ACTIVIDAD.en_reformulacion_actividad = true AND
                        ACTIVIDAD.id_proyecto = ?
                    ORDER BY
                        ACTIVIDAD.nombre_actividad ASC
                    ";
            $query_actividades = $this->db->query($sql, Array($id_proyecto));
            return $query_actividades->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    public function terminar_reformulacion_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "UPDATE ACTIVIDAD SET
                            ACTIVIDAD.en_reformulacion_actividad = false
                        WHERE
                            ACTIVIDAD.id_proyecto = ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto));
                $this->db->trans_complete();
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    private function get_hitos_cuantitativos_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        }
        try {
            $sql = "SELECT
                        HITO_CUANTITATIVO.id_hito_cn,
                        HITO_CUANTITATIVO.id_actividad,
                        HITO_CUANTITATIVO.nombre_hito_cn,
                        HITO_CUANTITATIVO.descripcion_hito_cn,
                        HITO_CUANTITATIVO.meta_hito_cn,
                        HITO_CUANTITATIVO.unidad_hito_cn,
                        META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.nombre_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.cantidad_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.unidad_meta_producto_cuantitativa
                    FROM
                        HITO_CUANTITATIVO
                    LEFT JOIN META_ACTIVIDAD_APORTA_META_PRODUCTO_CN ON META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn = HITO_CUANTITATIVO.id_hito_cn
                    LEFT JOIN META_PRODUCTO_CUANTITATIVA ON META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa = META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_meta_producto_cuantitativa
                    WHERE
                        HITO_CUANTITATIVO.id_actividad = ?
                    ORDER BY
                        HITO_CUANTITATIVO.nombre_hito_cn ASC
                    ";
            $query_indicadores = $this->db->query($sql, Array($id_actividad));
            return $query_indicadores->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    private function get_hitos_cuantitativos_actividad_con_avance($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        }
        try {
            $sql = "SELECT
                        HITO_CUANTITATIVO.id_hito_cn,
                        HITO_CUANTITATIVO.id_actividad,
                        HITO_CUANTITATIVO.nombre_hito_cn,
                        HITO_CUANTITATIVO.descripcion_hito_cn,
                        HITO_CUANTITATIVO.meta_hito_cn,
                        HITO_CUANTITATIVO.unidad_hito_cn,
                        META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.nombre_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.cantidad_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.unidad_meta_producto_cuantitativa,
                        COALESCE(SUM(AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn), 0) as cantidad_avance_cn
                    FROM
                        HITO_CUANTITATIVO
                    LEFT JOIN META_ACTIVIDAD_APORTA_META_PRODUCTO_CN ON META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn = HITO_CUANTITATIVO.id_hito_cn
                    LEFT JOIN META_PRODUCTO_CUANTITATIVA ON META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa = META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_meta_producto_cuantitativa
                    LEFT JOIN 
                        AVANCE_HITO_CUANTITATIVO ON AVANCE_HITO_CUANTITATIVO.id_hito_cn = HITO_CUANTITATIVO.id_hito_cn AND
                        AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn = true
                    WHERE
                        HITO_CUANTITATIVO.id_actividad = ?
                    GROUP BY
                        HITO_CUANTITATIVO.id_hito_cn
                    ORDER BY
                        HITO_CUANTITATIVO.nombre_hito_cn ASC
                    ";
            $query_indicadores = $this->db->query($sql, Array($id_actividad));
            return $query_indicadores->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    private function get_hitos_cualitativos_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        }
        try {
            $sql = "SELECT
                        HITO_CUALITATIVO.id_hito_cl,
                        HITO_CUALITATIVO.id_actividad,
                        HITO_CUALITATIVO.nombre_hito_cl,
                        HITO_CUALITATIVO.descripcion_hito_cl
                    FROM
                        HITO_CUALITATIVO
                    WHERE
                        HITO_CUALITATIVO.id_actividad = ?
                    ORDER BY
                        HITO_CUALITATIVO.nombre_hito_cl ASC
                    ";
            $query_indicadores = $this->db->query($sql, Array($id_actividad));
            return $query_indicadores->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    private function get_hitos_cuantitativos_actividad_en_reformulacion($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        }
        try {
            $sql = "SELECT
                        HITO_CUANTITATIVO.id_hito_cn,
                        HITO_CUANTITATIVO.id_actividad,
                        HITO_CUANTITATIVO.nombre_hito_cn,
                        HITO_CUANTITATIVO.descripcion_hito_cn,
                        HITO_CUANTITATIVO.meta_hito_cn,
                        HITO_CUANTITATIVO.unidad_hito_cn,
                        META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.nombre_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.cantidad_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.unidad_meta_producto_cuantitativa,
                        COUNT(AVANCE_HITO_CUANTITATIVO.id_avance_hito_cn) as numero_avances_cn
                    FROM
                        HITO_CUANTITATIVO
                    LEFT JOIN META_ACTIVIDAD_APORTA_META_PRODUCTO_CN ON 
                        META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn = HITO_CUANTITATIVO.id_hito_cn
                    LEFT JOIN META_PRODUCTO_CUANTITATIVA ON 
                        META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa = META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_meta_producto_cuantitativa
                    LEFT JOIN AVANCE_HITO_CUANTITATIVO ON
                        AVANCE_HITO_CUANTITATIVO.id_hito_cn = HITO_CUANTITATIVO.id_hito_cn
                    WHERE
                        HITO_CUANTITATIVO.id_actividad = ?
                    GROUP BY 
                        HITO_CUANTITATIVO.id_hito_cn
                    ORDER BY
                        HITO_CUANTITATIVO.nombre_hito_cn ASC
                    ";
            $query_indicadores = $this->db->query($sql, Array($id_actividad));
            return $query_indicadores->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    private function get_hitos_cualitativos_actividad_en_reformulacion($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        }
        try {
            $sql = "SELECT
                        HITO_CUALITATIVO.id_hito_cl,
                        HITO_CUALITATIVO.id_actividad,
                        HITO_CUALITATIVO.nombre_hito_cl,
                        HITO_CUALITATIVO.descripcion_hito_cl,
                        COUNT(AVANCE_HITO_CUALITATIVO.id_avance_hito_cl) as numero_avances_cl
                    FROM
                        HITO_CUALITATIVO
                    LEFT JOIN AVANCE_HITO_CUALITATIVO ON
                        AVANCE_HITO_CUALITATIVO.id_hito_cl = HITO_CUALITATIVO.id_hito_cl
                    WHERE
                        HITO_CUALITATIVO.id_actividad = ?
                    GROUP BY
                        HITO_CUALITATIVO.id_hito_cl
                    ORDER BY
                        HITO_CUALITATIVO.nombre_hito_cl ASC
                    ";
            $query_indicadores = $this->db->query($sql, Array($id_actividad));
            return $query_indicadores->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    private function get_indicadores_cuantitativos($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
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
                            HITO_CUANTITATIVO.id_actividad = ?
                        GROUP BY
                            INDICADOR_CUANTITATIVO.id_indicador_cn
                        ";
                $query = $this->db->query($sql, Array($id_actividad));
                if (!$query) {
                    $datos = Array();
                    return $datos;
                } else {
                    if ($query->num_rows() == 0) {
                        return $query->result();
                    } else {
                        $indicadores = $query->result();
                        $i = 0;
                        foreach ($indicadores as $indicador) {
                            $estado = 'Indefinido';
                            switch ($indicador->nombre_tipo_indicador_cn) {
                                case 'Acumulativo':
                                    $estado = $this->modelo_indicador_acumulativo->get_estado_indicador($indicador);
                                    break;
                                case 'Porcentaje':
                                    $estado = $this->modelo_indicador_porcentaje->get_estado_indicador($indicador);
                                    break;
                                case 'Promedio menor que':
                                    $estado = $this->modelo_indicador_promedio_menor_que->get_estado_indicador($indicador);
                                    break;
                            }
                            $indicadores[$i]->estado_indicador_cn = $estado;
                            $i = $i + 1;
                        }
                        return $indicadores;
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    private function get_indicadores_cualitativos($datos_hitos_cualitativos) {
        $datos = Array();
        try {
            if (sizeof($datos_hitos_cualitativos) > 0) {
                foreach ($datos_hitos_cualitativos as $hito_cualitativo) {
                    $estado = $this->modelo_indicador_cualitativo->get_estado_indicador($hito_cualitativo->id_hito_cl);
                    $datos[$hito_cualitativo->nombre_hito_cl] = Array('nombre_indicador_cualitativo' => $hito_cualitativo->nombre_hito_cl, 'estado_indicador_cualitativo' => $estado);
                }
            } else {
                $datos = Array();
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
        return $datos;
    }

    public function get_gastos_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
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
                            GASTO_ACTIVIDAD.id_actividad = ?
                        ";
                $query = $this->db->query($sql, Array($id_actividad));
                if (!$query) {
                    return false;
                } else {
                    if ($query->num_rows() == 0) {
                        return false;
                    } else {
                        return $query->result();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_proyectos_en_edicion() {
        try {
            $id_institucion = $this->session->userdata('id_institucion');
            $sql = "SELECT 
                        PROYECTO.id_proyecto, 
                        PROYECTO.nombre_proyecto, 
                        PROYECTO.descripcion_proyecto,
                        ANIO.valor_anio
                    FROM 
                        PROYECTO, 
                        PROYECTO_GLOBAL,
                        PROYECTO_TIENE_ANIO,
                        ANIO,
                        INSTITUCION
                    WHERE 
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion AND
                        PROYECTO_GLOBAL.id_institucion = ? AND
                        PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto AND
                        PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio AND
                        PROYECTO.en_edicion = true
                    ORDER BY
                        ANIO.valor_anio ASC
                        ";
            $query = $this->db->query($sql, Array($id_institucion));
            if (!$query) {
                return false;
            } else {
                if ($query->num_rows() == 0) {
                    return false;
                } else {
                    return $query->result();
                }
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }
    
    public function get_proyectos_en_reformulacion() {
        try {
            $id_institucion = $this->session->userdata('id_institucion');
            $sql = "SELECT DISTINCT
                        PROYECTO.id_proyecto, 
                        PROYECTO.nombre_proyecto, 
                        PROYECTO.descripcion_proyecto,
                        ANIO.valor_anio
                    FROM
                        PROYECTO
                    LEFT JOIN PROYECTO_GLOBAL ON
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global
                    LEFT JOIN PROYECTO_TIENE_ANIO ON
                        PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto
                    LEFT JOIN ANIO ON
                        PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio
                    LEFT JOIN ACTIVIDAD ON
                        ACTIVIDAD.id_proyecto = PROYECTO.id_proyecto
                    WHERE
                        ACTIVIDAD.en_reformulacion_actividad = true AND
                        PROYECTO_GLOBAL.id_institucion = ?
                    ";
            $query = $this->db->query($sql, Array($id_institucion));
            if(!$query) {
                return false;
            } else {
                if($query->num_rows() == 0) {
                    return false;
                } else {
                    return $query->result();
                }
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    public function get_proyecto($id_proyecto) {
        try {
            $id_institucion = $this->session->userdata('id_institucion');
            if (!is_numeric($id_proyecto)) {
                redirect(base_url() . 'socio/error');
            } else {
                $sql = "SELECT
                            PROYECTO.id_proyecto,
                            PROYECTO.nombre_proyecto,
                            PROYECTO.descripcion_proyecto,
                            PROYECTO.presupuesto_proyecto,
                            ANIO.id_anio,
                            ANIO.valor_anio
                        FROM
                            PROYECTO,
                            PROYECTO_GLOBAL,
                            INSTITUCION,
                            PROYECTO_TIENE_ANIO,
                            ANIO
                        WHERE
                            PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                            PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion AND
                            PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto AND
                            PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio AND
                            PROYECTO_GLOBAL.id_institucion = ? AND
                            PROYECTO.id_proyecto = ?
                        ";
                $query = $this->db->query($sql, Array($id_institucion, $id_proyecto));
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
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    public function insert_proyecto($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_anio) {
        try {
            $id_institucion = $this->session->userdata('id_institucion');
            $this->db->trans_start();
            $sql = "SELECT
                        PROYECTO_TIENE_ANIO.id_proyecto
                    FROM
                        PROYECTO_TIENE_ANIO,
                        ANIO,
                        PROYECTO,
                        PROYECTO_GLOBAL,
                        INSTITUCION
                    WHERE
                        PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio AND
                        PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto AND
                        PROYECTO.id_proyecto_global = PROYECTO_GLOBAL.id_proyecto_global AND
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion AND
                        ANIO.id_anio = ? AND
                        INSTITUCION.id_institucion = ?
                    ";
            $query = $this->db->query($sql, Array($id_anio, $id_institucion));
            if($query->num_rows() > 0) {
                $this->session->set_flashdata('poa_gestion_registrado', 'El POA para la gestión seleccionada anteriormente ya fue registrado.');
                redirect(base_url() . 'socio/registrar_nuevo_proyecto', 'refresh');
            } else {
                $sql = "SELECT
                            PROYECTO_GLOBAL.id_proyecto_global
                        FROM
                            PROYECTO_GLOBAL
                        WHERE
                            PROYECTO_GLOBAL.id_institucion = ?
                        ";
                $query = $this->db->query($sql, Array($id_institucion));
                if($query->num_rows() > 0) {
                    $datos_proyecto_global = $query->row();
                    $id_proyecto_global = $datos_proyecto_global->id_proyecto_global;
                    $sql = "INSERT INTO PROYECTO
                            (
                                nombre_proyecto,
                                descripcion_proyecto,
                                presupuesto_proyecto,
                                id_proyecto_global,
                                en_edicion
                            )
                            VALUES
                            (
                                ?,
                                ?,
                                ?,
                                ?,
                                true
                            )
                            ";
                    $query = $this->db->query($sql, Array($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_proyecto_global));
                    $id_proyecto = $this->db->insert_id();
                    $sql = "INSERT INTO PROYECTO_TIENE_ANIO
                            (
                                PROYECTO_TIENE_ANIO.id_proyecto,
                                PROYECTO_TIENE_ANIO.id_anio
                            )
                            VALUES
                            (
                                ?,
                                ?
                            )
                            ";
                    $query = $this->db->query($sql, Array($id_proyecto, $id_anio));
                    $this->db->trans_complete();
                    return $id_proyecto;
                } else {
                    redirect(base_url() . 'socio/error');
                }
            }
            $this->db->trans_complete();
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    public function update_proyecto($id_proyecto, $nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_anio, $id_anio_anterior) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_anio) || !is_numeric($id_anio_anterior)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $this->db->trans_start();
                $sql = "SELECT
                            PROYECTO.id_proyecto_global
                        FROM
                            PROYECTO
                        WHERE
                            PROYECTO.id_proyecto = ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto));
                $proyecto = $query->row();
                $id_proyecto_global = $proyecto->id_proyecto_global;
                $sql = "SELECT
                            PROYECTO_TIENE_ANIO.id_anio
                        FROM
                            PROYECTO_TIENE_ANIO,
                            PROYECTO
                        WHERE
                            PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto AND
                            PROYECTO.id_proyecto_global = ? AND
                            PROYECTO_TIENE_ANIO.id_anio = ? AND
                            PROYECTO_TIENE_ANIO.id_anio != ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto_global, $id_anio, $id_anio_anterior));
                if($query->num_rows() == 0) {
                    $sql = "UPDATE PROYECTO SET
                                PROYECTO.nombre_proyecto = ?,
                                PROYECTO.descripcion_proyecto = ?,
                                PROYECTO.presupuesto_proyecto = ? 
                            WHERE
                                PROYECTO.id_proyecto = ?
                            ";
                    $query = $this->db->query($sql, Array($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_proyecto));
                    $sql = "SELECT
                                PROYECTO_TIENE_ANIO.id_anio
                            FROM
                                PROYECTO_TIENE_ANIO,
                                PROYECTO
                            WHERE
                                PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto AND
                                PROYECTO.id_proyecto_global = ? AND
                                PROYECTO_TIENE_ANIO.id_anio = ?
                            ";
                    $query = $this->db->query($sql, Array($id_proyecto_global, $id_anio_anterior));
                    if($query->num_rows() > 0) {
                        $sql = "DELETE FROM PROYECTO_TIENE_ANIO
                                WHERE
                                    PROYECTO_TIENE_ANIO.id_proyecto = ? AND
                                    PROYECTO_TIENE_ANIO.id_anio = ?
                                ";
                        $query = $this->db->query($sql, Array($id_proyecto, $id_anio_anterior));
                        $sql = "INSERT INTO PROYECTO_TIENE_ANIO
                                (
                                    PROYECTO_TIENE_ANIO.id_proyecto,
                                    PROYECTO_TIENE_ANIO.id_anio
                                )
                                VALUES
                                (
                                    ?,
                                    ?
                                )
                                ";
                        $query = $this->db->query($sql, Array($id_proyecto, $id_anio));
                    }
                } else {
                    $this->session->set_flashdata('poa_gestion_registrado', 'El POA para la gestión seleccionada anteriormente ya fue registrado.');
                redirect(base_url() . 'socio/modificar_proyecto/' . $id_proyecto, 'refresh');
                }
                $this->db->trans_complete();
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            ACTIVIDAD.id_proyecto,
                            ACTIVIDAD.id_actividad,
                            ACTIVIDAD.nombre_actividad,
                            ACTIVIDAD.descripcion_actividad,
                            ACTIVIDAD.fecha_inicio_actividad,
                            ACTIVIDAD.fecha_fin_actividad,
                            ACTIVIDAD.presupuesto_actividad,
                            ACTIVIDAD.en_edicion_actividad,
                            ACTIVIDAD.contraparte_actividad,
                            ACTIVIDAD.gasto_actividad,
                            PRODUCTO.id_producto,
                            PRODUCTO.nombre_producto
                        FROM
                            ACTIVIDAD
                        LEFT JOIN PRODUCTO_RECIBE_ACTIVIDAD ON PRODUCTO_RECIBE_ACTIVIDAD.id_actividad = ACTIVIDAD.id_actividad
                        LEFT JOIN PRODUCTO ON PRODUCTO_RECIBE_ACTIVIDAD.id_producto = PRODUCTO.id_producto
                        WHERE
                            ACTIVIDAD.id_actividad = ?
                        ";
                $query = $this->db->query($sql, Array($id_actividad));
                if (!$query) {
                    return false;
                } else {
                    if ($query->num_rows() == 1) {
                        return $query->row();
                    } else {
                        return false;
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function insert_actividad($id_proyecto, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad, $id_producto, $contraparte_actividad) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $this->db->trans_start();
                $sql = "INSERT INTO ACTIVIDAD
                        (
                            id_proyecto,
                            nombre_actividad,
                            descripcion_actividad,
                            fecha_inicio_actividad,
                            fecha_fin_actividad,
                            presupuesto_actividad,
                            en_edicion_actividad,
                            contraparte_actividad,
                            en_reformulacion_actividad
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            true,
                            ?,
                            false
                        )
                        ";
                $query = $this->db->query($sql, Array($id_proyecto, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad, $contraparte_actividad));
                if($id_producto) {
                    $id_actividad = $this->db->insert_id();
                    $sql = "INSERT INTO PRODUCTO_RECIBE_ACTIVIDAD
                            (
                                PRODUCTO_RECIBE_ACTIVIDAD.id_actividad,
                                PRODUCTO_RECIBE_ACTIVIDAD.id_producto
                            )
                            VALUES
                            (
                                ?,
                                ?
                            )
                            ";
                    $query = $this->db->query($sql, Array($id_actividad, $id_producto));
                }
                $this->db->trans_complete();
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function update_actividad($id_actividad, $nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad, $id_producto, $contraparte_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $this->db->trans_start();
                $sql = "UPDATE ACTIVIDAD SET
                            ACTIVIDAD.nombre_actividad = ?,
                            ACTIVIDAD.descripcion_actividad = ?,
                            ACTIVIDAD.fecha_inicio_actividad = ?,
                            ACTIVIDAD.fecha_fin_actividad = ?,
                            ACTIVIDAD.presupuesto_actividad = ?,
                            ACTIVIDAD.contraparte_actividad = ?
                        WHERE
                            ACTIVIDAD.id_actividad = ?
                        ";
                $query = $this->db->query($sql, Array($nombre_actividad, $descripcion_actividad, $fecha_inicio_actividad, $fecha_fin_actividad, $presupuesto_actividad, $contraparte_actividad, $id_actividad));
                $sql = "SELECT
                            PRODUCTO_RECIBE_ACTIVIDAD.id_actividad,
                            PRODUCTO_RECIBE_ACTIVIDAD.id_producto
                        FROM
                            PRODUCTO_RECIBE_ACTIVIDAD
                        WHERE
                            PRODUCTO_RECIBE_ACTIVIDAD.id_actividad = ?
                        ";
                $query = $this->db->query($sql, Array($id_actividad));
                if($id_producto) {
                    if($query->num_rows() > 0) {
                        $producto_recibe_actividad = $query->row();
                        if($producto_recibe_actividad->id_producto != $id_producto) {
                            $sql = "UPDATE PRODUCTO_RECIBE_ACTIVIDAD SET
                                        PRODUCTO_RECIBE_ACTIVIDAD.id_producto = ?
                                    WHERE
                                        PRODUCTO_RECIBE_ACTIVIDAD.id_actividad = ?
                                    ";
                            $query = $this->db->query($sql, Array($id_producto, $id_actividad));
                            $sql = "DELETE FROM META_ACTIVIDAD_APORTA_META_PRODUCTO_CN
                                    WHERE
                                        META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn IN
                                        (
                                            SELECT
                                                HITO_CUANTITATIVO.id_hito_cn
                                            FROM
                                                HITO_CUANTITATIVO,
                                                ACTIVIDAD
                                            WHERE
                                                HITO_CUANTITATIVO.id_actividad = ?
                                        )
                                    ";
                            $query = $this->db->query($sql, Array($id_actividad));
                        }
                    } else {
                        $sql = "INSERT INTO PRODUCTO_RECIBE_ACTIVIDAD
                                (
                                    PRODUCTO_RECIBE_ACTIVIDAD.id_actividad,
                                    PRODUCTO_RECIBE_ACTIVIDAD.id_producto
                                )
                                VALUES
                                (
                                    ?,
                                    ?
                                )
                                ";
                        $query = $this->db->query($sql, Array($id_actividad, $id_producto));
                    }
                } else {
                    if($query->num_rows() > 0) {
                        $sql = "DELETE FROM PRODUCTO_RECIBE_ACTIVIDAD
                                WHERE
                                    PRODUCTO_RECIBE_ACTIVIDAD.id_actividad = ?
                                ";
                        $query = $this->db->query($sql, Array($id_actividad));
                        $sql = "DELETE FROM META_ACTIVIDAD_APORTA_META_PRODUCTO_CN
                                WHERE
                                    META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn IN
                                    (
                                        SELECT
                                            HITO_CUANTITATIVO.id_hito_cn
                                        FROM
                                            HITO_CUANTITATIVO,
                                            ACTIVIDAD
                                        WHERE
                                            HITO_CUANTITATIVO.id_actividad = ?
                                    )
                                ";
                        $query = $this->db->query($sql, Array($id_actividad));
                    }
                }
                $this->db->trans_complete();
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_hito_cuantitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            HITO_CUANTITATIVO.id_hito_cn,
                            HITO_CUANTITATIVO.id_actividad,
                            HITO_CUANTITATIVO.nombre_hito_cn,
                            HITO_CUANTITATIVO.descripcion_hito_cn,
                            HITO_CUANTITATIVO.meta_hito_cn,
                            HITO_CUANTITATIVO.unidad_hito_cn,
                            COALESCE(SUM(AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn), 0) as avance_hito_cn,
                            M.id_meta_producto_cuantitativa
                        FROM
                            HITO_CUANTITATIVO
                        LEFT JOIN AVANCE_HITO_CUANTITATIVO ON 
                            HITO_CUANTITATIVO.id_hito_cn = AVANCE_HITO_CUANTITATIVO.id_hito_cn AND
                            AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn = true
                        LEFT JOIN META_ACTIVIDAD_APORTA_META_PRODUCTO_CN M ON
                            HITO_CUANTITATIVO.id_hito_cn = M.id_hito_cn
                        WHERE
                            HITO_CUANTITATIVO.id_hito_cn = ?
                        GROUP BY
                            HITO_CUANTITATIVO.id_hito_cn
                        ";
                $query = $this->db->query($sql, Array($id_hito));
                if (!$query) {
                    return false;
                } else {
                    if ($query->num_rows() != 1) {
                        return false;
                    } else {
                        return $query->row();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function insert_hito_cuantitativo($id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $id_meta_producto, $aporta_producto) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $this->db->trans_start();
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
                            ?,
                            ?,
                            ?,
                            ?,
                            ?
                        )
                        ";
                $query = $this->db->query($sql, Array($id_actividad, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito));
                if($aporta_producto == "directo") {
                    $id_hito = $this->db->insert_id();
                    $sql = "INSERT INTO META_ACTIVIDAD_APORTA_META_PRODUCTO_CN
                         (
                            META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn,
                            META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_meta_producto_cuantitativa
                         )
                         VALUES
                         (
                            ?,
                            ?
                         )
                            ";
                    $query = $this->db->query($sql, Array($id_hito, $id_meta_producto));
                }
                $this->db->trans_complete();
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function update_hito_cuantitativo($id_hito, $nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $aporta_producto, $id_meta_producto) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $this->db->trans_start();
                $sql = "UPDATE HITO_CUANTITATIVO SET
                            HITO_CUANTITATIVO.nombre_hito_cn = ?,
                            HITO_CUANTITATIVO.descripcion_hito_cn = ?,
                            HITO_CUANTITATIVO.meta_hito_cn = ?,
                            HITO_CUANTITATIVO.unidad_hito_cn = ?
                        WHERE
                            HITO_CUANTITATIVO.id_hito_cn = ?
                        ";
                $query = $this->db->query($sql, Array($nombre_hito, $descripcion_hito, $meta_hito, $unidad_hito, $id_hito));
                if($aporta_producto == "directo") {
                    $sql = "SELECT
                                META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn,
                                META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_meta_producto_cuantitativa
                            FROM
                                META_ACTIVIDAD_APORTA_META_PRODUCTO_CN
                            WHERE
                                META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn = ?
                            ";
                    $query = $this->db->query($sql, Array($id_hito));
                    if($query->num_rows() > 0) {
                        $sql = "UPDATE META_ACTIVIDAD_APORTA_META_PRODUCTO_CN SET
                                    META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_meta_producto_cuantitativa = ?
                                WHERE
                                    META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn = ?
                                ";
                        $sql = $this->db->query($sql, Array($id_meta_producto, $id_hito));
                    } else {
                        $sql = "INSERT INTO META_ACTIVIDAD_APORTA_META_PRODUCTO_CN
                                (
                                    META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_meta_producto_cuantitativa,
                                    META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn
                                )
                                VALUES
                                (
                                    ?,
                                    ?
                                )
                                ";
                        $sql = $this->db->query($sql, Array($id_meta_producto, $id_hito));
                    }
                } else {
                    if($aporta_producto == "indirecto") {
                        $sql = "SELECT
                                    META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn,
                                    META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_meta_producto_cuantitativa
                                FROM
                                    META_ACTIVIDAD_APORTA_META_PRODUCTO_CN
                                WHERE
                                    META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn = ?
                            ";
                        $query = $this->db->query($sql, Array($id_hito));
                        if($query->num_rows() > 0) {
                            $sql = "DELETE FROM META_ACTIVIDAD_APORTA_META_PRODUCTO_CN
                                    WHERE
                                        META_ACTIVIDAD_APORTA_META_PRODUCTO_CN.id_hito_cn = ?
                                    ";
                            $query = $this->db->query($sql, Array($id_hito));
                        }
                    }
                }
                $this->db->trans_complete();
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function delete_hito_cuantitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "DELETE FROM HITO_CUANTITATIVO
                        WHERE
                            id_hito_cn = ?
                        ";
                $query = $this->db->query($sql, Array($id_hito));
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function insert_avance_hito_cuantitativo_sin_documentos($id_hito, $cantidad_avance_hito, $fecha_avance_hito, $descripcion_avance_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "INSERT INTO AVANCE_HITO_CUANTITATIVO
                        (
                            AVANCE_HITO_CUANTITATIVO.id_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.fecha_avance_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.descripcion_avance_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.en_revision_avance_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.fecha_registro_avance_hito_cn
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?,
                            ?,
                            false,
                            true,
                            now()
                        )
                        ";
                $query = $this->db->query($sql, Array($id_hito, $cantidad_avance_hito, $fecha_avance_hito, $descripcion_avance_hito));
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_avances_hito_cuantitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
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
                            AVANCE_HITO_CUANTITATIVO.id_hito_cn = ?
                        ";
                $query = $this->db->query($sql, Array($id_hito));
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
                                DOCUMENTO_AVANCE_HITO_CUANTITATIVO.id_avance_hito_cn = ?
                            ";
                    $query = $this->db->query($sql, Array($id_avance));
                    $datos['documentos'][$i] = $query->result();
                    $i = $i + 1;
                }
                $this->db->trans_complete();
                return $datos;
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    /* TODO mejorar creacion carpeta Ctrl+F */

    public function insert_avance_hito_cuantitativo_con_documentos($id_hito, $cantidad_avance_hito, $fecha_avance_hito, $descripcion_avance_hito, $titulo_documento_avance, $descripcion_documento_avance) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $config = Array();
                $config['upload_path'] = './files/' . $this->session->userdata('carpeta_institucion') . '/';
                $config['allowed_types'] = 'gif|jpg|jpeg|jpe|png|pdf|doc|docx|rar|zip|xls|xlsx';
                $config['max_size'] = '102400';
                $this->upload->initialize($config);
                $errores = false;
                $j = 0;
                $archivo_documento_avance = Array();
                foreach ($_FILES as $clave => $archivo) {
                    if (!empty($archivo['name'])) {
                        if (!$this->upload->do_upload($clave)) {
                            $errores = true;
                        } else {
                            $archivos[$archivo['name']] = $this->upload->data();
                            $archivo_documento_avance[$j] = $archivos[$archivo['name']]['file_name'];
                            $j = $j + 1;
                        }
                    }
                }
                if ($errores) {
                    foreach ($archivos as $archivo) {
                        @unlink($archivo['full_path']);
                    }
                    //error en los archivos
                    redirect(base_url() . 'socio/error');
                } else {
                    $this->db->trans_start();
                    $sql = "INSERT INTO AVANCE_HITO_CUANTITATIVO
                            (
                                AVANCE_HITO_CUANTITATIVO.id_hito_cn,
                                AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn,
                                AVANCE_HITO_CUANTITATIVO.fecha_avance_hito_cn,
                                AVANCE_HITO_CUANTITATIVO.descripcion_avance_hito_cn,
                                AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn,
                                AVANCE_HITO_CUANTITATIVO.en_revision_avance_hito_cn,
                                AVANCE_HITO_CUANTITATIVO.fecha_registro_avance_hito_cn
                            )
                            VALUES
                            (
                                ?,
                                ?,
                                ?,
                                ?,
                                false,
                                true,
                                now()
                            )
                            ";
                    $query = $this->db->query($sql, Array($id_hito, $cantidad_avance_hito, $fecha_avance_hito, $descripcion_avance_hito));
                    $id_avance_hito = $this->db->insert_id();
                    for ($i = 0; $i < sizeof($titulo_documento_avance); $i = $i + 1) {
                        $sql = "INSERT INTO DOCUMENTO_AVANCE_HITO_CUANTITATIVO
                                (
                                    DOCUMENTO_AVANCE_HITO_CUANTITATIVO.id_avance_hito_cn,
                                    DOCUMENTO_AVANCE_HITO_CUANTITATIVO.titulo_documento_avance_hito_cn,
                                    DOCUMENTO_AVANCE_HITO_CUANTITATIVO.descripcion_documento_avance_hito_cn,
                                    DOCUMENTO_AVANCE_HITO_CUANTITATIVO.archivo_documento_avance_hito_cn
                                )
                                VALUES
                                (
                                    ?,
                                    ?,
                                    ?,
                                    ?
                                )
                                ";
                        $query = $this->db->query($sql, Array($id_avance_hito, $titulo_documento_avance[$i], $descripcion_documento_avance[$i], $archivo_documento_avance[$i]));
                    }
                    $this->db->trans_complete();
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_hito_cualitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            HITO_CUALITATIVO.id_hito_cl,
                            HITO_CUALITATIVO.id_actividad,
                            HITO_CUALITATIVO.nombre_hito_cl,
                            HITO_CUALITATIVO.descripcion_hito_cl
                        FROM
                            HITO_CUALITATIVO
                        WHERE
                            HITO_CUALITATIVO.id_hito_cl = ?
                        ";
                $query = $this->db->query($sql, Array($id_hito));
                if (!$query) {
                    return false;
                } else {
                    if ($query->num_rows() != 1) {
                        return false;
                    } else {
                        return $query->row();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function insert_hito_cualitativo($id_actividad, $nombre_hito, $descripcion_hito) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "INSERT INTO HITO_CUALITATIVO
                        (
                            HITO_CUALITATIVO.id_actividad,
                            HITO_CUALITATIVO.nombre_hito_cl,
                            HITO_CUALITATIVO.descripcion_hito_cl
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?
                        )
                        ";
                $query = $this->db->query($sql, Array($id_actividad, $nombre_hito, $descripcion_hito));
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function update_hito_cualitativo($id_hito, $nombre_hito, $descripcion_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "UPDATE HITO_CUALITATIVO SET
                            HITO_CUALITATIVO.nombre_hito_cl = ?,
                            HITO_CUALITATIVO.descripcion_hito_cl = ?
                        WHERE
                            HITO_CUALITATIVO.id_hito_cl = ?
                        ";
                $query = $this->db->query($sql, Array($nombre_hito, $descripcion_hito, $id_hito));
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function delete_hito_cualitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "DELETE FROM HITO_CUALITATIVO
                        WHERE
                            id_hito_cl = ?
                        ";
                $query = $this->db->query($sql, Array($id_hito));
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_avances_hito_cualitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
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
                            AVANCE_HITO_CUALITATIVO.id_hito_cl = ?
                        ";
                $query = $this->db->query($sql, Array($id_hito));
                if (!$query) {
                    return false;
                } else {
                    if ($query->num_rows() == 0) {
                        return false;
                    } else {
                        return $query->result();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function insert_avance_hito_cualitativo($id_hito, $titulo_avance_hito, $fecha_avance_hito, $descripcion_avance_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $config = Array();
                $config['upload_path'] = './files/' . $this->session->userdata('carpeta_institucion') . '/';
                $config['allowed_types'] = 'gif|jpg|jpeg|jpe|png|pdf|doc|docx|rar|zip|xls|xlsx';
                $config['max_size'] = '102400';
                $this->upload->initialize($config);
                $errores = false;
                $j = 0;
                $archivo_documento_avance = Array();
                foreach ($_FILES as $clave => $archivo) {
                    if (!empty($archivo['name'])) {
                        if (!$this->upload->do_upload($clave)) {
                            $errores = true;
                        } else {
                            $archivos[$archivo['name']] = $this->upload->data();
                            $archivo_documento_avance[$j] = $archivos[$archivo['name']]['file_name'];
                            $j = $j + 1;
                        }
                    }
                }
                if ($errores) {
                    foreach ($archivos as $archivo) {
                        @unlink($archivo['full_path']);
                    }
                    //error en los archivos
                    redirect(base_url() . 'socio/error');
                } else {
                    $sql = "INSERT INTO AVANCE_HITO_CUALITATIVO
                            (
                                AVANCE_HITO_CUALITATIVO.id_hito_cl,
                                AVANCE_HITO_CUALITATIVO.fecha_avance_hito_cl,
                                AVANCE_HITO_CUALITATIVO.titulo_avance_hito_cl,
                                AVANCE_HITO_CUALITATIVO.descripcion_avance_hito_cl,
                                AVANCE_HITO_CUALITATIVO.documento_avance_hito_cl,
                                AVANCE_HITO_CUALITATIVO.aprobado_avance_hito_cl,
                                AVANCE_HITO_CUALITATIVO.en_revision_avance_hito_cl,
                                AVANCE_HITO_CUALITATIVO.fecha_registro_avance_hito_cl
                            )
                            VALUES
                            (
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                false,
                                true,
                                now()
                            )
                            ";
                    $query = $this->db->query($sql, Array($id_hito, $fecha_avance_hito, $titulo_avance_hito, $descripcion_avance_hito, $archivo_documento_avance[0]));
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function delete_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "DELETE FROM PROYECTO
                        WHERE
                            id_proyecto = $id_proyecto
                        ";
                $query = $this->db->query($sql);
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function delete_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "DELETE FROM ACTIVIDAD
                        WHERE
                            id_actividad = ?
                        ";
                $query = $this->db->query($sql, Array($id_actividad));
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function terminar_edicion_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $this->db->trans_start();
                $sql = "UPDATE PROYECTO SET
                            PROYECTO.en_edicion = false
                        WHERE
                            PROYECTO.id_proyecto = ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto));
                $sql = "UPDATE ACTIVIDAD SET
                            ACTIVIDAD.en_edicion_actividad = false
                        WHERE
                            ACTIVIDAD.id_proyecto = ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto));
                $this->db->trans_complete();
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function insert_gastos_actividad($id_actividad, $fecha_gasto, $importe_gasto, $concepto_gasto) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $config = Array();
                $config['upload_path'] = './files/' . $this->session->userdata('carpeta_institucion') . '/';
                $config['allowed_types'] = 'gif|jpg|jpeg|jpe|png|pdf|doc|docx|rar|zip|xls|xlsx';
                $config['max_size'] = '102400';
                $this->upload->initialize($config);
                $errores = false;
                $j = 0;
                $archivos_documento_gastos = Array();
                foreach ($_FILES as $clave => $archivo) {
                    if (!empty($archivo['name'])) {
                        if (!$this->upload->do_upload($clave)) {
                            $errores = true;
                        } else {
                            $archivos[$archivo['name']] = $this->upload->data();
                            $archivos_documento_gastos[$j] = $archivos[$archivo['name']]['file_name'];
                            $j = $j + 1;
                        }
                    }
                }
                if ($errores) {
                    foreach ($archivos as $archivo) {
                        @unlink($archivo['full_path']);
                    }
                    //error en los archivos
                    redirect(base_url() . 'socio/error');
                } else {
                    $this->db->trans_start();
                    for ($i = 0; $i < sizeof($fecha_gasto); $i = $i + 1) {
                        $sql = "INSERT INTO GASTO_ACTIVIDAD
                                (
                                    GASTO_ACTIVIDAD.id_actividad,
                                    GASTO_ACTIVIDAD.fecha_gasto_actividad,
                                    GASTO_ACTIVIDAD.concepto_gasto_actividad,
                                    GASTO_ACTIVIDAD.importe_gasto_actividad,
                                    GASTO_ACTIVIDAD.respaldo_gasto_actividad
                                )
                                VALUES
                                (
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?
                                )
                                ";
                        $query = $this->db->query($sql, Array($id_actividad, $fecha_gasto[$i], $concepto_gasto[$i], $importe_gasto[$i], $archivos_documento_gastos[$i]));
                    }
                    $this->db->trans_complete();
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function existe_nombre_proyecto_institucion($id_institucion, $nombre_proyecto) {
        try {
            $sql = "SELECT
                        PROYECTO.id_proyecto
                    FROM
                        PROYECTO
                    WHERE
                        PROYECTO.id_institucion = ? AND
                        PROYECTO.nombre_proyecto = ?
                    ";
            $query = $this->db->query($sql, Array($id_institucion, $nombre_proyecto));
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    public function existe_nombre_proyecto_institucion_con_id($id_institucion, $id_proyecto, $nombre_proyecto) {
        try {
            $sql = "SELECT
                        PROYECTO.id_proyecto
                    FROM
                        PROYECTO
                    WHERE
                        PROYECTO.id_institucion = ? AND
                        PROYECTO.id_proyecto != ? AND
                        PROYECTO.nombre_proyecto = ?
                    ";
            $query = $this->db->query($sql, Array($id_institucion, $id_proyecto, $nombre_proyecto));
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }
    
    public function get_productos() {
        try {
            $sql = "SELECT
                        PRODUCTO.id_producto,
                        PRODUCTO.id_efecto,
                        PRODUCTO.nombre_producto,
                        PRODUCTO.descripcion_producto
                    FROM
                        PRODUCTO
                    ";
            $query = $this->db->query($sql);
            if(!$query) {
                return Array();
            } else {
                return $query->result();
            }
        } catch (Exception $ex) {

        }
    }
    
    public function get_metas_cuantitativas_producto($id_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'socio/error');
        } else {
            $sql = "SELECT
                        META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.id_producto,
                        META_PRODUCTO_CUANTITATIVA.cantidad_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.unidad_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.nombre_meta_producto_cuantitativa,
                        META_PRODUCTO_CUANTITATIVA.descripcion_meta_producto_cuantitativa
                    FROM
                        META_PRODUCTO_CUANTITATIVA
                    INNER JOIN PRODUCTO ON META_PRODUCTO_CUANTITATIVA.id_producto = PRODUCTO.id_producto
                    WHERE
                        META_PRODUCTO_CUANTITATIVA.id_producto = ?
                    ";
            $query = $this->db->query($sql, Array($id_producto));
            if(!$query) {
                return Array();
            } else {
                return $query->result();
            }
        }
    }
    
    public function get_metas_cualitativas_producto($id_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'socio/error');
        } else {
            $sql = "SELECT
                        META_PRODUCTO_CUALITATIVA.id_meta_producto_cualitativa,
                        META_PRODUCTO_CUALITATIVA.id_producto,
                        META_PRODUCTO_CUALITATIVA.nombre_meta_producto_cualitativa,
                        META_PRODUCTO_CUALITATIVA.descripcion_meta_producto_cualitativa
                    FROM
                        META_PRODUCTO_CUALITATIVA
                    INNER JOIN PRODUCTO ON META_PRODUCTO_CUALITATIVA.id_producto = PRODUCTO.id_producto
                    WHERE
                        META_PRODUCTO_CUALITATIVA.id_producto = ?
                    ";
            $query = $this->db->query($sql, Array($id_producto));
            if(!$query) {
                return Array();
            } else {
                return $query->result();
            }
        }
    }
    
    public function get_gestion_actual() {
        try {
            $sql = "SELECT
                        ANIO.id_anio,
                        ANIO.valor_anio,
                        ANIO.activo_anio
                    FROM
                        ANIO
                    WHERE
                        ANIO.activo_anio = true
                    ";
            $query = $this->db->query($sql);
            if(!$query) {
                return false;
            } else {
                if($query->num_rows() == 0) {
                    return false;
                } else {
                    return $query->row();
                }
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }
    
    public function update_gasto_actividad($id_actividad, $gasto_actividad) {
        if(!is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "UPDATE ACTIVIDAD SET
                            ACTIVIDAD.gasto_actividad = ?
                        WHERE
                            ACTIVIDAD.id_actividad = ?
                        ";
                $this->db->query($sql, Array($gasto_actividad, $id_actividad));
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_presupuesto_disponible_institucion($id_institucion) {
        try {
            if (!is_numeric($id_institucion)) {
                redirect(base_url() . 'socio/error');
            } else {
                $this->db->trans_start();
                $sql = "SELECT
                            PROYECTO.id_proyecto
                        FROM
                            PROYECTO,
                            PROYECTO_GLOBAL
                        WHERE
                            PROYECTO.id_proyecto_global = PROYECTO_GLOBAL.id_proyecto_global AND
                            PROYECTO_GLOBAL.id_institucion = ?
                        ";
                $query = $this->db->query($sql, Array($id_institucion));
                if($query->num_rows() > 0) {
                    $sql = "SELECT 
                                PROYECTO_GLOBAL.presupuesto_proyecto_global - COALESCE(SUM(PROYECTO.presupuesto_proyecto), 0) AS presupuesto_disponible_institucion
                            FROM 
                                PROYECTO,
                                PROYECTO_GLOBAL
                            WHERE 
                                PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                                PROYECTO_GLOBAL.id_institucion = ? 
                            GROUP BY
                                PROYECTO_GLOBAL.id_institucion
                            ";
                    $query = $this->db->query($sql, Array($id_institucion));
                } else {
                    $sql = "SELECT
                                PROYECTO_GLOBAL.presupuesto_proyecto_global AS presupuesto_disponible_institucion
                            FROM 
                                PROYECTO_GLOBAL
                            WHERE
                                PROYECTO_GLOBAL.id_institucion = ? 
                            ";
                    $query = $this->db->query($sql, Array($id_institucion));
                }
                $this->db->trans_complete();
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
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    public function get_presupuesto_disponible_institucion_con_id($id_institucion, $id_proyecto) {
        try {
            if (!is_numeric($id_institucion) || !is_numeric($id_proyecto)) {
                redirect(base_url() . 'socio/error');
            } else {
                $this->db->trans_start();
                $sql = "SELECT
                            PROYECTO.id_proyecto
                        FROM
                            PROYECTO,
                            PROYECTO_GLOBAL
                        WHERE
                            PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                            PROYECTO_GLOBAL.id_institucion = ? AND
                            PROYECTO.id_proyecto != ?
                        ";
                $query = $this->db->query($sql, Array($id_institucion, $id_proyecto));
                if($query->num_rows() > 0) {
                    $sql = "SELECT 
                                PROYECTO_GLOBAL.presupuesto_proyecto_global - COALESCE(SUM(PROYECTO.presupuesto_proyecto), 0) AS presupuesto_disponible_institucion
                            FROM 
                                PROYECTO_GLOBAL, 
                                PROYECTO
                            WHERE 
                                PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                                PROYECTO_GLOBAL.id_institucion = ? AND
                                PROYECTO.id_proyecto != ?
                            GROUP BY
                                PROYECTO_GLOBAL.id_proyecto_global
                            ";
                    $query = $this->db->query($sql, Array($id_institucion, $id_proyecto));
                } else {
                    $sql = "SELECT 
                                PROYECTO_GLOBAL.presupuesto_proyecto_global AS presupuesto_disponible_institucion
                            FROM 
                                PROYECTO_GLOBAL
                            WHERE 
                                PROYECTO_GLOBAL.id_institucion = ?
                            GROUP BY
                                PROYECTO_GLOBAL.id_institucion
                            ";
                    $query = $this->db->query($sql, Array($id_institucion));
                }
                $this->db->trans_complete();
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
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    public function get_presupuesto_disponible_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            PROYECTO.presupuesto_proyecto - COALESCE(SUM(ACTIVIDAD.presupuesto_actividad), 0) AS presupuesto_disponible_proyecto
                        FROM
                            PROYECTO,
                            ACTIVIDAD
                        WHERE
                            PROYECTO.id_proyecto = ACTIVIDAD.id_proyecto AND
                            ACTIVIDAD.contraparte_actividad = false AND
                            PROYECTO.id_proyecto = ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto));
                if (!$query) {
                    return false;
                } else {
                    if ($query->num_rows() != 1) {
                        return false;
                    } else {
                        return $query->row();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_presupuesto_disponible_proyecto_con_id($id_proyecto, $id_actividad) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_actividad)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            PROYECTO.presupuesto_proyecto - COALESCE(SUM(ACTIVIDAD.presupuesto_actividad), 0) AS presupuesto_disponible_proyecto
                        FROM
                            PROYECTO,
                            ACTIVIDAD
                        WHERE
                            PROYECTO.id_proyecto = ACTIVIDAD.id_proyecto AND
                            PROYECTO.id_proyecto = ? AND
                            ACTIVIDAD.contraparte_actividad = false AND
                            ACTIVIDAD.id_actividad != ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto, $id_actividad));
                if (!$query) {
                    return false;
                } else {
                    if ($query->num_rows() != 1) {
                        return false;
                    } else {
                        return $query->row();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function get_suma_presupuestos_actividades_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'socio/error');
        } else {
            $sql = "SELECT
                        COALESCE(SUM(ACTIVIDAD.presupuesto_actividad), 0) AS suma_presupuesto_actividades
                    FROM
                        ACTIVIDAD
                    WHERE
                        ACTIVIDAD.contraparte_actividad = false AND
                        ACTIVIDAD.id_proyecto = ?
                    ";
            $query = $this->db->query($sql, Array($id_proyecto));
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
    
    public function get_anios() {
        try {
            $sql = "SELECT
                        ANIO.id_anio,
                        ANIO.valor_anio,
                        ANIO.activo_anio
                    FROM
                        ANIO
                    ";
            $query = $this->db->query($sql);
            if(!$query) {
                return false;
            } else {
                return $query->result();
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'socio/error');
        }
    }

    public function get_usuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            redirect(base_url() . 'socio');
        } else {
            try {
                $sql = "SELECT
                            USUARIO.id_usuario,
                            USUARIO.id_institucion,
                            USUARIO.id_rol,
                            USUARIO.nombre_usuario,
                            USUARIO.apellido_paterno_usuario,
                            USUARIO.apellido_materno_usuario,
                            USUARIO.login_usuario,
                            USUARIO.password_usuario,
                            USUARIO.telefono_usuario,
                            USUARIO.correo_usuario,
                            USUARIO.activo_usuario,
                            INSTITUCION.id_institucion,
                            INSTITUCION.nombre_institucion,
                            INSTITUCION.sigla_institucion,
                            ROL.id_rol,
                            ROL.nombre_rol
                        FROM
                            USUARIO,
                            INSTITUCION,
                            ROL
                        WHERE
                            USUARIO.id_institucion = INSTITUCION.id_institucion AND
                            USUARIO.id_rol = ROL.id_rol AND
                            USUARIO.id_usuario = ?
                        ";
                $query = $this->db->query($sql, Array($id_usuario));
                if (!$query) {
                    return false;
                } else {
                    if ($query->num_rows() != 1) {
                        return false;
                    } else {
                        return $query->row();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function verificar_password($id_usuario, $password) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            USUARIO.id_usuario
                        FROM
                            USUARIO
                        WHERE
                            USUARIO.id_usuario = ? AND
                            USUARIO.password_usuario = ?
                        ";
                $query = $this->db->query($sql, Array($id_usuario, $password));
                if($query->num_rows() == 1) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function update_password_usuario($id_usuario, $password_usuario) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "UPDATE USUARIO SET
                            USUARIO.password_usuario = ?
                        WHERE
                            USUARIO.id_usuario = ?
                        ";
                $query = $this->db->query($sql, Array($password_usuario, $id_usuario));
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function update_datos_contacto_usuario($id_usuario, $telefono_usuario, $correo_usuario) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "UPDATE USUARIO SET
                            USUARIO.telefono_usuario = ?,
                            USUARIO.correo_usuario = ?
                        WHERE
                            USUARIO.id_usuario = ?
                        ";
                $query = $this->db->query($sql, Array($telefono_usuario, $correo_usuario, $id_usuario));
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function get_reporte_proyecto_completo_activo($id_proyecto) {
        try {
            if (!is_numeric($id_proyecto)) {
                redirect(base_url() . 'socio/error');
            }
            $id_institucion = $this->session->userdata('id_institucion');
            $datos = Array();
            $this->db->trans_start();
            $sql = "SELECT 
                        PROYECTO.id_proyecto, 
                        PROYECTO.nombre_proyecto, 
                        PROYECTO.descripcion_proyecto,
                        PROYECTO.presupuesto_proyecto,
                        ANIO.id_anio,
                        ANIO.valor_anio
                    FROM 
                        PROYECTO, 
                        PROYECTO_GLOBAL,
                        INSTITUCION,
                        PROYECTO_TIENE_ANIO,
                        ANIO
                    WHERE 
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion AND
                        PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto AND
                        PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio AND
                        PROYECTO.en_edicion = false AND
                        PROYECTO_GLOBAL.id_institucion = ? AND
                        PROYECTO.id_proyecto = ?
                    ";
            $query_proyecto = $this->db->query($sql, Array($id_institucion, $id_proyecto));
            if ($query_proyecto->num_rows() == 1) {
                $proyecto = $query_proyecto->row();

                $actividades = $this->get_actividades_activas_proyecto($id_proyecto);

                if (sizeof($actividades) > 0) {
                    $i = 0;
                    foreach ($actividades as $actividad) {
                        $actividades[$i]->hitos_cuantitativos = $this->get_hitos_cuantitativos_actividad_con_avance($actividad->id_actividad);
                        $j = 0;
                        foreach($actividades[$i]->hitos_cuantitativos as $hito_cuantitativo) {
                            $id_hito = $actividades[$i]->hitos_cuantitativos[$j]->id_hito_cn;
                            $actividades[$i]->hitos_cuantitativos[$j]->avances = $this->get_avances_aceptados_hito_cuantitativo($id_hito);
                            $j += 1;
                        }
                        $k = 0;
                        $actividades[$i]->hitos_cualitativos = $this->get_hitos_cualitativos_actividad($actividad->id_actividad);
                        foreach($actividades[$i]->hitos_cualitativos as $hito_cualitativo) {
                            $id_hito = $actividades[$i]->hitos_cualitativos[$k]->id_hito_cl;
                            $actividades[$i]->hitos_cualitativos[$k]->avances = $this->get_avances_aceptados_hito_cualitativo($id_hito);
                            $k += 1;
                        }
                        $actividades[$i]->indicadores_cuantitativos = $this->get_indicadores_cuantitativos($actividad->id_actividad);
                        $actividades[$i]->indicadores_cualitativos = $this->get_indicadores_cualitativos($actividades[$i]->hitos_cualitativos);
                        $i += 1;
                    }
                }
                $proyecto->actividades = $actividades;
            } else {
                $datos['error'] = 'error_proyecto';
            }
            $this->db->trans_complete();
            return $proyecto;
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            redirect(base_url() . 'socio/error');
        }
    }
    
    public function get_avances_aceptados_hito_cuantitativo($id_hito) {
        if(!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            AVANCE_HITO_CUANTITATIVO.id_avance_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.id_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.fecha_avance_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.descripcion_avance_hito_cn,
                            AVANCE_HITO_CUANTITATIVO.fecha_registro_avance_hito_cn
                        FROM
                            AVANCE_HITO_CUANTITATIVO
                        WHERE
                            AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn = true AND
                            AVANCE_HITO_CUANTITATIVO.id_hito_cn = ?
                        ";
                $query = $this->db->query($sql, Array($id_hito));
                if(!$query) {
                    return false;
                } else {
                    if($query->num_rows() == 0) {
                        return false;
                    } else {
                        return $query->result();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }
    
    public function get_avances_aceptados_hito_cualitativo($id_hito) {
        if(!is_numeric($id_hito)) {
            redirect(base_url() . 'socio/error');
        } else {
            try {
                $sql = "SELECT
                            AVANCE_HITO_CUALITATIVO.id_avance_hito_cl,
                            AVANCE_HITO_CUALITATIVO.id_hito_cl,
                            AVANCE_HITO_CUALITATIVO.fecha_avance_hito_cl,
                            AVANCE_HITO_CUALITATIVO.titulo_avance_hito_cl,
                            AVANCE_HITO_CUALITATIVO.descripcion_avance_hito_cl,
                            AVANCE_HITO_CUALITATIVO.fecha_registro_avance_hito_cl
                        FROM
                            AVANCE_HITO_CUALITATIVO
                        WHERE
                            AVANCE_HITO_CUALITATIVO.aprobado_avance_hito_cl = true AND
                            AVANCE_HITO_CUALITATIVO.id_hito_cl = ?
                        ";
                $query = $this->db->query($sql, Array($id_hito));
                if(!$query) {
                    return false;
                } else {
                    if($query->num_rows() == 0) {
                        return false;
                    } else {
                        return $query->result();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'socio/error');
            }
        }
    }

    public function sanitizar_cadena($cadena) {
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
