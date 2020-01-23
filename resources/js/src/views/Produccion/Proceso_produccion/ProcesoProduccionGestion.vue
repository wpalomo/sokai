<template>
  <vx-card title="Gestión Proceso de Producción" class="mt-10">
    <vs-divider>Información de Fórmula de Producción</vs-divider>
    <div class="vx-row">
      <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
        <h5 style="text-align: center;">Código de Fórmula de Producción:</h5>
        <p style="text-align: center;">{{cod_form_prod}}</p>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
        <h5 style="text-align: center;">Nombre de Fórmula de Producción:</h5>
        <p style="text-align: center;">{{nombre_form}}</p>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
        <h5 style="text-align: center;">Nombre del Producto a Producir:</h5>
        <p style="text-align: center;"> {{nomproduct}}</p> 
      </div>

      <vs-divider />
    </div>
    <div class="vx-row sm:w-full w-full mt-5 tabproductos">
      <vs-tabs alignment="fixed">
        <!-- Visualiziacion de productos-->
        <vs-tab class="vx-col" label="Proceso 1" icon-pack="feather" icon="icon-layers"></vs-tab>
        <!-- Egreso de bodega-->
        <vs-tab class="vx-col" label="Proceso2 " icon-pack="feather" icon="icon-arrow-up"></vs-tab>
        <!-- Tranferencias-->
        <vs-tab class="vx-col" label="Proceso 3" icon-pack="feather" icon="icon-repeat"></vs-tab>
      </vs-tabs>
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
      cod_form_prod: "",
      nombre_form: "",
      nomproduct: "",

      //establece calendario español
      configdateTimePicker: {
        locale: SpanishLocale
      }
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
    listar() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/traerform/" + this.$route.params.id;
      axios.get(url).then(res => {
        this.cod_form_prod = res.data.codigo_produccion;
        this.nombre_form = res.data.nombre_form;
        this.nomproduct = res.data.nomproduct;
      });
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
    }
  },
  mounted() {
    this.listar();
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
</style>