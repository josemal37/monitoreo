<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modelo_indicador
 *
 * @author Jose
 */
class Modelo_indicador extends CI_Model {

    static $ACEPTABLE = 'Aceptable';
    static $LIMITADO = 'Limitado';
    static $NO_ACEPTABLE = 'No aceptable';

    public function __construct() {
        parent::__construct();
    }
    
    public function get_aceptable() {
        return self::$ACEPTABLE;
    }
    
    public function get_limitado() {
        return self::$LIMITADO;
    }
    
    public function get_no_aceptable() {
        return self::$NO_ACEPTABLE;
    }

}
