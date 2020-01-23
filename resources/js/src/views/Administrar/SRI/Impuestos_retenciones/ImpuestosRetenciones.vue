<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
          <!--<vs-button class="mb-4 md:mb-0" @click="enviar()">Agregar</vs-button>-->
        </div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />

          <!--<vs-button class="mb-4 md:mb-0" @click="gridApi.exportDataAsCsv()">Export as CSV</vs-button>-->
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" @click="agregarRet()">Agregar</vs-button>
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
          <!--<vs-th>Codigo.</vs-th>-->
          <vs-th>Descripcion</vs-th>
          <vs-th>%</vs-th>
          <vs-th>Tipo</vs-th>
          <vs-th>Cod SRI</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id" v-for="datos in data">
            <!--<vs-td>{{datos.cod_retencion }}</vs-td>-->
            <vs-td v-if="datos.descrip_retencion">{{datos.descrip_retencion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.porcen_retencion">{{datos.porcen_retencion }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.tipo_retencion">{{datos.tipo_retencion }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.cod_imp">{{datos.cod_imp }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EditIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click.stop="edit(datos.id_retencion)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="TrashIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                @click.stop="eliminar(datos.id_retencion)"
              />
              <!--@click.stop="eliminar(datos.id_empresa)"-->
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      <vs-popup :title="titulomodal" :active.sync="activePrompt">
        <div class="vx-row">
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-input class="w-full" label="Codigo" v-model="cod_retencion" maxlength="7" />
          </div>
          <div class="vx-col sm:w-4/5 w-full mb-6">
            <vs-input class="w-full" label="Descripcion" v-model="descrip_retencion" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-select
              placeholder="Seleccione la retención"
              class="selectExample w-full"
              label="Tipo Retencion"
              vs-multiple
              autocomplete
              v-model="tipo_retencion"
            >
              <vs-select-item value="Retencion Fuente Compra" text="Retencion Fuente Compra" />
              <vs-select-item value="Retencion Iva Compra" text="Retencion Iva Compra" />
              <vs-select-item value="Retencion Fuente Venta" text="Retencion Fuente Venta" />
              <vs-select-item value="Retencion Iva Venta" text="Retencion Iva Venta" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input
              class="w-full"
              @change="getImp(porcen_retencion)"
              @keypress="solodecimales"
              label="Porcentaje"
              v-model="porcen_retencion"
            />
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-select
              class="selectExample w-full"
              placeholder="Seleccione el IVA"
              label="Tipo IVA"
              vs-multiple
              autocomplete
              v-model="tipoiva_retencion"
            >
              <vs-select-item value="Credito Tributario" text="Credito Tributario" />
              <vs-select-item value="Activos fijos" text="Activos fijos" />
              <vs-select-item
                value="No aplica Credito Tributario"
                text="No aplica Credito Tributario"
              />
            </vs-select>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="cta_contable" />
              <template slot="append">
                <div class="append-text btn-addon">
                  <!-- -->
                  <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="Seleccione una moneda"
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
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="Seleccione un código SRI"
              class="selectExample w-full"
              label="Codigo SRI"
              vs-multiple
              autocomplete
              v-model="id_impuesto"
            >
              <vs-select-item
                v-for="data in imps"
                :key="data.id_imp"
                :value="data.id_imp"
                :text="data.cod_imp"
              />
            </vs-select>
          </div>
        </div>
        <div class="vx-col w-full">
          <vs-button color="success" type="filled" @click="guardarRet()" v-if="!idrecupera">GUARDAR</vs-button>
          <vs-button color="success" type="filled" @click="editarRet()" v-else>GUARDAR</vs-button>
          <vs-button color="warning" type="filled" @click="vaciarRet()">BORRAR</vs-button>
          <vs-button color="danger" type="filled" @click="cancelarRet()">CANCELAR</vs-button>
        </div>
        <vs-popup title="Plan Cuentas" :active.sync="activePrompt3">
          <div class="con-exemple-prompt">
            <vs-input
              class="mb-4 md:mb-0 mr-4 w-full"
              v-model="buscar3"
              @keyup="listar3(1,buscar3,criterio3,cantidadp3)"
              v-bind:placeholder="i18nbuscar3"
            />
            <vs-table stripe v-model="cuentaarray3" @selected="handleSelected3" :data="contenido3">
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
      </vs-popup>
    </vx-card>
  </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");
import Vue from "vue";

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
      cantidadp: 20,
      offset: 0,
      //buscador
      buscar: "",
      criterio: "descrip_retencion",
      //otros valores
      gridApi: null,
      contenido: [],
      activePrompt: false,
      val: "",
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //modal imp
      activePrompt: false,
      titulomodal: "",
      idrecupera: null,
      cod_retencion: "",
      descrip_retencion: "",
      tipo_retencion: "",
      tipoiva_retencion: "",
      porcen_retencion: "1",
      cta_contable: "",
      id_moneda: "",
      id_impuesto: "",
      //modal plan ctas
      activePrompt3: false,
      buscar3: "",
      pagina3: 1,
      cantidadp3: 50,
      criterio3: "codcta",
      pagination3: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      cuentaarray3: [],
      i18nbuscar3: this.$t("i18nbuscar"),
      contenido3: [],
      //traer monedas
      monedas: [],
      //traer imp
      imps: []
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
        res = this.$store.state.Roles[6].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[6].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[6].eliminar;
      }
      return res;
    }
  },
  methods: {
    handleSelected3(tr) {
      (this.cta_contable = `${tr.codcta}`), (this.activePrompt3 = false);
    },
    agregarRet() {
      (this.activePrompt = true),
        (this.titulomodal = "Agregar"),
        (this.idrecupera = null),
        (this.cod_retencion = ""),
        (this.descrip_retencion = ""),
        (this.tipo_retencion = ""),
        (this.tipoiva_retencion = ""),
        (this.cta_contable = ""),
        (this.porcen_retencion = ""),
        (this.id_moneda = ""),
        (this.id_impuesto = "");
    },
    guardarRet() {
      axios
        .post("/api/agregarretencion", {
          cod_retencion: this.cod_retencion,
          descrip_retencion: this.descrip_retencion,
          tipo_retencion: this.tipo_retencion,
          tipoiva_retencion: this.tipoiva_retencion,
          cta_contable: this.cta_contable,
          id_moneda: this.id_moneda,
          id_impuesto: this.id_impuesto,
          porcen_retencion: this.porcen_retencion,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.activePrompt = false;
          this.$vs.notify({
            title: "Registro Guardado",
            text: "Registro Guardado exitosamente",
            color: "success"
          });
          this.listar(1, this.buscar);
        })
        .catch(err => {});
    },
    edit(id) {
      if (id) {
        this.idrecupera = id;
        this.activePrompt = true;
        this.titulomodal = "Editar";
        // console.log("hola"+this.idrecupera);
        var url = "/api/abrirretencion/" + id;
        axios
          .put(url)
          .then(res => {
            let data = res.data[0];
            this.cod_retencion = data.cod_retencion;
            this.descrip_retencion = data.descrip_retencion;
            this.tipo_retencion = data.tipo_retencion;
            this.tipoiva_retencion = data.tipoiva_retencion;
            this.cta_contable = data.cta_contable;
            this.id_moneda = data.id_moneda;
            this.id_impuesto = data.id_impuesto;
            this.porcen_retencion = data.porcen_retencion;
            //this.fecult = data.fecult;
          })
          .catch(err => {
            console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
    },
    editarRet() {
      axios
        .put("/api/actualizarretencion", {
          id: this.idrecupera,
          cod_retencion: this.cod_retencion,
          descrip_retencion: this.descrip_retencion,
          tipo_retencion: this.tipo_retencion,
          tipoiva_retencion: this.tipoiva_retencion,
          cta_contable: this.cta_contable,
          id_moneda: this.id_moneda,
          id_impuesto: this.id_impuesto,
          porcen_retencion: this.porcen_retencion,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.activePrompt = false;
          this.$vs.notify({
            title: "Registro Editado",
            text: "Registro Editado exitosamente",
            color: "success"
          });
          this.listar(1, this.buscar);
        })
        .catch(err => {});
    },
    vaciarRet() {
      (this.cod_retencion = ""),
        (this.descrip_retencion = ""),
        (this.tipo_retencion = ""),
        (this.tipoiva_retencion = ""),
        (this.cta_contable = ""),
        (this.id_moneda = ""),
        (this.id_impuesto = ""),
        (this.porcen_retencion = "");
    },
    cancelarRet() {
      (this.activePrompt = false),
        (this.cod_retencion = ""),
        (this.descrip_retencion = ""),
        (this.tipo_retencion = ""),
        (this.tipoiva_retencion = ""),
        (this.cta_contable = ""),
        (this.id_moneda = ""),
        (this.id_impuesto = ""),
        (this.porcen_retencion = "");
    },
    eliminar(cd) {
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
      axios.delete("/api/eliminarretencion/" + parameters);
      this.$vs.notify({
        color: "danger",
        title: "Reguistro Eliminado  ",
        text: "El reguistro selecionado fue eliminado con exito"
      });
      this.listar(1, this.buscar);
    },
    listar(page, buscar) {
      let me = this;
      var url =
        "/api/retencion/" +
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
          //console.log(error);
          this.$vs.notify({
            title: "Error al Guardar",
            text: "Verifique bien sus datos al momento de guardar",
            color: "danger"
          });
        });
    },
    listar3(page3, buscar3, criterio3, cantidadp3) {
      let me = this;
      var url ="/api/cuentas/"+this.usuario.id_empresa+"?page=" +page3 +"&buscar=" +buscar3 +"&criterio=" +criterio3 +"&cantidadp=" +cantidadp3;
      axios.get(url).then(function(response) {
          var respuesta = response.data;
          me.contenido3 = respuesta.recupera;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getMonedas() {
      axios.get("/api/traermonedaret").then(
        function(response) {
          this.monedas = response.data;
        }.bind(this)
      );
    },
    getImp() {
      axios
        .get("/api/traerimpret", {
          params: {
            porcen_imp: this.porcen_retencion
          }
        })
        .then(
          function(response) {
            if (response.data) {
              this.imps = response.data;
            } else {
              this.id_impuesto = 0;
            }
          }.bind(this)
        );
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
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    }
  },
  mounted() {
    this.listar(1, this.buscar);
    this.listar3(1, this.buscar3, this.criterio3, this.cantidadp3);
    this.getMonedas();
    this.getImp();
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
  width: 1050px !important;
}
.peque .vs-popup {
  width: 400px !important;
}
</style>