<template>
  <div class="vx-row">
    <div class="vx-col w-full mb-base">
      <vx-card title="Agregar Usuario">
        <form action method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="vx-row">
            <div class="vx-col sm:w-1/2 w-full mb-2">
              <vs-input class="w-full" label="Nombres" v-model="nombre" />
              <div v-show="error">
                <span
                  class="text-danger"
                  style="font-size:12px;"
                  v-for="(err,index) in errornombre"
                  :key="index"
                  v-text="err"
                ></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-2">
              <vs-input class="w-full" label="Apellidos" v-model="apellido" />
              <div v-show="error">
                <span
                  class="text-danger"
                  style="font-size:12px;"
                  v-for="(err,index) in errorapellido"
                  :key="index"
                  v-text="err"
                ></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="Seleccione el establecimiento"
                class="selectExample w-full"
                label="Establecimiento"
                vs-multiple
                autocomplete
                v-model="establecimeinto"
              >
                <vs-select-item
                  v-for="(tr,index) in arrayestablecimientos"
                  :key="index"
                  :text="tr.nombre"
                  :value="tr.id_establecimiento"
                />
              </vs-select>
              <div v-show="error">
                <span
                  class="text-danger"
                  style="font-size:12px;"
                  v-for="(err,index) in errorestablecimeinto"
                  :key="index"
                  v-text="err"
                ></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="Seleccione punto de emisión"
                class="selectExample w-full"
                label="punto de emisión"
                vs-multiple
                autocomplete
                v-model="punto_emision"
              >
                <vs-select-item
                  v-for="(tr,index) in arraypunto_emision"
                  :key="index"
                  :text="tr.nombre"
                  :value="tr.id_punto_emision"
                />
              </vs-select>
              <div v-show="error">
                <span
                  class="text-danger"
                  style="font-size:12px;"
                  v-for="(err,index) in errorpunto_emision"
                  :key="index"
                  v-text="err"
                ></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="Seleccione Estado"
                class="selectExample w-full"
                label="Estado"
                vs-multiple
                v-model="estado"
              >
                <vs-select-item
                  v-for="(tr,index) in arrayestado"
                  :key="index"
                  :text="tr.nombre"
                  :value="tr.id"
                />
              </vs-select>
              <div v-show="error">
                <span
                  class="text-danger"
                  style="font-size:12px;"
                  v-for="(err,index) in errorestado"
                  :key="index"
                  v-text="err"
                ></span>
              </div>
            </div>
            <vs-divider border-style="solid" color="dark">Cambio de ingreso del usuario</vs-divider>
            <div class="vx-col sm:w-1/3 w-full mb-2" v-if="!$route.params.id">
              <vs-input class="w-full" label="Contraseña" v-model="password" type="password" autocomplete="new-password"/>
              <div v-show="error">
                <span
                  class="text-danger"
                  style="font-size:12px;"
                  v-for="(err,index) in errorpassword"
                  :key="index"
                  v-text="err"
                ></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6" v-if="!$route.params.id">
              <vs-input
                class="w-full"
                label="Repetir Contraseña"
                v-model="repassword"
                type="password"
                autocomplete="new-password"
              />
              <div v-show="error">
                <span
                  class="text-danger"
                  style="font-size:12px;"
                  v-for="(err,index) in errorrepassword"
                  :key="index"
                  v-text="err"
                ></span>
              </div>
            </div>
            <div class="vx-col w-full mb-6" :class="{'sm:w-1/3':!$route.params.id,'sm:w-full':$route.params.id}">
              <vs-input class="w-full" label="Correo electrónico" v-model="email" />
              <div v-show="error">
                <span
                  class="text-danger"
                  style="font-size:12px;"
                  v-for="(err,index) in erroremail"
                  :key="index"
                  v-text="err"
                ></span>
              </div>
            </div>
            <vs-divider border-style="solid" color="dark" v-if="usuario.id_rol==1">Permisos de módulos de usuario</vs-divider>
            <div class="vx-col sm:w-full w-full mb-6" v-if="usuario.id_rol==1">
              <table class="w-full">
                <tr>
                  <th class="font-semibold text-base text-left px-3 py-2">Modulo</th>
                  <th class="font-semibold text-base text-left px-3 py-2">Ver</th>
                  <th class="font-semibold text-base text-left px-3 py-2">Editar</th>
                  <th class="font-semibold text-base text-left px-3 py-2">Crear</th>
                  <th class="font-semibold text-base text-left px-3 py-2">Eliminar</th>
                </tr>
                <tr v-for="(val, index) in items" :key="index">
                  <td class="px-3 py-2">{{ val.nombre }}</td>
                  <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                  <td class="px-3 py-2"><vs-checkbox :disabled="usuario.id-empresa!=6 && val.value==1" v-model="val.editar"/></td>
                  <td class="px-3 py-2"><vs-checkbox :disabled="usuario.id-empresa!=6 && val.value==1" v-model="val.crear"/></td>
                  <td class="px-3 py-2"><vs-checkbox :disabled="usuario.id-empresa!=6 && val.value==1" v-model="val.eliminar"/></td>
                </tr>
              </table>
            </div>
          </div>
          <div class="vx-row">
            <div class="vx-col w-full">
              <vs-button
                color="success"
                type="filled"
                @click="guardar()"
                v-if="!$route.params.id"
              >Guardar</vs-button>
              <vs-button color="success" type="filled" @click="editar()" v-else>Guardar</vs-button>
              <vs-button color="danger" type="filled" to="/administrar/usuarios">Cancelar</vs-button>
            </div>
          </div>
        </form>
      </vx-card>
    </div>
  </div>
