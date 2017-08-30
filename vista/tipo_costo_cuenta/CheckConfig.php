<?php
/**
 * @package pXP
 * @file gen-CheckConfig.php
 * @author  (admin)
 * @date 30-12-2016 20:29:17
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.CheckConfig = Ext.extend(Phx.gridInterfaz, {

        constructor: function (config) {
            this.maestro = config.maestro;
            this.initButtons=[this.cmbGestion];
            //llama al constructor de la clase padre
            Phx.vista.CheckConfig.superclass.constructor.call(this, config);
            this.init();
            
            this.cmbGestion.on('select', function(){
			    if( this.validarFiltros() ){
	                  this.capturaFiltros();
	             }
			},this);

        },
        cmbGestion: new Ext.form.ComboBox({
				fieldLabel: 'Gestion',
				allowBlank: true,
				emptyText:'Gestion...',
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
				pageSize:50,
				queryDelay:500,
				listWidth:'280',
				width:80
			}),
        
        
            //hn
        Atributos: [
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'rownum'
                },
                type: 'Field',
                form: true
            },
            
            {
                config: {
                    name: 'nombre_cuenta',
                    fieldLabel: 'Nombre Cuenta',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 300,
                    maxLength: 10,
                    renderer : function(value, p, record) {
					     return String.format('{0} {1}', record.data['nro_cuenta'], value);
				    }
                },
                type: 'TextField',
                filters : {
					pfiltro : 'cue.nombre_cuenta#cue.nro_cuenta',
					type : 'string'
				},
				bottom_filter : true,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'nombre_auxiliar',
                    fieldLabel: 'Codigo Auxiliares',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 300,
                    maxLength: 10,
                    renderer : function(value, p, record) {
					     return String.format('{0} {1}', record.data['codigo_auxiliar'], value);
				    }
                },
                type: 'TextField',
                filters : {
					pfiltro : 'aux.nombre_auxiliar#aux.codigo_auxiliar',
					type : 'string'
				},
				bottom_filter : true,
                grid: true,
                form: false
            },


        ],
        tam_pag: 10000,
        title: 'Configuración',
        ActList: '../../sis_costos/control/TipoCostoCuenta/listarConfigPendiente',
        id_store: 'rownum',
        fields: [
             'id_auxiliar',
             'id_cuenta',
             'nro_cuenta',
             'nombre_cuenta',
             'codigo_auxiliar',
             'nombre_auxiliar',
             'rownum'


        ],
        sortInfo: {
            field: 'id_cuenta',
            direction: 'DESC'
        },

       
        onReloadPage: function (m) {
            
        },
        
        validarFiltros : function() {
			if (this.cmbGestion.validate() ) {
			
				return true;
			} else {
				return false;
			}
		},
        
        capturaFiltros : function(combo, record, index) {
			this.desbloquearOrdenamientoGrid();
			this.store.baseParams.id_gestion = this.cmbGestion.getValue();
			this.load();
		},
	        

		bnew: false,
		bedit: false,
        bdel: false,
        bsave: false
    })
</script>