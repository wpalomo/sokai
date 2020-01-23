<template>
  <div class="vx-row">
    <!-- MULTIPLE COLUMNS-->

    <div class="vx-col w-full mb-base">
      <vx-card>
        <h4 v-if="!idrecupera">Agregar Proveedor</h4>
        <h4 v-else>Editar Proveedor</h4>
        <br />

        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <!--<vs-input  class="w-full" label="Codigo" placeholder="PR010" v-model="codigo_proveedor" />-->
            <vs-input class="w-full" v-if="tipocod" label="Código" v-model="codigo_proveedor" />
            <vs-input class="w-full" v-else label="Código" disabled :value="codigo_proveedor" />
          </div>
         <!-- j{{codigoprov}}-->
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Grupo"
              vs-multiple
              autocomplete
              v-model="grupo"
              @change="getGrupo()"
            >
              <vs-select-item
                v-for="(data,index) in grupos"
                :key="index"
                :value="data.id_grupoprov"
                :text="data.nombre_grupoprov"
              />
            </vs-select>
            
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input
              type="email"
              class="w-full"
              label="Nombre"
              v-model="nombre"
              v-validate="'required'"
              name="nombre"
            />
            <span class="text-danger text-sm" v-show="errors.has('nombre')">Campo Obligatorio</span>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Tipo Identificacion"
              vs-multiple
              autocomplete
              v-model="tipoIdent"
              v-validate="'required'"
              name="tipoid"
            >
              <vs-select-item value="Cedula" text="Cedula" />
              <vs-select-item value="Ruc" text="Ruc" />
              <vs-select-item value="Pasaporte" text="Pasaporte" />
            </vs-select>
            <span class="text-danger text-sm" v-show="errors.has('tipoid')">Campo Obligatorio</span>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6" v-if="!tipoIdent" hidden>
            <!--<vs-input  class="w-full" label="Identificacion"  v-model="identificacion" />-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-if="tipoIdent=='Cedula'"
              @keypress="solonumeros"
              @keyup="validarcedula"
              maxlength="10"
            />
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else-if="tipoIdent=='Ruc'"
              @keypress="solonumeros"
              @keyup="validarruc"
              maxlength="13"
            />
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else
              maxlength="15"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6" v-else>
            <!--<vs-input  class="w-full" label="Identificacion"  v-model="identificacion" />-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-if="tipoIdent=='Cedula'"
              @keypress="solonumeros"
              @keyup="validarcedula"
              maxlength="10"
            />
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else-if="tipoIdent=='Ruc'"
              @keypress="solonumeros"
              @keyup="validarruc"
              maxlength="13"
            />
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else
              maxlength="15"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Contribuyente Especial</label>
            <vs-checkbox v-model="contribuyente" vs-value="1" ></vs-checkbox>
          </div>
         
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Beneficiario" v-model="beneficiario" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Contacto" v-model="contacto" />
            <div v-show="error" v-if="!contacto">
              <span class="text-danger" v-for="err in errorcontacto" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Direccion"  v-validate="'required'" name="dir" v-model="direccion" />
             <span class="text-danger text-sm" v-show="errors.has('dir')">Campo Obligatorio</span>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="No Casa" v-model="nrcasa" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              placeholder="Seleccione Provincia"
              class="selectExample w-full"
              label="Provincia"
              vs-multiple
              autocomplete
              v-model="provincia"
              v-validate="'required'" name="prov"
              @change="getCiudades()"
            >
              <!--<vs-select-item value=1 text="Pichincha" />-->
              <vs-select-item
                v-for="(data,index) in provincias"
                :key="index"
                :value="data.id_provincia"
                :text="data.nombre"
              />
            </vs-select>
            <span class="text-danger text-sm" v-show="errors.has('prov')">Campo Obligatorio</span>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              placeholder="Seleccione Ciudad"
              class="selectExample w-full"
              label="Ciudad"
              vs-multiple
              autocomplete
              v-model="ciudad"
              v-validate="'required'" name="ciud"
              @selected="getCiudades()"
            >
              <!--<vs-select-item value=1 text="Quito" />-->
              <vs-select-item
                v-for="(data,index) in ciudades"
                :key="index"
                :value="data.id_ciudad"
                :text="data.nombre"
              />
            </vs-select>
            <span class="text-danger text-sm" v-show="errors.has('ciud')">Campo Obligatorio</span>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Telefono" v-model="telefono" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6" v-if="idrecupera">
            <vs-select
              class="selectExample"
              label="Estado"
              vs-multiple
              autocomplete
              v-model="estado"
              
            >
              <vs-select-item value="1" text="Activo" />
              <vs-select-item value="0" text="Inactivo" />
            </vs-select>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-select
              placeholder="Seleccione Banco"
              class="selectExample w-full"
              label="Banco"
              vs-multiple
              autocomplete
              v-model="banco"
              @change="getBancos()"
            >
              <!--<vs-select-item value=1 text="Pichincha" />-->
              <vs-select-item
                v-for="(data,index) in bancos"
                :key="index"
                :value="data.id_banco"
                :text="data.nombre_banco"
              />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Tipo Cuenta"
              vs-multiple
              autocomplete
              v-model="tipCuenta"
            >
              <vs-select-item value="1" text="Corriente" />
              <vs-select-item value="2" text="Ahorros" />
              <vs-select-item value="3" text="Virtual" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-input class="w-full" label="Cuenta Banco" v-model="ctaBanco" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Pagos" v-model="pago" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Plazo" v-model="plazo" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Dias pago" v-model="dpagos" />
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6" >
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="ctacontable" :value="idContable" disabled/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <!-- -->
                  <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
        </div>
        <div>
          <label class="vs-input--label">Comentario</label>
          <vs-textarea v-model="comentario" height="80" />
        </div>
        <vs-divider position="left">Datos Sri</vs-divider>
        <div class="vx-row">
          
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Tipo Comprobante"
              vs-multiple
              autocomplete
              v-model="tcomprobante"
              @change="getTipoComprob()"
            >
              <vs-select-item
                v-for="(data,index) in tipcomprob"
                :key="index"
                :value="data.id_tipcomprobante"
                :text="data.descrip_tipcomprob"
              />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input
              class="w-full"
              label="Serie"
              v-model="serie"
              @keypress="solonumeros"
              maxlength="7"
            />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Fecha Validez</label>
            <flat-pickr :config="configdateTimePicker" v-model="fvalidez" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Factura Inicial" v-model="rangmin" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Factura Final" v-model="ranmax" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="#Autorizacion" v-model="nroAutorizacion" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Contribuye Sri</label>
            <vs-checkbox v-model="contribuyeSri" vs-value="1" ></vs-checkbox>
          </div>
          
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <ul class="demo-alignment">
              <li>
                <vs-radio v-model="tipElectronico" vs-value="0">Offline</vs-radio>
              </li>
              <li>
                <vs-radio v-model="tipElectronico" vs-value="1">Online</vs-radio>
              </li>
            </ul>
          </div>
        </div>
        <vs-divider position="left">Retenciones Aplicables</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-3/5 w-full mb-6">
            <vs-select
              class="selectExample w-full"
              label="Impuesto Retencion"
              vs-multiple
              autocomplete
              v-model="impstRetencion"
              @change="getImpFuente()"
            >
            <vs-select-item v-for="(data,index) in retfuente" :key="index" :value="index" :text="data.descrip_retencion"/>
            </vs-select>
           
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6"></div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Codigo Sri Impuesto"
              vs-multiple
              autocomplete
              v-model="codSriImp"
            >
              <vs-select-item
                v-for="(data,index) in impfuente"
                :key="index"
                :value="data.cod_imp"
                :text="data.cod_imp"
              />
            </vs-select>
            
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-3/5 w-full mb-6">
            <vs-select
              class="selectExample w-full"
              label="Retencion Iva"
              vs-multiple
              autocomplete
              v-model="retencionIva"
              @change="getImpIva()"
            >
              <vs-select-item
                v-for="(data,index) in retiva"
                :key="index"
                :value="index"
                :text="data.descrip_retencion"
              />
            </vs-select>

          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6"></div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Codigo Sri Iva"
              vs-multiple
              autocomplete
              v-model="codSriIva"
            >
              <vs-select-item
                v-for="(data,index) in impiva"
                :key="index"
                :value="data.cod_imp"
                :text="data.cod_imp"
              />
            </vs-select>
          </div>
        </div>
        <vs-divider />
        <div class="vx-col sm:w-1/6 w-full mb-6">
          <vs-input class="w-full" label="Cash Manager" v-model="idbanco" />
        </div>
        <vs-divider />
        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-button color="success" type="filled" @click="guardar()" v-if="!idrecupera">GUARDAR</vs-button>
            <vs-button color="success" type="filled" @click="editar()" v-else>GUARDAR</vs-button>
            <vs-button color="warning" type="filled" @click="borrar()">BORRAR</vs-button>
            <vs-button color="danger" type="filled" @click="cancelar()">CANCELAR</vs-button>
          </div>
        </div>
        <vs-popup title="Plan Cuentas" class="peque" :active.sync="activePrompt3">
          <div class="con-exemple-prompt">
            <vs-input
              class="mb-4 md:mb-0 mr-4 w-full"
              v-model="buscar"
              @keyup="listar(1,buscar)"
              v-bind:placeholder="i18nbuscar"
            />
            <vs-table stripe v-model="cuentaarray3" @selected="handleSelected3" :data="contenido">
              <template slot="thead">
                <vs-th>No.Cuenta</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].codcta">{{ data[indextr].codcta }}</vs-td>
                  <vs-td :data="data[indextr].nomcta">{{ data[indextr].nomcta }}</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </vs-popup>
        
      </vx-card>
    </div>
  </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import Datepicker from "vuejs-datepicker";
