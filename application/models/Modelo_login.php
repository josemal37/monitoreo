<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modelo_login
 *
 * @author Jose
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Modelo_login extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_usuario($login_usuario, $password_usuario) {
        $sql = "SELECT
                    USUARIO.id_usuario,
                    USUARIO.login_usuario,
                    USUARIO.nombre_usuario,
                    USUARIO.apellido_paterno_usuario,
                    USUARIO.apellido_materno_usuario,
                    ROL.nombre_rol,
                    INSTITUCION.id_institucion,
                    INSTITUCION.nombre_institucion,
                    INSTITUCION.carpeta_institucion
                FROM
                    USUARIO,
                    ROL,
                    INSTITUCION
                WHERE
                    USUARIO.id_rol = ROL.id_rol AND 
                    USUARIO.id_institucion = INSTITUCION.id_institucion AND 
                    BINARY USUARIO.login_usuario = ? AND 
                    BINARY USUARIO.password_usuario = ? AND
                    USUARIO.activo_usuario = true AND
                    INSTITUCION.activa_institucion = true
                    ";
        $query = $this->db->query($sql, Array($login_usuario, $password_usuario));
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            $this->session->set_flashdata('usuario_incorrecto', 'Los datos introducidos son incorrectos');
            redirect(base_url() . 'login', 'refresh');
        }
    }

}
