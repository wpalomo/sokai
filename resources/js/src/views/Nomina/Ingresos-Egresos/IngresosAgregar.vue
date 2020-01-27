<template>
  <div class="vx-row">
    <div class="vx-col w-full mb-base">
      <vx-card title="Agregar">
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <label class="vs-input--label">Departamento</label>
          <vx-input-group class>
            <vs-input class="w-full" v-model="departamento" :value="iddepart" disabled />
            <template slot="append">
              <div class="append-text btn-addon">
                <!-- -->
                <vs-button color="primary" @click="activePrompt4 = true">Buscar</vs-button>
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
        <vs-divider border-style="solid" color="dark">
          <span @click="octl()">Agregar</span>
          <vs-button
            v-if="usuario.id_rol==1"
            color="primary"
            style="margin-left: 9px;padding: 8px 20px;"
            type="border"
            @click="agregarcampo()"
          >agregar</vs-button>
        </vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <div
              class="vx-col mb-3 relativo"
              :class="{'w-full':contenidocamposadicionales.length==1, 'md:w-1/3':contenidocamposadicionales.length==20, 'md:w-1/3':contenidocamposadicionales.length>=50}"
              v-for="(add,index) in contenidocamposadicionales"
              v-bind:key="index"
            >
              <vs-button
                v-if="usuario.id_rol==1"
                color="danger"
                type="gradient"
                class="iconelim"
                @click="quitarcampo(index)"
              >x</vs-button>
              <vs-input
                class="w-full"
                label="Descripción"
                v-model="add.descripcion"
                maxlength="45"
              />
            </div>
          </div>

          <div class="vx-col sm:w-1/6 w-full mb-6">
            <div
              class="vx-col mb-3 relativo"
              :class="{'w-full':contenidocamposadicionales.length==1, 'md:w-1/6':contenidocamposadicionales.length==2, 'md:w-1/6':contenidocamposadicionales.length>=50}"
              v-for="(add,index) in contenidocamposadicionales"
              v-bind:key="index"
            >
              <vs-select
                placeholder="--Seleccione--"
                class="selectExample w-full"
                label="Tipo:"
                vs-multiple
                v-model="add.tipoie"
              >
                <vs-select-item
                  :key="index"
                  :value="item.text"
                  :text="item.text"
                  v-for="(item,index) in tipo"
                />
              </vs-select>
            </div>
          </div>
          <!--CUENTA CONTABLE======1-->
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <div
              class="vx-col mb-3 relativo"
              :class="{'w-full':contenidocamposadicionales.length==1, 'md:w-1/6':contenidocamposadicionales.length==2, 'md:w-1/6':contenidocamposadicionales.length>=50}"
              v-for="(add,index) in contenidocamposadicionales"
              v-bind:key="index"
            >
              <label class="vs-input--label">Cuenta Contable</label>
              <vx-input-group class>
                <vs-input class="w-full" v-model="add.ctacontable" :value="idContable"  />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <!-- -->
                    <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
          </div>
          <!--FIN CUENTA CONTABLE=====1-->
          <!--CUENTA CONTABLE======2-->
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <div
              class="vx-col mb-3 relativo"
              :class="{'w-full':contenidocamposadicionales.length==1, 'md:w-1/6':contenidocamposadicionales.length==2, 'md:w-1/6':contenidocamposadicionales.length>=50}"
              v-for="(add,index) in contenidocamposadicionales"
              v-bind:key="index"
            >
              <label class="vs-input--label">Cuenta Contable</label>
              <vx-input-group class>
                <vs-input class="w-full" v-model="add.ctacontable2" :value="idContable"  />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <!-- -->
                    <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
          </div>
          <!--FIN CUENTA CONTABLE=====2-->
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
          <!--
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="ctacontable" :value="idContable" disabled />
              <template slot="append">
                <div class="append-text btn-addon">
                
                  <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
       
    
          -->
          <!--BOTONES-->
          <div class="vx-col w-full">
            <vs-button
              color="success"
              type="filled"
              @click="editar()"
              v-if="$route.params.id"
            >GUARDAR</vs-button>
            <vs-button color="success" type="filled" @click="guardar()" v-else>GUARDAR</vs-button>
            <vs-button color="warning" type="filled" @click="borrar()">BORRAR</vs-button>
            <vs-button
              color="danger"
              type="filled"
              to="/nomina/ingreso-egreso"
              v-if="1"
              @click="cancelar(1)"
            >CANCELAR</vs-button>
            <vs-button color="danger" type="filled" to="/nomina/ingreso-egreso" v-else>Cancelar</vs-button>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>

