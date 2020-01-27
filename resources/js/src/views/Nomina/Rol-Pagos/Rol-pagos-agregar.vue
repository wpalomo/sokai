<template>
  <vx-card title="Agregar Empleado">
    <div class="vx-row"></div>
    <vs-tabs alignment="fixed" v-model="Tabnav">
      <vs-tab title="BasicInfo" label="Ingresos" icon-pack="feather" icon="icon-book-open">
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Departamento</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="departamento" :value="iddepart" disabled />

              <template slot="append">
                <div class="append-text btn-addon">
                  <!-- -->
                  <vs-button color="primary" @click="abrirproductos()">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <!--===============================DEPARTAMENTO POPUP============================================-->
          <vs-popup title="Departamento" class="depa" :active.sync="activePrompt4">
            <div class="con-exemple-prompt">
              <vs-table stripe @selected="handleSelected4" :data="contenidodepartamento">
                <template slot="thead">
                  <vs-th>Codigo</vs-th>

                  <vs-th>Nombre Departamento</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td :data="data[indextr].id_departamento">{{ data[indextr].id_departamento }}</vs-td>
                    <vs-td :data="data[indextr].dep_nombre">{{ data[indextr].dep_nombre}}</vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
          </vs-popup>
          <!--======================================FIN=====================================================-->
        </div>
        <vs-divider position="left">
          <h3>Productos</h3>
        </vs-divider>
        <div class="p-base">
          <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;">
            <template slot="thead">
              <vs-th>Nº</vs-th>
              <vs-th>Listado Personal</vs-th>
              <vs-th>Dias Trabajados</vs-th>
              <vs-th>total Ingresos</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                <vs-td :data="tr.codigo">{{ tr.codigo }}</vs-td>
                <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
                <vs-td :data="tr.cantidad" style="width:150px!important;" v-if="modofact">
                  <vs-input class="w-full" placeholder="0" v-model="tr.cantidad" />
                </vs-td>
                <vs-td
                  :data="tr.cantidad"
                  style="width:150px!important;"
                  v-else
                >{{tr.cantidad | currency }}</vs-td>
                <vs-td :data="tr.precio" style="width:150px!important;" v-if="modofact">
                  <vs-input class="w-full" placeholder v-model="tr.precio" />
                </vs-td>
                <vs-td :data="tr.cantidad" v-else>{{tr.precio | currency }}</vs-td>
                <vs-td :data="tr.descuento" style="width:200px!important;" v-if="modofact">
                  <vx-input-group>
                    <vs-input class="w-full" placeholder v-model="tr.descuento" />
                  </vx-input-group>
                </vs-td>
                <vs-td
                  :data="tr.cantidad"
                  style="width:150px!important;"
                  v-else
                >{{tr.descuento | currency }}</vs-td>
                <!--<vs-td :data="tr.iva" style="width:200px!important;">
                <vs-select class="selectExample w-full" vs-multiple v-model="tr.iva">
                  <vs-select-item :key="res.id_iva" :value="res.id_iva" :text="res.nombre" v-for="res in contenidoiva"/>
                </vs-select>
                </vs-td>-->
                <vs-td>{{ (parseFloat(tr.cantidad)+parseFloat(tr.precio)) | currency }}</vs-td>
                <vs-td v-if="modofact==0">
                  <feather-icon
                    icon="TrashIcon"
                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                    class="ml-2 cursor-pointer"
                    @click="borrarprd(index)"
                  />
                </vs-td>
              </vs-tr>
              <vs-tr style="border-top: 1px solid #ddd;">
                <vs-th></vs-th>
                <vs-td>{{totalfac | currency}}</vs-td>
                <vs-td>{{totalfac | currency}}</vs-td>
                <vs-td>{{totalfac | currency}}</vs-td>
                <vs-td>{{totalfac | currency}}</vs-td>
                <vs-td>{{totalfac | currency}}</vs-td>
              </vs-tr>
            </template>
          </vs-table>
          <!--dividir-->
        </div>
        <!---->
      </vs-tab>
      <vs-tab
        title="JobInfo"
        id="JobInfo"
        label="Egresos"
        icon-pack="feather"
        icon="icon-briefcase"
      >
        <div class="vx-col w-full mb-base">
          <vs-table>
            <template slot="thead">
              <vs-th>Nº</vs-th>
              <vs-th>Iess 9.45%</vs-th>
              <vs-th>Total Egresos</vs-th>
            </template>
          </vs-table>
        </div>
      </vs-tab>
    </vs-tabs>
  </vx-card>