</template>
<script>
const axios = require("axios");
export default {
  data() {
    return {
      checkBox2: [],
      popupActive: false,
      codigo: "",
      nombre: "",
      apellido: "",
      password: "",
      repassword: "",
      email: "",

      establecimeinto: null,
      punto_emision: null,
      rol: null,
      estado: 1,

      arrayestablecimientos: [],
      arraypunto_emision: [],
      arrayroles: [],
      arraymodulos: [],
      arrayestado: [{ id: 1, nombre: "Activo" }, { id: 0, nombre: "Inactivo" }],
      items: [  
        //{ nombre: "Empresa", value: 1, ver: 1, editar: 0, crear: 0, eliminar:0 },
        { nombre: "Establecimiento", value: 2, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Punto Emision", value: 3, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Usuarios", value: 4, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Codigo Impuestos", value: 5, ver: 1, editar: 1,crear: 1, eliminar:1 },
        { nombre: "Tipo Comprobante", value: 6, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Retenciones", value: 7, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Tipo Sustento", value: 8, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Plan Cuentas", value: 9, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Asientos Contables", value: 10, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Nota credito", value: 11, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Factura Venta", value: 12, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Proforma ", value: 13, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Pago Cliente", value: 14, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Clientes", value: 15, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Vendedor", value: 16, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Bodega", value: 17, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Catalogo", value: 18, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Empleados", value: 19, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Proveedor", value: 20, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Factura Compra", value: 21, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Importacion", value: 22, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Cuentas por pagar", value: 23, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Fórmula", value: 24, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Proceso Producción", value: 25, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Activos fijos", value: 26, ver: 1, editar: 1, crear: 1, eliminar:1 },
        { nombre: "Calendario", value: 27, ver: 1, editar: 1, crear: 1, eliminar:1 }
      ],
      error: 0,
      errorcodigo: [],
      errornombre: [],
      errorapellido: [],
      errorpassword: [],
      errorrepassword: [],
      erroremail: [],
      errorestablecimeinto: [],
      errorpunto_emision: [],
      errorrol: [],
      errorestado: [],
      selected: [],
      selectAll: false,
      selected1: [],
      selectAll1: false,
      selected2: [],
      selectAll2: false,
      selected3: [],
      selectAll3: false,
      selected4: [],
      selectAll4: false,
      selected5: [],
      selectAll5: false,
      selected6: [],
      selectAll6: false,
      empresa:"",
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
    listar() {
      if (this.$route.params.id >= 1) {
        var url = "/api/recregusuario/" + this.$route.params.id;
        axios.get(url).then(res => {
          var dat = res.data.datos;
          this.nombre = dat.nombres;
          this.apellido = dat.apellidos;
          this.email = dat.email;
          this.establecimeinto = dat.id_establecimiento;
          this.punto_emision = dat.id_punto_emision;
          this.rol = dat.id_rol;
          this.estado = dat.estado;
          
          this.items = res.data.roles;
        });
      }
    },
    listarestablecimientos() {
      var url = "/api/esttodo/" + this.usuario.id_empresa;
      axios.get(url).then(res => {
        this.arrayestablecimientos = res.data;
      });
    },
    listarpunto_emision() {
      var url = "/api/pttodo/" + this.usuario.id_empresa;
      axios.get(url).then(res => {
        this.arraypunto_emision = res.data;
      });
    },
    guardarm() {
      var todo = this.items;
      // var todo = this.checkBox1;
      var url = "/api/guardarmodulo";
      var datos = {
        iduser: this.$route.params.id,
        checkBox1: todo
      };
      axios.post(url, datos).then(res => {
        console.log(res.data);
      });
    },
    guardar() {
      if (this.validar()) {
        return;
      }
      var url = "/api/regusuario";
      var datos = {
        nombre: this.nombre,
        apellido: this.apellido,
        password: this.password,
        email: this.email,
        establecimeinto: this.establecimeinto,
        punto_emision: this.punto_emision,
        rol: this.rol,
        estado: this.estado,
        empresa: this.usuario.id_empresa,
        usuario: this.usuario.id,
        //items
        items: this.items
      };
      axios.post(url, datos).then(res => {
        this.$vs.notify({
          text: "Registro guardado exitosamente",
          color: "success"
        });
        this.$router.push("/administrar/usuarios");
      });
    },
    editar() {
      if (this.validar()) {
        return;
      }
      var url = "/api/editarregusuario";
      var datos = {
        id: this.$route.params.id,
        nombre: this.nombre,
        apellido: this.apellido,
        password: this.password,
        email: this.email,
        establecimeinto: this.establecimeinto,
        punto_emision: this.punto_emision,
        rol: this.rol,
        estado: this.estado,
        empresa: this.usuario.id_empresa
      };
      axios.post(url, datos).then(res => {
        console.log(res.data);
        this.$router.push("/administrar/usuarios");
      });
    },
    validar() {
      this.error = 0;
      this.errornombre = [];
      this.errorapellido = [];
      this.errorpassword = [];
      this.errorrepassword = [];
      this.erroremail = [];
      this.errorestablecimeinto = [];
      this.errorpunto_emision = [];
      this.errorrol = [];
      this.errorestado = [];

      if (!this.nombre) {
        this.errornombre.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.apellido) {
        this.errorapellido.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.$route.params.id) {
        if (!this.password) {
          this.errorpassword.push("Campo obligatorio");
          this.error = 1;
        }
          if (this.password != this.repassword) {
          this.errorrepassword.push("Las contraseñas no coinciden");
          this.error = 1;
        }
      }
      
      if (!this.email) {
        this.erroremail.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.establecimeinto) {
        this.errorestablecimeinto.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.punto_emision) {
        this.errorpunto_emision.push("Campo obligatorio");
        this.error = 1;
      }

      return this.error;
    }
  },
  mounted() {
    this.listarestablecimientos();
    this.listarpunto_emision();
    if (this.$route.params.id >= 1) {
      this.listar();
    }
  }
};
</script>
<style>
.vs-popup {
  width: 800px !important;
}
.text-base{
  text-align: center!important;
}
.px-3{
  text-align: center;
}
.px-3:nth-child(1){
  text-align: left!important;
}
.text-base:nth-child(1){
  text-align: left!important;
} 

.vs-checkbox-primary{
  display: inline-flex;
}
</style>
