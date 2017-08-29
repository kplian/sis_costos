<?php
/**
*@package pXP
*@file gen-ProrrateoCosDet.php
*@author  (franklin.espinoza)
*@date 25-08-2017 19:35:31
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ProrrateoCosDet=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.ProrrateoCosDet.superclass.constructor.call(this,config);
		this.init();
		this.iniciarEventos();
	},

	iniciarEventos: function () {
		this.Cmp.id_tipo_costo.on('select',function (cmb, record, index) {

			this.Cmp.id_cuenta.reset();
			this.Cmp.id_cuenta.modificado = true;
			this.Cmp.id_cuenta.setDisabled(false);
			this.Cmp.id_cuenta.store.baseParams = {par_filtro: 'c.nro_cuenta#c.nombre_cuenta', id_tipo_costo: this.Cmp.id_tipo_costo.getValue()};

			this.Cmp.id_auxiliar.reset();
			this.Cmp.id_auxiliar.modificado = true;
			this.Cmp.id_auxiliar.setDisabled(false);
			this.Cmp.id_auxiliar.store.baseParams = {par_filtro: 'aux.codigo_auxiliar#aux.nombre_auxiliar', id_tipo_costo: this.Cmp.id_tipo_costo.getValue()};

		},this);

	},
	
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_prorrateo_det'
			},
			type:'Field',
			form:true 
		},
		{

			config: {
				labelSeparator: '',
				inputType: 'hidden',
				name: 'id_prorrateo'
			},
			type: 'Field',
			form: true
		},
		{
			config: {
				name: 'id_tipo_costo',
				fieldLabel: 'Tipo de Costo',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_costos/control/TipoCosto/listarTipoCosto',
					id: 'id_tipo_costo',
					root: 'datos',
					sortInfo: {
						field: 'codigo',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_tipo_costo', 'nombre', 'codigo', 'descripcion'],
					remoteSort: true,
					baseParams: {par_filtro: 'tco.nombre#tco.codigo', sw_trans:'movimiento'}
				}),
				valueField: 'id_tipo_costo',
				displayField: 'nombre',
				gdisplayField: 'desc_tipo_costo',
				hiddenName: 'id_tipo_costo',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 300,
				minChars: 2,
				resizable:true,
				tpl: new Ext.XTemplate([
					'<tpl for=".">',
					'<div class="x-combo-list-item">',
					'<div class="awesomecombo-item {checked}">',
					'<p><b>Codigo: {codigo}</b></p>',
					'</div><p><b>Nombre:</b> <span style="color: green;">{nombre}</span></p>',
					'</div></tpl>'
				]),
				renderer : function(value, p, record) {
					var cadena = "<b style='color: green'>("+record.data['codigo']+") - </b>"+record.data['desc_tipo_costo'];
					return String.format('{0}',cadena);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			bottom_filter: true,
			filters: {pfiltro: 'tc.nombre#tc.codigo',type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_cuenta',
				fieldLabel: 'Cuenta',
				allowBlank: true,
				emptyText: 'Elija una cuenta...',
				disabled: true,
				store: new Ext.data.JsonStore({
					url: '../../sis_costos/control/ProrrateoCosDet/listarProrrateoCosCuenta',
					id: 'id_cuenta',
					root: 'datos',
					sortInfo: {
						field: 'nro_cuenta',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_cuenta', 'nro_cuenta','nombre_cuenta'],
					remoteSort: true,
					baseParams: {par_filtro: 'c.nro_cuenta#c.nombre_cuenta'}
				}),
				valueField: 'id_cuenta',
				displayField: 'nombre_cuenta',
				gdisplayField: 'desc_cuenta',
				hiddenName: 'id_cuenta',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 300,
				minChars: 2,
				resizable:true,
				tpl: new Ext.XTemplate([
					'<tpl for=".">',
					'<div class="x-combo-list-item">',
					'<div class="awesomecombo-item {checked}">',
					'<p><b>Nro. Cuenta: {nro_cuenta}</b></p>',
					'</div><p><b>Nombre Cuenta:</b> <span style="color: green;">{nombre_cuenta}</span></p>',
					'</div></tpl>'
				]),
				renderer : function(value, p, record) {
					var cadena = "<b style='color: green'>("+record.data['nro_cuenta']+") - </b>"+record.data['desc_cuenta'];
					return String.format('{0}', cadena);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			bottom_filter: true,
			filters: {pfiltro: 'c.nombre_cuenta#c.nro_cuenta',type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_auxiliar',
				fieldLabel: 'Auxiliar',
				allowBlank: true,
				emptyText: 'Elija un auxiliar...',
				disabled: true,
				store: new Ext.data.JsonStore({
					url: '../../sis_costos/control/ProrrateoCosDet/listarProrrateoCosAuxiliares',
					id: 'id_auxiliar',
					root: 'datos',
					sortInfo: {
						field: 'codigo_auxiliar',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_auxiliar', 'codigo_auxiliar', 'nombre_auxiliar'],
					remoteSort: true,
					baseParams: {par_filtro: 'aux.codigo_auxiliar#aux.nombre_auxiliar'}
				}),
				valueField: 'id_auxiliar',
				displayField: 'nombre_auxiliar',
				gdisplayField: 'desc_auxiliar',
				hiddenName: 'id_auxiliar',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 300,
				minChars: 2,
				resizable:true,
				tpl: new Ext.XTemplate([
					'<tpl for=".">',
					'<div class="x-combo-list-item">',
					'<div class="awesomecombo-item {checked}">',
					'<p><b>Cod. Auxiliar: {codigo_auxiliar}</b></p>',
					'</div><p><b>Nombre Auxiliar:</b> <span style="color: green;">{nombre_auxiliar}</span></p>',
					'</div></tpl>'
				]),
				renderer : function(value, p, record) {
					var cadena = "<b style='color: green'>("+record.data['codigo_auxiliar']+") - </b>"+record.data['desc_auxiliar'];
					return String.format('{0}', cadena);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			bottom_filter: true,
			filters: {pfiltro: 'aux.nombre_auxiliar#aux.codigo_auxiliar',type: 'string'},
			grid: true,
			form: true
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
				filters:{pfiltro:'procosde.estado_reg',type:'string'},
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
				filters:{pfiltro:'procosde.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'procosde.usuario_ai',type:'string'},
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
				filters:{pfiltro:'procosde.fecha_reg',type:'date'},
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
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'procosde.fecha_mod',type:'date'},
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
		}
	],
	tam_pag:50,	
	title:'ProrrateoCosDet',
	ActSave:'../../sis_costos/control/ProrrateoCosDet/insertarProrrateoCosDet',
	ActDel:'../../sis_costos/control/ProrrateoCosDet/eliminarProrrateoCosDet',
	ActList:'../../sis_costos/control/ProrrateoCosDet/listarProrrateoCosDet',
	id_store:'id_prorrateo_det',
	fields: [
		{name:'id_prorrateo_det', type: 'numeric'},
		{name:'id_prorrateo', type: 'numeric'},
		{name:'id_tipo_costo', type: 'numeric'},
		{name:'id_cuenta', type: 'numeric'},
		{name:'id_auxiliar', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_tipo_costo', type: 'string'},
		{name:'desc_cuenta', type: 'string'},
		{name:'desc_auxiliar', type: 'string'},
		{name:'codigo', type: 'string'},
		{name:'nro_cuenta', type: 'string'},
		{name:'codigo_auxiliar', type: 'string'}

	],
	sortInfo:{
		field: 'id_prorrateo_det',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
	btest:false,

	onButtonEdit:function () {

		Phx.vista.ProrrateoCosDet.superclass.onButtonEdit.call(this);
		this.Cmp.id_cuenta.store.baseParams = {par_filtro: 'c.nro_cuenta#c.nombre_cuenta', id_tipo_costo: this.Cmp.id_tipo_costo.getValue()};
		this.Cmp.id_auxiliar.store.baseParams = {par_filtro: 'aux.codigo_auxiliar#aux.nombre_auxiliar', id_tipo_costo: this.Cmp.id_tipo_costo.getValue()};
		this.Cmp.id_cuenta.setDisabled(false);
		this.Cmp.id_auxiliar.setDisabled(false);

	},

	onButtonNew:function () {
		this.Cmp.id_cuenta.reset();
		this.Cmp.id_auxiliar.reset();
		this.Cmp.id_cuenta.setDisabled(true);
		this.Cmp.id_auxiliar.setDisabled(true);

		Phx.vista.ProrrateoCosDet.superclass.onButtonNew.call(this);

	},

	onSubmit: function (o,x, force) {

		Ext.Ajax.request({
			url: '../../sis_costos/control/ProrrateoCosDet/validarProrrateoDet',
			params: {
				id_tipo_costo: this.Cmp.id_tipo_costo.getValue(),
				id_cuenta: this.Cmp.id_cuenta.getValue(),
				id_auxiliar: this.Cmp.id_auxiliar.getValue(),
				id_prorrateo: this.maestro.id_prorrateo
			},
			success: function (resp) {
				var reg = Ext.decode(Ext.util.Format.trim(resp.responseText));

				if(JSON.parse(reg.ROOT.datos.v_bandera)){
					Ext.Msg.show({
						title: 'Advertencia',
						msg: 'El registro con tipo costo (<b style="color: red">'+this.Cmp.id_tipo_costo.getRawValue()+'</b>), cuenta (<b style="color: red">'+this.Cmp.id_cuenta.getRawValue()
						+'</b>) y auxiliar (<b style="color: red">'+this.Cmp.id_auxiliar.getRawValue()+'</b>) ya fue registrado para este prorrateo, verifique su detalle.',
						buttons: Ext.Msg.OK,
						width: 512,
						icon: Ext.Msg.WARNING
					});
				}else{
					Phx.vista.ProrrateoCos.superclass.onSubmit.call(this, o);
				}
			},
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});

	},
	
	onReloadPage: function (m) {
		this.maestro = m;
		this.store.baseParams = {id_prorrateo:this.maestro.id_prorrateo};
		this.load({params: {start: 0, limit: 50}});
	},

	loadValoresIniciales: function () {
		this.Cmp.id_prorrateo.setValue(this.maestro.id_prorrateo);
		Phx.vista.ProrrateoCosDet.superclass.loadValoresIniciales.call(this);
	}
});
</script>
		
		