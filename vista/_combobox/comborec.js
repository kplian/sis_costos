Ext.namespace('Phx','Phx.comborec.sis_costos');

Phx.comborec.sis_costos.configini = function (config){

    if (config.origen == 'CUENTA2') {
        return {
            origen: 'CUENTA2',
            tinit:false,
            tasignacion:true,
            resizable:true,
            tname:'id_cuenta',
            tdisplayField:'nombre_cuenta',
            pid:this.idContenedor,
            name:'id_cuenta',
            fieldLabel:'Cuenta',
            allowBlank:true,
            emptyText:'Cuenta...',
            store: new Ext.data.JsonStore({
                url: '../../sis_costos/control/TipoCostoCuenta/listarCuentas',
                id: 'id_cuenta',
                root: 'datos',
                sortInfo:{
                    field: 'nro_cuenta',
                    direction: 'ASC'
                },
                totalProperty: 'total',
                fields: ['id_cuenta','nombre_cuenta','desc_cuenta','nro_cuenta','gestion','desc_moneda'],
                // turn on remote sorting
                remoteSort: true,
                baseParams:Ext.apply({par_filtro:'nro_cuenta#nombre_cuenta#desc_cuenta',sw_transaccional:'movimiento'}, config.baseParams)
            }),
            valueField: 'id_cuenta',
            displayField: 'nombre_cuenta',
            hiddenName: 'id_cuenta',
            tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nro_cuenta}</p><p>Nombre:{nombre_cuenta}</p> <p>({desc_moneda}) - {gestion}</p></div></tpl>',
            forceSelection:true,
            typeAhead: false,
            triggerAction: 'all',
            lazyRender:true,
            mode:'remote',
            pageSize:10,
            queryDelay:1000,
            width:250,
            listWidth:'280',
            minChars:2
        }

    }


}


