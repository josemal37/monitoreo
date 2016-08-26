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
class modelo_indicador_promedio_menor_que extends modelo_indicador_operativo {

    public function __construct() {
        parent::__construct();
    }

    public function get_avance_indicador($id_indicador) {
        if (!is_numeric($id_indicador)) {
            redirect(base_url());
        } else {
            $sql = "SELECT
                        AVG(AVANCE_INDICADOR_OPERATIVO.avance_op) AS 'total_avance'
                    FROM
                        AVANCE_INDICADOR_OPERATIVO
                    WHERE
                        AVANCE_INDICADOR_OPERATIVO.id_indicador_op = $id_indicador
                    ";
            $query = $this->db->query($sql);
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

}
