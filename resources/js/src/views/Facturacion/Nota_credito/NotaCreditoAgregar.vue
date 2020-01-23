<template>
  <div id="invoice-page">
    <vx-card>
      <!-- INVOICE METADATA -->
    
      <h1>Nota de Crédito</h1> 
     

      <!--encabezado del emisor empresa-->
       <vs-divider position="right">
         <vs-button color="danger" type="border" to="/facturacion/nota-credito" v-if="1"  @click="cancelar()">CANCELAR</vs-button>
      </vs-divider>
     
      <vs-divider position="left">
        <h3>Emisor</h3>
      </vs-divider>
      <div
        v-for="traerEmpresa in contenidoempresa[0]"
        :key="traerEmpresa.id_empresa"
        class="vx-row leading-loose p-base"
      >
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6>RAZÓN SOCIAL:</h6>
          {{ traerEmpresa.id_empresa}}
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6>RUC:</h6>
          {{ traerEmpresa.ruc_empresa}}
        </div>

        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6>OBLIGADO CONTABILIDAD:</h6>
        </div>

        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6>NOMBRE CONTRIBUYENTE:</h6>
          <p>{{ traerEmpresa.nombre_empresa }}</p>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6 class="mt-4">DIRECCIÓN ESTABLECIMIENTO:</h6>
          <p>{{ traerEmpresa.direccion_empresa }}</p>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6 class="mt-4">CONTRIBUYENTE ESPECIAL:</h6>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6 class="mt-4">DIRECCIÓN MATRIZ</h6>
          <p>{{ traerEmpresa.direccion_empresa }}</p>
        </div>
      </div>
      <vs-divider />
      <!--Fin encabezado del emisor empresa-->

      <!--inicion encabezado de comprobante-->
      <vs-divider position="left">
        <h3>Comprobante</h3>
      </vs-divider>
      <br />
      <div class="vx-row leading-loose p-base">
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Ambiente:</h6>
          {{ invoiceDetails.invoiceNo }}
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Tipo Emisión:</h6>
          {{ invoiceDetails.invoiceNo }}
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Fecha Emisión:</h6>
          <flat-pickr :config="configdateTimePicker" v-model="date" placeholder="Seleccionar" />
        </div>

        <div class="vx-col sm:w-1/2 w-full mb-2">
          <h6 class="mt-4">Clave de acceso:</h6>
          <p>{{ invoiceDetails.invoiceNo }}</p>
        </div>
      </div>
      <vs-divider />
      <!--Fin encabezado de comprobante-->

      <!--INICIO Comprobante Modificado -->
      <vs-divider position="left">
        <h3>Comprobante Modificado</h3>
      </vs-divider>

      <div class="vx-row leading-loose p-base">
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Tipo Documento:</h6>
          <vs-input class="w-full" v-model="tipo_documento" />
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Fecha Emisión:</h6>
          <flat-pickr :config="configdateTimePicker" v-model="date" placeholder="Seleccionar" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>N° Comprobante:</h6>
          <vs-input class="w-full" v-model="nro_comprobante" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Motivo:</h6>
          <vs-input class="w-full" v-model="motivo" />
        </div>
      </div>
      <vs-divider />

      <!--inicion encabezado de Cliente-->
      <vs-divider position="left">
        <h3>Cliente</h3>
      </vs-divider>

     
      <div class="vx-row leading-loose p-base">
        <div class="vx-col sm:w-1/2 w-full mb-2">
         <!--MODAL LISTAR CLIENTE aqui -->


      <div class="demo-alignment">
        <vs-button class="mb-4 md:mb-0 mr-4" @click="popupActive2=true" color="primary" type="filled">BUSCAR</vs-button>

        <vs-popup
          classContent="popup-example"
          title="Seleccione Cliente"
          :active.sync="popupActive2"
        >
          <vs-input
            class="mb-4 md:mb-0 mr-4 w-full"
            v-model="buscar"
            @keyup.enter="listar(1,buscar,criterio,cantidadp)"
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
          <vs-pagination
            :total="pagination.count"
            :max="7"
            v-model="pagina"
            @change="listar(pagina,buscar,criterio,cantidadp)"
            prev-icon="arrow_back"
            next-icon="arrow_forward"
          ></vs-pagination>
        </vs-popup>
      </div>
       </div>

      <div class="vx-col sm:w-1/2 w-full mb-2">
        <vs-button
          color="primary"
          type="filled"
          class="mb-4 md:mb-0 mr-4"
          @click="crear()"
        >Crear Cliente</vs-button>
      </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Nombre:</h6>
          <vs-input class="w-full" v-model="nombre" />
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Teléfono:</h6>
          <vs-input class="w-full" v-model="telefono" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Email:</h6>
          <vs-input class="w-full" v-model="email" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Tipo de Identificación:</h6>
          <vs-select class="selectExample w-full" v-model="tipo_identificacion">
            <vs-select-item
              :key="index"
              :value="item.value"
              :text="item.text"
              v-for="(item,index) in tipo_identificacion_array"
            />
          </vs-select>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Identificación:</h6>
          <vs-input class="w-full" v-model="ruc_ci" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Dirección:</h6>
          <vs-input class="w-full" v-model="direccion" />
        </div>
      </div>
      <vs-divider />
      <!--dividir-->
      <!--Fin encabezado de Cliente-->

      <!--INICIO DE TABLA DE PRODUCTOS -->
      <vs-divider position="left">
        <h3>Productos</h3>
      </vs-divider>
      <!-- INVOICE CONTENT  -->
      <div class="p-base">
        <!-- INVOICE TASKS TABLE -->
        <vs-table hoverFlat :data="invoiceData.tasks">
          <!-- HEADER -->
          <template slot="thead">
            <vs-th>DESCRIPCIÓN</vs-th>
            <vs-th>CANTIDAD</vs-th>
            <vs-th>PRECIO</vs-th>
            <vs-th>DESCUENTO</vs-th>
            <vs-th>TOTAL</vs-th>
            <vs-th>ICE</vs-th>
            <vs-th>IRBPNR</vs-th>
          </template>

          <!-- DATA -->
          <template slot-scope="{data}">
            <vs-tr v-for="(tr, index) in data" :key="index">
              <vs-td :data="data[index].task">{{ data[index].task }}</vs-td>
              <vs-td :data="data[index].hours">{{ data[index].hours }}</vs-td>
              <vs-td :data="data[index].rate">{{ data[index].rate }} USD</vs-td>
              <vs-td :data="data[index].rate">{{ data[index].rate }} USD</vs-td>
              <vs-td :data="data[index].amount">{{ data[index].amount }} USD</vs-td>
            </vs-tr>
          </template>
        </vs-table>

        <!-- INVOICE SUMMARY TABLE -->
        <vs-table hoverFlat class="w-1/2 ml-auto mt-4" :data="invoiceData">
          <vs-tr>
            <vs-th>SUBTOTAL SIN IMPUESTO</vs-th>
            <vs-td>{{ invoiceData.subtotal }} USD</vs-td>
          </vs-tr>
          <vs-tr>
            <vs-th>SUBTOTAL 12%</vs-th>
            <vs-td>{{ invoiceData.discountedAmount }} USD</vs-td>
          </vs-tr>
          <vs-tr>
            <vs-th>SUBTOTAL 0%</vs-th>
            <vs-td>{{ invoiceData.discountedAmount }} USD</vs-td>
          </vs-tr>
          <vs-tr>
            <vs-th>SUBTOTAL NO OBJETO IVA</vs-th>
            <vs-td>{{ invoiceData.discountedAmount }} USD</vs-td>
          </vs-tr>
          <vs-tr>
            <vs-th>DESCUENTO ({{ invoiceData.discountPercentage }}%)</vs-th>
            <vs-td>{{ invoiceData.discountedAmount }} USD</vs-td>
          </vs-tr>
          <vs-tr>
            <vs-th>VALOR ICE</vs-th>
            <vs-td>{{ invoiceData.discountedAmount }} USD</vs-td>
          </vs-tr>
          <vs-tr>
            <vs-th>VALOR IRBPNR</vs-th>
            <vs-td>{{ invoiceData.discountedAmount }} USD</vs-td>
          </vs-tr>
          <vs-tr>
            <vs-th>IVA 12%</vs-th>
            <vs-td>{{ invoiceData.discountedAmount }} USD</vs-td>
          </vs-tr>
          <vs-tr>
            <vs-th>Propina</vs-th>
            <vs-td>{{ invoiceData.discountedAmount }} USD</vs-td>
          </vs-tr>
          <vs-tr>
            <th>VALOR TOTAL</th>
            <td>{{ invoiceData.total }} USD</td>
          </vs-tr>
        </vs-table>
        <vs-divider />
        <!--dividir-->
      </div>
    </vx-card>

    <!--modales-->
  </div>
