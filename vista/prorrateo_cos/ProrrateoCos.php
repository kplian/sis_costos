<?php
/**
*@package pXP
*@file gen-ProrrateoCos.php
*@author  (franklin.espinoza)
*@date 25-08-2017 19:34:27
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ProrrateoCos=Ext.extend(Phx.gridInterfaz,{

	desc_gestion: '',
	constructor:function(config){

		this.tbarItems = ['-',
			this.cmbGestion,'-'

		];
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.ProrrateoCos.superclass.constructor.call(this,config);
		this.init();

		this.addButton('clonar_prorrateo',{
			grupo:[0,1,2,3,4,5],
			text:'Duplicar Prorrateo',
			iconCls: 'brenew',
			disabled:false,
			handler:this.clonarProrrateo,
			tooltip: '<b>Clona el prorrateo de costo.</b>'
		});

		Ext.Ajax.request({
			url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
			params:{fecha:new Date()},
			success:function (resp) {
				var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
				if(!reg.ROOT.error){
					this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
					this.cmbGestion.setRawValue(reg.ROOT.datos.anho);
					this.store.baseParams.id_gestion = reg.ROOT.datos.id_gestion;
					this.load({params:{start:0, limit:this.tam_pag}});
				}else{

					alert('Ocurrio un error al obtener la Gestión')
				}
			},
			failure: this.conexionFailure,
			timeout:this.timeout,
			scope:this
		});
		this.iniciarEventos();
		this.cmbGestion.on('select',this.capturarEventos, this);
	},

	iniciarEventos: function () {
		this.Cmp.id_gestion.on('focus', function () {
			this.desc_gestion = this.Cmp.id_gestion.getRawValue();
		},this);

		this.Cmp.id_gestion.on('select', function () {
			this.desc_gestion = this.Cmp.id_gestion.getRawValue();
		},this);

		this.Cmp.id_gestion.on('blur', function () {
			this.Cmp.id_gestion.setRawValue(this.desc_gestion);
		},this);

	},

	capturarEventos: function () {

		this.store.baseParams.id_gestion=this.cmbGestion.getValue();
		this.load({params:{start:0, limit:this.tam_pag}});
	},

	clonarProrrateo: function () {

			rec = {data:{id_gestion:this.cmbGestion.getValue()}}
			Phx.CP.loadWindows('../../../sis_costos/vista/prorrateo_cos/ClonarPro.php',
				'Definir la gestión a la que desea duplicar este prorrateo',
				{
					modal:true,
					width:450,
					height:150
				},
				rec.data
				,
				this.idContenedor,
				'ClonarPro'
			);

	},

	cmbGestion: new Ext.form.ComboBox({
		name: 'gestion',
		id: 'gestion_reg',
		fieldLabel: 'Gestion',
		allowBlank: true,
		emptyText:'Gestion...',
		blankText: 'Año',
		editable:false,
		store:new Ext.data.JsonStore(
			{
				url: '../../sis_parametros/control/Gestion/listarGestion',
				id: 'id_gestion',
				root: 'datos',
				sortInfo:{
					field: 'gestion',
					direction: 'DESC'
				},
				totalProperty: 'total',
				fields: ['id_gestion','gestion'],
				// turn on remote sorting
				remoteSort: true,
				baseParams:{par_filtro:'gestion'}
			}),
		valueField: 'id_gestion',
		triggerAction: 'all',
		displayField: 'gestion',
		hiddenName: 'id_gestion',
		mode:'remote',
		pageSize:5,
		queryDelay:500,
		listWidth:'280',
		hidden:false,
		width:80
	}),

	arrayDefaultColumHidden:[
		'fecha_reg','estado_reg', 'usuario_ai'
	],
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_prorrateo'
			},
			type:'Field',
			form:true 
		},

		{
			config:{
				name : 'id_gestion',
				origen : 'GESTION',
				fieldLabel : 'Gestión',
				allowBlank : false,
				forceSelection:false,
				editable: false,
				width: 125,
				listWidth:'232',
				pageSize: 5,
				forceSelection: true,
				renderer:function (value,p,record){

					return String.format('<div ext:qtip="Bueno"><b><font color="green">{0}</font></b><br></div>', record.data['gestion']);
				}
			},
			type : 'ComboRec',
			id_grupo : 0,
			form : true,
			grid:true
		},

		{
			config:{
				name: 'codigo',
				fieldLabel: 'Codigo',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:20
			},
				type:'TextField',
				bottom_filter:true,
				filters:{pfiltro:'pro_cos.codigo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nombre_prorrateo',
				fieldLabel: 'Nombre Prorrateo',
				allowBlank: false,
				anchor: '80%',
				gwidth: 300,
				maxLength:200
			},
				type:'TextField',
				bottom_filter:true,
				filters:{pfiltro:'pro_cos.nombre_prorrateo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'tipo_calculo',
				fieldLabel: 'Tipo Calculo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 25,
				typeAhead:true,
				forceSelection: true,
				triggerAction:'all',
				mode:'local',
				store:[ 'Hrs Vuelo ATO', 'ASK, RPK', 'Nro. Vuelos', 'Hrs. Vuelo Flota','Hrs. Vuelo Nave', 'NroPasajeros', 'ASK'],
				style:'text-transform:uppercase;'
			},
				type:'ComboBox',
				bottom_filter:true,
				filters:{pfiltro:'pro_cos.tipo_calculo',type:'string'},
				id_grupo:1,
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
				filters:{pfiltro:'pro_cos.estado_reg',type:'string'},
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
				filters:{pfiltro:'pro_cos.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'pro_cos.usuario_ai',type:'string'},
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
				filters:{pfiltro:'pro_cos.fecha_reg',type:'date'},
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
				filters:{pfiltro:'pro_cos.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'ProrrateoCostos',
	ActSave:'../../sis_costos/control/ProrrateoCos/insertarProrrateoCos',
	ActDel:'../../sis_costos/control/ProrrateoCos/eliminarProrrateoCos',
	ActList:'../../sis_costos/control/ProrrateoCos/listarProrrateoCos',
	id_store:'id_prorrateo',
	fields: [
		{name:'id_prorrateo', type: 'numeric'},
		{name:'codigo', type: 'string'},
		{name:'nombre_prorrateo', type: 'string'},
		{name:'tipo_calculo', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'id_gestion', type: 'numeric'},
		{name:'gestion', type: 'numeric'}

	],
	sortInfo:{
		field: 'id_prorrateo',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
	btest:false,
	tabsouth :[
		{
			url:'../../../sis_costos/vista/prorrateo_cos_det/ProrrateoCosDet.php',
			title:'Detalle Prorrateo',
			height:'50%',
			cls:'ProrrateoCosDet'
		}
	],

	onButtonNew:function () {

		Phx.vista.ProrrateoCos.superclass.onButtonNew.call(this);
		this.Cmp.id_gestion.setValue(this.cmbGestion.getValue());
		this.Cmp.id_gestion.setRawValue(this.cmbGestion.getRawValue());
	},

	onSubmit: function (o,x, force) {
		Ext.Ajax.request({
			url: '../../sis_costos/control/ProrrateoCos/validarProrrateo',
			params: {
				codigo: this.Cmp.codigo.getValue(),
				nombre_prorrateo: this.Cmp.nombre_prorrateo.getValue(),
				id_gestion: this.Cmp.id_gestion.getValue()
			},
			success: function (resp) {
				var reg = Ext.decode(Ext.util.Format.trim(resp.responseText));

				if(JSON.parse(reg.ROOT.datos.v_bandera)){
					Ext.Msg.show({
						title: 'Advertencia',
						msg: 'El registro con codigo /<b style="color: red">'+this.Cmp.codigo.getValue()+'</b>) y nombre (<span style="color: red">'+this.Cmp.nombre_prorrateo.getValue()
						+'</span>) ya fue registrado para la gestion (<b style="color: red">'+this.Cmp.id_gestion.getRawValue()+') verifique sus prorrateos.</b>',
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

	}
});
</script>
		
		