<template>
  <div class="vx-row">
    <!-- MULTIPLE COLUMNS-->
    <div class="vx-col w-full mb-base">
      <vx-card>
       
         <vs-divider position="left">
           
        <h3>Productos</h3>
      </vs-divider>
      <div class="p-base">
        <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;">
            <template slot="thead">
              <!--<vs-th>CÓDIGO</vs-th>-->
              <vs-th>NOMBRE</vs-th>
              <vs-th>CANTIDAD</vs-th>
              <vs-th>COSTO UNITARIO</vs-th>
              <vs-th>COSTO TOTAL</vs-th>
              <vs-th>COSTO TRANSPORTE</vs-th>
              <vs-th>COSTO IMPUESTO</vs-th>
              <vs-th>COSTO COURIER</vs-th>
              <vs-th>NUEVO COSTO</vs-th>
              <vs-th>NUEVO COSTO TOTAL</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">

                <!--<vs-td :data="tr.id_prodimp>{{ tr.id_prodimp }}</vs-td>-->
                <!--<vs-td :data="tr.codigo">{{ tr.codigo }}</vs-td>-->
                <vs-td :data="tr.nombre" style="width:150px!important;">{{ tr.nombre }}</vs-td>
                <vs-td :data="tr.cantidad" style="width:100px!important;">
                  {{tr.cantidad}}
                </vs-td>
                <vs-td :data="tr.precio" style="width:100px!important;">
                  {{tr.precio}}
                </vs-td>
                <vs-td style="width:100px!important;">{{ (tr.cantidad*tr.precio)| currency }}</vs-td>
                <vs-td style="width:100px!important;">
                    {{nuevocostounit | currency}}
                </vs-td>
                <vs-td style="width:100px!important;">
                    {{nuevocostounit2 | currency}}
                </vs-td>
                <vs-td style="width:100px!important;">
                    {{nuevocostounit3 | currency}}
                </vs-td>
                <vs-td style="width:100px!important;">
                    {{parseFloat(tr.precio)+nuevocostounit+nuevocostounit2+nuevocostounit3 | currency}}
                </vs-td>
                <vs-td style="width:100px!important;">
                    {{parseFloat(tr.cantidad)*(parseFloat(tr.precio)+nuevocostounit+nuevocostounit2+nuevocostounit3) | currency}}
                </vs-td>
              </vs-tr>
              <vs-tr style="border-top: 1px solid #ddd;">
            
            <vs-th>Cantidad Total</vs-th>
            <vs-td>{{totalcantidad}}</vs-td>
            <vs-th>Costo Total</vs-th>
            <vs-td>{{totalcosto | currency}}</vs-td>
            <vs-td></vs-td>
            <vs-td></vs-td>
            <vs-td></vs-td>
            <vs-th>Costo Importacion</vs-th>
            <vs-td>{{totalliquid2 |currency}}</vs-td>
          </vs-tr>
            </template>
          </vs-table>
        <!--dividir-->
      </div>


        
      <vs-divider position="left" >
        <h3>Calculos</h3>
      </vs-divider>
      <div class="vx-row p-base">
          <div class="vx-col sm:w-1/2 w-full mb-6" >
          <vs-table hoverFlat :data="total_factura" style="font-size: 12px;">
            <template slot="thead" >
              <vs-th>Id</vs-th>
              <vs-th>Descripcion</vs-th>
              <vs-th>Total</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
    
                <!--<vs-td :data="tr.id_prodimp>{{ tr.id_prodimp }}</vs-td>-->
                <vs-td :data="tr.id_factcompra">{{ tr.id_factcompra }}</vs-td>
                <vs-td :data="tr.descripcion">{{ tr.descripcion }}</vs-td>
                <vs-td :data="tr.total_factura">{{ tr.total_factura }}</vs-td>
              </vs-tr>
              <vs-tr style="border-top: 1px solid #ddd;">
            <vs-th></vs-th>
            <vs-th>Total Facturas</vs-th>
            <vs-td>{{totalfac | currency}}</vs-td>
            

          </vs-tr>
            </template>
          </vs-table>
          </div>
      <div class="vx-col sm:w-1/6 w-full mb-6" >
        <h5>{{descripcion_factura}}</h5>
        {{nuevocostounit | currency}}
      </div>
      <div class="vx-col sm:w-1/6 w-full mb-6">
        <h5 >{{descripcion_factura2}}</h5>
        {{nuevocostounit2 | currency}}
        
      </div>
      <div class="vx-col sm:w-1/6 w-full mb-6">
        <h5 >{{descripcion_factura3}}</h5>
        {{nuevocostounit3 | currency}}
        <!--<h7 v-if="valorfactura3">{{nuevocostounit3 | currency}}</h7>-->
      </div>
          
      </div>
      <vs-divider position="left">
        <h3>Total Liquidacion</h3>
      </vs-divider>
      <div class="vx-row">
      <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
            <label class="vs-input--label">SALDO SIN LIQUIDAR</label>
            <h1>{{ totalcosto | currency }}</h1>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
            <label class="vs-input--label">TOTAL PRODUCTOS</label>
            <h1>{{ totalcantidad}}</h1>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
            <label class="vs-input--label">TOTAL LIQUIDACION</label>
            <h1>{{ totalliq | currency }}</h1>
      </div>
      </div>
      <div class="vx-col w-full">
        <vs-button color="success" type="filled" @click="liquidar()" v-if="estado=='Inicial' && totalcosto!=totalliq">Liquidar</vs-button>
        <vs-button color="danger" type="filled" v-if="descripcion_factura" to="/compras/liquidacion">Cancelar</vs-button>
        <vs-button color="danger" type="filled" v-else to="/compras/importacion">Cancelar</vs-button>
      </div>
      </vx-card>
    </div>
  </div>
