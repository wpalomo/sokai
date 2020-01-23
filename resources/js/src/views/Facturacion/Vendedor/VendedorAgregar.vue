<template>
  <div class="vx-row">
    <div class="vx-col w-full mb-base">
      <vx-card title="Información General">
        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Código" v-model="codigo_vendedor" />
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Nombre" v-model="nombre_vendedor" />
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Email" v-model="email_vendedor" />
            <div v-show="error">
              <span class="text-danger" v-for="err in erroremail" :key="err" v-text="err"></span>
            </div>
          </div>

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
              to="/facturacion/vendedor"
              v-if="1"
              @click="cancelar(1)"
            >CANCELAR</vs-button>
            <vs-button color="danger" type="filled" to="/facturacion/vendedor" v-else>CANCELAR</vs-button>
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
      codigo_vendedor: "",
      nombre_vendedor: "",
      email_vendedor: "",
      contenido: [],

      //ERRORES
      error: 0,
      errorcodigo_vendedor: [],
      errornombre_vendedor: [],
      erroremail: []
    };
  },

  methods: {
    guardar() {
      if (this.validar()) {
        return;
      }

      axios
        .post("/api/guardarvendedorcliente", {
          codigo_vendedor: this.codigo_vendedor,
          nombre_vendedor: this.nombre_vendedor,
          email_vendedor: this.email_vendedor
        })
        .then(res => {
          this.$vs.notify({
            text: "Registro guardado exitosamente",
            color: "primary"
          });
          this.$router.push("/facturacion/vendedor");
        })
        .catch(err => {
          console.log(err);
        });
    },
    cancelar() {
      this.$router.push("/facturacion/vendedor");
    },
    editar() {
      if (this.validar()) {
        return;
      }
      axios
        .put("/api/editarvendedorcliente", {
          id: this.$route.params.id,
          codigo_vendedor: this.codigo_vendedor,
          nombre_vendedor: this.nombre_vendedor,
          email_vendedor: this.email_vendedor
        })
        .then(res => {
          this.$vs.notify({
            text: "Registro actualizado exitosamente",
            color: "primary"
          });
          this.$router.push("/facturacion/vendedor");
        })
        .catch(err => {
          console.log(err);
        });
    },
    borrar() {
      this.codigo_vendedor = "";
      this.nombre_vendedor = "";
      this.email_vendedor = "";
    },
    listarvendedor(id) { 
      axios
        .put("/api/vendedor/vercliente/", { id: id })
        .then(res => {
          console.log(res.canton);
          let data = res.data[0];

          this.codigo_vendedor = data.codigo_vendedor;
          this.nombre_vendedor = data.nombre_vendedor;
          this.email_vendedor = data.email_vendedor;
        })
        .catch(err => {
          console.log(err);
        });
    },

    validar() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.error = 0;
      this.errorcodigo_vendedor = [];
      this.errornombre_vendedor = [];
      this.erroremail = [];

      if (!this.nombre_vendedor) {
        this.errornombre_vendedor.push("Campo obligatorio");
        this.error = 1;
      }

      if (!this.validaremail(this.email_vendedor)) {
        this.erroremail.push("Email no valido");
        this.error = 1;
      }

      return this.error;
    },
    validaremail(email_vendedor) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email_vendedor);
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
    if (this.$route.params.id) {
      var id = this.$route.params.id;
      this.listarvendedor(id);
    }
  }
};
</script>
