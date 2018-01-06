<?php
/**
*@package pXP
*@file AuxiliarConf.php
*@author  Gonzalo Sarmiento Sejas
*@date 21-02-2013 20:44:52
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.AuxiliarConf=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.AuxiliarConf.superclass.constructor.call(this,config);
		this.init();
		

	},

	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_auxiliar'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_tipo_costo_cuenta'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'codigo_auxiliar',
				fieldLabel: 'Codigo Auxiliar',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:15
			},
			type:'TextField',
			filters:{pfiltro:'aux.codigo_auxiliar',type:'string'},
			bottom_filter : true,
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'nombre_auxiliar',
				fieldLabel: 'Nombre Auxiliar',
				allowBlank: true,
				anchor: '80%',
				gwidth: 230,
				maxLength:300
			},
			type:'TextField',
			filters:{pfiltro:'aux.nombre_auxiliar',type:'string'},
			bottom_filter : true,
			id_grupo:1,
			grid:true,
			form:true
		}
	],
	
	
	loadValoresIniciales:function(){
		Phx.vista.AuxiliarConf.superclass.loadValoresIniciales.call(this);
		this.Cmp.id_tipo_costo_cuenta.setValue(this.maestro.id_tipo_costo_cuenta);	
			

	},
	onReloadPage:function(m){
		this.maestro=m;						
		this.store.baseParams = { id_tipo_costo_cuenta:this.maestro.id_tipo_costo_cuenta };
		this.load({params:{start:0, limit:this.tam_pag}});
		
	},
	
	title:'Auxiliares de Cuenta',
	//ActSave:'../../sis_contabilidad/control/Auxiliar/insertarAuxiliar',
	//ActDel:'../../sis_contabilidad/control/Auxiliar/eliminarAuxiliar',
	ActList:'../../sis_costos/control/TipoCostoCuenta/listarAuxiliarConfigurado',
	id_store:'id_auxiliar',
	fields: [
		{name:'id_auxiliar', type: 'numeric'},
		{name:'nombre', type:'string'},
		{name:'codigo_auxiliar', type: 'string'},
		{name:'nombre_auxiliar', type: 'string'},
		'id_tipo_costo_cuenta'		
	],
	sortInfo:{
		field: 'id_auxiliar',
		direction: 'ASC'
	},
	bnew: false,
	bedit: false,
	bdel: false,
	bsave: false

})
</script>
		
		