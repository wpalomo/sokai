<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup.enter="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/administrar/empresa/agregar">Agregar</vs-button>
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
          <vs-th>Codigo.</vs-th>
          <vs-th>Nombre de empresa</vs-th>
          <vs-th>Razón social</vs-th>
          <vs-th>RUC</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id" v-for="datos in data">
            <vs-td>{{datos.codigo_empresa }}</vs-td>
            <vs-td v-if="datos.nombre_empresa">{{datos.nombre_empresa }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.razon_social">{{datos.razon_social}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.ruc_empresa">{{datos.ruc_empresa}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                @click="edit(datos.id_empresa)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                class="ml-2"
                @click="eliminar(datos.id_empresa)"
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
      //buscador
      buscar: "",
      criterio: "codCta",
      //otros valores
      gridApi: null,
      contenido: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar")
    };
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
      if(this.usuario == 1){
        res=1
      }else{
        res = this.$store.state.Roles[0].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario == 1){
        res=1
      }else{
        res = this.$store.state.Roles[0].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario == 1){
        res=1
      }else{
        res = this.$store.state.Roles[0].eliminar;
      }
      return res;
    }
  },
  methods: {
    edit(id) {
      this.$router.push(`/administrar/empresa/${id}/editar`);
    },
    eliminar(id) {
      axios
        //Envia id
        .delete("/api/eliminarempresa/" + id);
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        text: "¿Desea Elimnar este registro?",
        accept: this.acceptAlert
      });
    },
    acceptAlert() {
      this.$vs.notify({
        color: "danger",
        title: "Empresa Eliminado  ",
        text: "El producto selecionado fue eliminado con exito"
      });
      this.listar(1, this.buscar);
    },
    listar(page, buscar) {
      let me = this;
      var url = "/api/empresa?page=" + page + "&buscar=" + buscar;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenido = respuesta.recupera;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    }
  },
  mounted() {
    this.listar(1, this.buscar);
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
</style>
