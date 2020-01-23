<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <!-- ITEMS PER PAGE -->
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
          <div class="dropdown-button-container">
            <vs-button class="btnx" type="filled" to="/compras/orden-compra/agregar">Agregar</vs-button>
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
          <vs-th>No.</vs-th>
          <vs-th>Cliente</vs-th>
          <vs-th>Fecha de Emisión</vs-th>
          <!--<vs-th>Estatus</vs-th>-->
          <vs-th>Valor Total</vs-th>
          <vs-th>Opciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id_factcompra" v-for="datos in data">
            <vs-td v-if="datos.id_factcompra">{{datos.id_factcompra}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nombre_proveedor">{{datos.nombre_proveedor}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fech_emision">{{datos.fech_emision | fecha}}</vs-td>
            <vs-td v-else>-</vs-td>
            <!--<vs-td v-if="datos.modo==1"><div style="color:#61B633;">Facturado</div></vs-td>
            <vs-td v-else><div style="color:#CE7C3B;">Sin Facturar</div></vs-td>-->
            <vs-td v-if="datos.total_factura">{{datos.total_factura | currency}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                @click.stop="editar(datos.id_factcompra)"
              />
              <feather-icon
                v-if="datos.modo!=1"
                icon="ShoppingCartIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2 pointer"
                @click.stop="crearfactura(datos.id_factcompra)"
              />
              <feather-icon
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                class="ml-2"
                @click.stop="eliminarprof(datos.id_factcompra)"
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
import moment from "moment";
moment.locale("es");
const axios = require("axios");
export default {
  components: {
    AgGridVue
  },
  filters: {
    fecha(data) {
      return moment(data).format("LL");
    }
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
        res = this.$store.state.Roles[8].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[8].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[8].eliminar;
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
      //buscador
      buscar: "",
      criterio: "secuencial",
      //otros valores
      gridApi: null,
      contenido: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar")
    };
  },
  methods: {
    listar(page, buscar) {
      var url =
        "/api/ordencompra/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenido = respuesta.recupera;
      });
    },
    eliminarprof(cd) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: `¿Desea Elimnar este registro?`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.acceptAlert,
        parameters: cd
      });
    },
    acceptAlert(parameters) {
      axios.delete("/api/eliminarorden/" + parameters);
      this.$vs.notify({
        color: "success",
        title: "Orden Eliminada  ",
        text: "Orden eliminada con exito"
      });
      this.listar(1, this.buscar);
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    editar(id) {
      this.$router.push(`/compras/orden-compra/${id}/editar`);
    },
    crearfactura(id) {
      this.$router.push(`/compras/factura-compra/${id}/editar`);
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