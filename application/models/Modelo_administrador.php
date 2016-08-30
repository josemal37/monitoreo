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
    
    public function get_instituciones() {
        $sql = "SELECT
                    INSTITUCION.id_institucion,
                    INSTITUCION.nombre_institucion,
                    INSTITUCION.sigla_institucion,
                    INSTITUCION.presupuesto_institucion,
                    INSTITUCION.carpeta_institucion
                FROM
                    INSTITUCION
                ORDER BY
                    INSTITUCION.nombre_institucion ASC
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
