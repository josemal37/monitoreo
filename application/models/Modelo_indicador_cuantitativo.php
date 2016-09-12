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
class Modelo_indicador_cuantitativo extends Modelo_indicador {

    public function __construct() {
        parent::__construct();
    }
    
    public function get_estado_indicador($indicador) {
        $avance_indicador = $this->get_avance_indicador($indicador->id_hito_cn);
        return $this->calcular_estado_indicador($indicador, $avance_indicador);
    }
}
