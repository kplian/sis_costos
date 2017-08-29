<?php
/**
*@package pXP
*@file gen-MODProrrateoCosDet.php
*@author  (franklin.espinoza)
*@date 25-08-2017 19:35:31
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODProrrateoCosDet extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarProrrateoCosDet(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='cos.ft_prorrateo_cos_det_sel';
		$this->transaccion='COS_PROCOSDE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_prorrateo_det','int4');
		$this->captura('id_prorrateo','int4');
		$this->captura('id_tipo_costo','int4');
		$this->captura('id_cuenta','int4');
		$this->captura('id_auxiliar','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_tipo_costo','varchar');
		$this->captura('codigo','varchar');
		$this->captura('desc_cuenta','varchar');
		$this->captura('nro_cuenta','varchar');
		$this->captura('desc_auxiliar','varchar');
		$this->captura('codigo_auxiliar','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarProrrateoCosDet(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_prorrateo_cos_det_ime';
		$this->transaccion='COS_PROCOSDE_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_prorrateo','id_prorrateo','int4');
		$this->setParametro('id_tipo_costo','id_tipo_costo','int4');
		$this->setParametro('id_cuenta','id_cuenta','int4');
		$this->setParametro('id_auxiliar','id_auxiliar','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarProrrateoCosDet(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_prorrateo_cos_det_ime';
		$this->transaccion='COS_PROCOSDE_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_prorrateo_det','id_prorrateo_det','int4');
		$this->setParametro('id_prorrateo','id_prorrateo','int4');
		$this->setParametro('id_tipo_costo','id_tipo_costo','int4');
		$this->setParametro('id_cuenta','id_cuenta','int4');
		$this->setParametro('id_auxiliar','id_auxiliar','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarProrrateoCosDet(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_prorrateo_cos_det_ime';
		$this->transaccion='COS_PROCOSDE_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_prorrateo_det','id_prorrateo_det','int4');
		$this->setParametro('id_prorrateo','id_prorrateo','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarProrrateoCosCuenta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='cos.ft_prorrateo_cos_det_sel';
		$this->transaccion='COS_PROCUE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		$this->setCount(false);
		
		//Definicion de la lista del resultado del query
		$this->captura('id_tipo_costo_cuenta','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_cuenta','int4');
		$this->captura('nro_cuenta','varchar');
		$this->captura('nombre_cuenta','varchar');
		$this->captura('id_auxiliares','varchar');
		$this->captura('codigo_auxiliares','varchar');
		$this->captura('auxiliares','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('id_tipo_costo','int4');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}


	function listarProrrateoCosAuxiliares(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='cos.ft_prorrateo_cos_det_sel';
		$this->transaccion='COS_PROAUX_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		$this->setCount(false);
		$this->setParametro('id_tipo_costo','id_tipo_costo','int4');

		//Definicion de la lista del resultado del query
		$this->captura('id_auxiliar','int4');
		$this->captura('codigo_auxiliar','varchar');
		$this->captura('nombre_auxiliar','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function validarProrrateoDet(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='cos.ft_prorrateo_cos_det_ime';
		$this->transaccion='COS_PROCOSDE_VALIDAR';
		$this->tipo_procedimiento='IME';//tipo de transaccion
		$this->setCount(false);

		$this->setParametro('id_tipo_costo','id_tipo_costo','int4');
		$this->setParametro('id_cuenta','id_cuenta','int4');
		$this->setParametro('id_auxiliar','id_auxiliar','int4');
		$this->setParametro('id_prorrateo','id_prorrateo','int4');

		$this->captura('v_bandera','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>