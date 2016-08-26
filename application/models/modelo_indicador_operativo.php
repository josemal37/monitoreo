<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modelo_indicador_operativo
 *
 * @author Jose
 */
class modelo_indicador_operativo extends CI_Model {

    static $ACEPTABLE = 'Aceptable';
    static $LIMITADO = 'Limitado';
    static $NO_ACEPTABLE = 'No aceptable';
    
    public function __construct() {
        parent::__construct();
    }

    public function get_estado_indicador($id_indicador) {
        $indicador = $this->get_indicador_operativo($id_indicador);
        if (!$indicador) {
            return false;
        } else {
            $aceptable = $indicador->aceptable_op;
            $limitado = $indicador->limitado_op;
            $no_aceptable = $indicador->no_aceptable_op;
            $avance_indicador = $this->get_avance_indicador($id_indicador);
            $estado_indicador = $this->calcular_estado_indicador($avance_indicador, $aceptable, $limitado, $no_aceptable);
            return $estado_indicador;
        }
    }

    protected function get_indicador_operativo($id_indicador) {
        if (!is_numeric($id_indicador)) {
            redirect(base_url());
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

    function get_avance_indicador($id_indicador) {
        
    }

    protected function calcular_estado_indicador($avance_indicador, $aceptable, $limitado, $no_aceptable) {
        $estado = self::$NO_ACEPTABLE;
        if($avance_indicador <= $no_aceptable) {
            $estado = self::$NO_ACEPTABLE;
        } else {
            if($avance_indicador > $no_aceptable && $avance_indicador <= $limitado) {
                $estado = self::$LIMITADO;
            } else {
                if($avance_indicador > $limitado) {
                    $estado = self::$ACEPTABLE;
                }
            }
        }
        return $estado;
    }
}
