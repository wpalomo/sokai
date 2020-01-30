<template>
  <vx-card class="mt-10">
    <div class="flex flex-wrap justify-between items-center mb-3">
      <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
      </div>
      <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
        <vs-input
          class="mb-4 md:mb-0 mr-4"
          v-model="buscar"
          @keyup="listarbodega(1,buscar)"
          v-bind:placeholder="i18nbuscar"
        />
        <div class="dropdown-button-container" v-if="crearrol">
          <vs-button class="btnx" type="filled" @click="abrir('agregar')">Agregar</vs-button>
          <vs-dropdown>
            <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
            <vs-dropdown-menu style="width:13em;">
              <vs-dropdown-item class="text-center" divider>Importar registros</vs-dropdown-item>
              <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
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
        <vs-th>Serie Factura</vs-th>
        <vs-th>Ubicación</vs-th>
        <vs-th>Dirección</vs-th>
        <vs-th>Telefono</vs-th>
        <vs-th>Opciones</vs-th>
      </template>
      <template slot-scope="{data}">
        <vs-tr :key="datos.codigo" v-for="datos in data">
          <vs-td v-if="datos.codigo">
            <a href="javascript:void(0)" @click="activePrompt = true">{{datos.codigo}}</a>
          </vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td v-if="datos.serie_factura">{{datos.serie_factura}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td v-if="datos.ubicacion">{{datos.ubicacion}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td v-if="datos.direccion">{{datos.direccion}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td v-if="datos.telefono">{{datos.telefono}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td class="whitespace-no-wrap">
            <vx-tooltip text="Editar Bodega" position="top" style="display: inline-flex;">
            <feather-icon
              v-if="editarrol"
              icon="EditIcon"
              svgClasses="w-5 h-5 hover:text-primary stroke-current"
              class="pointer"
              @click.stop="abrir('editar',datos)"
            /></vx-tooltip>
            <vx-tooltip text="Borrar Bodega" position="top" style="display: inline-flex;">
            <feather-icon
              v-if="eliminarrol"
              icon="TrashIcon"
              svgClasses="w-5 h-5 hover:text-danger stroke-current"
              class="ml-2 pointer"
              @click.stop="ideliminar=datos.id_bodega,eliminar=true"
            /></vx-tooltip>
            <vx-tooltip text="Gestionar Bodega" position="top" style="display: inline-flex;">
            <feather-icon
              icon="PackageIcon"
              svgClasses="w-5 h-5 hover:text-danger stroke-current"
              class="ml-2 pointer"
              @click="gestionbodega(datos.id_bodega)"
            /></vx-tooltip>
          </vs-td>
        </vs-tr>
      </template>
    </vs-table>
    <!--Popup Agregar Bodega-->
    <vs-popup title="Agregar Bodega" :active.sync="popupActive">
      <div class="vx-col sm:w-full w-full mb-6">
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="Código:" v-model="codigo" />
          </div>
          <div class="vx-col sm:w-2/3 w-full mb-6">
            <vs-input class="w-full" label="Nombre:" v-model="nombre" />
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Responsable de Bodega:" v-model="serfactura" />
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Ubicación:" v-model="ubicacion" />
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Dirección:" v-model="direccion" />
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Teléfono:" v-model="telefono" />
          </div>
          <div class="vx-col sm:w-full w-full">
            <vs-button
              color="success"
              type="filled"
              @click="guardarbodega()"
              v-if="id_bodega==null"
            >Guardar</vs-button>
            <vs-button color="success" type="filled" @click="editarbodega()" v-else>Guardar</vs-button>
            <vs-button color="danger" type="filled" @click="popupActive=false">Cancelar</vs-button>
          </div>
        </div>
      </div>
    </vs-popup>

    <!-- Eliminar popup-->
    <vs-popup title="eliminar registro" :class="'peque'" :active.sync="eliminar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col sm:w-full w-full mb-6 text-center">
              <label class="text-center">
                Esta seguro que desea eliminar este registro
                <br />¡Se eliminará de forma permanente!
              </label>
            </div>
            <div class="vx-col sm:w-full w-full text-center">
              <vs-button
                color="danger"
                type="filled"
                @click="eliminarbodega(ideliminar)"
              >Eliminar bodega</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
  </vx-card>
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
      cantidadp: 100000,
      offset: 3,
      //buscador
      buscar: "",
      //otros valores
      gridApi: null,
      contenido: [],
      eliminar: false,
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //popup agregar bodega
      popupActive: false,
      //popup gestion bodega
      popupBodega: false,
      //variables bodega
      id_bodega: null,
      codigo: "",
      nombre: "",
      serfactura: "",
      ubicacion: "",
      direccion: "",
      telefono: ""
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
        res = this.$store.state.Roles[16].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[16].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[16].eliminar;
      }
      return res;
    }
  },
  methods: {
    listarbodega(page, buscar) {
      var url = "/api/bodega/" + this.usuario.id_empresa + "/" + this.usuario.id_establecimiento +  "?page=" + page + "&buscar=" + buscar;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenido = respuesta.recupera;
      });
    },
    guardarbodega() {
      axios
        .post("/api/guardarbodega", {
          codigo: this.codigo,
          nombre: this.nombre,
          serie: this.serfactura,
          ubicacion: this.ubicacion,
          direccion: this.direccion,
          telefono: this.telefono,
          id_empresa: this.usuario.id_empresa,
          id_establecimiento: this.usuario.id_establecimiento
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          (this.popupActive = false),
            this.listarbodega(1, this.buscar);
        })
        .catch(err => {});
    },
    abrir(tipo, datos) {
      this.popupActive = true;
      switch (tipo) {
        case "agregar": {
          this.id_bodega = null;
          this.codigo = "";
          this.nombre = "";
          this.serfactura = "";
          this.ubicacion = "";
          this.direccion = "";
          this.telefono = "";
          break;
        }
        case "editar": {
          this.id_bodega = datos.id_bodega;
          this.codigo = datos.codigo;
          this.nombre = datos.nombre;
          this.serfactura = datos.serie_factura;
          this.telefono = datos.telefono;
          this.ubicacion = datos.ubicacion;
          this.direccion = datos.direccion;
          break;
        }
      }
    },
    editarbodega() {
      axios
        .post("/api/editarbodega", {
          id: this.id_bodega,
          codigo: this.codigo,
          nombre: this.nombre,
          serie: this.serfactura,
          ubicacion: this.ubicacion,
          direccion: this.direccion,
          telefono: this.telefono,
          id_empresa: this.usuario.id_empresa,
          id_establecimiento: this.usuario.id_establecimiento
        })
        .then(res => {
          this.$vs.notify({
            title: "Edición Exitosa",
            text: "Registro editado con éxito",
            color: "success"
          });
          this.popupActive = false;
          this.listarbodega(1, this.buscar);
          this.id_bodega = null;
        })
        .catch(err => {});
    },
    eliminarbodega(id) {
      axios.delete("/api/eliminarbodega/" + id);
      this.$vs.notify({
        title: "Registro eliminado",
        text: "Este registro ha sido eliminado exitosamente",
        color: "success"
      });
      this.eliminar = false;
      this.listarbodega(1, this.buscar, this.cantidadp);
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    gestionbodega(id) {
        //this.$router.push("/inventario/bodega/gestionbodega" + id);
        this.$router.push(`/inventario/bodega/${id}/gestionar`);
    }
  },
  mounted() {
    this.listarbodega(1, this.buscar);
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
.vs-popup {
  width: 1060px !important; 
}
.peque .vs-popup { 
  width: 600px !important;
}
.full .vs-popup {
  width: 1060px !important;
}
/*@media screen and (min-width:1200px){
  .vs-popup{
    margin-left: 20%!important;
  }
}*/
</style>