<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
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
            <vs-button class="btnx" type="filled" @click="agregarImp()">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" divider to="/app/agregarEjemplo">Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>
      <br>
      <vs-table stripe :data="contenidotipo">
        <template slot="thead">
          <vs-th>Cod. Sri</vs-th>
          <vs-th>Descripcion</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id_tipcomprob" v-for="datos in data">
            <vs-td style="width: 80px;" v-if="datos.cod_tipcomprob">{{datos.cod_tipcomprob}}</vs-td>
            <vs-td style="width: 80px;" v-else>-</vs-td>
            <vs-td v-if="datos.descrip_tipcomprob">{{datos.descrip_tipcomprob}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon v-if="editarrol" icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="verTipo(datos.id_tipcomprobante)" />
              <feather-icon v-if="eliminarrol" icon="TrashIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-danger stroke-current"  @click.stop="eliminarTipo(datos.id_tipcomprobante)"/>
            </vs-td>
            </vs-tr>
          </template>
      </vs-table>
      <vs-popup :title="titulomodal" :active.sync="activePrompt">
          <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <vs-input class="w-full" label="Codigo Sri" v-model="cod_tipcomprob"   />
              </div>
              <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Descripción</label>
                <vs-input class="w-full" v-model="descrip_tipcomprob" rows="3"/>
              </div>
          </div>
          <div class="vx-col w-full">
              <vs-button color="success" type="filled" @click="guardarTipo()" v-if="!idrecupera">GUARDAR</vs-button>
              <vs-button color="success" type="filled" @click="editarTipo()" v-else>GUARDAR</vs-button>
              <vs-button color="warning" type="filled" @click="vaciarTipo()">BORRAR</vs-button>
              <vs-button color="danger" type="filled" @click="cancelarTipo()">CANCELAR</vs-button>
          </div>
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
      //mapeo de datos
      //paginacion
      pagination: {
        total: 0, 
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count:0,
      },
      pagina: 1,
      cantidadp:20,
      offset: 0,
      //buscador
      buscar: "",
      criterio: "descrip_imp",  
      //otros valores
      gridApi: null,
      contenidotipo: [],
      activePrompt: false,
      val:"",
      //lenguaje
      i18nbuscar:this.$t("i18nbuscar"), 
      //modal imp
      activePrompt:false,
      titulomodal:"",
      idrecupera:null,
      cod_tipcomprob:"",
      descrip_tipcomprob:"",
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
        res = this.$store.state.Roles[5].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[5].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[5].eliminar;
      }
      return res;
    }
  },
  methods: {
    agregarImp(){
      this.activePrompt=true,
      this.titulomodal="Agregar",
      this.idrecupera=null,
      this.cod_tipcomprob="",
      this.descrip_tipcomprob=""
    },
    guardarTipo(){
      axios.post("/api/agregartipcomprob",{ 
        cod_tipcomprob:this.cod_tipcomprob,
        descrip_tipcomprob:this.descrip_tipcomprob,
        id_empresa: this.usuario.id_empresa,
       }).then(res => {
         this.$vs.notify({
              title:'Registro Guardado',
              text: 'Registro Guardado exitosamente',
              color:'success',
            })
            this.activePrompt=false;
            this.listar(1,this.buscar,this.cantidadp);
       }).catch(err=>{
         this.$vs.notify({
              title:'Error al Gualdar',
              text: 'Error al Gualdar compruebe que escribio todos los campos',
              color:'danger',
            })
       });
    },
    editarTipo(){
      axios.put("/api/actualizartipcomprob",{ 
        id:this.idrecupera,
        cod_tipcomprob:this.cod_tipcomprob,
        descrip_tipcomprob:this.descrip_tipcomprob,
        id_empresa: this.usuario.id_empresa,
       }).then(res => {
         this.$vs.notify({
              title:'Registro Editado',
              text: 'Registro Editado exitosamente',
              color:'success',
            })
            this.activePrompt=false;
            this.listar(1,this.buscar,this.cantidadp);
       }).catch(err=>{
         this.$vs.notify({
              title:'Error al Editado',
              text: 'Error '+err,
              color:'danger',
            })
       });
    },
    vaciarTipo(){
        this.cod_tipcomprob="",
        this.descrip_tipcomprob=""
    },
    verTipo(id){
       if(id){
        this.idrecupera = id;
        this.activePrompt=true;
        this.titulomodal="Editar";
       // console.log("hola"+this.idrecupera);
        var url = "/api/abrirtipcomprob/"+id;
        axios.put(url).then( res => {
          let data = res.data[0];
          this.cod_tipcomprob = data.cod_tipcomprob;
          this.descrip_tipcomprob = data.descrip_tipcomprob;


          //this.fecult = data.fecult;
        }).catch( err => {
          console.log(err);
        });
      }else{
        this.idrecupera = null;
        
      }
    },
    editarImp(){
      axios.put("/api/actualizarimpuesto",{
        id:this.idrecupera,
        cod_imp:this.cod_imp,
        descrip_imp:this.descrip_imp,
        tipo_imp:this.tipo_imp
      }).then(res => {
        this.activePrompt=false;
        this.$vs.notify({
              title:'Registro Editado',
              text: 'Registro Editado exitosamente',
              color:'success',
            })
      this.listar(1, this.buscar, this.cantidadp);
      }).catch(err => {});
    },
    cancelarTipo(){
      this.activePrompt=false,
      this.cod_tipcomprob="",
      this.descrip_tipcomprob=""
    },
    eliminarTipo(cd) {
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
      axios.delete("/api/eliminartipcomprob/" + parameters);
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
        "/api/tipcomprob/"+this.usuario.id_empresa+"?page=" +
        page +
        "&buscar=" +
        buscar;
      axios
        .get(url)
        .then(function(response) { 
          var respuesta = response.data;
          me.contenidotipo = respuesta.recupera;
        })
        .catch(function(error) {
          //console.log(error);
          this.$vs.notify({
           title:'Error al Guardar',
           text: 'Verifique bien sus datos al momento de guardar',
           color:'danger',
         })
        });
    },
    updateSearchQuery(val) { 
      this.gridApi.setQuickFilter(val);
    }
  },
  mounted() {
    this.listar(1, this.buscar);
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
.peque .vs-popup {
  width: 400px !important;
}

</style>