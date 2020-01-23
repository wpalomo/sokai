<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
          <div class="dropdown-button-container mr-3">
          <vs-dropdown>
            <vs-button class="btn-drop" type="filled" icon="settings" style="border-radius: 5px;"></vs-button>
            <vs-dropdown-menu style="width:13em;">
              <vs-dropdown-item class="text-center" divider @click="verCaja()">Caja Chica</vs-dropdown-item>
              <!--<vs-dropdown-item class="text-center" divider @click="verTipocomprob()">Tipo Comprobante</vs-dropdown-item>-->
              <!--<vs-dropdown-item class="text-center" divider @click="abrirbodega()">Bodega</vs-dropdown-item>-->
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" @click="abrirModal('agregar')">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item
                  class="text-center"
                  divider
                  to="/app/agregarEjemplo"
                >Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>
      <br />
      <vs-table stripe :data="contenido">
        <template slot="thead">
          <vs-th>No.Cuenta</vs-th>
          <vs-th>Tipo Cuenta</vs-th>
          <vs-th>Moneda</vs-th>
          <vs-th>Grupo</vs-th>
          
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="datos in data" :key="datos.id_ctas">
            <vs-td>{{datos.codcta}}</vs-td>
            <vs-td v-if="datos.nomcta">{{datos.nomcta }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nomb_moneda">{{datos.nomb_moneda}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nomb_grupo">{{datos.nomb_grupo}}</vs-td>
            <vs-td v-else>-</vs-td>
            
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EditIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click="abrirModal('editar', datos)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2 cursor-pointer"
                @click="eliminar(datos.id_ctas)"
              />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      <vs-popup :title="titulomodal" :active.sync="activarmodal">
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full">
            <vs-select
              placeholder="Seleccionar banco"
              class="selectExample w-full"
              label="Banco"
              vs-multiple
              autocomplete
              v-model="bansel"
            >
              <vs-select-item value="1" text="Cuenta Corriente" />
              <vs-select-item value="2" text="Cuenta Ahorros" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/2 w-full">
            <vs-input class="inputx mb-3 w-full" name="cod" label="Codigo" v-model="codcta" />
          </div>
          <div class="vx-col sm:w-1/2 w-full">
            <vs-input class="inputx mb-3 w-full" name="cod" label="Nombre de Cuenta" v-model="nomcta" />
          </div>
          <div class="vx-col sm:w-1/2 w-full">
            <vs-select
              placeholder="Selecciona moneda"
              class="selectExample w-full"
              label="Moneda"
              vs-multiple
              autocomplete
              v-model="id_moneda"
            >
              <vs-select-item
                v-for="data in monedas"
                :key="data.id_moneda"
                :value="data.id_moneda"
                :text="data.nomb_moneda"
              />
            </vs-select>
          </div>
          <div class="vx-col sm:w-full w-full">
            <vs-select
              placeholder="Seleccionar grupo"
              class="selectExample w-full"
              label="Grupo"
              vs-multiple
              autocomplete
              v-model="id_grupo"
            >
              <vs-select-item
                v-for="data in grupos"
                :key="data.id_grupo"
                :value="data.id_grupo"
                :text="data.nomb_grupo"
              />
            </vs-select>
          </div>
        </div>
        <div class="vx-col w-full mt-6">
          <vs-button color="success" type="filled" @click="guardar()" v-if="!id">GUARDAR</vs-button>
          <vs-button color="success" type="filled" @click="editar()" v-else>GUARDAR</vs-button>
          <vs-button color="danger" type="filled" @click="activarmodal=false">CANCELAR</vs-button>
        </div>
      </vs-popup>
      <vs-popup :title="titulocaja" :active.sync="modalcaja">
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4 w-3/5"
            v-model="buscarcaja"
            @keyup="listarcaja(1,buscarcaja)"
            v-bind:placeholder="i18nbuscar"
          />
          <vs-button class="btnx" type="filled" @click="abrirModalcaja()">Agregar</vs-button>
        </div>
        <vs-table stripe :data="contenidocaja">
        <template slot="thead">
          <vs-th>No.Cuenta</vs-th>
          <vs-th>Tipo Cuenta</vs-th>
          <vs-th>Moneda</vs-th>

          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="datos in data" :key="datos.id_ctas">
            <vs-td>{{datos.cuenta_contable}}</vs-td>
            <vs-td v-if="datos.descrip_caja">{{datos.descrip_caja }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.grupo_caja">{{datos.grupo_caja}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                icon="EditIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click="leercaja(datos.id_caja)"
              />
              <feather-icon
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2 cursor-pointer"
                @click="eliminarcaja(datos.id_caja)"
              />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      <vs-popup :title="titulomodal"  :active.sync="modaleliminarcaja">
        <input v-model="id_caja" hidden/>
      <p>Desea eliminar Este reguistro</p>
      <div class="vx-row">
      <div class="vx-col w-full">
            <vs-button color="warning" type="filled" @click="acceptAlertCaja(id_caja)">BORRAR</vs-button>
      </div>
      </div>
      </vs-popup>
      <vs-popup  :title="titulocaja" :active.sync="activecaja">
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full">
            <vs-input class="inputx mb-3 w-full" name="cod" label="Descripcion" v-model="descrip_caja" />
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6" >
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="cuenta_contable" :value="idContable" disabled/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <!-- -->
                  <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6" >
          <vs-select
              placeholder="Selecciona moneda"
              class="selectExample w-full"
              label="Moneda"
              vs-multiple
              autocomplete
              v-model="id_moneda2"
            >
              <vs-select-item
                v-for="data in monedas2"
                :key="data.id_moneda"
                :value="data.id_moneda"
                :text="data.nomb_moneda"
              />
            </vs-select>
          </div>
        </div>
        <div class="vx-col w-full mt-6">
          <vs-button color="success" type="filled" @click="guardarcaja()" v-if="!idrecupera">GUARDAR</vs-button>
          <vs-button color="success" type="filled" @click="editarcaja()" v-else>GUARDAR</vs-button>
          <vs-button color="danger" type="filled" @click="activecaja=false">CANCELAR</vs-button>
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
           
            <!--
            <vs-pagination :total="pagination.count"
              :max="7"
              v-model="pagina"
              @change="listar(pagina,buscar,criterio,cantidadp)"
              prev-icon="arrow_back"
              next-icon="arrow_forward"
            ></vs-pagination>-->
          </div>
        </vs-popup>
      </vs-popup>
      </vs-popup>
    </vx-card>
  </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");
export default {
  components: {
    AgGridVue
  },
  data() {
    return {
      pagina: 1,
      buscar: "",
      activarmodal: false,
      contenido:[],
      i18nbuscar:this.$t("i18nbuscar"), 
      titulomodal:"",

      id: null,
      id_empresa:null,
      codcta: "",
      nomcta: "",
      id_moneda: null,
      refcon: "",
      bansel: "",
      id_grupo: null,

      empresas: [],
      monedas: [],
      grupos: [],
      //modal caja chica
      titulocaja:"Caja Chica",
      modalcaja:false,
       buscarcaja: "",
       contenidocaja:[],
       i18nbuscarcaja:this.$t("i18nbuscar"), 
       //form caja chica
       idrecupera:null,
       activecaja:false,
       descrip_caja:"",
       cuenta_contable:"",
       id_moneda2:"",
       ctacontable:"",
       idContable:"",
       activePrompt3:false,
       cuentaarray3:[],
       monedas2:[],
       id_caja:"",
       modaleliminarcaja:false
    };
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
  methods: {
    verCaja(){
      this.modalcaja=true;
    },
    handleSelected3(tr){
      this.cuenta_contable=`${tr.codcta}`,
      this.activePrompt3=false
    },
    listar(page, buscar) {
      let me = this;
      var url =
        "/api/cuentas/" + this.usuario.id_empresa + "?page=" + page + "&buscar=" + buscar;
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
    leercaja(idcaja){
      if(idcaja){
        this.idrecupera = idcaja;
        this.activecaja=true;
        this.titulocaja="Editar Caja"
       // console.log("hola"+this.idrecupera);
        var url = "/api/abrircaja/"+idcaja;
        axios.put(url).then( res => {
          let data = res.data[0];
          this.descrip_caja = data.descrip_caja;
          this.cuenta_contable = data.cuenta_contable;
          this.id_moneda2=data.id_moneda;
          //this.fecult = data.fecult;
        }).catch( err => {
          console.log(err);
        });
      }else{
        this.idrecupera = null;
        
      }
    },
    eliminarcaja(idcaja){
      this.modaleliminarcaja=true,
      this.id_caja=idcaja
    },
    acceptAlertCaja(parameters){
      console.log("2", parameters);
      this.modaleliminarcaja=false;
      axios.delete("/api/eliminarcaja/" + parameters);
       this.$vs.notify({
          color: "danger",
          title: "Caja Eliminada  ",
          text: "La Caja selecionada fue eliminada con exito"
      });
      this.listarcaja(1,this.buscarcaja);
    },
    listarcaja(pagecaja, buscarcaja) {
      let me = this;
      var url =
        "/api/caja/" + this.usuario.id_empresa + "?page=" + pagecaja + "&buscar=" + buscarcaja;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenidocaja = respuesta.recupera;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    abrirModalcaja(){
      this.activecaja=true,
      this.titulocaja="Agregar Caja"
      this.idrecupera=null,
      this.descrip_caja="",
      this.cuenta_contable="",
      this.id_moneda=""
    },
    abrirModal(tipo,dato){
      switch(tipo){
        case "agregar" : {
          this.titulomodal = "Agregar plan de cuentas";
          this.activarmodal = true;
          this.id = null;
          this.id_empresa = null;
          this.codcta = "";
          this.nomcta = "";
          this.id_moneda = null;
          this.refcon = "";
          this.bansel = "";
          this.id_grupo = null;
          break;
        }
        case "editar" : {
          this.titulomodal = "Editar plan de cuentas"
          this.activarmodal = true;
          this.id = dato.id_ctas;
          this.id_empresa = dato.id_empresa;
          this.codcta = dato.codcta;
          this.nomcta = dato.nomcta;
          this.id_moneda = dato.id_moneda;
          this.refcon = dato.refcon;
          this.bansel = dato.bansel;
          this.id_grupo = dato.id_grupo;
          break;  
        }
      }
    },
    listarempresas() {
      axios.get("/api/traerempresa").then(res => {
        this.empresas = res.data;
      });
    },
    listarmonedas() {
      axios.get("/api/traermoneda").then(res => {
        this.monedas = res.data;
      });
    },
    listarmonedas2() {
      axios.get("/api/traermoneda").then(res => {
        this.monedas2 = res.data;
      });
    },
    listargrupos() {
      axios.get("/api/traergrupos").then(res => {
        this.grupos = res.data;
      });
    },
    guardarcaja(){
       axios.post("/api/agregarcaja", {
         descrip_caja:this.descrip_caja,
         cuenta_contable:this.cuenta_contable,
         id_moneda:this.id_moneda2,
         id_empresa:this.usuario.id_empresa
       }).then(res =>{
         this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
            });
            this.activecaja=false;
            this.listarcaja(1,this.buscarcaja);
       }).catch(err =>{});
    },
    editarcaja(){
      axios.put("/api/actualizarcaja", {
         id:this.idrecupera,
         descrip_caja:this.descrip_caja,
         cuenta_contable:this.cuenta_contable,
         id_moneda:this.id_moneda2,

       }).then(res =>{
         this.$vs.notify({
              title: "Registro Editado",
              text: "Registro Editado exitosamente",
              color: "success"
            });
            this.activecaja=false;
            this.listarcaja(1,this.buscarcaja);
       }).catch(err =>{});
    },
    guardar() {
      axios.post("/api/agregarcuentas", {
        codcta: this.codcta,
        nomcta: this.nomcta,
        id_moneda: this.id_moneda,
        bansel: this.bansel,
        id_grupo: this.id_grupo,
        id_empresa: this.usuario.id_empresa
      }).then(res => {
          if (res.data != "existe") {
            this.activarmodal = false;
            this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
            });
            this.listar(1, this.buscar);
          } else {
            this.$vs.notify({
              title: "Error al Guardar",
              text: "Este código de cuenta ya existe",
              color: "danger"
            });
          }
        });
    },
    editar() {
      axios.put("/api/actualizarcta", {
        id: this.id,
        codcta: this.codcta,
        nomcta: this.nomcta,
        id_moneda: this.id_moneda,
        bansel: this.bansel,
        id_grupo: this.id_grupo,
        fecult: this.fecult,
        id_empresa: this.usuario.id_empresa
      }).then(res => {
        this.activarmodal = false;
        this.$vs.notify({
          title: "Registro Editado",
          text: "Registro Editado exitosamente",
          color: "success"
        });
        this.listar(1, this.buscar);
      });
    },
    eliminar(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: `¿Desea Elimnar este registro?:`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.eliminarreg,
        parameters: id
      });
    },
    eliminarreg(parameters) {
      axios.delete("/api/eliminarcta/" + parameters);
      this.$vs.notify({
        color: "danger",
        title: "Cuenta Eliminada  ",
        text: "La Cuenta selecionada fue eliminada con exito"
      });
      this.listar(1, this.buscar);
    },
  },
  mounted() {
    this.listar(1, this.buscar);
    this.listarcaja(1,this.buscarcaja);
    this.listarempresas();
    this.listarmonedas();
    this.listarmonedas2();
    this.listargrupos();
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
  width: 1000px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
</style>