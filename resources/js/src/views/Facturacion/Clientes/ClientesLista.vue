<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <!-- ITEMS PER PAGE -->
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup.enter="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
          <!--boton de herramientas-->
          <div class="dropdown-button-container mr-3">
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="settings" style="border-radius: 5px;"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" divider @click="abrirlinea()">Grupo Cliente</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="abrirtipo()">Tipo de Cliente</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
          <!--Fin deboton de herramientas-->
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/facturacion/cliente/agregar">Agregar</vs-button>
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
          <vs-th>Nombre</vs-th>
          <vs-th>Tipo de Identificación</vs-th>
          <vs-th>Identificación</vs-th>
          <vs-th>dirección</vs-th>
          <vs-th>email</vs-th>
          <vs-th>Teléfono</vs-th>

          <vs-th>Estado</vs-th>

          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id_cliente" v-for="datos in data">
            <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.tipo_identificacion">{{datos.tipo_identificacion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.identificacion">{{datos.identificacion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.direccion">{{datos.direccion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.email">{{datos.email}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.telefono">{{datos.telefono}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.estado">{{datos.estado}}</vs-td>
            <vs-td v-else>-</vs-td>

            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="cursor-pointer"
                @click.stop="editar(datos.id_cliente)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2 cursor-pointer"
                @click.stop="eliminarCliente(datos.id_cliente)"
              />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vx-card>
    <!-- Modales-->
    <vs-popup :title="titulomodal" :active.sync="modal">
      <div class="vx-row">
        <!-- Modal principal Grupo cliente-->
        <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==1">
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
                      v-model="buscar1"
                      @keyup.enter="listarlinea(1,buscar1)"
                      v-bind:placeholder="i18nbuscar"
                    />
                    <div>
                      <vs-button
                        class="btnx"
                        type="filled"
                        divider
                        @click="agregar('lineas','guardar')"
                      >Agregar Nuevo</vs-button>
                    </div>
                  </div>
                </div>
                <vs-table stripe :data="contenidolinea">
                  <template slot="thead">
                    <vs-th>Nombre</vs-th>
                    <vs-th>Cuenta</vs-th>
                    <vs-th>Cuenta Descuento</vs-th>
                    <vs-th>Cuenta Anticipo</vs-th>
                    <vs-th>Cuenta Servicio</vs-th>
                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.id_grupo_cliente" v-for="datos in data">
                      <vs-td v-if="datos.nombre_grupo">{{datos.nombre_grupo}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.cuenta">{{datos.cuenta}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.cuenta_descuento">{{datos.cuenta_descuento}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.cuenta_anticipo">{{datos.cuenta_anticipo}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.cuenta_servicio">{{datos.cuenta_servicio}}</vs-td>
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
                          @click.stop="ideliminar=datos.id_grupo_cliente;eliminar=true;tipoaccionmodal=1"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>
          </vx-card>
        </div>
        <!-- Modal principal  tipo cliente-->
        <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==2">
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
                      v-model="buscar2"
                      @keyup="listartipo(1,buscar2)"
                      v-bind:placeholder="i18nbuscar"
                    />
                    <div>
                      <vs-button
                        class="btnx"
                        type="filled"
                        divider
                        @click="agregar('tipos','guardar')"
                      >Agregar Nuevo</vs-button>
                    </div>
                  </div>
                </div>
                <vs-table stripe :data="contenidotipo">
                  <template slot="thead">
                    <vs-th>Descripción Cliente</vs-th>

                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.id_tipo_cliente" v-for="datos in data">
                      <vs-td
                        v-if="datos.descripcion_tipo_cliente"
                      >{{datos.descripcion_tipo_cliente}}</vs-td>
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
                          @click.stop="ideliminar=datos.id_tipo_cliente;eliminar=true;tipoaccionmodal=2"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>
          </vx-card>
        </div>
        <!-- Modal Principal Marca de Producto-->
        <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==3">
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
                      v-model="buscar3"
                      @keyup="listarvendedor(1,buscar3,cantidadp3)"
                      v-bind:placeholder="i18nbuscar"
                    />
                    <div>
                      <vs-button
                        class="btnx"
                        type="filled"
                        divider
                        @click="agregar('vendedores','guardar')"
                      >Agregar Nuevo</vs-button>
                    </div>
                  </div>
                </div>
                <vs-table stripe :data="contenidomarca">
                  <template slot="thead">
                    <vs-th>Código</vs-th>
                    <vs-th>Nombre</vs-th>
                    <vs-th>Email</vs-th>
                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.id_vendedor" v-for="datos in data">
                      <vs-td v-if="datos.codigo_vendedor">{{datos.codigo_vendedor}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.nombre_vendedor">{{datos.nombre_vendedor}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.email_vendedor">{{datos.email_vendedor}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td class="whitespace-no-wrap">
                        <feather-icon
                          icon="EditIcon"
                          svgClasses="w-5 h-5 hover:text-primary stroke-current"
                          class="cursor-pointer"
                          @click.stop="agregar('vendedores','editar',datos)"
                        />
                        <feather-icon
                          icon="TrashIcon"
                          svgClasses="w-5 h-5 hover:text-danger stroke-current"
                          class="ml-2 cursor-pointer"
                          @click.stop="ideliminar=datos.id_vendedor,eliminar=true,tipoaccionmodal=3"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
                <!--
                <vs-pagination
                  class="mt-3"
                  v-if="pagination3.count"
                  :total="pagination3.count"
                  v-model="pagina3"
                  @change="listarvendedor(pagina3,buscar3,cantidadp3)"
                  prev-icon="arrow_back"
                  next-icon="arrow_forward"
                  :max="6"
                ></vs-pagination>-->
              </div>
            </div>
          </vx-card>
        </div>
      </div>
      <!-- Opciones para agregar-->
      <!-- Modal Agregar grupo cliente-->
      <vs-popup :title="titulo" :active.sync="agregarlinea">
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <!--Nombre-->
            <div class="vx-col sm:w-full w-full mb-6">
              <vs-input class="w-full" label="Nombre:" v-model="nombre_grupo" />
            </div>
            <div class="vx-col w-1/2 mb-6">
              <label class="vs-input--label">Cuenta</label>
              <vx-input-group class="mb-base">
                <vs-input class="w-full" v-model="cuenta" @keypress="solonumeros($event) " />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary" @click="popupActive=true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <!---->
            <!-- Popup cuenta contable de cuenta-->
            <vs-popup title="Seleccione una Cuenta Contable" :active.sync="popupActive">
              <div class="con-exemple-prompt">
                <vs-input
                  class="mb-4 md:mb-0 mr-4 w-full"
                  v-model="buscar"
                  @keyup="listarcuenta(1,buscar)"
                  v-bind:placeholder="i18nbuscar"
                />
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
            <!--********************************Cuenta Descuento*****************************************-->
            <div class="vx-col w-1/2 mb-6">
              <label class="vs-input--label">Cuenta Descuento</label>
              <vx-input-group class="mb-base">
                <vs-input
                  class="w-full"
                  v-model="cuenta_descuento"
                  @keypress="solonumeros($event) "
                />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary" @click="popupActive1=true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>

            <!-- Popup cuenta contable de Descuento-->
            <vs-popup title="Seleccione una Cuenta Contable" :active.sync="popupActive1">
              <div class="con-exemple-prompt">
                <vs-input
                  class="mb-4 md:mb-0 mr-4 w-full"
                  v-model="buscar"
                  @keyup="listarcuenta1(1,buscar)"
                  v-bind:placeholder="i18nbuscar"
                />
                <vs-table
                  stripe
                  v-model="cuentaarray31"
                  @selected="handleSelected1"
                  :data="contenidocuenta1"
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

            <!--************************************Cuenta Anticipo********************************************-->

            <div class="vx-col w-1/2 mb-6">
              <label class="vs-input--label">Cuenta Anticipo</label>
              <vx-input-group class="mb-base">
                <vs-input
                  class="w-full"
                  v-model="cuenta_anticipo"
                  @keypress="solonumeros($event) "
                />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary" @click="popupActive2=true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <!---->
            <!-- Popup cuenta contable de CUENTA ANTICIPO-->
            <vs-popup title="Seleccione una Cuenta Contable" :active.sync="popupActive2">
              <div class="con-exemple-prompt">
                <vs-input
                  class="mb-4 md:mb-0 mr-4 w-full"
                  v-model="buscar"
                  @keyup="listarcuenta2(1,buscar)"
                  v-bind:placeholder="i18nbuscar"
                />
                <vs-table
                  stripe
                  v-model="cuentaarray32"
                  @selected="handleSelected2"
                  :data="contenidocuenta2"
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
            <!--**********************************Cuenta Servicio*************************************************-->
            <div class="vx-col w-1/2 mb-6">
              <label class="vs-input--label">Cuenta Servicio</label>
              <vx-input-group class="mb-base">
                <vs-input
                  class="w-full"
                  v-model="cuenta_servicio"
                  @keypress="solonumeros($event) "
                />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary" @click="popupActive3=true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <!---->

            <!-- Popup cuenta contable de CUENTA SERVICIO-->
            <vs-popup title="Seleccione una Cuenta Contable" :active.sync="popupActive3">
              <div class="con-exemple-prompt">
                <vs-input
                  class="mb-4 md:mb-0 mr-4 w-full"
                  v-model="buscar"
                  @keyup="listarcuenta3(1,buscar)"
                  v-bind:placeholder="i18nbuscar"
                />
                <vs-table
                  stripe
                  v-model="cuentaarray33"
                  @selected="handleSelected3"
                  :data="contenidocuenta3"
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

            <div class="vx-col sm:w-full w-full">
              <vs-button
                color="success"
                type="border"
                v-if="tipoaccionmodal==1"
                @click="guardarlinea()"
              >Guardar</vs-button>
              <vs-button color="success" type="border" v-else @click="editargrupo()">Guardar</vs-button>
              <vs-button color="danger" type="border" @click="agregarlinea=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vs-popup>

      <!-- Modal Agregar tipo cliente-->
      <vs-popup :title="titulo" :active.sync="agregartipo">
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col sm:w-full w-full mb-6">
              <vs-input
                class="w-full"
                label="Descripción de Tipo Cliente:"
                v-model="descripcion_tipo_cliente"
              />
            </div>
            <div class="vx-col sm:w-full w-full">
              <vs-button
                color="success"
                type="filled"
                v-if="tipoaccionmodal==1"
                @click="guardartipo()"
              >Guardar</vs-button>
              <vs-button color="success" type="filled" v-else @click="editartipo()">Guardar</vs-button>
              <vs-button color="danger" type="filled" @click="agregartipo=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vs-popup>
      <!--modal de eliminar --->
      <vs-popup title="eliminar registro" :class="'peque'" :active.sync="eliminar">
        <vx-card>
          <div class="vx-col sm:w-full w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6 text-center">
                <label class="text-center">
                  Esta seguro que desea eliminar este registro
                  <br />
                </label>
              </div>
              <div class="vx-col sm:w-full w-full text-center">
                <vs-button
                  color="danger"
                  type="filled"
                  v-if="tipoaccionmodal==1"
                  @click="eliminargrupo(ideliminar)"
                >Eliminar</vs-button>

                <vs-button
                  v-else-if="tipoaccionmodal==2"
                  color="danger"
                  type="filled"
                  @click="eliminartipo(ideliminar)"
                >Eliminar</vs-button>
                <vs-button color="primary" type="filled" @click="eliminar=false">Cancelar</vs-button>
              </div>
            </div>
          </div>
        </vx-card>
      </vs-popup>
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
              <!--
              <div class="vx-row">
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
    <!--fin modal de exportar-->
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
                    :onSuccess="loadDataInTable"
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
    <!--fin modal de exportar-->
  </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
import vSelect from "vue-select";
//const $ = require("jquery");
const axios = require("axios");
export default {
  components: {
    AgGridVue,
    ImportExcel,
    vSelect
  },
  data() {
    return {
      //excel import
      file: "",
      tableData: [],
      header: [],
      sheetName: "",
      //variables para traer una columna plan ctas
      cuentaarray3: [],
      cuentaarray31: [],
      cuentaarray32: [],
      cuentaarray33: [],
      //
      //cuenta contable listar
      contenidocuenta: [],
      contenidocuenta1: [],
      contenidocuenta2: [],
      contenidocuenta3: [],
      popupActive: false,
      popupActive1: false,
      popupActive2: false,
      popupActive3: false,
      codcta: [],

      //variables modal
      titulomodal: "",
      modal: false,
      tipoaccion: 0,
      tipoaccionmodal: 0,
      titulo: "",
      id: null,
      agregarlinea: false,
      agregartipo: false,
      agregarvendedor: false,

      eliminar: false,
      ideliminar: 0,
      tipoeliminar: null,
      //mapeo de datos
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

      pagina: 1,
      pagina1: 1,
      pagina2: 1,
      pagina3: 1,
      cantidadp1: 1000000,
      cantidadp2: 100000,
      cantidadp3: 100000,
      // cantidadp11: 10,
      offset: 3,
      offset1: 3,
      offset2: 3,
      offset3: 3,
      //buscador
      buscar: "",
      buscar1: "",
      buscar2: "",
      buscar3: "",
      //buscador

      criterio1: "codcta",
      criterio11: "codcta",
      //otros valores
      gridApi: null,
      contenido: [],
      contenidolinea: [],
      contenidotipo: [],
      contenidomarca: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //variables linea producto
      nombre_grupo: "",
      cuenta: "",
      cuenta_descuento: "",
      cuenta_anticipo: "",
      cuenta_servicio: "",
      //variables tipo producto
      descripcion_tipo_cliente: "",
      //variables marca
      codigo_vendedor: "",
      nombre_vendedor: "",
      email_vendedor: "",
      //opciones linea de producto para tipo de producto
      optionlinea: [],
      //buscador
      criterio: "id",
      //otros valoreS
      gridApi: null,
      contenido: [],
      //ERRORES
      error: 0,
      erroremail: [],
      //Datos para la importaciond de archivos
      importar: false,
      nombreimportar: "",

      //Datos para la Exportacion de archivos
      exportar: false,
      nombreexportar: "",
      formatoexportar: ["xlsx", "csv", "txt"],
      cellancho: true,
      tipoformatoexportar: "xlsx",
      //campos que existen para exportar
      campos: [
        "id_cliente",
        "codigo",
        "nombre",
        "identificacion",
        "direccion",
        "email",
        "telefono",
        "contacto",
        "estado",
        "cuenta_contable",
        "comentario",
        "descuento",
        "num_pago",
        "tipo_identificacion",
        "codigo_pais",
        "grupo_tributario",
        "provincia",
        "canton",
        "parroquia",
        "parte_relacionada",
        "lista_precios",
        "limite_credito",
        "formas_pago",
        "fcrea",
        "fmodifica",
        "umodifica",
        "ucrea",
        "id_grupo_cliente",
        "id_empresa",
        "id_tipo_cliente",
        "id_vendedor"
      ],
      //campos elegidos a exportar
      indexs: [
        "id_cliente",
        "codigo",
        "nombre",
        "identificacion",
        "direccion",
        "email",
        "telefono",
        "contacto",
        "estado",
        "cuenta_contable",
        "comentario",
        "descuento",
        "num_pago",
        "tipo_identificacion",
        "codigo_pais",
        "grupo_tributario",
        "provincia",
        "canton",
        "parroquia",
        "parte_relacionada",
        "lista_precios",
        "limite_credito",
        "formas_pago",
        "fcrea",
        "fmodifica",
        "umodifica",
        "ucrea",
        "id_grupo_cliente",
        "id_empresa",
        "id_tipo_cliente",
        "id_vendedor"
      ],
      //cabezera que imprimira en el excel
      cabezera: [
        "id_cliente",
        "Código:",
        "Nombre:",
        "Identificación:",
        "Dirección:",
        "Email:",
        "Teléfono:",
        "Contacto:",
        "Estado:",
        "Cuenta Contable:",
        "Comentario:",
        "Descuento:",
        "N° pago:",
        "Tipo Identificación:",
        "Código país:",
        "Grupo tributario:",
        "Provincia:",
        "Cantón",
        "Parroquia:",
        "Parte relacionada:",
        "Lista precios:",
        "Límite crédito:",
        "Formas pago:",
        "fcrea",
        "fmodifica",
        "umodifica",
        "ucrea",
        "id_grupo_cliente",
        "id_empresa",
        "id_tipo_cliente",
        "id_vendedor"
      ]
    };
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token
    },
    crearrol() {
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[14].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[14].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[14].eliminar;
      }
      return res;
    }
  },
  methods: {
    importardatos() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file);
      axios
        .post("/api/importarexcel", formData, {})
        .then(res => {
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar = false;
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
    //importar archivos
    loadDataInTable({ results, header, meta }) {
      this.header = header;
      this.tableData = results;
      this.sheetName = meta.sheetName;
    },

    listarcuenta(page1, buscar1) {
      var url =
        "/api/cuentas/" +
        this.usuario.id_empresa +
        "?page=" +
        page1 +
        "&buscar=" +
        buscar1;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidocuenta = respuesta.recupera;
      });
    },
    listarcuenta1(page1, buscar1) {
      var url =
        "/api/cuentas/" +
        this.usuario.id_empresa +
        "?page=" +
        page1 +
        "&buscar=" +
        buscar1;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidocuenta1 = respuesta.recupera;
      });
    },
    listarcuenta2(page1, buscar1) {
      var url =
        "/api/cuentas/" +
        this.usuario.id_empresa +
        "?page=" +
        page1 +
        "&buscar=" +
        buscar1;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidocuenta2 = respuesta.recupera;
      });
    },
    listarcuenta3(page1, buscar1) {
      var url =
        "/api/cuentas/" +
        this.usuario.id_empresa +
        "?page=" +
        page1 +
        "&buscar=" +
        buscar1;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidocuenta3 = respuesta.recupera;
      });
    },
    handleSelected(tr) {
      (this.popupActive = false), (this.cuenta = `${tr.codcta}`);
      // (this.popupActive = false), (this.cuenta_contable = `${tr.codcta}`);
    },
    handleSelected1(tr) {
      (this.popupActive1 = false), (this.cuenta_descuento = `${tr.codcta}`);
    },
    handleSelected2(tr) {
      (this.popupActive2 = false), (this.cuenta_anticipo = `${tr.codcta}`);
    },
    handleSelected3(tr) {
      (this.popupActive3 = false), (this.cuenta_servicio = `${tr.codcta}`);
    },
    cancelar() {
      this.$router.push("/facturacion/clientes");
    },
    //abren modales
    abrirlinea() {
      this.tipoaccion = 1;
      this.modal = true;
      this.titulomodal = "Grupo Cliente";
    },
    abrirtipo() {
      this.tipoaccion = 2;
      this.modal = true;
      this.titulomodal = "Tipos de Clientes";
    },
    //listar grupo clientes
    listarlinea(page1, buscar1) {
      var url =
        "/api/grupocliente/" +
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
    //listan clientes
    listar(page, buscar) {
      var url =
        "/api/clientes/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios.get(url).then(res => {
        this.contenido = res.data.recupera;
      });
    },
    //listar tipo clientes
    listartipo(page2, buscar2) {
      var url =
        "/api/listartipocliente/" +
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
    //guardar grupo cliente
    guardarlinea() {
      axios
        .post("/api/guardargrupo", {
          nombre_grupo: this.nombre_grupo,
          cuenta: this.cuenta,
          cuenta_descuento: this.cuenta_descuento,
          cuenta_anticipo: this.cuenta_anticipo,
          cuenta_servicio: this.cuenta_servicio,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarlinea = false;
          this.listarlinea(1, this.buscar1);
          this.todaslinea();
        })
        .catch(err => {});
    },
    //guardar tipo cliente
    guardartipo() {
      axios
        .post("/api/guardartipocliente", {
          descripcion_tipo_cliente: this.descripcion_tipo_cliente,
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
    //editar grupo cliente
    editargrupo() {
      axios
        .post("/api/editargrupo", {
          id: this.id,
          nombre_grupo: this.nombre_grupo,
          cuenta: this.cuenta,
          cuenta_descuento: this.cuenta_descuento,
          cuenta_anticipo: this.cuenta_anticipo,
          cuenta_servicio: this.cuenta_servicio,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarlinea = false;
          this.listarlinea(1, this.buscar1);
        });
    },
    //editar tipo cliente
    editartipo() {
      axios
        .post("/api/editartipocliente", {
          id: this.id,
          descripcion_tipo_cliente: this.descripcion_tipo_cliente,
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
        });
    },
    //eliminar grupo cliente
    eliminargrupo(id) {
      axios.delete("/api/eliminargrupo/" + id);
      this.$vs.notify({
        title: "Registro eliminado",
        text: "Este registro ha sido eliminado exitosamente",
        color: "success"
      });
      this.eliminar = false;
      this.listarlinea(1, this.buscar1);
    },
    //eliminar tipo cliente
    eliminartipo(id) {
      axios.delete("/api/eliminartipocliente/" + id);
      this.$vs.notify({
        title: "Registro eliminado",
        text: "Este registro ha sido eliminado exitosamente",
        color: "success"
      });
      this.eliminar = false;
      this.listartipo(1, this.buscar2);
    },
    todaslinea() {
      var url = "/api/grupoclienteall/" + this.usuario.id_empresa;
      axios.get(url).then(res => {
        this.optionlinea = res.data;
      });
    },
    //guardar editar grupo,tipo,vendedor
    agregar(tipo, accion, dato) {
      switch (tipo) {
        case "lineas": {
          switch (accion) {
            case "guardar": {
              this.agregarlinea = true;
              this.tipoaccionmodal = 1;
              this.titulo = "Agregar Grupo Cliente";
              this.nombre_grupo = "";
              this.cuenta = "";
              this.cuenta_descuento = "";
              this.cuenta_anticipo = "";
              this.cuenta_servicio = "";
              break;
            }
            case "editar": {
              this.agregarlinea = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar Grupo Cliente";
              this.id = dato.id_grupo_cliente;
              this.nombre_grupo = dato.nombre_grupo;
              this.cuenta = dato.cuenta;
              this.cuenta_descuento = dato.cuenta_descuento;
              this.cuenta_anticipo = dato.cuenta_anticipo;
              this.cuenta_servicio = dato.cuenta_servicio;

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
              this.titulo = "Agregar tipo de cliente";

              this.descripcion_tipo_cliente = "";

              break;
            }
            case "editar": {
              this.agregartipo = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar tipo de cliente";
              this.id = dato.id_tipo_cliente;

              this.descripcion_tipo_cliente = dato.descripcion_tipo_cliente;

              break;
            }
          }
          break;
        }
        case "vendedores": {
          switch (accion) {
            case "guardar": {
              this.agregarvendedor = true;
              this.tipoaccionmodal = 1;
              this.titulo = "Agregar vendedor";
              this.codigo_vendedor = "";
              this.nombre_vendedor = "";
              this.email_vendedor = "";
              break;
            }
            case "editar": {
              this.agregarvendedor = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar marca";
              this.id = dato.id_vendedor;
              this.codigo_vendedor = dato.codigo_vendedor;
              this.nombre_vendedor = dato.nombre_vendedor;
              this.email_vendedor = dato.email_vendedor;
              break;
            }
          }
          break;
        }
      }
    },
    limpiar() {
      //limpiar grupo cliente
      this.nombre_grupo = "";
      this.cuenta = "";
      this.cuenta_descuento = "";
      this.cuenta_anticipo = "";
      this.cuenta_servicio = "";
      //variables tipo producto

      this.descripcion_tipo_cliente = "";
      //variables marca
      this.codigo_vendedor = "";
      this.nombre_vendedor = "";
      this.email_vendedor = "";
    },
    validar() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.error = 0;
      this.erroremail = [];

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
    //exportar archivo
    exportardatos() {
      import("../../../vendor/Export2Excel").then(excel => {
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
    },
    //importar archivos
    loadDataInTable({ results, header, meta }) {
      this.header = header;
      this.tableData = results;
      this.sheetName = meta.sheetName;
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    //agregar cliente
    crear() {
      this.$router.push("/facturacion/cliente/agregar");
    },
    //editar cliente
    editar(id) {
      this.$router.push(`/facturacion/cliente/${id}/editar`);
    },
    //eliminar cliente
    eliminarCliente(id) {
      //metodo eliminar
      axios
        //Envia id
        .delete("/api/eliminarCliente/" + id);
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: "¿Desea Elimnar este registro?",
        accept: this.acceptAlert
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
    acceptAlert() {
      this.$vs.notify({
        color: "danger",
        title: "Cliente Eliminado  ",
        text: "El cliente selecionado fue eliminado con exito"
      });
      this.listar(1, this.buscar, this.cantidadp);
    },
    importarexcel() {
      $(".inputexcel").click();
    }
  },
  mounted() {
    this.listar(1, this.buscar);
    this.listarlinea(1, this.buscar1);
    this.todaslinea();
    this.listartipo(1, this.buscar2);
    this.listarcuenta(1, this.buscar1);
    this.listarcuenta1(1, this.buscar1);
    this.listarcuenta2(1, this.buscar1);
    this.listarcuenta3(1, this.buscar1);
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
</style>
