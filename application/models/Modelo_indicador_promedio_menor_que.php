<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modelo_indicador_promedio_menor_que
 *
 * @author Jose
 */
class Modelo_indicador_promedio_menor_que extends Modelo_indicador_cuantitativo {

    public function __construct() {
        parent::__construct();
    }
    
    public function get_avance_indicador($id_hito) {
        if (!is_numeric($id_hito)) {
            redirect(base_url());
        } else {
            $sql = "SELECT
                        AVG(AVANCE_HITO_CUANTITATIVO.cantidad_avance_hito_cn) AS 'total_avance'
                    FROM
                        AVANCE_HITO_CUANTITATIVO
                    WHERE
                        AVANCE_HITO_CUANTITATIVO.id_hito_cn = ? AND
                        AVANCE_HITO_CUANTITATIVO.aprobado_avance_hito_cn = true AND
                        AVANCE_HITO_CUANTITATIVO.en_revision_avance_hito_cn = false
                    ";
            $query = $this->db->query($sql, Array($id_hito));
            if (!$query) {
                return false;
            } else {
                if ($query->num_rows() == 0) {
                    return false;
                } else {
                    return $query->row()->total_avance;
                }
            }
        }
    }
    
    public function calcular_estado_indicador($indicador, $avance_indicador) {
        if($avance_indicador <= $indicador->no_aceptable_cn) {
            return self::$NO_ACEPTABLE;
        } else {
            if($avance_indicador > $indicador->no_aceptable_cn && $avance_indicador <= $indicador->limitado_cn) {
                return self::$LIMITADO;
            } else {
                if($avance_indicador > $indicador->limitado_cn) {
                    return self::$ACEPTABLE;
                }
            }
        }
    }

}
