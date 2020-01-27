<template>
  <div id="invoice-page">
    <vx-card>
      <!--encabezado del emisor empresa-->
      <!--<vs-divider position="left">
        <h3>Emisor</h3>
      </vs-divider>
      <div
        class="vx-row leading-loose p-base"
      >
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6>RAZÓN SOCIAL:</h6>
          {{ contenidoempresa.razon_social}}
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6>RUC:</h6>
          {{ contenidoempresa.ruc_empresa}}
        </div>

        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6>OBLIGADO CONTABILIDAD:</h6>
          <template v-if="parseInt(contenidoempresa.obligado_contabilidad)"> Si </template>
          <template v-else> No </template>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6>NOMBRE CONTRIBUYENTE:</h6>
          <p>{{ contenidoempresa.nombre_empresa }}</p>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6 class="mt-4">DIRECCIÓN ESTABLECIMIENTO:</h6>
          <p>{{ contenidoempresa.direccion_empresa }}</p>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6 class="mt-4">CONTRIBUYENTE ESPECIAL:</h6>
          <template v-if="contenidoempresa.contribuyente"> Si </template>
          <template v-else> No </template>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6 class="mt-4">DIRECCIÓN MATRIZ</h6>
          <p>{{ contenidoempresa.direccion_empresa }}</p>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6 class="mt-4">EMAIL ESTABLECIMIENTO</h6>
          <p>{{ contenidoempresa.email_empresa }}</p>
        </div>
      </div>-->
      <!--inicion encabezado de comprobante-->

      <vs-divider position="left">
        <h3>Proforma</h3>
      </vs-divider>
      <div class="vx-col sm:w-full w-full mb-3" v-if="num_prof">
        <h5 style="text-align:center">Número Proforma: {{num_prof}}</h5>
      </div>
      <div class="vx-row leading-loose p-base">
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Fecha Emisión:</h6>
          <flat-pickr
            :config="configdateTimePicker"
            class="w-full mt-3"
            v-model="date"
            placeholder="Seleccionar"
          />
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Fecha Expiración:</h6>
          <flat-pickr
            :config="configdateTimePicker"
            class="w-full mt-3"
            v-model="datex"
            placeholder="Seleccionar Fecha"
          />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Formas de Pago:</h6>
          <vs-select autocomplete class="selectExample w-full mt-3" v-model="forma_pago">
            <vs-select-item
              :key="index"
              :value="item.value"
              :text="item.text"
              v-for="(item,index) in forma_pago_array"
            />
          </vs-select>
        </div>
      </div>
      <!--Fin encabezado de comprobante-->
      <!--inicion encabezado de Cliente-->
      <vs-divider position="left">
        <h3>Cliente</h3>
      </vs-divider>
      <!--fin modal-->
      <div class="vx-row leading-loose p-base">
        <div class="vx-col w-full mb-4">
          <div class="dropdown-button-container">
            <vs-dropdown>
              <a class="flex items-center">
                Añadir Cliente
                <i class="material-icons">expand_more</i>
              </a>
              <vs-dropdown-menu>
                <vs-dropdown-item
                  class="text-center"
                  divider
                  @click="popupActive2=true,tipomodal=1"
                >Buscar Cliente</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="crear()">Crear Cliente</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Nombre:</h6>
          <vs-input disabled class="w-full" v-bind:value="nombre" />
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Teléfono:</h6>
          <vs-input disabled class="w-full" v-bind:value="telefono" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Email:</h6>
          <vs-input disabled class="w-full" v-bind:value="email" />
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Tipo de Identificación:</h6>
          <vs-select disabled class="selectExample w-full nover" v-bind:value="tipo_identificacion">
            <vs-select-item
              :key="index"
              :value="item.value"
              :text="item.text"
              v-for="(item,index) in tipo_identificacion_menu"
            />
          </vs-select>
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Identificación:</h6>
          <vs-input disabled class="w-full" v-bind:value="ruc_ci" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Dirección:</h6>
          <vs-input disabled class="w-full" v-bind:value="direccion" />
        </div>
      </div>
      <!--dividir-->
      <!--Fin encabezado de Cliente-->
      <!--INICIO DE TABLA DE PRODUCTOS -->
      <vs-divider position="left">
        <h3>Productos</h3>
      </vs-divider>
      <div class="p-base">
        <a class="flex items-center cursor-pointer mb-4" @click="abrirproductos()">Añadir Productos</a>
        <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;">
          <template slot="thead">
            <vs-th>CÓDIGO</vs-th>
            <vs-th>NOMBRE</vs-th>
            <vs-th>CANTIDAD</vs-th>
            <vs-th>PRECIO</vs-th>
            <vs-th>DESCUENTO</vs-th>
            <!--<vs-th>IVA</vs-th>-->
            <vs-th>SUBTOTAL</vs-th>
          </template>
          <template slot-scope="{data}">
            <vs-tr v-for="(tr, index) in data" :key="index">
              <vs-td :data="tr.codigo">{{ tr.codigo }}</vs-td>
              <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
              <vs-td :data="tr.cantidad" style="width:150px!important;">
                <vs-input class="w-full" placeholder="$0.00" v-model="tr.cantidad" />
              </vs-td>
              <vs-td :data="tr.precio" style="width:150px!important;">
                <vs-input class="w-full" placeholder="$0.00" v-model="tr.precio" />
              </vs-td>
              <vs-td :data="tr.descuento" style="width:200px!important;">
                <vx-input-group>
                  <vs-input class="w-full" placeholder="$0.00" v-model="tr.descuento" />
                  <template slot="append">
                    <div class="append-text btn-addon">
                      <vs-button
                        color="primary"
                        type="border"
                        style="padding: 15px!important;height: 37px;line-height: 7px;"
                      >$</vs-button>
                      <vs-button
                        color="primary"
                        type="border"
                        style="padding: 15px!important;height: 37px;line-height: 7px;"
                      >%</vs-button>
                    </div>
                  </template>
                </vx-input-group>
              </vs-td>
              <!--<vs-td :data="tr.iva" style="width:200px!important;">
                <vs-select class="selectExample w-full" vs-multiple v-model="tr.iva">
                  <vs-select-item :key="res.id_iva" :value="res.id_iva" :text="res.nombre" v-for="res in contenidoiva"/>
                </vs-select>
              </vs-td>-->
              <vs-td>{{ ((tr.cantidad*tr.precio)-tr.descuento) | currency }}</vs-td>
              <vs-td>
                <feather-icon
                  icon="TrashIcon"
                  svgClasses="w-5 h-5 hover:text-danger stroke-current"
                  class="ml-2 cursor-pointer"
                  @click="borrarprd(index)"
                />
              </vs-td>
            </vs-tr>
          </template>
        </vs-table>
        <div class="vx-row mt-6">
          <div class="vx-col sm:w-1/2 w-full">
            <h6>Observaciones:</h6>
            <vs-textarea class="w-full" v-model="observacion" rows="2" />
          </div>
          <div class="vx-col sm:w-1/2 w-full">
            <vs-table hoverFlat class="w-full" :data="invoiceData">
              <vs-tr>
                <vs-th>SUBTOTAL SIN IMPUESTO</vs-th>
                <vs-td>{{ subtotalpr | currency }}</vs-td>
              </vs-tr>

              <vs-tr v-if="subtotalivapr12>0">
                <vs-th>SUBTOTAL IVA 12%</vs-th>
                <vs-td>{{ subtotalivapr12 | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="ivapr12>0">
                <vs-th>Valor IVA 12%</vs-th>
                <vs-td>{{ ivapr12 | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="subtotalivapr14>0">
                <vs-th>SUBTOTAL IVA 14%</vs-th>
                <vs-td>{{ subtotalivapr14 | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="ivapr14>0">
                <vs-th>Valor IVA 14%</vs-th>
                <vs-td>{{ ivapr14 | currency }}</vs-td>
              </vs-tr>

              <vs-tr v-if="subtotalivapr0>0">
                <vs-th>SUBTOTAL IVA 0%</vs-th>
                <vs-td>{{ subtotalivapr0 | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="ivapr0>0">
                <vs-th>Valor IVA 0%</vs-th>
                <vs-td>{{ ivapr0 | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="subtotalivaprno>0">
                <vs-th>NO OBJETO DE IMPUESTO</vs-th>
                <vs-td>{{ subtotalivaprno | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="ivaprno>0">
                <vs-th>VALOR NO OBJETO DE IMPUESTO</vs-th>
                <vs-td>{{ ivaprno | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="subtotalivaprex>0">
                <vs-th>EXENTO DE IVA</vs-th>
                <vs-td>{{ subtotalivaprex | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="ivaprex>0">
                <vs-th>VALOR EXENTO DE IVA</vs-th>
                <vs-td>{{ ivaprex | currency }}</vs-td>
              </vs-tr>

              <vs-tr>
                <vs-th>TOTAL DESCUENTO</vs-th>
                <vs-td>{{ descuentopr | currency }}</vs-td>
              </vs-tr>
              <vs-tr>
                <vs-th>PROPINA</vs-th>
                <vs-td>
                  <vx-input-group>
                    <vs-input class="w-full" placeholder="$0.00" v-model="propinapr" />
                    <template slot="append">
                      <div class="append-text btn-addon">
                        <vs-button
                          color="primary"
                          type="border"
                          class="is-active"
                          style="padding: 15px!important;height: 37px;line-height: 7px;"
                        >$</vs-button>
                        <vs-button
                          color="primary"
                          type="border"
                          style="padding: 15px!important;height: 37px;line-height: 7px;"
                        >%</vs-button>
                      </div>
                    </template>
                  </vx-input-group>
                </vs-td>
              </vs-tr>
              <vs-tr>
                <vs-th>VALOR TOTAL</vs-th>
                <vs-td>{{ totalpr | currency }}</vs-td>
              </vs-tr>
            </vs-table>
          </div>
        </div>
        <!--dividir-->
      </div>

      <div class="vx-col w-full">
        <vs-button color="success" type="filled" @click="guardar()" v-if="!$route.params.id">Guardar</vs-button>
        <vs-button color="success" type="filled" @click="editar()" v-else>Guardar</vs-button>
        <vs-button color="danger" type="filled" to="/facturacion/proforma">Cancelar</vs-button>
      </div>
    </vx-card>
    <!--MODAL LISTAR CLIENTE aqui -->
    <div class="demo-alignment">
      <vs-popup classContent="popup-example" title="Seleccione Cliente" :active.sync="popupActive2">
        <div class="vx-row">
          <div class="vx-col w-full" v-if="tipomodal==1">
            <vs-input
              class="mb-4 mr-4 w-full"
              v-model="buscar"
              @keyup.enter="listar(1,buscar)"
              v-bind:placeholder="i18nbuscar"
            />
            <vs-table stripe v-model="cuentaarray" @selected="handleSelected" :data="contenido">
              <template slot="thead">
                <vs-th>Nombre</vs-th>
                <vs-th>Identificación</vs-th>
                <vs-th>Dirección</vs-th>
                <vs-th>Email</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].nombre">{{ data[indextr].nombre }}</vs-td>
                  <vs-td :data="data[indextr].identificacion">{{ data[indextr].identificacion }}</vs-td>
                  <vs-td :data="data[indextr].direccion">{{ data[indextr].direccion }}</vs-td>
                  <vs-td :data="data[indextr].email">{{ data[indextr].email }}</vs-td>
                </vs-tr>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="datos" :key="index" v-for="(datos,index) in data">
                  <vs-td>{{datos.nombre}}</vs-td>
                  <vs-td v-if="datos.identificacion">{{datos.identificacion}}</vs-td>
                  <vs-td v-if="datos.direccion">{{datos.direccion}}</vs-td>
                  <vs-td v-if="datos.email">{{datos.email}}</vs-td>
                  <vs-td v-else>-</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
          <div class="vx-col w-full" v-else>
            <vs-input
              class="mb-4 mr-4 w-full"
              v-model="buscarp"
              @keyup.enter="listarp(1,buscarp)"
              v-bind:placeholder="i18nbuscar"
            />
            <vs-table stripe v-model="cuentaarrayp" @selected="handleSelectedp" :data="contenidop">
              <template slot="thead">
                <vs-th>Código</vs-th>
                <vs-th>Nombre</vs-th>
                <vs-th>Descripcion</vs-th>
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
                  <vs-td v-if="tr.descripcion">{{tr.descripcion}}</vs-td>
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
        </div>
      </vs-popup>
    </div>
  </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
const axios = require("axios");
export default {
  data() {
    return {
      tipomodal: 0,
      cuentaarray: [],
      cuentaarrayp: [],
      identificacion: "",
      contenidoempresa: [],
      //cliente
      nombre: "",
      telefono: "",
      email: "",
      ruc_ci: "",
      direccion: "",
      observacion: "",
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
      paginationp: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      paginap: 1,
      cantidadpp: 10,
      offsetp: 3,
      paginationpr: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      paginapr: 1,
      cantidadppr: 10,
      offsetpr: 3,
      //buscador
      criterio: "nombre",
      //otros valores
      gridApi: null,
      buscar: "",
      buscarp: "",
      buscarpr: "",
      i18nbuscar: this.$t("i18nbuscar"),
      contenido: [],
      contenidop: [],
      contenidopr: [],
      value1: "",
      value2: "",
      popupActive2: false,
      popupActive3: false,
      forma_pago: "",
      forma_pago_array: "",
      tipo_identificacion: "",
      tipo_identificacion_array: "",
      num_prof: "",
      date: moment().format("YYYY-M-D"),
      datex: null,
      configdateTimePicker: {
        locale: SpanishLocale
      },
      tipo_identificacion_menu: [
        { text: "Seleccione", value: 0 },
        { text: "Cédula de Identidad", value: 1 },
        { text: "Ruc", value: 2 },
        { text: "Pasaporte", value: 3 },
        { text: "Consumidor Final", value: 4 }
      ],
      forma_pago_array: [
        { text: "Seleccione", value: 0 },
        { text: "Efectivo", value: "Efectivo" },
        { text: "Cheque", value: "Cheque" },
        { text: "Tarjeta", value: "Tarjeta" },
        { text: "Crédito", value: "Crédito" }
      ],
      companyDetails: {
        name: "Microsion Technologies Pvt. Ltd.",
        addressLine1: "9 N. Sherwood Court",
        addressLine2: "Elyria, OH",
        zipcode: "94203",
        mailId: "hello@pixinvent.net",
        mobile: "+91 999 999 9999"
      },
      recipientDetails: {
        fullName: "Peter Stark",
        addressLine1: "8577 West West Drive ",
        addressLine2: "Holbrook, NY",
        zipcode: "90001",
        mailId: "peter@mail.com",
        mobile: "+91 988 888 8888"
      },
      invoiceDetails: {
        invoiceNo: "001/2019",
        invoiceDate: "Mon Dec 10 2018 07:46:00 GMT+0000 (GMT)"
      },
      invoiceData: {
        tasks: [
          {
            id: 1,
            task: "Website Redesign",
            hours: 60,
            rate: 15,
            amount: 90000,
            iva: 15,
            ice: 15
          }
        ],
        subtotal: 114000,
        discountPercentage: 5,
        discountedAmount: 5700,
        total: 108300
      },
      id_cliente: null,
      contenidoiva: [],
      contenidoice: [],
      total: null,
      propinapr: null,
      idrecupera: null,
      recupera: null,
      contenidoc: []

      /*metodo_pago:null, 
      cantidad_pago:null, 
      comentario_compra:null,*/
    };
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    totalpagado() {
      var valor = 0;
      for (var i = 0; i < this.valorpagos.length; i++) {
        if (this.valorpagos[i].cantidad_pago > 0) {
          valor = valor + parseFloat(this.valorpagos[i].cantidad_pago);
        }
      }
      if (this.retencioniva <= 0) {
        var retiva = 0;
      } else {
        var retiva = this.retencioniva;
      }
      if (this.retencionrenta <= 0) {
        var retrenta = 0;
      } else {
        var retrenta = this.retencionrenta;
      }
      var total = parseFloat(retiva) + parseFloat(retrenta) + parseFloat(valor);
      return total;
    },
    subtotalpr() {
      var total = 0;
      this.contenidopr.forEach(el => {
        total += el.precio * el.cantidad - el.descuento;
      });
      return total;
    },
    descuentopr() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.descuento > 0) {
          total += parseFloat(el.descuento);
        }
      });
      return total;
    },
    subtotalivapr12() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 2) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    ivapr12() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 2) {
          total += (el.precio * el.cantidad - el.descuento) * 0.12;
        }
      });
      return total;
    },
    subtotalivapr14() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 5) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    ivapr14() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 5) {
          total += (el.precio * el.cantidad - el.descuento) * 0.14;
        }
      });
      return total;
    },
    subtotalivapr0() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 1) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    subtotalivaprno() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 3) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    subtotalivaprex() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 4) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    ivapr0() {
      var total = 0;
      return total;
    },
    ivaprno() {
      var total = 0;
      return total;
    },
    ivaprex() {
      var total = 0;
      return total;
    },
    totalpr() {
      var total = 0;
      var propina = 0;
      if (this.propinapr > 0) {
        propina = this.propinapr;
      }
      this.contenidopr.forEach(el => {
        total += el.precio * el.cantidad - el.descuento;
      });

      var dato = total + parseFloat(propina) + this.ivapr12 + this.ivapr14;

      return dato;
    }
  },
  methods: {
    listproforma() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        this.num_prof = this.$route.params.id;
        var url = "/api/abrirproforma";
        axios
          .put(url, { id: this.idrecupera })
          .then(res => {
            console.log(res.data);
            let data = res.data[0];
            this.date = data.fecha_emision;
            this.datex = data.fecha_expiracion;
            this.forma_pago = data.forma_pago;
            this.observacion = data.observacion;
            this.contenidopr.subtotalpr = data.subtotal_sin_impuesto;
            this.contenidopr.subtotalivapr12 = data.subtotal_12;
            this.valor_ice = data.valor_ice;
            this.valor_irbpnr = data.valor_irbpnr;
            this.propinapr = data.propina;
          })
          .catch(err => {
            console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
    },
    listarc() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/abrirproformac/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          //this.contenidopr = res.data;
          this.contenido = res.data[0];
          this.id_cliente = this.contenido.id_cliente;
          this.nombre = this.contenido.nombre;
          this.telefono = this.contenido.telefono;
          this.email = this.contenido.email;
          this.tipo_identificacion = this.contenido.tipo_identificacion;
          this.ruc_ci = this.contenido.identificacion;
          this.direccion = this.contenido.direccion;
        })
        .catch(err => {
          console.log(err);
        });
    },
    listarpr() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        var url = "/api/abrirproformap/" + this.idrecupera;
        axios
          .get(url)
          .then(res => {
            this.contenidopr = res.data;
          })
          .catch(err => {
            console.log(err);
          });
      }
    },
    listar(page, buscar) {
      var url =
        "/api/clientes/" +
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
    /*traerEmpresa() {
      if(this.usuario.id_empleado>=1){
        var url = "/api/traerEmpleado/"+this.usuario.id_empleado;
        axios.get(url).then(res => {
          this.contenidoempresa = res.data;
        }).catch( err => {
          console.log(error);
        });
      }else{
        var url = "/api/traerEmpresa/"+this.usuario.id_empresa;
        axios.get(url).then(res => {
          this.contenidoempresa = res.data[0];
        }).catch( err => {
          console.log(error);
        });
      }
    },*/
    crear() {
      this.$router.push("/facturacion/cliente/agregar");
    },
    abrirproductos() {
      this.popupActive2 = true;
      this.tipomodal = 2;
      this.listarp(1, this.buscarp, this.cantidadpp);
    },
    listarp(pagep, buscarp) {
      var url =
        "/api/productos/" +
        this.usuario.id_empresa +
        "?page=" +
        pagep +
        "&buscar=" +
        buscarp;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidop = respuesta.recupera;
      });
    },
    handleSelected(tr) {
      this.popupActive2 = false;
      this.id_cliente = tr.id_cliente;
      this.nombre = tr.nombre;
      this.telefono = tr.telefono;
      this.email = tr.email;
      this.tipo_identificacion = tr.tipo_identificacion;
      this.ruc_ci = tr.identificacion;
      this.direccion = tr.direccion;
    },
    handleSelectedp(tr) {
      this.popupActive2 = false;
      console.log(tr);
      this.contenidopr.push({
        id_producto: tr.id_producto,
        id_detalle: null,
        nombre: tr.nombre,
        codigo: tr.cod_principal,
        cantidad: null,
        precio: tr.pvp_precio1,
        descripcion: tr.descripcion,
        descuento: tr.descuento,
        iva: tr.iva,
        ice: tr.ice
      });
      /*this.id_cliente = tr.id_cliente;
      this.nombre = tr.nombre;
      this.telefono = tr.telefono;
      this.email = tr.email;
      this.tipo_identificacion = tr.tipo_identificacion;
      this.ruc_ci = tr.identificacion;
      this.direccion = tr.direccion;*/
    },
    listariva() {
      let me = this;
      var url = "/api/iva";
      axios
        .get(url)
        .then(function(response) {
          me.contenidoiva = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarice() {
      let me = this;
      var url = "/api/ice";
      axios
        .get(url)
        .then(function(response) {
          me.contenidoice = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    zeroFill(number, width) {
      width -= number.toString().length;
      if (width > 0) {
        return (
          new Array(width + (/\./.test(number) ? 2 : 1)).join("0") + number
        );
      }
      return number + "";
    },
    borrarprd(id) {
      this.contenidopr.splice(id, 1);
    },
    guardar() {
      axios
        .post("/api/crearproforma", {
          //factura
          ambiente: 1,
          tipo_emision: 1,
          fecha_emision: this.date,
          fecha_expiracion: this.datex,
          forma_pago: this.forma_pago,
          observacion: this.observacion,
          id_user: this.usuario.id,
          id_punto_emision: this.usuario.id_punto_emision,
          id_empresa: this.usuario.id_empresa,
          id_establecimiento: this.usuario.id_establecimiento,
          //VALORES
          subtotal_sin_impuesto: this.subtotalpr,
          subtotal_12: this.subtotalivapr12,
          subtotal_0: this.subtotalivapr0,
          subtotal_no_obj_iva: this.subtotalivaprno,
          descuento: this.descuentopr,
          valor_ice: 0,
          valor_irbpnr: 0,
          iva_12: this.ivapr12,
          propina: this.propinapr,
          valor_total: this.totalpr,
          //cliente
          id_cliente: this.id_cliente,
          //productos
          productos: this.contenidopr
        })
        .then(res => {
          this.$vs.notify({
            title: "Proforma Guardada",
            text: "Proforma agregada con éxito",
            color: "success"
          });
          this.$router.push("/facturacion/proforma");
        });
    },
    editar() {
      axios
        .put("/api/editarproforma", {
          id: this.$route.params.id,
          //factura
          ambiente: 1,
          tipo_emision: 1,
          fecha_emision: this.date,
          fecha_expiracion: this.datex,
          forma_pago: this.forma_pago,
          observacion: this.observacion,
          id_user: this.usuario.id,
          id_punto_emision: this.usuario.id_punto_emision,
          id_empresa: this.usuario.id_empresa,
          id_establecimiento: this.usuario.id_establecimiento,
          //VALORES
          subtotal_sin_impuesto: this.subtotalpr,
          subtotal_12: this.subtotalivapr12,
          subtotal_0: this.subtotalivapr0,
          subtotal_no_obj_iva: this.subtotalivaprno,
          descuento: this.descuentopr,
          valor_ice: 0,
          valor_irbpnr: 0,
          iva_12: this.ivapr12,
          propina: this.propinapr,
          valor_total: this.totalpr,
          //cliente
          id_cliente: this.id_cliente,
          //productos
          productos: this.contenidopr
        })
        .then(res => {
          this.$vs.notify({
            title: "Proforma Editada",
            text: "Proforma editada con éxito",
            color: "success"
          });
          this.$router.push("/facturacion/proforma");
        });
    }
  },
  mounted() {
    this.listproforma();
    this.listarc();
    this.listarpr();
    this.listar(1, this.buscar, this.cantidadp);
    this.listarp(1, this.buscarp, this.cantidadpp);
    this.listariva();
    this.listarice();
  },
  components: {
    flatPickr,
    "v-select": vSelect
  }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.vs-popup {
  width: 1060px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
.sindis .vs-input--input:focus {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.sindis .vs-input--input {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.nover > .icon-select {
  display: none;
}
.hovertrash:hover > .trasher {
  display: block !important;
}
</style>
