<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modelo_financiador
 *
 * @author Jose
 */
class Modelo_financiador extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_usuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            redirect(base_url() . 'financiador');
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
                redirect(base_url() . 'financiador/error');
            }
        }
    }
    
    public function verificar_password($id_usuario, $password) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url() . 'financiador/error');
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
                redirect(base_url() . 'financiador/error');
            }
        }
    }
    
    public function update_password_usuario($id_usuario, $password_usuario) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url() . 'financiador/error');
        } else {
            try {
                $sql = "UPDATE USUARIO SET
                            USUARIO.password_usuario = ?
                        WHERE
                            USUARIO.id_usuario = ?
                        ";
                $query = $this->db->query($sql, Array($password_usuario, $id_usuario));
            } catch (Exception $ex) {
                redirect(base_url() . 'financiador/error');
            }
        }
    }
    
    public function update_datos_contacto_usuario($id_usuario, $telefono_usuario, $correo_usuario) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url() . 'financiador/error');
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
                redirect(base_url() . 'financiador/error');
            }
        }
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
            redirect(base_url() . 'financiador/error');
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
                redirect(base_url() . 'financiador/error');
            }
        }
    }
    
    public function get_productos_efecto($id_efecto) {
        if(!is_numeric($id_efecto)) {
            redirect(base_url() . 'financiador/error');
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
                redirect(base_url() . 'financiador/error');
            }
        }
    }
    
    public function get_metas_producto_cuantitativa($id_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'financiador/error');
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
                redirect(base_url() . 'financiador/error');
            }
        }
    }
    
    public function get_metas_producto_cualitativa($id_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'financiador/error');
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
                redirect(base_url() . 'financiador/error');
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
            redirect(base_url() . 'financiador/error');
        }
    }
    
    public function get_proyectos() {
        try {
            $sql = "SELECT
                        PROYECTO_GLOBAL.id_proyecto_global,
                        PROYECTO_GLOBAL.nombre_proyecto_global,
                        PROYECTO_GLOBAL.descripcion_proyecto_global,
                        PROYECTO_GLOBAL.presupuesto_proyecto_global,
                        INSTITUCION.id_institucion,
                        INSTITUCION.nombre_institucion,
                        INSTITUCION.sigla_institucion
                    FROM
                        PROYECTO_GLOBAL
                    INNER JOIN INSTITUCION ON
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion
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
        } catch (Exception $ex) {
            redirect(base_url() . 'financiador/error');
        }
    }
    
    public function get_proyecto_global_completo($id_proyecto_global) {
        if(!is_numeric($id_proyecto_global)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $proyecto_global = false;
                $this->db->trans_start();
                $sql = "SELECT
                            PROYECTO_GLOBAL.id_proyecto_global,
                            PROYECTO_GLOBAL.nombre_proyecto_global,
                            PROYECTO_GLOBAL.descripcion_proyecto_global,
                            PROYECTO_GLOBAL.presupuesto_proyecto_global,
                            INSTITUCION.id_institucion,
                            INSTITUCION.nombre_institucion,
                            INSTITUCION.sigla_institucion
                        FROM
                            PROYECTO_GLOBAL
                        INNER JOIN INSTITUCION ON INSTITUCION.id_institucion = PROYECTO_GLOBAL.id_institucion
                        WHERE
                            PROYECTO_GLOBAL.id_proyecto_global = ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto_global));
                if(!$query) {
                    $proyecto_global = false;
                } else {
                    if($query->num_rows() == 0) {
                        $proyecto_global = false;
                    } else {
                        $proyecto_global = $query->row();
                        $sql = "SELECT
                                    PROYECTO.id_proyecto,
                                    PROYECTO.id_proyecto_global,
                                    PROYECTO.nombre_proyecto,
                                    PROYECTO.descripcion_proyecto,
                                    PROYECTO.presupuesto_proyecto,
                                    PROYECTO.en_edicion,
                                    PROYECTO.concluido,
                                    ANIO.id_anio,
                                    ANIO.valor_anio
                                FROM
                                    PROYECTO
                                INNER JOIN PROYECTO_TIENE_ANIO ON PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto
                                INNER JOIN ANIO ON PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio
                                WHERE
                                    PROYECTO.en_edicion = false AND
                                    PROYECTO.id_proyecto_global = ?
                                ";
                        $query = $this->db->query($sql, Array($id_proyecto_global));
                        if(!$query) {
                            $proyecto_global->proyectos = false;
                        } else {
                            if($query->num_rows() == 0) {
                                $proyecto_global->proyectos = false;
                            } else {
                                $proyecto_global->proyectos = $query->result();
                            }
                        }
                    }
                }
                $this->db->trans_complete();
                return $proyecto_global;
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function get_reporte_proyecto_completo_activo($id_proyecto) {
        try {
            if (!is_numeric($id_proyecto)) {
                redirect(base_url() . 'financiador/error');
            }
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
                        PROYECTO.id_proyecto = ?
                    ";
            $query_proyecto = $this->db->query($sql, Array($id_proyecto));
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
            redirect(base_url() . 'financiador/error');
        }
    }

    private function get_actividades_activas_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'financiador/error');
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
            redirect(base_url() . 'financiador/error');
        }
    }

    private function get_hitos_cuantitativos_actividad_con_avance($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'financiador/error');
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
            redirect(base_url() . 'financiador/error');
        }
    }

    private function get_hitos_cualitativos_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'financiador/error');
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
                        HITO_CUALITATIVO.id_actividad = $id_actividad
                    ORDER BY
                        HITO_CUALITATIVO.nombre_hito_cl ASC
                    ";
            $query_indicadores = $this->db->query($sql);
            return $query_indicadores->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'financiador/error');
        }
    }

    private function get_indicadores_cuantitativos($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'financiador/error');
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
                redirect(base_url() . 'financiador/error');
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
            redirect(base_url() . 'financiador/error');
        }
        return $datos;
    }
    
    public function get_avances_aceptados_hito_cuantitativo($id_hito) {
        if(!is_numeric($id_hito)) {
            redirect(base_url() . 'financiador/error');
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
                redirect(base_url() . 'financiador/error');
            }
        }
    }
    
    public function get_avances_aceptados_hito_cualitativo($id_hito) {
        if(!is_numeric($id_hito)) {
            redirect(base_url() . 'financiador/error');
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
                redirect(base_url() . 'financiador/error');
            }
        }
    }
    
    public function get_proyectos_activos_gestion_actual() {
        try {
            $sql = "SELECT
                        PROYECTO.id_proyecto,
                        PROYECTO.nombre_proyecto,
                        PROYECTO.descripcion_proyecto,
                        PROYECTO.presupuesto_proyecto,
                        INSTITUCION.id_institucion,
                        INSTITUCION.nombre_institucion,
                        INSTITUCION.sigla_institucion,
                        ANIO.valor_anio
                    FROM
                        PROYECTO
                    INNER JOIN PROYECTO_GLOBAL ON
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global
                    INNER JOIN INSTITUCION ON
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion
                    INNER JOIN PROYECTO_TIENE_ANIO ON
                        PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto
                    INNER JOIN ANIO ON
                        PROYECTO_TIENE_ANIO.id_anio = ANIO.id_anio
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
                    return $query->result();
                }
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'financiador/error');
        }
    }
}
