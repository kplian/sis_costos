<?php
/**
*@package pXP
*@file gen-MODProrrateoCos.php
*@author  (franklin.espinoza)
*@date 25-08-2017 19:34:27
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODProrrateoCos extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarProrrateoCos(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='cos.ft_prorrateo_cos_sel';
		$this->transaccion='COS_PRO_COS_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_prorrateo','int4');
		$this->captura('codigo','varchar');
		$this->captura('nombre_prorrateo','varchar');
		$this->captura('tipo_calculo','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('id_gestion','int4');
		$this->captura('gestion','integer');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarProrrateoCos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_prorrateo_cos_ime';
		$this->transaccion='COS_PRO_COS_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('nombre_prorrateo','nombre_prorrateo','varchar');
		$this->setParametro('tipo_calculo','tipo_calculo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_gestion','id_gestion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarProrrateoCos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_prorrateo_cos_ime';
		$this->transaccion='COS_PRO_COS_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_prorrateo','id_prorrateo','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('nombre_prorrateo','nombre_prorrateo','varchar');
		$this->setParametro('tipo_calculo','tipo_calculo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_gestion','id_gestion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarProrrateoCos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_prorrateo_cos_ime';
		$this->transaccion='COS_PRO_COS_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_prorrateo','id_prorrateo','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function clonarProrrateoCos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_prorrateo_cos_ime';
		$this->transaccion='COS_PRO_COS_CLONAR';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_gestion_maestro','id_gestion_maestro','int4');
		$this->setParametro('id_gestion','id_gestion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function validarProrrateo(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='cos.ft_prorrateo_cos_ime';
		$this->transaccion='COS_PRO_COS_VALIDAR';
		$this->tipo_procedimiento='IME';//tipo de transaccion
		$this->setCount(false);

		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('nombre_prorrateo','nombre_prorrateo','varchar');
		$this->setParametro('id_gestion','id_gestion','int4');


		$this->captura('v_bandera','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>