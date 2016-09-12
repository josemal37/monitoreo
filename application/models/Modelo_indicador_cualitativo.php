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
class Modelo_indicador_cualitativo extends Modelo_indicador {

    public function __construct() {
        parent::__construct();
    }

    public function get_estado_indicador($id_hito) {
        $avances = $this->get_avances_aprobados_hito_cualitativo($id_hito);
        if (!$avances) {
            return self::$NO_ACEPTABLE;
        } else {
            return $this->calcular_estado_indicador($avances);
        }
    }

    protected function get_avances_aprobados_hito_cualitativo($id_indicador) {
        if (!is_numeric($id_indicador)) {
            redirect(base_url());
        } else {
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
                        AVANCE_HITO_CUALITATIVO.en_revision_avance_hito_cl = false AND
                        AVANCE_HITO_CUALITATIVO.aprobado_avance_hito_cl = true
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
    }

    protected function calcular_estado_indicador($avances) {
        $estado = self::$NO_ACEPTABLE;
        if (sizeof($avances) >= 0) {
            $estado = self::$ACEPTABLE;
        }
        return $estado;
    }

}