</template>
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { concat } from "bytebuffer";
import Datepicker from "vuejs-datepicker";
import VueUploadMultipleImage from "vue-upload-multiple-image";

const axios = require("axios");
export default {
  data() {
    return {
      total_factura: [],
      popupActive2: false,
      modofact: 1,
      contenidopr: [],
      Tabnav: 0,
      departamento: "",
      iddepart: "",
      contenidodepartamento: [],
      contenidodepartamento2: [],
      activePrompt4: false,
      contenido2: [],
      filtroNombre: ""
    };
  },
  //importa calendario español
  components: {
    flatPickr,
    FormWizard,
    TabContent,
    Datepicker
  },
  methods: {
    totalfac() {
      var total = 0;
      this.total_factura.forEach(el => {
        total += parseFloat(el.total_factura);
      });
      return total;
    },
    listarpr(pagepr, buscarpr, cantidadppr) {
      if (this.$route.params.id) {
        var idfactura = this.$route.params.id;
        let me = this;
        var url = "/api/factproductos";
        var valores = {
          page: pagepr,
          buscar: buscarpr,
          idfactura: parseInt(idfactura),
          cantidadp: cantidadppr
        };
        axios.get(url, valores).then(function(response) {
          var respuesta = response.data;
          me.contenidopr = respuesta.recupera.data;
          me.paginationpr = respuesta.pagination;
          if (cantidadppr > me.paginationpr.total) {
            cantidadppr = me.paginationpr.total;
          }
        });
      }
    },
    abrirproductos() {
      this.activePrompt4 = true;
      this.tipomodal = 2;
      this.listarDepartamento(1, this.buscarp, this.cantidadpp);
    },
    listarem(page, buscar, cantidadp) {
      let me = this;
      var url =
        "/api/nomina?page=" +
        page +
        "&buscar=" +
        buscar +
        "&cantidadp=" +
        cantidadp;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenido2 = respuesta.recupera.data;
          me.pagination = respuesta.pagination;
          if (cantidadp > me.pagination.total) {
            cantidadp = me.pagination.total;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    handleSelected4(tr) {
      this.departamento = `${tr.dep_nombre}`;
      this.iddepart = `${tr.id_departamento}`;
      this.activePrompt4 = false;
      this.departamento = `${tr.nombre}`;
      this.contenidopr.push({
        departamento: tr.nombre
      });
    },
    listarDepartamento() {
      let me = this;
      var url = "/api/departamento/listar"+1;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenidodepartamento = respuesta.recupera.data;
          me.pagination1 = respuesta.pagination;
          if (cantidadp1 > me.pagination1.total) {
            cantidadp1 = me.pagination1.total;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarDepartamento2() {
      let me = this;
      var url = "/api/departamento/rol";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenidodepartamento2 = respuesta.recupera.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  },
  mounted() {
    this.listarDepartamento();
    this.listarDepartamento2();
    this.listarem();
  }
};
</script>
<style scoped>
.btn-upload-all {
  display: none;
}
.btn-upload-file {
  display: none;
}
.image-container[data-v-10e59822] {
  width: 100%;
  height: 270px;
  border: 1px dashed #d6d6d6;
  border-radius: 4px;
  background-color: #fff;
}

.centered[data-v-10e59822] {
  width: 100%;
  height: 100%;
}

.show-img[data-v-10e59822] {
  max-width: 100%;
  max-height: 185px;
  width: 100%;
}

.image-overlay[data-v-10e59822] {
  height: 170px;
}

.image-overlay-details[data-v-10e59822] {
  top: 62%;
}

.image-list-container .image-list-item .show-img[data-v-10e59822] {
  max-width: 30px;
  max-height: 33px;
  height: 30px;
}

.imagenpre {
  height: 100%;
  cursor: pointer;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
.centimg {
  height: 225px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.8) !important;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}

.verimagen {
  overflow: hidden;
  padding: 0px;
  height: 225px;
  height: 225px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.8) !important;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
  border: 5px solid rgba(0, 0, 0, 0.3);
}

.imagenpre:hover {
  -moz-transform: scale(1.03);
  -webkit-transform: scale(1.03);
  -o-transform: scale(1.03);
  -ms-transform: scale(1.03);
  transform: scale(1.03);
}

.centimg:hover {
  background: rgba(255, 255, 255, 0.6) !important;
  cursor: pointer;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}

.centimg img {
  max-width: 100%;
  max-height: 100px;
  cursor: pointer;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
.btnrl {
  margin-top: 5px;
  margin-bottom: 20px;
}
</style>