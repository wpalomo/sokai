<template>
  <vx-card class="mt-10">
    <div class="flex flex-wrap justify-between items-center mb-3">
      <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
      <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
        <vs-input
          class="mb-4 md:mb-0 mr-4"
          v-model="buscar"
          @keyup="listarformula(1,buscar)"
          v-bind:placeholder="i18nbuscar"
        />
        <div class="dropdown-button-container" v-if="crearrol">
          <vs-button class="btnx" type="filled" to="/produccion/proceso-produccion/añadir">Agregar</vs-button>
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
        <vs-th>Nº de Orden</vs-th>
        <vs-th class="text-center">Detalle</vs-th>
        <vs-th class="text-center">Estado</vs-th>
        <vs-th class="text-center">Opciones</vs-th>
      </template>
      <template slot-scope="{data}">
        <vs-tr :key="datos.id_proceso_produccion" v-for="datos in data">
          <vs-td v-if="datos.num_orden" style="width:10%!important;">{{datos.num_orden}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td
            class="text-center"
            v-if="datos.detalle"
            style="width:30%!important;"
          >{{datos.detalle}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td
            class="text-center"
            v-if="datos.estado"
            style="width:35%!important;"
          >{{datos.estado}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td class="whitespace-no-wrap text-center" style="width:5%!important;">
            <feather-icon
              icon="FileTextIcon"
              svgClasses="w-5 h-5 hover:text-primary stroke-current"
              class="pointer"
              @click.stop="abrirprod(datos.id_proceso_produccion)"
            />
            <feather-icon
              icon="TrashIcon"
              svgClasses="w-5 h-5 hover:text-primary stroke-current"
              class="pointer"
            />
          </vs-td>
        </vs-tr>
      </template>
    </vs-table>
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
      //buscador
      buscar: "",
      i18nbuscar: this.$t("i18nbuscar"),

      eliminar: false,
      //listar
      contenido: []
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
        res = this.$store.state.Roles[23].crear;
      }
      return res;
    },
    editarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[23].editar;
      }
      return res;
    },
    eliminarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[23].eliminar;
      }
      return res;
    }
  },
  methods: {
    listarformula(page, buscar) {
      var url =
        "/api/traerprocesprod/" +
        this.usuario.id_empresa +
        "/" +
        this.usuario.id_establecimiento +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenido = respuesta.recupera;
      });
    },
    abrirprod(id) {
      this.$router.push(`/produccion/proceso-produccion/${id}/gestion`);
    }
  },

  mounted() {
    this.listarformula(1, this.buscar);
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
.peque .vs-popup {
  width: 600px !important;
}
.txt-center > div > input {
  text-align: center;
}
.text-center > .vs-table-text {
  text-align: center !important;
  display: block;
}
</style>