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
        try {
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
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
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
            redirect(base_url() . 'administrador/error');
        }
    }

    public function get_roles() {
        try {
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
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }

    public function get_usuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            redirect(base_url() . 'administrador');
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
                redirect(base_url() . 'administrador/error');
            }
        }
    }

    public function insert_usuario($id_institucion, $id_rol, $nombre_usuario, $apellido_paterno_usuario, $apellido_materno_usuario, $login_usuario, $password_usuario, $telefono_usuario, $correo_usuario) {
        if (!is_numeric($id_institucion) || !is_numeric($id_rol)) {
            redirect(base_url() . 'administrador');
        } else {
            try {
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
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            true
                        )
                        ";
                $query = $this->db->query($sql, Array($id_institucion, $id_rol, $nombre_usuario, $apellido_paterno_usuario, $apellido_materno_usuario, $login_usuario, $password_usuario, $telefono_usuario, $correo_usuario));
            } catch (Exception $ex) {
                redirect(base_url() . 'administrador/error');
            }
        }
    }

    public function update_usuario($id_usuario, $id_institucion, $id_rol, $nombre_usuario, $apellido_paterno_usuario, $apellido_materno_usuario, $login_usuario, $password_usuario, $telefono_usuario, $correo_usuario) {
        if (!is_numeric($id_usuario) || !is_numeric($id_institucion) || !is_numeric($id_rol)) {
            redirect(base_url() . 'administrador');
        } else {
            try {
                $sql = "UPDATE USUARIO SET
                            USUARIO.id_institucion = ?,
                            USUARIO.id_rol = ?,
                            USUARIO.nombre_usuario = ?,
                            USUARIO.apellido_paterno_usuario = ?,
                            USUARIO.apellido_materno_usuario = ?,
                            USUARIO.login_usuario = ?,
                            USUARIO.password_usuario = ?,
                            USUARIO.telefono_usuario = ?,
                            USUARIO.correo_usuario = ?
                        WHERE
                            USUARIO.id_usuario = ?
                        ";
                $query = $this->db->query($sql, Array($id_institucion, $id_rol, $nombre_usuario, $apellido_paterno_usuario, $apellido_materno_usuario, $login_usuario, $password_usuario, $telefono_usuario, $correo_usuario, $id_usuario));
            } catch (Exception $ex) {
                redirect(base_url() . 'administrador/error');
            }
        }
    }

    public function activar_usuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            redirect(base_url() . 'administrador');
        } else {
            try {
                $sql = "UPDATE USUARIO SET
                            USUARIO.activo_usuario = true
                        WHERE
                            USUARIO.id_usuario = $id_usuario
                        ";
                $query = $this->db->query($sql);
            } catch (Exception $ex) {
                redirect(base_url() . 'administrador/error');
            }
        }
    }

    public function desactivar_usuario($id_usuario) {
        if (!is_numeric($id_usuario)) {
            redirect(base_url() . 'administrador');
        } else {
            try {
                $sql = "UPDATE USUARIO SET
                            USUARIO.activo_usuario = false
                        WHERE
                            USUARIO.id_usuario = $id_usuario AND
                            $id_usuario NOT IN( 
                                                SELECT
                                                    U.id_usuario 
                                                FROM
                                                    (
                                                        SELECT
                                                            USUARIO.id_usuario
                                                        FROM
                                                            USUARIO,
                                                            ROL
                                                        WHERE
                                                            USUARIO.id_rol = ROL.id_rol AND
                                                            ROL.nombre_rol = 'administrador'
                                                    ) as U
                                                )
                        ";
                $query = $this->db->query($sql);
            } catch (Exception $ex) {
                redirect(base_url() . 'administrador/error');
            }
        }
    }
    
    public function existe_correo_usuario($correo_usuario) {
        try {
            $sql = "SELECT
                        USUARIO.id_usuario
                    FROM
                        USUARIO
                    WHERE
                        USUARIO.correo_usuario = ?
                    ";
            $query = $this->db->query($sql, Array($correo_usuario));
            if($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }
    
    public function existe_correo_usuario_con_id($id_usuario, $correo_usuario) {
        try {
            $sql = "SELECT
                        USUARIO.id_usuario
                    FROM
                        USUARIO
                    WHERE
                        USUARIO.id_usuario != ? AND
                        USUARIO.correo_usuario = ?
                    ";
            $query = $this->db->query($sql, Array($id_usuario, $correo_usuario));
            if($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }
    
    public function existe_login_usuario($login_usuario) {
        try {
            $sql = "SELECT
                        USUARIO.id_usuario
                    FROM
                        USUARIO
                    WHERE
                        USUARIO.login_usuario = ?
                    ";
            $query = $this->db->query($sql, Array($login_usuario));
            if($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }
    
    public function existe_login_usuario_con_id($id_usuario, $login_usuario) {
        try {
            $sql = "SELECT
                        USUARIO.id_usuario
                    FROM
                        USUARIO
                    WHERE
                        USUARIO.id_usuario != ? AND
                        USUARIO.login_usuario = ?
                    ";
            $query = $this->db->query($sql, Array($id_usuario, $login_usuario));
            if($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }

    public function get_institucion($id_institucion) {
        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'administrador');
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
                redirect(base_url() . 'administrador/error');
            }
        }
    }

    public function insert_institucion($nombre_institucion, $sigla_institucion, $presupuesto_institucion, $carpeta_institucion) {
        try {
            $this->db->trans_start();
            $sql = "SELECT
                        INSTITUCION.id_institucion
                    FROM
                        INSTITUCION
                    WHERE
                        INSTITUCION.nombre_institucion = ? OR
                        INSTITUCION.sigla_institucion = ?
                    ";
            $query = $this->db->query($sql, Array($nombre_institucion, $sigla_institucion));
            $existe = false;
            if($query->num_rows() > 0) {
                $existe = true;
            }
            if(!$existe) {
                $i = 1;
                while (file_exists('./files/' . $carpeta_institucion)) {
                    if($i > 1) {
                        $carpeta_institucion = substr($carpeta_institucion, 0, strlen($carpeta_institucion) - 2);
                    }
                    $carpeta_institucion = $carpeta_institucion . '_' . $i;
                    $i = $i + 1;
                }
                $carpeta = mkdir('./files/' . $carpeta_institucion);
                if(file_exists('./files/index.html') && file_exists('./files/' . $carpeta_institucion)) {
                    copy('./files/index.html', './files/' . $carpeta_institucion . '/index.html');
                }
                $sql = "INSERT INTO INSTITUCION
                        (
                            INSTITUCION.nombre_institucion,
                            INSTITUCION.sigla_institucion,
                            INSTITUCION.carpeta_institucion,
                            INSTITUCION.activa_institucion
                        )
                        VALUES
                        (
                            '$nombre_institucion',
                            '$sigla_institucion',
                            '$carpeta_institucion',
                            true
                        )
                        ";
                $query = $this->db->query($sql);
            } else {
                $this->session->set_flashdata('existe_institucion', true);
                redirect(base_url() . 'administrador/nueva_institucion', 'refresh');
            }
            $this->db->trans_complete();
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }

    public function update_institucion($id_institucion, $nombre_institucion, $sigla_institucion, $presupuesto_institucion) {
        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'administrador');
        } else {
            try {
                $sql = "UPDATE INSTITUCION SET
                            INSTITUCION.nombre_institucion = '$nombre_institucion',
                            INSTITUCION.sigla_institucion = '$sigla_institucion'
                        WHERE
                            INSTITUCION.id_institucion = $id_institucion
                        ";
                $query = $this->db->query($sql);
            } catch (Exception $ex) {
                redirect(base_url() . 'administrador/error');
            }
        }
    }

    public function activar_institucion($id_institucion) {
        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'administrador');
        } else {
            try {
                $sql = "UPDATE INSTITUCION SET
                            INSTITUCION.activa_institucion = true
                        WHERE
                            INSTITUCION.id_institucion = $id_institucion
                        ";
                $query = $this->db->query($sql);
            } catch (Exception $ex) {
                redirect(base_url() . 'administrador/error');
            }
        }
    }

    public function desactivar_institucion($id_institucion) {
        if (!is_numeric($id_institucion)) {
            redirect(base_url() . 'administrador');
        } else {
            try {
                $sql = "UPDATE INSTITUCION SET
                            INSTITUCION.activa_institucion = false
                        WHERE
                            INSTITUCION.id_institucion = $id_institucion
                        ";
                $query = $this->db->query($sql);
            } catch (Exception $ex) {
                redirect(base_url() . 'administrador/error');
            }
        }
    }
    
    public function existe_nombre_institucion($nombre_institucion) {
        try {
            $sql = "SELECT
                        INSTITUCION.id_institucion
                    FROM
                        INSTITUCION
                    WHERE
                        INSTITUCION.nombre_institucion = ?
                    ";
            $query = $this->db->query($sql, Array($nombre_institucion));
            if($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }
    
    public function existe_nombre_institucion_con_id($id_institucion, $nombre_institucion) {
        try {
            $sql = "SELECT
                        INSTITUCION.id_institucion
                    FROM
                        INSTITUCION
                    WHERE
                        INSTITUCION.id_institucion != ? AND
                        INSTITUCION.nombre_institucion = ?
                    ";
            $query = $this->db->query($sql, Array($id_institucion, $nombre_institucion));
            if($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }
    
    public function existe_sigla_institucion($sigla_institucion) {
        try {
            $sql = "SELECT
                        INSTITUCION.id_institucion
                    FROM
                        INSTITUCION
                    WHERE
                        INSTITUCION.sigla_institucion = ?
                    ";
            $query = $this->db->query($sql, Array($sigla_institucion));
            if($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }
    
    public function existe_sigla_institucion_con_id($id_institucion, $sigla_institucion) {
        try {
            $sql = "SELECT
                        INSTITUCION.id_institucion
                    FROM
                        INSTITUCION
                    WHERE
                        INSTITUCION.id_institucion != ? AND
                        INSTITUCION.sigla_institucion = ?
                    ";
            $query = $this->db->query($sql, Array($id_institucion, $sigla_institucion));
            if($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            redirect(base_url() . 'administrador/error');
        }
    }

}
