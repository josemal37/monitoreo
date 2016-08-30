<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modelo_administrador
 *
 * @author Jose
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Modelo_administrador extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_usuarios() {
        $sql = "SELECT
                    USUARIO.id_usuario,
                    USUARIO.id_institucion,
                    USUARIO.id_rol,
                    USUARIO.nombre_usuario,
                    USUARIO.apellido_paterno_usuario,
                    USUARIO.apellido_materno_usuario,
                    USUARIO.login_usuario,
                    USUARIO.telefono_usuario,
                    USUARIO.correo_usuario,
                    USUARIO.activo_usuario,
                    INSTITUCION.nombre_institucion,
                    INSTITUCION.sigla_institucion,
                    ROL.nombre_rol
                FROM
                    USUARIO,
                    INSTITUCION,
                    ROL
                WHERE
                    USUARIO.id_institucion = INSTITUCION.id_institucion AND
                    USUARIO.id_rol = ROL.id_rol
                ORDER BY
                    USUARIO.apellido_paterno_usuario ASC
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

    public function get_instituciones() {
        $sql = "SELECT
                    INSTITUCION.id_institucion,
                    INSTITUCION.nombre_institucion,
                    INSTITUCION.sigla_institucion,
                    INSTITUCION.presupuesto_institucion,
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
    }

    public function get_roles() {
        $sql = "SELECT
                    ROL.id_rol,
                    ROL.nombre_rol
                FROM
                    ROL
                ORDER BY
                    ROL.nombre_rol
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

    public function get_usuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            redirect(base_url() . 'administrador');
        } else {
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
                        INSTITUCION.nombre_institucion,
                        INSTITUCION.sigla_institucion,
                        ROL.nombre_rol
                    FROM
                        USUARIO,
                        INSTITUCION,
                        ROL
                    WHERE
                        USUARIO.id_institucion = INSTITUCION.id_institucion AND
                        USUARIO.id_rol = ROL.id_rol AND
                        USUARIO.id_usuario = $id_usuario
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

    public function insert_usuario($id_institucion, $id_rol, $nombre_usuario, $apellido_paterno_usuario, $apellido_materno_usuario, $login_usuario, $password_usuario, $telefono_usuario, $correo_usuario) {
        $sql = "INSERT INTO USUARIO
                (
                    USUARIO.id_institucion,
                    USUARIO.id_rol,
                    USUARIO.nombre_usuario,
                    USUARIO.apellido_paterno_usuario,
                    USUARIO.apellido_materno_usuario,
                    USUARIO.login_usuario,
                    USUARIO.password_usuario,
                    USUARIO.telefono_usuario,
                    USUARIO.correo_usuario,
                    USUARIO.activo_usuario
                )
                VALUES
                (
                    $id_institucion,
                    $id_rol,
                    '$nombre_usuario',
                    '$apellido_paterno_usuario',
                    '$apellido_materno_usuario',
                    '$login_usuario',
                    '$password_usuario',
                    $telefono_usuario,
                    '$correo_usuario',
                    true
                )
                ";
        $query = $this->db->query($sql);
    }
    
    public function activar_usuario($id_usuario) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url().'administrador');
        } else {
            $sql = "UPDATE USUARIO SET
                        USUARIO.activo_usuario = true
                    WHERE
                        USUARIO.id_usuario = $id_usuario
                    ";
            $query = $this->db->query($sql);
        }
    }
    
    public function desactivar_usuario($id_usuario) {
        if(!is_numeric($id_usuario)) {
            redirect(base_url().'administrador');
        } else {
            $sql = "UPDATE USUARIO SET
                        USUARIO.activo_usuario = false
                    WHERE
                        USUARIO.id_usuario = $id_usuario
                    ";
            $query = $this->db->query($sql);
        }
    }
    
    public function get_institucion($id_institucion) {
        if(!is_numeric($id_institucion)) {
            redirect(base_url() . 'administrador');
        } else {
            $sql = "SELECT
                        INSTITUCION.id_institucion,
                        INSTITUCION.nombre_institucion,
                        INSTITUCION.sigla_institucion,
                        INSTITUCION.presupuesto_institucion,
                        INSTITUCION.carpeta_institucion,
                        INSTITUCION.activa_institucion
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

    public function insert_institucion($nombre_institucion, $sigla_institucion, $presupuesto_institucion, $carpeta_institucion) {
        $sql = "INSERT INTO INSTITUCION
                (
                    INSTITUCION.nombre_institucion,
                    INSTITUCION.sigla_institucion,
                    INSTITUCION.presupuesto_institucion,
                    INSTITUCION.carpeta_institucion,
                    INSTITUCION.activa_institucion
                )
                VALUES
                (
                    '$nombre_institucion',
                    '$sigla_institucion',
                    $presupuesto_institucion,
                    '$carpeta_institucion',
                    true
                )
                ";
        $query = $this->db->query($sql);
    }
    
    public function update_institucion($id_institucion, $nombre_institucion, $sigla_institucion, $presupuesto_institucion) {
        if(!is_numeric($id_institucion)) {
            redirect(base_url() . 'administrador');
        } else {
            $sql = "UPDATE INSTITUCION SET
                        INSTITUCION.nombre_institucion = '$nombre_institucion',
                        INSTITUCION.sigla_institucion = '$sigla_institucion',
                        INSTITUCION.presupuesto_institucion = $presupuesto_institucion
                    WHERE
                        INSTITUCION.id_institucion = $id_institucion
                    ";
            $query = $this->db->query($sql);
        }
    }
    
    public function activar_institucion($id_institucion) {
        if(!is_numeric($id_institucion)) {
            redirect(base_url().'administrador');
        } else {
            $sql = "UPDATE INSTITUCION SET
                        INSTITUCION.activa_institucion = true
                    WHERE
                        INSTITUCION.id_institucion = $id_institucion
                    ";
            $query = $this->db->query($sql);
        }
    }
    
    public function desactivar_institucion($id_institucion) {
        if(!is_numeric($id_institucion)) {
            redirect(base_url().'administrador');
        } else {
            $sql = "UPDATE INSTITUCION SET
                        INSTITUCION.activa_institucion = false
                    WHERE
                        INSTITUCION.id_institucion = $id_institucion
                    ";
            $query = $this->db->query($sql);
        }
    }

}
