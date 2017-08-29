<?php
/**
 *@package pXP
 *@file    SolModPresupuesto.php
 *@author  Rensi Arteaga Copari
 *@date    30-01-2014
 *@description permites subir archivos a la tabla de documento_sol
 */
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Phx.vista.ClonarPro=Ext.extend(Phx.frmInterfaz,{
        ActSave:'../../sis_costos/control/ProrrateoCos/clonarProrrateoCos',

        constructor:function(config){
            this.maestro = config;
            Phx.vista.ClonarPro.superclass.constructor.call(this,config);
            this.init();
        },

        Atributos:[
            {
                config:{
                    labelSeparator: '',
                    name: 'id_gestion_maestro',
                    inputType:'hidden'
                    ///value:  this.maestro.id_solicitud
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
                    width: 300,
                    listWidth:'232',
                    pageSize: 5
                },
                type : 'ComboRec',
                id_grupo : 0,
                form : true
            }
        ],
        title:'Clonar el Prorrateo de Costos a  la Getión',

        fields: [
            {name:'id_gestion_maestro', type: 'numeric'},
            {name:'id_gestion', type: 'numeric'}
        ],

        onSubmit:function(o){
            //se carga la getion
            this.Cmp.id_gestion_maestro.setValue(this.maestro.id_gestion);

            
            if( parseInt(this.Cmp.id_gestion.getValue())<= parseInt(this.maestro.id_gestion) && parseInt(this.Cmp.id_gestion.getValue()) != NaN){
                Ext.Msg.show({
                    title: 'Información',
                    msg: '<b>Estimado usuario, no es posible copiar los prorrateos de esta gestión en la misma gestión, o a un gestión menor a la actual seleccione un gestión superior.</b>',
                    buttons: Ext.Msg.OK,
                    width: 512,
                    icon: Ext.Msg.INFO
                });
            }else if(this.Cmp.id_gestion.getValue()==''){
                Ext.Msg.show({
                    title: 'Información',
                    msg: '<b>Estimado usuario no ha elegido ningun gestión para copiar sus prorrateos, seleccione una gestión.</b>',
                    buttons: Ext.Msg.OK,
                    width: 512,
                    icon: Ext.Msg.INFO
                });
            }else{
                Phx.vista.ClonarPro.superclass.onSubmit.call(this,o);

            }
        },

        successSave:function(resp){
            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            this.close();
        }



    })
</script>