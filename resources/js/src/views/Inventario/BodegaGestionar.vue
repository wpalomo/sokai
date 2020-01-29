<template>
  <vx-card title="Gestión de Bodegas" class="mt-10">
    <vs-divider>Información de Bodega</vs-divider>
    <div class="vx-row">
      <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
        <h5>Código:</h5>
        <p>{{codigo}}</p>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
        <h5>Nombre de Bodega:</h5>
        <p>{{nombre}}</p>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
        <h5>Serie de Factura:</h5>
        {{serfactura}}
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
        <h5>Ubicación:</h5>
        {{ubicacion}}
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
        <h5>Dirección:</h5>
        {{direccion}}
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
        <h5>Teléfono:</h5>
        {{telefono}}
      </div>
      <vs-divider />
    </div>
    <div class="vx-row sm:w-full w-full mt-5 tabproductos">
      <vs-tabs alignment="fixed">
        <!-- Visualiziacion de productos-->
        <vs-tab class="vx-col "  label="Inventario" icon-pack="feather" icon="icon-layers">
          <div class="vx-row">
            <vs-divider />
            <vs-divider position="left">Productos en Stock</vs-divider>
            <vs-table
              class="vx-col sm:w-full w-full"
              :data="stockarray"
              hoverFlat
              style="font-size: 12px;"
            >
              <!-- HEADER -->
              <template slot="thead">
                <vs-th>CÓDIGO</vs-th>
                <vs-th>NOMBRE</vs-th>
                <vs-th>DESCRIPCIÓN</vs-th>
                <vs-th class="text-center">CANTIDAD</vs-th>
              </template>
              <!-- DATA -->
              <template>
                <vs-tr v-for="(pr,index) in stockarray" :key="index">
                  <vs-td v-if="pr.cod_principal">{{ pr.cod_principal }}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="pr.nombrep">{{ pr.nombrep }}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="pr.descripcion">{{ pr.descripcion }}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td class="text-center" v-if="pr.cantidad">{{ pr.cantidad }}</vs-td>
                  <vs-td class="text-center" v-else>0</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </vs-tab>
        <!-- Ingreso de bodega-->
        <vs-tab class="vx-col" label="Ingresos" icon-pack="feather" icon="icon-arrow-down">
          <div class="vx-row">
            <vs-divider>Registro de Ingreso a Bodega</vs-divider>
            <vs-divider position="left">Información de Ingreso</vs-divider>
            <div class="vx-col md:w-1/3 sm:w-full w-full ml-auto mb-6">
              <vs-input
                class="w-full"
                label="Numero de Ingreso:"
                v-model="num_ingreso"
                @keypress="solonumeros($event)"
              />
              <div v-show="error" v-if="!num_ingreso">
                <div v-for="err in errornum_ingreso" :key="err" v-text="err" class="text-danger"></div>
              </div>
            </div>
            <div class="vx-col md:w-1/3 sm:w-full w-full mr-auto mb-6">
              <label class="vs-input--label">Fecha de Ingreso:</label>
              <flat-pickr
                class="w-full"
                :config="configdateTimePicker"
                v-model="fecha_ingreso"
                placeholder="Elegir Fecha"
              />
            </div>
            <div class="vx-col md:w-1/3 sm:w-full w-full mb-6">
              <vs-select
                placeholder="Seleccione uno"
                class="selectExample w-full"
                label="Tipo de Ingreso:"
                label-placeholder="Seleccione uno"
                vs-multiple
                v-model="tipo_ingreso"
              >
                <vs-select-item value="Inventario Inicial" text="Inventario Inicial" />
                <vs-select-item value="Fabricación" text="Fabricación" />
                <vs-select-item value="Ingreso por Ajuste" text="Ingreso por Ajuste" />
                <vs-select-item value="Devolucion por Venta" text="Devolucion por Venta" />
              </vs-select>
              <div v-show="error" v-if="!tipo_ingreso">
                <div v-for="err in errortipo_ingreso" :key="err" v-text="err" class="text-danger"></div>
              </div>
            </div>
          </div>
          <div class="vx-col md:w-full sm:w-full w-full mb-6">
            <label class="vs-input--label">Observaciones:</label>
            <vs-textarea v-model="observ_ingreso" rows="2" />
          </div>
          <!-- Inicio Agregar Producto-->
          <vs-divider />
          <vs-divider position="left">Registro de Productos</vs-divider>
          <!-- INVOICE TASKS TABLE -->
          <a
            class="flex items-center cursor-pointer mb-4"
            @click="abrirproductos()"
          >Añadir Productos</a>
          <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;">
            <!-- HEADER -->
            <template slot="thead">
              <vs-th>CÓDIGO</vs-th>
              <vs-th>NOMBRE</vs-th>
              <vs-th class="text-center">CANTIDAD</vs-th>
              <vs-th class="text-center">COSTO UNITARIO</vs-th>
              <vs-th class="text-center">COSTO TOTAL</vs-th>
              <vs-th class="text-center">
                <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
              </vs-th>
            </template>
            <!-- DATA -->
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                <vs-td
                  :data="tr.cod_principal"
                  style="width:150px!important;"
                >{{ tr.cod_principal }}</vs-td>
                <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
                <vs-td :data="tr.cant_ingreso" style="width:200px!important;">
                  <vs-input
                    class="w-full txt-center"
                    v-model="tr.cant_ingreso"
                    @keypress="solonumeros($event)"
                  />
                  <div v-show="error" v-if="!tr.cant_ingreso">
                    <div
                      v-for="err in tr.errorcant_ingreso"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.cost_unit_ingreso" style="width:200px!important;">
                  <vs-input
                    class="w-full txt-center"
                    v-model="tr.cost_unit_ingreso"
                    @keypress="solonumeros($event)"
                  />
                  <div v-show="error" v-if="!tr.cost_unit_ingreso">
                    <div
                      v-for="err in tr.errorcost_unit_ingreso"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.cost_tot_ingreso" style="width:200px!important;">
                  <span
                    style="display:none"
                  >{{ tr.cost_tot_ingreso = tr.cant_ingreso * tr.cost_unit_ingreso }}</span>
                  <vs-input class="w-full txt-center" v-model="tr.cost_tot_ingreso" />
                </vs-td>
                <vs-td :data="tr.cost_tot_ingreso" style="width:25px!important;">
                  <vx-tooltip text="Eliminar" position="top" style="display: inline-flex;">
                    <feather-icon
                      icon="TrashIcon"
                      svgClasses="w-5 h-5 hover:text-danger stroke-current"
                      class="pointer"
                      @click="eliminar(index)"
                    />
                  </vx-tooltip>
                </vs-td>
              </vs-tr>
            </template>
          </vs-table>
          <!-- Botones-->
          <div class="vx-row">
            <div class="vx-col w-1/3 ml-auto mt-6 text-center">
              <vs-button color="success" type="filled" @click="guardarbodegaingreso()">Guardar</vs-button>
            </div>
            <div class="vx-col w-1/3 mr-auto mt-6 text-center">
              <vs-button color="danger" type="filled" @click="ainicio(),cancelar()">Cancelar</vs-button>
            </div>
          </div>
        </vs-tab>
        <!-- Egreso de bodega-->
        <vs-tab class="vx-col" label="Egresos" icon-pack="feather" icon="icon-arrow-up">
          <div class="vx-row">
            <vs-divider>Registro de Salida a Bodega</vs-divider>
            <vs-divider position="left">Información de Salida</vs-divider>
            <div class="vx-col md:w-1/3 sm:w-full w-full ml-auto mb-6">
              <vs-input
                class="w-full"
                label="Numero de Egreso:"
                v-model="num_egreso"
                @keypress="solonumeros($event)"
              />
              <div v-show="error" v-if="!num_egreso">
                <div v-for="err in errornum_egreso" :key="err" v-text="err" class="text-danger"></div>
              </div>
            </div>
            <div class="vx-col md:w-1/3 sm:w-full w-full mr-auto mb-6">
              <label class="vs-input--label">Fecha de Salida:</label>
              <flat-pickr
                class="w-full"
                :config="configdateTimePicker"
                v-model="fecha_egreso"
                placeholder="Elegir Fecha"
              />
            </div>
            <div class="vx-col md:w-1/3 sm:w-full w-full mb-6">
              <vs-select
                placeholder="Seleccione uno"
                class="selectExample w-full"
                label="Tipo de Egreso:"
                label-placeholder="Seleccione uno"
                vs-multiple
                v-model="tipo_egreso"
              >
                <vs-select-item value="Ventas" text="Ventas" />
                <vs-select-item value="Auto Consumo" text="Auto Consumo" />
                <vs-select-item value="Egreso por Ajuste" text="Egreso por Ajuste" />
                <vs-select-item value="Devolucion por Ingreso" text="Devolucion por Ingreso" />
              </vs-select>
              <div v-show="error" v-if="!tipo_egreso">
                <div v-for="err in errortipo_egreso" :key="err" v-text="err" class="text-danger"></div>
              </div>
            </div>
          </div>
          <div class="vx-col md:w-full sm:w-full w-full mb-6">
            <label class="vs-input--label">Observaciones:</label>
            <vs-textarea v-model="observ_egreso" rows="2" />
          </div>
          <!-- Inicio Agregar Producto-->
          <vs-divider />
          <vs-divider position="left">Registro de Productos</vs-divider>
          <!-- INVOICE TASKS TABLE -->
          <a class="flex items-center cursor-pointer mb-4" @click="abrirstock()">Añadir Productos</a>
          <vs-table hoverFlat :data="contenidostock" style="font-size: 12px;">
            <!-- HEADER -->
            <template slot="thead">
              <vs-th>CÓDIGO</vs-th>
              <vs-th>NOMBRE</vs-th>
              <vs-th class="text-center">STOCK DISPONIBLE</vs-th>
              <vs-th class="text-center">PRECIO</vs-th>
              <vs-th class="text-center">CANTIDAD EGRESO</vs-th>
              <vs-th class="text-center">COSTO</vs-th>
              <vs-th class="text-center">
                <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
              </vs-th>
            </template>
            <!-- DATA -->
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                <vs-td
                  :data="tr.cod_principal"
                  style="width:150px!important;"
                >{{ tr.cod_principal }}</vs-td>
                <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
                <vs-td
                  class="w-full text-center"
                  style="width:200px!important;"
                  :data="tr.cantidad"
                >{{ tr.cantidad }}</vs-td>
                <vs-td :data="tr.precio_egreso" style="width:200px!important;">
                  <vs-input
                    class="w-full txt-center"
                    v-model="tr.precio_egreso"
                    @keypress="solonumeros($event)"
                  />
                  <div v-show="error" v-if="!tr.precio_egreso">
                    <div
                      v-for="err in tr.errorprecio_egreso"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.cant_egreso" style="width:200px!important;">
                  <vs-input
                    class="w-full txt-center"
                    v-model="tr.cant_egreso"
                    @keypress="solonumeros($event)"
                  />
                  <div v-show="error" v-if="!tr.cant_egreso">
                    <div
                      v-for="err in tr.errorcant_egreso"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.cost_egreso" style="width:200px!important;">
                  <vs-input
                    class="w-full txt-center"
                    v-model="tr.cost_egreso"
                    @keypress="solonumeros($event)"
                  />
                  <div v-show="error" v-if="!tr.cost_egreso">
                    <div
                      v-for="err in tr.errorcost_egreso"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.cost_tot_ingreso" style="width:25px!important;">
                  <vx-tooltip text="Eliminar" position="top" style="display: inline-flex;">
                    <feather-icon
                      icon="TrashIcon"
                      svgClasses="w-5 h-5 hover:text-danger stroke-current"
                      class="pointer"
                      @click="eliminar(index)"
                    />
                  </vx-tooltip>
                </vs-td>
              </vs-tr>
            </template>
          </vs-table>
          <!-- Botones-->
          <div class="vx-row">
            <div class="vx-col w-1/3 ml-auto mt-6 text-center">
              <vs-button color="success" type="filled" @click="guardarbodegaegreso()">Guardar</vs-button>
            </div>
            <div class="vx-col w-1/3 mr-auto mt-6 text-center">
              <vs-button color="danger" type="filled" @click="ainicio(),cancelar()">Cancelar</vs-button>
            </div>
          </div>
        </vs-tab>
        <!-- Tranferencias-->
        <vs-tab class="vx-col" label="Transferencias" icon-pack="feather" icon="icon-repeat">
          <vs-divider />
          <vs-divider>Tipos de Transferencias</vs-divider>
          <vs-tabs alignment="fixed">
            <!-- Envio de Transferencias-->
            <vs-tab class="vx-col" label="Envío de Transferencias">
              <div class="vx-row">
                <vs-divider position="left">Información de Transferencia</vs-divider>
                <div class="vx-col md:w-1/4 sm:w-full w-full ml-auto mb-6">
                  <vs-input class="w-full" label="Número de Transferencia:" v-model="num_transe" />
                  <div v-show="error" v-if="!num_transe">
                    <div v-for="err in errornum_transe" :key="err" v-text="err" class="text-danger"></div>
                  </div>
                </div>
                <div class="vx-col md:w-1/4 sm:w-full w-full mr-auto mb-6">
                  <label class="vs-input--label">Fecha de Iniciación:</label>
                  <flat-pickr
                    class="w-full"
                    :config="configdateTimePicker"
                    v-model="f_ini_transe"
                    placeholder="Elegir Fecha"
                  />
                </div>
                <div class="vx-col md:w-1/4 sm:w-full w-full mr-auto mb-6">
                  <label class="vs-input--label">Fecha de Finalización :</label>
                  <flat-pickr
                    class="w-full"
                    :config="configdateTimePicker"
                    v-model="f_fin_transe"
                    placeholder="Elegir Fecha"
                  />
                </div>
                <div class="vx-col md:w-1/4 sm:w-full w-full mb-6">
                  <vs-select
                    placeholder="Seleccione uno"
                    class="selectExample w-full"
                    label="Motivo de Transferencia:"
                    label-placeholder="Seleccione uno"
                    vs-multiple
                    v-model="motivo_transe"
                  >
                    <vs-select-item value="Abastecimiento" text="Abastecimiento" />
                    <vs-select-item value="Cambio" text="Cambio" />
                    <vs-select-item value="Devolución" text="Devolución" />
                    <vs-select-item value="Traspaso" text="Traspaso" />
                    <vs-select-item value="Otros" text="Otros" />
                  </vs-select>
                  <div v-show="error" v-if="!motivo_transe">
                    <div
                      v-for="err in errormotivo_transe"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </div>
                <div class="vx-col md:w-1/2 sm:w-full w-full mb-6">
                  <span style="display:none">{{ emisor_transe = nombre }}</span>
                  <vs-input
                    disabled
                    class="w-full"
                    label="Emisor de Transferencia:"
                    v-model="emisor_transe"
                  />
                </div>
                <div class="vx-col md:w-1/2 sm:w-full w-full mb-6">
                  <vs-select
                    placeholder="Buscar bodega"
                    class="selectExample w-full"
                    label="Receptor de Transferencia:"
                    label-placeholder="Buscar bodega"
                    vs-multiple
                    autocomplete
                    v-model="receptor_transe"
                  >
                    <vs-select-item
                      :key="res.id_bodega"
                      :value="res.id_bodega"
                      :text="res.nombre"
                      v-for="res in contenidobodegas"
                    />
                  </vs-select>
                  <div v-show="error" v-if="!receptor_transe">
                    <div
                      v-for="err in errorreceptor_transe"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </div>
                <div class="vx-col md:w-full sm:w-full w-full mb-0">
                  <label class="vs-input--label">Observaciones:</label>
                  <vs-textarea v-model="observ_transe" rows="2" />
                </div>
                <div
                  class="vx-col md:w-full sm:w-full w-full mb-6"
                  style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                  <vs-checkbox icon-pack="feather" icon="icon-check" v-model="transport_transe">
                    <template v-if="transport_transe">
                      <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
                    </template>
                    <template v-else>
                      <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
                    </template>
                    | Registrar Datos de Guia de Remisión
                  </vs-checkbox>
                </div>
                <template v-if="transport_transe">
                  <vs-divider position="left">Información de Transporte</vs-divider>
                  <div class="vx-col md:w-1/3 sm:w-full w-full mb-6">
                    <vs-input class="w-full" label="Marca Vehículo:" v-model="marca_transe" />
                    <div v-show="error" v-if=" !marca_transe">
                      <div
                        v-for="err in errormarca_transe"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                      ></div>
                    </div>
                  </div>
                  <div class="vx-col md:w-1/3 sm:w-full w-full mb-6">
                    <vs-input class="w-full" label="Placa de Vehículo:" v-model="placa_transe" />
                    <div v-show="error" v-if="!placa_transe">
                      <div
                        v-for="err in errorplaca_transe"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                      ></div>
                    </div>
                  </div>
                  <div class="vx-col md:w-1/3 sm:w-full w-full mb-6">
                    <vs-input class="w-full" label="Color de Vehículo:" v-model="color_transe" />
                    <div v-show="error" v-if="!color_transe">
                      <div
                        v-for="err in errorcolor_transe"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                      ></div>
                    </div>
                  </div>
                  <div class="vx-col md:w-1/2 sm:w-full w-full ml-auto mr-auto mb-6">
                    <vs-input class="w-full" label="Transportado por:" v-model="sr_transe" />
                    <div v-show="error" v-if="!sr_transe">
                      <div
                        v-for="err in errorsr_transe"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                      ></div>
                    </div>
                  </div>
                </template>
              </div>
              <!-- Inicio Agregar Producto-->
              <vs-divider />
              <vs-divider position="left">Registro de Productos</vs-divider>
              <!-- INVOICE TASKS TABLE -->
              <div class="vx-row">
                <div class="vx-col md:w-full sm:w-full w-full mb-6">
                  <a
                    class="flex items-center cursor-pointer mb-4"
                    @click="abrirtranse()"
                  >Añadir Productos</a>
                </div>
                <div class="vx-col md:w-full sm:w-full w-full mb-6">
                  <vs-table hoverFlat :data="contenidotranse" style="font-size: 12px;">
                    <!-- HEADER -->
                    <template slot="thead">
                      <vs-th>CÓDIGO</vs-th>
                      <vs-th>NOMBRE</vs-th>
                      <vs-th class="text-center">STOCK DISPONIBLE</vs-th>
                      <vs-th class="text-center">CANTIDAD A ENVIAR</vs-th>
                      <vs-th class="text-center">
                        <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
                      </vs-th>
                    </template>
                    <!-- DATA -->
                    <template slot-scope="{data}">
                      <vs-tr v-for="(tr, index) in data" :key="index">
                        <vs-td
                          :data="tr.cod_principal"
                          style="width:150px!important;"
                        >{{ tr.cod_principal }}</vs-td>
                        <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
                        <vs-td
                          class="w-full text-center"
                          :data="tr.catidad"
                          style="width:250px!important;"
                        >{{ tr.cantidad }}</vs-td>
                        <vs-td :data="tr.cant_enviadae" style="width:250px!important;">
                          <vs-input
                            class="w-full txt-center"
                            v-model="tr.cant_enviadae"
                            @keypress="solonumeros($event)"
                          />
                          <div v-show="error" v-if="!tr.cant_enviadae">
                            <div
                              v-for="err in tr.errorcant_enviadae"
                              :key="err"
                              v-text="err"
                              class="text-danger"
                            ></div>
                          </div>
                        </vs-td>
                        <vs-td :data="tr.cost_tot_ingreso" style="width:25px!important;">
                          <vx-tooltip
                            text="Eliminar"
                            position="top"
                            style="display: inline-flex;"
                          >
                            <feather-icon
                              icon="TrashIcon"
                              svgClasses="w-5 h-5 hover:text-danger stroke-current"
                              class="pointer"
                              @click="eliminar(index)"
                            />
                          </vx-tooltip>
                        </vs-td>
                        <!--<vs-td :data="tr.cant_recibidae" style="width:250px!important;">
                          <vs-input
                          disabled
                            class="w-full txt-center"
                            v-model="tr.cant_recibidae"
                            @keypress="solonumeros($event)"
                          />
                        </vs-td>-->
                      </vs-tr>
                    </template>
                  </vs-table>
                </div>
              </div>
              <!-- Botones-->
              <div class="vx-row">
                <div class="vx-col w-1/3 ml-auto mt-6 text-center">
                  <vs-button color="success" type="filled" @click="guardarbodegatranse()">Guardar</vs-button>
                </div>
                <div class="vx-col w-1/3 mr-auto mt-6 text-center">
                  <vs-button color="danger" type="filled" @click="ainicio(),cancelar()">Cancelar</vs-button>
                </div>
              </div>
            </vs-tab>
            <!-- Recepcion de Transferencias-->
            <vs-tab class="vx-col" label="Recepción de Transferencias ">
              <div class="vx-row">
                <vs-divider position="left">Información de Transferencia</vs-divider>
                <div class="vx-col md:w-1/4 sm:w-full w-full ml-auto mb-6">
                  <label class="vs-input--label">Seleccione el Número de Transferencia:</label>
                  <select
                    @change="handleSelectedtransr(num_transr)"
                    v-model="num_transr"
                    class="input-select vs-select--input"
                  >
                    <option
                      v-for="(tr,index) in contenidotransr"
                      :Key="index"
                      :value="tr"
                      class="input-select vs-select--input"
                    >{{tr.num_trans}}</option>
                  </select>
                </div>
                <div class="vx-col md:w-1/4 sm:w-full w-full mr-auto mb-6">
                  <label class="vs-input--label">Fecha de Iniciación:</label>
                  <flat-pickr
                    disabled
                    class="w-full"
                    :config="configdateTimePicker"
                    v-model="f_ini_transr"
                    placeholder="Elegir Fecha"
                  />
                </div>
                <div class="vx-col md:w-1/4 sm:w-full w-full mr-auto mb-6">
                  <label class="vs-input--label">Fecha de Finalización :</label>
                  <flat-pickr
                    disabled
                    class="w-full"
                    :config="configdateTimePicker"
                    v-model="f_fin_transr"
                    placeholder="Elegir Fecha"
                  />
                </div>
                <div class="vx-col md:w-1/4 sm:w-full w-full mb-6">
                  <vs-select
                    disabled
                    placeholder="Seleccione uno"
                    class="selectExample w-full"
                    label="Motivo de Transferencia:"
                    label-placeholder="Seleccione uno"
                    vs-multiple
                    v-model="motivo_transr"
                  >
                    <vs-select-item value="Ventas" text="Ventas" />
                    <vs-select-item value="Auto Consumo" text="Auto Consumo" />
                    <vs-select-item value="Egreso por Ajuste" text="Egreso por Ajuste" />
                    <vs-select-item value="Devolucion por Ingreso" text="Devolucion por Ingreso" />
                  </vs-select>
                  <!-- <div v-show="error" v-if="!motivo_transr">
                    <div
                      v-for="err in errormotivo_transr"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>-->
                </div>
                <div class="vx-col md:w-1/2 sm:w-full w-full mb-6">
                  <vs-select
                    disabled
                    placeholder="Bodega emisora"
                    class="w-full"
                    label="Emisor de Transferencia:"
                    label-placeholder
                    vs-multiple
                    autocomplete
                    v-model="emisor_transr"
                  >
                    <vs-select-item
                      :key="res.id_bodega"
                      :value="res.id_bodega"
                      :text="res.nombre"
                      v-for="res in contenidobodegas"
                    />
                  </vs-select>
                </div>
                <div class="vx-col md:w-1/2 sm:w-full w-full mb-6">
                  <vs-select
                    disabled
                    placeholder="Bodega receptora"
                    class="w-full"
                    label="Receptor de Transferencia:"
                    label-placeholder
                    vs-multiple
                    autocomplete
                    v-model="receptor_transr"
                  >
                    <vs-select-item
                      :key="res.id_bodega"
                      :value="res.id_bodega"
                      :text="res.nombre"
                      v-for="res in contenidobodegas"
                    />
                  </vs-select>
                </div>
                <div class="vx-col md:w-full sm:w-full w-full mb-0">
                  <label class="vs-input--label">Observaciones:</label>
                  <vs-textarea disabled v-model="observ_transr" rows="2" />
                </div>
                <div
                  class="vx-col md:w-full sm:w-full w-full mb-6"
                  style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                  <vs-checkbox
                    disabled
                    icon-pack="feather"
                    icon="icon-check"
                    v-model="transport_transr"
                  >
                    <template v-if="transport_transr">
                      <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
                    </template>
                    <template v-else>
                      <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
                    </template>
                    | Registrar Datos de Transporte
                  </vs-checkbox>
                </div>
                <template v-if="transport_transr">
                  <vs-divider position="left">Información de Transporte</vs-divider>
                  <div class="vx-col md:w-1/3 sm:w-full w-full mb-6">
                    <vs-input
                      disabled
                      class="w-full"
                      label="Marca Vehículo:"
                      v-model="marca_transr"
                    />
                  </div>
                  <div class="vx-col md:w-1/3 sm:w-full w-full mb-6">
                    <vs-input
                      disabled
                      class="w-full"
                      label="Placa de Vehículo:"
                      v-model="placa_transr"
                    />
                  </div>
                  <div class="vx-col md:w-1/3 sm:w-full w-full mb-6">
                    <vs-input
                      disabled
                      class="w-full"
                      label="Color de Vehículo:"
                      v-model="color_transr"
                    />
                  </div>
                  <div class="vx-col md:w-1/2 sm:w-full w-full ml-auto mr-auto mb-6">
                    <vs-input
                      disabled
                      class="w-full"
                      label="Transportado por:"
                      v-model="sr_transr"
                    />
                  </div>
                </template>
              </div>
              <!-- Inicio Agregar Producto-->
              <vs-divider />
              <vs-divider position="left">Registro de Productos</vs-divider>
              <div class="divider md:w-1/2 sm:w-full w-full mb-6">Productos Transferidos</div>
              <vs-table hoverFlat :data="contenidoproductostr" style="font-size: 12px;">
                <!-- HEADER -->
                <template slot="thead">
                  <vs-th>CÓDIGO</vs-th>
                  <vs-th>NOMBRE</vs-th>
                  <vs-th>CANTIDAD ENVIADA</vs-th>
                  <vs-th>CANTIDAD RECIBIDA</vs-th>
                </template>
                <!-- DATA -->
                <template slot-scope="{data}">
                  <vs-tr v-for="(tr, index) in data" :key="index">
                    <vs-td style="width:150px!important;">{{ tr.cod_principal }}</vs-td>
                    <vs-td>{{ tr.nombre }}</vs-td>
                    <vs-td style="width:250px!important;">
                      <vs-input disabled class="w-full txt-center" v-model="tr.cant_env" />
                    </vs-td>
                    <vs-td style="width:250px!important;">
                      <vs-input
                        :disabled="tr.podereditar==0"
                        class="w-full txt-center"
                        v-model="tr.cant_recib"
                      />
                      <div v-show="error" v-if="!tr.cant_recib">
                        <div
                          v-for="err in tr.errorcant_recib"
                          :key="err"
                          v-text="err"
                          class="text-danger"
                        ></div>
                      </div>
                    </vs-td>
                  </vs-tr>
                </template>
              </vs-table>
              <!-- Botones-->
              <div class="vx-row" v-if="editartotales!=0">
                <div class="vx-col w-1/3 ml-auto mt-6 text-center">
                  <vs-button color="success" type="filled" @click="guardarbodegatransr()">Guardar</vs-button>
                </div>
                <div class="vx-col w-1/3 mr-auto mt-6 text-center">
                  <vs-button color="danger" type="filled" @click="ainicio(),cancelar()">Cancelar</vs-button>
                </div>
              </div>
            </vs-tab>
          </vs-tabs>
        </vs-tab>
      </vs-tabs>
    </div>
    <!-- Popup Agregar Producto-->
    <vs-popup classContent="popup-example" title="Seleccione el Producto" :active.sync="popupprod">
      <div class="vx-col w-full">
        <vs-input
          class="mb-4 mr-4 w-full"
          v-model="buscarp"
          @keyup="listarp(1,buscarp)"
          v-bind:placeholder="i18nbuscar"
        />
        <vs-table stripe v-model="cuentaarrayp" @selected="handleSelectedp" :data="contenidop">
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
      </div>
    </vs-popup>
    <!-- Popup Stock para egresos-->
    <vs-popup classContent="popup-example" title="Seleccione el Producto" :active.sync="popupstock">
      <div class="vx-col w-full">
        <vs-input
          class="mb-4 mr-4 w-full"
          v-model="buscarp"
          @keyup="listarstock()"
          v-bind:placeholder="i18nbuscar"
        />
        <vs-table
          class="vx-col sm:w-full w-full"
          v-model="cuentastock"
          @selected="handleSelectedstock"
          :data="stockarrayegreso"
          hoverFlat
          style="font-size: 12px;"
        >
          <!-- HEADER -->

          <template slot="thead">
            <vs-th>CÓDIGO</vs-th>
            <vs-th>NOMBRE</vs-th>
            <vs-th>DESCRIPCIÓN</vs-th>
            <vs-th>CANTIDAD</vs-th>
          </template>
          <!-- DATA -->
          <template slot-scope="{data}">
            <vs-tr :data="pr" v-for="(pr,index) in data" :key="index">
              <vs-td v-if="pr.cod_principal">{{ pr.cod_principal }}</vs-td>
              <vs-td v-else>-</vs-td>
              <vs-td v-if="pr.nombrep">{{ pr.nombrep }}</vs-td>
              <vs-td v-else>-</vs-td>
              <vs-td v-if="pr.descripcion">{{ pr.descripcion }}</vs-td>
              <vs-td v-else>-</vs-td>
              <vs-td v-if="pr.cantidad">{{ pr.cantidad }}</vs-td>
              <vs-td v-else>-</vs-td>
            </vs-tr>
          </template>
        </vs-table>
        <vs-pagination
          style="display:none;"
          :total="paginationp.count"
          :max="7"
          class="mt-4"
          v-model="paginap"
          @change="listarstock()"
          prev-icon="arrow_back"
          next-icon="arrow_forward"
        ></vs-pagination>
        <!--<pre>{{ cuentaarray }}</pre>-->
      </div>
    </vs-popup>
    <!-- Popup Stock para trans-->
    <vs-popup
      classContent="popup-example"
      title="Seleccione el Producto"
      :active.sync="popuptranse"
    >
      <div class="vx-col w-full">
        <vs-input
          class="mb-4 mr-4 w-full"
          v-model="buscarp"
          @keyup="listarstock()"
          v-bind:placeholder="i18nbuscar"
        />
        <vs-table
          class="vx-col sm:w-full w-full"
          v-model="cuentastranse"
          @selected="handleSelectedtranse"
          :data="stockarraytranse"
          hoverFlat
          style="font-size: 12px;"
        >
          <!-- HEADER -->

          <template slot="thead">
            <vs-th>CÓDIGO</vs-th>
            <vs-th>NOMBRE</vs-th>
            <vs-th>DESCRIPCIÓN</vs-th>
            <vs-th>CANTIDAD</vs-th>
          </template>
          <!-- DATA -->
          <template slot-scope="{data}">
            <vs-tr :data="pr" v-for="(pr,index) in data" :key="index">
              <vs-td v-if="pr.cod_principal">{{ pr.cod_principal }}</vs-td>
              <vs-td v-else>-</vs-td>
              <vs-td v-if="pr.nombrep">{{ pr.nombrep }}</vs-td>
              <vs-td v-else>-</vs-td>
              <vs-td v-if="pr.descripcion">{{ pr.descripcion }}</vs-td>
              <vs-td v-else>-</vs-td>
              <vs-td v-if="pr.cantidad">{{ pr.cantidad }}</vs-td>
              <vs-td v-else>-</vs-td>
            </vs-tr>
          </template>
        </vs-table>
        <vs-pagination
          style="display:none;"
          :total="paginationp.count"
          :max="7"
          class="mt-4"
          v-model="paginap"
          @change="listarstock()"
          prev-icon="arrow_back"
          next-icon="arrow_forward"
        ></vs-pagination>
        <!--<pre>{{ cuentaarray }}</pre>-->
      </div>
    </vs-popup>
  </vx-card>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import VueUploadMultipleImage from "vue-upload-multiple-image";
