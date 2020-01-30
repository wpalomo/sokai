<template>
  <div id="invoice-page">
    <vx-card>
      <vs-divider position="left">
        <h3>Comprobante</h3>
      </vs-divider>
      <div class="vx-row leading-loose p-base">
        <div class="vx-col sm:w-1/2 w-full mb-3 ml-auto" style="text-align: center;">
          <h6 class="mb-1">Ambiente:</h6>
          <span v-if="contenidoempresa==2">Producción</span>
          <span v-else>Pruebas</span>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-3 ml-auto mr-auto" style="text-align: center;">
          <h6 class="mb-1">Tipo Emisión:</h6>Emisión Normal
        </div>
        <div class="vx-col sm:w-1/4 w-full mb-3">
          <h6 class="mb-1">Orden de compra:</h6>
          <vs-input class="w-full" v-model="ordencompra" :disabled="modofact!=0" />
        </div>
        <div class="vx-col sm:w-1/4 w-full mb-3">
          <h6 class="mb-1">Migo:</h6>
          <vs-input class="w-full" v-model="migo" :disabled="modofact!=0" />
        </div>
                <div class="vx-col sm:w-1/2 w-full mb-2">
                    <h6>Fecha Emisión:</h6>
                    <flat-pickr
                        :disabled="modofact != 0"
                        :config="configdateTimePicker"
                        :change="listarclave()"
                        class="w-full mt-1"
                        v-model="date"
                        placeholder="Seleccionar"
                    ></flat-pickr>
                </div>
                <div class="vx-col sm:w-full w-full mb-2 text-center">
                    <h6 class="mt-4">Clave de acceso:</h6>
                    <p>{{ claveacceso }}</p>
                </div>
                <div
                    class="vx-col sm:w-1/3 w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="guia"
                    >
                        <template v-if="guia">
                            <label
                                class="vs-input--label"
                                style="font-size: 14px;font-weight: bold;"
                                >Si</label
                            >
                        </template>
                        <template v-else>
                            <label
                                class="vs-input--label"
                                style="font-size: 14px;font-weight: bold;"
                                >No</label
                            >
                        </template>
                        | Guia de Remisión
                    </vs-checkbox>
                </div>
            </div>
            <vs-divider position="left" v-if="guia">
                <h3>Guia de remisión</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base" v-if="guia">
                <div
                    class="vx-col sm:w-1/2 w-full mb-3 ml-auto"
                    style="text-align: center;"
                >
                    <h6 class="mb-1">Razón social del transportista</h6>
                    <vs-input
                        class="w-full"
                        :disabled="modofact != 0"
                        v-model="transportista.nombre_transporte"
                    />
                    <div v-show="error" v-if="!transportista.nombre_transporte">
                        <div
                            v-for="err in errornombre_transporte"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div
                    class="vx-col sm:w-1/4 w-full mb-3 ml-auto"
                    style="text-align: center;"
                >
                    <h6 class="mb-1">Tipo Identificación</h6>
                        <vs-select
                            placeholder="Tipo"
                            class="selectExample w-full"
                            vs-multiple
                            v-model="transportista.tipo_identificacion_transporte"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item,index) in options1"
                            />
                        </vs-select>
                    <div
                        v-show="error"
                        v-if="!transportista.tipo_identificacion_transporte"
                    >
                        <div
                            v-for="err in errortipo_identificacion_transporte"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div
                    class="vx-col sm:w-1/4 w-full mb-3 ml-auto"
                    style="text-align: center;"
                >
                    <h6 class="mb-1">Identificación</h6>
                    <vs-input
                        class="w-full"
                        :disabled="modofact != 0"
                        v-model="transportista.identificacion_transporte"
                    />
                    <div
                        v-show="error"
                        v-if="!transportista.identificacion_transporte"
                    >
                        <div
                            v-for="err in erroridentificacion_transporte"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div
                    class="vx-col sm:w-1/3 w-full mb-3 ml-auto"
                    style="text-align: center;"
                >
                    <h6 class="mb-1">fecha de Inicio</h6>
                    <flat-pickr
                        :disabled="modofact != 0"
                        :config="configdateTimePicker"
                        class="w-full mt-1"
                        v-model="transportista.fecha_inicio_transporte"
                        placeholder="Seleccionar"
                    ></flat-pickr>
                    <div
                        v-show="error"
                        v-if="!transportista.fecha_inicio_transporte"
                    >
                        <div
                            v-for="err in errorfecha_inicio_transporte"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div
                    class="vx-col sm:w-1/3 w-full mb-3 ml-auto"
                    style="text-align: center;"
                >
                    <h6 class="mb-1">fecha de Finalización</h6>
                    <flat-pickr
                        :disabled="modofact != 0"
                        :config="configdateTimePicker"
                        class="w-full mt-1"
                        v-model="transportista.fecha_fin_transporte"
                        placeholder="Seleccionar"
                    ></flat-pickr>
                    <div
                        v-show="error"
                        v-if="!transportista.identificacion_transporte"
                    >
                        <div
                            v-for="err in erroridentificacion_transporte"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div
                    class="vx-col sm:w-1/3 w-full mb-3 ml-auto"
                    style="text-align: center;"
                >
                    <h6 class="mb-1">Placa</h6>
                    <vs-input
                        class="w-full"
                        :disabled="modofact != 0"
                        v-model="transportista.placa_transporte"
                    />
                    <div v-show="error" v-if="!transportista.placa_transporte">
                        <div
                            v-for="err in errorplaca_transporte"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3 ml-auto"
                    style="text-align: center;"
                >
                    <h6 class="mb-1">Documento aduanero</h6>
                    <vs-input
                        class="w-full"
                        :disabled="modofact != 0"
                        v-model="transportista.documento_aduanero"
                    />
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3 ml-auto"
                    style="text-align: center;"
                >
                    <h6 class="mb-1">Motivo de translado</h6>
                    <vs-input
                        class="w-full"
                        :disabled="modofact != 0"
                        v-model="transportista.motivo_translado"
                    />
                </div>
            </div>
            <!--Fin encabezado de comprobante-->
            <!--inicion encabezado de Cliente-->
            <vs-divider position="left">
                <h3>Cliente</h3>
            </vs-divider>
            <!--fin modal-->
            <div class="vx-row leading-loose p-base">
                <div class="vx-col w-full mb-4" v-if="modofact == 0">
                    <div class="dropdown-button-container">
                        <vs-dropdown>
                            <a class="flex items-center">
                                Añadir Cliente
                                <i class="material-icons">expand_more</i>
                            </a>
                            <vs-dropdown-menu>
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="
                                        (popupActive2 = true), (tipomodal = 1)
                                    "
                                    >Buscar Cliente</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="crear()"
                                    >Crear Cliente</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                    <div v-show="error" v-if="!id_cliente">
                        <div
                            v-for="err in errorcliente"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2">
                    <h6>Nombre:</h6>
                    <vs-input disabled class="w-full" v-bind:value="nombre" />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2">
                    <h6>Teléfono:</h6>
                    <vs-input disabled class="w-full" v-bind:value="telefono" />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2">
                    <h6>Email:</h6>
                    <vs-input disabled class="w-full" v-bind:value="email" />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2">
                    <h6>Tipo de Identificación:</h6>
                    <vs-select
                        disabled
                        class="selectExample w-full nover"
                        v-bind:value="tipo_identificacion"
                    >
                        <vs-select-item
                            :key="index"
                            :value="item.value"
                            :text="item.text"
                            v-for="(item, index) in tipo_identificacion_menu"
                        />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2">
                    <h6>Identificación:</h6>
                    <vs-input disabled class="w-full" v-bind:value="ruc_ci" />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2">
                    <h6>Dirección:</h6>
                    <vs-input
                        disabled
                        class="w-full"
                        v-bind:value="direccion"
                    />
                </div>
            </div>
            <!--dividir-->
            <!--Fin encabezado de Cliente-->
            <!--INICIO DE TABLA DE PRODUCTOS -->
            <vs-divider position="left">
                <h3>Productos</h3>
            </vs-divider>
            <div class="p-base">
                <a
                    v-if="modofact == 0"
                    class="flex items-center cursor-pointer mb-4"
                    @click="abrirproductos()"
                    >Añadir Productos</a
                >
                <vs-table
                    hoverFlat
                    :data="contenidopr"
                    style="font-size: 12px;"
                >
                    <template slot="thead">
                        <vs-th>CÓDIGO</vs-th>
                        <vs-th>NOMBRE</vs-th>
                        <vs-th>CANTIDAD</vs-th>
                        <vs-th>PRECIO</vs-th>
                        <vs-th>DESCUENTO</vs-th>
                        <!--<vs-th>IVA</vs-th>-->
                        <vs-th>SUBTOTAL</vs-th>
                    </template>
                    <template slot-scope="{ data }">
                        <vs-tr v-for="(tr, index) in data" :key="index">
                            <vs-td :data="tr.codigo">{{ tr.codigo }}</vs-td>
                            <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
                            <vs-td
                                :data="tr.cantidad"
                                style="width:150px!important;"
                                v-if="!modofact"
                            >
                                <vs-input
                                    class="w-full"
                                    placeholder="$0.00"
                                    v-model="tr.cantidad"
                                />
                                <div v-show="error" v-if="!tr.cantidad">
                                    <div
                                        v-for="err in tr.errorcant_ingreso"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </vs-td>
                            <vs-td
                                :data="tr.cantidad"
                                style="width:150px!important;"
                                v-else
                                >{{ tr.cantidad | currency }}</vs-td
                            >
                            <vs-td
                                :data="tr.precio"
                                style="width:150px!important;"
                                v-if="!modofact"
                            >
                                <vs-input
                                    class="w-full"
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
                            <vs-td
                                :data="tr.cantidad"
                                style="width:150px!important;"
                                v-else
                                >{{ tr.precio | currency }}</vs-td
                            >
                            <vs-td
                                :data="tr.descuento"
                                style="width:200px!important;"
                                v-if="!modofact"
                            >
                                <vx-input-group>
                                    <vs-input
                                        class="w-full"
                                        placeholder="$0.00"
                                        v-model="tr.descuento"
                                    />
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                            <button
                                                class="botonstl"
                                                :class="{
                                                    'botonstl elejido':
                                                        tr.p_descuento == 1,
                                                    botonstl:
                                                        tr.p_descuento != 1
                                                }"
                                                @click="tr.p_descuento = 1"
                                            >
                                                $
                                            </button>
                                            <button
                                                class="botonstl"
                                                :class="{
                                                    'botonstl elejido':
                                                        tr.p_descuento == 0,
                                                    botonstl:
                                                        tr.p_descuento != 0
                                                }"
                                                @click="tr.p_descuento = 0"
                                            >
                                                %
                                            </button>
                                        </div>
                                    </template>
                                </vx-input-group>
                            </vs-td>
                            <vs-td
                                :data="tr.cantidad"
                                style="width:150px!important;"
                                v-else
                                >{{ tr.descuento | currency }}</vs-td
                            >
                            <!--<vs-td :data="tr.iva" style="width:200px!important;">
                <vs-select class="selectExample w-full" vs-multiple v-model="tr.iva">
                  <vs-select-item :key="res.id_iva" :value="res.id_iva" :text="res.nombre" v-for="res in contenidoiva"/>
                </vs-select>
              </vs-td>-->
                            <vs-td
                                v-if="tr.p_descuento != 1"
                                v-model="tr.sub"
                                >{{
                                    (tr.cantidad * tr.precio -
                                        (tr.cantidad *
                                            tr.precio *
                                            tr.descuento) /
                                            100)
                                        | currency
                                }}</vs-td
                            >
                            <vs-td v-else v-model="tr.sub">{{
                                (tr.cantidad * tr.precio - tr.descuento)
                                    | currency
                            }}</vs-td>
                            <vs-td v-if="modofact == 0">
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
                <div class="vx-row mt-6">
                    <div class="vx-col sm:w-1/2 w-full">
                        <h6>Observaciones:</h6>
                        <vs-textarea
                            class="w-full"
                            v-model="observacion"
                            rows="2"
                            :disabled="modofact != 0"
                        />
                    </div>
                    <div class="vx-col sm:w-1/2 w-full">
                        <vs-table hoverFlat class="w-full" :data="invoiceData">
                            <vs-tr>
                                <vs-th>SUBTOTAL FINAL</vs-th>
                                <vs-td>{{ subtotalpr | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="subtotalivapr12 > 0">
                                <vs-th>SUBTOTAL IVA 12%</vs-th>
                                <vs-td>{{ subtotalivapr12 | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="ivapr12 > 0">
                                <vs-th>Valor IVA 12%</vs-th>
                                <vs-td>{{ ivapr12 | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="subtotalivapr14 > 0">
                                <vs-th>SUBTOTAL IVA 14%</vs-th>
                                <vs-td>{{ subtotalivapr14 | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="ivapr14 > 0">
                                <vs-th>Valor IVA 14%</vs-th>
                                <vs-td>{{ ivapr14 | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="subtotalivapr0 > 0">
                                <vs-th>SUBTOTAL IVA 0%</vs-th>
                                <vs-td>{{ subtotalivapr0 | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="ivapr0 > 0">
                                <vs-th>Valor IVA 0%</vs-th>
                                <vs-td>{{ ivapr0 | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="subtotalivaprno > 0">
                                <vs-th>NO OBJETO DE IMPUESTO</vs-th>
                                <vs-td>{{ subtotalivaprno | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="ivaprno > 0">
                                <vs-th>VALOR NO OBJETO DE IMPUESTO</vs-th>
                                <vs-td>{{ ivaprno | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="subtotalivaprex > 0">
                                <vs-th>EXENTO DE IVA</vs-th>
                                <vs-td>{{ subtotalivaprex | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr v-if="ivaprex > 0">
                                <vs-th>VALOR EXENTO DE IVA</vs-th>
                                <vs-td>{{ ivaprex | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr>
                                <vs-th>TOTAL DESCUENTO</vs-th>
                                <vs-td>{{ descuentopr | currency }}</vs-td>
                            </vs-tr>
                            <!--<vs-tr>
                <vs-th>TOTAL RECEPTADO</vs-th>
                <vs-td>{{ receptadopr | currency }}</vs-td>
              </vs-tr>
              <vs-tr>
                <vs-th>TOTAL RENTA</vs-th>
                <vs-td>{{ rentapr | currency }}</vs-td>
              </vs-tr>-->
                            <vs-tr>
                                <vs-th>PROPINA</vs-th>
                                <vs-td v-if="!modofact">
                                    <vx-input-group>
                                        <vs-input
                                            class="w-full"
                                            placeholder="$0.00"
                                            v-model="propinapr"
                                        />
                                        <template slot="append">
                                            <div class="append-text btn-addon">
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        'botonstl elejido':
                                                            pp_descuento == 1,
                                                        botonstl:
                                                            pp_descuento != 1
                                                    }"
                                                    @click="pp_descuento = 1"
                                                >
                                                    $
                                                </button>
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        'botonstl elejido':
                                                            pp_descuento == 0,
                                                        botonstl:
                                                            pp_descuento != 0
                                                    }"
                                                    @click="pp_descuento = 0"
                                                >
                                                    %
                                                </button>
                                            </div>
                                        </template>
                                    </vx-input-group>
                                </vs-td>
                                <vs-td v-else>{{ propinapr | currency }}</vs-td>
                            </vs-tr>
                            <vs-tr>
                                <vs-th>VALOR TOTAL</vs-th>
                                <vs-td>{{ totalpr | currency }}</vs-td>
                            </vs-tr>
                        </vs-table>
                    </div>
                </div>
                <!--dividir-->
            </div>
            <!--INICIO DE PAGOS-->
            <vs-divider position="left">
                <h3>Total Facturas</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <label class="vs-input--label">SALDO TOTAL</label>
                    <h1>{{ totalpr | currency }}</h1>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <label class="vs-input--label">SALDO PENDIENTE</label>
                    <h1>{{ (totalpr - totalpagado) | currency }}</h1>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <label class="vs-input--label">SALGO PAGADO</label>
                    <h1>{{ totalpagado | currency }}</h1>
                </div>
            </div>
            <vs-divider position="left" class="flexy">
                <h3>Créditos</h3>
                <vs-switch
                    vs-icon-on="check"
                    color="success"
                    v-if="modofact == 0"
                    class="ml-2"
                    v-model="vercreditos"
                    vs-value="Si"
                    style="margin-top: 4px;"
                >
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-show="vercreditos">
                    <div class="vx-col sm:w-1/2 w-full mb-2 text-center">
                        <label class="vs-input--label">Periodo de pago</label>
                        <vs-select
                            :change="cambiopagopal()"
                            placeholder="Selecciona el periodo de pago"
                            :disabled="totalpr <= 0 || modofact != 0"
                            autocomplete
                            class="selectExample w-full"
                            v-model="periodo_credito"
                        >
                            <vs-select-item value text="Slecciona el periodo" />
                            <vs-select-item value="Dias" text="Dias" />
                            <vs-select-item value="Semanas" text="Semanas" />
                            <vs-select-item value="Meses" text="Meses" />
                            <vs-select-item value="Años" text="Años" />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Plazos de pago</label>
                        <vs-select
                            :change="cambiopagopal()"
                            placeholder="Seleccione"
                            :disabled="totalpr <= 0 || modofact != 0"
                            autocomplete
                            class="selectExample w-full"
                            v-model="plazos_credito"
                        >
                            <vs-select-item
                                v-for="(v, index) in 24"
                                :key="index"
                                :value="v"
                                :text="v + ' Periodos'"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <vs-input
                            :change="cambiopagopal()"
                            class="w-full text-center"
                            label="Monto de pago"
                            :disabled="totalpr <= 0 || modofact != 0"
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
                <h3>Retenciones</h3>
                <vs-switch
                    vs-icon-on="check"
                    color="success"
                    v-if="modofact == 0"
                    class="ml-2"
                    v-model="verretenciones"
                    vs-value="Si"
                    style="margin-top: 4px;"
                >
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div
                    class="vx-row leading-loose p-base"
                    v-show="verretenciones"
                >
                    <div class="w-full" v-if="modofact == 0">
                        <div
                            class="vx-row hovertrash"
                            v-for="(tr, index) in valorretenciones"
                            :key="index"
                        >
                            <div class="w-2/3 ml-auto mr-auto">
                                <div class="vx-row">
                                    <div
                                        class="vx-col md:w-3/5 w-full mb-2 ml-auto text-center"
                                    >
                                        <label class="vs-input--label"
                                            >Valores por IVA</label
                                        >
                                        <vs-select
                                            :disabled="
                                                totalpr <= 0 ||
                                                    modofact != 0 ||
                                                    (totalef == 100 &&
                                                        tr.iva == null)
                                            "
                                            placeholder="Selecciona la retención"
                                            @change="agregarretencion()"
                                            autocomplete
                                            class="selectExample w-full"
                                            v-model="tr.iva"
                                        >
                                            <vs-select-item
                                                v-for="(tr,
                                                index) in listretenciones"
                                                :key="index"
                                                :value="tr"
                                                :text="tr.descrip_retencion"
                                                v-if="tr.tipo_retencion =='Retencion IVA Ventas'"
                                            />
                                            <!-- v-if="tr.tipo_retencion=='Retencion IVA Ventas'"-->
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input
                                            label="Valor"
                                            class="w-full"
                                            disabled
                                            v-model="ivapr12"
                                        />
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input
                                            label="Porcentaje"
                                            class="w-full"
                                            disabled
                                            v-model="tr.porcentajeiva"
                                        />
                                    </div>
                                    <div class="flex-1 mb-2 text-center">
                                        <vs-input
                                            label="Cantidad"
                                            class="w-full"
                                            disabled
                                            v-model="tr.cantidadiva"
                                        />
                                    </div>
                                </div>
                                <div class="vx-row">
                                    <div
                                        class="vx-col md:w-2/3 w-full mb-2 mr-auto text-center"
                                    >
                                        <label class="vs-input--label"
                                            >Valores por RENTA</label
                                        >
                                        <vs-select
                                            :disabled="
                                                totalpr <= 0 || modofact != 0
                                            "
                                            placeholder="Selecciona la retención"
                                            @change="agregarretencion()"
                                            autocomplete
                                            class="selectExample w-full"
                                            v-model="tr.renta"
                                        >
                                            <vs-select-item
                                                v-for="(tr,
                                                index) in listretenciones"
                                                :key="index"
                                                :value="tr"
                                                :text="tr.descrip_retencion"
                                                v-if="tr.tipo_retencion == 'Retencion Fuente Ventas' "
                                            />
                                            <!--v-if="tr.tipo_retencion=='Retencion Fuente Ventas'"-->
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input
                                            label="Base"
                                            :disabled="
                                                totalpr <= 0 || modofact != 0
                                            "
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
                                        <vs-input
                                            label="Cantidad"
                                            class="w-full"
                                            disabled
                                            v-model="tr.cantidadrenta"
                                        />
                                    </div>
                                    <vs-divider
                                        v-if="
                                            valorretenciones.length > 1 &&
                                                valorretenciones.length - 1 !=
                                                    index
                                        "
                                        position="left"
                                    ></vs-divider>
                                </div>
                            </div>
                            <feather-icon
                                v-if="index >= 1 && !modofact"
                                icon="TrashIcon"
                                style="position: absolute !important;right: 125px;margin-top: 70px;display: none;"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer trasher"
                                @click="eliminararrayretencion(index)"
                            />
                        </div>
                    </div>
                    <div class="w-full" v-else>
                        <div
                            class="vx-row hovertrash"
                            v-for="(tr, index) in valorretenciones"
                            :key="index"
                        >
                            <div class="w-2/3 ml-auto mr-auto">
                                <div class="vx-row">
                                    <div
                                        class="vx-col md:w-3/4 w-full mb-2 ml-auto text-center"
                                    >
                                        <label class="vs-input--label"
                                            >Valores por IVA</label
                                        >
                                        <vs-select
                                            :disabled="
                                                totalpr <= 0 ||
                                                    modofact != 0 ||
                                                    (totalef == 100 &&
                                                        tr.iva == null)
                                            "
                                            placeholder="Selecciona la retención"
                                            @change="agregarretencion()"
                                            autocomplete
                                            class="selectExample w-full"
                                            v-model="tr.id_retencion_iva"
                                        >
                                            <vs-select-item
                                                v-for="(tr,
                                                index) in listretenciones"
                                                :key="index"
                                                :value="tr.id_retencion"
                                                :text="tr.descrip_retencion"
                                            />
                                            <!-- v-if="tr.tipo_retencion=='Retencion IVA Ventas'"-->
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input
                                            label="Porcentaje"
                                            class="w-full"
                                            disabled
                                            v-model="tr.porcentajeiva"
                                        />
                                    </div>
                                    <div class="flex-1 mb-2 text-center">
                                        <vs-input
                                            label="Cantidad"
                                            class="w-full"
                                            disabled
                                            v-model="tr.cantidadiva"
                                        />
                                    </div>
                                </div>
                                <div class="vx-row">
                                    <div
                                        class="vx-col md:w-2/3 w-full mb-2 mr-auto text-center"
                                    >
                                        <label class="vs-input--label"
                                            >Valores por RENTA</label
                                        >
                                        <vs-select
                                            :disabled="
                                                totalpr <= 0 || modofact != 0
                                            "
                                            placeholder="Selecciona la retención"
                                            @change="agregarretencion()"
                                            autocomplete
                                            class="selectExample w-full"
                                            v-model="tr.id_retencion_renta"
                                        >
                                            <vs-select-item
                                                v-for="(tr,
                                                index) in listretenciones"
                                                :key="index"
                                                :value="tr.id_retencion"
                                                :text="tr.descrip_retencion"
                                            />
                                            <!--v-if="tr.tipo_retencion=='Retencion Fuente Ventas'"-->
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input
                                            label="Base"
                                            :disabled="
                                                totalpr <= 0 || modofact != 0
                                            "
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
                                        <vs-input
                                            label="Cantidad"
                                            class="w-full"
                                            disabled
                                            v-model="tr.cantidadrenta"
                                        />
                                    </div>
                                    <vs-divider
                                        v-if="
                                            valorretenciones.length > 1 &&
                                                valorretenciones.length - 1 !=
                                                    index
                                        "
                                        position="left"
                                    ></vs-divider>
                                </div>
                            </div>
                            <feather-icon
                                v-if="index >= 1 && !modofact"
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
                <h3>Pagos</h3>
                <vs-switch
                    vs-icon-on="check"
                    v-if="modofact == 0"
                    color="success"
                    class="ml-2"
                    v-model="verpagos"
                    vs-value="Si"
                    style="margin-top: 4px;"
                >
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-show="verpagos">
                    <div class="w-full" v-if="modofact == 0">
                        <div
                            class="vx-row hovertrash"
                            v-for="(tr, index) in valorpagos"
                            :key="index"
                        >
                            <div
                                class="vx-col w-full mb-2 text-center"
                                :class="{
                                    'sm:w-1/6':
                                        tr.metodo_pago != '' &&
                                        tr.metodo_pago != 'Efectivo',
                                    'sm:w-1/3':
                                        tr.metodo_pago == '' ||
                                        tr.metodo_pago == 'Efectivo'
                                }"
                            >
                                <label class="vs-input--label"
                                    >Método de pago</label
                                >
                                <vs-select
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    placeholder="Selecciona el método de pago"
                                    @change="agregarpago()"
                                    autocomplete
                                    class="selectExample w-full"
                                    v-model="tr.metodo_pago"
                                >
                                    <vs-select-item
                                        value="Efectivo"
                                        text="Efectivo"
                                    />
                                    <vs-select-item
                                        value="Deposito"
                                        text="Deposito"
                                    />
                                    <vs-select-item
                                        value="Cheque"
                                        text="Cheque"
                                    />
                                    <vs-select-item
                                        value="Transferencia"
                                        text="Transferencia"
                                    />
                                    <vs-select-item
                                        value="Tarjeta Credito"
                                        text="Tarjeta Credito"
                                    />
                                    <vs-select-item value="Otro" text="Otro" />
                                </vs-select>
                            </div>
                            <div
                                class="vx-col sm:w-1/3 w-full mb-2 text-center"
                                v-if="
                                    tr.metodo_pago != 'Tarjeta Credito' &&
                                        tr.metodo_pago != 'Otro' &&
                                        tr.metodo_pago != 'Efectivo' &&
                                        tr.metodo_pago != ''
                                "
                            >
                                <vs-input
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    class="w-full text-center"
                                    label="Banco"
                                    v-model="tr.banco"
                                />
                            </div>
                            <div
                                class="vx-col sm:w-1/3 w-full mb-2 text-center"
                                v-else-if="tr.metodo_pago == 'Tarjeta Credito'"
                            >
                                <vs-input
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    class="w-full text-center"
                                    label="Nro. Tarjeta"
                                    v-model="tr.tarjeta"
                                />
                            </div>
                            <div
                                class="vx-col sm:w-1/3 w-full mb-2 text-center"
                                v-else-if="tr.metodo_pago == 'Otro'"
                            >
                                <vs-input
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    class="w-full text-center"
                                    label="Cuenta contable"
                                    v-model="tr.cuenta"
                                />
                            </div>
                            <div
                                class="vx-col sm:w-1/6 w-full mb-2 text-center"
                            >
                                <vs-input
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    class="w-full text-center"
                                    label="Cantidad"
                                    @change="agregarpago()"
                                    v-model="tr.cantidad_pago"
                                />
                            </div>
                            <div
                                class="vx-col w-full mb-2 text-center"
                                :class="{
                                    'sm:w-1/3':
                                        tr.metodo_pago != '' &&
                                        tr.metodo_pago != 'Efectivo',
                                    'sm:w-1/2':
                                        tr.metodo_pago == '' ||
                                        tr.metodo_pago == 'Efectivo'
                                }"
                            >
                                <label class="vs-input--label"
                                    >Comentario</label
                                >
                                <vs-textarea
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    v-model="tr.comentario_pago"
                                    rows="1"
                                />
                            </div>
                            <feather-icon
                                v-if="index >= 1 && !modofact"
                                icon="TrashIcon"
                                style="position: absolute!important;right: 15px;margin-top: 38px;display:none"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer trasher"
                                @click="eliminararraypagos(index)"
                            />
                        </div>
                    </div>
                    <div class="w-full" v-else>
                        <div
                            class="vx-row hovertrash"
                            v-for="(tr, index) in valorpagos"
                            :key="index"
                        >
                            <div
                                class="vx-col w-full mb-2 text-center"
                                :class="{
                                    'sm:w-1/6': tr.forma_pago != '',
                                    'sm:w-1/3': tr.forma_pago == ''
                                }"
                            >
                                <label class="vs-input--label"
                                    >Método de pago</label
                                >
                                <vs-select
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    placeholder="Selecciona el método de pago"
                                    @change="agregarpago()"
                                    autocomplete
                                    class="selectExample w-full"
                                    v-model="tr.forma_pago"
                                >
                                    <vs-select-item
                                        value="Efectivo"
                                        text="Efectivo"
                                    />
                                    <vs-select-item
                                        value="Deposito"
                                        text="Deposito"
                                    />
                                    <vs-select-item
                                        value="Cheque"
                                        text="Cheque"
                                    />
                                    <vs-select-item
                                        value="Transferencia"
                                        text="Transferencia"
                                    />
                                    <vs-select-item
                                        value="Tarjeta Credito"
                                        text="Tarjeta Credito"
                                    />
                                    <vs-select-item value="Otro" text="Otro" />
                                </vs-select>
                            </div>
                            <div
                                class="vx-col sm:w-1/3 w-full mb-2 text-center"
                                v-if="
                                    tr.forma_pago != 'Tarjeta Credito' &&
                                        tr.forma_pago != 'Otro' &&
                                        tr.forma_pago != ''
                                "
                            >
                                <vs-input
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    class="w-full text-center"
                                    label="Banco"
                                    v-model="tr.banco"
                                />
                            </div>
                            <div
                                class="vx-col sm:w-1/3 w-full mb-2 text-center"
                                v-else-if="tr.forma_pago == 'Tarjeta Credito'"
                            >
                                <vs-input
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    class="w-full text-center"
                                    label="Nro. Tarjeta"
                                    v-model="tr.nro_tarjeta"
                                />
                            </div>
                            <div
                                class="vx-col sm:w-1/3 w-full mb-2 text-center"
                                v-else-if="tr.forma_pago == 'Otro'"
                            >
                                <vs-input
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    class="w-full text-center"
                                    label="Cuenta contable"
                                    v-model="tr.cta_contable"
                                />
                            </div>
                            <div
                                class="vx-col sm:w-1/6 w-full mb-2 text-center"
                            >
                                <vs-input
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    class="w-full text-center"
                                    label="Cantidad"
                                    @change="agregarpago()"
                                    v-model="tr.monto"
                                />
                            </div>
                            <div
                                class="vx-col w-full mb-2 text-center"
                                :class="{
                                    'sm:w-1/3': tr.forma_pago != '',
                                    'sm:w-1/2': tr.forma_pago == ''
                                }"
                            >
                                <label class="vs-input--label"
                                    >Comentario</label
                                >
                                <vs-textarea
                                    :disabled="totalpr <= 0 || modofact != 0"
                                    v-model="tr.comentario"
                                    rows="1"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
            <div class="vx-col w-full" v-if="modofact == 0">
                <vs-button
                    color="success"
                    type="filled"
                    v-if="modofact == !$route.params.id"
                    @click="guardar()"
                    >Guardar</vs-button
                >
                <vs-button
                    color="success"
                    type="filled"
                    v-else
                    @click="guardar()"
                    >Guardar</vs-button
                >
                <!--<vs-button color="success" type="filled" @click="editar()">Guardar1</vs-button>-->
                <vs-button
                    color="danger"
                    type="filled"
                    v-if="modofact == !$route.params.id"
                    to="/facturacion/proforma"
                    >Cancelar</vs-button
                >
                <vs-button
                    color="danger"
                    type="filled"
                    v-else
                    to="/facturacion/factura-venta"
                    >Cancelar</vs-button
                >
            </div>
            <div class="vx-col w-full" v-else>
                <vs-button
                    color="danger"
                    type="filled"
                    to="/facturacion/factura-venta"
                    >Regresar</vs-button
                >
            </div>
        </vx-card>
        <!--MODAL LISTAR CLIENTE aqui -->
        <div class="demo-alignment">
            <vs-popup
                classContent="popup-example"
                title="Seleccione Cliente"
                :active.sync="popupActive2"
            >
                <div class="vx-row">
                    <div class="vx-col w-full" v-if="tipomodal == 1">
                        <vs-input
                            class="mb-4 mr-4 w-full"
                            v-model="buscar"
                            @keyup.enter="listar(1, buscar)"
                            v-bind:placeholder="i18nbuscar"
                        />
                        <vs-table
                            stripe
                            v-model="cuentaarray"
                            @selected="handleSelected"
                            :data="contenido"
                        >
                            <template slot="thead">
                                <vs-th>Nombre</vs-th>
                                <vs-th>Identificación</vs-th>
                                <vs-th>Dirección</vs-th>
                                <vs-th>Email</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :data="tr"
                                    :key="indextr"
                                    v-for="(tr, indextr) in data"
                                >
                                    <vs-td :data="data[indextr].nombre">{{
                                        data[indextr].nombre
                                    }}</vs-td>
                                    <vs-td
                                        :data="data[indextr].identificacion"
                                        >{{
                                            data[indextr].identificacion
                                        }}</vs-td
                                    >
                                    <vs-td :data="data[indextr].direccion">{{
                                        data[indextr].direccion
                                    }}</vs-td>
                                    <vs-td :data="data[indextr].email">{{
                                        data[indextr].email
                                    }}</vs-td>
                                </vs-tr>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :data="datos"
                                    :key="index"
                                    v-for="(datos, index) in data"
                                >
                                    <vs-td>{{ datos.nombre }}</vs-td>
                                    <vs-td v-if="datos.identificacion">{{
                                        datos.identificacion
                                    }}</vs-td>
                                    <vs-td v-if="datos.direccion">{{
                                        datos.direccion
                                    }}</vs-td>
                                    <vs-td v-if="datos.email">{{
                                        datos.email
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                    <div class="vx-col w-full" v-else>
                        <vs-input
                            class="mb-4 mr-4 w-full"
                            v-model="buscarp"
                            @keyup.enter="listarp(1, buscarp)"
                            v-bind:placeholder="i18nbuscar"
                        />
                        <vs-table
                            stripe
                            v-model="cuentaarrayp"
                            @selected="handleSelectedp"
                            :data="contenidop"
                        >
                            <template slot="thead">
                                <vs-th>Código</vs-th>
                                <vs-th>Nombre</vs-th>
                                <vs-th>Descripcion</vs-th>
                                <vs-th>Marca</vs-th>
                                <vs-th>Modelo</vs-th>
                                <vs-th>Costo</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :data="tr"
                                    :key="indextr"
                                    v-for="(tr, indextr) in data"
                                >
                                    <vs-td v-if="tr.cod_principal">{{
                                        tr.cod_principal
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="tr.nombre">{{
                                        tr.nombre
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="tr.descripcion">{{
                                        tr.descripcion
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="tr.nombremarca">{{
                                        tr.nombremarca
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="tr.nombremodelo">{{
                                        tr.nombremodelo
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="tr.costo_total">{{
                                        tr.costo_total
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </div>
            </vs-popup>
        </div>
        <vs-popup classContent="popup-example" title="Creación de cliente" :active.sync="popupActive4">
            <div class="vx-row">
                <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" v-if="codigoen" label="Código" v-model="codigocliente" />
            <vs-input class="w-full" v-else label="Código" v-bind:value="codigocliente" disabled />
            <!---->
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="Nombres" v-model="nombrecliente" />
            <div v-show="error" v-if="!nombrecliente">
              <span class="text-danger" v-for="err in errornombre" :key="err" v-text="err"></span>
            </div>
          </div>

          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="-Seleccione-"
              class="selectExample w-full"
              v-model="tipo_identificacion_cliente"
              label="Tipo de Identificación"
              
            >
            <!--@change="cambio(tipo_identificacion)"-->
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in tipo_identificacion_array"
              />
            </vs-select>
            <div v-show="error" v-if="!tipo_identificacion_cliente">
              <span
                class="text-danger"
                v-for="err in errortipo_identificacion"
                :key="err"
                v-text="err"
              ></span>
            </div>
          </div>
           <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input
              class="w-full"
              label="C.I"
              v-model="ruc_cicliente"
              v-if="tipo_identificacion=='Cédula de Identidad'"
              maxlength="10"
            />
            <vs-input
              class="w-full"
              label="Ruc"
              v-model="ruc_cicliente"
              v-else-if="tipo_identificacion=='Ruc'"
              
              maxlength="13"
            />

           <vs-input class="w-full" label="Identificación" v-model="ruc_cicliente" v-else />
            <div v-show="error" v-if="!ruc_cicliente">
              <span class="text-danger" v-for="err in errorcedula" :key="err" v-text="err"></span>
            </div>
          </div>
          <div v-if="tipopasaporte" class="vx-col sm:w-1/6 w-full mb-6">
            <label class="vs-input--label">País</label>
            <vs-select autocomplete class="selectExamplen w-full" v-model="codigopais">
              <vs-select-item
                :key="res.id_codigopais"
                :value="res.codigo_ISO_alpha_2"
                :text="res.nombre_pais"
                v-for="res in contenidocodigopais"
              />
            </vs-select>
          </div>
          <div v-if="tipopasaporte" class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Código País" v-model="codigopais" disabled />
          </div>

          <div class="vx-col sm:w-1/6 w-full mb-6">
            <label class="vs-input--label">Grupo Cliente</label>
            <vs-select
              placeholder="buscar"
              autocomplete
              class="selectExample w-full"
              v-model="grupo_cliente"
            >
              <vs-select-item
                :key="res.id_grupo_cliente"
                :value="res.id_grupo_cliente"
                :text="res.nombre_grupo"
                v-for="res in grupo_cliente2"
              />
            </vs-select>
            <div v-show="error" v-if="grupo_cliente">
              <span
                class="text-danger"
                v-for="err in errorgrupo_cliente"
                :key="err"
                v-text="err"
              ></span>
            </div>
          </div>

          <div class="vx-col sm:w-1/6 w-full mb-6">
            <label class="vs-input--label">Tipo Cliente</label>
            <vs-select
              placeholder="buscar"
              autocomplete
              class="selectExample w-full"
              v-model="tipo_cliente_cli"
            >
              <vs-select-item
                :key="res.id_tipo_cliente"
                :value="res.id_tipo_cliente"
                :text="res.descripcion_tipo_cliente"
                v-for="res in grupo_cliente4"
              />
            </vs-select>
          </div>

          <!--ffffff-->

          <div class="vx-col sm:w-1/6 w-full mb-6">
            <label class="vs-input--label">Grupo Tributario</label>
            <vs-select autocomplete class="selectExample w-full" v-model="grupo_tributario">
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in grupo_tributario_array"
              />
            </vs-select>
            <div v-show="error" v-if="!grupo_tributario">
              <span
                class="text-danger"
                v-for="err in errorgrupo_tributario"
                :key="err"
                v-text="err"
              ></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Dirección" v-model="direccioncli" />
            <div v-show="error" v-if="!direccioncli">
              <span class="text-danger" v-for="err in errordireccion" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Provincia</label>
            <vs-select
              placeholder="buscar"
              autocomplete
              class="selectExample w-full"
              v-model="provincia"
              @change="getCiudades();canton='';parroquia=''"
            >
              <vs-select-item
                v-for="data in provincias2"
                :key="data.id_provincia"
                :value="data.id_provincia"
                :text="data.nombre"
              />
            </vs-select>

            <div v-show="error" v-if="!provincia">
              <span class="text-danger" v-for="err in errorprovincia" :key="err" v-text="err"></span>
            </div>
          </div>
          
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cantón</label>
            <vs-select
              placeholder="buscar"
              autocomplete
              class="selectExample w-full"
              v-model="canton"
              @change="getParroquias()"
            >
              <vs-select-item
                v-for="data in ciudades2"
                :key="data.id_ciudad"
                :value="data.id_ciudad"
                :text="data.nombre"
              />
            </vs-select>

            <div v-show="error" v-if="!canton">
              <span class="text-danger" v-for="err in errorcanton" :key="err" v-text="err"></span>
            </div>
          </div>

          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Parroquia</label>
            <vs-select
              placeholder="buscar"
              autocomplete
              class="selectExample w-full"
              v-model="parroquia"
              @change="getParroquias()"
            >
              <vs-select-item
                v-for="data in parroquias2"
                :key="data.id_parroquia"
                :value="data.id_parroquia"
                :text="data.nombre_parroquia"
              />
            </vs-select>
            <div v-show="error" v-if="!parroquia">
              <span class="text-danger" v-for="err in errorparroquia" :key="err" v-text="err"></span>
            </div>
          </div>
          <div
            class="vx-col sm:w-1/4 w-full mb-6"
            style="margin-top: 30px; margin-bottom: 0.2rem !important;"
          >
            <vs-checkbox icon-pack="feather" icon="icon-check" v-model="radios1">
              <template v-if="radios1">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | Parte relacionada
            </vs-checkbox>
          </div>

          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input class="w-full" label="E-mail" v-model="emailcliente" />

            <div v-show="error" v-if="emailinvalido=true">
              <span class="text-danger" v-for="err in erroremail" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input
              class="w-full"
              label="Teléfono"
              v-model="telefonocliente"
              @keypress="solonumeros"
              maxlength="17"
            />
            <div v-show="error" v-if="!telefonocliente">
              <span class="text-danger" v-for="err in errortelefono" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input
              class="w-full"
              label="Contacto"
              v-model="contacto"
            />
            <div v-show="error" v-if="!contacto">
              <span class="text-danger" v-for="err in errorcontacto" :key="err" v-text="err"></span>
            </div>
          </div>
        
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Vendedor</label>
            <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="vendedor">
              <vs-select-item
                :key="res.id_vendedor"
                :value="res.id_vendedor"
                :text="res.nombre_vendedor"
                v-for="res in grupo_cliente3"
              />
            </vs-select>
          </div>

          <!---->

          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Estado</label>
            <vs-select autocomplete class="selectExample w-full" v-model="estado">
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in estado_array"
              />
            </vs-select>
            <div v-show="error" v-if="!estado">
              <span class="text-danger" v-for="err in errorestado" :key="err" v-text="err"></span>
            </div>
          </div>

          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input
              class="w-full"
              label="Descuento %"
              v-model="descuento"
              @keypress="solonumeros($event)"
              maxlength="17"
            />
            
          </div>
          <!---->
          <div class="vx-col w-1/2 mb-6">
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class="mb-base">
              <vs-input class="w-full" v-model="cuenta_contable" @keypress="solonumeros($event) " />
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary" @click="popupActive=true">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>

          <!-- Popup cuenta contable -->
          <vs-popup title="Seleccione una Cuenta Contable" :active.sync="popupActive">
            <div class="con-exemple-prompt">
              <vs-input
                class="mb-4 md:mb-0 mr-4 w-full"
                v-model="buscar"
                
                v-bind:placeholder="i18nbuscar"
              />
              <!--@keyup="listarcuenta(1,buscar)"-->
              <vs-table
                stripe
                v-model="cuentaarray3"
                @selected="handleSelected"
                :data="contenidocuenta"
              >
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
          <!-- Fin popup cuenta contable -->
          <!---->
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input
              class="w-full"
              label="Número Pagos"
              v-model="num_pago"
              @keypress="solonumeros($event)"
              maxlength="17"
            />
            
          </div>

          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Lista de Precios</label>
            <vs-select autocomplete class="selectExample w-full" v-model="lista_precios">
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in lista_precios_array"
              />
            </vs-select>
          </div>

          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Forma de Pago</label>
            <vs-select autocomplete class="selectExample w-full" v-model="forma_pago">
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in forma_pago_array"
              />
            </vs-select>
            
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input
              class="w-full"
              label="Límite de Credito"
              v-model="limite_credito"
              @keypress="solonumeros($event)"
              maxlength="17"
            />
            
          </div>
          <div class="vx-col sm:w-full w-full mb-6">
            <div>
              <label class="vs-input--label">Comentario:</label>
              <vs-textarea v-model="comentario" rows="3" />
            </div>
          </div>
          <div class="vx-col w-full">

            <vs-button color="success" type="filled" @click="guardarCliente()">GUARDAR</vs-button>
            <vs-button color="danger" type="filled" @click="popupActive4=false">CANCELAR</vs-button>
          </div>
            </div>
            
        </vs-popup>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
import $ from "jquery";
import { log } from "util";
const axios = require("axios");
export default {
    data() {
        return {
            tipomodal: 0,
            cuentaarray: [],
            cuentaarrayp: [],
            identificacion: "",
            contenidoempresa: [],
            //cliente
            nombre: "",
            telefono: "",
            email: "",
            ruc_ci: "",
            direccion: "",
            observacion: "",
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
            cantidadp: 10,
            offset: 3,
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
            cantidadpp: 10,
            offsetp: 3,
            paginationpr: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            paginapr: 1,
            cantidadppr: 10,
            offsetpr: 3,
            //buscador
            criterio: "nombre",
            //otros valores
            gridApi: null,
            buscar: "",
            buscarp: "",
            buscarpr: "",
            i18nbuscar: this.$t("i18nbuscar"),
            contenido: [],
            contenidop: [],
            contenidopr: [],
            value1: "",
            value2: "",
            popupActive2: false,
            popupActive3: false,
            forma_pago: "",
            forma_pago_array: "",
            tipo_identificacion: "",
            tipo_identificacion_array: "",
            date: null,
            configdateTimePicker: {
                locale: SpanishLocale
            },
            tipo_identificacion_menu: [
                { text: "Seleccione", value: 0 },
                { text: "Cédula de Identidad", value: 1 },
                { text: "Ruc", value: 2 },
                { text: "Pasaporte", value: 3 },
                { text: "Consumidor Final", value: 4 }
            ],
            forma_pago_array: [
                { text: "Seleccione", value: 0 },
                { text: "Efectivo", value: "Efectivo" },
                { text: "Cheque", value: "Cheque" },
                { text: "Tarjeta", value: "Tarjeta" },
                { text: "Crédito", value: "Crédito" }
            ],
            companyDetails: {
                name: "Microsion Technologies Pvt. Ltd.",
                addressLine1: "9 N. Sherwood Court",
                addressLine2: "Elyria, OH",
                zipcode: "94203",
                mailId: "hello@pixinvent.net",
                mobile: "+91 999 999 9999"
            },
            recipientDetails: {
                fullName: "Peter Stark",
                addressLine1: "8577 West West Drive ",
                addressLine2: "Holbrook, NY",
                zipcode: "90001",
                mailId: "peter@mail.com",
                mobile: "+91 988 888 8888"
            },
            invoiceDetails: {
                invoiceNo: "001/2019",
                invoiceDate: "Mon Dec 10 2018 07:46:00 GMT+0000 (GMT)"
            },
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
            id_cliente: null,
            contenidoiva: [],
            contenidoice: [],
            total: null,
            propinapr: null,
            claveacceso: null,
            switch1: false,
            ordencompra: "",
            migo: "",
            periodo_credito: null,
            plazos_credito: null,
            monto_credito: 0,
            pago_credito: null,
            retencioniva: null,
            retencionrenta: null,
            /*metodo_pago:null, 
            cantidad_pago:null, 
            comentario_compra:null,*/
            valorpagos: [
                {
                    metodo_pago: "",
                    cantidad_pago: null,
                    comentario_pago: "",
                    banco: "",
                    tarjeta: "",
                    cuenta: ""
                }
            ],
            contenido_p12: null,
            contenido_comprobante: [],
            recueidfact: null,
            modofact: 1,
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
            totalef: null,
            //errores
            error: 0,
            errorcliente: [],
            errorcant_ingreso: [],
            errorcost_unit_ingreso: [],
            verretenciones: false,
            verpagos: false,
            vercreditos: false,
            pp_descuento: 1,
            totalpropinaf: 0,
            guia: 0,
            //transportista
            transportista: {
                nombre_transporte: "",
                tipo_identificacion_transporte: null,
                identificacion_transporte: "",
                fecha_inicio_transporte: "",
                fecha_fin_transporte: "",
                placa_transporte: "",
                documento_aduanero: "",
                motivo_translado: ""
            },
            errornombre_transporte: [],
            errortipo_identificacion_transporte: [],
            erroridentificacion_transporte: [],
            errorfecha_inicio_transporte: [],
            errorfecha_fin_transporte: [],
            errorplaca_transporte: [],
            tipofactura:"factura",
            //valores cliente
            popupActive4:false,
            codigoen: 0,
            //variables para traer una columna plan ctas
            cuentaarray3: [],
            //
            //cuenta contable listar
            contenidocuenta: [],
            popupActive: false,

            //variables paginacion de las tablas
            pagination1: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagina1: 1,
            cantidadp1: 10,
            offset: 3,
            gridApi: null,
            contenido: [],
            //buscador
            buscar: "",
            criterio1: "codcta",
            
            //
            idActivo: "",
            cuenta_contable: "",
            tipopasaporte: 0,
            codigo1: "",
            contenidocuenta: [],
            contenidocodigopais: [],
            provincias2: [],
            grupo_cliente2: [],
            grupo_cliente3: [],
            grupo_cliente4: [],
            ciudades2: [],
            parroquias2: [],
            
            codigocliente: "",
            nombrecliente: "",
            ruc_cicliente: "",
            grupo_cliente: "",
            tipo_cliente_cli:"",
            provincia: "",
            provincia_array: "",
            direccioncli: "",
            telefonocliente: "",
            emailcliente: "",
            contacto: "",
            comentario: "",
            descuento: "",
            radios1: "",
            num_pago: "",
            limite_credito: "",
            tipo_identificacion_cliente: 0,
            grupo_tributario: "",
            grupo_tributario_array: "",
            canton: "",
            canton_array: "",
            parroquia: "",
            vendedor: "",
            estado: "",
            estado_array: "",
            cuenta_contable: "",
            codigopais: "",
            lista_precios: "1",
            forma_pago: "",
            forma_pago_array: "",
            select2: 0,
            select3: 0,
            tipo_identificacion_array: [
                { text: "Seleccione", value: 0 },
                { text: "Cédula de Identidad", value: "Cédula de Identidad" },
                { text: "Ruc", value: "Ruc" },
                { text: "Pasaporte", value: "Pasaporte" },
                { text: "Consumidor Final", value: "Consumidor Final" }
            ],


            grupo_tributario_array: [
                { text: "Seleccione", value: 0 },
                { text: "Persona Natural", value: "Persona Natural" },
                { text: "Persona Jurídica", value: "Persona Jurídica" }
            ],

            estado_array: [
                { text: "Seleccione", value: 0 },
                { text: "Activo", value: "Activo" },
                { text: "Inactivo", value: "Inactivo" }
            ],

            lista_precios_array: [
                { text: "Seleccione", value: 0 },
                { text: "1", value: 1 },
                { text: "2", value: 2 },
                { text: "3", value: 3 },
                { text: "4", value: 4 },
                { text: "5", value: 5 }
            ],

            forma_pago_array: [
                { text: "Seleccione", value: 0 },
                { text: "Efectivo", value: "Efectivo" },
                { text: "Cheque", value: "Cheque" },
                { text: "Tarjeta", value: "Tarjeta" },
                { text: "Crédito", value: "Crédito" }
            ],

            //variables paginacion de las tablas
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            //valido email
            emailinvalido:false,
            pagina: 1,
            cantidadp: 5,
            offset: 3,
            gridApi: null,
            contenido: [],
            //variables para traer una columna plan ctas
            cuentaarray3: [],
            //buscador
            buscar: "",
            criterio: "codcta",
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //variables para ventanas modales
            activePrompt3: false,

            //ERRORES
            error: 0,
            errornombre : [],
            errortipo_identificacion : [],
            errorruc_ci : [],
            errorgrupo_tributario : [],
            errorgrupo_cliente: [],
            errordireccion : [],
            errorprovincia : [],
            errorcanton : [],
            errorparroquia : [],
            erroremail : [],
            errortelefono : [],
            errorcontacto : [],
            errorestado : [],
            errorcedula: [],
            options1: [
                { text: "Cedula", value: "Cedula" },
                { text: "Ruc", value: "Ruc" },
                { text: "Pasaporte", value: "Pasaporte" }
            ],
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        totalpagado() {
            var valor = 0;
            for (var i = 0; i < this.valorpagos.length; i++) {
                if (!this.$route.params.id) {
                    if (this.valorpagos[i].cantidad_pago > 0) {
                        valor =
                            valor +
                            parseFloat(this.valorpagos[i].cantidad_pago);
                    }
                } else {
                    if (this.valorpagos[i].monto > 0) {
                        valor = valor + parseFloat(this.valorpagos[i].monto);
                    }
                }
            }

            if (this.retencioniva <= 0) {
                var retiva = 0;
            } else {
                var retiva = this.retencioniva;
            }
            if (this.retencionrenta <= 0) {
                var retrenta = 0;
            } else {
                var retrenta = this.retencionrenta;
            }

            var valorr = 0;
            for (var f = 0; f < this.valorretenciones.length; f++) {
                if (!this.$route.params.id) {
                    if (
                        this.valorretenciones[f].baserenta != null &&
                        this.valorretenciones[f].renta != null
                    ) {
                        valorr +=
                            (parseFloat(this.valorretenciones[f].baserenta) *
                                parseFloat(
                                    this.valorretenciones[f].renta
                                        .porcen_retencion
                                )) /
                            100;
                    }
                } else {
                    if (
                        this.valorretenciones[f].baserenta != null &&
                        this.valorretenciones[f].id_retencion_renta != null
                    ) {
                        valorr += parseFloat(
                            this.valorretenciones[f].cantidadrenta
                        );
                    }
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
        subtotalpr() {
            var total = 0;
            this.contenidopr.forEach(el => {
                if (el.p_descuento == 1) {
                    total += el.precio * el.cantidad - el.descuento;
                } else {
                    total +=
                        el.precio * el.cantidad -
                        (el.cantidad * el.precio * el.descuento) / 100;
                }
            });
            return total;
        },
        descuentopr() {
            var total = 0;
            this.contenidopr.forEach(el => {
                if (el.descuento > 0) {
                    if (el.p_descuento == 1) {
                        total += parseFloat(el.descuento);
                    } else {
                        total +=
                            (parseFloat(el.precio) *
                                parseFloat(el.cantidad) *
                                parseFloat(el.descuento)) /
                            100;
                    }
                }
            });
            return total;
        },
        subtotalivapr12() {
            var total = 0;
            this.contenidopr.forEach(el => {
                if (el.iva == 2) {
                    if (el.p_descuento == 1) {
                        total += el.precio * el.cantidad - el.descuento;
                    } else {
                        total +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                }
            });
            return total;
        },
        ivapr12() {
            var total = 0;
            total = this.subtotalivapr12 * 0.12;
            return total;
        },
        subtotalivapr14() {
            var total = 0;
            this.contenidopr.forEach(el => {
                if (el.iva == 5) {
                    if (el.p_descuento == 1) {
                        total += el.precio * el.cantidad - el.descuento;
                    } else {
                        total +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                }
            });
            return total;
        },
        ivapr14() {
            var total = 0;
            this.contenidopr.forEach(el => {
                if (el.iva == 5) {
                    total += (el.precio * el.cantidad - el.descuento) * 0.14;
                }
            });
            return total;
        },
        subtotalivapr0() {
            var total = 0;
            this.contenidopr.forEach(el => {
                if (el.iva == 1) {
                    if (el.p_descuento == 1) {
                        total += el.precio * el.cantidad - el.descuento;
                    } else {
                        total +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                }
            });
            return total;
        },
        subtotalivaprno() {
            var total = 0;
            this.contenidopr.forEach(el => {
                if (el.iva == 3) {
                    if (el.p_descuento == 1) {
                        total += el.precio * el.cantidad - el.descuento;
                    } else {
                        total +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                }
            });
            return total;
        },
        subtotalivaprex() {
            var total = 0;
            this.contenidopr.forEach(el => {
                if (el.iva == 4) {
                    if (el.p_descuento == 1) {
                        total += el.precio * el.cantidad - el.descuento;
                    } else {
                        total +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                }
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
        totalpr() {
            var total = 0;

            this.contenidopr.forEach(el => {
                if (el.p_descuento == 1) {
                    total += el.precio * el.cantidad - el.descuento;
                } else {
                    total +=
                        el.precio * el.cantidad -
                        (el.cantidad * el.precio * el.descuento) / 100;
                }
            });

            var propina = 0;
            if (this.propinapr > 0) {
                if (this.pp_descuento == 1) {
                    propina = this.propinapr;
                } else {
                    var valorff = total + this.ivapr12 + this.ivapr14;
                    propina = (valorff * this.propinapr) / 100;
                }
            }
            this.totalpropinaf = parseFloat(propina).toFixed(2);
            var dato =
                total + parseFloat(propina) + this.ivapr12 + this.ivapr14;

            return dato;
        },
        receptadopr() {
            var total = 0;
            if (this.monto_credito) {
                total += parseFloat(this.monto_credito);
            }
            this.valorpagos.forEach(el => {
                total += parseFloat(el.cantidad_pago);
            });
            return total;
        },
        validarrucrepresentante($event) {
            this.errorcedula = [];
            var number = this.ruc_ci;
            var dto = number.length;
            var valor;
            var acu = 0;

            for (var i = 0; i < dto; i++) {
                valor = number.substring(i, i + 1);
                if (
                valor == 0 ||
                valor == 1 ||
                valor == 2 ||
                valor == 3 ||
                valor == 4 ||
                valor == 5 ||
                valor == 6 ||
                valor == 7 ||
                valor == 8 ||
                valor == 9
                ) {
                acu = acu + 1;
                }
            }
            if (acu == dto) {
                while (number.substring(10, 13) != "001") {
                this.errorcedula.push("RUC inválido");
                this.error = 1;
                return;
                }
                while (number.substring(0, 2) > 24) {
                this.errorcedula.push("RUC inválido");
                this.error = 1;
                return;
                }
                var porcion1 = number.substring(2, 3);
            } else {
                this.errorcedula.push("RUC inválido");
                this.error = 1;
            }
            return this.error;
        },
        //validacionvalida de ruc
        validarruc($event) {
            this.error = 0;
            this.errorcedula=[];
            var numero = this.ruc_ci;
            var suma = 0;
            var residuo = 0;
            var pri = false;
            var pub = false;
            var nat = false;
            var numeroProvincias = 22;
            var modulo = 11;

            /* Verifico que el campo no contenga letras */
            var ok = 1;

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
                this.errorcedula.push("El tercer dígito ingresado es inválido");
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
                this.errorcedula.push("Ruc invalido");
                this.error = 1;
                return false;
                }
                /* El ruc de las empresas del sector publico terminan con 0001*/
                if (numero.substr(9, 4) != "0001") {
                this.errorcedula.push("Ruc invalido");
                this.error = 1;
                return false;
                }
            } else if (pri == true) {
                if (digitoVerificador != d10) {
                this.errorcedula.push("Ruc invalido");
                this.error = 1;
                return false;
                }
                if (numero.substr(10, 3) != "001") {
                this.errorcedula.push("Ruc invalido");
                this.error = 1;
                return false;
                }
            } else if (nat == true) {
                if (digitoVerificador != d10) {
                //console.log('El número de cédula de la persona natural es incorrecto.');
                this.errorcedula.push("Ruc invalido");
                this.error = 1;
                return false;
                }
                if (numero.length < 14 && numero.substr(10, 12) != "001") {
                //console.log('El ruc de la persona natural debe terminar con 001');
                this.errorcedula.push("Ruc invalido");
                this.error = 1;
                return false;
                }
            }
            return true;
        },
        validarrepresentante($event) {
            this.errorcedula = [];
            var cad = this.ruc_ci;
            var total = 0;
            var longitud = cad.length;
            var longcheck = longitud - 1;
            for (var i = 0; i < longcheck; i++) {
                if (i % 2 === 0) {
                var aux = cad.charAt(i) * 2;
                if (aux > 9) aux -= 9;
                total += aux;
                } else {
                total += parseInt(cad.charAt(i)); // parseInt o concatenará en lugar de sumar
                }
            }
            total = total % 10 ? 10 - (total % 10) : 0;

            if (cad.substring(0, 10).charAt(longitud - 1) == total) {
                this.errorcedula = [];
            } else {
                this.errorcedula.push("Cédula inválida");
                this.error = 1;
                return;
            }
        },
        validarrucfinalrepre() {
            this.error = 0;
            this.errorcedula = [];
            var ruc = this.ruc_ci.substring(10, 13);
            if (ruc == "001") {
                this.error = 0;
                this.errorcedula = [];
            } else {
                this.errorcedula.push("RUC inválido");
                this.error = 1;
            }
        },   
    },
    methods: {
        listar(page, buscar) {
            var url =
                "/api/clientes/" +
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
        traerEmpresa() {
            var url = "/api/traerEmpresa/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(res => {
                    this.contenidoempresa = res.data[0];
                })
                .catch(err => {
                    console.log(error);
                });
        },
        crear() {
            //this.$router.push("/facturacion/cliente/agregar");
            this.popupActive4=true;
        },
        abrirproductos() {
            this.popupActive2 = true;
            this.tipomodal = 2;
            this.listarp(1, this.buscarp, this.cantidadpp);
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
        handleSelected(tr) {
            this.popupActive2 = false;
            this.id_cliente = tr.id_cliente;
            this.nombre = tr.nombre;
            this.telefono = tr.telefono;
            this.email = tr.email;
            this.tipo_identificacion = tr.tipo_identificacion;
            this.ruc_ci = tr.identificacion;
            this.direccion = tr.direccion;
        },
        handleSelectedp(tr) {
            this.popupActive2 = false;
            this.contenidopr.push({
                id_producto: tr.id_producto,
                id_detalle: null,
                nombre: tr.nombre,
                codigo: tr.cod_principal,
                cantidad: null,
                precio: tr.pvp_precio1,
                descripcion: tr.descripcion,
                descuento: tr.descuento,
                iva: tr.iva,
                ice: tr.ice,
                ice: tr.ice,
                p_descuento: 1,
                errorcant_ingreso: [],
                errorcost_unit_ingreso: []
            });
            /*this.id_cliente = tr.id_cliente;
            this.nombre = tr.nombre;
            this.telefono = tr.telefono;
            this.email = tr.email;
            this.tipo_identificacion = tr.tipo_identificacion;
            this.ruc_ci = tr.identificacion;
            this.direccion = tr.direccion;*/
        },
        listarpr(pagepr, buscarpr, cantidadppr) {
            if (this.$route.params.id) {
                var idfactura = this.$route.params.id;
                let me = this;
                var url = "/api/factproductos";
                var valores = {
                    page: pagepr,
                    buscar: buscarpr,
                    idfactura: parseInt(idfactura),
                    cantidadp: cantidadppr
                };
                axios.get(url, valores).then(function(response) {
                    var respuesta = response.data;
                    me.contenidopr = respuesta.recupera.data;
                    me.paginationpr = respuesta.pagination;
                    if (cantidadppr > me.paginationpr.total) {
                        cantidadppr = me.paginationpr.total;
                    }
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
                    console.log(error);
                });
        },
        listarice() {
            let me = this;
            var url = "/api/ice";
            axios
                .get(url)
                .then(function(response) {
                    me.contenidoice = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        }, 
        listarclave() {
            if (!this.$route.params.id) {
                var url = "/api/listarclave/" + this.usuario.id;
                axios.get(url).then(res => {
                    console.log(res.data);
                    var fecha = moment(this.date).format("DDMMYYYY");
                    var rec = res.data.recupera[0];
                    var secuencial = this.zeroFill(res.data.secuencial, 9);
                    var establecimiento = this.zeroFill(rec.establecimiento, 3); 
                    var punto_emision = this.zeroFill(rec.punto_emision, 3);
                    var codigoacc = fecha+"01"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
                    var acceso = this.Modulo11(codigoacc);
                    this.claveacceso = codigoacc + acceso;
                });
                return false;
            }
        },
        listarretenciones() {
            var url = "/api/listarretenciones";
            axios.get(url).then(res => {
                this.listretenciones = res.data;
            });
        },
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return (new Array(width + (/\./.test(number) ? 2 : 1)).join("0") +number);
            }
            return number + "";
        },
        Modulo11(claveAcceso) {
            var multiplos = [2, 3, 4, 5, 6, 7];
            var i = 0;
            var cantidad = claveAcceso.length;
            var total = 0;
            while (cantidad > 0) {
                total += parseInt(claveAcceso.substring(cantidad - 1, cantidad)) * multiplos[i];
                //console.log(total + " - " + (claveAcceso.substring(cantidad - 1, cantidad) *multiplos[i]) + " - " + claveAcceso.substring(cantidad - 1, cantidad) + " - " + multiplos[i]);
                i++;
                i = i % 6;
                cantidad--;
            }
            var modulo11 = 11 - (total % 11);
            if (modulo11 == 11) {
                modulo11 = 0;
            } else if (modulo11 == 10) {
                modulo11 = 1;
            }
            return modulo11;
        },
        borrarprd(id) {
            this.contenidopr.splice(id, 1);
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
        agregarpago() {
            if (
                this.valorpagos[0].metodo_pago >= 1 &&
                this.valorpagos[0].cantidad_pago == null
            ) {
                this.valorpagos[0].cantidad_pago = this.totalpr;
            }

            if (this.valorpagos.length > 2) {
                if (
                    (this.valorpagos[this.valorpagos.length - 1].metodo_pago ||
                        this.valorpagos[this.valorpagos.length - 1]
                            .cantidad_pago) &&
                    !this.valorpagos[this.valorpagos.length].metodo_pago &&
                    !this.valorpagos[this.valorpagos.length].cantidad_pago
                ) {
                    this.valorpagos.push({
                        metodo_pago: "",
                        cantidad_pago: 0,
                        comentario_pago: "",
                        banco: "",
                        tarjeta: "",
                        cuenta: ""
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
                        banco: "",
                        tarjeta: "",
                        cuenta: ""
                    });
                }
            }
        },
        agregarretencion() {
            for (var i = 0; i < this.valorretenciones.length; i++) {
                if (this.valorretenciones[i].iva != null) {
                    this.valorretenciones[i].porcentajeiva =
                        this.valorretenciones[i].iva.porcen_retencion + "%";
                    this.valorretenciones[i].cantidadiva = (
                        (this.ivapr12 *
                            this.valorretenciones[i].iva.porcen_retencion) /
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
                    this.valorretenciones[
                        this.valorretenciones.length - 2
                    ].baserenta = 0;
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
                this.valorretenciones[
                    this.valorretenciones.length - 2
                ].iva = null;
                this.valorretenciones[
                    this.valorretenciones.length - 2
                ].cantidadiva = null;
            }

            if (
                this.valorretenciones[this.valorretenciones.length - 1].iva &&
                this.valorretenciones[this.valorretenciones.length - 1].renta &&
                this.valorretenciones[this.valorretenciones.length - 2].iva &&
                this.valorretenciones[this.valorretenciones.length - 2].renta
            ) {
                this.valorretenciones.splice(
                    this.valorretenciones[this.valorretenciones.length],
                    1
                );
            }
        },
        eliminararraypagos(id) {
            this.valorpagos.splice(id, 1);
        },
        eliminararrayretencion(id) {
            this.valorretenciones.splice(id, 1);
        },
        listproforma() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/abrirproforma";
                axios
                    .put(url, { id: this.idrecupera })
                    .then(res => {
                        let data = res.data[0];
                        this.modofact = data.modo;
                        this.date = data.fecha_emision;
                        this.datex = data.fecha_expiracion;
                        this.forma_pago = data.forma_pago;
                        this.observacion = data.observacion;
                        this.contenidopr.subtotalpr =
                            data.subtotal_sin_impuesto;
                        this.contenidopr.subtotalivapr12 = data.subtotal_12;
                        this.valor_ice = data.valor_ice;
                        this.valor_irbpnr = data.valor_irbpnr;
                        this.propinapr = data.propina;
                        this.ordencompra = data.ordcompra;
                        this.migo = data.migo;
                        if (data.clave_acceso) {
                            this.claveacceso = data.clave_acceso;
                        }
                        this.listarc();
                        this.listarpr();
                        this.listarretencion();
                        this.listarcreditos();
                        this.listarpagos();
                    })
                    .catch(err => {
                        console.log(err);
                    });
            } else {
                this.idrecupera = null;
                this.modofact = 0;
                this.valorretenciones = [
                    {
                        iva: null,
                        porcentajeiva: null,
                        cantidadiva: null,
                        renta: null,
                        baserenta: null,
                        porcentajerenta: null,
                        cantidadrenta: null
                    }
                ];
                this.valorpagos = [
                    {
                        metodo_pago: "",
                        cantidad_pago: null,
                        comentario_pago: "",
                        banco: "",
                        tarjeta: "",
                        cuenta: ""
                    }
                ];
            }
        },
        listarc() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/abrirproformac/" + this.idrecupera;
                axios
                    .get(url)
                    .then(res => {
                        //this.contenidopr = res.data;
                        this.contenido = res.data[0];
                        this.id_cliente = this.contenido.id_cliente;
                        this.nombre = this.contenido.nombre;
                        this.telefono = this.contenido.telefono;
                        this.email = this.contenido.email;
                        this.tipo_identificacion = this.contenido.tipo_identificacion;
                        this.ruc_ci = this.contenido.identificacion;
                        this.direccion = this.contenido.direccion;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            }
        },
        listarpr() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/abrirproformap/" + this.idrecupera;
                axios
                    .get(url)
                    .then(res => {
                        this.contenidopr = res.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            }
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
                            this.verretenciones = true;
                        } else {
                            this.verretenciones = false;
                            this.valorretenciones = [
                                {
                                    iva: null,
                                    renta: null
                                }
                            ];
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    });
            }
        },
        listarcreditos() {
            if (this.$route.params.id && this.modofact != 0) {
                this.vercreditos = false;
                this.idrecupera = this.$route.params.id;
                var url = "/api/abrircreditosp/" + this.idrecupera;
                axios
                    .get(url)
                    .then(res => {
                        this.periodo_credito = res.data.unidad_tiempo;
                        this.plazos_credito = res.data.cuotas_totales;
                        this.monto_credito = res.data.monto;
                        this.pago_credito =
                            res.data.cuotas_totales / res.data.monto;
                        if (res.data) {
                            this.vercreditos = true;
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    });
            }
        },
        listarpagos() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/abrirpagosp/" + this.idrecupera;
                axios
                    .get(url)
                    .then(res => {
                        if (res.data.length) {
                            this.verpagos = true;
                            this.valorpagos = res.data;
                        } else {
                            this.verpagos = false;
                            this.valorpagos = [
                                {
                                    metodo_pago: "",
                                    cantidad_pago: null,
                                    comentario_pago: "",
                                    banco: "",
                                    tarjeta: "",
                                    cuenta: ""
                                }
                            ];
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    });
            }
        },
        guardar() {
            if (this.validarfactura()) {return;}
            if (parseFloat(this.totalpagado) + parseFloat(this.monto_credito) <= 0) {
                this.$vs.notify({time: 8000,title: "No se puede Guardar la factura",text:"Debe agregar pagos a la factura",color: "danger"});
                return;
            }
            if (this.totalpr > parseFloat(this.totalpagado) + parseFloat(this.monto_credito)) {
                this.$vs.notify({time: 8000,title: "No se puede Guardar la factura",text:"El saldo total no puede ser mayor al saldo pendiente",color: "danger"});
                return;
            }
            if (this.totalpr <parseFloat(this.totalpagado) + parseFloat(this.monto_credito)) {
                this.$vs.notify({time: 8000,title: "No se puede Guardar la factura",text:"El saldo total no puede ser menor al saldo pendiente",color: "danger"});
                return;
            }
            this.$vs.notify({time: 8000,title: "Enviando Factura",text:"La factura esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",color: "primary"});
            axios.post("/api/crearfactura", {
                //factura
                ambiente: 1,
                tipo_emision: 1,
                fecha_emision: this.date,
                forma_pago: this.forma_pago,
                clave_acceso: this.claveacceso,
                observacion: this.observacion,
                id_user: this.usuario.id,
                id_punto_emision: this.usuario.id_punto_emision,
                id_empresa: this.usuario.id_empresa,
                id_establecimiento: this.usuario.id_establecimiento,
                migo: this.migo,
                ordencompra: this.ordencompra,
                //VALORES
                subtotal_sin_impuesto: this.subtotalpr,
                subtotal_12: this.subtotalivapr12,
                subtotal_0: this.subtotalivapr0,
                subtotal_no_obj_iva: this.subtotalivaprno,
                descuento: this.descuentopr,
                valor_ice: 0,
                valor_irbpnr: 0,
                iva_12: this.ivapr12,
                propina: this.propinapr,
                valor_total: this.totalpr,
                //cliente
                id_cliente: this.id_cliente,
                //productos
                productos: this.contenidopr,
                //creditos
                periodo: this.periodo_credito,
                plazos: this.plazos_credito,
                monto: this.monto_credito,
                letra: this.pago_credito,
                //retenciones
                retencion: this.valorretenciones,
                //pagos
                valorpagos: this.valorpagos,
                //revisar
                verretenciones: this.verretenciones,
                verpagos: this.verpagos,
                vercreditos: this.vercreditos,
                totalpropinaf: this.totalpropinaf,
                pp_descuento: this.pp_descuento,
                // transportista
                guia: this.guia,
                transportista: this.transportista
            }).then(resp => { 
                var urlxmlf = "/api/factura/xml_factura";
                var dataf = resp.data.factura[0];
                this.recueidfact = resp.data.factura[0].id_factura;
                axios.post(urlxmlf, dataf).then(res => {
                    var firma = res.data.recupera.pass_firma;
                    var claveacc = res.data.recupera.firma;
                    var ruta_factura ="../server/" +this.usuario.id_empresa +"/comprobantes/factura/" +this.claveacceso +".xml";
                    var ruta_certificado ="/empresas/" +this.usuario.id_empresa +"/firma/" +claveacc;
                    this.obtenerComprobanteFirmado_sri(ruta_certificado,firma,ruta_factura,this.tipofactura); 
                    if(this.guia){ 
                        this.tipofactura = "guia";
                        var urlxmlg = "/api/factura/xml_guia";
                        var datag = resp.data.guia[0];
                        axios.post(urlxmlg, datag).then(res => {
                            var ruta_factura1 ="../server/" +this.usuario.id_empresa +"/comprobantes/guia/" +this.claveacceso +".xml";
                            this.obtenerComprobanteFirmado_sri(ruta_certificado,firma,ruta_factura1,this.tipofactura); 
                        }); 
                    }
                });
            });
        },
        validarfactura() {
            this.error = 0;
            this.errorcliente = [];
            this.errorcant_ingreso = [];
            this.errorcost_unit_ingreso = [];

            this.errornombre_transporte = [];
            this.errortipo_identificacion_transporte = [];
            this.erroridentificacion_transporte = [];
            this.errorfecha_inicio_transporte = [];
            this.errorfecha_fin_transporte = [];
            this.errorplaca_transporte = [];

            if (this.guia) {
                if (!this.transportista.nombre_transporte) {
                    this.errornombre_transporte.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.transportista.tipo_identificacion_transporte) {
                    this.errortipo_identificacion_transporte.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.transportista.identificacion_transporte) {
                    this.erroridentificacion_transporte.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.transportista.fecha_inicio_transporte) {
                    this.errorfecha_inicio_transporte.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.transportista.fecha_fin_transporte) {
                    this.errorfecha_fin_transporte.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.transportista.placa_transporte) {
                    this.errorplaca_transporte.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
            }

            if (this.id_cliente == null) {
                this.errorcliente.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            for (var i = 0; i < this.contenidopr.length; i++) {
                this.contenidopr[i].errorcant_ingreso = [];
                this.contenidopr[i].errorcost_unit_ingreso = [];
                if (!this.contenidopr[i].cantidad) {
                    this.contenidopr[i].errorcant_ingreso.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.contenidopr[i].precio) {
                    this.contenidopr[i].errorcost_unit_ingreso.push(
                        "Campo obligatorio"
                    );
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
        //funciones cliente
        guardarCliente() {
            if (this.validarcliente()) {
                return;
            }
            if (this.codigoen) {
                this.codigocliente = this.codigocliente + "-1";
            }
            axios
                .post("/api/cliente/guardar", {
                codigo: this.codigocliente,
                nombre: this.nombrecliente,
                ruc_ci: this.ruc_cicliente,
                grupo_cliente: this.grupo_cliente,
                tipo_cliente: this.tipo_cliente_cli,
                provincia: this.provincia,
                canton: this.canton,
                direccion: this.direccioncli,
                telefono: this.telefonocliente,
                email: this.emailcliente,
                contacto: this.contacto,
                comentario: this.comentario,
                descuento: this.descuento,
                radios1: this.radios1,
                num_pago: this.num_pago,
                limite_credito: this.limite_credito,
                tipo_identificacion: this.tipo_identificacion_cliente,
                grupo_tributario: this.grupo_tributario,
                parroquia: this.parroquia,
                vendedor: this.vendedor,
                estado: this.estado,
                cuenta_contable: this.cuenta_contable,
                codigopais: this.codigopais,
                lista_precios: this.lista_precios,
                forma_pago: this.forma_pago,
                empresa: this.usuario.id_empresa
                })
                .then(res => {
                this.crearcliente(res.data);
                this.popupActive4=false;
                this.borrarcliente();
                //this.$router.push("/facturacion/clientes");
                })
                .catch(err => {
                console.log(err);
                });
        },
        borrarcliente() {
            this.nombrecliente = "";
            this.codigopais = "";
            this.ruc_cicliente = "";
            this.grupo_cliente = "";
            this.tipo_cliente_cli = "";
            this.provincia = "";
            this.direccioncli = "";
            this.telefonocliente = "";
            this.emailcliente = "";
            this.contacto = "";
            this.comentario = "";
            this.descuento = "";
            this.radios1 = "";
            this.num_pago = "";
            this.limite_credito = "";
            this.tipo_identificacion_cliente = "";
            this.grupo_tributario = "";
            this.canton = "";
            this.parroquia = "";
            this.vendedor = "";
            this.estado = "";
            this.cuenta_contable = "";
            this.lista_precios = "";
            this.forma_pago = "";
        },
        crearcliente(id){
            var url = "/api/traerclientefactura/"+id;
            axios
                .get(url)
                .then(res => {
                let data = res.data[0];
                //console.log(data.id_proveedor);
                this.id_cliente = data.id_cliente;
                    this.nombre = data.nombre;
                    this.telefono = data.telefono;
                    this.email = data.email;
                    this.tipo_identificacion = data.tipo_identificacion;
                    this.ruc_ci = data.identificacion;
                    this.direccion = data.direccion;
                })
                .catch(err => {
                console.log(err);
                });
        },
        cambio(c) {
            this.ruc_cicliente = "";
            this.errorcedula = "";
            this.tipopasaporte = 0;
            /**Validar cedula */
            if (c === "Cédula de Identidad") {
            }
            /**
             * VALIDAR ruc
             */
            if (c === "Ruc") {
            }

            if (c === "Pasaporte") {
                this.tipopasaporte = 1;
            }
            if (c === "Consumidor Final") {
                this.ruc_cicliente = "999999999999";
            }
        },
        validarcliente() {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            this.error = 0;
            
            this.errornombre = [];
            this.errortipo_identificacion = [];
            this.errorruc_ci = [];
            this.errorgrupo_tributario = [];
            this.errorgrupo_cliente = [];
            this.errorcedula = [],

            this.errordireccion = [];
            this.errorprovincia = [];
            this.errorcanton = [];
            this.errorparroquia = [];
            this.erroremail = [];
            this.errortelefono = [];
            this.errorcontacto = [];
            this.errorestado = [];
            
            if (!this.nombrecliente) {
                this.errornombre.push("Campo obligatorio");
                this.error = 1;
                //console.log("nombre");
            }

            if (!this.tipo_identificacion_cliente) {
                this.errortipo_identificacion.push("Campo obligatorio");
                this.error = 1;
                //console.log("tipo_ident");
            }
            if (!this.ruc_cicliente) {
                this.errorruc_ci.push("Campo obligatorio");
                this.error = 1;
            }

            if (!this.ruc_cicliente) {
                this.errorcedula.push("Campo obligatorio");
                this.error = 1;
                //console.log("ruc.ci");
            }

            

            if (!this.grupo_tributario) {
                this.errorgrupo_tributario.push("Campo obligatorio");
                this.error = 1;
                //console.log("grupo trib");
            }

            /*if (!this.grupo_cliente) {
                this.errorgrupo_cliente.push("Campo obligatorio");
                this.error = 1;
            }*/
            if (!this.direccioncli) {
                this.errordireccion.push("Campo obligatorio");
                this.error = 1;
                //console.log("direccion");
            }

            if (!this.provincia) {
                this.errorprovincia.push("Campo obligatorio");
                this.error = 1;
                //console.log("provincia");
            }

            if (!this.canton) {
                this.errorcanton.push("Campo obligatorio");
                this.error = 1;
                //console.log("canton");
            }

            if (!this.parroquia) {
                this.errorparroquia.push("Campo obligatorio");
                this.error = 1;
                //console.log("parroquia");
            }

            if (!this.emailcliente) {
                this.erroremail.push("Campo Obligatorio");
                this.error = 1;
                this.emailinvalido=true;
                //console.log("email no ingresado");
            }else{
                if(!this.validaremail(this.emailcliente)){
                this.erroremail.push("Email no valido");
                this.error = 1;
                this.emailinvalido=true;
                //console.log("email invalido");
                }else{
                this.erroremail=[];
                this.error = 0;
                this.emailinvalido=false;
                //console.log("email valido");
                }
            }

            if (!this.telefonocliente) {
                this.errortelefono.push("Campo obligatorio");
                this.error = 1;
                //console.log("telefono");
            }

            if (!this.contacto) {
                this.errorcontacto.push("Campo obligatorio");
                this.error = 1;
                //console.log("contacto");
            }

            

            if (!this.estado) {
                this.errorestado.push("Campo obligatorio");
                this.error = 1;
                //console.log("esatdo");
            }

            return this.error;
        },
        validaremail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
            },
            listarcuentas() {
            var url = "/api/listarcuentas/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.contenidocuenta = res.data;
            });
            },
            listarcodigopais() {
            let me = this;
            var url = "/api/listarcodigopais";
            axios
                .get(url)
                .then(function(response) {
                me.contenidocodigopais = response.data;
                })
                .catch(function(error) {
                console.log(error);
                });
            },
            leercodigo() {    
            if (!this.$route.params.id) {
                axios
                .get("/api/verificarcliente/" +this.usuario.id_empresa) 
                .then(res => {
                    if (res.data == "vacio") {
                    this.codigoen = 1;
                    } else {
                    this.codigoen = 0;
                    this.codigocliente = res.data;
                    }
                });
            }
        },
        validaremail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        getProvincias: function() {
            axios.get("/api/provincia").then(
                function(response) {
                this.provincias2 = response.data;
                }.bind(this)
            );
        },
        getCiudades: function() {
            //this.canton = "";
            axios
                .get("/api/ciudad", {
                params: {
                    id_provincia: this.provincia
                }
                })
                .then(
                function(response) {
                    this.ciudades2 = response.data;
                }.bind(this)
                );
        },
        getParroquias: function() {
            axios
                .get("/api/parroquia", {
                params: {
                    id_ciudad: this.canton
                }
                })
                .then(
                function(response) {
                    this.parroquias2 = response.data;
                }.bind(this)
                );
        },
        gettipocliente() {
            let me = this;
            var url = "/api/grupotipocliente";
            axios
                .get(url)
                .then(function(response) {
                me.grupo_cliente4 = response.data;
                })
                .catch(function(error) {
                console.log(error);
                });
        },
        getGrupovendedor() {
            let me = this;
            var url = "/api/grupo_vendedor/"+this.usuario.id_empresa;
            axios
                .get(url)
                .then(function(response) {
                me.grupo_cliente3 = response.data;
                })
                .catch(function(error) {
                console.log(error);
                });
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
    }, 
    mounted() {
        this.date = moment().format("YYYY-M-D");
        this.listar(1, this.buscar, this.cantidadp);
        this.listarp(1, this.buscarp, this.cantidadpp);
        this.listarpr(1, this.buscarpr, this.cantidadppr);
        this.listariva();
        this.listarice();
        this.listarclave();
        this.listproforma();
        this.listarretenciones();
        this.traerEmpresa();
        this.listarcuentas();
        this.getProvincias();
        this.getCiudades();
        this.getParroquias();
        if (this.$route.params.id) {
        var id = this.$route.params.id;
        this.listarcliente(id);
        }
        this.listarcodigopais();
        if (this.$route.params.id) {
        var id = this.$route.params.id;
        this.listarcliente(id);
        }
        this.leercodigo();
        this.gettipocliente();
        this.getGrupovendedor();
    },
    components: {
        flatPickr,
        "v-select": vSelect
    }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
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
.botonstl {
    height: 100%;
    width: 38px;
    border: 1px solid #635ace;
    background: transparent;
    color: #635ace;
    font-size: 16px;
    cursor: pointer;
}
.elejido {
    background: #635ace !important;
    color: #fff !important;
}
.flexy > .vs-divider--text {
    display: flex;
}
.slide-fade-enter-active {
    transition: all 0.5s ease;
}
.slide-fade-leave-active {
    transition: all 0.5s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for <2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
}
</style>
