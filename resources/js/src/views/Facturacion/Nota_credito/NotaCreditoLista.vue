<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
        </div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup.enter="listar(1,buscar,cantidadp)"
            v-bind:placeholder="i18nbuscar"
          />
          <!--boton de herramientas-->
          <div class="dropdown-button-container mr-3">
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="settings" style="border-radius: 5px;"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" divider @click="abrirlinea()" >Link</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="abrirtipo()">Link</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="abrirmarca()">Link</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="abrirmodelo()">Link</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
          <!--Fin deboton de herramientas-->
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/facturacion/nota-credito/agregar">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" divider>Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>
      <vs-table stripe :data="contenido">
        <template slot="thead">
           <vs-th>Nro.</vs-th>
          <vs-th>Cliente</vs-th>
          <vs-th>Fecha de Emisión</vs-th>
          <vs-th>Fecha autorización</vs-th>
          <vs-th>Valor Total</vs-th>
          <vs-th>Estado</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id_cliente" v-for="datos in data">
            <vs-td v-if="datos.id_cliente">
              <a href="javascript:void(0)" @click="activePrompt = true">{{datos.id_cliente}}</a>
            </vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
           
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="pointer"
                @click.stop="editar(datos.id_cliente)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2 pointer"
                @click.stop="eliminarpro(datos.id_cliente)"
              />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vx-card>
  </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");
export default {
  components: {
    AgGridVue
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    crearrol() {
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[10].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[10].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[10].eliminar;
      }
      return res;
    }
  },
  data() {
    return {
       //mapeo de datos
      //paginacion
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      
      pagina: 1,
      cantidadp: 10,
      offset: 3,
      id: null,
     
    //buscador
      buscar: "",
      criterio: "id",
      //otros valores

      gridApi: null,
      contenido: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar")
      
    };
  },
  methods: {
   
    //listan tablas
    listar(page, buscar, cantidadp) {
      let me = this;
      var url =
        "/api/notacredito?page=" +
        page +
        "&buscar=" +
        buscar +
        "&cantidadp=" +
        cantidadp;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenido = respuesta.recupera.data;
          me.pagination = respuesta.pagination;
          if (cantidadp > me.pagination.total) {
            cantidadp = me.pagination.total;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
     updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
     crear() {
       console.log('agregar')
      this.$router.push("/facturacion/nota-credito/agregar");
    },
    
    editar(id){
      // revisar esta ruta 
        this.$router.push("/agregar/notacredito/"+id);
    },
    eliminar(id) {//metodo eliminar
      axios
      //Envia id 
      .delete("/api/eliminarCliente/"+id)
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text:
          "¿Desea Elimnar este registro?",
        accept: this.acceptAlert
      });
    },
    acceptAlert() {
 
      this.$vs.notify({
        color: "danger",
        title: "Cliente Eliminado  ",
        text: "El cliente selecionado fue eliminado con exito"
      });
      this.listar(1,this.buscar,this.cantidadp);
    },
    
   
    
  },
  mounted() {
    this.listar(1, this.buscar, this.cantidadp);
    
  }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.dropdown-button-container {
  display: flex;
  align-items: center;

  .btnx {
    border-radius: 5px 0px 0px 5px;
  }

  .btn-drop {
    border-radius: 0px 5px 5px 0px;
    border-left: 1px solid rgba(255, 255, 255, 0.2);
  }
}
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
.vs-popup {
  width: 1060px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
</style>
