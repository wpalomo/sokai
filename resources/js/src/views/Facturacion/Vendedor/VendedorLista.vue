<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <!-- ITEMS PER PAGE -->
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
          <vs-dropdown vs-trigger-click class="cursor-pointer" v-if="pagination.total>=1">
            <div
              class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
            >
              <span
                class="mr-2"
              >{{ pagination.from }} - {{ pagination.to }} de {{ pagination.total }}</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>
            <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
            <vs-dropdown-menu>
              <vs-dropdown-item @click="listar(pagina,buscar,10)">
                <span>10</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="listar(pagina,buscar,20)">
                <span>20</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="listar(pagina,buscar,50)">
                <span>50</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="listar(pagina,buscar,100)">
                <span>100</span>
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
          <vs-dropdown vs-trigger-click class="cursor-pointer" v-else>
            <div
              class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
            >
              <span class="mr-2">Sin registros</span>
            </div>
          </vs-dropdown>
        </div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup.enter="listar(1,buscar,cantidadp)"
            v-bind:placeholder="i18nbuscar"
          />
          <!--boton de herramientas-->
          <div class="dropdown-button-container mr-3">
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="settings" style="border-radius: 5px;"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" divider>Link</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider >Link</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider >Link</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
          <!--Fin deboton de herramientas-->
          <div class="dropdown-button-container">
            <vs-button class="btnx" type="filled"  to="/facturacion/vendedor/agregar">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" @click="importar=true">Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" @click="exportar=true">Exportar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>
      <vs-table stripe :data="contenido">
        <template slot="thead">
          <vs-th>Código</vs-th>
          <vs-th>Nombre</vs-th>
          <vs-th>Email</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
           <vs-tr :key="datos.id_vendedor" v-for="datos in data">
           <vs-td v-if="datos.codigo_vendedor">{{datos.codigo_vendedor}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td v-if="datos.nombre_vendedor">{{datos.nombre_vendedor}}</vs-td>
          <vs-td v-else>-</vs-td>
         <vs-td v-if="datos.email_vendedor">{{datos.email_vendedor}}</vs-td>
          <vs-td v-else>-</vs-td>

            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="pointer"
                @click.stop="editar(datos.id_vendedor)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2 pointer"
                @click.stop="eliminarVendedor(datos.id_vendedor)"
              />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      <!--
      <vs-pagination
        v-if="pagination.count"
        class="mt-3"
        :total="pagination.count"
        v-model="pagina"
        @change="listar(pagina,buscar,cantidadp)"
        prev-icon="arrow_back"
        next-icon="arrow_forward"
        :max="6"
      ></vs-pagination>
      -->
    </vx-card>

    

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
import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue"

const axios = require("axios");
export default {
  components: {
    AgGridVue,
    ImportExcel,
    // vSelect
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
      //excel import
      file:[],
      tableData: [],
      header: [],
      sheetName: "",
      //mapeo de datos
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
      cantidadp: 1000000,
          
      //buscador
      buscar: "",

      //otros valores
      gridApi: null,
      contenido: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //ERRORES
      error: 0,
      erroremail: [],
      //Datos para la importaciond de archivos
      importar: false,
      nombreimportar: "",

      //Datos para la Exportacion de archivos
      exportar: false,
      nombreexportar: "",
      formatoexportar: ["xlsx", "csv", "txt"],
      cellancho: true,
      tipoformatoexportar: "xlsx",
      //campos que existen para exportar
      campos: [
        "id_vendedor",
        "codigo_vendedor",
        "nombre_vendedor",
        "email_vendedor",
        "id_empresa"
       
      ],
      //campos elegidos a exportar
      indexs: [
         "id_vendedor",
        "codigo_vendedor",
        "nombre_vendedor",
        "email_vendedor",
        "id_empresa"
        
      ],
      //cabezera que imprimira en el excel
      cabezera: [
         "id_vendedor",
        "Código vendedor",
        "Nombre vendedor",
        "Email vendedor",
        "id_empresa"
      ]
    };
  },
  methods: {
    importardatos(){
  
      axios.post("/api/importarexcel",{
        file:this.file,

      })


    },
 
    cancelar() {
      this.$router.push("/facturacion/vendedor");
    },

    //listan clientes
    /*
    listar(page, buscar, cantidadp) {
      let me = this;
      var url =
        "/api/listarvendedorcliente?page=" +
        page +
        "&buscar=" +
        buscar +
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
    */
    listar(page, buscar) {
      var url =
        "/api/listarvendedorcliente/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios.get(url).then(res => {
        this.contenido = res.data.recupera;
      });
    },
 
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

    //importar archivos
    loadDataInTable({ results, header, meta }) {
      this.header = header
      this.tableData = results
      this.sheetName = meta.sheetName
    },
   
    
    //agregar cliente
    crear() {
      this.$router.push("/facturacion/vendedor/agregar");
    },
    //editar vendedor
    editar(id) {
      this.$router.push(`/facturacion/vendedor/${id}/editar`);
    },
  
    //eliminar vendedor
    eliminarVendedor(id) {
      //metodo eliminar
      axios
        //Envia id
        .delete("/api/eliminarvendedorcliente/" + id);
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: "¿Desea Elimnar este registro?",
        accept: this.acceptAlert
      });
    },
    acceptAlert() {
      this.$vs.notify({
        color: "danger",
        title: "Vendedor Eliminado  ",
        text: "El vendedor selecionado fue eliminado con exito"
      });
      this.listar(1, this.buscar, this.cantidadp);
    }
  },
  mounted() {
    this.listar(1, this.buscar, this.cantidadp);
    
    
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
  width: 1060px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
</style>
