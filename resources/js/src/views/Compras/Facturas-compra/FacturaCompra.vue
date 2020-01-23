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
            @keyup.enter="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/compras/factura-compra/agregar">Agregar</vs-button>
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
      <flat-pickr :config="configdateTimePicker" v-model="event_at" hidden />
      <vs-table stripe :data="contenido">
        <template slot="thead">
          <vs-th>Nro. Comprobante</vs-th>
          <vs-th>Proveedor</vs-th>
          <vs-th>Fecha Reguistro</vs-th>
          <!--<vs-th>Fecha Vencimiento</vs-th>-->
          <!--<vs-th>Monto</vs-th>
          <vs-th>Abono</vs-th>-->
          <vs-th>Saldo</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id" v-for="datos in data">
            <vs-td>{{datos.id_factcompra}}</vs-td>
            <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fech_emision">{{datos.fech_emision | fecha}}</vs-td>
            <vs-td v-else>-</vs-td>
            <!--<vs-td v-if="datos.fvencimiento">
              <vs-alert color="danger" active="true">{{datos.fvencimiento}}</vs-alert>
            </vs-td>
            <vs-td v-else>{{datos.fvencimiento}}</vs-td>
            <vs-td v-if="datos.monto ">{{datos.monto }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.abono">{{datos.abono }}</vs-td>
            <vs-td v-else>-</vs-td>-->
            <vs-td v-if="datos.total_factura">{{datos.total_factura }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click.stop="listar2(datos.id_factcompra)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2"
                @click.stop="eliminar(datos.id_factcompra)"
              />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vx-card>
  </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
moment.locale("es");
import $ from "jquery";
const axios = require("axios");
import moment from "moment";
export default {
  components: {
    AgGridVue,
    flatPickr
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
      cuentaarray8: [],
      totalSaldo: "1.00",
      totalVencido: "",
      saldo: [],
      //buscador
      buscar: "",
      criterio: "cliente.id_cliente",
      //otros valores
      gridApi: null,
      contenido: [],
      event_at: new Date(),

      configdateTimePicker: {
        locale: SpanishLocale
      },
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //modal
      modal: false,
      titulomodal: "",
      //variables
      idrecupera: null,
      cliente: "",
      formapg: "",
      valor: "",
      descuento: "",
      nrotarjeta: "",
      banco: "",
      ctaContable: "",
      nroCta: "",
      nroComprobante: "",
      nroCuota: "",
      monto: 0,
      abono: 0,
      saldo: "0.00",
      estado: "",
      fechReguistro: "",
      event_at: new Date(),
      openDate: new Date(),
      configdateTimePicker: {
        locale: SpanishLocale
      },
      fechVencimiento: "",
      configFromdateTimePicker: {
        minDate: new Date(),
        maxDate: null,
        locale: SpanishLocale
      },
      //traer clientes
      clientes: [],
      idContable: ""
    };
  },
  computed: {
    totalsaldo() {
      let sum = 0;
      for (let i = 0; i < this.contenido.length; i++) {
        if (this.contenido[i].saldo != null) {
          sum += parseFloat(this.contenido[i].saldo);
        }
      }
      return sum;
    },
    totalsaldoVencido() {
      let sum2 = 0;
      let fh = new Date();
      let m = fh.getMonth() + 1;
      let fa = fh.getFullYear() + "-" + m + "-" + fh.getDate();
      //console.log(fa);
      for (let i = 0; i < this.contenido.length; i++) {
        if (this.contenido[i].fvencimiento < fa) {
          sum2 += parseFloat(this.contenido[i].saldo);
          //console.log("suma"+sum2);
        }
      }
      return sum2;
    },
    saldot() {
      this.saldo = parseFloat(this.monto) + parseFloat(this.abono);
    },
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
        res = this.$store.state.Roles[20].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[20].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[20].eliminar;
      }
      return res;
    }
  },
  filters: {
    fecha(data) {
      return moment(data).format("LL");
    },
  },
  methods: {
    listar2(id) {
      this.$router.push(`/compras/factura-compra/${id}/editar`);
    },
    eliminar(cd) {
      //console.log("1", cd);
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: `¿Desea Elimnar este registro?:`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.acceptAlert,
        parameters: cd
      });
    },
    acceptAlert(parameters) {
      //console.log("2", parameters);
      axios.delete("/api/eliminarfactcomp/" + parameters);
      //this.modal=false;
      this.$vs.notify({
        color: "danger",
        title: "Factura Eliminada",
        text: "La Factura selecionada fue eliminada con exito"
      });
      this.listar(1, this.buscar, this.cantidadp);
    },
    listar(page, buscar) {
      var url = "/api/factcompra/"+this.usuario.id_empresa+"?page=" + page + "&buscar=" + buscar;
      axios.get(url).then(res => {
        this.contenido = res.data.recupera;
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
.vs-popup {
  width: 1060px !important;
}
</style>