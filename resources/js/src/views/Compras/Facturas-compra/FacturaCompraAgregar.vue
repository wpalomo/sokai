<template>
  <div class="vx-row">
    <!-- MULTIPLE COLUMNS-->
    <div class="vx-col w-full mb-base">
      <vx-card>
        <div class="vx-row">
          <vs-divider position="left">
            <h3>Factura</h3>
          </vs-divider>
          <div class="vx-col sm:w-1/6 w-full mb-2">
            <vs-input class="w-full" label="Nº de Factura" v-model="nroFacturaProveedor" />
            <div v-show="error" v-if="!nroFacturaProveedor">
          <div v-for="err in errornrofactura" :key="err" v-text="err" class="text-danger"></div>
      </div>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <label class="vs-input--label">Fecha Emision</label>
            <flat-pickr :config="configdateTimePicker" class="w-full" v-model="femision" />
            <div v-show="error" v-if="!femision">
          <div v-for="err in errorfech_emision" :key="err" v-text="err" class="text-danger"></div>
      </div>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <label class="vs-input--label">Fecha Validez</label>
            <flat-pickr :config="configdateTimePicker" class="w-full" v-model="fvalidez" />
            <div v-show="error" v-if="!fvalidez">
          <div v-for="err in errorfech_validez" :key="err" v-text="err" class="text-danger"></div>
      </div>
          </div>
          <div class="vx-col w-1/2 mb-6">
            <vs-input class="w-full" label="Número de Autorizacion" v-model="nroAutorizacion" />
            <div v-show="error" v-if="!nroAutorizacion">
          <div v-for="err in errornroautorizacion" :key="err" v-text="err" class="text-danger"></div>
      </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-2">
            <vs-select
              placeholder="Selecciona el tipo de sustento"
              class="selectExample w-full"
              label="Tipo Sustento"
              vs-multiple
              autocomplete
              v-model="tipoSustento"
            >
              <vs-select-item
                v-for="data in sustentos"
                :key="data.id_sustento"
                :value="data.id_sustento"
                :text="data.descrip_sustento"
              />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-2">
            <vs-select
              placeholder="Selecciona destino"
              class="selectExample w-full"
              label="Destino del Pago"
              vs-multiple
              autocomplete
              v-model="destPago"
            >
              <vs-select-item value="Pago a residentes" text="Pago a residentes" />
              <vs-select-item value="Pago a no residentes" text="Pago a no residentes" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Gastos de Importacion</label>
            <ul class="demo-alignment">
              <li style="margin: 13px 1.5rem;">
                <vs-radio v-model="gastos" vs-value="1">Si</vs-radio>
              </li>
              <li style="margin: 13px 1.5rem;">
                <vs-radio v-model="gastos" vs-value="0">No</vs-radio>
              </li>
            </ul>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6" v-if="gastos==1">
            <vs-select
              class="selectExample w-full"
              label="Nro Importacion"
              vs-multiple
              autocomplete
              v-model="nro_importacion"
            >
              <vs-select-item
                v-for="(data,index) in imports"
                :key="index"
                :value="data.id_importacion"
                :text="data.cod_importacion" 
              />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/2 ml-auto mr-auto w-full mb-2">
            <vs-input class="w-full" label="Orden de compra" v-model="ordcompra" />
          </div>
        </div>
        <div class="vx-row">
          <vs-divider position="left">
            <h3>Proveedor</h3>
          </vs-divider>
          <div class="vx-col w-full mb-2">
            <div class="dropdown-button-container">
              <vs-dropdown>
                <a class="flex items-center">
                  Añadir Proveedor
                  <i class="material-icons">expand_more</i>
                </a>
                <div v-show="error" v-if="!id_proveedor">
          <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
      </div>
                <vs-dropdown-menu>
                  <vs-dropdown-item
                    class="text-center"
                    divider
                    @click="popupActive2=true,tipomodal=1"
                  >Buscar Proveedor</vs-dropdown-item>
                  <vs-dropdown-item class="text-center" divider @click="crear()">Crear Proveedor</vs-dropdown-item>
                </vs-dropdown-menu>
              </vs-dropdown>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-2">
            <vs-input class="w-full" label="Proveedor" v-model="proveedor" disabled />
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-2">
            <vs-select
              class="selectExample w-full"
              label="Provincia"
              vs-multiple
              autocomplete
              v-model="provincia"
              disabled
            >
              <vs-select-item
                v-for="data in provincias"
                :key="data.id_provincia"
                :value="data.id_provincia"
                :text="data.nombre"
              />
            </vs-select>
            
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-2">
            <vs-select
              class="selectExample w-full"
              label="Cliente Asociado"
              vs-multiple
              autocomplete
              v-model="cliente"
            >
              <vs-select-item
                v-for="data in clients"
                :key="data.id_cliente"
                :value="data.id_cliente"
                :text="data.nombre"
              />
            </vs-select>
          </div>

          <vs-popup
            classContent="popup-example"
            title="Seleccione Proveedor"
            :active.sync="popupActive2"
          >
            <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
              <vs-input
                class="mb-4 mr-4 w-full"
                v-model="buscarprov"
                @keyup="listarproveedor(1,buscarprov,cantidadprov)"
                v-bind:placeholder="i18nbuscarprov"
              />
            </div>
            <vs-table
              stripe
              v-model="cuentaarray5"
              @selected="handleSelected5"
              :data="contenidoprov"
            >
              <template slot="thead">
                <vs-th>Nombre Proveedor</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].nombre_proveedor">{{ data[indextr].nombre_proveedor }}</vs-td>
                  <vs-td
                    :data="data[indextr].identif_proveedor"
                  >{{ data[indextr].identif_proveedor }}</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </vs-popup>
        </div>
        <div class="vx-row">
          <div class="vx-col w-full mb-2">
            <label class="vs-input--label">Descripcion</label>
            <vs-textarea v-model="descripcion" />
          </div>
        </div>
        <vs-divider position="left">
          <h3>Productos</h3>
        </vs-divider>
        <div class="p-base">
          <a
            class="flex items-center cursor-pointer mb-4"
            @click="abrirproductos()"
          >Añadir Productos</a>
          <vs-table hoverFlat :data="contenidoprod" style="font-size: 12px;">
            <template slot="thead">
              <!--<vs-th>CÓDIGO</vs-th>-->
              <vs-th>NOMBRE</vs-th>
              <vs-th>CANTIDAD</vs-th>
              <vs-th>COSTO</vs-th>
              <vs-th>DESCUENTO</vs-th>
              <vs-th>IVA</vs-th>
              <vs-th>SUBTOTAL</vs-th>
              <!--<vs-th>ELIMINAR</vs-th>-->
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                <!--<vs-td :data="tr.codigo">{{ tr.codigo }}</vs-td>-->
                <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
                <vs-td :data="tr.cantidad" style="width:150px!important;">
                  <vs-input class="w-full" placeholder="cantidad" @keypress="solonumeros" v-model="tr.cantidad" />
                  <div v-show="error" v-if="!tr.cantidad">
                    <div
                      v-for="err in tr.errorcant_ingreso"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.precio" style="width:150px!important;">
                  <vs-input
                    class="w-full"
                    @keypress="solodecimales"
                    placeholder="$0.00"
                    v-model="tr.precio"
                  />
                  <div v-show="error" v-if="!tr.precio">
                    <div
                      v-for="err in tr.errorcost_unit_ingreso"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.descuento" style="width:200px!important;">
                  <vx-input-group>
                    <vs-input
                      class="w-full"
                      @keypress="solodecimales"
                      placeholder="$0.00"
                      v-model="tr.descuento"
                    />
                    <template slot="append">
                      <div class="append-text btn-addon">
                        <button class="botonstl" :class="{'botonstl elejido':tr.p_descuento==1,'botonstl':tr.p_descuento!=1}" @click="tr.p_descuento=1">$</button>
                        <button class="botonstl" :class="{'botonstl elejido':tr.p_descuento==0,'botonstl':tr.p_descuento!=0}" @click="tr.p_descuento=0">%</button>
                      </div>
                    </template>
                  </vx-input-group>
                </vs-td>
                <vs-td :data="tr.iva" style="width:200px!important;">
                  <vs-select class="selectExample w-full" vs-multiple v-model="tr.iva">
                    <vs-select-item
                      :key="res.id_iva"
                      :value="res.id_iva"
                      :text="res.nombre"
                      v-for="res in contenidoiva"
                    />
                  </vs-select>
                </vs-td>
                <vs-td v-if="tr.p_descuento!=1" v-model="tr.sub">{{ (tr.cantidad*tr.precio-(((tr.cantidad*tr.precio)*tr.descuento)/100) )| currency }}</vs-td>
                <vs-td v-else v-model="tr.sub">{{ (tr.cantidad*tr.precio)-tr.descuento | currency }}</vs-td>
                <vs-td :data="tr.dolar">
                  <feather-icon
                    icon="TrashIcon"
                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                    class="ml-2 cursor-pointer"
                    @click="borrarprd(index)"
                  />
                </vs-td>
              </vs-tr>
            </template>
          </vs-table>
          <vs-table hoverFlat class="w-1/2 ml-auto mt-4" :data="invoiceData">
            <vs-tr>
              <vs-th>SubTotal Neto</vs-th>
              <vs-td>{{ subtotalpr | currency }}</vs-td>
            </vs-tr>
            <vs-tr v-if="subtotalivapr12>0">
              <vs-th>SUBTOTAL IVA 12%</vs-th>
              <vs-td>{{ subtotalivapr12 | currency }}</vs-td>
            </vs-tr>
            <vs-tr v-if="ivapr12>0">
              <vs-th>Valor IVA 12%</vs-th>
              <vs-td>{{ ivapr12 | currency }}</vs-td>
            </vs-tr>
            <vs-tr v-if="subtotalivapr14>0">
              <vs-th>SUBTOTAL IVA 14%</vs-th>
              <vs-td>{{ subtotalivapr14 | currency }}</vs-td>
            </vs-tr>
            <vs-tr v-if="ivapr14>0">
              <vs-th>Valor IVA 14%</vs-th>
              <vs-td>{{ ivapr14 | currency }}</vs-td>
            </vs-tr>

            <vs-tr v-if="subtotalivapr0>0">
              <vs-th>SUBTOTAL IVA 0%</vs-th>
              <vs-td>{{ subtotalivapr0 | currency }}</vs-td>
            </vs-tr>
            <vs-tr v-if="ivapr0>0">
              <vs-th>Valor IVA 0%</vs-th>
              <vs-td>{{ ivapr0 | currency }}</vs-td>
            </vs-tr>
            <vs-tr v-if="subtotalivaprno>0">
              <vs-th>NO OBJETO DE IMPUESTO</vs-th>
              <vs-td>{{ subtotalivaprno | currency }}</vs-td>
            </vs-tr>
            <vs-tr v-if="ivaprno>0">
              <vs-th>VALOR NO OBJETO DE IMPUESTO</vs-th>
              <vs-td>{{ ivaprno | currency }}</vs-td>
            </vs-tr>
            <vs-tr v-if="subtotalivaprex>0">
              <vs-th>EXENTO DE IVA</vs-th>
              <vs-td>{{ subtotalivaprex | currency }}</vs-td>
            </vs-tr>
            <vs-tr v-if="ivaprex>0">
              <vs-th>VALOR EXENTO DE IVA</vs-th>
              <vs-td>{{ ivaprex | currency }}</vs-td>
            </vs-tr>
            <vs-tr>
              <th>Total Descuento</th>
              <td>{{ totaldesc | currency }}</td>
            </vs-tr>
            <vs-tr>
              <th>Total Factura</th>
              <td>{{ totalfac | currency }}</td>
            </vs-tr>
          </vs-table>
        </div>
        <vs-popup
          classContent="popup-example"
          title="Seleccione Producto"
          :active.sync="popupActive3"
        >
          <vs-input
            class="mb-4 mr-4 w-full"
            v-model="buscarprod"
            @keyup="listarproducto(1,buscarprod,cantidadpprod)"
            v-bind:placeholder="i18nbuscarprod"
          />
          <vs-table
            stripe
            v-model="cuentaarrayprod"
            @selected="handleSelectedprod"
            :data="contenidoproduc"
          >
            <template slot="thead">
              <vs-th>Código</vs-th>
              <vs-th>Nombre</vs-th>
              <vs-th>Descripcion</vs-th>
              <vs-th>Marca</vs-th>
              <vs-th>Modelo</vs-th>
              <vs-th>Costo</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                <vs-td v-if="tr.cod_principal">{{tr.cod_principal}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.nombre">{{tr.nombre}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.descripcion">{{tr.descripcion}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.nombremarca">{{tr.nombremarca}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.nombremodelo">{{tr.nombremodelo}}</vs-td>
                <vs-td v-else>-</vs-td>
                <vs-td v-if="tr.costo_total">{{tr.costo_total}}</vs-td>
                <vs-td v-else>-</vs-td>
              </vs-tr>
            </template>
          </vs-table>
        </vs-popup>
        <vs-divider position="left">
          <h3>Total Facturas</h3>
        </vs-divider>
        <div class="vx-row leading-loose p-base">
          <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
            <label class="vs-input--label">SALDO TOTAL</label>
            <h1>{{ totalfac | currency }}</h1>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
            <label class="vs-input--label">SALDO PENDIENTE</label>
            <h1>{{ totalfac-totalpagado | currency }}</h1>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
            <label class="vs-input--label">SALGO PAGADO</label>
            <h1>{{ totalpagado | currency }}</h1>
          </div>
        </div>
        <vs-divider position="left" class="flexy">
          <h3>Retenciones</h3>
          <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="verretencion" vs-value="Si" style="margin-top: 4px;">
            <span slot="off">No</span>
          </vs-switch>
        </vs-divider>
        <transition name="slide-fade">
          <div class="vx-row leading-loose p-base" v-show="verretencion">
            <div class="w-full">
              <div class="vx-row hovertrash" v-for="(tr,index) in valorretenciones" :key="index">
                <div class="w-2/3 ml-auto mr-auto">
                  <div class="vx-row">
                    <div class="vx-col md:w-3/5 w-full mb-2 ml-auto text-center">
                      <label class="vs-input--label">Valores por IVA</label>
                      <vs-select
                        :disabled="totalpr<=0  || (totalef==100 && tr.iva==null)"
                        placeholder="Selecciona la retención"
                        @change="agregarretencion()"
                        autocomplete
                        class="selectExample w-full"
                        v-model="tr.iva"              
                      >
                        <vs-select-item
                          v-for="(tr,index) in listretenciones"
                          :key="index"
                          :value="tr"
                          :text="tr.descrip_retencion"
                          v-if="tr.tipo_retencion=='Retencion IVA Ventas'"
                        />
                      </vs-select>
                    </div>
                    <div class="flex-1 mb-2 mr-3 text-center">
                      <vs-input
                        label="Base"
                        disabled
                        class="w-full"
                        v-bind:value="ivapr12"
                      />
                    </div>
                    <div class="flex-1 mb-2 mr-3 text-center">
                      <vs-input
                        label="Porcentaje"
                        class="w-full"
                        disabled
                        v-bind:value="tr.porcentajeiva"
                      />
                    </div>
                    <div class="flex-1 mb-2 text-center">
                      <vs-input label="Cantidad" class="w-full" disabled v-bind:value="tr.cantidadiva" />
                    </div>
                  </div>
                  <div class="vx-row">
                    <div class="vx-col md:w-3/5 w-full mb-2 mr-auto text-center">
                      <label class="vs-input--label">Valores por RENTA</label>
                      <vs-select
                        :disabled="totalpr<=0"
                        placeholder="Selecciona la retención"
                        @change="agregarretencion()"
                        autocomplete
                        class="selectExample w-full"
                        v-model="tr.renta"
                      >
                        <vs-select-item
                          v-for="(tr,index) in listretenciones"
                          :key="index"
                          :value="tr"
                          :text="tr.descrip_retencion"
                          v-if="tr.tipo_retencion=='Retencion Fuente Ventas'"
                        />
                        <!--v-if="tr.tipo_retencion=='Retencion Fuente Ventas'"-->
                      </vs-select>
                    </div>
                    <div class="flex-1 mb-2 mr-3 text-center">
                      <vs-input
                        label="Base"
                        :disabled="totalpr<=0"
                        class="w-full"
                        @change="agregarretencion()"
                        v-model="tr.baserenta"
                      />
                    </div>
                    <div class="flex-1 mb-2 mr-3 text-center">
                      <vs-input
                        label="Porcentaje"
                        class="w-full"
                        disabled
                        v-model="tr.porcentajerenta"
                      />
                    </div>
                    <div class="flex-1 mb-2 text-center">
                      <vs-input label="Cantidad" class="w-full" disabled v-model="tr.cantidadrenta" />
                    </div>
                    <vs-divider
                      v-if="valorretenciones.length>1 && valorretenciones.length-1 != index"
                      position="left"
                    ></vs-divider>
                  </div>
                </div>
                <feather-icon
                  v-if="index>=1"
                  icon="TrashIcon"
                  style="position: absolute !important;right: 125px;margin-top: 70px;display: none;"
                  svgClasses="w-5 h-5 hover:text-danger stroke-current"
                  class="ml-2 cursor-pointer trasher"
                  @click="eliminararrayretencion(index)"
                />
              </div>
            </div>
          </div>
        </transition>
        <vs-divider position="left" class="flexy">
          <h3>Créditos</h3>
          <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="vercreditos" vs-value="Si" style="margin-top: 4px;">
            <span slot="off">No</span>
          </vs-switch>
        </vs-divider>
        <transition name="slide-fade">
          <div class="vx-row leading-loose p-base" v-show="vercreditos">
            <div class="vx-col sm:w-1/2 w-full mb-2 text-center">
              <label class="vs-input--label">Periodo de pago</label>
              <vs-select
                @change="cambiopagopal()"
                placeholder="Selecciona el periodo de pago"
                :disabled="totalpr<=0"
                autocomplete
                class="selectExample w-full"
                v-model="periodo_credito"
              >
                <vs-select-item value text="Selecciona el periodo" />
                <vs-select-item value="Dias" text="Dias" />
                <vs-select-item value="Semanas" text="Semanas" />
                <vs-select-item value="Meses" text="Meses" />
                <vs-select-item value="Años" text="Años" />
              </vs-select>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
              <vs-input
                @change="cambiopagopal()"
                class="w-full text-center"
                label="Plazos de pago"
                :disabled="totalpr<=0"
                v-model="plazos_credito"
              />
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
              <vs-input
                @change="cambiopagopal()"
                class="w-full text-center"
                label="Monto de pago"
                :disabled="totalpr<=0"
                v-model="monto_credito"
              />
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
              <vs-input
                class="w-full text-center"
                disabled
                label="Pago por letra"
                v-model="pago_credito"
              />
            </div>
          </div>   
        </transition>   
        <vs-divider position="left" class="flexy">
          <h3>Pagos</h3>
          <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="verpagos" vs-value="Si" style="margin-top: 4px;">
            <span slot="off">No</span>
          </vs-switch>
        </vs-divider>
        <transition name="slide-fade">
          <div class="vx-row leading-loose p-base" v-show="verpagos">
            <div class="w-full">
              <div class="vx-row hovertrash" v-for="(tr,index) in valorpagos" :key="index">
                <div
                  class="vx-col w-full mb-2 text-center ml-auto sm:w-1/3"
                >
                  <label class="vs-input--label">Método de pago</label>
                  <vs-select
                    :disabled="totalpr<=0"
                    placeholder="Selecciona el método de pago"
                    @change="agregarpago()"
                    autocomplete
                    class="selectExample w-full"
                    v-model="tr.metodo_pago"
                  >
                    <vs-select-item value="Efectivo" text="Efectivo" />
                    <vs-select-item value="Deposito" text="Deposito" />
                    <vs-select-item value="Cheque" text="Cheque" />
                    <vs-select-item value="Debito" text="Debito" />
                    <vs-select-item value="Tarjeta Credito" text="Tarjeta Credito" />
                    <vs-select-item value="Caja" text="Caja" />
                  </vs-select>
                </div>
                <div
                  class="vx-col sm:w-1/3 w-full mb-2 text-center"
                  v-if="tr.metodo_pago!='Otro' && tr.metodo_pago!='Efectivo' && tr.metodo_pago!=''"
                >
                  <vs-select
                    class="selectExample w-full"
                    label="Banco"
                    vs-multiple
                    autocomplete
                    v-model="tr.banco_pago"
                    :disabled="totalpr<=0"
                  >
                    <vs-select-item
                      v-for="data in bancos"
                      :key="data.id_banco"
                      :value="data.id_banco"
                      :text="data.nombre_banco"
                    />
                  </vs-select>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-2 text-center mr-auto">
                  <vs-input
                    :disabled="totalpr<=0"
                    class="w-full text-center"
                    label="Cantidad"
                    @change="agregarpago()"
                    v-model="tr.cantidad_pago"
                  />
                </div>
                <div
                  class="vx-col sm:w-1/6 w-full mb-2 text-center"
                  v-if="tr.metodo_pago=='Tarjeta Credito'"
                >
                  <vs-input
                    :disabled="totalpr<=0"
                    class="w-full text-center"
                    label="Nro. Tarjeta"
                    v-model="tr.tarjeta"
                  />
                </div>
                <div
                  class="vx-col sm:w-1/6 w-full mb-2 text-center"
                  v-if="tr.metodo_pago=='Cheque' || tr.metodo_pago=='Debito' || tr.metodo_pago=='Deposito'"
                >
                  <vs-input
                    :disabled="totalpr<=0"
                    class="w-full text-center"
                    label="Nro de transacción"
                    v-model="tr.nro_trans"
                  />
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-2 text-center" v-if="tr.metodo_pago=='Caja'">
                  <vs-select
                    class="selectExample w-full"
                    label="Caja Asignada"
                    vs-multiple
                    autocomplete
                    v-model="tr.id_caja"
                  >
                    <vs-select-item
                      v-for="data in caja"
                      :key="data.id_caja"
                      :value="data.id_caja"
                      :text="data.descrip_caja"
                    />
                  </vs-select>
                </div>
                <feather-icon
                  v-if="index>=1"
                  icon="TrashIcon"
                  style="position: absolute!important;right: 15px;margin-top: 38px;display:none"
                  svgClasses="w-5 h-5 hover:text-danger stroke-current"
                  class="ml-2 cursor-pointer trasher"
                  @click="eliminararraypagos(index)"
                />
              </div>
            </div>
          </div>
        </transition>
        <div class="vx-row">
          <div class="vx-col w-full mt-4">
            <vs-button
              color="success"
              type="filled"
              @click="guardarFact()"
            >GUARDAR</vs-button>
            <vs-button color="danger" type="filled" to="/compras/factura-compra">CANCELAR</vs-button>
          </div>
        </div>
        <vs-popup classContent="popup-example" title="Cree Proveedor" :active.sync="popupActive4">
         <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <!--<vs-input  class="w-full" label="Codigo" placeholder="PR010" v-model="codigo_proveedor" />-->
            <vs-input class="w-full" v-if="tipocod" label="Código" v-model="codigo_proveedor" />
            <vs-input class="w-full" v-else label="Código" disabled :value="codigo_proveedor" />
          </div>
         
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Grupo"
              vs-multiple
              autocomplete
              v-model="grupo"
              @change="getGrupoProv()"
            >
              <vs-select-item
                v-for="(data,index) in grupos"
                :key="index"
                :value="data.id_grupoprov"
                :text="data.nombre_grupoprov"
              />
            </vs-select>
            
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input
              type="email"
              class="w-full"
              label="Nombre"
              v-model="nombre"
            />
            <div v-show="errorprov" v-if="!nombre">
          <div v-for="err in errornombre" :key="err" v-text="err" class="text-danger"></div>
      </div>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Tipo Identificacion"
              vs-multiple
              autocomplete
              v-model="tipoIdent"
              v-validate="'required'"
              name="tipoid"
            >
              <vs-select-item value="Cedula" text="Cedula" />
              <vs-select-item value="Ruc" text="Ruc" />
              <vs-select-item value="Pasaporte" text="Pasaporte" />
            </vs-select>
            <div v-show="errorprov" v-if="!tipoIdent">
          <div v-for="err in errortipoIdent" :key="err" v-text="err" class="text-danger"></div>
      </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6" v-if="!tipoIdent" hidden>
            <!--<vs-input  class="w-full" label="Identificacion"  v-model="identificacion" />-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-if="tipoIdent=='Cedula'"
              
              @keyup="validarcedula"
              maxlength="10"
            />
            <!--@keypress="solonumeros"-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else-if="tipoIdent=='Ruc'"
              
              @keyup="validarruc"
              maxlength="13"
            />
            <!--@keypress="solonumeros"-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else
              maxlength="15"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6" v-else>
            <!--<vs-input  class="w-full" label="Identificacion"  v-model="identificacion" />-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-if="tipoIdent=='Cedula'"
              
              @keyup="validarcedula"
              maxlength="10"
            />
            <!--@keypress="solonumeros"-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else-if="tipoIdent=='Ruc'"
              
              @keyup="validarruc"
              maxlength="13"
            />
            <!--@keypress="solonumeros"-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else
              maxlength="15"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Contribuyente Especial</label>
            <vs-checkbox v-model="contribuyente" vs-value="1" ></vs-checkbox>
          </div>
         
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Beneficiario" v-model="beneficiario" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Contacto" v-model="contacto" />
            <div v-show="errorprov" v-if="!contacto">
              <span class="text-danger" v-for="err in errorcontacto" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Direccion" v-model="direccion" />
             <div v-show="errorprov" v-if="!direccion">
              <span class="text-danger" v-for="err in errordireccion" :key="err" v-text="err"></span>
            </div>
          </div>
        </div>

        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="No Casa" v-model="nrcasa" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              placeholder="Seleccione Provincia"
              class="selectExample w-full"
              label="Provincia"
              vs-multiple
              autocomplete
              v-model="provinciaprov"
              
              @change="getCiudadesProv()"
            >
              <!--<vs-select-item value=1 text="Pichincha" />-->
              <vs-select-item
                v-for="(data,index) in provincias"
                :key="index"
                :value="data.id_provincia"
                :text="data.nombre"
              />
            </vs-select>
            <div v-show="errorprov" v-if="!provinciaprov">
              <span class="text-danger" v-for="err in errorprovincia" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              placeholder="Seleccione Ciudad"
              class="selectExample w-full"
              label="Ciudad"
              vs-multiple
              autocomplete
              v-model="ciudad"
              
              @selected="getCiudadesProv()"
            >
              <!--<vs-select-item value=1 text="Quito" />-->
              <vs-select-item
                v-for="(data,index) in ciudades"
                :key="index"
                :value="data.id_ciudad"
                :text="data.nombre"
              />
            </vs-select>
            <div v-show="errorprov" v-if="!ciudad">
              <span class="text-danger" v-for="err in errorciudad" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Telefono" v-model="telefono" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6" v-if="idrecupera">
            <vs-select
              class="selectExample"
              label="Estado"
              vs-multiple
              autocomplete
              v-model="estado"
              
            >
              <vs-select-item value="1" text="Activo" />
              <vs-select-item value="0" text="Inactivo" />
            </vs-select>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-select
              placeholder="Seleccione Banco"
              class="selectExample w-full"
              label="Banco"
              vs-multiple
              autocomplete
              v-model="banco"
              @change="getBancosProv()"
            >
              <!--<vs-select-item value=1 text="Pichincha" />-->
              <vs-select-item
                v-for="(data,index) in bancos"
                :key="index"
                :value="data.id_banco"
                :text="data.nombre_banco"
              />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Tipo Cuenta"
              vs-multiple
              autocomplete
              v-model="tipCuenta"
            >
              <vs-select-item value="1" text="Corriente" />
              <vs-select-item value="2" text="Ahorros" />
              <vs-select-item value="3" text="Virtual" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-input class="w-full" label="Cuenta Banco" v-model="ctaBanco" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Pagos" v-model="pago" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Plazo" v-model="plazo" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Dias pago" v-model="dpagos" />
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6" >
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="ctacontable" :value="idContable" disabled/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <!-- -->
                  <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
        </div>
        <div>
          <label class="vs-input--label">Comentario</label>
          <vs-textarea v-model="comentario" height="80" />
        </div>
        <vs-divider position="left">Datos Sri</vs-divider>
        <div class="vx-row">
          
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Tipo Comprobante"
              vs-multiple
              autocomplete
              v-model="tcomprobante"
              @change="getTipoComprob()"
            >
              <vs-select-item
                v-for="(data,index) in tipcomprob"
                :key="index"
                :value="data.id_tipcomprobante"
                :text="data.descrip_tipcomprob"
              />
            </vs-select>
            <!--@change="getTipoComprob()"-->
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input
              class="w-full"
              label="Serie"
              v-model="serie"
              
              maxlength="7"
            />
            <!--@keypress="solonumeros"-->
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Fecha Validez</label>
            <flat-pickr :config="configdateTimePicker" v-model="fvalidez" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Factura Inicial" v-model="rangmin" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Factura Final" v-model="ranmax" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="#Autorizacion" v-model="nroAutorizacion" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Contribuye Sri</label>
            <vs-checkbox v-model="contribuyeSri" vs-value="1" ></vs-checkbox>
          </div>
          
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <ul class="demo-alignment">
              <li>
                <vs-radio v-model="tipElectronico" vs-value="0">Offline</vs-radio>
              </li>
              <li>
                <vs-radio v-model="tipElectronico" vs-value="1">Online</vs-radio>
              </li>
            </ul>
          </div>
        </div>
        <vs-divider position="left">Retenciones Aplicables</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-3/5 w-full mb-6">
            <vs-select
              class="selectExample w-full"
              label="Impuesto Retencion"
              vs-multiple
              autocomplete
              v-model="impstRetencion"
              @change="getImpFuente()"
            >
            <vs-select-item v-for="(data,index) in retfuente" :key="index" :value="index" :text="data.descrip_retencion"/>
            </vs-select>
           
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6"></div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Codigo Sri Impuesto"
              vs-multiple
              autocomplete
              v-model="codSriImp"
            >
              <vs-select-item
                v-for="(data,index) in impfuente"
                :key="index"
                :value="data.cod_imp"
                :text="data.cod_imp"
              />
            </vs-select>
            
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-3/5 w-full mb-6">
            <vs-select
              class="selectExample w-full"
              label="Retencion Iva"
              vs-multiple
              autocomplete
              v-model="retencionIva"
              @change="getImpIva()"
            >
              <vs-select-item
                v-for="(data,index) in retiva"
                :key="index"
                :value="index"
                :text="data.descrip_retencion"
              />
            </vs-select>

          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6"></div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Codigo Sri Iva"
              vs-multiple
              autocomplete
              v-model="codSriIva"
            >
              <vs-select-item
                v-for="(data,index) in impiva"
                :key="index"
                :value="data.cod_imp"
                :text="data.cod_imp"
              />
            </vs-select>
          </div>
        </div>
        <vs-divider />
        <div class="vx-col sm:w-1/6 w-full mb-6">
          <vs-input class="w-full" label="Cash Manager" v-model="idbanco" />
        </div>
        <vs-divider />
        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-button color="success" type="filled" @click="guardarproveedor()">GUARDAR</vs-button>
            <vs-button color="warning" type="filled" @click="borrarproveedor()">BORRAR</vs-button>
            <vs-button color="danger" type="filled" @click="popupActive4=false,borrarproveedor()">CANCELAR</vs-button>
          </div>
        </div>
        <vs-popup title="Plan Cuentas" class="peque" :active.sync="activePrompt3">
          <div class="con-exemple-prompt">
            <vs-input
              class="mb-4 md:mb-0 mr-4 w-full"
              v-model="buscarplactas"
              @keyup="listarplanctas(1,buscarplactas)"
              v-bind:placeholder="i18nbuscarplanctas"
            />
            <vs-table stripe v-model="cuentaarray3" @selected="handleSelected3" :data="contenidoplanctas">
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
       </vs-popup>
      </vx-card>
    </div>
  </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import $ from "jquery";
const axios = require("axios");
export default {
  data() {
    return {
      idrecupera: null,
      numero: "",
      cont: "",
      gastos: 0,
      nro_importacion: "",
      tipProveedor: "",
      moneda: "",
      proveedor: "",
      nroFacturaProveedor: "",
      especial: null,
      fecha: "",
      ordcompra: "",
      provincia: "",
      tipoSustento: "",
      destPago: "",
      bodega: "",
      provincia: "",
      cliente: "",
      descripcion: "",
      tipIva: "",
      uniDestino: "",
      femision: "",
      fvalidez: "",
      serie: "",
      tipo_pago: "Contado",
      metodo_pago: "",
      pago_ant: null,
      pago_abo: null,
      efectivo: null,
      ret_iva: "0",
      ret_fuente: "0",
      ret_fuente_porc: "0",
      ret_iva_porc: "0",
      cant_efectivo: "",
      caja_chica: null,
      cant_caja: "",
      id_caja: "",
      nro_caja: "",
      transacion: null,
      tipo_trans: "",
      nro_trans: "",
      institucion_trans: "",
      ftransacion: "",
      tarjeta: null,
      cant_tarjeta: "",
      nro_tarjeta: "",
      insti_tarjeta: "",
      nro_pago: "",
      plazo_pago: "",
      pago_empresa: "",
      descuento: "",
      iva: "",
      nroAutorizacion: "",
      configdateTimePicker: {
        locale: SpanishLocale
      },
      //modal proveeedores
      popupActive2: false,
      paginationprov: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      paginaprov: 1,
      cantidadprov: 20,
      offset: 0,
      buscarprov: "",
      criterioprov: "nombre_proveedor",
      modalretencion: false,
      modaliva: false,
      contenidoretencion: [],
      //cantidadiva
      cantiva: "",
      //otros valores
      gridApi: null,
      contenidoprov: [],
      i18nbuscarprov: this.$t("i18nbuscar"),
      cuentaarray5: [],
      //modal productos
      buscarpr: "",
      popupActive3: false,
      paginationprod: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      paginaprod: 1,
      cantidadpprod: 10,
      offsetprod: 3,
      contenidoprod: [],
      contenidoproduc: [],
      cuentaarrayprod: [],
      buscarprod: "",
      i18nbuscarprod: this.$t("i18nbuscar"),
      cantidad_factcompra: "",
      precio_factcompra: "",
      descuento_factcompra: "",
      contenidoiva: [],
      //traer sustento
      sustentos: [],
      imports: [],
      provs: [],
      pto: [],
      clients: [],
      monedas: [],
      id_proveedor: "",
      id_producto: "",
      //traer productos
      paginapr: 1,
      cantidadppr: 10,
      offsetpr: 3,
      buscarpr: "",
      paginationpr: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      //traer pagos
      paginapag: 1,
      cantidadpag: 10,
      offsetpag: 3,
      buscarpag: "",
      paginationpag: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      //traer retenciones
      retiva: [],
      cantretiva: "",
      retfuente: [],
      cantretfuente: "",
      valorretencion: [
        {
          cantidad_iva: "",
          iva: "",
          ret_iva: []
        }
      ],
      valorretencionfuente: [
        {
          cantidad_fuente: "",
          fuente: "",
          ret_fuente: []
        }
      ],
      //traer caja
      caja: [],
      //pagos
      valorpagos: [
        {
          metodo_pago: "",
          cantidad_pago: null,
          comentario_pago: "",
          banco_pago: "",
          tarjeta: "",
          cuenta: "",
          nro_trans: "",
          id_caja: ""
        }
      ],
      //creditos
      plazos_credito: null,
      monto_credito: null,
      pago_credito: null,
      periodo_credito: null,
      dolares: false,
      porcentaje: false,
      //traer bancos
      bancos: [],
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
      valorretenciones: [
        {
          iva: null,
          porcentajeiva: null,
          cantidadiva: null,
          renta: null,
          baserenta: null,
          porcentajerenta: null,
          cantidadrenta: null
        }
      ],
      listretenciones: [],
      modofact: 1,
      totalef: null,
      verretencion:false,
      verpagos:false,
      vercreditos:false,
      recueidfact:null,
      //traer provincias
      provincias:[],
      //errores
      error:0,
      errorproveedor:[],
      errornrofactura:[],
      errorfech_emision:[],
      errorfech_validez:[],
      errornroautorizacion:[],
      errorcant_ingreso:[],
      errorcost_unit_ingreso:[],
       //modal agregar proveedores
      popupActive4:false,
      activePrompt3:false,
      codigo_proveedor: "",
      grupo: "",
      nombre: "",
      tipoIdent: "",
      identificacion: "",
      tipo: "",
      contribuyente: null,
      contribesp_valor:"0",
      contribuye_valor:"0",
      beneficiario: "",
      //identificacionBenf:"",
      contacto: "",
      direccion: "",
      nrcasa: "",
      provinciaprov: "",
      ciudad: "",
      telefono: "",
      estado: "",
      banco: "",
      tipCuenta: "",
      ctaBanco: "",
      idbanco: "",
      //nrctaInterbancaria:"",
      pago: "",
      plazo: "",
      dpagos: "",
      ctacontable: "",
      comentario: "",
      tcomprobante: "",
      serie: "",
      fvalidez: "",
      rangmin: "",
      ranmax: "",
      nroAutorizacion: "",
      contribuyeSri: null,
      tipElectronico: "0",
      impstRetencion: "I.R.F. Por Pagar (8%) Arriendos",
      impstRetencionporcent:"",
      retencionIva: "I.V.A. Retenido por Pagar (70%)",
      codSriImp: "",
      codSriIva: "",
      idContable: "",
      //traer grupo-proveedor
      grupos: [],
      //traer impuesto de retencion a la fuente
      impfuente: [],
      //traer impuesto de retencion al iva
      impiva: [],
      //traer tipo comprobante
      tipcomprob: [],
      //traer retencion fuente compra
      retfuente: [],
      ////traer retencion iva compra
      retiva: [],
      //traer
      provincias: [],
      ciudades: [],
      bancos: [],
      codigoen: 0,
      codigoprov: [],
      tipocod:0,
      retencion_nombre:"",
      retencion_iva:"",
      //errores proveedor
      errorprov: 0,
      erroridentificacion: [],
      errorcodigo_proveedor: [],
      errorgrupo: [],
      errornombre: [],
      errortipoIdent: [],
      errordireccion:[],
      errorprovincia:[],
      errorciudad:[],
      errorcontacto: [],
      //plan cuentas
      cuentaarray3:[],
      contenidoplanctas:[],
      i18nbuscarplanctas: this.$t("i18nbuscar"),
      buscarplactas:"",
      tipofactura:"retencioncompra",
    };
  },
  components: {
    flatPickr
  },
  mounted() {
    this.listarproveedor(1, this.buscarprov);
    this.listarproducto(1, this.buscarprod, this.cantidadpprod);
    this.getSustento();
    this.getImportaciones();
    //this.getPtoemision();
    this.abrirCredito();
    this.getCliente();
    this.abrirFactura();
    this.abrirproducto(1, this.buscarpr, this.cantidadppr);
    this.abrirPago(1, this.buscarpag, this.cantidadpag);
    this.getRetFuente();
    this.getRetIva();
    this.getCaja();
    this.getBanco();
    this.listariva();
    this.listarretenciones();
    this.getProvincias();
    //this.baseretencion();
    this.getRetFuenteProv();
    this.getRetIvaProv();
    this.listarplanctas(1,this.buscarplactas);
    this.getProvinciasProv();
    this.getCiudadesProv();
    this.getBancosProv();
    this.getGrupoProv();
    this.getImpFuente();
    this.getImpIva();
    this.getTipoComprob();
    this.leercodigoprov();
  },
  methods: {
    listarproveedor(pageprov, buscarprov) {
      var url =
        "/api/proveedor/" +
        this.usuario.id_empresa +
        "?page=" +
        pageprov +
        "&buscar=" +
        buscarprov;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidoprov = respuesta.recupera;
      });
    },
    handleSelected5(tr) {
      (this.proveedor = `${tr.nombre_proveedor}`),
        (this.tipProveedor = `${tr.grupo}`),
        (this.provincia = `${tr.id_provincia}`),
        (this.id_proveedor = `${tr.id_proveedor}`);
      this.popupActive2 = false;
    },
    handleSelectedprod(tr) {
      this.contenidoprod.push({
        id_detalle: null,
        nombre: tr.nombre,
        codigo: tr.cod_principal,
        cantidad: null,
        precio: null, //tr.pvp_precio1,
        descripcion: tr.descripcion,
        descuento: null, //tr.descuento,
        p_descuento:1,
        iva: tr.iva,
        ice: tr.ice,
        id_producto: tr.id_producto
      });
      this.id_producto = tr.id_producto;
      this.popupActive3 = false;
    },
    handleSelectedret(tr) {
      this.contenidoretencion.push({
        id_retencion_fuente: "",
        base: this.subtotalpr,
        porcentaje_fuente: "0",
        total_fuente: ""
      });
    },
    handleSelectedretiva(tr) {
      this.contenidoretencion.push({
        id_retencion_iva: "",
        base_iva: this.subtotalpr,
        porcentaje_iva: "0",
        total_iva: ""
      });
    },
    listarretenciones() {
      var url = "/api/listarretenciones";
      axios.get(url).then(res => {
        this.listretenciones = res.data;
      });
    },
    agregarretencion() {
      for (var i = 0; i < this.valorretenciones.length; i++) {
        if (this.valorretenciones[i].iva != null) {
          this.valorretenciones[i].baseiva = this.ivapr12;
          this.valorretenciones[i].porcentajeiva =
            this.valorretenciones[i].iva.porcen_retencion + "%";
          this.valorretenciones[i].cantidadiva = (
            (this.ivapr12 * this.valorretenciones[i].iva.porcen_retencion) /
            100
          ).toFixed(2);
        }
        if (this.valorretenciones[i].renta != null) {
          this.valorretenciones[i].porcentajerenta =
            this.valorretenciones[i].renta.porcen_retencion + "%";
          this.valorretenciones[i].cantidadrenta = (
            (this.valorretenciones[i].baserenta *
              this.valorretenciones[i].renta.porcen_retencion) /
            100
          ).toFixed(2);
        }
        if (this.subtotalpr < this.valorretenciones[i].baserenta) {
          this.valorretenciones[i].baserenta = 0.0;
          this.valorretenciones[i].cantidadrenta = 0.0;
          this.$vs.notify({
            title: "La cantidad no puede superar el subtotal",
            color: "danger"
          });
        }
      }
      var totale = 0;
      this.valorretenciones.forEach(el => {
        if (el.baserenta > 0) {
          totale += parseFloat(el.baserenta);
        }
      });
      var totalef = 0;
      this.valorretenciones.forEach(el => {
        if (el.iva != null) {
          totalef += parseFloat(el.iva.porcen_retencion);
        }
      });
      if (
        this.valorretenciones[this.valorretenciones.length - 1].iva ||
        this.valorretenciones[this.valorretenciones.length - 1].renta
      ) {
        this.valorretenciones.push({
          iva: null,
          porcentajeiva: null,
          cantidadiva: null,
          renta: null,
          baserenta: null,
          porcentajerenta: null,
          cantidadrenta: null
        });
      }

      if (this.valorretenciones.length > 1) {
        if (totale > this.subtotalpr) {
          this.$vs.notify({
            title: "La cantidad no puede superar el subtotal",
            color: "danger"
          });
          this.valorretenciones[this.valorretenciones.length - 2].baserenta = 0;
        }
      }
      this.totalef = totalef;
      if (totalef > 100) {
        this.$vs.notify({
          title: "El iva no puede ser mayor al 100%",
          color: "danger"
        });
        this.valorretenciones[
          this.valorretenciones.length - 2
        ].procentajeiva = null;
        this.valorretenciones[this.valorretenciones.length - 2].iva = null;
        this.valorretenciones[
          this.valorretenciones.length - 2
        ].cantidadiva = null;
      }

      if (
        this.valorretenciones[this.valorretenciones.length - 1].iva &&
        this.valorretenciones[this.valorretenciones.length - 1].renta &&
        (this.valorretenciones[this.valorretenciones.length - 2].iva &&
          this.valorretenciones[this.valorretenciones.length - 2].renta)
      ) {
        this.valorretenciones.splice(
          this.valorretenciones[this.valorretenciones.length],
          1
        );
      }
    },
    eliminararrayretencion(id) {
      this.valorretenciones.splice(id, 1);
    },
    listarretencion() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        var url = "/api/abrirretencionp/" + this.idrecupera;
        axios
          .get(url)
          .then(res => {
            if (res.data.length) {
              this.valorretenciones = res.data;
            } else {
              this.valorretenciones = [
                {
                  iva: null,
                  renta: null
                }
              ];
            }
          })
          .catch(err => {
            //console.log(err);
          });
      }
    },
    listariva() {
      let me = this;
      var url = "/api/iva";
      axios
        .get(url)
        .then(function(response) {
          me.contenidoiva = response.data;
        })
        .catch(function(error) {
          //console.log(error);
        });
    },
    cantidiva(id) {
      if (!id) {
        this.iva = 0;
      }
      if (id == 1 || id == 3 || id == 4) {
        this.iva = 0;
      } else {
        this.iva = 12;
      }
      //console.log(this.iva);
    },
    crear() {
      //this.$router.push("/compras/proveedor/agregar");
      this.popupActive4=true;
    },
    solonumeros($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode < 48 || keyCode > 57) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    solodecimales($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    listarproducto(pageprod, buscarprod) {
      var url =
        "/api/productos/" +
        this.usuario.id_empresa +
        "?page=" +
        pageprod +
        "&buscar=" +
        buscarprod;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidoproduc = respuesta.recupera;
      });
    },
    abrirproductos() {
      this.popupActive3 = true;
      this.listarproducto(1, this.buscarprod);
    },
    guardarRetencion19(id_fact) {
      axios.post("/api/guardarretffactcom", {
        valorretenciones: this.contenidoretencion,
        id_factura: 19,
        total_fuente: this.totalretf,
        empresa: this.usuario.id_empresa
      });
    },
    cambiopagopal() {
      if (
        this.periodo_credito != null &&
        this.totalpr > 0 &&
        this.monto_credito == null
      ) {
        this.monto_credito = this.totalpr - this.totalpagado;
        this.plazos_credito = 3;
      }

      if (this.monto_credito != null && this.plazos_credito != null) {
        this.pago_credito = parseFloat(
          this.monto_credito / this.plazos_credito
        ).toFixed(2);
      } else {
        this.pago_credito = parseFloat(0);
      }
    },
    borrarprd(id) {
      this.contenidoprod.splice(id, 1);
    },
    editarFact() {
      axios
        .put("/api/actualfactcompra", {
          id: this.$route.params.id,
          gasto_import: this.gastos,
          id_importacion: this.nro_importacion,
          id_moneda: this.moneda,
          id_proveedor: this.id_proveedor,
          nro_factprov: this.nroFacturaProveedor,
          especial: this.especial,
          fech_factcompra: this.fecha,
          cotizacion: this.cotizacion,
          id_provincia: this.provincia,
          id_sustento: this.tipoSustento,
          destino_pago: this.destPago,
          //id_ptoemision:this.bodega,
          id_ptoemision: this.usuario.id_punto_emision,
          id_cliente_asoc: this.cliente,
          descripcion: this.descripcion,
          tipo_iva: this.tipIva,
          unid_destino: this.uniDestino,
          fech_emision: this.femision,
          fech_validez: this.fvalidez,
          serie: this.serie,
          tipo_pago: this.tipo_pago,
          pago_ant: this.pago_ant,
          //pago_abo:this.pago_abo,
          metodo_pago: this.metodo_pago,
          efectivo: this.efectivo,
          id_retiva: this.ret_iva,
          id_retfuente: this.ret_fuente,
          cant_efectivo: this.cant_efectivo,
          caja_chica: this.caja_chica,
          cant_caja: this.cant_caja,
          id_caja: this.id_caja,
          nro_caja: this.nro_caja,
          transaccion: this.transacion,
          tipo_transaccion: this.tipo_trans,
          nro_transacion: this.nro_trans,
          id_banco: this.institucion_trans,
          fech_transacion: this.ftransacion,
          tarjeta: this.tarjeta,
          cant_tarjeta: this.cant_tarjeta,
          nro_tarjeta: this.nro_tarjeta,
          //insti_tarjeta:"",
          nro_pago: this.nro_pago,
          plazo_pago: this.plazo_pago,
          dias_pago_empresa: this.pago_empresa,
          descuento: this.descuento,
          iva: this.iva,
          contenidopr: this.contenidoprod,
          nro_autorizacion: this.nroAutorizacion,
          base_12: (this.subtotalpr * 12) / 100,
          base_0: this.subtotalivapr0,
          tarifa_no_graba: this.subtotalivaprno,
          otro_imp: this.subtotalivaprex,
          total_factura: this.totalfac,
          //compensacion:this.invoiceData.compensacion,
          porcentaje_retencionf: this.cantretfuente,
          porcentaje_retencioniva: this.cantretiva,
          total_retencionf: this.ret_fuente_porc,
          total_retencioniva: this.ret_iva_porc
          //compensacion:this.invoiceData.compensacion
        })
        .then(res => {
          this.editarProducto(res.data);
          this.guardarRetencion19();
          this.$vs.notify({
            title: "Registro Editado",
            text: "Registro Editado exitosamente",
            color: "success"
          });
          this.$router.push("/compras/factura-compra");
        })
        .catch();
    },
    editarProducto(id_fact) {
      axios.put("/api/actprodfactcompra", {
        contenidopr: this.contenidoprod,
        id_factura: id_fact
      });
    },
    abrirproducto(pagepr, buscarpr, cantidadppr) {
      if (this.$route.params.id) {
        var idfactura = this.$route.params.id;
        let me = this;
        var url = "/api/abrirprodfactcompra/" + idfactura;
        var valores = {
          page: pagepr,
          buscar: buscarpr,
          idfactura: parseInt(idfactura),
          cantidadp: cantidadppr
        };
        axios.get(url, valores).then(function(response) {
          var respuesta = response.data;
          //console.log("hola" + idfactura);
          me.contenidoprod = respuesta.recupera.data;

          me.paginationpr = respuesta.pagination;
          if (cantidadppr > me.paginationpr.total) {
            cantidadppr = me.paginationpr.total;
          }
        });
      }
    },
    abrirPago(pagepag, buscarpag, cantidadpag) {
      if (this.$route.params.id) {
        var idfactura = this.$route.params.id;
        let me = this;
        var url = "/api/abrirpagfactcompra/" + idfactura;
        var valores = {
          page: pagepag,
          buscar: buscarpag,
          idfactura: parseInt(idfactura),
          cantidadp: cantidadpag
        };
        axios.get(url, valores).then(function(response) {
          var respuesta = response.data;
          //console.log("hola" + idfactura);
          me.valorpagos = respuesta.recupera.data;

          me.paginationpag = respuesta.pagination;
          if (cantidadpag > me.paginationpr.total) {
            cantidadpag = me.paginationpr.total;
          }
        });
      }
    },
    abrirCredito() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        var url = "/api/abrircredfactcompra/" + this.idrecupera;
        axios.put(url).then(res => {
          let data = res.data[0];
          this.periodo_credito = data.periodo;
          this.plazos_credito = data.plazo;
        });
      }
    },
    abrirFactura() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        var url = "/api/abrirfactcompra";
        axios
          .put(url, { id: this.idrecupera })
          .then(res => {
            let data = res.data[0];

            //this.codigo_proveedor = "PR0" + data.id_proveedor;
            this.nroFacturaProveedor = data.nro_factprov;
            (this.gastos = data.gasto_import),
              (this.nro_importacion = data.id_importacion),
              (this.moneda = data.id_moneda),
              (this.id_proveedor = data.id_proveedor),
              (this.nroFacturaProveedor = data.nro_factprov),
              (this.ordcompra=data.orden_compra),
              (this.especial = data.especial),
              (this.fecha = data.fech_factcompra),
              (this.cotizacion = data.cotizacion),
              (this.proveedor = data.nombre),
              //this.id_ptoemision=this.usuario.id_punto_emision
              (this.provincia = data.provincias),
              (this.tipoSustento = data.id_sustento),
              (this.destPago = data.destino_pago),
              (this.bodega = data.id_ptoemision), //data.id_ptoemision ,
              (this.cliente = data.id_cliente_asoc),
              (this.descripcion = data.descripcion),
              (this.tipIva = data.tipo_iva),
              (this.uniDestino = data.unid_destino),
              (this.femision = data.fech_emision),
              (this.fvalidez = data.fech_validez),
              (this.serie = data.serie),
              (this.tipo_pago = data.tipo_pago),
              (this.metodo_pago = data.metodo_pago),
              (this.efectivo = data.efectivo),
              (this.ret_iva = data.id_retiva),
              (this.ret_fuente = data.id_retfuente),
              (this.cant_efectivo = data.cant_efectivo),
              (this.caja_chica = data.caja_chica),
              (this.cant_caja = data.cant_caja),
              (this.id_caja = data.id_caja),
              (this.nro_caja = data.nro_caja),
              (this.transacion = data.transaccion),
              (this.tipo_trans = data.tipo_transaccion),
              (this.nro_trans = data.nro_transacion),
              (this.institucion_trans = data.id_banco),
              (this.ftransacion = data.fech_transacion),
              (this.tarjeta = data.tarjeta),
              (this.cant_tarjeta = data.cant_tarjeta),
              (this.nro_tarjeta = data.nro_tarjeta),
              //insti_tarjeta "",
              (this.nro_pago = data.nro_pago),
              (this.plazo_pago = data.plazo_pago),
              (this.pago_empresa = data.dias_pago_empresa),
              (this.descuento = data.descuento),
              (this.iva = data.iva),
              //this.contenidoprod=data.contenidopr,
              (this.nroAutorizacion = data.nro_autorizacion);
            // this.subtotalpr=data.total_neto,
            //this.invoiceData.discountPercentage=data.base_12 ,
            /* this.invoiceData.total=data.base_0 ,
             this.invoiceData.no_graba=data.tarifa_no_graba ,
             this.invoiceData.otro_imp=data.otro_imp ,
             this.invoiceData.total_fact=data.total_factura ,
             this.invoiceData.compensacion=data.compensacion */
          })
          .catch(err => {
            //console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
    },
    getSustento() {
      axios
        .get("/api/traersustento")

        .then(
          function(response) {
            this.sustentos = response.data;
          }.bind(this)
        );
    },
    getImportaciones() {
      axios.get("/api/traerimport").then( response => {
        console.log(response.data);
        this.imports = response.data;
      });
    },
    getPorcentaje() {
      var me = this;
      axios
        .get("/api/abrirporcretfactcompra", {
          params: { id_retfuente: this.ret_fuente }
        })

        .then(
          function(response) {
            //let data = respose.data[0];
            //this.pto = response.data;
            this.cantretfuente = response.data[0].porcen_retencion;
            var num =
              (this.subtotalpr * response.data[0].porcen_retencion) / 100;
            this.ret_fuente_porc = num.toFixed(2);
          }.bind(this)
        );
    },
    getPorcentajeIva() {
      axios
        .get("/api/abrirporcivafactcompra", {
          params: { id_retiva: this.ret_iva }
        })

        .then(
          function(response) {
            //this.pto = response.data;
            //this.cantretiva=response.data;
            var iva = (this.subtotalpr * this.iva) / 100;
            var num = (iva * response.data[0].porcen_retencion) / 100;
            this.cantretiva = response.data[0].porcen_retencion;
            this.ret_iva_porc = num.toFixed(2);
          }.bind(this)
        );
    },
    getPtoemision() {
      axios.get("/api/traerptoemfactcomp").then(
        function(response) {
          this.pto = response.data;
        }.bind(this)
      );
    },
    getCliente() {
      axios
        .get("/api/traerclientfactcomp")

        .then(
          function(response) {
            this.clients = response.data;
          }.bind(this)
        );
    },
    getRetFuente() {
      axios.get("/api/traerretffactcomp").then(
        function(response) {
          this.retfuente = response.data;
          //this.cantretfuente=response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getRetIva() {
      axios.get("/api/traerretivafactcomp").then(
        function(response) {
          this.retiva = response.data;
          // this.cantretiva=response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getCaja() {
      axios.get("/api/traercajafactcomp").then(
        function(response) {
          this.caja = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getBanco() {
      axios.get("/api/traerbancofactcomp").then(
        function(response) {
          this.bancos = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getProvincias(){
      axios.get("/api/traerprovinciafactcomp").then(
        function(response) {
          this.provincias = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    agregarpago() {
      /*if(this.valorpagos[0].metodo_pago >= 1 && this.valorpagos[0].cantidad_pago == null){
        this.valorpagos[0].cantidad_pago = this.totalpr;
      }*/

      if (this.valorpagos.length > 2) {
        if (
          (this.valorpagos[this.valorpagos.length - 1].metodo_pago ||
            this.valorpagos[this.valorpagos.length - 1].cantidad_pago) &&
          (!this.valorpagos[this.valorpagos.length].metodo_pago &&
            !this.valorpagos[this.valorpagos.length].cantidad_pago)
        ) {
          this.valorpagos.push({
            metodo_pago: "",
            cantidad_pago: 0,
            comentario_pago: "",
            banco_pago: "",
            tarjeta: "",
            cuenta: "",
            nro_trans: "",
            id_caja: ""
          });
        }
      } else {
        if (
          this.valorpagos[this.valorpagos.length - 1].metodo_pago ||
          this.valorpagos[this.valorpagos.length - 1].cantidad_pago
        ) {
          this.valorpagos.push({
            metodo_pago: "",
            cantidad_pago: 0,
            comentario_pago: "",
            banco_pago: "",
            tarjeta: "",
            cuenta: "",
            nro_trans: "",
            id_caja: ""
          });
        }
      }
    },
    baseretencion(id) {
      var valor = 0;
      for (var i = 0; i < this.contenidoretencion.length; i++) {
        valor = parseFloat(this.contenidoretencion[i].porcentaje_fuente);
        //this.contenidoretencion[i].porcentaje_fuente=this.subtotalpr;
        if (this.contenidoretencion[i].id_retencion_fuente == 1) {
          this.contenidoretencion[i].porcentaje_fuente = 0.01;
        }
        if (this.contenidoretencion[i].id_retencion_fuente == 2) {
          this.contenidoretencion[i].porcentaje_fuente = 0.02;
        }
        if (
          this.contenidoretencion[i].id_retencion_fuente == 3 ||
          this.contenidoretencion[i].id_retencion_fuente == 17
        ) {
          this.contenidoretencion[i].porcentaje_fuente = 0.08;
        }
        if (this.contenidoretencion[i].id_retencion_fuente == 15) {
          this.contenidoretencion[i].porcentaje_fuente = 0.1;
        }
        if (
          this.contenidoretencion[i].id_retencion_fuente == 16 ||
          this.contenidoretencion[i].id_retencion_fuente == 18
        ) {
          this.contenidoretencion[i].porcentaje_fuente = 0.0;
        }
      }
      return valor;
    },
    baseretencioniva(id) {
      var valor = 0;
      for (var i = 0; i < this.contenidoretencion.length; i++) {
        valor = parseFloat(this.contenidoretencion[i].porcentaje_iva);
        //this.contenidoretencion[i].porcentaje_fuente=this.subtotalpr;
        if (this.contenidoretencion[i].id_retencion_iva == 4) {
          this.contenidoretencion[i].porcentaje_iva = 0.3;
        }
        if (this.contenidoretencion[i].id_retencion_iva == 5) {
          this.contenidoretencion[i].porcentaje_iva = 0.7;
        }
        if (this.contenidoretencion[i].id_retencion_iva == 6) {
          this.contenidoretencion[i].porcentaje_iva = 1;
        }
        if (this.contenidoretencion[i].id_retencion_iva == 7) {
          this.contenidoretencion[i].porcentaje_iva = 0.0;
        }
      }
      return valor;
    },
    guardarFact() {
      if((parseFloat(this.totalpagado) + parseFloat(this.monto_credito))<=0){
        this.$vs.notify({
          time: 8000,
          title: "No se puede Guardar la factura",
          text: "Debe agregar pagos a la factura",
          color: "danger"
        });
        return; 
      }
      if(this.totalpr>parseFloat(this.totalpagado) + parseFloat(this.monto_credito)){
        this.$vs.notify({
          time:8000,
          title: "No se puede Guardar la factura",
          text: "El saldo total no puede ser mayor al saldo pendiente",
          color: "danger",
        });
        return;
      }
      if(this.totalpr<parseFloat(this.totalpagado) + parseFloat(this.monto_credito)){
        this.$vs.notify({
          time:8000,
          title: "No se puede Guardar la factura",
          text: "El saldo total no puede ser menor al saldo pendiente",
          color: "danger",
        });
        return;
      }
      if(this.validarfactura()){
        return;
      }
      axios.post("/api/guardarfactura", {
        //datos generales
        id_empresa:this.usuario.id_empresa,
        id:this.usuario.id,
        //datos de la factura
        nro_factprov: this.nroFacturaProveedor,
        fech_emision: this.femision,
        fech_validez:this.fvalidez,
        nro_autorizacion:this.nroAutorizacion,
        id_sustento:this.tipoSustento,
        destino_pago:this.destPago,
        gasto_import:this.gastos,
        id_importacion:this.nro_importacion,
        orden_compra:this.ordcompra, 
        id_proveedor:this.id_proveedor,
        descripcion:this.descripcion,
        id_cliente_asoc:this.cliente,
        //datos factura valores
        subtotal_sin_impuesto:this.subtotalpr,
        subtotal_12:this.subtotalivapr12,
        subtotal_0:this.subtotalivapr0,
        subtotal_no_obj_iva:this.subtotalivaprno,
        descuento:this.totaldesc,
        valor_ice:0,
        valor_irbpnr:0,
        iva_12:this.ivapr12,
        total_factura:this.totalpr,
        //guardar productos de factura
        productos:this.contenidoprod,
        //creditos
        periodo:this.periodo_credito,
        plazos:this.plazos_credito,
        monto:this.monto_credito,
        credito:this.pago_credito,
        //retenciones
        retenciones:this.valorretenciones,
        //pagos
        pagos:this.valorpagos,
        //revisar
        verretencion:this.verretencion,
        verpagos:this.verpagos,
        vercreditos:this.vercreditos,
      }).then(res => {
        var urlxmlf ="/api/factura/xml_compro_retenc";
        var dataf = res.data[0];
        this.recueidfact = res.data[0].id_factcompra;
        axios.post(urlxmlf,dataf).then(res => {
          var firma = res.data.recupera.pass_firma;
          var claveacc = res.data.recupera.firma;
          var ruta_factura = "../server/" + this.usuario.id_empresa + "/comprobantes/retencioncompra/" + this.nroAutorizacion + ".xml";
          var ruta_certificado = "/empresas/" + this.usuario.id_empresa + "/firma/" + claveacc;
          var ruta_respuesta = "/agregar/factura";
          this.obtenerComprobanteFirmado_sri(ruta_certificado, firma,ruta_factura, this.tipofactura);
        });
      });
    },
    validarfactura(){
      this.error=0;
      this.errorproveedor=[];
      this.errornrofactura=[];
      this.errorfech_emision=[];
      this.errorfech_validez=[];
      this.errornroautorizacion=[];
      this.errorcant_ingreso=[];
      this.errorcost_unit_ingreso=[];
      if(!this.nroFacturaProveedor){
        this.errornrofactura.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.femision){
        this.errorfech_emision.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.fvalidez){
        this.errorfech_validez.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.nroAutorizacion){
        this.errornroautorizacion.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.id_proveedor){
        this.errorproveedor.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      for (var i = 0; i < this.contenidoprod.length; i++) {
        this.contenidoprod[i].errorcant_ingreso = [];
        this.contenidoprod[i].errorcost_unit_ingreso = [];
        if (!this.contenidoprod[i].cantidad) {
          this.contenidoprod[i].errorcant_ingreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.contenidoprod[i].precio) {
          this.contenidoprod[i].errorcost_unit_ingreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
      }
      return this.error;
    },
    //Facturación
    obtenerComprobanteFirmado_sri(ruta_certificado,pwd_p12,ruta_factura,tipofactura) {
        var response = [];
        axios.post("/api/leerFacturaphp", { ruta_factura: ruta_factura }).then(respuesta => {
            this.contenido_comprobante = respuesta.data;
            var oReq = new XMLHttpRequest();
            oReq.open("GET", ruta_certificado, true);
            oReq.responseType = "arraybuffer";
            oReq.onload = oEvent => {
                var blob = new Blob([oReq.response], {type: "application/x-pkcs12"});
                this.contenido_p12 = [oReq.response];
                var comprobanteFirmado_xml = this.firmarComprobante(this.contenido_p12[0],pwd_p12,this.contenido_comprobante);
                axios.post("/api/firmaphp", {mensaje: comprobanteFirmado_xml,id_empresa: this.usuario.id_empresa, tipo:tipofactura}).then(res => {
                    var service = 'Validar Comprobante';
                    var xmlDoc = $.parseXML(this.contenido_comprobante),$xml = $(xmlDoc),$claveAcceso = $xml.find("claveAcceso");
                    axios.post("/api/validarComprobantephp", {service: service, claveAcceso: $claveAcceso.text(), id_empresa: this.usuario.id_empresa, tipo:tipofactura}).then(respuestaValidarComprobante => {
                        console.log(respuestaValidarComprobante.data);
                        respuesta = decodeURIComponent(respuestaValidarComprobante.data);
                        respuesta = respuesta.toString();
                        var validar_comprobante = respuestaValidarComprobante.data;   
                        if (/RECIBIDA/i.test(respuesta) || /CLAVE ACCESO REGISTRADA/i.test(respuesta)) {
                            var service = 'Autorizacion Comprobante';
                            var xmlDoc = $.parseXML(this.contenido_comprobante),$xml = $(xmlDoc),$claveAcceso = $xml.find("claveAcceso");
                            axios.post("/api/autorizacionComprobantephp",{service: service,claveAcceso: $claveAcceso.text(),id_empresa:this.usuario.id_empresa, tipo:tipofactura}).then(respuestaAutorizacionComprobante => {
                                console.log(respuestaAutorizacionComprobante.data);
                                var autorizacion_comprobante = respuestaAutorizacionComprobante.data;
                                response[0] = validar_comprobante;
                                response[1] = autorizacion_comprobante;
                                var envioestado ="/api/respfactura";
                                var enviourl = {estado: "Enviado",id: this.recueidfact,tipo:tipofactura};
                                $.ajax({
                                    type: 'POST',
                                    url: envioestado,
                                    data: enviourl,
                                    context: document.body
                                }).done(respuesta => {
                                    this.$vs.notify({
                                        tithis: 8000,
                                        title: "Factura Enviada",
                                        text:"La factura se generó exitosamente",
                                        color: "success"
                                    });
                                    this.$router.push("/facturacion/factura-venta");
                                }).catch( err => {
                                    this.errorf(err,tipofactura);
                                });
                            });
                        } else {
                            response[0] = validar_comprobante;
                            this.errorf(response);
                        }
                    }).catch( err => {
                        this.errorf(err,tipofactura);
                    });
                }).catch( err => {
                    this.errorf(err,tipofactura);
                });
            };
            oReq.send();
        }).catch( err => {
            this.errorf(err,tipofactura);
        });
    },
    errorf(err,tipofactura){
        var envioestado = "/api/respfactura";
        var enviourl = {estado: "Error",id: this.recueidfact,tipo:tipofactura};
        axios.post(envioestado, enviourl).then( () => {
            this.$vs.notify({
                tithis: 8000,
                title: "Factura Erronea",
                text:"La factura no pudo ser validada, verifique la factura e Intente mas tarde",
                color: "danger"
            });
            this.$router.push("/facturacion/factura-venta");
        }).catch( () => {
            this.$vs.notify({
                tithis: 8000,
                title: "Factura Erronea",
                text:"La factura no pudo ser validada, verifique la factura e Intente mas tarde",
                color: "danger"
            });
            this.$router.push("/facturacion/factura-venta");
        });
    },
    firmarComprobante(mi_contenido_p12, mi_pwd_p12, comprobante) {
        var arrayUint8 = new Uint8Array(mi_contenido_p12);
        var p12B64 = forge.util.binary.base64.encode(arrayUint8);
        var p12Der = forge.util.decode64(p12B64);
        var p12Asn1 = forge.asn1.fromDer(p12Der);
        var p12 = forge.pkcs12.pkcs12FromAsn1(p12Asn1, mi_pwd_p12);
        var certBags = p12.getBags({bagType: forge.pki.oids.certBag})
        var signaturesQuantity = certBags[forge.oids.certBag];
        var count = 0;
        var positionSignature = 0;
        var entidad = signaturesQuantity[0].attributes.friendlyName[0];
        if (/BANCO CENTRAL/i.test(entidad)) {
            entidad = 'BANCO_CENTRAL';
            var certBags = p12.getBags({bagType: forge.pki.oids.certBag})
            var cert = certBags[forge.oids.certBag][1].cert;
            var issuerName = 'CN=AC BANCO CENTRAL DEL ECUADOR,L=QUITO,OU=ENTIDAD DE CERTIFICACION DE INFORMACION-ECIBCE,O=BANCO CENTRAL DEL ECUADOR,C=EC';
        }else if(/SECURITY DATA/i.test(entidad)) {
            entidad = 'SECURITY_DATA';
            var contador = 0;
            var max = 0;
            var attributes_array=[];        
            certBags[forge.oids.certBag].forEach(function (entry) {
                var bag = entry.cert;
                var attributes = bag.extensions;
                attributes_array[contador] = attributes;
                attributes_array.sort().reverse();
                max = attributes_array[0].length;   
                contador++;   
            });
            certBags[forge.oids.certBag].forEach(function (entry) {
                var bag = entry.cert;
                var attributes = bag.extensions;
                if (attributes.length >= max) { cert = bag; }   
            });
            var issuerName = 'CN=AUTORIDAD DE CERTIFICACION SUB SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A.,C=EC';
        }else {
            me.$vs.notify({
                time: 8000,
                title:"Error de factura",
                text:"Tipo de certificado no registrado",
                color:"success"
            });
        }
        var fechaInicio = cert.validity['notBefore'];
        var fechaFin = cert.validity['notAfter'];
        var pkcs8bags = p12.getBags({bagType: forge.pki.oids.pkcs8ShroudedKeyBag});
        if (entidad == 'BANCO_CENTRAL') {
            var pkcs8 = pkcs8bags[forge.oids.pkcs8ShroudedKeyBag][1];
        } else {
            var pkcs8 = pkcs8bags[forge.oids.pkcs8ShroudedKeyBag][0];
        }
        var key = pkcs8.key;
        if (key == null) {
            key = pkcs8.asn1;
        }
        var certificateX509_pem = forge.pki.certificateToPem(cert);
        var certificateX509 = certificateX509_pem;
        certificateX509 = certificateX509.substr(certificateX509.indexOf('\n'));
        certificateX509 = certificateX509.substr(0, certificateX509.indexOf('\n-----END CERTIFICATE-----'));
        certificateX509 = certificateX509.replace(/\r?\n|\r/g, '').replace(/([^\0]{76})/g, '$1\n');
        var certificateX509_asn1 = forge.pki.certificateToAsn1(cert);
        var certificateX509_der = forge.asn1.toDer(certificateX509_asn1).getBytes();
        var certificateX509_der_hash = this.sha1_base64(certificateX509_der);
        var X509SerialNumber = parseInt(cert.serialNumber, 16);
        var exponent = this.hexToBase64(key.e.data[0].toString(16));
        var modulus = this.bigint2base64(key.n);
        var comprobante = comprobante.replace(/\t|\r/g, "")
        var sha1_comprobante = this.sha1_base64(comprobante.replace('<?xml version="1.0" encoding="UTF-8"?>', ''));
        var xmlns = 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:etsi="http://uri.etsi.org/01903/v1.3.2#"';
        var Certificate_number = this.p_obtener_aleatorio();
        var Signature_number = this.p_obtener_aleatorio();
        var SignedProperties_number = this.p_obtener_aleatorio();
        var SignedInfo_number = this.p_obtener_aleatorio();
        var SignedPropertiesID_number = this.p_obtener_aleatorio();
        var Reference_ID_number = this.p_obtener_aleatorio();
        var SignatureValue_number = this.p_obtener_aleatorio();
        var Object_number = this.p_obtener_aleatorio();
        var SignedProperties = '';
        SignedProperties += '<etsi:SignedProperties Id="Signature' + Signature_number + '-SignedProperties' + SignedProperties_number + '">';
        SignedProperties += '<etsi:SignedSignatureProperties>';
        SignedProperties += '<etsi:SigningTime>';
        SignedProperties += moment().format('YYYY-MM-DD\THH:mm:ssZ');
        SignedProperties += '</etsi:SigningTime>';
        SignedProperties += '<etsi:SigningCertificate>';
        SignedProperties += '<etsi:Cert>';
        SignedProperties += '<etsi:CertDigest>';
        SignedProperties += '<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
        SignedProperties += '</ds:DigestMethod>';
        SignedProperties += '<ds:DigestValue>';
        SignedProperties += certificateX509_der_hash;
        SignedProperties += '</ds:DigestValue>';
        SignedProperties += '</etsi:CertDigest>';
        SignedProperties += '<etsi:IssuerSerial>';
        SignedProperties += '<ds:X509IssuerName>';
        SignedProperties += issuerName;
        SignedProperties += '</ds:X509IssuerName>';
        SignedProperties += '<ds:X509SerialNumber>';
        SignedProperties += X509SerialNumber;
        SignedProperties += '</ds:X509SerialNumber>';
        SignedProperties += '</etsi:IssuerSerial>';
        SignedProperties += '</etsi:Cert>';
        SignedProperties += '</etsi:SigningCertificate>';
        SignedProperties += '</etsi:SignedSignatureProperties>';
        SignedProperties += '<etsi:SignedDataObjectProperties>';
        SignedProperties += '<etsi:DataObjectFormat ObjectReference="#Reference-ID-' + Reference_ID_number + '">';
        SignedProperties += '<etsi:Description>';
        SignedProperties += 'contenido comprobante';
        SignedProperties += '</etsi:Description>';
        SignedProperties += '<etsi:MimeType>';
        SignedProperties += 'text/xml';
        SignedProperties += '</etsi:MimeType>';
        SignedProperties += '</etsi:DataObjectFormat>';
        SignedProperties += '</etsi:SignedDataObjectProperties>';
        SignedProperties += '</etsi:SignedProperties>';
        var SignedProperties_para_hash = SignedProperties.replace('<etsi:SignedProperties', '<etsi:SignedProperties ' + xmlns);
        var sha1_SignedProperties = this.sha1_base64(SignedProperties_para_hash);
        var KeyInfo = '';
        KeyInfo += '<ds:KeyInfo Id="Certificate' + Certificate_number + '">';
        KeyInfo += '\n<ds:X509Data>';
        KeyInfo += '\n<ds:X509Certificate>\n';
        KeyInfo += certificateX509;
        KeyInfo += '\n</ds:X509Certificate>';
        KeyInfo += '\n</ds:X509Data>';
        KeyInfo += '\n<ds:KeyValue>';
        KeyInfo += '\n<ds:RSAKeyValue>';
        KeyInfo += '\n<ds:Modulus>\n';
        KeyInfo += modulus;
        KeyInfo += '\n</ds:Modulus>';
        KeyInfo += '\n<ds:Exponent>';
        KeyInfo += exponent;
        KeyInfo += '</ds:Exponent>';
        KeyInfo += '\n</ds:RSAKeyValue>';
        KeyInfo += '\n</ds:KeyValue>';
        KeyInfo += '\n</ds:KeyInfo>';
        var KeyInfo_para_hash = KeyInfo.replace('<ds:KeyInfo', '<ds:KeyInfo ' + xmlns);
        var sha1_certificado = this.sha1_base64(KeyInfo_para_hash);
        var SignedInfo = '';
        SignedInfo += '<ds:SignedInfo Id="Signature-SignedInfo' + SignedInfo_number + '">';
        SignedInfo += '\n<ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315">';
        SignedInfo += '</ds:CanonicalizationMethod>';
        SignedInfo += '\n<ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1">';
        SignedInfo += '</ds:SignatureMethod>';
        SignedInfo += '\n<ds:Reference Id="SignedPropertiesID' + SignedPropertiesID_number + '" Type="http://uri.etsi.org/01903#SignedProperties" URI="#Signature' + Signature_number + '-SignedProperties' + SignedProperties_number + '">';
        SignedInfo += '\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
        SignedInfo += '</ds:DigestMethod>';
        SignedInfo += '\n<ds:DigestValue>';
        SignedInfo += sha1_SignedProperties;
        SignedInfo += '</ds:DigestValue>';
        SignedInfo += '\n</ds:Reference>';
        SignedInfo += '\n<ds:Reference URI="#Certificate' + Certificate_number + '">';
        SignedInfo += '\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
        SignedInfo += '</ds:DigestMethod>';
        SignedInfo += '\n<ds:DigestValue>';
        SignedInfo += sha1_certificado;
        SignedInfo += '</ds:DigestValue>';
        SignedInfo += '\n</ds:Reference>';
        SignedInfo += '\n<ds:Reference Id="Reference-ID-' + Reference_ID_number + '" URI="#comprobante">';
        SignedInfo += '\n<ds:Transforms>';
        SignedInfo += '\n<ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature">';
        SignedInfo += '</ds:Transform>';
        SignedInfo += '\n</ds:Transforms>';
        SignedInfo += '\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
        SignedInfo += '</ds:DigestMethod>';
        SignedInfo += '\n<ds:DigestValue>';
        SignedInfo += sha1_comprobante;
        SignedInfo += '</ds:DigestValue>';
        SignedInfo += '\n</ds:Reference>';
        SignedInfo += '\n</ds:SignedInfo>';
        var SignedInfo_para_firma = SignedInfo.replace('<ds:SignedInfo', '<ds:SignedInfo ' + xmlns);
        var md = forge.md.sha1.create();
        md.update(SignedInfo_para_firma, 'utf8');
        var signature = btoa(key.sign(md)).match(/.{1,76}/g).join("\n");
        var xades_bes = '';
        xades_bes += '<ds:Signature ' + xmlns + ' Id="Signature' + Signature_number + '">';
        xades_bes += '\n' + SignedInfo;
        xades_bes += '\n<ds:SignatureValue Id="SignatureValue' + SignatureValue_number + '">\n';
        xades_bes += signature;
        xades_bes += '\n</ds:SignatureValue>';
        xades_bes += '\n' + KeyInfo;
        xades_bes += '\n<ds:Object Id="Signature' + Signature_number + '-Object' + Object_number + '">';
        xades_bes += '<etsi:QualifyingProperties Target="#Signature' + Signature_number + '">';
        xades_bes += SignedProperties;
        xades_bes += '</etsi:QualifyingProperties>';
        xades_bes += '</ds:Object>';
        xades_bes += '</ds:Signature>';
        return  comprobante.replace(/(<[^<]+)$/, xades_bes + '$1');
    },
    sha1_base64(txt) {
        var md = forge.md.sha1.create();
        md.update(txt);
        return new window.buffer.Buffer(md.digest().toHex(), 'hex').toString('base64');
    },
    hexToBase64(str) {
        var hex = ('00' + str).slice(0 - str.length - str.length % 2);
        return btoa(String.fromCharCode.apply(null,hex.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" ")));
    },
    bigint2base64(bigint) {
        var base64 = '';
        base64 = btoa(bigint.toString(16).match(/\w{2}/g).map(function (a) { return String.fromCharCode(parseInt(a, 16)); }).join(""));
        base64 = base64.match(/.{1,76}/g).join("\n");
        return base64;
    },
    p_obtener_aleatorio() {
        return Math.floor(Math.random() * 999000) + 990;
    },
    //funcienes crear proveedores
    listarplanctas(pageplancta, buscarplactas) {
      let me = this;
      var url =
        "/api/cuentas/" +
        this.usuario.id_empresa +
        "?page=" +
        pageplancta +
        "&buscar=" +
        buscarplactas;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenidoplanctas = respuesta.recupera;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    handleSelected3(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      (this.ctacontable = `${tr.codcta}`),
        (this.idContable = `${tr.id_ctas}`),
        (this.activePrompt3 = false);
    },
    getImpIva() {
      var por=0;
      if(this.retiva.length>=1){
        //console.log("iddd"+this.retiva[this.retencionIva].id_retencion)
        //console.log("ppp"+this.retiva[this.retencionIva].porcen_retencion)
        if(this.retencionIva != null){
          por=this.retiva[this.retencionIva].porcen_retencion;
        this.retencion_iva=this.retiva[this.retencionIva].descrip_retencion;
        }else{
          por=95;
        }
        
      }
      axios
        .get("/api/traerimpiva", {
          params: {
            porcen_imp: por
          }
        })
        .then(
          function(response) {
            if (response.data) {
              this.impiva = response.data;
            } else {
              this.impiva = 0;
              console.log("hola");
            }
          }.bind(this)
        );
    },
    getImpFuente() {
      var r=0;
      var id_ret;
      if(this.retfuente.length>=1){
        //console.log(this.retfuente[this.impstRetencion].id_retencion)
        //console.log(this.retfuente[this.impstRetencion].porcen_retencion)
        if(this.impstRetencion != null){
          r=this.retfuente[this.impstRetencion].porcen_retencion
        this.retencion_nombre=this.retfuente[this.impstRetencion].descrip_retencion
        }else{
          r=95;
        }
        
        
      }
      axios
        .get("/api/traerimpfuente", {
          params: {
            
            porcen_impret: r
          }
        })
        .then(
          function(response) {
            if (response.data) {
              this.impfuente = response.data;
            } else {
              this.impfuente = 0;
              console.log("hola");
            }
          }.bind(this)
        );
    },
    getProvinciasProv: function() {
      axios.get("/api/traerprovinciaprov").then(
        function(response) {
          this.provincias = response.data;
          this.provs == this.id_provincia;
        }.bind(this)
      );
    },
    getCiudadesProv: function() {
      axios
        .get("/api/traerciudadprov", {
          params: {
            provincia: this.provinciaprov
          }
        })
        .then(
          function(response) {
            //console.log("provincia",this.id_provs,"s");
            this.ciudades = response.data;
          }.bind(this)
        );
    },
    getBancosProv: function() {
      axios.get("/api/traerbancoprov").then(
        function(response) {
          this.bancos = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getGrupoProv() {
      axios.get("/api/traergruprov").then(
        function(response) {
          this.grupos = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getTipoComprob() {
      axios.get("/api/traertipcomprob").then(
        function(response) {
          this.tipcomprob = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getRetFuenteProv() {
      axios.get("/api/traerretfuente").then(
        function(response) {
          this.retfuente = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getRetIvaProv() {
      axios.get("/api/traerretiva").then(
        function(response) {
          this.retiva = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    leercodigoprov() {
      if (!this.$route.params.id) {
        axios
          .get("/api/codigo?id=" + this.usuario.id_empresa)
          .then(res => {
            this.codigoprov = res.data;
            if(this.codigoprov=="vacio"){
              this.tipocod=1;
            }else{
              this.tipocod=0;
              this.codigo_proveedor = this.codigoprov;
            }
          });
      }
    },
    guardarproveedor(){
      if(this.validarproveedor()){
        
        return;
      }
       axios
            .post("/api/agregarproveedor", {
              cod_proveedor: this.codigo_proveedor,
              grupo: this.grupo,
              nombre_proveedor: this.nombre,
              tipo_identificacion: this.tipoIdent,
              identif_proveedor: this.identificacion,
              //tipo_proveedor:this.tipo,
              contribuyente: this.contribuyente,
              beneficiario: this.beneficiario,
              //identif_benefic:this.identificacionBenf,
              contacto: this.contacto,
              direccion_prov: this.direccion,
              nrcasa: this.nrcasa,
              telefono_prov: this.telefono,
              //estado_prov: this.estado,
              tipo_cuenta: this.tipCuenta,
              cta_banco: this.ctaBanco,
              id: this.idbanco,
              //nrcta_interbancaria:this.nrctaInterbancaria,
              pagos: this.pago,
              plazo: this.plazo,
              dias_pago: this.dpagos,
              tip_comprob: this.tcomprobante,
              serie: this.serie,
              fvalidez: this.fvalidez,
              comentario: this.comentario,
              rangomax: this.ranmax,
              rangomin: this.rangmin,
              nrautorizacion: this.nroAutorizacion,
              contribuye_sri: this.contribuyeSri,
              tip_electronico: this.tipElectronico,
              imp_retencion: this.impstRetencion,
              codsri_imp: this.codSriImp,
              retencion_iva: this.retencionIva,
              codsri_iva: this.codSriIva,
              cta_contable: this.ctacontable,
              //id_contable:this.ctacontable,
              id_provincia: this.provinciaprov,
              id_ciudad: this.ciudad,
              id_banco: this.banco,
              id_empresa: this.usuario.id_empresa,
            })
            .then(res => {
              this.crearproveedor(res.data);
              if (res.data != "existe") {
                (this.popupActive4 = false),
                this.borrarproveedor();
              } else {
                alert("No se Encuentra Cuenta Contable");
                
                console.log(res);
              }
              
            }).catch(err => {});
    },
    borrarproveedor(){
      (this.grupo = ""),
        (this.nombre = ""),
        (this.tipoIdent = ""),
        (this.identificacion = ""),
        (this.tipo = ""),
        (this.contribuyente = null),
        (this.beneficiario = ""),
        //this.identificacionBenf="",
        (this.contacto = ""),
        (this.direccion = ""),
        (this.nrcasa = ""),
        (this.provinciaprov = ""),
        (this.ciudad = ""),
        (this.telefono = ""),
        (this.estado = ""),
        (this.banco = ""),
        (this.tipCuenta = ""),
        (this.ctaBanco = ""),
        (this.idbanco = ""),
        //this.nrctaInterbancaria="",
        (this.pago = ""),
        (this.plazo = ""),
        (this.dpagos = ""),
        (this.ctacontable = ""),
        (this.idContable = ""),
        (this.comentario = ""),
        (this.tcomprobante = ""),
        (this.serie = ""),
        (this.fvalidez = ""),
        (this.rangmin = ""),
        (this.ranmax = ""),
        (this.nroAutorizacion = ""),
        (this.contribuyeSri = null),
        (this.tipElectronico = "0"),
        (this.impstRetencion = null),
        (this.retencionIva = null),
        
        //(this.retencionIva = ""),
        //(this.retencion_iva = ""),
        (this.codSriImp = ""),
        (this.codSriIva = "");

    },
    validarcedula($event) {
      this.error = 0;
      this.erroridentificacion = [];
      if (this.identificacion.length < 10) {
        this.erroridentificacion.push("Cedula invalida");
        this.error = 1;
        console.log("Cedula invalida");
        return;
      }
      if (
        typeof this.identificacion == "string" &&
        this.identificacion.length == 10 &&
        /^\d+$/.test(this.identificacion)
      ) {
        var digitos = this.identificacion.split("").map(Number);
        var codigo_provincia = digitos[0] * 10 + digitos[1];

        //if (codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30) && digitos[2] < 6) {

        if (
          codigo_provincia >= 1 &&
          (codigo_provincia <= 24 || codigo_provincia == 30)
        ) {
          var digito_verificador = digitos.pop();

          var digito_calculado =
            digitos.reduce(function(valorPrevio, valorActual, indice) {
              return (
                valorPrevio -
                ((valorActual * (2 - (indice % 2))) % 9) -
                (valorActual == 9) * 9
              );
            }, 1000) % 10;
          console.log(digito_calculado);
          //return digito_calculado === digito_verificador;
          if (digito_calculado === digito_verificador) {
            this.erroridentificacion = [];
          } else {
            this.erroridentificacion.push("Cédula inválida");
            this.error = 1;

            return;
          }
        } else {
          this.erroridentificacion.push("Cédula inválida");
          this.error = 1;
          return;
        }
      }
      return false;
     
    },
    validarruc($event) {
      this.error = 0;
      this.erroridentificacion = [];
      var numero = this.identificacion;
      var suma = 0;
      var residuo = 0;
      var pri = false;
      var pub = false;
      var nat = false;
      var numeroProvincias = 22;
      var modulo = 11;

      /* Verifico que el campo no contenga letras */
      var ok = 1;
      /*for (var i=0; i<numeroProvincias ;i++){
      alert('El código de la provincia (dos primeros dígitos) es inválido'); return false;
      }*/
      /* Aqui almacenamos los digitos de la cedula en variables. */
      var d1 = numero.substr(0, 1);
      var d2 = numero.substr(1, 1);
      var d3 = numero.substr(2, 1);
      var d4 = numero.substr(3, 1);
      var d5 = numero.substr(4, 1);
      var d6 = numero.substr(5, 1);
      var d7 = numero.substr(6, 1);
      var d8 = numero.substr(7, 1);
      var d9 = numero.substr(8, 1);
      var d10 = numero.substr(9, 1);

      /* El tercer digito es: */
      /* 9 para sociedades privadas y extranjeros */
      /* 6 para sociedades publicas */
      /* menor que 6 (0,1,2,3,4,5) para personas naturales */

      if (d3 == 7 || d3 == 8) {
        //console.log('El tercer dígito ingresado es inválido');
        this.erroridentificacion.push("El tercer dígito ingresado es inválido");
        this.error = 1;
        return false;
      }

      /* Solo para personas naturales (modulo 10) */
      if (d3 < 6) {
        nat = true;
        p1 = d1 * 2;
        if (p1 >= 10) p1 -= 9;
        p2 = d2 * 1;
        if (p2 >= 10) p2 -= 9;
        p3 = d3 * 2;
        if (p3 >= 10) p3 -= 9;
        p4 = d4 * 1;
        if (p4 >= 10) p4 -= 9;
        p5 = d5 * 2;
        if (p5 >= 10) p5 -= 9;
        p6 = d6 * 1;
        if (p6 >= 10) p6 -= 9;
        p7 = d7 * 2;
        if (p7 >= 10) p7 -= 9;
        p8 = d8 * 1;
        if (p8 >= 10) p8 -= 9;
        p9 = d9 * 2;
        if (p9 >= 10) p9 -= 9;
        modulo = 10;
      } else if (d3 == 6) {

      /* Solo para sociedades publicas (modulo 11) */
      /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
        pub = true;
        p1 = d1 * 3;
        p2 = d2 * 2;
        p3 = d3 * 7;
        p4 = d4 * 6;
        p5 = d5 * 5;
        p6 = d6 * 4;
        p7 = d7 * 3;
        p8 = d8 * 2;
        p9 = 0;
      } else if (d3 == 9) {

      /* Solo para entidades privadas (modulo 11) */
        var pri = true;
        var p1 = d1 * 4;
        var p2 = d2 * 3;
        var p3 = d3 * 2;
        var p4 = d4 * 7;
        var p5 = d5 * 6;
        var p6 = d6 * 5;
        var p7 = d7 * 4;
        var p8 = d8 * 3;
        var p9 = d9 * 2;
      }

      var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
      var residuo = suma % modulo;

      /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
      var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;

      /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
      if (pub == true) {
        if (digitoVerificador != d9) {
          //console.log('El ruc de la empresa del sector público es incorrecto.');
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
        /* El ruc de las empresas del sector publico terminan con 0001*/
        if (numero.substr(9, 4) != "0001") {
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
      } else if (pri == true) {
        if (digitoVerificador != d10) {
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
        if (numero.substr(10, 3) != "001") {
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
      } else if (nat == true) {
        if (digitoVerificador != d10) {
          //console.log('El número de cédula de la persona natural es incorrecto.');
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
        if (numero.length < 14 && numero.substr(10, 12) != "001") {
          //console.log('El ruc de la persona natural debe terminar con 001');
          this.erroridentificacion.push("Ruc invalido");
          this.error = 1;
          return false;
        }
      }
      return true;
    
    },
   validarproveedor(){
      this.errorprov= 0;
      this.erroridentificacion= [];
      this.errorcodigo_proveedor= [];
      this.errorgrupo= [];
      this.errornombre= [];
      this.errortipoIdent= [];
      this.errortipo= [];
      this.errorcontribuyente= [];
      this.errorbeneficiario= [];
      this.errordireccion=[];
      this.errorprovincia=[];
      this.errorciudad=[];
      this.errorcontacto=[];
      if(!this.nombre){
        this.errornombre.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      if(!this.tipoIdent){
        this.errortipoIdent.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      if(!this.direccion){
        this.errordireccion.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      if(!this.contacto){
        this.errorcontacto.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      if(!this.provinciaprov){
        this.errorprovincia.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      if(!this.ciudad){
        this.errorciudad.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      return this.errorprov;
   },
   crearproveedor(id){
      var url = "/api/actualizarprovimportacion/"+id;
      axios
        .get(url)
        .then(res => {
          let data = res.data[0];
          //console.log(data.id_proveedor);
           this.id_proveedor = data.id_proveedor;
           this.proveedor = data.nombre_proveedor;
           this.provincia = data.id_provincia;
        })
        .catch(err => {
          console.log(err);
        });
    },
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    subtotalpr() {
      var total = 0;
      this.contenidoprod.forEach(el => {
        if(el.p_descuento==1){
          total += (el.precio * el.cantidad) -  el.descuento;
        }else{
          total +=el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento) / 100;
        }
      });
      return total;
    },
    subtotalivapr12() {
      var total = 0;
      this.contenidoprod.forEach(el => {
        if (el.iva == 2) {
          if(el.p_descuento==1){
            total += (el.precio * el.cantidad) -  el.descuento;
          }else{
            total +=el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento) / 100;
          }
        }
      });
      return total;
    },
    porcentajeret() {
      var total = 0;
      this.contenidoretencion.forEach(el => {
        if (el.id_retencion_fuente == 1) {
          total += el.base_fuente * el.porcentaje_fuente;
        }
      });
      return total;
    },
    ivapr12() {
      var total = 0;
      /*this.contenidoprod.forEach(el => {
        if (el.iva == 2) {
          if(el.p_descuento==1){
            total += ((el.precio * el.cantidad) - el.descuento) * 0.12;
          }else{
            total += (el.cantidad * el.precio * el.descuento / 100) * 0.12;
          }
        }
      });*/
      total = this.subtotalivapr12 *0.12;
      return total;
    },
    subtotalivapr14() {
      var total = 0;
      this.contenidoprod.forEach(el => {
        if (el.iva == 5) {
          if(el.p_descuento==1){
            total += (el.precio * el.cantidad) -  el.descuento;
          }else{
            total +=el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento) / 100;
          }
        }
      });
      return total;
    },
    ivapr14() {
      var total = 0;
      this.contenidoprod.forEach(el => {
        if (el.iva == 5) {
          total += (el.precio * el.cantidad - el.descuento) * 0.14;
        }
      });
      return total;
    },
    subtotalivapr0() {
      var total = 0;
      this.contenidoprod.forEach(el => {
        if (el.iva == 1) {
          if(el.p_descuento==1){
            total += (el.precio * el.cantidad) -  el.descuento;
          }else{
            total +=el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento) / 100;
          }
        }
      });
      return total;
    },
    subtotalivaprno() {
      var total = 0;
      this.contenidoprod.forEach(el => {
        if (el.iva == 3) {
          if(el.p_descuento==1){
            total += (el.precio * el.cantidad) -  el.descuento;
          }else{
            total +=el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento) / 100;
          }
        }
      });
      return total;
    },
    subtotalivaprex() {
      var total = 0;
      this.contenidoprod.forEach(el => {
        if (el.iva == 4) {
          if(el.p_descuento==1){
            total += (el.precio * el.cantidad) -  el.descuento;
          }else{
            total +=el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento) / 100;
          }
        }
      });
      return total;
    },
    subtotalretiva() {
      var total = 0;
      this.contenidoretencion.forEach(el => {
        total += parseFloat(el.base_iva);
      });
      return total;
    },
    totalretiva() {
      var total = 0;
      this.contenidoretencion.forEach(el => {
        total += parseFloat(el.base_iva) * parseFloat(el.porcentaje_iva);
      });
      return total;
    },
    subtotalretf() {
      var total = 0;
      this.contenidoretencion.forEach(el => {
        total += parseFloat(el.base);
      });
      return total;
    },
    totalretf() {
      var total = 0;
      this.contenidoretencion.forEach(el => {
        total += parseFloat(el.base) * parseFloat(el.porcentaje_fuente);
      });
      return total;
    },
    ivapr0() {
      var total = 0;
      return total;
    },
    ivaprno() {
      var total = 0;
      return total;
    },
    ivaprex() {
      var total = 0;
      return total;
    },
    totaldesc() {
      var total = 0;
      this.contenidoprod.forEach(el => {
        if (el.descuento > 0) {
          if(el.p_descuento==1){
            total += parseFloat(el.descuento);
          }else{
            total += (parseFloat(el.precio)*parseFloat(el.cantidad))*(parseFloat(el.descuento))/100;
          }
        }
      });
      return total;
    },
    totalfac() {
      var total = 0;
      var propina = 0;
      if (this.iva > 0) {
        propina = this.iva / 100;
      }
      this.contenidoprod.forEach(el => {
        if(el.p_descuento==1){
          total += el.precio * el.cantidad - el.descuento;
        }else{
          total += (el.precio * el.cantidad) - ((el.precio * el.cantidad) *el.descuento)/100;
        }
      });
      var dato = total + this.ivapr12 + this.ivapr14; //-(total*parseFloat(propina))
      return dato;
    },
    totalpr() {
      var total = 0;
      var propina = 0;
      if (this.iva > 0) {
        propina = this.iva;
      }
      this.contenidoprod.forEach(el => {
        if(el.p_descuento==1){
          total += (el.precio * el.cantidad) -  el.descuento;
        }else{
          total +=el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento) / 100;
        }
      });
      var dato = total + parseFloat(propina) + this.ivapr12 + this.ivapr14;
      return dato;
    },
    totalpagado() {
      var valor = 0;

      for (var i = 0; i < this.valorpagos.length; i++) {
        if (this.valorpagos[i].cantidad_pago > 0) {
          valor = valor + parseFloat(this.valorpagos[i].cantidad_pago);
        }
      }

      if (this.ret_iva_porc <= 0) {
        var retiva = 0;
      } else {
        var retiva = this.ret_iva_porc;
      }
      if (this.ret_fuente_porc <= 0) {
        var retrenta = 0;
      } else {
        var retrenta = this.ret_fuente_porc;
      }
      var valorr = 0;
      for (var f = 0; f < this.valorretenciones.length; f++) {
        if (
          this.valorretenciones[f].baserenta != null &&
          this.valorretenciones[f].renta != null
        ) {
          valorr +=
            (parseFloat(this.valorretenciones[f].baserenta) *
              parseFloat(this.valorretenciones[f].renta.porcen_retencion)) /
            100;
        }
      }
      var valorri = 0;
      for (var g = 0; g < this.valorretenciones.length; g++) {
        if (this.valorretenciones[g].cantidadiva != null) {
          valorri += parseFloat(this.valorretenciones[g].cantidadiva);
        }
      }

      var total =
        parseFloat(retiva) +
        parseFloat(retrenta) +
        parseFloat(valor) +
        parseFloat(valorr) +
        parseFloat(valorri);
      return total;
    },
    eliminararrayretencionf(id) {
      this.contenidoretencion.splice(id, 1);
    },
    eliminararraypagos(id) {
      this.valorpagos.splice(id, 1);
    },
    
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
.sindis .vs-input--input:focus {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.sindis .vs-input--input {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.nover > .icon-select {
  display: none;
}
.hovertrash:hover > .trasher {
  display: block !important;
}
.agregado{
  background:#635ace!important;
  color:#fff!important;
}
.botonstl{
  height: 100%;
    width: 38px;
    border: 1px solid #635ace;
    background: transparent;
    color: #635ace;
    font-size: 16px;
    cursor: pointer;
}
.elejido{
  background: #635ace!important;
  color:#fff!important;
}
.flexy>.vs-divider--text{
 display:flex;
}
.slide-fade-enter-active {
  transition: all .5s ease;
}
.slide-fade-leave-active {
  transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for <2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
</style>
