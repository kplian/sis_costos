<?php
/**
*@package pXP
*@file gen-ACTProrrateoCosDet.php
*@author  (franklin.espinoza)
*@date 25-08-2017 19:35:31
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTProrrateoCosDet extends ACTbase{    
			
	function listarProrrateoCosDet(){
		$this->objParam->defecto('ordenacion','id_prorrateo_det');

		if($this->objParam->getParametro('id_prorrateo') != '') {
			$this->objParam->addFiltro(" procosde.id_prorrateo = " . $this->objParam->getParametro('id_prorrateo'));
		}

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODProrrateoCosDet','listarProrrateoCosDet');
		} else{
			$this->objFunc=$this->create('MODProrrateoCosDet');
			
			$this->res=$this->objFunc->listarProrrateoCosDet($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarProrrateoCosDet(){
		$this->objFunc=$this->create('MODProrrateoCosDet');	
		if($this->objParam->insertar('id_prorrateo_det')){
			$this->res=$this->objFunc->insertarProrrateoCosDet($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarProrrateoCosDet($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarProrrateoCosDet(){
		$this->objFunc=$this->create('MODProrrateoCosDet');
		$this->res=$this->objFunc->eliminarProrrateoCosDet($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarProrrateoCosCuenta(){

		if($this->objParam->getParametro('id_tipo_costo') != '') {
			$this->objParam->addFiltro(" coc.id_tipo_costo = " . $this->objParam->getParametro('id_tipo_costo'));
		}

		$this->objFunc=$this->create('MODProrrateoCosDet');
		$this->res=$this->objFunc->listarProrrateoCosCuenta($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarProrrateoCosAuxiliares(){

		$this->objFunc=$this->create('MODProrrateoCosDet');
		$this->res=$this->objFunc->listarProrrateoCosAuxiliares($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function validarProrrateoDet(){
		$this->objFunc=$this->create('MODProrrateoCosDet');
		$this->res=$this->objFunc->validarProrrateoDet($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>