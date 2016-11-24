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
            redirect(base_url() . 'coordinador/error');
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function get_productos_efecto($id_efecto) {
        if(!is_numeric($id_efecto)) {
            redirect(base_url() . 'coordinador/error');
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function get_metas_producto_cuantitativa($id_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'coordinador/error');
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function get_metas_producto_cualitativa($id_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'coordinador/error');
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
                redirect(base_url() . 'coordinador/error');
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
            redirect(base_url() . 'coordinador/error');
        }
    }
    
    public function get_prodoc($id_prodoc) {
        if(!is_numeric($id_prodoc)) {
            redirect(base_url() . 'coordinador/error');
        } else {
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function insert_prodoc($nombre_prodoc, $descripcion_prodoc, $objetivo_global_prodoc, $objetivo_proyecto_prodoc) {
        try {
            $this->db->trans_start();
            $sql = "INSERT INTO PRODOC
                    (
                        PRODOC.nombre_prodoc,
                        PRODOC.descripcion_prodoc,
                        PRODOC.objetivo_global_prodoc,
                        PRODOC.objetivo_proyecto_prodoc
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        ?,
                        ?
                    )
                    ";
            $query = $this->db->query($sql, Array($nombre_prodoc, $descripcion_prodoc, $objetivo_global_prodoc, $objetivo_proyecto_prodoc));
            $id_prodoc = $this->db->insert_id();
            $this->db->trans_complete();
            return $id_prodoc;
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }
    
    public function update_prodoc($id_prodoc, $nombre_prodoc, $descripcion_prodoc, $objetivo_global_prodoc, $objetivo_proyecto_prodoc) {
        if(!is_numeric($id_prodoc)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "UPDATE PRODOC SET
                            PRODOC.nombre_prodoc = ?,
                            PRODOC.descripcion_prodoc = ?,
                            PRODOC.objetivo_global_prodoc = ?,
                            PRODOC.objetivo_proyecto_prodoc = ?
                        WHERE
                            PRODOC.id_prodoc = ?
                        ";
                $query = $this->db->query($sql, Array($nombre_prodoc, $descripcion_prodoc, $objetivo_global_prodoc, $objetivo_proyecto_prodoc, $id_prodoc));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function get_efecto($id_efecto) {
        if(!is_numeric($id_efecto)) {
            redirect(base_url() . 'coordinador/error');
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
                            EFECTO.id_efecto = ?
                        ";
                $query = $this->db->query($sql, Array($id_efecto));
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function insert_efecto($id_prodoc, $nombre_efecto, $descripcion_efecto) {
        if(!is_numeric($id_prodoc)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "INSERT INTO EFECTO
                        (
                            EFECTO.id_prodoc,
                            EFECTO.nombre_efecto,
                            EFECTO.descripcion_efecto
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?
                        )
                        ";
                $query = $this->db->query($sql, Array($id_prodoc, $nombre_efecto, $descripcion_efecto));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function update_efecto($id_efecto, $nombre_efecto, $descripcion_efecto) {
        if(!is_numeric($id_efecto)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "UPDATE EFECTO SET
                            EFECTO.nombre_efecto = ?,
                            EFECTO.descripcion_efecto = ?
                        WHERE
                            EFECTO.id_efecto = ?
                        ";
                $query = $this->db->query($sql, Array($nombre_efecto, $descripcion_efecto, $id_efecto));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function get_producto($id_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'coordinador/error');
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
                            PRODUCTO.id_producto = ?
                        ";
                $query = $this->db->query($sql, Array($id_producto));
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function insert_producto($id_efecto, $nombre_producto, $descripcion_producto) {
        if(!is_numeric($id_efecto)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "INSERT INTO PRODUCTO
                        (
                            PRODUCTO.id_efecto,
                            PRODUCTO.nombre_producto,
                            PRODUCTO.descripcion_producto
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?
                        )
                        ";
                $query = $this->db->query($sql, Array($id_efecto, $nombre_producto, $descripcion_producto));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function update_producto($id_producto, $nombre_producto, $descripcion_producto) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "UPDATE PRODUCTO SET
                            PRODUCTO.nombre_producto = ?,
                            PRODUCTO.descripcion_producto = ?
                        WHERE
                            PRODUCTO.id_producto = ?
                        ";
                $query = $this->db->query($sql, Array($nombre_producto, $descripcion_producto, $id_producto));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function get_meta_producto_cuantitativa($id_meta_producto_cuantitativa) {
        if(!is_numeric($id_meta_producto_cuantitativa)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "SELECT
                            META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.id_producto,
                            META_PRODUCTO_CUANTITATIVA.cantidad_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.unidad_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.nombre_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.descripcion_meta_producto_cuantitativa
                        FROM
                            META_PRODUCTO_CUANTITATIVA
                        WHERE
                            META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa = ?
                        ";
                $query = $this->db->query($sql, Array($id_meta_producto_cuantitativa));
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function insert_meta_producto_cuantitativa($id_producto, $nombre_meta_producto_cuantitativa, $descripcion_meta_producto_cuantitativa, $cantidad_meta_producto_cuantitativa, $unidad_meta_producto_cuantitativa) {
        if(!is_numeric($id_producto)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "INSERT INTO META_PRODUCTO_CUANTITATIVA
                        (
                            META_PRODUCTO_CUANTITATIVA.id_producto,
                            META_PRODUCTO_CUANTITATIVA.nombre_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.descripcion_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.cantidad_meta_producto_cuantitativa,
                            META_PRODUCTO_CUANTITATIVA.unidad_meta_producto_cuantitativa
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
                $query = $this->db->query($sql, Array($id_producto, $nombre_meta_producto_cuantitativa, $descripcion_meta_producto_cuantitativa, $cantidad_meta_producto_cuantitativa, $unidad_meta_producto_cuantitativa));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function update_meta_producto_cuantitativa($id_meta_producto_cuantitativa, $nombre_meta_producto_cuantitativa, $descripcion_meta_producto_cuantitativa, $cantidad_meta_producto_cuantitativa, $unidad_meta_producto_cuantitativa) {
        if(!is_numeric($id_meta_producto_cuantitativa)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "UPDATE META_PRODUCTO_CUANTITATIVA SET
                            META_PRODUCTO_CUANTITATIVA.nombre_meta_producto_cuantitativa = ?,
                            META_PRODUCTO_CUANTITATIVA.descripcion_meta_producto_cuantitativa = ?,
                            META_PRODUCTO_CUANTITATIVA.cantidad_meta_producto_cuantitativa = ?,
                            META_PRODUCTO_CUANTITATIVA.unidad_meta_producto_cuantitativa = ?
                        WHERE
                            META_PRODUCTO_CUANTITATIVA.id_meta_producto_cuantitativa = ?
                        ";
                $query = $this->db->query($sql, Array($nombre_meta_producto_cuantitativa, $descripcion_meta_producto_cuantitativa, $cantidad_meta_producto_cuantitativa, $unidad_meta_producto_cuantitativa, $id_meta_producto_cuantitativa));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_proyectos() {
        try {
            $sql = "SELECT
                        PROYECTO_GLOBAL.id_proyecto_global,
                        PROYECTO_GLOBAL.id_institucion,
                        PROYECTO_GLOBAL.nombre_proyecto_global,
                        PROYECTO_GLOBAL.descripcion_proyecto_global,
                        PROYECTO_GLOBAL.presupuesto_proyecto_global,
                        INSTITUCION.nombre_institucion,
                        INSTITUCION.sigla_institucion,
                        PROYECTO_GLOBAL.presupuesto_proyecto_global - COALESCE(SUM(PROYECTO.presupuesto_proyecto), 0) as presupuesto_disponible
                    FROM
                        PROYECTO_GLOBAL
                    LEFT JOIN INSTITUCION ON PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion
                    LEFT JOIN PROYECTO ON PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global
                    GROUP BY
                        PROYECTO_GLOBAL.id_proyecto_global
                    ";
            $query = $this->db->query($sql);
            if (!$query) {
                return Array();
            } else {
                return $query->result();
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function get_proyecto_global($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "SELECT
                            PROYECTO_GLOBAL.id_proyecto_global,
                            PROYECTO_GLOBAL.id_institucion,
                            PROYECTO_GLOBAL.nombre_proyecto_global,
                            PROYECTO_GLOBAL.descripcion_proyecto_global,
                            PROYECTO_GLOBAL.presupuesto_proyecto_global,
                            COALESCE(SUM(PROYECTO.presupuesto_proyecto), 0) as presupuesto_asignado
                        FROM
                            PROYECTO_GLOBAL
                        LEFT JOIN PROYECTO ON PROYECTO.id_proyecto_global = PROYECTO_GLOBAL.id_proyecto_global
                        WHERE
                            PROYECTO_GLOBAL.id_proyecto_global = ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto));
                if (!$query) {
                    return Array();
                } else {
                    if ($query->num_rows() != 1) {
                        return Array();
                    } else {
                        return $query->row();
                    }
                }
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_proyecto_global_institucion($id_institucion, $id_institucion_antiguo) {
        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'coordinador/error');
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
                            PROYECTO_GLOBAL.id_institucion = ? AND
                            PROYECTO_GLOBAL.id_institucion != ?
                        ";
                $query = $this->db->query($sql, Array($id_institucion, $id_institucion_antiguo));
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function insert_proyecto($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_institucion) {
        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "INSERT INTO PROYECTO_GLOBAL
                        (
                            PROYECTO_GLOBAL.nombre_proyecto_global,
                            PROYECTO_GLOBAL.descripcion_proyecto_global,
                            PROYECTO_GLOBAL.presupuesto_proyecto_global,
                            PROYECTO_GLOBAL.id_institucion
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?,
                            ?
                        )
                        ";
                $query = $this->db->query($sql, Array($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_institucion));
            } catch (Exception $ex) {
                
            }
        }
    }

    public function update_proyecto($id_proyecto, $nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_institucion) {
        if (!is_numeric($id_proyecto) || !is_numeric($id_institucion)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "UPDATE PROYECTO_GLOBAL SET
                            PROYECTO_GLOBAL.nombre_proyecto_global = ?,
                            PROYECTO_GLOBAL.descripcion_proyecto_global = ?,
                            PROYECTO_GLOBAL.presupuesto_proyecto_global = ?,
                            PROYECTO_GLOBAL.id_institucion = ?
                        WHERE
                            PROYECTO_GLOBAL.id_proyecto_global = ?
                        ";
                $query = $this->db->query($sql, Array($nombre_proyecto, $descripcion_proyecto, $presupuesto_proyecto, $id_institucion, $id_proyecto));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function delete_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $this->db->trans_start();
                $sql = "DELETE FROM PROYECTO_GLOBAL
                        WHERE
                            PROYECTO_GLOBAL.id_proyecto_global = ?
                        ";
                $query = $this->db->query($sql, Array($id_proyecto));
                $this->db->trans_complete();
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
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

    public function get_instituciones() {
        try {
            $sql = "SELECT
                        INSTITUCION.id_institucion,
                        INSTITUCION.nombre_institucion,
                        INSTITUCION.sigla_institucion,
                        INSTITUCION.carpeta_institucion,
                        INSTITUCION.activa_institucion
                    FROM
                        INSTITUCION
                    ORDER BY
                        INSTITUCION.nombre_institucion ASC
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
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
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
                    ORDER BY
                        ANIO.valor_anio ASC
                    ";
            $query = $this->db->query($sql);
            if (!$query) {
                return Array();
            } else {
                return $query->result();
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function insert_anio($valor_anio) {
        if (!is_numeric($valor_anio)) {
            redirect(base_url() . 'coordinador/error');
        }
        try {
            $this->db->trans_start();
            $sql = "SELECT
                        ANIO.id_anio
                    FROM
                        ANIO
                    WHERE
                        ANIO.valor_anio = ?
                    ";
            $query = $this->db->query($sql, Array($valor_anio));
            if ($query->num_rows() > 0) {
                $this->session->set_flashdata('anio_registrado', 'El año ya se encuentra registrado.');
                redirect(base_url() . 'coordinador/habilitar_registro_poa_gestion', 'refresh');
            } else {
                $sql = "INSERT INTO ANIO
                        (
                            ANIO.valor_anio,
                            ANIO.activo_anio
                        )
                        VALUES
                        (
                            ?,
                            false
                        )
                        ";
                $query = $this->db->query($sql, Array($valor_anio));
                $this->db->trans_complete();
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function activar_anio($id_anio) {
        if (!is_numeric($id_anio)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $this->db->trans_start();
                $sql = "UPDATE ANIO SET
                            ANIO.activo_anio = false
                        ";
                $this->db->query($sql);
                $sql = "UPDATE ANIO SET
                            ANIO.activo_anio = true
                        WHERE
                            ANIO.id_anio = ?
                        ";
                $this->db->query($sql, Array($id_anio));
                $this->db->trans_complete();
            } catch (Exception $ex) {
                
            }
        }
    }

    public function get_anio_activo() {
        try {
            $sql = "SELECT
                        ANIO.valor_anio
                    FROM
                        ANIO
                    WHERE
                        ANIO.activo_anio = true
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
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
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
            redirect(base_url() . 'coordinador/error');
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
                        PROYECTO,
                        PROYECTO_GLOBAL,
                        INSTITUCION,
                        PROYECTO_TIENE_ANIO,
                        ANIO
                    WHERE
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion AND
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global AND
                        PROYECTO.id_proyecto = PROYECTO_TIENE_ANIO.id_proyecto AND
                        ANIO.id_anio = PROYECTO_TIENE_ANIO.id_anio AND
                        ANIO.activo_anio = true AND
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
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function get_proyectos_activos() {
        try {
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
                        PROYECTO_GLOBAL,
                        INSTITUCION
                    WHERE
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion AND
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto AND
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
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function get_proyecto_completo($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            $this->session->set_flashdata('acceso_no_autorizado', 'Operacion no permitida.');
            //TODO registrar intento de fallo
            redirect(base_url() . 'coordinador/error');
        }
        try {
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
                        INSTITUCION.carpeta_institucion,
                        ANIO.valor_anio
                    FROM 
                        PROYECTO
                    INNER JOIN PROYECTO_GLOBAL ON 
                        PROYECTO_GLOBAL.id_proyecto_global = PROYECTO.id_proyecto_global
                    INNER JOIN PROYECTO_TIENE_ANIO ON 
                        PROYECTO_TIENE_ANIO.id_proyecto = PROYECTO.id_proyecto
                    INNER JOIN ANIO ON 
                        ANIO.id_anio = PROYECTO_TIENE_ANIO.id_anio
                    INNER JOIN INSTITUCION ON 
                        PROYECTO_GLOBAL.id_institucion = INSTITUCION.id_institucion
                    WHERE
                        PROYECTO.id_proyecto = ?
                    ";
            $query_proyecto = $this->db->query($sql, Array($id_proyecto));
            if ($query_proyecto->num_rows() == 1) {
                $datos_proyecto = $query_proyecto->row();
                $datos['datos_proyecto'] = $datos_proyecto;

                $datos_actividades = $this->get_actividades_proyecto($id_proyecto);
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
                $this->session->set_flashdata('no_existe_proyecto', 'El proyecto al que intenta acceder no existe.');
                redirect(base_url() . 'coordinador/error');
            }

            return $datos;
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function get_actividades_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            $this->session->set_flashdata('acceso_no_autorizado', 'Operacion no permitida.');
            //TODO registrar intento de fallo
            redirect(base_url() . 'coordinador/error');
        }
        try {
            $sql = "SELECT
                        ACTIVIDAD.id_actividad,
                        ACTIVIDAD.nombre_actividad,
                        ACTIVIDAD.descripcion_actividad,
                        ACTIVIDAD.fecha_inicio_actividad,
                        ACTIVIDAD.fecha_fin_actividad,
                        ACTIVIDAD.presupuesto_actividad,
                        ACTIVIDAD.contraparte_actividad,
                        ACTIVIDAD.en_reformulacion_actividad,
                        ACTIVIDAD.gasto_actividad,
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
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function get_hitos_cuantitativos_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            $this->session->set_flashdata('acceso_no_autorizado', 'Operacion no permitida.');
            //TODO registrar intento de fallo
            redirect(base_url() . 'coordinador/error');
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
                        HITO_CUANTITATIVO.id_actividad = $id_actividad
                    ORDER BY
                        HITO_CUANTITATIVO.nombre_hito_cn ASC
                    ";
            $query_indicadores = $this->db->query($sql);
            return $query_indicadores->result();
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }
    
    private function get_hitos_cuantitativos_actividad_con_avance($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'coordinador/error');
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
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function get_hitos_cualitativos_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            $this->session->set_flashdata('acceso_no_autorizado', 'Operacion no permitida.');
            //TODO registrar intento de fallo
            redirect(base_url() . 'coordinador/error');
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
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function get_indicadores_cuantitativos($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'coordinador/error');
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
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_indicadores_cualitativos($datos_hitos_cualitativos) {
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
            redirect(base_url() . 'coordinador/error');
        }
        return $datos;
    }

    public function get_gastos_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'coordinador');
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
                            GASTO_ACTIVIDAD.id_actividad = $id_actividad
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
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_hito_cuantitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
        } else {
            try {
                $sql = "SELECT
                            HITO_CUANTITATIVO.id_hito_cn,
                            HITO_CUANTITATIVO.id_actividad,
                            HITO_CUANTITATIVO.nombre_hito_cn,
                            HITO_CUANTITATIVO.descripcion_hito_cn,
                            HITO_CUANTITATIVO.meta_hito_cn,
                            HITO_CUANTITATIVO.unidad_hito_cn,
                            COALESCE(SUM(AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn), 0) as avance_hito_cn
                        FROM
                            HITO_CUANTITATIVO
                        LEFT JOIN AVANCE_HITO_CUANTITATIVO ON 
                            HITO_CUANTITATIVO.id_hito_cn = AVANCE_HITO_CUANTITATIVO.id_hito_cn AND
                            AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn = true
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_avances_hito_cuantitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
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
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_hito_cualitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
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
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_avances_hito_cualitativo($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url() . 'coordinador');
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
                            AVANCE_HITO_CUALITATIVO.id_hito_cl = $id_hito
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
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_actividad($id_actividad) {
        if (!is_numeric($id_actividad)) {
            redirect(base_url() . 'coordinador');
        } else {
            try {
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
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function insert_indicador_cuantitativo($id_hito, $nombre_indicador, $tipo_indicador, $aceptable_indicador, $limitado_indicador, $no_aceptable_indicador) {
        if (!is_numeric($id_hito) || !is_numeric($tipo_indicador)) {
            redirect(base_url() . 'coordinador');
        } else {
            try {
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
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_tipos_indicador_cuantitativo() {
        try {
            $sql = "SELECT
                        TIPO_INDICADOR_CUANTITATIVO.id_tipo_indicador_cn,
                        TIPO_INDICADOR_CUANTITATIVO.nombre_tipo_indicador_cn,
                        TIPO_INDICADOR_CUANTITATIVO.descripcion_tipo_indicador_cn
                    FROM
                        TIPO_INDICADOR_CUANTITATIVO
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
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }

    public function modificar_estado_avance_hito_cuantitativo($id_avance_hito, $estado) {
        if (!is_numeric($id_avance_hito) || !is_bool($estado)) {
            redirect(base_url() . 'coordinador');
        } else {
            try {
                $estado = ($estado) ? 'true' : 'false';
                $sql = "UPDATE AVANCE_HITO_CUANTITATIVO SET
                            AVANCE_HITO_CUANTITATIVO.en_revision_avance_hito_cn = false,
                            AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn = $estado
                        WHERE
                            AVANCE_HITO_CUANTITATIVO.id_avance_hito_cn = $id_avance_hito
                        ";
                $query = $this->db->query($sql);
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function modificar_estado_avance_hito_cualitativo($id_avance_hito, $estado) {
        if (!is_numeric($id_avance_hito) || !is_bool($estado)) {
            redirect(base_url() . 'coordinador');
        } else {
            try {
                $estado = ($estado) ? 'true' : 'false';
                $sql = "UPDATE AVANCE_HITO_CUALITATIVO SET
                            AVANCE_HITO_CUALITATIVO.en_revision_avance_hito_cl = false,
                            AVANCE_HITO_CUALITATIVO.aprobado_avance_hito_cl = $estado
                        WHERE
                            AVANCE_HITO_CUALITATIVO.id_avance_hito_cl = $id_avance_hito
                        ";
                $query = $this->db->query($sql);
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function insert_actividad_en_reformulacion($id_proyecto) {
        if(!is_numeric($id_proyecto)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "INSERT INTO ACTIVIDAD
                        (
                            ACTIVIDAD.id_proyecto,
                            ACTIVIDAD.nombre_actividad,
                            ACTIVIDAD.descripcion_actividad,
                            ACTIVIDAD.fecha_inicio_actividad,
                            ACTIVIDAD.fecha_fin_actividad,
                            ACTIVIDAD.presupuesto_actividad,
                            ACTIVIDAD.en_edicion_actividad,
                            ACTIVIDAD.contraparte_actividad,
                            ACTIVIDAD.en_reformulacion_actividad
                        )
                        VALUES
                        (
                            ?,
                            'Nueva actividad',
                            'Descripción de la nueva actividad',
                            now(),
                            now(),
                            0,
                            false,
                            false,
                            true
                        )
                        ";
                $query = $this->db->query($sql, Array($id_proyecto));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function modificar_estado_reformulacion_actividad($id_actividad, $estado) {
        if(!is_numeric($id_actividad)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "UPDATE ACTIVIDAD SET
                            ACTIVIDAD.en_reformulacion_actividad = ?
                        WHERE
                            ACTIVIDAD.id_actividad = ?
                        ";
                $query = $this->db->query($sql, Array($estado, $id_actividad));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_institucion($id_institucion) {
        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'coordinador');
        } else {
            try {
                $sql = "SELECT
                            INSTITUCION.id_institucion,
                            INSTITUCION.nombre_institucion,
                            INSTITUCION.sigla_institucion,
                            INSTITUCION.carpeta_institucion,
                            INSTITUCION.activa_institucion
                        FROM
                            INSTITUCION
                        WHERE
                            INSTITUCION.id_institucion = $id_institucion
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
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

    public function get_datos_reporte_financiero() {
        try {
            $datos = Array();
            $this->db->trans_start();
            $sql = "SELECT
                        PROYECTO.id_proyecto,
                        PROYECTO.nombre_proyecto,
                        PROYECTO.presupuesto_proyecto,
                        INSTITUCION.id_institucion,
                        INSTITUCION.nombre_institucion
                    FROM
                        PROYECTO,
                        INSTITUCION
                    WHERE
                        PROYECTO.id_institucion = INSTITUCION.id_institucion AND
                        PROYECTO.en_edicion = false
                    ORDER BY
                        INSTITUCION.nombre_institucion ASC
                ";
            $query = $this->db->query($sql);
            if (!$query) {
                return Array();
            } else {
                if ($query->num_rows() == 0) {
                    return Array();
                } else {
                    $datos = Array();
                    $proyectos = $query->result();
                    $datos['proyectos'] = $proyectos;
                    foreach ($proyectos as $clave_proyecto => $proyecto) {
                        $actividades;
                        $sql = "SELECT
                                ACTIVIDAD.id_actividad,
                                ACTIVIDAD.nombre_actividad,
                                ACTIVIDAD.presupuesto_actividad
                            FROM
                                ACTIVIDAD
                            WHERE
                                ACTIVIDAD.id_proyecto = ?
                            ";
                        $query = $this->db->query($sql, Array($proyecto->id_proyecto));
                        if (!$query) {
                            $actividades = Array();
                            $datos['proyectos'][$clave_proyecto]->actividades = $actividades;
                        } else {
                            if ($query->num_rows() == 0) {
                                $actividades = Array();
                                $datos['proyectos'][$clave_proyecto]->actividades = $actividades;
                            } else {
                                $actividades = $query->result();
                                $datos['proyectos'][$clave_proyecto]->actividades = $actividades;
                                foreach ($actividades as $clave_actividad => $actividad) {
                                    $gastos;
                                    $sql = "SELECT
                                            GASTO_ACTIVIDAD.id_gasto_actividad,
                                            GASTO_ACTIVIDAD.fecha_gasto_actividad,
                                            GASTO_ACTIVIDAD.concepto_gasto_actividad,
                                            GASTO_ACTIVIDAD.importe_gasto_actividad,
                                            GASTO_ACTIVIDAD.respaldo_gasto_actividad
                                        FROM
                                            GASTO_ACTIVIDAD
                                        WHERE
                                            GASTO_ACTIVIDAD.id_actividad = ?
                                        ";
                                    $query = $this->db->query($sql, Array($actividad->id_actividad));
                                    if (!$query) {
                                        $gastos = Array();
                                        $datos['proyectos'][$clave_proyecto]->actividades[$clave_actividad]->gastos = $gastos;
                                    } else {
                                        if ($query->num_rows() == 0) {
                                            $gastos = Array();
                                            $datos['proyectos'][$clave_proyecto]->actividades[$clave_actividad]->gastos = $gastos;
                                        } else {
                                            $gastos = $query->result();
                                            $datos['proyectos'][$clave_proyecto]->actividades[$clave_actividad]->gastos = $gastos;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $this->db->trans_complete();
            return $datos;
        } catch (Exception $ex) {
            redirect(base_url() . 'coordinador/error');
        }
    }
    
    public function get_reporte_proyecto_completo_activo($id_proyecto) {
        try {
            if (!is_numeric($id_proyecto)) {
                redirect(base_url() . 'coordinador/error');
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
            redirect(base_url() . 'coordinador/error');
        }
    }

    private function get_actividades_activas_proyecto($id_proyecto) {
        if (!is_numeric($id_proyecto)) {
            redirect(base_url() . 'coordinador/error');
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
            redirect(base_url() . 'coordinador/error');
        }
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

    public function get_usuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            redirect(base_url() . 'coordinador');
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function verificar_password($id_usuario, $password) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url() . 'coordinador/error');
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function update_password_usuario($id_usuario, $password_usuario) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url() . 'coordinador/error');
        } else {
            try {
                $sql = "UPDATE USUARIO SET
                            USUARIO.password_usuario = ?
                        WHERE
                            USUARIO.id_usuario = ?
                        ";
                $query = $this->db->query($sql, Array($password_usuario, $id_usuario));
            } catch (Exception $ex) {
                redirect(base_url() . 'coordinador/error');
            }
        }
    }
    
    public function update_datos_contacto_usuario($id_usuario, $telefono_usuario, $correo_usuario) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url() . 'coordinador/error');
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
                redirect(base_url() . 'coordinador/error');
            }
        }
    }

}
