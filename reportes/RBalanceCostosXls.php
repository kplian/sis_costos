<?php
//incluimos la libreria
//echo dirname(__FILE__);
//include_once(dirname(__FILE__).'/../PHPExcel/Classes/PHPExcel.php');
class RBalanceCostosXls
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
		$sheet->getColumnDimension($this->equivalencias[11])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[12])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[13])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[14])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[15])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[13])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[14])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[15])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[16])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[17])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[18])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[19])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[20])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[21])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[22])->setWidth(15);
		$sheet->getColumnDimension($this->equivalencias[23])->setWidth(20);
		
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
    	
		$estilo = array('bold'=>true,
					    'italic'=>false,
					    'underline'=>$underline,
					    'size'=>12,
					    'name'=>Arial);
						
		$sheet->getStyle(($this->equivalencias[1]).$fila)->getFont()->applyFromArray($estilo);
																
																
		$sheet->getStyle(($this->equivalencias[1]).$fila)->getAlignment()->setHorizontal('center');																	
		$sheet->setCellValueByColumnAndRow(1,$fila,'Costo/Cuenta/Auxiliar');		
		$sheet->mergeCells(($this->equivalencias[1]).$fila.':k'.$fila);		
		$sheet->getStyle(($this->equivalencias[1]).$fila)->getAlignment()->setWrapText(true);
		
		
		
		
																			
		$sheet->setCellValueByColumnAndRow(11,$fila,'Enero');
		$sheet->setCellValueByColumnAndRow(12,$fila,'Febrero');	
		$sheet->setCellValueByColumnAndRow(13,$fila,'Marzo');	
		$sheet->setCellValueByColumnAndRow(14,$fila,'Abril');	
		$sheet->setCellValueByColumnAndRow(15,$fila,'Mayo');	
		$sheet->setCellValueByColumnAndRow(16,$fila,'Junio');	
		$sheet->setCellValueByColumnAndRow(17,$fila,'Julio');	
		$sheet->setCellValueByColumnAndRow(18,$fila,'Agosto');	
		$sheet->setCellValueByColumnAndRow(19,$fila,'Septiembre');	
		$sheet->setCellValueByColumnAndRow(20,$fila,'Octubre');	
		$sheet->setCellValueByColumnAndRow(21,$fila,'Noviembre');	
		$sheet->setCellValueByColumnAndRow(22,$fila,'Diciembre');
		$sheet->setCellValueByColumnAndRow(23,$fila,'TOTAL');	
		
		$sheet->getStyle(($this->equivalencias[11]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[12]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[13]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[14]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[15]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[16]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[17]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[18]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[19]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[20]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[21]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[22]).$fila)->getAlignment()->setHorizontal('center');
		$sheet->getStyle(($this->equivalencias[23]).$fila)->getAlignment()->setHorizontal('center');
		
		
		
		$sheet->getStyle(($this->equivalencias[11]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[12]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[13]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[14]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[15]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[16]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[17]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[18]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[19]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[20]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[21]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[22]).$fila)->getFont()->applyFromArray($estilo);
		$sheet->getStyle(($this->equivalencias[23]).$fila)->getFont()->applyFromArray($estilo);
		
		
		
		
    	
		
    }

 
			
	function imprimirDatos(){
		$datos = $this->objParam->getParametro('datos');
		$sheet = $this->docexcel->getActiveSheet();
		//Cabecera
		$this->imprimirTitulo($sheet);	
		$this->imprimirCabecera($sheet, 5);
		
		
		$fila = 6;
		$columnas = 0;
		$moneda = $this->objParam->getParametro('moneda');
		
		
		$sw = 0;
		$codigo_anterior='';
		/////////////////////***********************************Detalle***********************************************
		foreach($datos as $val) {
					
				if($sw == 0){
					$fila = $this->imprimirLinea($sheet,$fila, $val);
					$codigo_anterior = $val['codigo_orden'];
					$sw = 1;
				}
				else{
					if($codigo_anterior == $val['codigo_orden']){
						$this->agregarMes($sheet,$fila-1,$val);
					}
					else{
						$fila = $this->imprimirLinea($sheet,$fila, $val);
					}
				}
				
				
				$codigo_anterior = $val['codigo_orden'];
				
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
		
		$this->agregarMes($sheet,$fila,$val);
		
		
		$this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23,$fila,'=SUM(L'.$fila.':W' .($fila).')');
		$sheet->getStyle(($this->equivalencias[23]).$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2); 
		
		$this->docexcel->setActiveSheetIndex(0)
									->getRowDimension($fila)									
									->setOutlineLevel( $val["nivel"])
									->setVisible(true)
									->setCollapsed(false);
		
		$this->docexcel->getActiveSheet(0)->setShowSummaryBelow(false);
		
		//$this->checkGrupo($val,$fila);
		
		$fila++;
		
		
		
		return $fila;
		
    }
	
	Function agregarMes($sheet, $fila, $val){
		
		//////////////////////
		//coloca los montos
		/////////////////////				
		$col_ini_montos = 10;
		$columnaVal = $val["periodo"] + $col_ini_montos;
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

    function checkGrupo($val, $fila){
    	
		$nivel = $val["nivel"];
		
		if($nivel > $this->ulitmoNivelGrupo){
			$this->grupos[$nivel] = array($fila);
		}
		elseif ($nivel == $this->ulitmoNivelGrupo) {
			//actulizamos posicion final del grupo
			array_push($this->grupos[$nivel],$fila);
		}
		else{
				
			//si es menor cerramos grupo previo	
			//cerramos grupos
			/*for ($i=$this->ulitmoNivelGrupo; $i--; $i>=$nivel){
				$this->cerrarGrupo($i,$val);
				unset($this->grupos[$i]);
			}*/
			
			
			for ($i = $nivel ; $i++; $i<=$this->ulitmoNivelGrupo){
				$this->cerrarGrupo($i,$val);
				unset($this->grupos[$i]);
			}
			
			 print_r($nivel);
			 print_r("\n");
			 print_r($this->ulitmoNivelGrupo);
			 exit;
			 
			
			//parametrizamos nuevo grupo
			$this->grupos[$nivel] = array($fila);
			
		}
		$this->ulitmoNivelGrupo = $nivel;
	}
	
	function cerrarGrupo($nivel, $val){
		
		if(isset($this->grupos[$nivel])){
			foreach ($this->grupos[$nivel] as $row){
							
						$this->docexcel->setActiveSheetIndex(0)
									->getRowDimension($row)									
									->setOutlineLevel( $nivel)
									->setVisible(false)
									->setCollapsed(true);
									
					//->setOutlineLevel( ( (7- $nivel) % 7 ) + 1)	->setOutlineLevel( $nivel)			
									
		     }
		}
		else{
			/*	
			print_r($this->grupos[$nivel]);
			
			
			print_r($val);
			
			echo "ulitmo nivel registrado ".$this->ulitmoNivelGrupo. " NIVEL ".$nivel."\n";
			print_r($this->grupos);
		    exit;*/
		
		}
		
		
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