<template>
  <vx-card title="Proceso de Producción" class="mt-10">
    <vs-divider>Información de Proceso de Producción</vs-divider>
    <vs-divider />
    <div class="vx-row sm:w-full w-full mt-5 tabproductos">
      <vs-tabs alignment="fixed">
        <!-- Orden de produccion-->
        <vs-tab class="vx-col" label="Orden de Producción" icon-pack="feather" icon="icon-layers">
          <vs-divider>Registro de Orden de Producción</vs-divider>
          <vs-divider position="left">Información de Orden</vs-divider>
          <div class="vx-row">
            <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
              <vs-input class="w-full" label="Número de Orden" v-model="num_orden" />
            </div>
            <div class="vx-col md:w-3/5 sm:w-full w-full mb-6">
              <vs-input class="w-full" label="Descripción" v-model="descrip_orden" />
            </div>
            <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
              <vs-input class="w-full" disabled label="Fecha de Inicio" v-model="f_ini_orden" />
            </div>
            <vs-divider position="left">Productos a Producir</vs-divider>
            <a
              class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
              @click="popupprod = true"
            >Añadir Productos</a>
            <div class="vx-col md:w-full sm:w-full w-full mb-6">
              <vs-table :data="continprod">
                <template slot="thead">
                  <vs-th>Código Producto</vs-th>
                  <vs-th class="text-center">Nombre Producto</vs-th>
                  <vs-th class="text-center">Fórmula de Producción</vs-th>
                  <vs-th class="text-center">Cantidad a Producir</vs-th>
                  <vs-th class="text-center"></vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td
                      style="width:10%!important;"
                      :data="data[indextr].cod_principal"
                    >{{ data[indextr].cod_principal }}</vs-td>

                    <vs-td
                      class="text-center"
                      style="width:30%!important;"
                      :data="data[indextr].nombre"
                    >{{ data[indextr].nombre }}</vs-td>
                    <vs-td
                      class="text-center"
                      style="width:30%!important;"
                      :data="data[indextr].form_prod"
                    >{{ data[indextr].form_prod }}</vs-td>
                    <vs-td class="text-center" style="width:20%!important;">
                      <vs-input
                        class="w-full txt-center"
                        @keypress="soloenteros($event)"
                        @keyup="recalculo(tr)"
                        v-model="tr.cant_prod"
                      />
                    </vs-td>

                    <vs-td class="text-center" style="width=10%!important;">
                      <vx-tooltip text="Eliminar" position="top" style="display: inline-flex;">
                        <feather-icon
                          icon="TrashIcon"
                          svgClasses="w-5 h-5 hover:text-danger stroke-current"
                          class="pointer"
                          @click="eliminarp(indextr)"
                        />
                      </vx-tooltip>
                    </vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>

            <!-- Inicio Agregar Ingredientes-->
            <vs-divider />
            <vs-divider position="left">Ingredientes Necesarios para Producción</vs-divider>
            <!-- INVOICE TASKS TABLE -->
            <div class="vx-col md:w-full sm:w-full w-full mb-6">
              <vs-table :data="contingred">
                <template slot="thead">
                  <vs-th>Cod Producto</vs-th>
                  <vs-th class="text-center">Nombre Ingrediente</vs-th>
                  <vs-th class="text-center">Bodega</vs-th>
                  <vs-th class="text-center">Stock Disponible</vs-th>
                  <vs-th class="text-center">Catidad Necesaria</vs-th>
                  <vs-th class="text-center">Saldo</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td
                      style="width:10%!important;"
                      :data="data[indextr].id_producto"
                    >{{ data[indextr].id_producto }}</vs-td>
                    <vs-td
                      class="text-center"
                      style="width:25%!important;"
                      :data="data[indextr].nombre"
                    >{{ data[indextr].nombre }}</vs-td>
                    <vs-td
                      class="text-center"
                      style="width:20%!important;"
                      :data="data[indextr].nombre_bodega"
                    >{{ data[indextr].nombre_bodega }}</vs-td>
                    <vs-td
                      class="text-center"
                      style="width:15%!important;"
                      :data="data[indextr].stock"
                    >{{ data[indextr].stock }}</vs-td>
                    <vs-td
                      class="text-center"
                      style="width:15%!important;"
                      :data="data[indextr].canti"
                      @change="calsaldo(tr)"
                    >
                      <vs-input disabled class="w-full txt-center" v-model="tr.canti" />
                    </vs-td>
                    <vs-td
                      class="text-center"
                      style="width:15%!important;"
                      :data="data[indextr].saldo"
                    >
                      <vs-input disabled class="w-full txt-center" v-model="tr.saldo" />
                    </vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
            <div class="vx-col w-full">
              <vs-button color="success" type="border">Guardar</vs-button>
              <vs-button color="danger" type="border" to="/produccion/proceso-produccion">Cancelar</vs-button>
            </div>
          </div>
        </vs-tab>
        <!-- Proceso-->
        <vs-tab class="vx-col" label="Proceso " icon-pack="feather" icon="icon-arrow-up"></vs-tab>
        <!-- Liquidación de Producción-->
        <vs-tab
          class="vx-col"
          label="Liquidación de Producción"
          icon-pack="feather"
          icon="icon-repeat"
        ></vs-tab>
      </vs-tabs>
      <!-- Popup Agregar Producto-->
      <vs-popup
        classContent="popup-example"
        title="Seleccione el Producto"
        :active.sync="popupprod"
      >
        <div class="vx-col w-full">
          <vs-input
            class="mb-4 mr-4 w-full"
            v-model="buscarp"
            @keyup="listarp(1,buscarp)"
            v-bind:placeholder="i18nbuscar"
          />
          <vs-table
            stripe
            v-model="contenidoprod"
            @selected="handleSelectedp"
            :data="arrayproductos"
          >
            <template slot="thead">
              <vs-th>Código</vs-th>
              <vs-th>Nombre</vs-th>
              <vs-th>Fórmula de Producción</vs-th>
              <vs-th>Marca</vs-th>
              <vs-th>Modelo</vs-th>
              <vs-th>Costo</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                <vs-td v-if="tr.cod_principal">{{tr.cod_principal}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.nombre">{{tr.nombre}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.form_prod">{{tr.form_prod}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.nombremarca">{{tr.nombremarca}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.nombremodelo">{{tr.nombremodelo}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.costo_total">{{tr.costo_total}}</vs-td>
                <vs-td v-else>-</vs-td>
              </vs-tr>
            </template>
          </vs-table>
        </div>
      </vs-popup>
    </div>
  </vx-card>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import VueUploadMultipleImage from "vue-upload-multiple-image";
import moment from "moment";
import { AgGridVue } from "ag-grid-vue";
import vSelect from "vue-select";

const axios = require("axios");
const $ = require("jquery");
export default {
  data() {
    return {
      //variables encabezado
      idrecupera: null,
      //variables de orden de produccion
      num_orden: "",
      descrip_orden: "",
      f_ini_orden: moment().format("YYYY-M-D"),
      cant_prod: 1,
      //popups
      popupprod: false,

      //arrays
      arrayproductos: [],
      contenidoprod: [],
      continprod: [],
      contingred: [],
      //buscar
      buscarp: "",
      i18nbuscar: this.$t("i18nbuscar"),
      //establece calendario español
      configdateTimePicker: {
        locale: SpanishLocale
      },
      idprodrec: null,
      contingred1: []
    };
  },
  //importa calendario español
  components: {
    flatPickr,
    VueUploadMultipleImage,
    AgGridVue,
    "v-select": vSelect
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    }
  },
  methods: {
    //Funciones Orden de Produccion
    listar() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/traerform/" + this.$route.params.id;
      axios.get(url).then(res => {
        this.cod_form_prod = res.data.codigo_produccion;
        this.nombre_form = res.data.nombre_form;
        this.nomproduct = res.data.nomproduct;
      });
    },
    listarp(pagep, buscarp) {
      var url =
        "/api/producformu/" +
        this.usuario.id_empresa +
        "?page=" +
        pagep +
        "&buscar=" +
        buscarp;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.arrayproductos = respuesta.recupera;
        //this.arrayprodprodu = respuesta.recupera;
      });
    },
    handleSelectedp(tr) {
      this.popupprod = false;
      this.continprod.push({
        id: tr.id_producto,
        cod_principal: tr.cod_principal,
        nombre: tr.nombre,
        id_form_prod: tr.id_form_prod,
        form_prod: tr.form_prod
      });
      this.idprodrec = tr.id_producto;
      this.listari();
    },
    eliminarp(id) {
      this.continprod.splice(id, 1);
    },
    //Funciones Proceso
    //funciones Liquidacion produccion
    //Validaciones
    soloenteros: function($event) {
      //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
      var num = /^\d+$/;
      if (
        $event.charCode === 0 ||
        num.test(String.fromCharCode($event.charCode))
      ) {
        return true;
      } else {
        $event.preventDefault();
      }
    },
    solonumeros: function($event) {
      //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
      var num = /^\d*\.?\d*$/;
      if (
        $event.charCode === 0 ||
        num.test(String.fromCharCode($event.charCode))
      ) {
        return true;
      } else {
        $event.preventDefault();
      }
    },
    ainicio() {
      $(".tabproductos")
        .find("li:nth-child(1)>button")
        .click();
      this.motivo_trans = "";
      this.receptor_trans = "";
    },
    listari() {
      axios
        .get(
          "/api/traerprocesingred?establecimiento=" +
            this.usuario.id_establecimiento +
            "&pr=" +
            this.idprodrec
        )
        .then(res => {
          if (this.contingred.length == 0) {
            this.contingred = res.data;
            console.log(this.contingred);
            for (let s in this.contingred) {
              var cant = parseFloat(this.contingred[s].canti);
              var stock = parseFloat(this.contingred[s].stock);
              this.contingred[s].saldo = (stock - cant).toFixed(2);
            }
            //console.log(this.contingred);
          } else {
            for (let s in this.contingred) {
              for (let i in res.data) {
                if (
                  this.contingred[s].id_producto == res.data[i].id_producto &&
                  this.contingred[s].nombre_bodega == res.data[i].nombre_bodega
                ) {
                  var uno = parseFloat(res.data[i].canti);
                  var dos = parseFloat(this.contingred[s].canti);
                  var tres = parseFloat(this.contingred[s].stock);
                  this.contingred[s].canti = (uno + dos).toFixed(2);
                  this.contingred[s].saldo = (tres - this.contingred[s].canti).toFixed(2);
                }
              }
            }
            res.data.forEach(el => {
              let recu = this.contingred.find(
                e => e.id_producto == el.id_producto
              );
              if (!recu) {
                this.contingred.push(el);
              }
            });
          }
        });
    },
    recalculo(tr) {
      for (let t in this.contingred) {
        if (this.contingred[t].id_formula_produccion == tr.id_form_prod) {
          var uno = parseFloat(this.contingred[t].cant_unit_prod);
          var dos = parseFloat(tr.cant_prod);
          if (!dos) {
            this.contingred[t].canti = 0;
          } else {
            this.contingred[t].canti = (uno * dos).toFixed(2);
          }
        }
      }
    },
    calsaldo(tr) {}
  },
  mounted() {
    this.listar();
    this.listarp(1, this.buscarp);
  }
};
</script>
<style lang="scss">
.txt-center > div > input {
  text-align: center;
}
.text-center > .vs-table-text {
  text-align: center !important;
  display: block;
}
.theme-dark .vx-card select {
  background: #262c49;
}
.vs-tabs--btn {
  display: block !important;
}
.vs-popup {
  width: 1060px !important;
}
</style>