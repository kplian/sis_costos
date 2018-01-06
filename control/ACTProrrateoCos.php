<?php
/**
*@package pXP
*@file gen-ACTProrrateoCos.php
*@author  (franklin.espinoza)
*@date 25-08-2017 19:34:27
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTProrrateoCos extends ACTbase{    
			
	function listarProrrateoCos(){
		$this->objParam->defecto('ordenacion','id_prorrateo');

		if ($this->objParam->getParametro('id_gestion') != '') {

			$this->objParam->addFiltro("pro_cos.id_gestion = ". $this->objParam->getParametro('id_gestion'));

		}

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODProrrateoCos','listarProrrateoCos');
		} else{
			$this->objFunc=$this->create('MODProrrateoCos');
			
			$this->res=$this->objFunc->listarProrrateoCos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarProrrateoCos(){
		$this->objFunc=$this->create('MODProrrateoCos');	
		if($this->objParam->insertar('id_prorrateo')){
			$this->res=$this->objFunc->insertarProrrateoCos($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarProrrateoCos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarProrrateoCos(){
			$this->objFunc=$this->create('MODProrrateoCos');	
		$this->res=$this->objFunc->eliminarProrrateoCos($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function clonarProrrateoCos(){
		$this->objFunc=$this->create('MODProrrateoCos');
		$this->res=$this->objFunc->clonarProrrateoCos($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function validarProrrateo(){
		$this->objFunc=$this->create('MODProrrateoCos');
		$this->res=$this->objFunc->validarProrrateo($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

			
}

?>