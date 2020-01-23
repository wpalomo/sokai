<template>
  <vx-card class="mt-10">
    <div class="flex flex-wrap justify-between items-center mb-3">
      <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
        <vs-input
          class="mb-4 md:mb-0 mr-4 w-full"
          v-model="buscar"
          @keyup="listarformula(1,buscar)"
          v-bind:placeholder="i18nbuscar"
        />
      </div>
      <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"></div>
    </div>
    <vs-table stripe :data="contenido">
      <template slot="thead">
        <vs-th>Código</vs-th>
        <vs-th class="text-center">Nombre Fórmula</vs-th>
        <vs-th class="text-center">Producto</vs-th>
        <vs-th class="text-center">Opciones</vs-th>
      </template>
      <template slot-scope="{data}">
        <vs-tr :key="datos.id_formula_produccion" v-for="datos in data">
          <vs-td
            v-if="datos.codigo_produccion"
            style="width:15%!important;"
          >{{datos.codigo_produccion}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td
            class="text-center"
            v-if="datos.nombre_form"
            style="width:35%!important;"
          >{{datos.nombre_form}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td
            class="text-center"
            v-if="datos.nomprod"
            style="width:35%!important;"
          >{{datos.nomprod}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td class="whitespace-no-wrap text-center" style="width:15%!important;">
            <feather-icon
              icon="FileTextIcon"
              svgClasses="w-5 h-5 hover:text-primary stroke-current"
              class="pointer"
              @click.stop="abrirprod(datos.id_formula_produccion)"
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
        "/api/formula/" +
        this.usuario.id_empresa +
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