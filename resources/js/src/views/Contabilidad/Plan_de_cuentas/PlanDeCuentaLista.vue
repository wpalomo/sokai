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
                  @click="importar=true"
                >Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" @click="exportar=true">Exportar registros</vs-dropdown-item>
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
            <div v-show="error" v-if="!codcta">
              <span class="text-danger" v-for="err in errorcodcta" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full">
            <vs-input
              class="inputx mb-3 w-full"
              name="cod"
              label="Nombre de Cuenta"
              v-model="nomcta"
            />
            <div v-show="error" v-if="!nomcta">
              <span class="text-danger" v-for="err in errornomcta" :key="err" v-text="err"></span>
            </div>
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
            <div v-show="error" v-if="!id_moneda">
              <span class="text-danger" v-for="err in errorid_moneda" :key="err" v-text="err"></span>
            </div>
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
            <div v-show="error" v-if="!id_grupo">
              <span class="text-danger" v-for="err in errorid_grupo" :key="err" v-text="err"></span>
            </div>
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
                v-if="editarrol"
                icon="EditIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click.stop="leercaja(datos.id_caja)"
              />
                <feather-icon
                  icon="TrashIcon"
                  svgClasses="w-5 h-5 hover:text-danger stroke-current"
                  class="ml-2 cursor-pointer"
                  @click.stop="eliminarcaja(datos.id_caja)"
                />
                <!--svgClasses="w-5 h-5 hover:text-danger stroke-current"-->
              </vs-td>
            </vs-tr>
          </template>
        </vs-table>
        <vs-popup :title="titulomodal" :active.sync="modaleliminarcaja">
          <input v-model="id_caja" hidden />
          <p>Desea eliminar Este reguistro</p>
          <div class="vx-row">
            <div class="vx-col w-full">
              <vs-button color="warning" type="filled" @click="acceptAlertCaja(id_caja)">BORRAR</vs-button>
            </div>
          </div>
        </vs-popup>
        <vs-popup :title="titulocaja" :active.sync="activecaja">
          <div class="vx-row">
            <div class="vx-col sm:w-1/2 w-full">
              <vs-input
                class="inputx mb-3 w-full"
                name="cod"
                label="Descripcion"
                v-model="descrip_caja"
              />
              <div v-show="error" v-if="!descrip_caja">
              <span class="text-danger" v-for="err in errordescrip_caja" :key="err" v-text="err"></span>
            </div>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <label class="vs-input--label">Cuenta Contable</label>
              <vx-input-group class>
                <vs-input class="w-full" v-model="cuenta_contable" :value="idContable" disabled />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <!-- -->
                    <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
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
              <div v-show="error" v-if="!id_moneda2">
              <span class="text-danger" v-for="err in errorid_moneda2" :key="err" v-text="err"></span>
            </div>
            </div>
          </div>
          <div class="vx-col w-full mt-6">
           <vs-button color="success" type="filled" v-if="!idrecuperacaj" @click="guardarcaja()" >GUARDAR</vs-button>
            <vs-button color="success" type="filled" v-else @click="editarcaja()" >GUARDAR</vs-button>
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
    
    <!--Modal para importar excel-->
    <vs-popup title="Importar Excel" :active.sync="importar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <!---->
            <div class="vx-col sm:w-full w-full mb-6">
              <label class="vs-input--label">Subir Archivo</label>
              <div class="vx-col md:w-full w-full mb-6">
                <div style="display:none">
                  <input
                    :onSuccess="loadDataInTable"
                    type="file"
                    class="custom-file-input inputexcel"
                    @change="obtenerimagen"
                    accept=".XLSX, .CSV"
                  />
                </div>
                <div class="centimg vx-card input" @click="importarexcel()">
                  <img src="/images/upload.png" />
                  <div style="position:absolute;margin-top:60px;color:#000">Click para subir Archivo</div>
                </div>
              </div>
            </div>
            <vx-card v-if="tableData.length && header.length">
              <vs-table stripe pagination :max-items="10" search :data="tableData">
                <template slot="header">
                  <h4>{{ sheetName }}</h4>
                </template>

                <template slot="thead">
                  <vs-th :sort-key="heading" v-for="heading in header" :key="heading">{{ heading }}</vs-th>
                </template>

                <template slot-scope="{data}" @change="obtenerimagen">
                  <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td :data="col" v-for="col in data[indextr]" :key="col">{{ col }}</vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </vx-card>

            <div class="vx-col sm:w-full w-full mb-6">
              <vs-button color="success" @click="importardatos()">Subir Archivo</vs-button>
              <vs-button color="danger" type="filled" @click="importar=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
    <!--fin modal de exportar-->
    
    <!--Modal para exportar excel-->
    <vs-popup title="Exportar Excel" :active.sync="exportar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col sm:w-full w-full mt-5">
              <vs-input
                v-model="nombreexportar"
                placeholder="Nombre del archivo..."
                class="w-full"
              />
             
              <div class="flex mb-4">
                <span class="mr-4">Celda con ancho predefinido:</span>
                <vs-switch v-model="cellancho">Ancho de los campos del archivo</vs-switch>
              </div>
            
            </div>
            <div class="vx-col sm:w-full w-full mt-5">
              <vs-button color="success" type="filled" @click="exportardatos">Descargar Excel</vs-button>
              <vs-button color="danger" type="filled" @click="exportar=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
    <!--fin modal de exportar-->
  </div>
