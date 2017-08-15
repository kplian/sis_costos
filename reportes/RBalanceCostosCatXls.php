<?php
//incluimos la libreria
//echo dirname(__FILE__);
//include_once(dirname(__FILE__).'/../PHPExcel/Classes/PHPExcel.php');
class RBalanceCostosCatXls
{
	private $docexcel;
	private $objWriter;
	private $nombre_archivo;
	private $hoja;
	private $columnas=array();
	private $fila;
	private $equivalencias=array();
	private $grupos=array();
	
	private $indice, $m_fila, $titulo;
	private $swEncabezado=0; //variable que define si ya se imprimi� el encabezado
	private $objParam;
	public  $url_archivo;	
	private $ulitmoNivelGrupo = 0;
	private $cabeceras=array();
	private $cabecerasCat=array();
	private $columna_fin;
	private $id_categoria_ult;
	private $ult_agrupador;
	private $estiloCabecera;
	
	function __construct(CTParametro $objParam){
		$this->objParam = $objParam;
		$this->url_archivo = "../../../reportes_generados/".$this->objParam->getParametro('nombre_archivo');
		//ini_set('memory_limit','512M');
		set_time_limit(400);
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
		$cacheSettings = array('memoryCacheSize'  => '10MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
		
		
		$this->docexcel = new PHPExcel();
		$this->docexcel->getProperties()->setCreator("PXP")
							 ->setLastModifiedBy("PXP")
							 ->setTitle($this->objParam->getParametro('titulo_archivo'))
							 ->setSubject($this->objParam->getParametro('titulo_archivo'))
							 ->setDescription('Reporte "'.$this->objParam->getParametro('titulo_archivo').'", generado por el framework PXP')
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Report File");
		$this->docexcel->setActiveSheetIndex(0);
		$this->docexcel->getActiveSheet()->setTitle($this->objParam->getParametro('titulo_archivo'));
		$this->equivalencias=array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
								9=>'J',10=>'K',11=>'L',12=>'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',
								18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',
								26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',
								34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP',
								42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',
								50=>'AY',51=>'AZ',
								52=>'BA',53=>'BB',54=>'BC',55=>'BD',56=>'BE',57=>'BF',58=>'BG',59=>'BH',
								60=>'BI',61=>'BJ',62=>'BK',63=>'BL',64=>'BM',65=>'BN',66=>'BO',67=>'BP',
								68=>'BQ',69=>'BR',70=>'BS',71=>'BT',72=>'BU',73=>'BV',74=>'BW',75=>'BX',
								76=>'BY',77=>'BZ');	
								
							
									
	}  
	
	
	function imprimirTitulo($sheet){
		$titulo = 'Árbol de Ánalisis de Costos ';
		$codigos = $this->objParam->getParametro('codigos');		
		$fechas = 'Del '.$this->objParam->getParametro('desde').' al '.$this->objParam->getParametro('hasta');
		$moneda = 'Expresado en moneda base';
		
		//TODO imprimir titulo
		
		$sheet->getColumnDimension($this->equivalencias[0])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[1])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[2])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[3])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[4])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[5])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[6])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[7])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[8])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[9])->setWidth(2);
		$sheet->getColumnDimension($this->equivalencias[10])->setWidth(45);
		
		//$sheet->setCellValueByColumnAndRow(0,1,$this->objParam->getParametro('titulo_rep'));
		$sheet->getStyle('A1')->getFont()->applyFromArray(array('bold'=>true,
															    'size'=>12,
															    'name'=>Arial));
																
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->setCellValueByColumnAndRow(0,1,strtoupper($titulo));		
		$sheet->mergeCells('A1:T1');
		
		//DEPTOS TITLE
		$sheet->getStyle('A2')->getFont()->applyFromArray(array(
															    'bold'=>true,
															    'size'=>10,
															    'name'=>Arial));	
																															
		$sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->setCellValueByColumnAndRow(0,2,strtoupper('DEPTOS: '.$codigos));		
		$sheet->mergeCells('A2:T2');
		//FECHAS
		$sheet->getStyle('A3')->getFont()->applyFromArray(array(
															    'bold'=>true,
															    'size'=>10,
															    'name'=>Arial));	
																															
		$sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->setCellValueByColumnAndRow(0,3,$fechas);		
		$sheet->mergeCells('A3:T3');
		
		
		$sheet->getStyle('A4')->getFont()->applyFromArray(array(
															    'bold'=>true,
															    'size'=>10,
															    'name'=>Arial));	
																															
		$sheet->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->setCellValueByColumnAndRow(0,3,$moneda);		
		$sheet->mergeCells('A4:T4');
		
	}

    function imprimirCabecera($sheet, $fila){
    	
		$this->estiloCabecera	= array('bold'=>true,
					    'italic'=>false,
					    'underline'=>$underline,
					    'size'=>12,
					    'name'=>Arial);
						
						
		
		$sheet->getStyle(($this->equivalencias[1]).$fila)->getFont()->applyFromArray($this->estiloCabecera);
																
																
		$sheet->getStyle(($this->equivalencias[1]).$fila)->getAlignment()->setHorizontal('center');																	
		$sheet->setCellValueByColumnAndRow(1,$fila,'Costo/Cuenta/Auxiliar');		
		$sheet->mergeCells(($this->equivalencias[1]).$fila.':k'.$fila);	
		$sheet->mergeCells(($this->equivalencias[1]).$fila.':k'.($fila+1));		
		$sheet->getStyle(($this->equivalencias[1]).$fila)->getAlignment()->setWrapText(true);
		
		
		$this->columna_fin = 11;
		$id_categoria_ult = 0;
		$ult_agrupador = 0;
		
		
    }
     
	function cerrarColumnasCabecera($sheet, $fila, $val) {
		$sheet->setCellValueByColumnAndRow($this->columna_fin,$fila,"Total");
		$sheet->getStyle(($this->equivalencias[$this->columna_fin]).($fila))->getAlignment()->setHorizontal('center');
		//$sheet->getStyle(($this->equivalencias[$this->columna_fin]).($fila))->applyFromArray($this->estiloCabecera);
		$sheet->getColumnDimension($this->equivalencias[$this->columna_fin])->setWidth(20);
		 //cerramos el argupador de cabecera
		$sheet->mergeCells(($this->equivalencias[$this->ult_agrupador]).($fila-1).':'.($this->equivalencias[$this->columna_fin]).($fila-1));
		$this->cabecerasCat["act".$this->id_categoria_ult][1] = $this->columna_fin;
		
	}
	 
     function agregarColumnaCabecera($sheet, $fila, $val){
     	
		//imprime cabecera
		if($this->id_categoria_ult != $val["id_categoria_programatica"]){
			
			// si no es el primer grupo cerramos el anterior
		    if($this->id_categoria_ult != 0){				 	
				 $this->cerrarColumnasCabecera($sheet, $fila, $val);				 
				 $this->columna_fin = $this->columna_fin +1;				 
			}
			
			$sheet->setCellValueByColumnAndRow($this->columna_fin,$fila-1,$val["desc_categoria"]);
			$sheet->getStyle(($this->equivalencias[$this->columna_fin]).($fila-1))->getAlignment()->setHorizontal('center');
			//$sheet->getStyle(($this->equivalencias[$this->columna_fin]).($fila-1))->getFont()->applyFromArray($this->estiloCabecera);
			//$sheet->getStyle(($this->equivalencias[$this->columna_fin]).($fila-1))->applyFromArray($this->estiloCabecera);
			
			
			$this->ult_agrupador = $this->columna_fin;
			$this->id_categoria_ult = $val["id_categoria_programatica"];
			$this->cabecerasCat["act".$val["id_categoria_programatica"]][0] = $this->ult_agrupador;
		}
		
		$this->cabeceras["act".$val["id_cp_actividad"]] = $this->columna_fin;
		
		
		//imprime subcabecera
	    $sheet->setCellValueByColumnAndRow($this->columna_fin, $fila, $val["desc_actividad"]);
		$sheet->getStyle(($this->equivalencias[$this->columna_fin]).$fila)->getAlignment()->setHorizontal('center');
		//$sheet->getStyle(($this->equivalencias[$this->columna_fin]).($fila))->getFont()->applyFromArray($this->estiloCabecera);
		//$sheet->getStyle(($this->equivalencias[$this->columna_fin]).($fila))->applyFromArray($this->estiloCabecera);
		$sheet->getColumnDimension($this->equivalencias[$this->columna_fin])->setWidth(40);
		
		
		$this->columna_fin = $this->columna_fin +1;
	 
	 }

    function  calcularTotales($sheet,$fila,$val){
    	$formula = '';
		foreach ($this->cabecerasCat as $valor){
			$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($valor[1],$fila,'=SUM('.$this->equivalencias[$valor[0]].$fila.':'.$this->equivalencias[$valor[1]-1].($fila).')');
		    $sheet->getStyle(($this->equivalencias[$valor[1]]).$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2); 
		
		     if($formula == ''){
		     	$formula = $formula.$this->equivalencias[$valor[1]].($fila);
		     }
			 else{
			 	$formula = $formula." +(".$this->equivalencias[$valor[1]].($fila).')';
			 }		
		}
		
		//print_r($formula);exit;
		
		//$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($this->columna_fin,$fila,'='.$formula);
		$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow($this->columna_fin+1,$fila,'=('.$formula.')');
		$sheet->getStyle(($this->equivalencias[$this->columna_fin+1]).$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2); 
		
		    
   	 
	   
    }
 
			
	function imprimirDatos(){
		$datos = $this->objParam->getParametro('datos');
		$sheet = $this->docexcel->getActiveSheet();
		//Cabecera
		$this->imprimirTitulo($sheet);	
		$this->imprimirCabecera($sheet, 5);
		
		
		$fila = 8;
		$columnas = 0;
		$moneda = $this->objParam->getParametro('moneda');
		/*
		$this->estiloCabecera	= array('bold'=>true,
					    'italic'=>false,
					    'underline'=>$underline,
					    'size'=>10,
					    'name'=>Arial);*/
		
		
		$this->estiloCabecera = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 10,
                'name'  => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'C0C0C0'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));
					
		
		$sw = 0;
		$codigo_anterior='';
		/////////////////////***********************************Detalle***********************************************
		foreach($datos as $val) {
				
			if($val['tipo'] != "cabecera"){
				if($sw == 0){
					
					//cierra el ulitmo grupo de la cabecera	
					$this->cerrarColumnasCabecera($sheet, $fila - 2, $val);
					//estilo de las cabeceras
					$sheet->getStyle($this->equivalencias[11]."6:".$this->equivalencias[$this->columna_fin]."6")->getAlignment()->setWrapText(true); 
					$sheet->getStyle($this->equivalencias[11]."5:".$this->equivalencias[$this->columna_fin]."5")->getAlignment()->setWrapText(true); 
					//Coloca la cabecera para el total general
					$sheet->setCellValueByColumnAndRow($this->columna_fin+1,$fila - 2,"TOTAL GENERAL");
					$sheet->getStyle(($this->equivalencias[$this->columna_fin+1]).($fila - 2))->getAlignment()->setHorizontal('center');
					//$sheet->getStyle(($this->equivalencias[$this->columna_fin+1]).($fila - 2))->applyFromArray($this->estiloCabecera);
					$sheet->getColumnDimension($this->equivalencias[$this->columna_fin+1])->setWidth(30);
					
					$sheet->getStyle(($this->equivalencias[1]).($fila-3).':'.($this->equivalencias[$this->columna_fin]).($fila-3))->applyFromArray($this->estiloCabecera);
					$sheet->getStyle(($this->equivalencias[1]).($fila-2).':'.($this->equivalencias[$this->columna_fin+1]).($fila-2))->applyFromArray($this->estiloCabecera);
					
					
					
					//primera line adel cuerpo
					$fila = $this->imprimirLinea($sheet,$fila, $val);
					$codigo_anterior = $val['codigo_orden'];
					$sw = 1;
				}
				else{
					if($codigo_anterior == $val['codigo_orden']){
						$this->agregarColumna($sheet,$fila-1,$val);
					}
					else{
						$fila = $this->imprimirLinea($sheet,$fila, $val);
					}
				}
				
			
				
				
				$codigo_anterior = $val['codigo_orden'];
			}
			else{
				$this->agregarColumnaCabecera($sheet,$fila -2,$val);
				
			}		
				
				
		}
		//************************************************Fin Detalle***********************************************
		
	}

    function imprimirLinea($sheet, $fila, $val){
    	
		$sw_espacio = 1;
		$sw_detalle = 1;
		//TABS
		$tabs = '';
		//signo	
		$signo = '';
		//alineacion del texto	
		//$posicion = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;	
		$underline = false;
		$bold = false;
		$italic = false;
		
		//////////////////
		//Coloca el texto
		//////////////////
		
		$texto = $tabs.'('.$val['codigo'].') '.$val['nombre'];
		$sheet->getStyle(($this->equivalencias[$val["nivel"] - 1]).$fila)->getFont()->applyFromArray(array(
															    'bold'=>$bold,
															    'italic'=>$italic,
															    'underline'=>$underline,
															    'size'=>8,
															    'name'=>Arial));
																
		
		
		
		$sheet->getStyle(($this->equivalencias[$val["nivel"] - 1]).$fila)->getAlignment()->setHorizontal($posicion);															
		$sheet->setCellValueByColumnAndRow($val["nivel"] - 1,$fila,$texto);
		$sheet->mergeCells(($this->equivalencias[$val["nivel"] - 1]).$fila.':k'.$fila);
		$sheet->getStyle(($this->equivalencias[$val["nivel"] - 1]).$fila)->getAlignment()->setWrapText(true);
		
		$this->agregarColumna($sheet,$fila,$val);
		
		
		
		$this->docexcel->setActiveSheetIndex(0)
									->getRowDimension($fila)									
									->setOutlineLevel( $val["nivel"])
									->setVisible(true)
									->setCollapsed(false);
		
		$this->docexcel->getActiveSheet(0)->setShowSummaryBelow(false);
		
		//$this->checkGrupo($val,$fila);
		
		$this->calcularTotales($sheet,$fila, $val);
		
		$fila++;
		
		
		
		return $fila;
		
    }
	
	Function agregarColumna($sheet, $fila, $val){
		
		//////////////////////
		//coloca los montos
		/////////////////////				
		$col_ini_montos = 10;
		
		$columnaVal = $this->cabeceras["act".$val["id_cp_actividad"]];
		$monto_str =  $val['monto'];
				
		//si el monto es menor a cero color rojo codigo CMYK
		if($monto_str*1 < 0){
			$color = array('rgb'=>'FF0000');
		}
		else{
			$color = array('rgb'=>'000000');
		}
				
		$sheet->getStyle(($this->equivalencias[$columnaVal]).$fila)->getFont()->applyFromArray(array(
																    'bold'=>true,
																    'size'=>10,
																    'name'=>Arial,
																    'color'=>$color));
																	
		//$sheet->mergeCells(($this->equivalencias[$columna]).$fila.':Q'.$fila);													
		
		$sheet->getStyle(($this->equivalencias[$columnaVal]).$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2); 
		
		$sheet->setCellValueByColumnAndRow($columnaVal,$fila,$monto_str);
		
		$sheet->getStyle(($this->equivalencias[$columnaVal]).$fila)
    					->getAlignment()
    					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		
	}

   
	
	

	function formatearTextoDetalle($texto){
		$tex=  ucwords(strtolower($texto));	
		$tex = str_replace("Y", "y", $tex);
		$tex = str_replace("De", "de", $tex);
		$tex = str_replace("En", "en", $tex);
		$tex = str_replace("Del", "del", $tex);
		
		return $tex;
	}
	
	function generarReporte(){
		//echo $this->nombre_archivo; exit;
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->docexcel->setActiveSheetIndex(0);
		$this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
		$this->objWriter->save($this->url_archivo);	
		
	}	
	
	function getLlaveFila($val){
		return  $val['descripcion'];		
		
	}
}
?>