import moment from "moment";
import { AgGridVue } from "ag-grid-vue";
import vSelect from "vue-select";

const axios = require("axios");
const $ = require("jquery");
export default {
  data() {
    return {
      //establece calendario español
      configdateTimePicker: {
        locale: SpanishLocale
      },
      //variables bodega
      stockarray: [],
      arraynumtrans: [],
      stockarrayegreso: [],
      stockarraytranse: [],
      id_bodega: null,
      codigo: "",
      nombre: "",
      codigost: "",
      nombrest: "",
      serfactura: "",
      ubicacion: "",
      direccion: "",
      telefono: "",

      //variables ingreso de bodega
      num_ingreso: "",
      fecha_ingreso: moment().format("YYYY-M-D"),
      tipo_ingreso: "",
      observ_ingreso: "",
      id_producto: "",
      id_bodega: "",
      cant_ingreso: "",
      cost_unit_ingreso: "",
      cost_tot_ingreso: "",

      //variables egreso de bodega
      num_egreso: "",
      fecha_egreso: moment().format("YYYY-M-D"),
      tipo_egreso: "",
      observ_egreso: "",
      cod_principal: "",
      prod_egreso: "",
      precio_egreso: "",
      cant_egreso: "",
      cost_egreso: "",

      //variables transferenciae
      num_transe: "",
      f_ini_transe: moment().format("YYYY-M-D"),
      f_fin_transe: moment().format("YYYY-M-D"),
      motivo_transe: "",
      emisor_transe: "",
      receptor_transe: "",
      observ_transe: "",
      transport_transe: false,
      marca_transe: "",
      placa_transe: "",
      color_transe: "",
      sr_transe: "",
      cant_enviadae: "",
      cant_recibidae: "",
      //variabes trasferenciar
      num_transr: [],
      f_ini_transr: "",
      f_fin_transr: "",
      motivo_transr: "",
      emisor_transr: "",
      receptor_transr: "",
      observ_transr: "",
      transport_transr: "",
      marca_transr: "",
      placa_transr: "",
      color_transr: "",
      sr_transr: "",
      cant_env: "",
      cant_recib: "",

      //variables recuperacion
      idrecupera: null,

      //popups
      popupprod: false,
      popupstock: false,
      popuptranse: false,
      /*  Bodegas*/
      contenidobodegas: [],
      /*     Productos   */
      contenidop: [],
      cuentaarrayp: [],
      cuentastock: [],
      cuentastranse: [],
      contenidopr: [],
      contenidostock: [],
      contenidotranse: [],
      contenidotransr: [],
      contenidoproductostr: [],
      buscarp: "",
      i18nbuscar: this.$t("i18nbuscar"),
      //tabla producto
      paginationp: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      paginap: 1,
      cantidadpp: 10000000,
      offsetp: 3,
      /*  Variables error validacion*/
      error: 0,
      errornum_ingreso: [],
      errortipo_ingreso: [],
      errorcant_ingreso: [],
      errorcost_unit_ingreso: [],

      errornum_egreso: [],
      errortipo_egreso: [],
      errorprecio_egreso: [],
      errorcant_egreso: [],
      errorcost_egreso: [],

      errornum_transe: [],
      errormotivo_transe: [],
      errorreceptor_transe: [],
      errormarca_transe: [],
      errorplaca_transe: [],
      errorcolor_transe: [],
      errorsr_transe: [],
      errorcant_enviadae: [],

      errorcant_recib: [],
      editartotales: 0
    };
  },
  //importa calendario español
  components: {
    flatPickr,
    VueUploadMultipleImage,
    AgGridVue,
    "v-select": vSelect
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
      this.idrecupera = this.$route.params.id;
      var url = "/api/abrirproductosbodega/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          this.stockarray = res.data.datos;
          var data = res.data.bodega;
          this.codigo = data.codigo;
          this.nombre = data.nombre;
          this.ubicacion = data.ubicacion;
          this.serfactura = data.serie_factura;
          this.direccion = data.direccion;
          this.telefono = data.telefono;
        })
        .catch(err => {
          console.log(err);
        });
    },
    listartransr() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/abrirbodegatransr/" + this.idrecupera;
      axios.get(url).then(res => {
        this.contenidotransr = res.data.datos;
      });
    },
    listarstock() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/abrirproductosbodega/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          this.stockarrayegreso = res.data.datos;
          this.stockarraytranse = res.data.datos;
          var data = res.data.bodega;
          this.codigo = data.codigo;
          this.nombre = data.nombre;
          this.ubicacion = data.ubicacion;
          this.serfactura = data.serie_factura;
          this.direccion = data.direccion;
          this.telefono = data.telefono;
        })
        .catch(err => {
          console.log(err);
        });
    },
    cancelar() {
      this.num_ingreso = "";
      this.fecha_ingreso = moment().format("YYYY-M-D");
      this.tipo_ingreso = "";
      this.observ_ingreso = "";
      this.id_producto = "";
      this.id_bodega = "";
      this.cant_ingreso = "";
      this.cost_unit_ingreso = "";
      this.cost_tot_ingreso = "";
      this.num_egreso = "";
      this.fecha_egreso = moment().format("YYYY-M-D");
      this.tipo_egreso = "";
      this.observ_egreso = "";
      this.cod_principal = "";
      this.prod_egreso = "";
      this.cant_egreso = "";
      this.precio_egreso = "";
      this.cost_egreso = "";
      this.num_transe = "";
      this.f_ini_transe = "";
      this.f_fin_transe = "";
      this.motivo_transe = "";
      this.emisor_transe = "";
      this.receptor_transe = "";
      this.cant_enviadae = "";
      this.cant_recibidae = "";
      this.num_transr = [];
      this.f_ini_transr = "";
      this.f_fin_transr = "";
      this.motivo_transr = "";
      this.emisor_transr = "";
      this.receptor_transr = "";
      this.observ_transr = "";
      this.transport_transr = "";
      this.marca_transr = "";
      this.placa_transr = "";
      this.color_transr = "";
      this.sr_transr = "";
      this.cant_env = "";
      this.cant_recib = "";

      this.errornum_ingreso = [];
      this.errortipo_ingreso = [];
      this.errorcant_ingreso = [];
      this.errorcost_unit_ingreso = [];

      this.errornum_egreso = [];
      this.errortipo_egreso = [];
      this.errorprecio_egreso = [];
      this.errorcant_egreso = [];
      this.errorcost_egreso = [];

      this.errornum_transe = [];
      this.errormotivo_transe = [];
      this.errorreceptor_transe = [];
      this.errormarca_transe = [];
      this.errorplaca_transe = [];
      this.errorcolor_transe = [];
      this.errorsr_transe = [];
      this.errorcant_enviadae = [];

      this.errorcant_recib = [];
    },
    abrirproductos() {
      this.popupprod = true;
    },
    abrirstock() {
      this.popupstock = true;
      this.listarstock();
    },
    abrirtranse() {
      this.popuptranse = true;
      this.listarstock();
    },
    listarp(pagep, buscarp) {
      var url =
        "/api/productos/" +
        this.usuario.id_empresa +
        "?page=" +
        pagep +
        "&buscar=" +
        buscarp;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidop = respuesta.recupera;
      });
    },
    handleSelectedp(tr) {
      this.popupprod = false;
      this.contenidopr.push({
        id: tr.id_producto,
        cod_principal: tr.cod_principal,
        nombre: tr.nombre,
        cant_ingreso: null,
        cost_unit_ingreso: null,
        cost_tot_ingreso: null,
        errorcant_ingreso: [],
        errorcost_unit_ingreso: []
      });
    },
    handleSelectedstock(pr) {
      this.popupstock = false;
      this.contenidostock.push({
        id: pr.idprod,
        cod_principal: pr.cod_principal,
        nombre: pr.nombrep,
        cantidad: pr.cantidad,
        precio_egreso: null,
        cant_egreso: null,
        cost_egreso: null,
        errorprecio_egreso: [],
        errorcant_egreso: [],
        errorcost_egreso: []
      });
    },
    handleSelectedtranse(pr) {
      this.popuptranse = false;
      this.contenidotranse.push({
        id: pr.idprod,
        cod_principal: pr.cod_principal,
        nombre: pr.nombrep,
        cantidad: pr.cantidad,
        cant_enviadae: null,
        cant_recibidae: null,
        errorcant_enviadae: []
      });
    },
    handleSelectedtransr(data) {
      this.f_ini_transr = data.f_iniciacion;
      this.f_fin_transr = data.f_finalizacion;
      this.motivo_transr = data.motivo_trans;
      this.emisor_transr = data.emisor_trans;
      this.receptor_transr = data.receptor_trans;
      this.observ_transr = data.observ_trans;
      this.transport_transr = data.transporte;
      this.marca_transr = data.marcav;
      this.placa_transr = data.placasv;
      this.color_transr = data.colorv;
      this.sr_transr = data.transportista;

      if (data.id_bodega_transferencia) {
        this.llamarproductostr(data.id_bodega_transferencia);
      }
    },
    listarbodegas() {
      var url = "/api/bodegaall/" + this.usuario.id_empresa;
      axios.get(url).then(res => {
        this.contenidobodegas = res.data;
      });
    },
    llamarproductostr(id) {
      var url = "/api/recuperarproductostr/" + id;
      axios.get(url).then(res => {
        this.contenidoproductostr = res.data.datos;
        this.validarrecibido();
      });
    },
    //store
    guardarbodegaingreso() {
      if (this.validaringreso()) {
        return;
      }
      axios
        .post("/api/guardarbodegaingreso", {
          id_bodega: this.$route.params.id,
          num_ingreso: this.num_ingreso,
          fecha_ingreso: this.fecha_ingreso,
          tipo_ingreso: this.tipo_ingreso,
          observ_ingreso: this.observ_ingreso,
          contenidopr: this.contenidopr,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Ingreso Registrado",
            text: "Ingreso Registrado con éxito",
            color: "success"
          });
          this.ainicio();
          this.cancelar();
          this.listar();
        })
        .catch(err => {});
    },
    guardarbodegaegreso() {
      if (this.validaregreso()) {
        return;
      }
      axios
        .post("/api/guardarbodegaegreso", {
          id_bodega: this.$route.params.id,
          num_egreso: this.num_egreso,
          fecha_egreso: this.fecha_egreso,
          tipo_egreso: this.tipo_egreso,
          observ_egreso: this.observ_egreso,
          contenidostock: this.contenidostock,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Egreso Registrado",
            text: "Egreso Registrado con éxito",
            color: "success"
          });
          this.ainicio();
          this.cancelar();
          this.listar();
        })
        .catch(err => {});
    },
    guardarbodegatranse() {
      if (this.validartranse()) {
        return;
      }
      axios
        .post("/api/guardarbodegatranse", {
          id_bodega: this.$route.params.id,
          num_transe: this.num_transe,
          f_ini_transe: this.f_ini_transe,
          f_fin_transe: this.f_fin_transe,
          motivo_transe: this.motivo_transe,
          emisor_transe: this.$route.params.id,
          receptor_transe: this.receptor_transe,
          observ_transe: this.observ_transe,
          transport_transe: this.transport_transe,
          marca_transe: this.marca_transe,
          placa_transe: this.placa_transe,
          color_transe: this.color_transe,
          sr_transe: this.sr_transe,
          contenidotranse: this.contenidotranse,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Transferencia Enviada",
            text: "Transferencia exitosa",
            color: "success"
          });
          this.ainicio();
          this.cancelar();
          this.listar();
        })
        .catch(err => {});
    },
    guardarbodegatransr() {
      if (this.validartransr()) {
        return;
      }
      axios
        .post("/api/guardarbodegatransr", {
          id_bodega: this.$route.params.id,
          id_bodega_transferencia: this.num_transr.id_bodega_transferencia,
          contenidoproductostr: this.contenidoproductostr,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Transferencia Recibida Actualizada",
            text: "Productos Ingresados a odega",
            color: "success"
          });
          this.ainicio();
          this.cancelar();
          this.listar();
        })
        .catch(err => {});
    },
    /* Validaciones */
    validaringreso() {
      this.error = 0;
      this.errornum_ingreso = [];
      this.errortipo_ingreso = [];
      if (!this.num_ingreso) {
        this.errornum_ingreso.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.tipo_ingreso) {
        this.errortipo_ingreso.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      for (var i = 0; i < this.contenidopr.length; i++) {
        this.contenidopr[i].errorcant_ingreso = [];
        this.contenidopr[i].errorcost_unit_ingreso = [];
        if (!this.contenidopr[i].cant_ingreso) {
          this.contenidopr[i].errorcant_ingreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.contenidopr[i].cost_unit_ingreso) {
          this.contenidopr[i].errorcost_unit_ingreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
      }

      return this.error;
    },
    validaregreso() {
      this.error = 0;
      this.errornum_egreso = [];
      this.errortipo_egreso = [];
      this.errorprecio_egreso = [];
      this.errorcant_egreso = [];
      this.errorcost_egreso = [];

      if (!this.num_egreso) {
        this.errornum_egreso.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.tipo_egreso) {
        this.errortipo_egreso.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      for (var i = 0; i < this.contenidostock.length; i++) {
        this.contenidostock[i].errorprecio_egreso = [];
        this.contenidostock[i].errorcant_egreso = [];
        this.contenidostock[i].errorcost_egreso = [];
        if (!this.contenidostock[i].precio_egreso) {
          this.contenidostock[i].errorprecio_egreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.contenidostock[i].cant_egreso) {
          this.contenidostock[i].errorcant_egreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.contenidostock[i].cost_egreso) {
          this.contenidostock[i].errorcost_egreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
      }
      return this.error;
    },
    validartranse() {
      this.error = 0;
      this.errornum_transe = [];
      this.errormotivo_transe = [];
      this.errorreceptor_transe = [];
      this.errormarca_transe = [];
      this.errorplaca_transe = [];
      this.errorcolor_transe = [];
      this.errorsr_transe = [];
      this.errorcant_enviadae = [];

      if (!this.num_transe) {
        this.errornum_transe.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.motivo_transe) {
        this.errormotivo_transe.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.receptor_transe) {
        this.errorreceptor_transe.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (this.transport_transe == 1) {
        if (!this.marca_transe) {
          this.errormarca_transe.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.placa_transe) {
          this.errorplaca_transe.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.color_transe) {
          this.errorcolor_transe.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.sr_transe) {
          this.errorsr_transe.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
      }

      for (var i = 0; i < this.contenidotranse.length; i++) {
        this.contenidotranse[i].errorcant_enviadae = [];

        if (!this.contenidotranse[i].cant_enviadae) {
          this.contenidotranse[i].errorcant_enviadae.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
      }

      return this.error;
    },
    validartransr() {
      this.error = 0;
      this.errorcant_recib = [];

      for (var i = 0; i < this.contenidoproductostr.length; i++) {
        this.contenidoproductostr[i].errorcant_recib = [];

        if (!this.contenidoproductostr[i].cant_recib) {
          this.contenidoproductostr[i].errorcant_recib.push(
            "Campo obligatorio"
          );
          this.error = 1;
          window.scrollTo(0, 0);
        }

        if (
          this.contenidoproductostr[i].cant_env <
          this.contenidoproductostr[i].cant_recib
        ) {
          this.error = 1;
          this.$vs.notify({
            title: "Danger",
            text: "Cantidad Recibida no puede ser Mayor a la Cantidad Enviada",
            color: "danger"
          });
        }
      }
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
    },
    ainicio() {
      $(".tabproductos")
        .find("li:nth-child(1)>button")
        .click();
      this.motivo_trans = "";
      this.receptor_trans = "";
    },
    eliminar(id) {
      this.contenidopr.splice(id, 1);
      this.contenidostock.splice(id, 1);
      this.contenidotranse.splice(id,1);
    },
    validarrecibido() {
      var total1 = 0;
      this.contenidoproductostr.forEach(el => {
        var total = parseFloat(el.cant_env) - parseFloat(el.cant_recib);
        total1 += parseFloat(el.cant_env) - parseFloat(el.cant_recib);
        if (total == 0) {
          el.podereditar = 0;
        } else {
          el.podereditar = 1;
        }
      });
      this.editartotales = total1;
    }
  },
  mounted() {
    this.listar();
    this.listartransr();
    this.listarbodegas();
    this.listarp(1, this.buscarp);
  }
};
</script>
<style lang="scss">
.txt-center > div > input {
  text-align: center;
}
.text-center > .vs-table-text {
  text-align: center !important;
  display: block;
}
.theme-dark .vx-card select {
  background: #262c49;
}
.vs-tabs--btn{
  display: block!important;
}
</style>