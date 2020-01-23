<template>
  <div id="invoice-page">
    <vx-card>
        <vs-divider position="left">
        <h3>Importacion</h3>
      </vs-divider>
      <div class="vx-row leading-loose p-base">
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Periodo Inicio:</h6>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="periodo_inicio"
            />
            <div v-show="error" v-if="!periodo_inicio">
            <div v-for="err in errorperiodo_inicio" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Periodo Fin:</h6>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="periodo_fin"
            />
            <div v-show="error" v-if="!periodo_fin">
            <div v-for="err in errorperiodo_fin" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
        <h6>Número Orden:</h6>
             <vs-select
              class="selectExample w-full"
              autocomplete
              v-model="nro_orden"
            >
              <vs-select-item
                v-for="data in ordens"
                :key="data.id_factcompra"
                :value="data.id_factcompra"
                :text="data.orden_compra"
              />
            </vs-select>
          </div>
          
        <!--<div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Proveedor:</h6>
          <vs-select
              class="selectExample w-full"
              autocomplete
              v-model="id_proveedor"
            >
              <vs-select-item
                v-for="data in proveedors"
                :key="data.id_proveedor"
                :value="data.id_proveedor"
                :text="data.nombre_proveedor"
              />
            </vs-select>>
        </div>-->
      </div>
      <div class="vx-row leading-loose p-base">
          

          <div class="vx-col sm:w-1/3 w-full mb-5" hidden>
            <vs-select
              class="selectExample w-full"
              label="Estado"
              vs-multiple
              autocomplete
              v-model="estado"
            >
              <vs-select-item value="Inicial" text="Inicial" />
              <vs-select-item value="Liquidado" text="Liquidado" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-5">
            <label class="vs-input--label">Fecha Embarque</label>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="fech_embarque"
            />

        </div>
        <div class="vx-col sm:w-1/6 w-full mb-2">
          <label class="vs-input--label">Fecha Arribo</label>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="fech_arribo"
            />
        </div>
          <!--<div class="vx-col sm:w-1/4 w-full mb-2">
            <label class="vs-input--label" style="margin-left: 1px;">Liquidar</label>
            <vs-checkbox v-model="liquidar" vs-value="1" ></vs-checkbox>
          </div>-->
      </div>
      <vs-divider position="left">
        <h3>Proveedor</h3>
      </vs-divider>
      <!--fin modal-->
      <div class="vx-row leading-loose p-base" >
        <div class="vx-col w-full mb-2">
          <div class="dropdown-button-container">
            <vs-dropdown>
              <a class="flex items-center">
                Añadir Proveedor
                <i class="material-icons">expand_more</i>
              </a>
              <div v-show="error" v-if="valorproveedores.length<1">
          <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
      </div>
              <vs-dropdown-menu>
                <vs-dropdown-item
                  class="text-center"
                  divider
                  @click="popupActive3=true,tipomodalprov=1"
                >Buscar Proveedor</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="crear()">Crear Proveedor</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
        <div class="vx-row p-base" v-for="(tr,index) in valorproveedores" :key="index">
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Nombre:</h6>
          <vs-input disabled class="w-full" v-bind:value="tr.nombre" />
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Teléfono:</h6>
          <vs-input disabled class="w-full" v-bind:value="tr.telefono" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Grupo:</h6>
          <!--<vs-input disabled class="w-full" v-bind:value="email" />-->
          <vs-select disabled class="selectExample w-full nover" v-bind:value="tr.grupo">
            <vs-select-item
                v-for="data in grupo_menu"
                :key="data.id_grupoprov"
                :value="data.id_grupoprov"
                :text="data.nombre_grupoprov"
              />
          </vs-select>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Tipo de Identificación:</h6>
          <vs-select disabled class="selectExample w-full nover" v-bind:value="tr.tipo_identificacion">
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
          <vs-input disabled class="w-full" v-bind:value="tr.identificacion" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Dirección:</h6>
          <vs-input disabled class="w-full" v-bind:value="tr.direccion" />
        </div>
        <feather-icon
                    v-if="index>=1"
                    icon="TrashIcon"
                    style="position: absolute !important;right: 125px;margin-top: 70px;display: none;"
                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                    class="ml-2 cursor-pointer"
                    @click="borrarprov(index)"
                  />
      </div>
      </div>
      
          <vs-divider position="left">
        <h3>Productos</h3>
      </vs-divider>
      <div class="p-base">
        <a class="flex items-center cursor-pointer mb-4" @click="abrirproductos()">Añadir Productos</a>
        <div v-show="error" v-if="contenidopr.length<1">
            <div v-for="err in errorproducto" :key="err" v-text="err" class="text-danger"></div>
          </div>
        <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;">
            <template slot="thead">
              <vs-th>CÓDIGO</vs-th>
              <vs-th>NOMBRE</vs-th>
              <vs-th>CANTIDAD</vs-th>
              <vs-th>COSTO UNITARIO</vs-th>
              <!--<vs-th>DESCUENTO</vs-th>
              <vs-th>IVA</vs-th>-->
              <vs-th>COSTO TOTAL</vs-th>
              <vs-th>ELIMINAR</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                
                <!--<vs-td :data="tr.id_prodimp>{{ tr.id_prodimp }}</vs-td>-->
                <vs-td :data="tr.codigo">{{ tr.codigo }}</vs-td>
                <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
                <vs-td :data="tr.cantidad" style="width:150px!important;">
                  <vs-input class="w-full" placeholder="cantidad"  v-model="tr.cantidad" />
                  <div v-show="error" v-if="!tr.cantidad">
                    <div
                      v-for="err in tr.errorcant_ingreso"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.precio" style="width:150px!important;">
                  <vs-input
                    class="w-full"
                    
                    placeholder="$0.00"
                    v-model="tr.precio"
                  />
                  <div v-show="error" v-if="!tr.precio">
                    <div
                      v-for="err in tr.errorcost_unit_ingreso"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <!--<vs-td :data="tr.descuento" style="width:200px!important;">
                  <vx-input-group>
                    <vs-input
                      class="w-full"
                      
                      placeholder="$0.00"
                      v-model="tr.descuento"
                    />
                    <template slot="append">
                      <div class="append-text btn-addon">
                        <button class="botonstl" :class="{'botonstl elejido':tr.p_descuento==1,'botonstl':tr.p_descuento!=1}" @click="tr.p_descuento=1">$</button>
                        <button class="botonstl" :class="{'botonstl elejido':tr.p_descuento==0,'botonstl':tr.p_descuento!=0}" @click="tr.p_descuento=0">%</button>
                      </div>
                    </template>
                  </vx-input-group>
                </vs-td>
                <vs-td :data="tr.iva" style="width:200px!important;">
                  <vs-select class="selectExample w-full" vs-multiple v-model="tr.iva">
                    <vs-select-item
                      :key="res.id_iva"
                      :value="res.id_iva"
                      :text="res.nombre"
                      v-for="res in contenidoiva"
                    />
                  </vs-select>
                </vs-td>-->
               
                <vs-td >{{ (tr.cantidad*tr.precio)| currency }}</vs-td>
                <vs-td :data="tr.dolar">
                  <feather-icon
                    icon="TrashIcon"
                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                    class="ml-2 cursor-pointer"
                    @click="borrarprov(index)"
                  />
                </vs-td>
              </vs-tr>
            </template>
          </vs-table>
        <div class="vx-row mt-6">

          <div class="vx-col sm:w-1/2 w-full">
            <vs-table hoverFlat class="w-full" :data="invoiceData">
              <vs-tr v-if="liquidar">
                <vs-th>Costo Sin Liquidar</vs-th>
                <vs-td>{{ subtotalliq | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="facturas">
                <vs-th>Costos Adicionales</vs-th>
                <vs-td>{{ facturas | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-if="liquidar">
                <vs-th >Valor Total</vs-th>
                <vs-td>{{ subtotalpr | currency }}</vs-td>
              </vs-tr>
              <vs-tr v-else>
                <vs-th >Valor Total</vs-th>
                <vs-td>{{ subtotalliq | currency }}</vs-td>
              </vs-tr>
              <!--<vs-tr v-if="subtotalivapr12>0">
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
                <vs-th>VALOR TOTAL</vs-th>
                <vs-td>{{ totalpr | currency }}</vs-td>
              </vs-tr>-->
            </vs-table>
          </div>
        </div>
        <!--dividir-->
      </div>
      <div class="vx-col w-full">
        <vs-button color="success" type="filled" @click="guardar()" v-if="!$route.params.id">Guardar</vs-button>
        <vs-button color="success" type="filled" @click="editar()" v-else>Guardar</vs-button>
        <vs-button class="vs-con-loading__container" color="danger" type="filled" to="/compras/importacion">Cancelar</vs-button>
      </div>
       <vs-popup
            classContent="popup-example"
            title="Seleccione Proveedor"
            :active.sync="popupActive3"
          >
            <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
              <vs-input
                class="mb-4 mr-4 w-full"
                v-model="buscarprov"
                @keyup="listarproveedor(1,buscarprov,cantidadprov)"
                v-bind:placeholder="i18nbuscarprov"
              />
            </div>
            <vs-table
              stripe
              v-model="cuentaarray5"
              @selected="handleSelected5"
              :data="contenidoprov"
            >
              <template slot="thead">
                <vs-th>Nombre Proveedor</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].nombre_proveedor">{{ data[indextr].nombre_proveedor }}</vs-td>
                  <vs-td
                    :data="data[indextr].identif_proveedor"
                  >{{ data[indextr].identif_proveedor }}</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </vs-popup>
       <div class="demo-alignment">
      <vs-popup classContent="popup-example" title="Seleccione Proveedor" :active.sync="popupActive2">
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
                <vs-th>Telefono</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].nombre_proveedor">{{ data[indextr].nombre_proveedor }}</vs-td>
                  <vs-td :data="data[indextr].identif_proveedor">{{ data[indextr].identif_proveedor }}</vs-td>
                  <vs-td :data="data[indextr].direccion_prov">{{ data[indextr].direccion_prov }}</vs-td>
                  <vs-td :data="data[indextr].telefono_prov">{{ data[indextr].telefono_prov }}</vs-td>
                </vs-tr>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="datos" :key="index" v-for="(datos,index) in data">
                  <vs-td>{{datos.nombre_proveedor}}</vs-td>
                  <vs-td v-if="datos.identif_proveedor">{{datos.identif_proveedor}}</vs-td>
                  <vs-td v-if="datos.direccion_prov">{{datos.direccion_prov}}</vs-td>
                  <vs-td v-if="datos.telefono_prov">{{datos.telefono_prov}}</vs-td>
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
    </vx-card>
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
    components: {
    flatPickr,
    "v-select": vSelect
  },
    data() {
        return {
          //variables
        idrecupera:null,
        cod_importacion:"",
        periodo_inicio:"",
        periodo_fin:"",
        id_proveedor:"",
        nro_orden:"",
        estado:"",
        fech_embarque:"",
        fech_arribo:"",
        liquidar:null,
        liquidacion:false,
        facturas:"",
        configdateTimePicker: {
                locale: SpanishLocale
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
      
       //modal productos
       contenidop:[],
       contenidopr:[],
       popupActive2:false,
       tipomodal: 0,
       cuentaarray: [],
       cuentaarrayp: [],
       identificacion: "",
       contenidoempresa: [],
       buscarp: "",
       buscarpr: "",
       i18nbuscar: this.$t("i18nbuscar"),
       contenidoiva: [],
       //errores
      //errores
      error: 0,
      errorproveedor:[],
      errorproducto:[],
      errorperiodo_inicio: [],
      errorperiodo_fin: [],
      errorcant_ingreso: [],
      errorcost_unit_ingreso: [],
      //traer proveedor
      proveedors: [],
      //traer ordenes
      ordens:[],
      //modal proveedores
      paginaprov: 1,
      cantidadprov: 20,
      offset: 0,
      buscarprov: "",
      i18nbuscarprov: this.$t("i18nbuscar"),
      criterioprov: "nombre_proveedor",
      contenidoprov:[],
      cuentaarray5:[],
      //array proveedores
      valorproveedores: [
        {
          id_importacion:null,
          id_proveedor:null,
          nombre: null,
          telefono: null,
          grupo: null,
          tipo_identificacion: null,
          identificacion: null,
          direccion: null,
        }
      ],
      tipomodalprov:0,
      popupActive3:false,
      tipo_identificacion_menu: [
        { text: "Seleccione", value: 0 },
        { text: "Cédula de Identidad", value: "Cedula" },
        { text: "Ruc", value: "Ruc" },
        { text: "Pasaporte", value: 3 },
        { text: "Consumidor Final", value: 4 }
      ],
      grupo_menu: [],
    };
    },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    subtotalliq(){
      var total=0;
      this.contenidopr.forEach(el => {
        total += (el.precio*el.cantidad)
      });
      return total;
    },
    subtotalpr() {
      var total = 0;
      var total1 =0;
      this.contenidopr.forEach(el => {
        total += (el.precio*el.cantidad)
      });
      if(this.liquidar){
        total1 = total+parseFloat(this.facturas);
      }else{
         total1 = total;
      }
      return total1;
    },
    solonumeros($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode < 48 || keyCode > 57) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    solodecimales($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
        // 46 is dot
        $event.preventDefault();
      }
    },
  },
    
   methods: {
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
    handleSelected5(tr){
      
      this.popupActive3=false
      if(this.valorproveedores.length<1){
      this.valorproveedores.splice(0, 1);
      }
      if(this.valorproveedores.length<3){
        this.valorproveedores.push(
        {
          i_importacion:null,
          id_proveedor:tr.id_proveedor,
          nombre: tr.nombre_proveedor,
          telefono: tr.telefono_prov,
          grupo: tr.grupo,
          tipo_identificacion: tr.tipo_identificacion,
          identificacion: tr.identif_proveedor,
          direccion: tr.direccion_prov,
        },
      );
      }
    },
    listarproveedor(pageprov, buscarprov) {
      var url =
        "/api/proveedor/" +
        this.usuario.id_empresa +
        "?page=" +
        pageprov +
        "&buscar=" +
        buscarprov;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidoprov = respuesta.recupera;
      });
    },
    listargrupprov() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/abrirgrupprovorden";
      axios
        .get(url)
        .then(res => {
          //this.contenidopr = res.data;
          this.grupo_menu = res.data;
        })
        .catch(err => {
          //console.log(err);
        });
    },
    borrarprov(id) {
      this.valorproveedores.splice(id, 1);
    },
    abrirproductos() {
      this.popupActive2 = true;
      this.tipomodal = 2;
      this.listarp(1, this.buscarp, this.cantidadpp);
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
          //console.log(error);
        });
    },
    handleSelectedp(tr) {
      this.popupActive2 = false;
      //console.log(tr);
      this.contenidopr.push({
        id_producto: tr.id_producto,
        id_prodimp: null,
        nombre: tr.nombre,
        codigo: tr.cod_principal,
        cantidad: null,
        precio: null,//tr.pvp_precio1,
        descripcion: tr.descripcion,
        descuento: null,//tr.descuento,
        p_descuento:1,
        iva: tr.iva,
        
      });
    },
    guardar(){
      if (this.validarimport()) {
        return;
      }
       axios
        .post("/api/agregarimportacion", {
          cod_importacion: this.cod_importacion,
          nro_orden: this.nro_orden,
          estado: this.estado,
          periodo_inicio: this.periodo_inicio,
          periodo_fin: this.periodo_fin,
          fech_embarque: this.fech_embarque,
          fech_arribo: this.fech_arribo,
          //liquidar: this.liquidar,
          id_proveedor: this.id_proveedor,
          id_empresa: this.usuario.id_empresa,
          id_user: this.usuario.id,
          id_punto_emision: this.usuario.id_punto_emision,
          total_importacion: this.subtotalpr
         // id_producto: this.id_producto
        })
        .then(res => {
          this.guardarProductos(res.data);
          this.guardarProveedores(res.data);
            this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
            });
            this.$router.push("/compras/importacion");
        })
        .catch(err => {
          //console.log(err);
          this.$vs.notify({
            title: "Error al Guardar",
            text: "Verifique bien sus datos al momento de guardar",
            color: "danger"
          });
        });
    },
    guardarProductos(id){
      axios.post('/api/agregarprodimportacion',{
        id_import:id,
        productos:this.contenidopr
      });
    },
    guardarProveedores(id){
      axios.post('/api/agregarprovimportacion',{
        id_import:id,
        id_empresa:this.usuario.id_empresa,
        provds:this.valorproveedores
      });
    },
    editar() {
      axios
        .put("/api/actualizarimportacion", {
          id: this.idrecupera,
          cod_importacion: this.cod_importacion,
          nro_orden: this.nro_orden,
          estado: this.estado,
          periodo_inicio: this.periodo_inicio,
          periodo_fin: this.periodo_fin,
          fech_embarque: this.fech_embarque,
          fech_arribo: this.fech_arribo,
          //liquidar: this.liquidar,
          cantidad: this.cantidad,
          costo_unit: this.costo_unit,
          costo_total: this.costo_total,
          id_proveedor: this.id_proveedor,
          id_producto: this.id_producto,
          total_importacion:this.subtotalpr
        })
        .then(res => {
          this.editProductos(res.data);
          this.$vs.notify({
            title: "Registro Editado",
            text: "Registro Editado exitosamente",
            color: "success"
          });
          this.$router.push("/compras/importacion");
        })
        .catch(err => {
          this.$vs.notify({
            title: "Error al Editar",
            text: "Verifique bien sus datos al momento de editar",
            color: "danger"
          });
        });
    },
    editProductos(id){
        
        axios.put("/api/actualizarprodimportacion",{
            id_orden:parseInt(id),
            productos: this.contenidopr
        }).then(res=>{
            //console.log("Actualizado productos"+res);
        }).catch(err => {
            //console.log("Error al actualizar prod"+err);
        });
    },
    listarimport() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        this.num_prof = this.$route.params.id;
        var url = "/api/abririmportacion/"+this.idrecupera;
        //``, ``, ``, ``, `subtotal_iva12`, `valor_12`, `subtotal_iva0`, `sin_imp`, `total_descuento`, `total_orden`, `observacion`, `id_proveedor`, `id_empresa`
         axios
          .put(url)
          .then(res => {
            let data = res.data[0];

              (this.cod_importacion = data.cod_importacion),
              (this.nro_orden = data.id_orden),
              (this.estado = data.estado),
              (this.periodo_inicio = data.periodo_inicio),
              (this.periodo_fin = data.periodo_fin),
              (this.fech_embarque = data.fech_embarque),
              (this.fech_arribo = data.fech_arribo),
              (this.liquidar = data.total_liquidacion),
              (this.facturas = data.total_facturas),
              (this.id_proveedor = data.id_proveedor);
            //this.fecult = data.fecult;
          })
          .catch(err => {
            console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
    },
    listarprod() {
      if(this.$route.params.id){
        this.idrecupera = this.$route.params.id;
      }else{
        this.idrecupera = 0;
      }
      
      var url = "/api/traerproductoimport/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          this.contenidopr = res.data;
          //console.log(this.contenidopr);
        })
        .catch(err => {
          //console.log(err);
        });
    },
    listarprovs() {
      if(this.$route.params.id){
        this.idrecupera = this.$route.params.id;
      }else{
        this.idrecupera = 0;
      }
      var url = "/api/abrirproveedorimport/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          this.valorproveedores = res.data;
          //console.log(this.contenidopr);
        })
        .catch(err => {
          //console.log(err);
        });
    },
    validarimport() {
      this.error = 0;
      this.errorproveedor=[];
      this.errorproducto=[];
      this.errorperiodo_inicio = [];
      this.errorperiodo_fin = [];
      this.errorcant_ingreso = [];
      this.errorcost_unit_ingreso = [];
      if (!this.periodo_inicio) {
        this.errorperiodo_inicio.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.periodo_fin) {
        this.errorperiodo_fin.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.valorproveedores.length){
        this.errorproveedor.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.contenidopr.length){
        this.errorproducto.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      for (var i = 0; i < this.contenidopr.length; i++) {
        this.contenidopr[i].errorcant_ingreso = [];
        this.contenidopr[i].errorcost_unit_ingreso = [];
        if (!this.contenidopr[i].cantidad) {
          this.contenidopr[i].errorcant_ingreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.contenidopr[i].precio) {
          this.contenidopr[i].errorcost_unit_ingreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
      }
      
      return this.error;
    },
    crear() {
      this.$router.push("/compras/importacion/agregar");
    },
    getProveedor() {
      axios
        .get("/api/traerproveedorimport/" + this.usuario.id_empresa)

        .then(
          function(response) {
            this.proveedors = response.data;
          }.bind(this)
        );
    },
    getOrden() {
      axios
        .get("/api/traerorden/" + this.usuario.id_empresa)

        .then(
          function(response) {
            this.ordens = response.data;
          }.bind(this)
        );
    }
    },
    mounted() {
      this.getProveedor();
      this.listariva();
      this.getOrden();
      this.listarimport();
      this.listarprod();
      this.listarproveedor(1,this.buscarprov);
      this.listargrupprov();
      this.listarprovs();
    },
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
.botonstl{
  height: 100%;
    width: 38px;
    border: 1px solid #635ace;
    background: transparent;
    color: #635ace;
    font-size: 16px;
    cursor: pointer;
}
.elejido{
  background: #635ace!important;
  color:#fff!important;
}
</style>