</template>
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";

const axios = require("axios");
export default {
    data(){
        return{
            idrecupera:null,
            total_factura:[],
            descripcion_factura:"",
            valorfactura1:"",
            descripcion_factura2:"",
            valorfactura2:"",
            descripcion_factura3:"",
            valorfactura3:"",
            campo:"",
            estado:"",
            invoiceData: {
            tasks: [
              {
                id: 1,
                task: "Website Redesign",
                hours: 60,
                rate: 15,
                amount: 90000,
                iva: 15,
                ice: 15
              }
            ],
        subtotal: 114000,
        discountPercentage: 5,
        discountedAmount: 5700,
        total: 108300
        },
            //productos importacion
            contenidopr:[]
        };
    },
    computed: {
        totalcantidad(){
            var total = 0;
      this.contenidopr.forEach(el => {
          total += parseInt(el.cantidad)
      });
      return total;
        },
        totalcosto(){
            var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.cantidad*el.precio)
      });
      return total;
        },
        totalfac(){
            var total = 0;
      this.total_factura.forEach(el => {
          total += parseFloat(el.total_factura)
      });
      return total;
        },
        totalliq(){
      var total = 0;
      var totalprod=0;
      totalprod=this.totalfac+this.totalcosto;
      return totalprod;
        },
        nuevocostounit(){
            var total=0;
          total = this.valorfactura1 / this.totalcantidad;
           return total
        },
        nuevocostounit2(){
            var total=0;
          total = this.valorfactura2 / this.totalcantidad;
           return total
        },
        nuevocostounit3(){
            var total=0;
          total = this.valorfactura3 / this.totalcantidad;
           return total
        },
        totalliquid2(){
            var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.cantidad)*(parseFloat(el.precio)+this.nuevocostounit+this.nuevocostounit2+this.nuevocostounit3);
      });
      return total;
        }

    },
    methods: {
        listarliquid(){
            if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        var url = "/api/verliquid";
        axios
          .put(url, { id: this.idrecupera })
          .then(res => {
            let data = res.data[0];

            //this.codigo_proveedor = "PR0" + data.id_proveedor;
           this.campo=data.id_importacion;
           this.estado=data.estado;
          })
          .catch(err => {
            //console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
        },

        listarFactura(){
            if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        var url = "/api/traerfactliquid/"+this.idrecupera;
        axios
          .get(url)
          .then(res => {
            let data = res.data[0];
            this.cabecera = res.data.recupera1;
            //this.codigo_proveedor = "PR0" + data.id_proveedor;
           this.total_factura=res.data;
           console.log(res.data);
           this.descripcion_factura=data.descripcion;
           var cantidad=this.totalcantidad;
           this.valorfactura1=parseFloat(data.total_factura);
           this.descripcion_factura2=res.data[1].descripcion;
           this.valorfactura2=parseFloat(res.data[1].total_factura);
            if(!res.data[2].descripcion){
            this.descripcion_factura3="Courier";
           this.valorfactura3=parseFloat(res.data[1].total_factura);
            }else{
            this.descripcion_factura3=res.data[2].descripcion;
           this.valorfactura3=parseFloat(res.data[2].total_factura);
            }
          })
          .catch(err => {
            //console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
        },
        liquidar(){
            axios.put("/api/liquidar",{
                id:this.idrecupera,
                total:this.totalcosto,
                totalfac:this.totalfac
            }).then(res =>{
                //console.log("LIquidado");
                this.$router.push("/compras/liquidacion");
            }).catch();
        },
        listarprod() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/traerproductoimport/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          this.contenidopr = res.data;
          //console.log(this.contenidopr);
        })
        .catch(err => {
          //console.log(err);
        });
    },
    },
    mounted() {
        this.listarliquid();
        this.listarFactura();
        this.listarprod();
    },
}
</script>