import moment from "moment";
const $ = require("jquery");
const axios = require("axios");
export default {
  data() {
    return {
      idrecupera: null,
      codigo_proveedor: "",
      grupo: "",
      nombre: "",
      tipoIdent: "",
      identificacion: "",
      tipo: "",
      contribuyente: null,
      contribesp_valor:"0",
      contribuye_valor:"0",
      beneficiario: "",
      //identificacionBenf:"",
      contacto: "",
      direccion: "",
      nrcasa: "",
      provincia: "",
      ciudad: "",
      telefono: "",
      estado: "",
      banco: "",
      tipCuenta: "",
      ctaBanco: "",
      idbanco: "",
      //nrctaInterbancaria:"",
      pago: "",
      plazo: "",
      dpagos: "",
      ctacontable: "",
      comentario: "",
      tcomprobante: "",
      serie: "",
      fvalidez: "",
      rangmin: "",
      ranmax: "",
      nroAutorizacion: "",
      contribuyeSri: null,
      tipElectronico: "0",
      impstRetencion: "I.R.F. Por Pagar (8%) Arriendos",
      impstRetencionporcent:"",
      retencionIva: "I.V.A. Retenido por Pagar (70%)",
      codSriImp: "",
      codSriIva: "",
      configdateTimePicker: {
        locale: SpanishLocale
      },
      event_at: new Date(),
      activePrompt3: false,
      idContable: "",
      cuentaarray3: [],
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
      cantidadp: 1000,
      offset: 3,
      //buscador
      buscar: "",
      criterio: "codcta",
      //otros valores
      gridApi: null,
      contenido: [],
      //errores
      error: 0,
      erroridentificacion: [],
      errorcodigo_proveedor: [],
      errorgrupo: [],
      errornombre: [],
      errortipoIdent: [],
      erroridentificacion: [],
      errortipo: [],
      errorcontribuyente: [],
      errorbeneficiario: [],

      errorcontacto: [],
      errordireccion: [],
      errornrcasa: [],
      errorprovincia: [],
      errorciudad: [],
      errortelefono: [],
      errorestado: [],
      errorbanco: [],
      errortipCuenta: [],
      errorctaBanco: [],
      erroridbanco: [],

      errorpago: [],
      errorplazo: [],
      errordpagos: [],
      errorctacontable: [],
      errorcomentario: [],
      errortcomprobante: [],
      errorserie: [],
      errorfvalidez: [],
      errorrangmin: [],
      errorranmax: [],
      errornroAutorizacion: [],
      errorcontribuyeSri: [],
      errortipElectronico: [],
      errorimpstRetencion: [],
      errorretencionIva: [],
      errorcodSriImp: [],
      errorcodSriIva: [],
      //traer
      provincias: [],
      ciudades: [],
      bancos: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //traer grupo-proveedor
      grupos: [],
      //traer impuesto de retencion a la fuente
      impfuente: [],
      //traer impuesto de retencion al iva
      impiva: [],
      //traer tipo comprobante
      tipcomprob: [],
      //traer retencion fuente compra
      retfuente: [],
      ////traer retencion iva compra
      retiva: [],
      codigoen: 0,
      codigoprov: [],
      tipocod:0,
      retencion_nombre:"",
      retencion_iva:""
    };
  },
  mounted() {
    this.listar2();
    this.listar(1, this.buscar);
    this.leercodigoprov();
    this.getProvincias();
    this.getCiudades();
    this.getBancos();
    this.getGrupo();
    this.getImpFuente();
    this.getImpIva();
    this.getTipoComprob();
    this.getRetFuente();
    this.getRetIva();
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    
  },
  components: {
    flatPickr,
    Datepicker
  },
  methods: {
    checkespecial(){
      if(!this.contribuyente){
        console.log("null");
        this.contribesp_valor="0"
      }else{
        console.log("no null ");
        this.contribesp_valor="1"
      }
    },
    checksri(){
      if(!this.contribuyeSri){
        console.log("null");
        this.contribuye_valor="0"
      }else{
        console.log("no null ");
        this.contribuye_valor="1"
      }
    },
    leercodigoprov() {
      if (!this.$route.params.id) {
        axios
          .get("/api/codigo?id=" + this.usuario.id_empresa)
          .then(res => {
            this.codigoprov = res.data;
            if(this.codigoprov=="vacio"){
              this.tipocod=1;
            }else{
              this.tipocod=0;
              this.codigo_proveedor = this.codigoprov;
            }
          });
      }
    },
    handleSelected3(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      (this.ctacontable = `${tr.codcta}`),
        (this.idContable = `${tr.id_ctas}`),
        (this.activePrompt3 = false);
    },
    guardar() {
      /*if (this.validar()) {
        $('html, body').animate({scrollTop:0}, 'slow');
        return;
      }*/
      /*if (this.codigoen) {
        this.codigo_proveedor = this.codigo_proveedor + "-1";
      }*/
      this.$validator.validateAll().then(result => {
        if (result) {
          // if form have no errors
          axios
            .post("/api/agregarproveedor", {
              cod_proveedor: this.codigo_proveedor,
              grupo: this.grupo,
              nombre_proveedor: this.nombre,
              tipo_identificacion: this.tipoIdent,
              identif_proveedor: this.identificacion,
              //tipo_proveedor:this.tipo,
              contribuyente: this.contribuyente,
              beneficiario: this.beneficiario,
              //identif_benefic:this.identificacionBenf,
              contacto: this.contacto,
              direccion_prov: this.direccion,
              nrcasa: this.nrcasa,
              telefono_prov: this.telefono,
              //estado_prov: this.estado,
              tipo_cuenta: this.tipCuenta,
              cta_banco: this.ctaBanco,
              id: this.idbanco,
              //nrcta_interbancaria:this.nrctaInterbancaria,
              pagos: this.pago,
              plazo: this.plazo,
              dias_pago: this.dpagos,
              tip_comprob: this.tcomprobante,
              serie: this.serie,
              fvalidez: this.fvalidez,
              comentario: this.comentario,
              rangomax: this.ranmax,
              rangomin: this.rangmin,
              nrautorizacion: this.nroAutorizacion,
              contribuye_sri: this.contribuyeSri,
              tip_electronico: this.tipElectronico,
              imp_retencion: this.impstRetencion,
              codsri_imp: this.codSriImp,
              retencion_iva: this.retencionIva,
              codsri_iva: this.codSriIva,
              cta_contable: this.ctacontable,
              //id_contable:this.ctacontable,
              id_provincia: this.provincia,
              id_ciudad: this.ciudad,
              id_banco: this.banco,
              id_empresa: this.usuario.id_empresa,
            })
            .then(res => {
              if (res.data != "existe") {
                this.$vs.notify({
                  title: "Registro Guardado",
                  text: "Registro Guardado exitosamente",
                  color: "success"
                });
                //console.log(res.data);
                this.$router.push("/compras/proveedor").catch(() => {});
              } else {
                this.$vs.notify({
                  title: "Cuenta Contable",
                  text: "cuenta contable no existe",
                  color: "danger"
                });
              }
            })
            .catch(err => {});
        } else {
          // form have errors
          $("html, body").animate({ scrollTop: 0 }, "slow");
          return;
        }
      });
    },
    editar() {
      axios
        .put("/api/actualizarproveedor", {
          id_proveedor: this.idrecupera,
          cod_proveedor: this.codigo_proveedor,
          grupo: this.grupo,
          nombre_proveedor: this.nombre,
          tipo_identificacion: this.tipoIdent,
          identif_proveedor: this.identificacion,
          //tipo_proveedor:this.tipo,
          contribuyente: this.contribuyente,
          beneficiario: this.beneficiario,
          //identif_benefic:this.identificacionBenf,
          contacto: this.contacto,
          direccion_prov: this.direccion,
          nrcasa: this.nrcasa,
          telefono_prov: this.telefono,
          estado_prov: this.estado,
          tipo_cuenta: this.tipCuenta,
          cta_banco: this.ctaBanco,
          id: this.idbanco,
          //nrcta_interbancaria:this.nrctaInterbancaria,
          pagos: this.pago,
          plazo: this.plazo,
          dias_pago: this.dpagos,
          tip_comprob: this.tcomprobante,
          serie: this.serie,
          fvalidez: this.fvalidez,
          comentario: this.comentario,
          rangomax: this.ranmax,
          rangomin: this.rangmin,
          nrautorizacion: this.nroAutorizacion,
          contribuye_sri: this.contribuyeSri,
          tip_electronico: this.tipElectronico,
          imp_retencion: this.retencion_nombre,
          codsri_imp: this.codSriImp,
          retencion_iva: this.retencion_iva,
          codsri_iva: this.codSriIva,
          cta_contable: this.ctacontable,
          id_provincia: this.provincia,
          id_ciudad: this.ciudad,
          id_banco: this.banco
        })
        .then(res => {
          if (res.data != "existe") {
            this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
            });
            //console.log(res.data);
            this.$router.push("/compras/proveedor").catch(() => {});
          } else {
            this.$vs.notify({
              title: "Cuenta Contable",
              text: "cuenta contable no existe",
              color: "danger"
            });
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    borrar() {
      (this.codigo_proveedor = ""),
        (this.grupo = ""),
        (this.nombre = ""),
        (this.tipoIdent = ""),
        (this.identificacion = ""),
        (this.tipo = ""),
        (this.contribuyente = null),
        (this.beneficiario = ""),
        //this.identificacionBenf="",
        (this.contacto = ""),
        (this.direccion = ""),
        (this.nrcasa = ""),
        (this.provincia = ""),
        (this.ciudad = ""),
        (this.telefono = ""),
        (this.estado = ""),
        (this.banco = ""),
        (this.tipCuenta = ""),
        (this.ctaBanco = ""),
        (this.idbanco = ""),
        //this.nrctaInterbancaria="",
        (this.pago = ""),
        (this.plazo = ""),
        (this.dpagos = ""),
        (this.ctacontable = ""),
        (this.idContable = ""),
        (this.comentario = ""),
        (this.tcomprobante = ""),
        (this.serie = ""),
        (this.fvalidez = ""),
        (this.rangmin = ""),
        (this.ranmax = ""),
        (this.nroAutorizacion = ""),
        (this.contribuyeSri = null),
        (this.tipElectronico = "Online"),
        (this.impstRetencion = ""),
        (this.retencionIva = ""),
        (this.codSriImp = ""),
        (this.codSriIva = "");
    },
    cancelar() {
      this.$router.push("/compras/proveedor");
    },
    validar() {
      (this.error = 0),
        (this.erroridentificacion = []),
        (this.codigo_proveedor = []),
        (this.errorgrupo = []),
        (this.errornombre = []),
        (this.errortipoIdent = []),
        (this.erroridentificacion = []),
        (this.errortipo = []),
        (this.errorcontribuyente = []),
        (this.errorbeneficiario = []),
        (this.errorcontacto = []),
        (this.errordireccion = []),
        (this.errornrcasa = []),
        (this.errorprovincia = []),
        (this.errorciudad = []),
        (this.errortelefono = []),
        (this.errorestado = []),
        (this.errorbanco = []),
        (this.errortipCuenta = []),
        (this.errorctaBanco = []),
        (this.erroridbanco = []),
        (this.errorpago = []),
        (this.errorplazo = []),
        (this.errordpagos = []),
        (this.errorctacontable = []),
        (this.errorcomentario = []),
        (this.errortcomprobante = []),
        (this.errorserie = []),
        (this.errorfvalidez = []),
        (this.errorrangmin = []),
        (this.errorranmax = []),
        (this.errornroAutorizacion = []),
        (this.errorcontribuyeSri = []),
        (this.errortipElectronico = []),
        (this.errorimpstRetencion = []),
        (this.errorretencionIva = []),
        (this.errorcodSriImp = []),
        (this.errorcodSriIva = []);

      if (!this.nombre) {
        this.errornombre.push("Campo Obligatorio");
        this.error = 1;
      }
      if (!this.tipoIdent) {
        this.errortipoIdent.push("Campo Obligatorio");
        this.error = 1;
      }
      if (!this.contacto) {
        this.errorcontacto.push("Campo Obligatorio");
        this.error = 1;
      }

      return this.error;
    },
    validarcedula($event) {
      this.error = 0;
      this.erroridentificacion = [];
      if (this.identificacion.length < 10) {
        this.erroridentificacion.push("Cedula invalida");
        this.error = 1;
        return;
      }
      if (
        typeof this.identificacion == "string" &&
        this.identificacion.length == 10 &&
        /^\d+$/.test(this.identificacion)
      ) {
        var digitos = this.identificacion.split("").map(Number);
        var codigo_provincia = digitos[0] * 10 + digitos[1];

        //if (codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30) && digitos[2] < 6) {

        if (
          codigo_provincia >= 1 &&
          (codigo_provincia <= 24 || codigo_provincia == 30)
        ) {
          var digito_verificador = digitos.pop();

          var digito_calculado =
            digitos.reduce(function(valorPrevio, valorActual, indice) {
              return (
                valorPrevio -
                ((valorActual * (2 - (indice % 2))) % 9) -
                (valorActual == 9) * 9
              );
            }, 1000) % 10;
          console.log(digito_calculado);
          //return digito_calculado === digito_verificador;
          if (digito_calculado === digito_verificador) {
            this.erroridentificacion = [];
          } else {
            this.erroridentificacion.push("Cédula inválida");
            this.error = 1;
            return;
          }
        } else {
          this.erroridentificacion.push("Cédula inválida");
          this.error = 1;
          return;
        }
      }
      return false;
      /*
      this.erroridentificacion=[];
      var cad = this.identificacion;
      var total = 0;
      var longitud = cad.length;
      var longcheck = longitud - 1;
      for(var i = 0; i < longcheck; i++){
        if (i%2 === 0) {
          var aux = cad.charAt(i) * 2;
          if (aux > 9) aux -= 9;
          total += aux;
        } else {
          total += parseInt(cad.charAt(i)); // parseInt o concatenará en lugar de sumar
        }
      }
      total = total % 10 ? 10 - total % 10 : 0;

      if (cad.substring(0,10).charAt(longitud-1) == total) {
        this.erroridentificacion=[];
      }else{
        this.erroridentificacion.push("Cédula inválida");
        this.error = 1;
      }*/
    },
    validarruc($event) {
      this.error = 0;
      this.erroridentificacion = [];
      var numero = this.identificacion;
      var suma = 0;
      var residuo = 0;
      var pri = false;
      var pub = false;
      var nat = false;
      var numeroProvincias = 22;
      var modulo = 11;

      /* Verifico que el campo no contenga letras */
      var ok = 1;
      /*for (var i=0; i<numeroProvincias ;i++){
      alert('El código de la provincia (dos primeros dígitos) es inválido'); return false;
      }*/
            /*
      if (typeof(this.identificacion) == 'string' && this.identificacion.length == 10 && /^\d+$/.test(this.identificacion)) {
      var digitos = numero.split('').map(Number);
          var codigo_provincia = digitos[0] * 10 + digitos[1];
          
          //if (codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30) && digitos[2] < 6) {
            if(codigo_provincia<24 && codigo_provincia <= 1){
              this.erroridentificacion.push("Ruc inválido");
              this.error=1;
              return ;
            }
      }*/
      /* Aqui almacenamos los digitos de la cedula en variables. */
      var d1 = numero.substr(0, 1);
      var d2 = numero.substr(1, 1);
      var d3 = numero.substr(2, 1);
      var d4 = numero.substr(3, 1);
      var d5 = numero.substr(4, 1);
      var d6 = numero.substr(5, 1);
      var d7 = numero.substr(6, 1);
      var d8 = numero.substr(7, 1);
      var d9 = numero.substr(8, 1);
      var d10 = numero.substr(9, 1);

      /* El tercer digito es: */
      /* 9 para sociedades privadas y extranjeros */
      /* 6 para sociedades publicas */
      /* menor que 6 (0,1,2,3,4,5) para personas naturales */

      if (d3 == 7 || d3 == 8) {
        //console.log('El tercer dígito ingresado es inválido');
        this.erroridentificacion.push("El tercer dígito ingresado es inválido");
        this.error = 1;
        return false;
      }

      /* Solo para personas naturales (modulo 10) */
      if (d3 < 6) {
        nat = true;
        p1 = d1 * 2;
        if (p1 >= 10) p1 -= 9;
        p2 = d2 * 1;
        if (p2 >= 10) p2 -= 9;
        p3 = d3 * 2;
        if (p3 >= 10) p3 -= 9;
        p4 = d4 * 1;
        if (p4 >= 10) p4 -= 9;
        p5 = d5 * 2;
        if (p5 >= 10) p5 -= 9;
        p6 = d6 * 1;
        if (p6 >= 10) p6 -= 9;
        p7 = d7 * 2;
        if (p7 >= 10) p7 -= 9;
        p8 = d8 * 1;
        if (p8 >= 10) p8 -= 9;
        p9 = d9 * 2;
        if (p9 >= 10) p9 -= 9;
        modulo = 10;
      } else if (d3 == 6) {

      /* Solo para sociedades publicas (modulo 11) */
      /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
        pub = true;
        p1 = d1 * 3;
        p2 = d2 * 2;
        p3 = d3 * 7;
        p4 = d4 * 6;
        p5 = d5 * 5;
        p6 = d6 * 4;
        p7 = d7 * 3;
        p8 = d8 * 2;
        p9 = 0;
      } else if (d3 == 9) {

      /* Solo para entidades privadas (modulo 11) */
        var pri = true;
        var p1 = d1 * 4;
        var p2 = d2 * 3;
        var p3 = d3 * 2;
        var p4 = d4 * 7;
        var p5 = d5 * 6;
        var p6 = d6 * 5;
        var p7 = d7 * 4;
        var p8 = d8 * 3;
        var p9 = d9 * 2;
      }

      var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
      var residuo = suma % modulo;

      /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
      var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;

      /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
      if (pub == true) {
        if (digitoVerificador != d9) {
          //console.log('El ruc de la empresa del sector público es incorrecto.');
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
        /* El ruc de las empresas del sector publico terminan con 0001*/
        if (numero.substr(9, 4) != "0001") {
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
      } else if (pri == true) {
        if (digitoVerificador != d10) {
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
        if (numero.substr(10, 3) != "001") {
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
      } else if (nat == true) {
        if (digitoVerificador != d10) {
          //console.log('El número de cédula de la persona natural es incorrecto.');
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
        if (numero.length < 14 && numero.substr(10, 12) != "001") {
          //console.log('El ruc de la persona natural debe terminar con 001');
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
      }
      return true;
      /*
      this.erroridentificacion=[];
      var cad = this.identificacion;
      var total = 0;
      var longitud = cad.length;
      var longcheck = longitud - 1;
      for(var i = 0; i < longcheck; i++){
        if (i%2 === 0) {
          var aux = cad.charAt(i) * 2;
          if (aux > 9) aux -= 9;
          total += aux;
        } else {
          total += parseInt(cad.charAt(i)); // parseInt o concatenará en lugar de sumar
        }
      }
      total = total % 10 ? 10 - total % 10 : 0;

      if (cad.substring(0,10).charAt(longitud-1) == total) {
        this.erroridentificacion=[];
        if(cad.length>=10 && cad.length<13){
          //this.errorruc = [];
          this.erroridentificacion.push("RUC erroneo")
          this.error = 1;
        }else{
          this.error = 0;
          if(this.identificacion.substring(8,9)){
            this.error = 0;
            //this.erroridentificacion.push("holla");
            console.log("hola"+this.identificacion.substring(8,9));
          }else{
              this.validarrucfinalrepre();
          }
          
        }
      }else{
        //this.errorruc = [];
        this.erroridentificacion.push("RUC inválida");
        this.error = 1;
        if(cad.length>=10 && cad.length<13){
          //this.errorruc = [];
          this.erroridentificacion.push("RUC erroneo")
          this.error = 1;
        }
      }
      return this.error;*/
    },
    validarrucfinalrepre() {
      this.error = 0;
      this.erroridentificacion = [];
      var ruc = this.identificacion.substring(10, 13);
      if (ruc == "001") {
        this.error = 0;
        this.erroridentificacion = [];
      } else {
        this.erroridentificacion.push("RUC inválido");
        this.error = 1;
        //console.log("incorrecto :");
      }
    },
    solonumeros($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode < 48 || keyCode > 57) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    listar(page, buscar) {
      let me = this;
      var url =
        "/api/cuentas/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
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
    listar2() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        var url = "/api/abrirproveedor";
        axios
          .put(url, { id: this.idrecupera })
          .then(res => {
            let data = res.data[0];

            this.codigo_proveedor = data.cod_proveedor;
            this.grupo = data.grupo;
            this.nombre = data.nombre_proveedor;
            this.tipoIdent = data.tipo_identificacion;
            this.identificacion = data.identif_proveedor;
            //this.tipo=data.tipo_proveedor
            this.contribuyente = data.contribuyente;
            this.beneficiario = data.beneficiario;
            //this.identificacionBenf=data.identif_benefic
            this.contacto = data.contacto;
            this.direccion = data.direccion_prov;
            this.nrcasa = data.nrcasa;
            this.provincia = data.id_provincia;
            this.ciudad = data.id_ciudad;
            this.telefono = data.telefono_prov;
            this.estado = data.estado_prov;
            this.banco = data.id_banco;
            this.tipCuenta = data.tipo_cuenta;
            this.ctaBanco = data.cta_banco;
            this.idbanco = data.id;
            // this.nrctaInterbancaria=data.nrcta_interbancaria
            this.pago = data.pagos;
            this.plazo = data.plazo;
            this.dpagos = data.dias_pago;
            this.ctacontable = data.cta_contable;
            //this.idContable=data.cta_contable
            this.comentario = data.comentario;
            this.tcomprobante = data.tip_comprob;
            this.serie = data.serie;
            this.fvalidez = data.fvalidez;
            this.rangmin = data.rangomin;
            this.ranmax = data.rangomax;
            this.nroAutorizacion = data.nrautorizacion;
            this.contribuyeSri = data.contribuye_sri;
            this.tipElectronico = data.tip_electronico;
            this.impstRetencion = data.imp_retencion;
            this.retencionIva = data.retencion_iva;
            this.codSriImp = data.codsri_imp;
            this.codSriIva = data.codsri_iva;
            this.retencion_nombre=data.imp_retencion;
            this.retencion_iva=data.retencion_iva;
            
          })
          .catch(err => {
            console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
    },
    getProvincias: function() {
      axios.get("/api/traerprovinciaprov").then(
        function(response) {
          this.provincias = response.data;
          this.provs == this.id_provincia;
        }.bind(this)
      );
    },
    getCiudades: function() {
      axios
        .get("/api/traerciudadprov", {
          params: {
            provincia: this.provincia
          }
        })
        .then(
          function(response) {
            //console.log("provincia",this.id_provs,"s");
            this.ciudades = response.data;
          }.bind(this)
        );
    },
    getBancos: function() {
      axios.get("/api/traerbancoprov").then(
        function(response) {
          this.bancos = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getGrupo() {
      axios.get("/api/traergruprov").then(
        function(response) {
          this.grupos = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getImpFuente() {
      var r=0;
      var id_ret;
      if(this.retfuente.length>=1){
        //console.log(this.retfuente[this.impstRetencion].id_retencion)
        console.log(this.retfuente[this.impstRetencion].porcen_retencion)
        r=this.retfuente[this.impstRetencion].porcen_retencion
        this.retencion_nombre=this.retfuente[this.impstRetencion].descrip_retencion
        
      }
      axios
        .get("/api/traerimpfuente", {
          params: {
            
            porcen_impret: r
          }
        })
        .then(
          function(response) {
            if (response.data) {
              this.impfuente = response.data;
            } else {
              this.impfuente = 0;
              console.log("hola");
            }
          }.bind(this)
        );
    },
    getImpIva() {
      var por=0;
      if(this.retiva.length>=1){
        //console.log("iddd"+this.retiva[this.retencionIva].id_retencion)
        //console.log("ppp"+this.retiva[this.retencionIva].porcen_retencion)
        por=this.retiva[this.retencionIva].porcen_retencion
        this.retencion_iva=this.retiva[this.retencionIva].descrip_retencion
      }
      axios
        .get("/api/traerimpiva", {
          params: {
            porcen_imp: por
          }
        })
        .then(
          function(response) {
            if (response.data) {
              this.impiva = response.data;
            } else {
              this.impiva = 0;
              console.log("hola");
            }
          }.bind(this)
        );
    },
    getTipoComprob() {
      axios.get("/api/traertipcomprob").then(
        function(response) {
          this.tipcomprob = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getRetFuente() {
      axios.get("/api/traerretfuente").then(
        function(response) {
          this.retfuente = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getRetIva() {
      axios.get("/api/traerretiva").then(
        function(response) {
          this.retiva = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    }
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
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
.vs-popup {
  width: 900px !important;
}
.peque .vs-popup {
  width: 1060px !important;
}
</style>