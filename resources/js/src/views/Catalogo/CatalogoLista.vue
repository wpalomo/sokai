<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup="listar(1,buscar,cantidadp)"
            v-bind:placeholder="i18nbuscar"
          />
          <div class="dropdown-button-container mr-3">
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="settings" style="border-radius: 5px;"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item
                  class="text-center"
                  divider
                  @click="abrirlinea()"
                >Linea de producto</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="abrirtipo()">Tipo de producto</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="abrirmarca()">Marca</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="abrirmodelo()">Modelo</vs-dropdown-item>
                <!--<vs-dropdown-item class="text-center" divider @click="abrirbodega()">Bodega</vs-dropdown-item>-->
                <vs-dropdown-item
                  class="text-center"
                  divider
                  @click="abrirpresentacion()"
                >Presentación</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/inventario/catalogo/agregar">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" @click="importar=true">Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" @click="exportar=true">Exportar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>
      <vs-table stripe :data="contenido">
        <template slot="thead">
          <vs-th>Código</vs-th>
          <vs-th>Nombre</vs-th>
          <vs-th>Descripcion</vs-th>
          <vs-th>Marca</vs-th>
          <vs-th>Modelo</vs-th>
          <vs-th>Costo</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.cod_principal" v-for="datos in data">
            <vs-td v-if="datos.cod_principal">
              <a href="javascript:void(0)" @click="activePrompt = true">{{datos.cod_principal}}</a>
            </vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.descripcion">{{datos.descripcion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nombremarca">{{datos.nombremarca}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nombremodelo">{{datos.nombremodelo}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.costo_total">{{datos.costo_total}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <vx-tooltip text="Editar Producto" position="top" style="display: inline-flex;">
                <feather-icon
                  v-if="editarrol"
                  icon="EditIcon"
                  svgClasses="w-5 h-5 hover:text-primary stroke-current"
                  class="pointer"
                  @click.stop="editar(datos.id_producto)"
                />
              </vx-tooltip>
              <vx-tooltip text="Borrar Producto" position="top" style="display: inline-flex;">
                <feather-icon
                  v-if="eliminarrol"
                  icon="TrashIcon"
                  svgClasses="w-5 h-5 hover:text-danger stroke-current"
                  class="ml-2 pointer"
                  @click.stop="eliminarpro(datos.id_producto)"
                />
              </vx-tooltip>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vx-card>
    <!-- Modales-->
    <vs-popup :title="titulomodal" :active.sync="modal">
      <div class="vx-row">
        <!-- Modal Linea de Prodycto-->
        <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==1">
          <vx-card>
            <div class="vx-row">
              <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                <div class="flex flex-wrap justify-between items-center mb-3">
                  <!-- ITEMS PER PAGE -->
                  <!--
                  <a
                    class="flex items-center cursor-pointer mb-4"
                    @click="importar1=true"
                  >+ Importar</a>
                  -->
                  <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>

                  <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                  >
                    <vs-input
                      class="mb-4 md:mb-0 mr-4"
                      v-model="buscar1"
                      @keyup="listarlinea(1,buscar1,cantidadp1)"
                      v-bind:placeholder="i18nbuscar"
                    />

                    <div class="dropdown-button-container">
                      <!--
                      <vs-button
                        class="btnx"
                        type="filled"
                        @click="agregar('lineas','guardar')"
                      >Agregar Nuevo</vs-button>
                      -->

                      <div class="dropdown-button-container">
                        <vs-button
                          class="btnx"
                          type="filled"
                          @click="agregar('lineas','guardar')"
                        >Agregar Nuevo</vs-button>

                        <vs-dropdown>
                          <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                          <vs-dropdown-menu style="width:13em;">
                            <vs-dropdown-item
                              class="text-center"
                              @click="importar1=true"
                            >Importar registros</vs-dropdown-item>
                            <vs-dropdown-item
                              class="text-center"
                              @click="exportar=true"
                            >Exportar registros</vs-dropdown-item>
                            <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                            <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
                          </vs-dropdown-menu>
                        </vs-dropdown>
                      </div>
                    </div>
                  </div>
                </div>
                <vs-table stripe :data="contenidolinea">
                  <template slot="thead">
                    <vs-th>Código</vs-th>
                    <vs-th>Línea Producto</vs-th>
                    <vs-th>Cuenta Compras IVA</vs-th>
                    <vs-th>Cuenta Compras IVA O</vs-th>
                    <vs-th>Cuenta Ventas IVA</vs-th>
                    <vs-th>Cuenta Ventas IVA 0</vs-th>
                    <vs-th>Cuenta Costo</vs-th>
                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.id_producto" v-for="datos in data">
                      <vs-td v-if="datos.codigo">{{datos.codigo}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.cta_compras_iva">{{datos.cta_compras_iva}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.cta_compras_iva_0">{{datos.cta_compras_iva_0}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.cta_ventas_iva">{{datos.cta_ventas_iva}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.cta_ventas_iva_0">{{datos.cta_ventas_iva_0}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.cuenta_costo">{{datos.cuenta_costo}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td class="whitespace-no-wrap">
                        <feather-icon
                          icon="EditIcon"
                          class="cursor-pointer"
                          svgClasses="w-5 h-5 hover:text-primary stroke-current"
                          @click.stop="agregar('lineas','editar',datos)"
                        />
                        <feather-icon
                          icon="TrashIcon"
                          svgClasses="w-5 h-5 hover:text-danger stroke-current"
                          class="ml-2 cursor-pointer"
                          @click.stop="ideliminar=datos.id_linea_producto;eliminar=true;tipoaccionmodal=1"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>
          </vx-card>
        </div>
        <!-- Modal Tipo de Producto-->
        <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==2">
          <vx-card>
            <div class="vx-row">
              <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                <div class="flex flex-wrap justify-between items-center mb-3">
                  <!-- ITEMS PER PAGE -->
                  <!--
                  <a
                    class="flex items-center cursor-pointer mb-4"
                    @click="importar2=true"
                  >+ Importar</a>
                  -->
                  <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                  <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                  >
                    <vs-input
                      class="mb-4 md:mb-0 mr-4"
                      v-model="buscar2"
                      @keyup="listartipo(1,buscar2)"
                      v-bind:placeholder="i18nbuscar"
                    />
                    <div>
                      <!--
                      <vs-button
                        class="btnx"
                        type="filled"
                        divider
                        @click="agregar('tipos','guardar')"
                      >Agregar Nuevo</vs-button>
                      -->

                      <div class="dropdown-button-container">
                        <vs-button
                          class="btnx"
                          type="filled"
                          @click="agregar('tipos','guardar')"
                        >Agregar Nuevo</vs-button>

                        <vs-dropdown>
                          <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                          <vs-dropdown-menu style="width:13em;">
                            <vs-dropdown-item
                              class="text-center"
                              @click="importar2=true"
                            >Importar registros</vs-dropdown-item>
                            <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
                            <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                            <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
                          </vs-dropdown-menu>
                        </vs-dropdown>
                      </div>
                    </div>
                  </div>
                </div>
                <vs-table stripe :data="contenidotipo">
                  <template slot="thead">
                    <vs-th>Código</vs-th>
                    <vs-th>Tipo Producto</vs-th>
                    <vs-th>Utilidad</vs-th>
                    <vs-th>Línea de Productos</vs-th>
                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.codigo" v-for="datos in data">
                      <vs-td v-if="datos.codigo">{{datos.codigo}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.utilidad">{{datos.utilidad}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.nombrelinea">{{datos.nombrelinea}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td class="whitespace-no-wrap">
                        <feather-icon
                          icon="EditIcon"
                          class="cursor-pointer"
                          svgClasses="w-5 h-5 hover:text-primary stroke-current"
                          @click.stop="agregar('tipos','editar',datos)"
                        />
                        <feather-icon
                          icon="TrashIcon"
                          svgClasses="w-5 h-5 hover:text-danger stroke-current"
                          class="ml-2 cursor-pointer"
                          @click.stop="ideliminar=datos.id_tipo_producto,eliminar=true,tipoaccionmodal=2"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>
          </vx-card>
        </div>
        <!-- Modal Marca de Producto-->
        <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==3">
          <vx-card>
            <div class="vx-row">
              <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                <div class="flex flex-wrap justify-between items-center mb-3">
                  <!-- ITEMS PER PAGE -->
                  <!--
                  <a
                    class="flex items-center cursor-pointer mb-4"
                    @click="importar3=true"
                  >+ Importar</a>
                  -->
                  <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                  <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                  >
                    <vs-input
                      class="mb-4 md:mb-0 mr-4"
                      v-model="buscar3"
                      @keyup="listarmarca(1,buscar3,cantidadp3)"
                      v-bind:placeholder="i18nbuscar"
                    />
                    <div>
                      <!--
                      <vs-button
                        class="btnx"
                        type="filled"
                        divider
                        @click="agregar('marcas','guardar')"
                      >Agregar Nuevo</vs-button>
                      -->

                      <div class="dropdown-button-container">
                        <vs-button
                          class="btnx"
                          type="filled"
                          @click="agregar('marcas','guardar')"
                        >Agregar Nuevo</vs-button>

                        <vs-dropdown>
                          <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                          <vs-dropdown-menu style="width:13em;">
                            <vs-dropdown-item
                              class="text-center"
                              @click="importar3=true"
                            >Importar registros</vs-dropdown-item>
                            <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
                            <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                            <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
                          </vs-dropdown-menu>
                        </vs-dropdown>
                      </div>
                    </div>
                  </div>
                </div>
                <vs-table stripe :data="contenidomarca">
                  <template slot="thead">
                    <vs-th>nombre</vs-th>
                    <vs-th>Descripción</vs-th>
                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.codigo" v-for="datos in data">
                      <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.descripcion">{{datos.descripcion}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td class="whitespace-no-wrap">
                        <feather-icon
                          icon="EditIcon"
                          svgClasses="w-5 h-5 hover:text-primary stroke-current"
                          class="cursor-pointer"
                          @click.stop="agregar('marcas','editar',datos)"
                        />
                        <feather-icon
                          icon="TrashIcon"
                          svgClasses="w-5 h-5 hover:text-danger stroke-current"
                          class="ml-2 cursor-pointer"
                          @click.stop="ideliminar=datos.id_marca,eliminar=true,tipoaccionmodal=3"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>
          </vx-card>
        </div>
        <!-- Modal Modelo de Producto-->
        <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==4">
          <vx-card>
            <div class="vx-row">
              <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                <div class="flex flex-wrap justify-between items-center mb-3">
                  <!--
                   <a
                    class="flex items-center cursor-pointer mb-4"
                    @click="importar4=true"
                  >+ Importar</a>
                  -->
                  <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                  <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                  >
                    <vs-input
                      class="mb-4 md:mb-0 mr-4"
                      v-model="buscar4"
                      @keyup="listarmodelo(1,buscar4,cantidadp4)"
                      v-bind:placeholder="i18nbuscar"
                    />
                    <div>
                      <!--
                      <vs-button
                        class="btnx"
                        type="filled"
                        divider
                        @click="agregar('modelos','guardar')"
                      >Agregar Nuevo</vs-button>
                      -->

                      <div class="dropdown-button-container">
                        <vs-button
                          class="btnx"
                          type="filled"
                          @click="agregar('modelos','guardar')"
                        >Agregar Nuevo</vs-button>

                        <vs-dropdown>
                          <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                          <vs-dropdown-menu style="width:13em;">
                            <vs-dropdown-item
                              class="text-center"
                              @click="importar4=true"
                            >Importar registros</vs-dropdown-item>
                            <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
                            <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                            <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
                          </vs-dropdown-menu>
                        </vs-dropdown>
                      </div>
                    </div>
                  </div>
                </div>
                <vs-table stripe :data="contenidomodelo">
                  <template slot="thead">
                    <vs-th>nombre</vs-th>
                    <vs-th>Descripción</vs-th>
                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.codigo" v-for="datos in data">
                      <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.descripcion">{{datos.descripcion}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td class="whitespace-no-wrap">
                        <feather-icon
                          icon="EditIcon"
                          svgClasses="w-5 h-5 hover:text-primary stroke-current"
                          class="cursor-pointer"
                          @click.stop="agregar('modelos','editar',datos)"
                        />
                        <feather-icon
                          icon="TrashIcon"
                          svgClasses="w-5 h-5 hover:text-danger stroke-current"
                          class="ml-2 cursor-pointer"
                          @click.stop="ideliminar=datos.id_modelo,eliminar=true,tipoaccionmodal=4"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>
          </vx-card>
        </div>
        <!-- Modal bodega de Producto-->
        <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==5">
          <vx-card>
            <div class="vx-row">
              <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                <div class="flex flex-wrap justify-between items-center mb-3">
                  <!-- ITEMS PER PAGE -->
                  <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                  <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                  >
                    <vs-input
                      class="mb-4 md:mb-0 mr-4"
                      v-model="buscar5"
                      @keyup="listarbodega(1,buscar5,cantidadp5)"
                      v-bind:placeholder="i18nbuscar"
                    />
                    <div>
                      <vs-button
                        class="btnx"
                        type="filled"
                        divider
                        @click="agregar('bodegas','guardar')"
                      >Agregar Nuevo</vs-button>
                    </div>
                  </div>
                </div>
                <vs-table stripe :data="contenidobodega">
                  <template slot="thead">
                    <vs-th>Código</vs-th>
                    <vs-th>Nombre</vs-th>
                    <vs-th>Serie</vs-th>
                    <vs-th>Ubicación</vs-th>
                    <vs-th>Dirección</vs-th>
                    <vs-th>Teléfono</vs-th>
                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.codigo" v-for="datos in data">
                      <vs-td v-if="datos.codigo">{{datos.codigo}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.serie_factura">{{datos.serie_factura}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.ubicacion">{{datos.ubicacion}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.direccion">{{datos.direccion}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.telefono">{{datos.telefono}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td class="whitespace-no-wrap">
                        <feather-icon
                          icon="EditIcon"
                          svgClasses="w-5 h-5 hover:text-primary stroke-current"
                          class="cursor-pointer"
                          @click.stop="agregar('bodegas','editar',datos)"
                        />
                        <feather-icon
                          icon="TrashIcon"
                          svgClasses="w-5 h-5 hover:text-danger stroke-current"
                          class="ml-2 cursor-pointer"
                          @click.stop="ideliminar=datos.id_bodega,eliminar=true,tipoaccionmodal=5"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>
          </vx-card>
        </div>
        <!-- Modal Presentacion de Producto-->
        <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==6">
          <vx-card>
            <div class="vx-row">
              <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                <div class="flex flex-wrap justify-between items-center mb-3">
                  <!-- ITEMS PER PAGE -->
                  <!--
                   <a
                    class="flex items-center cursor-pointer mb-4"
                    @click="importar5=true"
                  >+ Importar</a>
                  -->
                  <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                  <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                  >
                    <vs-input
                      class="mb-4 md:mb-0 mr-4"
                      v-model="buscar6"
                      @keyup="listarpresentacion(1,buscar6,cantidadp6)"
                      v-bind:placeholder="i18nbuscar"
                    />
                    <div>
                      <!--
                      <vs-button
                        class="btnx"
                        type="filled"
                        divider
                        @click="agregar('presentaciones','guardar')"
                      >Agregar Nuevo</vs-button>
                      -->

                      <div class="dropdown-button-container">
                        <vs-button
                          class="btnx"
                          type="filled"
                          @click="agregar('presentaciones','guardar')"
                        >Agregar Nuevo</vs-button>

                        <vs-dropdown>
                          <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                          <vs-dropdown-menu style="width:13em;">
                            <vs-dropdown-item
                              class="text-center"
                              @click="importar5=true"
                            >Importar registros</vs-dropdown-item>
                            <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
                            <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                            <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
                          </vs-dropdown-menu>
                        </vs-dropdown>
                      </div>
                    </div>
                  </div>
                </div>
                <vs-table stripe :data="contenidopresentacion">
                  <template slot="thead">
                    <vs-th>nombre</vs-th>
                    <vs-th>Descripción</vs-th>
                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.codigo" v-for="datos in data">
                      <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.descripcion">{{datos.descripcion}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td class="whitespace-no-wrap">
                        <feather-icon
                          icon="EditIcon"
                          svgClasses="w-5 h-5 hover:text-primary stroke-current"
                          class="cursor-pointer"
                          @click.stop="agregar('presentaciones','editar',datos)"
                        />
                        <feather-icon
                          icon="TrashIcon"
                          svgClasses="w-5 h-5 hover:text-danger stroke-current"
                          class="ml-2 cursor-pointer"
                          @click.stop="ideliminar=datos.id_presentacion,eliminar=true,tipoaccionmodal=6"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>
          </vx-card>
        </div>
      </div>
      <!-- Opciones para agregar-->
      <!-- Modal Agregar Linea de Producto-->
      <vs-popup :title="titulo" :active.sync="agregarlinea">
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col sm:w-full w-full mb-6">
              <vs-input class="w-full" label="Nombre:" v-model="nombre" />
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full" label="Código:" v-model="codigo" />
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full" label="Cta. Compras IVA:" v-model="cta_civa" />
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full" label="Cta. Compras IVA 0:" v-model="cta_civa0" />
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full" label="Cta. Ventas IVA:" v-model="cta_viva" />
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full" label="Cta. Ventas IVA 0:" v-model="cta_viva0" />
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full" label="Cuenta Costo:" v-model="cta_costo" />
            </div>
            <div class="vx-col sm:w-full w-full">
              <vs-button
                color="success"
                type="filled"
                v-if="tipoaccionmodal==1"
                @click="guardarlinea()"
              >Guardar</vs-button>
              <vs-button color="success" type="filled" v-else @click="editarlinea()">Editar</vs-button>
              <vs-button color="danger" type="filled" @click="agregarlinea=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vs-popup>
      <!-- Modal Agregar Tipo de Producto-->
      <vs-popup :title="titulo" :active.sync="agregartipo">
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-select
                placeholder="Buscar Línea de Producto"
                label="Seleccione Línea de Producto:"
                vs-multiple
                autocomplete
                class="selectExample w-full"
                v-model="seleclinea"
              >
                <vs-select-item
                  :key="item.id_linea_producto"
                  :value="item.id_linea_producto"
                  :text="item.nombre"
                  v-for="item in optionlinea"
                />
              </vs-select>
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full" label="Código:" v-model="codtipo" />
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full" label="Nombre:" v-model="nombretipo" />
            </div>
            <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full" label="Utilidad:" v-model="utilidadtipo" />
            </div>
            <div class="vx-col sm:w-full w-full">
              <vs-button
                color="success"
                type="filled"
                v-if="tipoaccionmodal==1"
                @click="guardartipo()"
              >Guardar</vs-button>
              <vs-button color="success" type="filled" v-else @click="editartipo()">Editar</vs-button>
              <vs-button color="danger" type="filled" @click="agregartipo=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vs-popup>
      <!-- Modal Agregar Marca-->
      <vs-popup :title="titulo" :active.sync="agregarmarca">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <vs-input class="w-full" label="Nombre" v-model="nombremarca" />
              </div>
              <div class="vx-col sm:w-full w-full mb-6 vs-card">
                <label class="vs-input--label">Descripción</label>
                <vs-textarea v-model="descripcionmarca" rows="3" />
              </div>
              <div class="vx-col sm:w-full w-full">
                <vs-button
                  color="success"
                  type="filled"
                  v-if="tipoaccionmodal==1"
                  @click="guardarmarca()"
                >Guardar</vs-button>
                <vs-button color="success" type="filled" v-else @click="editarmarca()">Editar</vs-button>
                <vs-button color="danger" type="filled" @click="agregarmarca=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <!-- Modal Agregar Modelo-->
      <vs-popup :title="titulo" :active.sync="agregarmodelo">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <vs-input class="w-full" label="Nombre" v-model="nombremodelo" />
              </div>
              <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Descripción</label>
                <vs-textarea v-model="descripcionmodelo" rows="3" />
              </div>
              <div class="vx-col sm:w-full w-full">
                <vs-button
                  color="success"
                  type="filled"
                  v-if="tipoaccionmodal==1"
                  @click="guardarmodelo()"
                >Guardar</vs-button>
                <vs-button color="success" type="filled" v-else @click="editarmodelo()">Editar</vs-button>
                <vs-button color="danger" type="filled" @click="agregarmodelo=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <!-- Modal Agregar Bodega-->
      <vs-popup :title="titulo" :active.sync="agregarbodega">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-1/3 w-full mb-6">
                <vs-input class="w-full" label="Código" v-model="codigobodega" />
              </div>
              <div class="vx-col sm:w-2/3 w-full mb-6">
                <vs-input class="w-full" label="Nombre" v-model="nombrebodega" />
              </div>
              <div class="vx-col sm:w-1/2 w-full mb-6">
                <vs-input class="w-full" label="Serie" v-model="seriebodega" />
              </div>
              <div class="vx-col sm:w-1/2 w-full mb-6">
                <vs-input class="w-full" label="Teléfono" v-model="telefonobodega" />
              </div>
              <div class="vx-col sm:w-full w-full mb-6">
                <vs-input class="w-full" label="Ubicación" v-model="ubicacionbodega" />
              </div>
              <div class="vx-col sm:w-full w-full mb-6">
                <vs-input class="w-full" label="Dirección" v-model="direccionbodega" />
              </div>
              <div class="vx-col sm:w-full w-full">
                <vs-button
                  color="success"
                  type="filled"
                  v-if="tipoaccionmodal==1"
                  @click="guardarbodega()"
                >Guardar</vs-button>
                <vs-button color="success" type="filled" v-else @click="editarbodega()">Editar</vs-button>
                <vs-button color="danger" type="filled" @click="agregarbodega=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <!-- Modal Agregar Presentación-->
      <vs-popup :title="titulo" :active.sync="agregarpresentacion">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <vs-input class="w-full" label="Nombre" v-model="nombrepresentacion" />
              </div>
              <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Descripción</label>
                <vs-textarea v-model="descripcionpresentacion" rows="3" />
              </div>
              <div class="vx-col sm:w-full w-full">
                <vs-button
                  color="success"
                  type="filled"
                  v-if="tipoaccionmodal==1"
                  @click="guardarpresentacion()"
                >Guardar</vs-button>
                <vs-button color="success" type="filled" v-else @click="editarpresentacion()">Editar</vs-button>
                <vs-button color="danger" type="filled" @click="agregarpresentacion=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <vs-popup title="eliminar registro" :class="'peque'" :active.sync="eliminar">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6 text-center">
                <label class="text-center">
                  Esta seguro que desea eliminar este registro
                  <br />¡Se eliminará de forma permanente!
                </label>
                <div>
                  <label
                    class="text-center"
                    v-if="tipoaccionmodal==1"
                  >Si esta linea contiene tipos de productos tambien se borrarán del sistema!!!</label>
                </div>
              </div>
              <div class="vx-col sm:w-full w-full text-center">
                <vs-button
                  color="danger"
                  type="filled"
                  v-if="tipoaccionmodal==1"
                  @click="eliminarlinea(ideliminar)"
                >Eliminar linea</vs-button>
                <vs-button
                  color="danger"
                  type="filled"
                  v-else-if="tipoaccionmodal==2"
                  @click="eliminartipo(ideliminar)"
                >Eliminar tipo</vs-button>
                <vs-button
                  color="danger"
                  type="filled"
                  v-else-if="tipoaccionmodal==3"
                  @click="eliminarmarca(ideliminar)"
                >Eliminar marca</vs-button>
                <vs-button
                  color="danger"
                  type="filled"
                  v-else-if="tipoaccionmodal==4"
                  @click="eliminarmodelo(ideliminar)"
                >Eliminar modelo</vs-button>
                <vs-button
                  color="danger"
                  type="filled"
                  v-else-if="tipoaccionmodal==5"
                  @click="eliminarbodega(ideliminar)"
                >Eliminar bodega</vs-button>
                <vs-button
                  color="danger"
                  type="filled"
                  v-else
                  @click="eliminarpresentacion(ideliminar)"
                >Eliminar presentacion</vs-button>
                <vs-button color="primary" type="filled" @click="agregarpresentacion=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <!--Modal para importar linea de productos excel-->

      <vs-popup title="Importar Excel" :active.sync="importar1">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Subir Archivo</label>
                <div class="vx-col md:w-full w-full mb-6">
                  <div style="display:none">
                    <input
                      :onSuccess="loadDataInTable"
                      type="file"
                      class="custom-file-input inputexcel1"
                      @change="obtenerimagen"
                      accept=".XLSX, .CSV"
                    />
                  </div>
                  <div class="centimg vx-card input" @click="importarexcel1()">
                    <img src="/images/upload.png" />
                    <div
                      style="position:absolute;margin-top:60px;color:#000"
                    >Click para subir Archivo</div>
                  </div>
                </div>
              </div>
              <vx-card v-if="tableData.length && header.length">
                <vs-table stripe pagination :max-items="10" search :data="tableData">
                  <template slot="header">
                    <h4>{{ sheetName }}</h4>
                  </template>

                  <template slot="thead">
                    <vs-th
                      :sort-key="heading"
                      v-for="heading in header"
                      :key="heading"
                    >{{ heading }}</vs-th>
                  </template>

                  <template slot-scope="{data}" @change="obtenerimagen">
                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                      <vs-td :data="col" v-for="col in data[indextr]" :key="col">{{ col }}</vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </vx-card>

              <div class="vx-col sm:w-full w-full mb-6">
                <vs-button color="success" @click="importardatosLineasProducto()">Subir Archivo</vs-button>
                <vs-button color="danger" type="filled" @click="importar1=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <!--fin modal importar linea de productos de importar-->

      <!--Modal para importar tipo de productos excel-->

      <vs-popup title="Importar Excel" :active.sync="importar2">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Subir Archivo</label>
                <div class="vx-col md:w-full w-full mb-6">
                  <div style="display:none">
                    <input
                      :onSuccess="loadDataInTable"
                      type="file"
                      class="custom-file-input inputexcel2"
                      @change="obtenerimagen"
                      accept=".XLSX, .CSV"
                    />
                  </div>
                  <div class="centimg vx-card input" @click="importarexcel2()">
                    <img src="/images/upload.png" />
                    <div
                      style="position:absolute;margin-top:60px;color:#000"
                    >Click para subir Archivo</div>
                  </div>
                </div>
              </div>
              <vx-card v-if="tableData.length && header.length">
                <vs-table stripe pagination :max-items="10" search :data="tableData">
                  <template slot="header">
                    <h4>{{ sheetName }}</h4>
                  </template>

                  <template slot="thead">
                    <vs-th
                      :sort-key="heading"
                      v-for="heading in header"
                      :key="heading"
                    >{{ heading }}</vs-th>
                  </template>

                  <template slot-scope="{data}" @change="obtenerimagen">
                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                      <vs-td :data="col" v-for="col in data[indextr]" :key="col">{{ col }}</vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </vx-card>

              <div class="vx-col sm:w-full w-full mb-6">
                <vs-button color="success" @click="importardatosTipoProducto()">Subir Archivo</vs-button>
                <vs-button color="danger" type="filled" @click="importar2=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <!--fin modal -->

      <!--Modal para importar marca de productos excel-->

      <vs-popup title="Importar Excel" :active.sync="importar3">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Subir Archivo</label>
                <div class="vx-col md:w-full w-full mb-6">
                  <div style="display:none">
                    <input
                      :onSuccess="loadDataInTable"
                      type="file"
                      class="custom-file-input inputexcel3"
                      @change="obtenerimagen"
                      accept=".XLSX, .CSV"
                    />
                  </div>
                  <div class="centimg vx-card input" @click="importarexcel3()">
                    <img src="/images/upload.png" />
                    <div
                      style="position:absolute;margin-top:60px;color:#000"
                    >Click para subir Archivo</div>
                  </div>
                </div>
              </div>
              <vx-card v-if="tableData.length && header.length">
                <vs-table stripe pagination :max-items="10" search :data="tableData">
                  <template slot="header">
                    <h4>{{ sheetName }}</h4>
                  </template>

                  <template slot="thead">
                    <vs-th
                      :sort-key="heading"
                      v-for="heading in header"
                      :key="heading"
                    >{{ heading }}</vs-th>
                  </template>

                  <template slot-scope="{data}" @change="obtenerimagen">
                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                      <vs-td :data="col" v-for="col in data[indextr]" :key="col">{{ col }}</vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </vx-card>

              <div class="vx-col sm:w-full w-full mb-6">
                <vs-button color="success" @click="importardatosMarcaProducto()">Subir Archivo</vs-button>
                <vs-button color="danger" type="filled" @click="importar3=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <!--fin modal -->

      <!--Modal para importar modelos de productos excel-->

      <vs-popup title="Importar Excel" :active.sync="importar4">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Subir Archivo</label>
                <div class="vx-col md:w-full w-full mb-6">
                  <div style="display:none">
                    <input
                      :onSuccess="loadDataInTable"
                      type="file"
                      class="custom-file-input inputexcel4"
                      @change="obtenerimagen"
                      accept=".XLSX, .CSV"
                    />
                  </div>
                  <div class="centimg vx-card input" @click="importarexcel4()">
                    <img src="/images/upload.png" />
                    <div
                      style="position:absolute;margin-top:60px;color:#000"
                    >Click para subir Archivo</div>
                  </div>
                </div>
              </div>
              <vx-card v-if="tableData.length && header.length">
                <vs-table stripe pagination :max-items="10" search :data="tableData">
                  <template slot="header">
                    <h4>{{ sheetName }}</h4>
                  </template>

                  <template slot="thead">
                    <vs-th
                      :sort-key="heading"
                      v-for="heading in header"
                      :key="heading"
                    >{{ heading }}</vs-th>
                  </template>

                  <template slot-scope="{data}" @change="obtenerimagen">
                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                      <vs-td :data="col" v-for="col in data[indextr]" :key="col">{{ col }}</vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </vx-card>

              <div class="vx-col sm:w-full w-full mb-6">
                <vs-button color="success" @click="importardatosModelosProducto()">Subir Archivo</vs-button>
                <vs-button color="danger" type="filled" @click="importar4=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <!--fin modal -->

      <!--Modal para importar presentacion de productos excel-->

      <vs-popup title="Importar Excel" :active.sync="importar5">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Subir Archivo</label>
                <div class="vx-col md:w-full w-full mb-6">
                  <div style="display:none">
                    <input
                      :onSuccess="loadDataInTable"
                      type="file"
                      class="custom-file-input inputexcel5"
                      @change="obtenerimagen"
                      accept=".XLSX, .CSV"
                    />
                  </div>
                  <div class="centimg vx-card input" @click="importarexcel5()">
                    <img src="/images/upload.png" />
                    <div
                      style="position:absolute;margin-top:60px;color:#000"
                    >Click para subir Archivo</div>
                  </div>
                </div>
              </div>
              <vx-card v-if="tableData.length && header.length">
                <vs-table stripe pagination :max-items="10" search :data="tableData">
                  <template slot="header">
                    <h4>{{ sheetName }}</h4>
                  </template>

                  <template slot="thead">
                    <vs-th
                      :sort-key="heading"
                      v-for="heading in header"
                      :key="heading"
                    >{{ heading }}</vs-th>
                  </template>

                  <template slot-scope="{data}" @change="obtenerimagen">
                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                      <vs-td :data="col" v-for="col in data[indextr]" :key="col">{{ col }}</vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </vx-card>

              <div class="vx-col sm:w-full w-full mb-6">
                <vs-button
                  color="success"
                  @click="importardatosPresentacionProducto()"
                >Subir Archivo</vs-button>
                <vs-button color="danger" type="filled" @click="importar5=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
      <!--fin modal -->
    </vs-popup>
    <!--Modal para exportar excel-->
    <vs-popup title="Exportar Excel" :active.sync="exportar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col sm:w-full w-full mt-5">
              <vs-input
                v-model="nombreexportar"
                placeholder="Nombre del archivo..."
                class="w-full"
              />
              <vs-select v-model="tipoformatoexportar" :options="formatoexportar" class="my-4" />
              <div class="flex mb-4">
                <span class="mr-4">Celda con ancho predefinido:</span>
                <vs-switch v-model="cellancho">Ancho de los campos del archivo</vs-switch>
              </div>
              <!--<div class="vx-row">
                <div class="vx-col sm:w-1/6 w-full mb-2" v-for="(c,index) in campos" :key="index">
                    <vs-switch vs-icon-on="check" class="w-full" color="success" v-model="indexs" :vs-value="c">
                      <span slot="off"> {{c}} </span>
                      <span slot="on"> {{c}} </span>
                    </vs-switch>
                </div>
              </div>-->
            </div>
            <div class="vx-col sm:w-full w-full mt-5">
              <vs-button color="success" type="filled" @click="exportardatos">Descargar Excel</vs-button>
              <vs-button color="danger" type="filled" @click="exportar=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
    <!--Modal para importar excel-->
    <vs-popup title="Importar Excel" :active.sync="importar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <!---->
            <div class="vx-col sm:w-full w-full mb-6">
              <label class="vs-input--label">Subir Archivo</label>
              <div class="vx-col md:w-full w-full mb-6">
                <div style="display:none">
                  <input
                    type="file"
                    class="custom-file-input inputexcel"
                    @change="obtenerimagen"
                    accept=".XLSX, .CSV"
                  />
                </div>
                <div class="centimg vx-card input" @click="importarexcel()">
                  <img src="/images/upload.png" />
                  <div style="position:absolute;margin-top:60px;color:#000">Click para subir Archivo</div>
                </div>
              </div>
            </div>
            <vx-card v-if="tableData.length && header.length">
              <vs-table stripe pagination :max-items="10" search :data="tableData">
                <template slot="header">
                  <h4>{{ sheetName }}</h4>
                </template>

                <template slot="thead">
                  <vs-th :sort-key="heading" v-for="heading in header" :key="heading">{{ heading }}</vs-th>
                </template>

                <template slot-scope="{data}" @change="obtenerimagen">
                  <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td :data="col" v-for="col in data[indextr]" :key="col">{{ col }}</vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </vx-card>

            <div class="vx-col sm:w-full w-full mb-6">
              <vs-button color="success" @click="importardatos()">Subir Archivo</vs-button>
              <vs-button color="danger" type="filled" @click="importar=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
    <!--fin modal de importar-->
  </div>
</template>
<script>
import ImportExcel from "@/components/excel/ImportExcel.vue";
import { AgGridVue } from "ag-grid-vue";
import $ from "jquery";
import vSelect from "vue-select";

const axios = require("axios");
export default {
  components: {
    AgGridVue,
    vSelect
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
        res = this.$store.state.Roles[17].crear;
      }
      return res;
    },
    editarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[17].editar;
      }
      return res;
    },
    eliminarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[17].eliminar;
      }
      return res;
    }
  },
  data() {
    return {
      //Datos para la importaciond de archivos
      importar: false,
      importar1: false,
      importar2: false,
      importar3: false,
      importar4: false,
      importar5: false,

      //excel import
      file: "",
      tableData: [],
      header: [],
      sheetName: "",
      //variables modal
      titulomodal: "",
      modal: false,
      tipoaccion: 0,
      tipoaccionmodal: 0,
      titulo: "",
      id: null,
      agregarlinea: false,
      agregartipo: false,
      agregarmarca: false,
      agregarmodelo: false,
      agregarbodega: false,
      agregarpresentacion: false,

      eliminar: false,
      ideliminar: 0,
      tipoeliminar: null,
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
      pagination1: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      pagination2: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      pagination3: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      pagination4: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      pagination5: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      pagination6: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      pagina: 1,
      pagina1: 1,
      pagina2: 1,
      pagina3: 1,
      pagina4: 1,
      pagina5: 1,
      pagina6: 1,
      cantidadp: 100000,
      cantidadp1: 100000,
      cantidadp2: 100000,
      cantidadp3: 100000,
      cantidadp4: 100000,
      cantidadp5: 100000,
      cantidadp6: 100000,
      offset: 3,
      offset1: 3,
      offset2: 3,
      offset3: 3,
      offset4: 3,
      offset5: 3,
      offset6: 3,
      //buscador
      buscar: "",
      buscar1: "",
      buscar2: "",
      buscar3: "",
      buscar4: "",
      buscar5: "",
      buscar6: "",
      //otros valores
      gridApi: null,
      contenido: [],
      contenidolinea: [],
      contenidotipo: [],
      contenidomarca: [],
      contenidomodelo: [],
      contenidobodega: [],
      contenidopresentacion: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //variables linea producto
      nombre: "",
      codigo: "",
      cta_civa: "",
      cta_civa0: "",
      cta_viva: "",
      cta_viva0: "",
      cta_costo: "",
      //variables tipo producto
      seleclinea: "",
      codtipo: "",
      nombretipo: "",
      utilidadtipo: "",
      //variables marca
      nombremarca: "",
      descripcionmarca: "",
      //variables modelo
      nombremodelo: "",
      descripcionmodelo: "",
      //variables bodega
      codigobodega: "",
      nombrebodega: "",
      seriebodega: "",
      telefonobodega: "",
      ubicacionbodega: "",
      direccionbodega: "",
      //variables presentación
      nombrepresentacion: "",
      descripcionpresentacion: "",

      //opciones linea de producto para tipo de producto
      optionlinea: [],
      //Datos para la Exportacion de archivos
      exportar: false,
      nombreexportar: "",
      formatoexportar: ["xlsx", "csv", "txt"],
      cellancho: true,
      tipoformatoexportar: "xlsx",
      //campos que existen para exportar

      campos: [
        "cod_principal",
        "cod_alterno",
        "imagen",
        "nombre",
        "codigo_barras",
        "cuenta_contable",
        "descripcion",
        "nombrec",
        "sector",
        "ubicacion_fisica",
        "unidad_entrada",
        "unidad_salida",
        "vencimiento",
        "existencia_maxima",
        "existencia_minima",
        "numero_unidad",
        "estado",
        "vehiculo",
        "placa",
        "pais_origen",
        "ano_fabricacionv",
        "color",
        "carroceria",
        "combustible",
        "motor",
        "cilindraje",
        "chasis",
        "clase",
        "subclase",
        "numero_pasajeros",
        "iva",
        "ice",
        "arancel",
        "comision",
        "salvaguardia",
        "pvp_precio1",
        "precio2",
        "precio3",
        "precio4",
        "precio5",
        "descuento",
        "utilidad",
        "fecha_fabricacion",
        "ultimo_costo",
        "costo_promedio",
        "costo_total",
        "existencia_total",
        "fcrea",
        "fmodifica",
        "nombremarca",
        "nombremodelo"
      ],
      //campos elegidos a exportar
      indexs: [
        "cod_principal",
        "cod_alterno",
        "imagen",
        "nombre",
        "codigo_barras",
        "cuenta_contable",
        "descripcion",
        "nombrec",
        "sector",
        "ubicacion_fisica",
        "unidad_entrada",
        "unidad_salida",
        "vencimiento",
        "existencia_maxima",
        "existencia_minima",
        "numero_unidad",
        "estado",
        "vehiculo",
        "placa",
        "pais_origen",
        "ano_fabricacionv",
        "color",
        "carroceria",
        "combustible",
        "motor",
        "cilindraje",
        "chasis",
        "clase",
        "subclase",
        "numero_pasajeros",
        "iva",
        "ice",
        "arancel",
        "comision",
        "salvaguardia",
        "pvp_precio1",
        "precio2",
        "precio3",
        "precio4",
        "precio5",
        "descuento",
        "utilidad",
        "fecha_fabricacion",
        "ultimo_costo",
        "costo_promedio",
        "costo_total",
        "existencia_total",
        "fcrea",
        "fmodifica",
        "nombremarca",
        "nombremodelo"
      ],
      //cabezera que imprimira en el excel
      cabezera: [
        "cod_principal",
        "cod_alterno",
        "imagen",
        "nombre",
        "codigo_barras",
        "cuenta_contable",
        "descripcion",
        "nombrec",
        "sector",
        "ubicacion_fisica",
        "unidad_entrada",
        "unidad_salida",
        "vencimiento",
        "existencia_maxima",
        "existencia_minima",
        "numero_unidad",
        "estado",
        "vehiculo",
        "placa",
        "pais_origen",
        "ano_fabricacionv",
        "color",
        "carroceria",
        "combustible",
        "motor",
        "cilindraje",
        "chasis",
        "clase",
        "subclase",
        "numero_pasajeros",
        "iva",
        "ice",
        "arancel",
        "comision",
        "salvaguardia",
        "pvp_precio1",
        "precio2",
        "precio3",
        "precio4",
        "precio5",
        "descuento",
        "utilidad",
        "fecha_fabricacion",
        "ultimo_costo",
        "costo_promedio",
        "costo_total",
        "existencia_total",
        "fcrea",
        "fmodifica",
        "nombremarca",
        "nombremodelo"
      ]
    };
  },
  methods: {
    //importar archivos
    loadDataInTable({ results, header, meta }) {
      this.header = header;
      this.tableData = results;
      this.sheetName = meta.sheetName;
    },
    importarexcel() {
      $(".inputexcel").click();
    },
    //tabla productos

    importardatos() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file);
      axios
        .post("/api/importarproductosexcel", formData, {})
        .then(res => {
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar = false;
          this.listar(page, buscar, cantidadp);
        })
        .catch(err => {
          console.log(err);
        });
    },

    importarexcel1() {
      $(".inputexcel1").click();
    },

    importardatosLineasProducto() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file);
      axios
        .post("/api/importarlineaproductosexcel", formData, {})
        .then(res => {
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar1 = false;
          this.listarlinea(1, this.buscar1, this.cantidadp1); //listarlinea(1,buscar1,cantidadp1)"
        })
        .catch(err => {
          console.log(err);
        });
    },
    importarexcel2() {
      $(".inputexcel2").click();
    },
    importardatosTipoProducto() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file);
      axios
        .post("/api/importartipoproductosexcel", formData, {})
        .then(res => {
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar2 = false;
          this.listartipo(1, this.buscar2);
        })
        .catch(err => {
          console.log(err);
        });
    },
    importarexcel3() {
      $(".inputexcel3").click();
    },
    importardatosMarcaProducto() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file);
      axios
        .post("/api/importarmarcaproductosexcel", formData, {})
        .then(res => {
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar3 = false;
          this.listarmarca(1, this.buscar4, this.cantidadp4);
        })
        .catch(err => {
          console.log(err);
        });
    },
    importarexcel4() {
      $(".inputexcel4").click();
    },
    importardatosModelosProducto() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file);
      axios
        .post("/api/importarmodelosproductosexcel", formData, {})
        .then(res => {
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar4 = false;
          this.listarmodelo(1, this.buscar3, this.cantidadp3);
        })
        .catch(err => {
          console.log(err);
        });
    },
    importarexcel5() {
      $(".inputexcel5").click();
    },

    importardatosPresentacionProducto() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file);
      axios
        .post("/api/importarpresentacionproductosexcel", formData, {})
        .then(res => {
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar5 = false;
          this.listarpresentacion(1, this.buscar6, this.cantidadp6);
        })
        .catch(err => {
          console.log(err);
        });
    },

    obtenerimagen(e) {
      let file = e.target.files[0];
      var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
      if (!allowedExtensions.exec(file.name)) {
        this.$vs.notify({
          title: "Tipo de archivo no compatible",
          text: "Formatos aceptados: .jpg, .jpeg, .png",
          color: "danger"
        });
        return;
      }
      this.file = file;
    },
    //fin importar archivos
    //abren modales
    abrirlinea() {
      this.tipoaccion = 1;
      this.modal = true;
      this.titulomodal = "Líneas de Producto";
    },
    abrirtipo() {
      this.tipoaccion = 2;
      this.modal = true;
      this.titulomodal = "Tipos de Producto";
    },
    abrirmarca() {
      this.tipoaccion = 3;
      this.modal = true;
      this.titulomodal = "Marcas de Producto";
    },
    abrirmodelo() {
      this.tipoaccion = 4;
      this.modal = true;
      this.titulomodal = "Modelos de Producto";
    },
    abrirbodega() {
      this.tipoaccion = 5;
      this.modal = true;
      this.titulomodal = "Bodegas de Producto";
    },
    abrirpresentacion() {
      this.tipoaccion = 6;
      this.modal = true;
      this.titulomodal = "Presentaciones de Producto";
    },
    //listan tablas
    listar(page, buscar) {
      var url =
        "/api/productos/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios
        .get(url)
        .then(res => {
          var respuesta = res.data;
          this.contenido = respuesta.recupera;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarlinea(page1, buscar1) {
      var url =
        "/api/lineaproductos/" +
        this.usuario.id_empresa +
        "?page=" +
        page1 +
        "&buscar=" +
        buscar1;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidolinea = respuesta.recupera;
      });
    },
    listartipo(page2, buscar2) {
      var url =
        "/api/tipoproductos/" +
        this.usuario.id_empresa +
        "?page=" +
        page2 +
        "&buscar=" +
        buscar2;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidotipo = respuesta.recupera;
      });
    },
    listarmarca(page3, buscar3) {
      var url =
        "/api/marca/" +
        this.usuario.id_empresa +
        "?page=" +
        page3 +
        "&buscar=" +
        buscar3;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidomarca = respuesta.recupera;
      });
    },
    listarmodelo(page4, buscar4) {
      var url =
        "/api/modelo/" +
        this.usuario.id_empresa +
        "?page=" +
        page4 +
        "&buscar=" +
        buscar4;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidomodelo = respuesta.recupera;
      });
    },

    listarpresentacion(page6, buscar6) {
      var url =
        "/api/presentacion/" +
        this.usuario.id_empresa +
        "?page=" +
        page6 +
        "&buscar=" +
        buscar6;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidopresentacion = respuesta.recupera;
      });
    },
    eliminarlinea(id) {
      axios.delete("/api/eliminarlinea/" + id);
      this.$vs.notify({
        title: "Registro eliminado",
        text: "Este registro ha sido eliminado exitosamente",
        color: "success"
      });
      this.eliminar = false;
      this.listarlinea(1, this.buscar1, this.cantidadp1);
    },
    eliminartipo(id) {
      axios.delete("/api/eliminartipo/" + id);
      this.$vs.notify({
        title: "Registro eliminado",
        text: "Este registro ha sido eliminado exitosamente",
        color: "success"
      });
      this.eliminar = false;
      this.listartipo(1, this.buscar2);
      this.listarlinea(1, "", this.cantidadp1);
    },
    eliminarmodelo(id) {
      axios.delete("/api/eliminarmodelo/" + id);
      this.$vs.notify({
        title: "Registro eliminado",
        text: "Este registro ha sido eliminado exitosamente",
        color: "success"
      });
      this.eliminar = false;
      this.listarmodelo(1, this.buscar3, this.cantidadp3);
    },
    eliminarmarca(id) {
      axios.delete("/api/eliminarmarca/" + id);
      this.$vs.notify({
        title: "Registro eliminado",
        text: "Este registro ha sido eliminado exitosamente",
        color: "success"
      });
      this.eliminar = false;
      this.listarmarca(1, this.buscar4, this.cantidadp4);
    },

    eliminarpresentacion(id) {
      axios.delete("/api/eliminarpresentacion/" + id);
      this.$vs.notify({
        title: "Registro eliminado",
        text: "Este registro ha sido eliminado exitosamente",
        color: "success"
      });
      this.eliminar = false;
      this.listarpresentacion(1, this.buscar6, this.cantidadp6);
    },
    todaslinea() {
      let me = this;
      var url = "/api/lineaproductosall/" + this.usuario.id_empresa;
      axios
        .get(url)
        .then(function(response) {
          me.optionlinea = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    //Funcion eliminar registro, v-dialog confirm
    eliminarpro(cd) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: `¿Desea Elimnar este registro?`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.acceptAlert,
        parameters: cd
      });
    },
    acceptAlert(parameters) {
      axios.delete("/api/eliminarproductos/" + parameters);
      this.$vs.notify({
        color: "success",
        title: "Producto Eliminado  ",
        text: "El producto selecionado fue eliminado con exito"
      });
      this.listar(1, this.buscar, this.cantidadp);
    },
    agregar(tipo, accion, dato) {
      switch (tipo) {
        case "lineas": {
          switch (accion) {
            case "guardar": {
              this.agregarlinea = true;
              this.tipoaccionmodal = 1;
              this.titulo = "Agregar linea de producto";
              this.nombre = "";
              this.codigo = "";
              this.cta_civa = "";
              this.cta_civa0 = "";
              this.cta_viva = "";
              this.cta_viva0 = "";
              this.cta_costo = "";
              break;
            }
            case "editar": {
              this.agregarlinea = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar linea de producto";
              this.id = dato.id_linea_producto;
              this.nombre = dato.nombre;
              this.codigo = dato.codigo;
              this.cta_civa = dato.cta_compras_iva;
              this.cta_civa0 = dato.cta_compras_iva_0;
              this.cta_viva = dato.cta_ventas_iva;
              this.cta_viva0 = dato.cta_ventas_iva_0;
              this.cta_costo = dato.cuenta_costo;
              break;
            }
          }
          break;
        }
        case "tipos": {
          switch (accion) {
            case "guardar": {
              this.agregartipo = true;
              this.tipoaccionmodal = 1;
              this.titulo = "Agregar tipo de producto";
              this.seleclinea = "";
              this.codtipo = "";
              this.nombretipo = "";
              this.utilidadtipo = "";
              break;
            }
            case "editar": {
              this.agregartipo = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar tipo de producto";
              this.id = dato.id_tipo_producto;
              this.seleclinea = dato.id_linea_producto;
              this.codtipo = dato.codigo;
              this.nombretipo = dato.nombre;
              this.utilidadtipo = dato.utilidad;
              break;
            }
          }
          break;
        }
        case "marcas": {
          switch (accion) {
            case "guardar": {
              this.agregarmarca = true;
              this.tipoaccionmodal = 1;
              this.titulo = "Agregar marca";
              this.nombremarca = "";
              this.descripcionmarca = "";
              break;
            }
            case "editar": {
              this.agregarmarca = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar marca";
              this.id = dato.id_marca;
              this.nombremarca = dato.nombre;
              this.descripcionmarca = dato.descripcion;
              break;
            }
          }
          break;
        }
        case "modelos": {
          switch (accion) {
            case "guardar": {
              this.agregarmodelo = true;
              this.tipoaccionmodal = 1;
              this.titulo = "Agregar modelo";
              this.nombremodelo = "";
              this.descripcionmodelo = "";
              break;
            }
            case "editar": {
              this.agregarmodelo = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar modelo";
              this.id = dato.id_modelo;
              this.nombremodelo = dato.nombre;
              this.descripcionmodelo = dato.descripcion;
              break;
            }
          }
          break;
        }
        case "bodegas": {
          switch (accion) {
            case "guardar": {
              this.agregarbodega = true;
              this.tipoaccionmodal = 1;
              this.titulo = "Agregar bodega";
              this.codigobodega = "";
              this.nombrebodega = "";
              this.seriebodega = "";
              this.telefonobodega = "";
              this.ubicacionbodega = "";
              this.direccionbodega = "";
              break;
            }
            case "editar": {
              this.agregarbodega = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar bodega";
              this.id = dato.id_bodega;
              this.codigobodega = dato.codigo;
              this.nombrebodega = dato.nombre;
              this.seriebodega = dato.serie_factura;
              this.telefonobodega = dato.telefono;
              this.ubicacionbodega = dato.ubicacion;
              this.direccionbodega = dato.direccion;
              break;
            }
          }
          break;
        }
        case "presentaciones": {
          switch (accion) {
            case "guardar": {
              this.agregarpresentacion = true;
              this.tipoaccionmodal = 1;
              this.titulo = "Agregar presentacion";
              this.nombrepresentacion = "";
              this.descripcionpresentacion = "";
              break;
            }
            case "editar": {
              this.agregarpresentacion = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar presentacion";
              this.id = dato.id_presentacion;
              this.nombrepresentacion = dato.nombre;
              this.descripcionpresentacion = dato.descripcion;
              break;
            }
          }
          break;
        }
      }
    },
    editar(id) {
      this.$router.push(`/inventario/catalogo/${id}/editar`);
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    guardarlinea() {
      axios
        .post("/api/guardarlinea", {
          nombre: this.nombre,
          codigo: this.codigo,
          cta_civa: this.cta_civa,
          cta_civa0: this.cta_civa0,
          cta_viva: this.cta_viva,
          cta_viva0: this.cta_viva0,
          cta_costo: this.cta_costo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarlinea = false;
          this.listarlinea(1, this.buscar1, this.cantidadp1);
          this.todaslinea();
        })
        .catch(err => {});
    },
    editarlinea() {
      axios
        .post("/api/editarlinea", {
          id: this.id,
          nombre: this.nombre,
          codigo: this.codigo,
          cta_civa: this.cta_civa,
          cta_civa0: this.cta_civa0,
          cta_viva: this.cta_viva,
          cta_viva0: this.cta_viva0,
          cta_costo: this.cta_costo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarlinea = false;
          this.listarlinea(1, this.buscar1, this.cantidadp1);
        })
        .catch(err => {});
    },
    guardartipo() {
      axios
        .post("/api/guardartipo", {
          seleclinea: this.seleclinea,
          codtipo: this.codtipo,
          nombretipo: this.nombretipo,
          utilidadtipo: this.utilidadtipo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregartipo = false;
          this.listartipo(1, this.buscar2);
        })
        .catch(err => {});
    },
    editartipo() {
      axios
        .post("/api/editartipo", {
          id: this.id,
          seleclinea: this.seleclinea,
          codtipo: this.codtipo,
          nombretipo: this.nombretipo,
          utilidadtipo: this.utilidadtipo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregartipo = false;
          this.listartipo(1, this.buscar2);
        })
        .catch(err => {});
    },
    guardarmarca() {
      axios
        .post("/api/guardarmarca", {
          nombre: this.nombremarca,
          descripcion: this.descripcionmarca,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarmarca = false;
          this.listarmarca(1, this.buscar3, this.cantidadp3);
        })
        .catch(err => {});
    },
    editarmarca() {
      axios
        .post("/api/editarmarca", {
          id: this.id,
          nombre: this.nombremarca,
          descripcion: this.descripcionmarca,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarmarca = false;
          this.listarmarca(1, this.buscar3, this.cantidadp3);
        })
        .catch(err => {});
    },
    guardarmodelo() {
      axios
        .post("/api/guardarmodelo", {
          nombre: this.nombremodelo,
          descripcion: this.descripcionmodelo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarmodelo = false;
          this.listarmodelo(1, this.buscar4, this.cantidadp4);
        })
        .catch(err => {});
    },
    editarmodelo() {
      axios
        .post("/api/editarmodelo", {
          id: this.id,
          nombre: this.nombremodelo,
          descripcion: this.descripcionmodelo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarmodelo = false;
          this.listarmodelo(1, this.buscar4, this.cantidadp4);
        })
        .catch(err => {});
    },
    guardarpresentacion() {
      axios
        .post("/api/guardarpresentacion", {
          nombre: this.nombrepresentacion,
          descripcion: this.descripcionpresentacion,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarpresentacion = false;
          this.listarpresentacion(1, this.buscar6, this.cantidadp6);
        })
        .catch(err => {});
    },
    editarpresentacion() {
      axios
        .post("/api/editarpresentacion", {
          id: this.id,
          nombre: this.nombrepresentacion,
          descripcion: this.descripcionpresentacion,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarpresentacion = false;
          this.listarpresentacion(1, this.buscar6, this.cantidadp6);
        })
        .catch(err => {});
    },
    limpiar() {
      this.nombre = "";
      this.codigo = "";
      this.cta_civa = "";
      this.cta_civa0 = "";
      this.cta_viva = "";
      this.cta_viva0 = "";
      this.cta_costo = "";
      //variables tipo producto
      this.seleclinea = "";
      this.codtipo = "";
      this.nombretipo = "";
      this.utilidadtipo = "";
      //variables marca
      this.nombremarca = "";
      this.descripcionmarca = "";
      //variables modelo
      this.nombremodelo = "";
      this.descripcionmodelo = "";
      //variables bodega
      this.codigobodega = "";
      this.nombrebodega = "";
      this.seriebodega = "";
      this.telefonobodega = "";
      this.ubicacionbodega = "";
      this.direccionbodega = "";
      //variables presentación
      this.nombrepresentacion = "";
      this.descripcionpresentacion = "";
    },
    exportardatos() {
      import("../../vendor/Export2Excel").then(excel => {
        const list = this.contenido;
        const data = this.formatJson(this.indexs, list);
        excel.export_json_to_excel({
          header: this.cabezera,
          data,
          filename: this.nombreexportar,
          autoWidth: this.cellancho,
          bookType: this.tipoformatoexportar
        });
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v =>
        filterVal.map(j => {
          return v[j];
        })
      );
    }
  },
  mounted() {
    this.listar(1, this.buscar);
    this.listarlinea(1, this.buscar1);
    this.todaslinea();
    this.listartipo(1, this.buscar2);
    this.listarmarca(1, this.buscar3);
    this.listarmodelo(1, this.buscar4);

    this.listarpresentacion(1, this.buscar6);
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
.con-vs-dialog .vs-dialog {
  max-width: 950px;
}

input[type="”file”"]#nuestroinput {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}
label[for=" nuestroinput"] {
  font-size: 14px;
  font-weight: 600;
  color: #fff;
  background-color: #106ba0;
  display: inline-block;
  transition: all 0.5s;
  cursor: pointer;
  padding: 15px 40px !important;
  text-transform: uppercase;
  width: fit-content;
  text-align: center;
}
.imagenpre {
  height: 100%;
  cursor: pointer;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
.centimg {
  height: 225px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.8) !important;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
.verimagen {
  overflow: hidden;
  padding: 0px;
  height: 300px;
  height: 300px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.8) !important;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
  border: 5px solid rgba(0, 0, 0, 0.3);
}
.centimg {
  height: 225px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.8) !important;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
.centimg:hover {
  background: rgba(255, 255, 255, 0.6) !important;
  cursor: pointer;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}

.centimg img {
  max-width: 100%;
  max-height: 100px;
  cursor: pointer;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
.demo-alignment > * {
  margin-right: 1.5rem;
  margin-top: 0.8rem;
}
.con-vs-dropdown--menu {
  z-index: 9999999999999999;
}
</style>
