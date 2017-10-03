<?php
/**
*@package pXP
*@file gen-ACTTipoCosto.php
*@author  (admin)
*@date 27-12-2016 20:53:14
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/../reportes/RBalanceCostosXls.php');
require_once(dirname(__FILE__).'/../reportes/RBalanceCostosCatXls.php');
class ACTTipoCosto extends ACTbase{    
			
	function listarTipoCosto(){
		$this->objParam->defecto('ordenacion','id_tipo_costo_fk');
		$this->objParam->defecto('dir_ordenacion','asc');

		//filtro de tipo de costos de solo movimiento
		if ($this->objParam->getParametro('sw_trans') != '') {
			$this->objParam->addFiltro("tco.sw_trans  = ''". $this->objParam->getParametro('sw_trans')."''");
		}

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTipoCosto','listarTipoCosto');
		} else{
			$this->objFunc=$this->create('MODTipoCosto');			
			$this->res=$this->objFunc->listarTipoCosto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarTipoCostoArb(){
        $this->objParam->defecto('ordenacion','codigo');
        $this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('codigo') != '') {
            $this->objParam->addFiltro(" tco.codigo = " . $this->objParam->getParametro('codigo'));
        }
        
        //obtiene el parametro nodo enviado por la vista
        $node=$this->objParam->getParametro('node');

        $id_tipo_costo=$this->objParam->getParametro('id_tipo_costo');
        $tipo_nodo=$this->objParam->getParametro('tipo_nodo');
		
		
        
                   
        if($node=='id'){
            $this->objParam->addParametro('id_padre','%');
        }
        else {
            $this->objParam->addParametro('id_padre',$id_tipo_costo);
        }
        
		$this->objFunc=$this->create('MODTipoCosto');
        $this->res=$this->objFunc->listarTipoCostoArb();
        
        $this->res->setTipoRespuestaArbol();
        
        $arreglo=array();
        
        array_push($arreglo,array('nombre'=>'id','valor'=>'id_tipo_costo'));
        array_push($arreglo,array('nombre'=>'id_p','valor'=>'id_tipo_costo_fk'));
        
        
        array_push($arreglo,array('nombre'=>'text','valores'=>'<b> #codigo# - #nombre#</b>'));
        array_push($arreglo,array('nombre'=>'cls','valor'=>'nombre'));
        array_push($arreglo,array('nombre'=>'qtip','valores'=>'<b> #codigo#</b><br/><b> #nombre#</b><br> #descripcion#'));
        
        
        $this->res->addNivelArbol('tipo_nodo','raiz',array('leaf'=>false,
                                                        'allowDelete'=>true,
                                                        'allowEdit'=>true,
                                                        'cls'=>'folder',
                                                        'tipo_nodo'=>'raiz',
                                                        'icon'=>'../../../lib/imagenes/a_form.png'),
                                                        $arreglo);
         
        /*se ande un nivel al arbol incluyendo con tido de nivel carpeta con su arreglo de equivalencias
          es importante que entre los resultados devueltos por la base exista la variable\
          tipo_dato que tenga el valor en texto = 'hoja' */
                                                                

         $this->res->addNivelArbol('tipo_nodo','hijo',array(
                                                        'leaf'=>false,
                                                        'allowDelete'=>true,
                                                        'allowEdit'=>true,
                                                        'tipo_nodo'=>'hijo',
                                                        'icon'=>'../../../lib/imagenes/a_form.png'),
                                                        $arreglo);
														
		
		$this->res->addNivelArbol('tipo_nodo','hoja',array(
                                                        'leaf'=>true,
                                                        'allowDelete'=>true,
                                                        'allowEdit'=>true,
                                                        'tipo_nodo'=>'hoja',
                                                        'icon'=>'../../../lib/imagenes/a_table_gear.png'),
                                                        $arreglo);												
														

        $this->res->imprimirRespuesta($this->res->generarJson());         

    }
				
	function insertarTipoCosto(){
		$this->objFunc=$this->create('MODTipoCosto');	
		if($this->objParam->insertar('id_tipo_costo')){
			$this->res=$this->objFunc->insertarTipoCosto($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarTipoCosto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarTipoCosto(){
			$this->objFunc=$this->create('MODTipoCosto');	
		$this->res=$this->objFunc->eliminarTipoCosto($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function recuperarDatosBalanceCostos(){
    	
		$this->objFunc = $this->create('MODTipoCosto');
		$cbteHeader = $this->objFunc->listarBalanceCostos($this->objParam);
		if($cbteHeader->getTipo() == 'EXITO'){				
			return $cbteHeader->getDatos();
		}
        else{
		    $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
			exit;
		}              
		
    }
	
	
	
	function recuperarDatosBalanceCostosCategoria(){
    	
		$this->objFunc = $this->create('MODTipoCosto');
		$cbteHeader = $this->objFunc->listarBalanceCostosCat($this->objParam);
		if($cbteHeader->getTipo() == 'EXITO'){				
			return $cbteHeader->getDatos();
		}
        else{
		    $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
			exit;
		}              
		
    }

   function reporteBalanceCostos()	{
		
		
		if($this->objParam->getParametro('tipo_reporte') == 'periodo') {
           $dataSource = $this->recuperarDatosBalanceCostos();
           var_dump($dataSource);exit;
        }
		else{
			$dataSource = $this->recuperarDatosBalanceCostosCategoria();
		}
		
		
		
		//TODO recueprar configuracion ....
		
		$config = 'carta_horizontal';
		$titulo = 'Árbol de Ánalisis de Costos';
		$nombreArchivo=uniqid(md5(session_id()));
		
		//obtener tamaño y orientacion
		if ($config == 'carta_vertical') {
			$tamano = 'LETTER';
			$orientacion = 'P';
		} else if ($config == 'carta_horizontal') {
			$tamano = 'LETTER';
			$orientacion = 'L';
		} else if ($config == 'oficio_vertical') {
			$tamano = 'LEGAL';
			$orientacion = 'P';
		} else {
			$tamano = 'LEGAL';
			$orientacion = 'L';
		}
		
		$this->objParam->addParametro('orientacion',$orientacion);
		$this->objParam->addParametro('tamano',$tamano);		
		$this->objParam->addParametro('titulo_archivo',$titulo);
		$this->objParam->addParametro('test',$titulo);
		
		
		
			
		$nombreArchivo.='.xls';
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);
		//$this->objParam->addParametro('config',$this->res->datos[0]);
		$this->objParam->addParametro('datos',$dataSource);
		
		//Instancia la clase de excel
		
		if($this->objParam->getParametro('tipo_reporte') == 'periodo') {
           $this->objReporteFormato=new RBalanceCostosXls($this->objParam);
        }
		else{
			$this->objReporteFormato=new RBalanceCostosCatXls($this->objParam);
		}
		
		
		
		$this->objReporteFormato->imprimirDatos();
		
		$this->objReporteFormato->generarReporte();		
		
		
		//Retorna nombre del archivo
		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
				
	}
	
			
}

?>