</template>
<script>
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");
export default {
  components: {
    AgGridVue,
    ImportExcel,
    vSelect
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
        res = this.$store.state.Roles[15].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[15].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[15].eliminar;
      }
      return res;
    }
  },
  data() {
    return {
      pagina: 1,
      buscar: "",
      activarmodal: false,
      contenido: [],
      i18nbuscar: this.$t("i18nbuscar"),
      titulomodal: "",

      id: null,
      id_empresa: null,
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
      titulocaja: "Caja Chica",
      modalcaja: false,
      buscarcaja: "",
      contenidocaja: [],
      i18nbuscarcaja: this.$t("i18nbuscar"),
      //form caja chica
      idrecuperacaj: null,
      activecaja: false,
      descrip_caja: "",
      cuenta_contable: "",
      id_moneda2: "",
      ctacontable: "",
      idContable: "",
      activePrompt3: false,
      cuentaarray3: [],
      monedas2: [],
      id_caja: "",
      modaleliminarcaja: false,
      //excel import
      file: "",
      tableData: [],
      header: [],
      sheetName: "",
      //Datos para la importaciond de archivos
      importar: false,
      //Datos para la Exportacion de archivos
      exportar: false,
      nombreexportar: "",
      formatoexportar: ["xlsx", "csv", "txt"],
      cellancho: true,
      tipoformatoexportar: "xlsx",
      //campos que existen para exportar
      campos: [
        "id_ctas ",
        "id_empresa ",
        "codcta ",
        "nomcta ",
        "id_moneda ",
        "refcon ",
        "bansel ",
        "fcrea ",
        "fmodifica ",
        "id_grupo "
      ],
      //campos elegidos a exportar
      indexs: [
        "id_ctas ",
        "id_empresa ",
        "codcta ",
        "nomcta ",
        "id_moneda ",
        "refcon ",
        "bansel ",
        "fcrea ",
        "fmodifica ",
        "id_grupo "
      ],
      //cabezera que imprimira en el excel
      cabezera: [
        "id_ctas ",
        "id_empresa ",
        "codcta ",
        "nomcta ",
        "id_moneda ",
        "refcon ",
        "bansel ",
        "fcrea ",
        "fmodifica ",
        "id_grupo "
      ],
      //errores plan cuentas
      error:0,
      errorcodcta: [],
      errornomcta: [],
      errorid_moneda: [],
      errorrefcon: [],
      errorbansel: [],
      errorid_grupo: [],
      //errores caja
      errorcaja:0,
      errordescrip_caja: "",
      errorcuenta_contable: "",
      errorid_moneda2: "",
      errorctacontable: "",
      erroridContable: "",
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
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[8].crear;
      }
      return res;
    },
    editarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[8].editar;
      }
      return res;
    },
    eliminarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[8].eliminar;
      }
      return res;
    }
  },
  methods: {
    //exportar archivos

    exportardatos() {
        import("../../../vendor/Export2Excel").then(excel => {
        const list = this.contenido;
        const data = this.formatJson(this.indexs, list);
        excel.export_json_to_excel({
          header: this.cabezera,
          data,
          filename: this.nombreexportar,
          autoWidth: this.cellancho,
          bookType: this.tipoformatoexportar
        });
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v =>
        filterVal.map(j => {
          return v[j];
        })
      );
    },
      updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },

    //importar archivos
     loadDataInTable({ results, header, meta }) {
      this.header = header
      this.tableData = results
      this.sheetName = meta.sheetName
    },

    importarexcel() {
      $(".inputexcel").click();
    },

    importardatos() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file);
      axios
        .post("/api/importarplancuentaexcel", formData, {})
        .then(res => {
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar = false;
          this.listar(1, this.buscar);
        })
        .catch(err => {
          console.log(err);
        });
    },
    obtenerimagen(e) {
      let file = e.target.files[0];
      var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
      if (!allowedExtensions.exec(file.name)) {
        this.$vs.notify({
          title: "Tipo de archivo no compatible",
          text: "Formatos aceptados: .jpg, .jpeg, .png",
          color: "danger"
        });
        return;
      }
      this.file = file;
    },
    //fin importar
    verCaja() {
      this.modalcaja = true;
    },
    handleSelected3(tr) {
      (this.cuenta_contable = `${tr.codcta}`), (this.activePrompt3 = false);
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
          console.log(me.contenido);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    prueba(){
      con
      //this.titulomodal = "Agregar plan de cuentas";
      //this.activecaja = true;
    },
    leercaja(idcaja) {
        this.idrecuperacaj = idcaja;
        this.activecaja = true;
        this.titulocaja = "Editar Caja";
        // console.log("hola"+this.idrecuperacaj);
        var url = "/api/abrircaja/" + idcaja;
        axios
          .put(url)
          .then(res => {
            let data = res.data[0];
            this.descrip_caja = data.descrip_caja;
            this.cuenta_contable = data.cuenta_contable;
            this.id_moneda2 = data.id_moneda;
            //this.fecult = data.fecult;
          })
          .catch(err => {
            console.log(err);
          });
      
    },
    eliminarcaja(idcaja) {
      (this.modaleliminarcaja = true), (this.id_caja = idcaja);
    },
    acceptAlertCaja(parameters) {
      console.log("2", parameters);
      this.modaleliminarcaja = false;
      axios.delete("/api/eliminarcaja/" + parameters);
      this.$vs.notify({
        color: "danger",
        title: "Caja Eliminada  ",
        text: "La Caja selecionada fue eliminada con exito"
      });
      this.listarcaja(1, this.buscarcaja);
    },
    listarcaja(pagecaja, buscarcaja) {
      let me = this;
      var url =
        "/api/caja/" +
        this.usuario.id_empresa +
        "?page=" +
        pagecaja +
        "&buscar=" +
        buscarcaja;
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
    abrirModalcaja() {
      (this.activecaja = true), (this.titulocaja = "Agregar Caja");
      (this.idrecuperacaj = null),
        (this.descrip_caja = ""),
        (this.cuenta_contable = ""),
        (this.id_moneda = "");
    },
    abrirModal(tipo, dato) {
      switch (tipo) {
        case "agregar": {
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
        case "editar": {
          this.titulomodal = "Editar plan de cuentas";
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
    guardarcaja() {
      if(this.validarcaja()){
        return;
      }
      axios
        .post("/api/agregarcaja", {
          descrip_caja: this.descrip_caja,
          cuenta_contable: this.cuenta_contable,
          id_moneda: this.id_moneda2,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Guardado",
            text: "Registro Guardado exitosamente",
            color: "success"
          });
          console.log(res);
          this.activecaja = false;
          this.listarcaja(1, this.buscarcaja);
        })
        .catch(err => {
          console.log("eerr"+err);
        });
    },
    editarcaja() {
      axios
        .put("/api/actualizarcaja", {
          id: this.idrecuperacaj,
          descrip_caja: this.descrip_caja,
          cuenta_contable: this.cuenta_contable,
          id_moneda: this.id_moneda2
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Editado",
            text: "Registro Editado exitosamente",
            color: "success"
          });
          this.activecaja = false;
          this.listarcaja(1, this.buscarcaja);
        })
        .catch(err => {});
    },
    guardar() {
      if(this.validarplancuentas()){
        return;
      }
      axios
        .post("/api/agregarcuentas", {
          codcta: this.codcta,
          nomcta: this.nomcta,
          id_moneda: this.id_moneda,
          bansel: this.bansel,
          id_grupo: this.id_grupo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
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
      
      axios
        .put("/api/actualizarcta", {
          id: this.id,
          codcta: this.codcta,
          nomcta: this.nomcta,
          id_moneda: this.id_moneda,
          bansel: this.bansel,
          id_grupo: this.id_grupo,
          fecult: this.fecult,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
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
    validarplancuentas(){
      this.error=0;
      this.errorcodcta= [];
      this.errornomcta= [];
      this.errorid_moneda= [];
      this.errorrefcon= [];
      this.errorbansel= [];
      this.errorid_grupo= [];

      if(!this.codcta){
        this.errorcodcta.push("Campo Obligatorio");
        this.error=1
      }

      if(!this.nomcta){
        this.errornomcta.push("Campo Obligatorio");
        this.error=1
      }

      if(!this.id_moneda){
        this.errorid_moneda.push("Campo Obligatorio");
        this.error=1
      }

      if(!this.id_grupo){
        this.errorid_grupo.push("Campo Obligatorio");
        this.error=1
      }
      return this.error;
    },
    validarcaja(){
       this.errorcaja=0;
       this.errordescrip_caja= [];
       this.errorcuenta_contable= [];
       this.errorid_moneda2= [];
       this.errorctacontable= [];
       this.erroridContable= [];

      if(!this.descrip_caja){
        this.errordescrip_caja.push("Campo Obligatorio");
        this.errorcaja=1;
        console.log("error descripcion");
      }

      if(!this.id_moneda2){
        this.errorid_moneda2.push("Campo Obligatorio");
        this.errorcaja=1;
        console.log("errormoneda");
      }

      return this.errorcaja;
    },
  },
  mounted() {
    this.listar(1, this.buscar);
    this.listarcaja(1, this.buscarcaja);
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
input[type="”file”"]#nuestroinput {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}
label[for=" nuestroinput"] {
  font-size: 14px;
  font-weight: 600;
  color: #fff;
  background-color: #106ba0;
  display: inline-block;
  transition: all 0.5s;
  cursor: pointer;
  padding: 15px 40px !important;
  text-transform: uppercase;
  width: fit-content;
  text-align: center;
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
  height: 300px;
  height: 300px;
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
.demo-alignment > * {
  margin-right: 1.5rem;
  margin-top: 0.8rem;
}
</style>