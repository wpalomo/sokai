<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center">
      <div></div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <!--<vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup.enter="listar(1,buscar,criterio,cantidadp)"
            v-bind:placeholder="i18nbuscar"
          />-->
          <vs-select
            placeholder="Buscar Proveedor"
            class="mb-4 md:mb-0 mr-4"
            vs-multiple
            autocomplete
            v-model="buscar"
            @change="listar(1,buscar)"
          >
            <vs-select-item
              v-for="data in clientes"
              :key="data.id_proveedor"
              :value="data.id_proveedor"
              :text="data.nombre_proveedor"
            />
          </vs-select>
          <!--<vs-button class="mb-4 md:mb-0" @click="gridApi.exportDataAsCsv()">Export as CSV</vs-button>-->
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/compras/cuentas-por-pagar/agregar">Agregar</vs-button>
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
      <br />
      <vs-popup classContent="popup-example" :active.sync="modal" :title="titulomodal">
        <div class="vx-row">
          <div class="vx-col sm:w-1/5 w-full mb-2">
            <vs-select
              placeholder="Search and select"
              class="selectExample"
              label="Cliente"
              vs-multiple
              autocomplete
              v-model="cliente"
              @change="getClientes()"
            >
              <vs-select-item
                v-for="data in clientes"
                :key="data.id_cliente"
                :value="data.id_cliente"
                :text="data.nombre"
              />
            </vs-select>
            {{clientes}}
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-2">
            <vs-select
              placeholder="Search and select"
              class="selectExample"
              label="Forma Pago"
              vs-multiple
              autocomplete
              v-model="formapg"
            >
              <vs-select-item value="1" text="Efectivo" />
              <vs-select-item value="2" text="Deposito" />
              <vs-select-item value="3" text="Cheque" />
              <vs-select-item value="4" text="Transferencia" />
              <vs-select-item value="5" text="Tarjeta Credito" />
              <vs-select-item value="6" text="Otro" />
            </vs-select>
          </div>
        </div>
        <div class="vx-row">
          <!-- <div class="vx-col sm:w-1/6 w-full mb-6">
                <vs-input class="w-full" label="Valor" v-validate="'max_value:5000000'" name="val" v-model="valor" />
                <span class="text-danger text-sm" v-show="errors.has('val')">Escriba correctamente el valor</span> 
          </div>-->
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input
              class="w-full"
              label="Descuento"
              v-validate="'max_value:5000000'"
              name="desc"
              v-model="descuento"
            />
            <span
              class="text-danger text-sm"
              v-show="errors.has('desc')"
            >Escriba correctamente el descuento</span>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6" v-if="formapg==5">
            <vs-input
              class="w-full"
              label="Nro Tarjeta"
              v-validate="'numeric'"
              name="nrotarj"
              v-model="nrotarjeta"
            />
            <span
              class="text-danger text-sm"
              v-show="errors.has('nrotarj')"
            >Escriba correctamente el nro Tarjeta</span>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-2" v-if="formapg==2 || formapg==3 || formapg==4">
            <vs-input class="w-full" label="Banco" v-model="banco" />
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6" v-if="formapg==6">
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class="mb-base">
              <vs-input class="w-full" v-model="ctaContable" :value="idContable" disabled />

              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary" @click="activePrompt = true">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-2">
            <vs-input class="w-full" label="Nro Comprobante" v-model="nroComprobante" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Nro Cuota" v-model="nroCuota" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Fecha Reguistro</label>
            <flat-pickr :config="configdateTimePicker" v-model="fechReguistro" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Fecha Vencimiento</label>
            <flat-pickr :config="configFromdateTimePicker" v-model="fechVencimiento" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-2">
            <vs-input
              class="w-full"
              label="Monto"
              v-validate="'max_value:5000000'"
              name="mont"
              v-model="monto"
            />
            <span
              class="text-danger text-sm"
              v-show="errors.has('mont')"
            >Escriba correctamente el monto</span>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input
              class="w-full"
              label="Abono"
              v-validate="'max_value:5000000'"
              name="abon"
              v-model="abono"
            />
            <span
              class="text-danger text-sm"
              v-show="errors.has('abon')"
            >Escriba correctamente el Abono</span>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input
              class="w-full"
              label="Saldo"
              v-validate="'max_value:5000000'"
              name="sald"
              v-model="saldo"
            />
            <span
              class="text-danger text-sm"
              v-show="errors.has('sald')"
            >Escriba correctamente el Abono</span>
          </div>

          <vs-select
            placeholder="Search and select"
            class="selectExample"
            label="Estado"
            vs-multiple
            autocomplete
            v-model="estado"
            v-if="idrecupera"
          >
            <vs-select-item value="1" text="Activo" />
            <vs-select-item value="0" text="Inactivo" />
          </vs-select>
        </div>
        <vs-button color="success" type="filled" @click="guardar()" v-if="!idrecupera">Agregar</vs-button>
        <vs-button color="success" type="filled" @click="editar()" v-else>Agregar</vs-button>
      </vs-popup>

      <flat-pickr :config="configdateTimePicker" v-model="event_at" hidden />

      <vs-table stripe :data="contenido">
        <template slot="thead">
          <vs-th>Nro. Comprobante</vs-th>
          <vs-th>Nro. Cuota</vs-th>
          <vs-th>Fecha Reguistro</vs-th>
          <vs-th>Fecha Vencimiento</vs-th>
          <vs-th>Monto</vs-th>
          <vs-th>Abono</vs-th>
          <vs-th>Saldo</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id" v-for="datos in data">
            <vs-td>{{datos.nro_comprobante}}</vs-td>
            <vs-td v-if="datos.nro_cuota">{{datos.nro_cuota}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.freguistro">{{datos.freguistro}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fvencimiento<=format_date(event_at)">
              <vs-alert color="danger" active="true">{{datos.fvencimiento}}</vs-alert>
            </vs-td>
            <vs-td v-else>{{datos.fvencimiento}}</vs-td>
            <vs-td v-if="datos.monto ">{{datos.monto }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.abono">{{datos.abono }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.saldo">{{datos.saldo }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click.stop="listar2(datos.id_ctascobrar)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2"
                @click.stop="eliminar(datos.id_ctascobrar)"
              />
            </vs-td>
          </vs-tr>
          <vs-tr style="border-top: 1px solid #ddd;">
            <vs-td>Total Vencido</vs-td>
            <vs-td>
              <vs-alert color="danger" active="true"></vs-alert>
            </vs-td>
            <vs-td>-</vs-td>
            <vs-td>-</vs-td>
            <vs-td>-</vs-td>
            <vs-td>Total</vs-td>
            <vs-td></vs-td>
            <vs-td>-</vs-td>
          </vs-tr>
        </template>
      </vs-table>

      <vs-pagination
        :total="pagination.count"
        v-model="pagina"
        @change="listar(pagina,buscar,criterio,cantidadp)"
        prev-icon="arrow_back"
        next-icon="arrow_forward"
      ></vs-pagination>
    </vx-card>
  </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");
import moment from "moment";
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
  components: {
    flatPickr
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
        res = this.$store.state.Roles[22].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[22].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[22].eliminar;
      }
      return res;
    }
    /*
    totalsaldo() {
      let sum = 0;
      for(let i = 0; i < this.contenido.length; i++){
        if(this.contenido[i].saldo!=null){
          sum += (parseFloat(this.contenido[i].saldo));
        }
      }
      return sum;
    },
    totalsaldoVencido() {
      let sum2 = 0;
      let fh=new Date();
      let m=fh.getMonth()+1;
      let fa=fh.getFullYear()+"-"+m+"-"+fh.getDate();
      console.log(fa);
      for(let i = 0; i < this.contenido.length; i++){
        if(this.contenido[i].fvencimiento<fa){
          sum2 += (parseFloat(this.contenido[i].saldo));
          console.log("suma"+sum2);
        }
      }
      return sum2;
    },
    saldot(){
      this.saldo = parseFloat(this.monto) - parseFloat(this.abono); 
    }*/
  },
  methods: {
    /*handleSelected8(tr) {
      this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })
      this.totalSaldo=`${tr.saldo}`,
      this.idGasto=`${tr.id_ctas}`
    },*/
    format_date(value) {
      if (value) {
        return moment(String(value)).format("YYYY-MM-DD");
      }
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
      axios.delete("/api/eliminarpago/" + parameters);
      //this.modal=false;
      this.$vs.notify({
        color: "danger",
        title: "Pago Eliminadao",
        text: "El Pago selecionado fue eliminado con exito"
      });
      this.listar(1, this.buscar);
    },

    listar(page, buscar) {
      let me = this;
      var url = "/api/ctaxpagar?page=" + page + "&buscar=" + buscar;
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
    },
    getClientes: function() {
      var me = this;
      axios.get("/api/traerprovctaxpagar").then(
        function(response) {
          me.clientes = response.data;
          console.log("cliente:" + this.clientes);
        }.bind(this)
      );
    }
  },
  mounted() {
    this.listar(1, this.buscar);
  },
  created: function() {
    this.getClientes();
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