<script>
import { log } from "util";
const axios = require("axios");
export default {
  data() {
    return {
      tipoie: "",
      activePrompt3: false,
      cuentaarray3: [],
      i18nbuscar: this.$t("i18nbuscar"),
      buscar: "",
      criterio: "codcta",
      descripcion: "asdasdsa",
      ctacontable: "",
      iddepart: "",
      idContable: "",
      ctacontable2: "",
      //
      departamento: "",
      activePrompt4: false,
      //listar depart
      contenidodepartamento: [],
      contenido: [],
      contenidocamposadicionales: [],
      nombrec: [],
      contenido2: [],
      //ERRORES
      error: 0,

      errornombre: [],
      errorcodigo: [],
      errorsecuencial_factura: [],
      errorsecuencial_nota_credito: [],
      errorsecuencial_nota_debito: [],
      errorsecuencial_guia: [],
      errorsecuencial_retencion: [],
      errorestablecimiento: [],
      errorsecuencial_l: [],
      asignar: [],
      //arrays
      tipo: [
        //{ text: "Seleccioné", value: 0 },
        { text: "Ingreso", value: 1 },
        { text: "Egreso", value: 2 }
      ]
    };
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    }
  },
  methods: {
    quitarcampo(x) {
      this.contenidocamposadicionales.splice(x, 1);
    },
    agregarcampo() {
      this.ocult = true;
      if (this.contenidocamposadicionales.length < 30) {
        this.contenidocamposadicionales.push({nombrec: "",contenido2: ""});
      } else {
        this.$vs.notify({
          title: "Error al Agregar Campo",
          text: "No se puede agregar mas de 7 campos",
          color: "danger"
        });
      }
    },
    handleSelected3(tr) {
      (this.ctacontable = `${tr.codcta}`),
        (this.idContable = `${tr.id_ctas}`),
        (this.activePrompt3 = false);
    },
    handleSelected4(tr) {
      (this.departamento = `${tr.dep_nombre}`),
        (this.iddepart = `${tr.id_departamento}`),
        (this.activePrompt4 = false);
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
    listarDepartamento() {
      let me = this;
      var url = "/api/departamento/listar"+
        this.usuario.id_empresa ;
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
    cancelar() {
      this.$router.push("/nomina/ingresos");
    },
    guardar() {
      //alert(this.nombrec);
      this.$router.push("/nomina/ingreso-egreso");
    },
    editar() {
      if (this.validar()) {
        return;
      }
      axios
        .put("/api/actualizarpt", {
          id: this.$route.params.id,

          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            text: "Registro actualizado exitosamente",
            color: "primary"
          });
          this.$router.push("");
        })
        .catch(err => {
          console.log(err);
        });
    },
    borrar() {},
    validar() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.error = 0;
      this.errornombre = [];
      this.errorcodigo = [];
      this.errorsecuencial_factura = [];
      this.errorsecuencial_nota_credito = [];
      this.errorsecuencial_nota_debito = [];
      this.errorsecuencial_guia = [];
      this.errorsecuencial_retencion = [];
      this.errorestablecimiento = [];

      if (!this.nombre) {
        this.errornombre.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.codigo) {
        this.errorcodigo.push("Campo obligatorio");
        this.error = 1;
      } else if (this.codigo.length != 3) {
        this.errorcodigo.push("Debe tener 3 dígitos");
        this.error = 1;
      }
      if (!this.secuencial_factura) {
        this.errorsecuencial_factura.push("Campo obligatorio");
        this.error = 1;
      } else if (this.codigo.length != 3) {
        this.errorsecuencial_factura.push("Debe tener 9 dígitos");
        this.error = 1;
      }

      if (!this.secuencial_nota_credito) {
        this.errorsecuencial_nota_credito.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.secuencial_nota_debito) {
        this.errorsecuencial_nota_debito.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.secuencial_guia) {
        this.errorsecuencial_guia.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.secuencial_retencion) {
        this.errorsecuencial_retencion.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.establecimiento) {
        this.errorestablecimiento.push("Campo obligatorio");
        this.error = 1;
      }
      if (
        this.errornombre ||
        this.errorcodigo ||
        this.errorsecuencial_factura ||
        this.errorsecuencial_nota_credito ||
        this.errorsecuencial_nota_debito ||
        this.errorsecuencial_guia ||
        this.errorsecuencial_retencion ||
        this.errorestablecimiento
      )
        return this.error;
    },
    solonumeros: function($event) {
      //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
      var num = /^\d*\.?\d*$/;
      if (
        $event.charCode === 0 ||
        num.test(String.fromCharCode($event.charCode))
      ) {
        return true;
      } else {
        $event.preventDefault();
      }
    }
  },
  mounted() {
    this.listarDepartamento();
    this.listar(1, this.buscar);
  }
};
</script>
<style lang="scss">
.vs-popup {
  width: 900px !important;
}
.peque .vs-popup {
  width: 1060px !important;
}
.depa .vs-popup {
  width: 650px !important;
}
.iconelim {
  float: none;
  position: absolute;
  right: 16px;
  padding: 1px !important;
  margin-top: -4px;
  width: 23px !important;
  height: 23px !important;
  cursor: pointer;
  z-index: 9;
}
</style>
