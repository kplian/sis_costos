<?php
/**
*@package pXP
*@file Cuenta.php
*@author  Gonzalo Sarmiento Sejas
*@date 21-02-2013 15:04:03
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.TipoCosto = Ext.extend(Phx.arbGridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
		
    	//llama al constructor de la clase padre
		Phx.vista.TipoCosto.superclass.constructor.call(this,config);
		this.loaderTree.baseParams={id_gestion:0};
		this.init();
		this.iniciarEventos();
		
		this.addButton('bAux',{text:'Auxiliares',iconCls: 'blist',disabled:true,handler:this.onButonAux,tooltip: '<b>Auxiliares de la cuenta</b><br/>Se habilita si esta cuenta tiene permitido el registro de auxiliares '});
        this.addButton('btnImprimir',
			{
				text: 'Imprimir',
				iconCls: 'bprint',
				disabled: true,
				handler: this.imprimirCbte,
				tooltip: '<b>Imprimir Plan de Cuentas</b><br/>Imprime el Plan de Cuentas en el formato oficial.'
			}
		);
	},
	
		
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_tipo_costo'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_tipo_costo_fk'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'codigo',
				fieldLabel: 'codigo',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'tco.codigo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nombre',
				fieldLabel: 'nombre',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:500
			},
				type:'TextField',
				filters:{pfiltro:'tco.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		
		{
			config:{
				name: 'descripcion',
				fieldLabel: 'descripcion',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:500
			},
				type:'TextField',
				filters:{pfiltro:'tco.descripcion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		

		{
	       		config:{
	       			name:'sw_trans',
	       			fieldLabel:'Operación',
	       			allowBlank:false,
	       			emptyText:'Tipo...',
	       			typeAhead: true,
	       		    triggerAction: 'all',
	       		    lazyRender:true,
	       		    mode: 'local',
	       		    gwidth: 100,
	       		    store:['movimiento','titular']
	       		},
	       		type:'ComboBox',
	       		filters:{pfiltro:'tco.sw_trans',type:'string'},
	       		id_grupo:0,
	       		grid:true,
	       		form:true
	       	},
		
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'tco.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'tco.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'tco.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'tco.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu2.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'tco.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	
	title:'Clasificacición de Costos',
	ActSave:'../../sis_costos/control/TipoCosto/insertarTipoCosto',
	ActDel:'../../sis_costos/control/TipoCosto/eliminarTipoCosto',
	ActList:'../../sis_costos/control/TipoCosto/listarTipoCostoArb',
	id_store:'id_tipo_costo',
	
	textRoot:'Costos',
    id_nodo:'id_tipo_costo',
    id_nodo_p:'id_tipo_costo_fk',
	
	fields: [
		{name:'id_tipo_costo', type: 'numeric'},
		{name:'codigo', type: 'string'},
		{name:'nombre', type: 'string'},
		{name:'sw_trans', type: 'string'},
		{name:'descripcion', type: 'string'},
		{name:'id_tipo_costo_fk', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
		
	sortInfo:{
		field: 'id_tipo_costo',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
	rootVisible:true,
	expanded:false,
	
	
    getTipoCuentaPadre: function(n) {
			var direc
			var padre = n.parentNode;
            if (padre) {
				if (padre.attributes.id != 'id') {
					return this.getTipoCuentaPadre(padre);
				} else {
					return n.attributes.tipo_cuenta;
				}
			} else {
				return undefined;
			}
		},
   
    preparaMenu:function(n){
        if(n.attributes.tipo_nodo == 'hijo' || n.attributes.tipo_nodo == 'raiz' || n.attributes.id == 'id'){
            this.tbar.items.get('b-new-'+this.idContenedor).enable()
        }
        else {
            this.tbar.items.get('b-new-'+this.idContenedor).disable()
        }
        
      
        //this.getBoton('bAux').disable(); 
       
        // llamada funcion clase padre
        Phx.vista.TipoCosto.superclass.preparaMenu.call(this,n);
    },
    
    liberaMenu:function(n){
        // llamada funcion clase padre
        Phx.vista.TipoCosto.superclass.liberaMenu.call(this,n);
        
    },
    
    
    loadValoresIniciales:function()
	{
		Phx.vista.TipoCosto.superclass.loadValoresIniciales.call(this);
		
	},
	
    onButonAux:function(){
        var nodo = this.sm.getSelectedNode();
        Phx.CP.loadWindows('../../../sis_contabilidad/vista/cuenta_auxiliar/CuentaAuxiliar.php',
                    'Interfaces',
                    {
                        width:900,
                        height:400
                    },nodo.attributes,this.idContenedor,'CuentaAuxiliar')
       },
    
    iniciarEventos:function(){
    	
    	
    }
})
</script>