</template>



        <script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";

import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");

//comentario comentario
export default {
  data() {
    return {
      cuentaarray: [],
      identificacion: "",
      contenidoempresa: [],
      //cliente
      nombre: "",
      telefono: "",
      email: "",
      ruc_ci: "",
      direccion: "",
      //comproban modificado
      tipo_documento: "",
      nro_comprobante: "",
      motivo: "",
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
      criterio: "nombre",
      //otros valores

      gridApi: null,

      buscar: "",
      i18nbuscar: this.$t("i18nbuscar"),

      contenido: [],
      value1: "",
      value2: "",
      popupActive2: false,
      popupActive3: false,

      tipo_identificacion: "",
      tipo_identificacion_array: "",
      date: null,
      configdateTimePicker: {
        locale: SpanishLocale
      },
      tipo_identificacion_array: [
        { text: "Seleccione", value: 0 },
        { text: "Cédula de Identidad", value: "Cédula de Identidad" },
        { text: "Ruc", value: "Ruc" },
        { text: "Pasaporte", value: "Pasaporte" },
        { text: "Consumidor Final", value: "Consumidor Final" }
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
            amount: 90000
          },
          {
            id: 2,
            task: "Newsletter template design",
            hours: 20,
            rate: 12,
            amount: 24000
          }
        ],
        subtotal: 114000,
        discountPercentage: 5,
        discountedAmount: 5700,
        total: 108300
      }
    };
  },
  computed: {},
  methods: {
    listar(page, buscar, criterio, cantidadp) {
      let me = this;
      var url =
        "/api/clientes?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio +
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
    traerEmpresa() {
      let me = this;
      var url = "/api/traerEmpresa";
      let f = [me.contenidoempresa];
      axios
        .get(url)
        .then(function(response) {
          me.contenidoempresa = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    crear() {
      this.$router.push("/facturacion/cliente/agregar");
    },
     cancelar() {
      this.$router.push("/facturacion/nota-credito");
    },
    handleSelected(tr) {
      (this.nombreResultado = `${tr.nombre}`),
        (this.identificacionResultado = `${tr.identificacion}`);
    }
  },
  mounted() {
    this.listar(1, this.buscar, this.criterio, this.cantidadp);
    this.traerEmpresa();
  },
  components: {
    flatPickr
  }
};
</script>