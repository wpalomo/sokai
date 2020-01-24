<template>
  <div class="vx-row">
    <div class="vx-col w-full ">
      <vx-card >
        <h4 v-if="!$route.params.id">Agregar Empresa</h4>
        <h4 v-else>Editar Empresa</h4>
      <br>
        <div class="vx-row">
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-select
              placeholder="Seleccione"
              class="selectExample w-full"
              label="Periodo"
              vs-multiple
              autocomplete
              v-model="periodo_empresa"
              v-if="usuario.id_rol==3"
            >
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in options2"
              />
            </vs-select>
            <vs-select
              placeholder="Seleccione"
              class="selectExample w-full"
              label="Periodo"
              vs-multiple
              autocomplete
              disabled
              v-bind:value="periodo_empresa"
              v-else
            >
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in options2"
              />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3" label="Ruc" v-model="ruc_empresa" @keypress="solonumeros" @blur="validarruc" maxlength="13"/>
            <vs-input class="w-full" v-else disabled label="Ruc" v-bind:value="ruc_empresa"/>
            <div v-show="error" >
              <span class="text-danger" v-for="(err,index) in errorruc" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3"  label="Nombre Comercial" v-model="nombre_empresa" />
            <vs-input class="w-full" v-else disabled label="Nombre Comercial" v-bind:value="nombre_empresa" />
            <div v-show="error" v-if="!nombre_empresa">
              <span class="text-danger" v-for="(err,index) in errornombre" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-2/5 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3" label="Razón social" v-model="nombre_comercial" />
            <vs-input class="w-full" v-else disabled label="Razón social" v-bind:value="nombre_comercial" />
            <div v-show="error" v-if="!nombre_comercial">
              <span class="text-danger" v-for="(err,index) in errornombre_comercial" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-input type="text" v-if="usuario.id_rol==3"  v-validate="'numeric'" name="telef" @keypress="solonumeros" class="w-full" label="Telefono" v-model="telefono" maxlength="13"/>
            <vs-input type="text" v-else disabled class="w-full" label="Telefono" v-bind:value="telefono" maxlength="13"/>
            <span class="text-danger text-sm" v-show="errors.has('telef')">Este campo solo lleva numeros</span>
          </div>
          <div class="vx-col sm:w-2/5 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3" label="Direccion" v-model="direccion_empresa" />
            <vs-input class="w-full" v-else disabled label="Direccion" v-bind:value="direccion_empresa" />
            <div v-show="error" v-if="!direccion_empresa">
              <span class="text-danger" v-for="(err,index) in errordireccion" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" >
            <!--<vs-input class="w-full"  label="Provincia" v-model="id_provincia" />-->
            <vs-select
              placeholder="Seleccione Provincia"
              class="selectExample w-full"
              label="Provincia"
              vs-multiple
              autocomplete
              v-model="id_provincia"
              v-if="usuario.id_rol==3"
              @change="getCiudades()"
            >
              <!--<vs-select-item value=1 text="Pichincha" />-->
              <vs-select-item v-for="data in provincias" :key="data.id" :value="data.id_provincia" :text="data.nombre"/>
            </vs-select>
            <vs-select
              placeholder="Seleccione Provincia"
              class="selectExample w-full"
              label="Provincia"
              vs-multiple
              autocomplete
              disabled
              v-bind:value="id_provincia"
              v-else
            >
              <vs-select-item v-for="data in provincias" :key="data.id" :value="data.id_provincia" :text="data.nombre"/>
            </vs-select>
            <div v-show="error" v-if="!id_provincia">
              <span class="text-danger" v-for="(err,index) in errorprovincia" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" >
            <vs-select
              placeholder="Seleccione Ciudad"
              class="selectExample w-full"
              label="Ciudad"
              vs-multiple
              autocomplete
              v-model="id_ciudad"
              v-if="usuario.id_rol==3"
            >
              <vs-select-item v-for="data in ciudades" :key="data.id_ciudad" :value="data.id_ciudad" :text="data.nombre"/>
            </vs-select>
            <vs-select
              placeholder="Seleccione Ciudad"
              class="selectExample w-full"
              label="Ciudad"
              vs-multiple
              autocomplete
              v-bind:value="id_ciudad"
              v-else
              disabled
            >
              <vs-select-item v-for="data in ciudades" :key="data.id_ciudad" :value="data.id_ciudad" :text="data.nombre"/>
            </vs-select>
            <div v-show="error" v-if="!id_ciudad">
              <span class="text-danger" v-for="(err,index) in errorciudad" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3" v-validate="'numeric'" name="rucConta"  label="Ruc Contador" v-model="ruc_contador" @keypress="solonumeros" @blur="validarruccontador" maxlength="13"/>
            <vs-input class="w-full" disabled v-else name="rucConta"  label="Ruc Contador" v-bind:value="ruc_contador"/>
           <div v-show="error" >
              <span class="text-danger" v-for="(err,index) in errorrucContador" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input type="text" v-if="usuario.id_rol==3" class="w-full" label="Nombre Contador" v-model="nombre_contador" />
            <vs-input type="text" v-else disabled class="w-full" label="Nombre Contador" v-bind:value="nombre_contador" />
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input type="text" v-if="usuario.id_rol==3" class="w-full" label="Nombre Representante" v-model="nomb_representante" />
            <vs-input type="text" v-else disabled class="w-full" label="Nombre Representante" v-bind:value="nomb_representante" />
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="Tipo"
              class="selectExample w-full"
              label="Tipo Identificacion RL"
              vs-multiple
              v-model="tipo_identidicacion_empresa"
              v-if="usuario.id_rol==3"
            >
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in options1"
              />
            </vs-select>
            <vs-select
              placeholder="Tipo"
              class="selectExample w-full"
              label="Tipo Identificacion RL"
              vs-multiple
              disabled
              v-else
              v-bind:value="tipo_identidicacion_empresa"
            >
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in options1"
              />
            </vs-select>
            <div v-show="error" v-if="!tipo_identidicacion_empresa">
              <span class="text-danger" v-for="(err,index) in errortipoIdent" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" v-if="!tipo_identidicacion_empresa && usuario.id_rol==3" >
          <!--@keyup="validarCedula()" @keypress="solonumeros"-->
            <vs-input class="w-full"   label="Identificacion Representante" v-model="identificacion_repr" v-if="tipo_identidicacion_empresa=='Cedula'"  @keypress="solonumeros" @blur="validarrepresentante"  maxlength="10" disabled/>
            <vs-input class="w-full"   label="Identificacion Representante" v-model="identificacion_repr" v-else-if="tipo_identidicacion_empresa=='Ruc'"  @keypress="solonumeros" @blur="validarrucrepresentante"  maxlength="13" disabled/>
            <vs-input class="w-full"   label="Identificacion Representante" v-model="identificacion_repr" v-else  maxlength="15" disabled/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorrucRepre" :key="index" v-text="err"></span>
            </div>

          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" v-else-if="tipo_identidicacion_empresa && usuario.id_rol==3">
            <vs-input class="w-full"   label="Identificacion Representante" v-model="identificacion_repr" v-if="tipo_identidicacion_empresa=='Cedula'"  @keypress="solonumeros" @blur="validarrepresentante"  maxlength="10"/>
            <vs-input class="w-full"   label="Identificacion Representante" v-model="identificacion_repr" v-else-if="tipo_identidicacion_empresa=='Ruc'"  @keypress="solonumeros" @blur="validarrucrepresentante"  maxlength="13"/>
            <vs-input class="w-full"   label="Identificacion Representante" v-model="identificacion_repr" v-else  maxlength="15"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorrucRepre" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
            <vs-input class="w-full" label="Identificacion Representante" v-bind:value="identificacion_repr" disabled/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Periodo Inicio</label>
            <flat-pickr :config="configdateTimePicker" class="w-full" v-if="usuario.id_rol==3" placeholder="Fecha de inicio" v-model="periodo_inicio"/>
            <flat-pickr class="w-full" v-else placeholder="Fecha de inicio" disabled v-bind:value="periodo_inicio"/>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Periodo Fin</label>
            <flat-pickr :config="configFromdateTimePicker" v-if="usuario.id_rol==3"  max="2019" min class="w-full" placeholder="Fecha finalización" v-model="periodo_fin"/>
            <flat-pickr class="w-full" placeholder="Fecha finalización" v-else disabled v-bind:value="periodo_fin"/>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-select
              placeholder="Seleccione su Moneda"
              class="selectExample w-full"
              label="Moneda"
              vs-multiple
              autocomplete
              v-model="id_moneda"
              v-if="usuario.id_rol==3"
            >
              <vs-select-item v-for="data in monedas" :key="data.id_moneda" :value="data.id_moneda" :text="data.nomb_moneda"/>
            </vs-select>
            <vs-select
              placeholder="Seleccione su Moneda"
              class="selectExample w-full"
              label="Moneda"
              vs-multiple
              autocomplete
              v-bind:value="id_moneda"
              v-else
              disabled
            >
              <vs-select-item v-for="data in monedas" :key="data.id_moneda" :value="data.id_moneda" :text="data.nomb_moneda"/>
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Fecha de duración</label>
            <flat-pickr v-if="usuario.id_rol==3" class="w-full" placeholder="Fecha de expiración" v-model="clave_duracion"/>
            <flat-pickr v-else disabled class="w-full" placeholder="Fecha de expiración" v-bind:value="clave_duracion"/>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Obligado llevar contabilidad</label>
            <vs-checkbox v-if="usuario.id_rol==3" icon-pack="feather" icon="icon-check" class="mt-2" v-model="contabilidad">
              <template v-if="contabilidad">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | Obligado
            </vs-checkbox>
            <vs-checkbox v-else disabled icon-pack="feather" icon="icon-check" class="mt-2" v-model="contabilidad">
              <template v-if="contabilidad">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | Obligado
          </vs-checkbox>
        </div>
          <template v-if="$route.params.id || usuario.id_rol!=3">
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <label class="vs-input--label">Cuenta Activo</label>
              <vx-input-group class="">
                <vs-input class="w-full" v-model="ctaActivo" :value="idActivo"  />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary"  @click="activePrompt3 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <label class="vs-input--label">Cuenta Pasivo</label>
              <vx-input-group class="">
                <vs-input class="w-full" v-model="ctaPasivo" :value="idPasivo" />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary"  @click="activePrompt4 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <label class="vs-input--label">Cuenta Patrimonio</label>
              <vx-input-group class="">
                <vs-input class="w-full" v-model="ctaPatrimonio" :value="idPatrimonio" />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary"  @click="activePrompt5 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <label class="vs-input--label">Cuenta Ingreso</label>
              <vx-input-group class="">
                <vs-input class="w-full" v-model="ctaIngreso" :value="idIngreso" />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary"  @click="activePrompt6 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <label class="vs-input--label">Cuenta Costo</label>
              <vx-input-group class="">
                <vs-input class="w-full" v-model="ctaCosto" :value="idCosto" />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary"  @click="activePrompt7 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <label class="vs-input--label">Cuenta Gasto</label>
              <vx-input-group class="">
                <vs-input class="w-full" v-model="ctaGasto" :value="idGasto" />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary"  @click="activePrompt8 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <label class="vs-input--label">Cuenta Orden</label>
              <vx-input-group class="">
                <vs-input class="w-full" v-model="ctaOrden" :value="idOrden" />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary"  @click="activePrompt2 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <label class="vs-input--label">Cuenta Resultado</label>
              <vx-input-group class="">
                <vs-input class="w-full" v-model="ctaResultado" :value="idResultado"  />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button @click="activePrompt = true" color="primary">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
            </div>
          </template>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':balance=='Si'}">
            <label class="vs-input--label">Fecha de último cierre</label>
            <flat-pickr v-if="usuario.id_rol==3" :config="configdateTimePicker" class="w-full" placeholder="fecha de cierre" v-model="fcierre"/>
            <flat-pickr v-else disabled class="w-full" placeholder="fecha de cierre" v-bind:value="fcierre"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':balance=='Si'}">
            <vs-select
              placeholder="Balance consolidado"
              class="selectExample w-full"
              label="Balance Consolidado"
              vs-multiple
              autocomplete
              v-model="balance"
              v-if="usuario.id_rol==3"
            >
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in options3"
              />
            </vs-select>
            <vs-select
              placeholder="Balance consolidado"
              class="selectExample w-full"
              label="Balance Consolidado"
              vs-multiple
              autocomplete
              v-bind:value="balance"
              v-else
              disabled
            >
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.text"
                v-for="(item,index) in options3"
              />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':balance=='Si'}" v-if="balance=='Si'">
            <vs-select
              placeholder="Seleccione Empresa"
              class="selectExample w-full"
              label="Empresas Asociadas"
              vs-multiple
              autocomplete
              v-model="empresa_asociada"
              v-if="usuario.id_rol==3"
            >
              <vs-select-item v-for="data in empresas" :key="data.id_empresa" :value="data.id_empresa" :text="data.nombre_empresa"/>
            </vs-select>
            <vs-select
              placeholder="Seleccione Empresa"
              class="selectExample w-full"
              label="Empresas Asociadas"
              vs-multiple
              autocomplete
              v-bind:value="empresa_asociada"
              v-else
              disabled
            >
              <vs-select-item v-for="data in empresas" :key="data.id_empresa" :value="data.id_empresa" :text="data.nombre_empresa"/>
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':balance=='Si'}">
            <label class="vs-input--label">Ultimo Recalculo</label>
            <flat-pickr class="w-full" v-if="usuario.id_rol==3" :config="configdateTimePicker" v-model="recalculo"/>
            <flat-pickr class="w-full" v-else disabled v-bind:value="recalculo"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3"  v-validate="'numeric'" name="codEntidad" label="Codigo Entidad" v-model="codigo_entidad" />
            <vs-input class="w-full" v-else disabled label="Codigo Entidad" v-bind:value="codigo_entidad" />
            <span class="text-danger text-sm" v-show="errors.has('codEntidad')">Este campo solo lleva numeros</span>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="Contribuyente especial"
              class="selectExample w-full"
              label="Contribuyente Especial"
              vs-multiple
              autocomplete
              v-model="contribuyente"
              v-if="usuario.id_rol==3"
            >
              <vs-select-item value=1 text="Si" />
              <vs-select-item value=0 text="No" />
            </vs-select>
            <vs-select
              placeholder="Contribuyente especial"
              class="selectExample w-full"
              label="Contribuyente Especial"
              vs-multiple
              autocomplete
              v-bind:value="contribuyente"
              v-else
              disabled
            >
              <vs-select-item value=1 text="Si" />
              <vs-select-item value=0 text="No" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              type="text"
              class="w-full"
              label="No. Establecimiento"
              v-model="id_establecimiento"
              v-if="usuario.id_rol==3"
            />
            <vs-input
              type="text"
              class="w-full"
              label="No. Establecimiento"
              v-bind:value="id_establecimiento"
              v-else
              disabled
            />
          </div>
        </div>
        <div class="vx-row chtm" v-if="contribuyente==1">
          <vs-divider border-style="solid" color="dark">Contribuyente especial</vs-divider>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Fecha Resolucion</label>
            <flat-pickr v-if="usuario.id_rol==3" :config="configdateTimePicker" class="w-full" v-model="fresolucion"/>
            <flat-pickr v-else  disabled class="w-full" v-bind:value="fresolucion"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input type="text" v-if="usuario.id_rol==3" v-validate="'numeric'" name="noResolucion" class="w-full" label="No. Resolucion" v-model="noresolucion" />
            <vs-input type="text" v-else  disabled class="w-full" label="No. Resolucion" v-bind:value="noresolucion" />
            <span class="text-danger text-sm" v-show="errors.has('noResolucion')">Este campo solo lleva numeros</span>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              type="text"
              class="w-full"
              label="No. Contribuyente"
              v-model="nocontribuyente"
              v-if="usuario.id_rol==3"
            />
            <vs-input
              type="text"
              class="w-full"
              label="No. Contribuyente"
              v-bind:value="nocontribuyente"
              v-else
              disabled
            />
          </div>
        </div>
        <vs-divider border-style="solid" color="dark">Datos adicionales</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3" name="text"  label="Página web" v-model="pag_web" />
            <vs-input class="w-full" v-else  disabled name="text"  label="Página web" v-bind:value="pag_web" />
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <label class="vs-input--label">Facturación en negativo</label>
            <vs-checkbox class="mt-3" v-if="usuario.id_rol==3" icon-pack="feather" icon="icon-check" v-model="negativo">
              <template v-if="negativo">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | Permitir facturación en negativo
            </vs-checkbox>
            <vs-checkbox class="mt-3" v-else  disabled icon-pack="feather" icon="icon-check" v-bind:value="negativo">
              <template v-if="negativo">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | Permitir facturación en negativo
            </vs-checkbox>
          </div>
        </div>
        <vs-divider border-style="solid" color="dark">Punto de emisión</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3" name="text"  label="Secuencial de Factura" v-model="secuencial_facturape" />
            <vs-input class="w-full" v-else disabled name="text"  label="Secuencial de Factura" v-bind:value="secuencial_facturape" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorrucRepre" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text" v-if="usuario.id_rol==3" label="Secuencial de Nota de crédito" v-model="secuencial_nota_creditope" />
            <vs-input class="w-full" name="text" v-else disabled  label="Secuencial de Nota de crédito" v-bind:value="secuencial_nota_creditope" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_nota_creditope" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text" v-if="usuario.id_rol==3" label="Secuencial de Nota de débito" v-model="secuencial_nota_debitope" />
            <vs-input class="w-full" name="text" v-else disabled label="Secuencial de Nota de débito" v-bind:value="secuencial_nota_debitope" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_nota_debitope" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text"  label="Secuencial de Guia de remisión" v-if="usuario.id_rol==3" v-model="secuencial_guia_remisionpe" />
            <vs-input class="w-full" name="text"  label="Secuencial de Guia de remisión" v-else disabled v-bind:value="secuencial_guia_remisionpe" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_guia_remisionpe" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text" v-if="usuario.id_rol==3" label="Secuencial de Retención" v-model="secuencial_retencionpe" />
            <vs-input class="w-full" name="text" v-else disabled label="Secuencial de Retención" v-bind:value="secuencial_retencionpe" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_retencionpe" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text" v-if="usuario.id_rol==3" label="Secuencial liquidación de compra" v-model="secuencial_liquidacion_comprape" />
            <vs-input class="w-full" name="text" v-else disabled label="Secuencial liquidación de compra" v-bind:value="secuencial_liquidacion_comprape" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_liquidacion_comprape" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Estado de punto de emisión:</label>
            <vs-checkbox icon-pack="feather" v-if="usuario.id_rol==3" class="mt-3" icon="icon-check" v-model="activope">
              <template v-if="activope">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | <span v-if="activope">Activo</span> <span v-else>Inactivo</span>
            </vs-checkbox>
            <vs-checkbox icon-pack="feather" v-else disabled class="mt-3" icon="icon-check" v-bind:value="activope">
              <template v-if="activope">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | <span v-if="activope">Activo</span> <span v-else>Inactivo</span>
            </vs-checkbox>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in erroractivope" :key="index" v-text="err"></span>
            </div>
          </div>
        </div>
        <vs-divider border-style="solid" color="dark">Configuración de facturación electrónica</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3" v-validate="'email'" name="email"  label="Email" v-model="email_empresa" />
            <vs-input class="w-full" v-else disabled label="Email" v-bind:value="email_empresa" />
            <span class="text-danger text-sm" v-show="errors.has('email')">Email no válido</span>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input type="password" v-if="usuario.id_rol==3" class="w-full" label="Contraseña" v-model="password" maxlength="20"/>
            <vs-input type="password" v-else disabled class="w-full" label="Contraseña" v-bind:value="password" maxlength="20"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" v-if="usuario.id_rol==3" label="Servidor Correo" v-model="servidor_correo" />
            <vs-input class="w-full" v-else disabled label="Servidor Correo" v-bind:value="servidor_correo" />
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input type="text" v-if="usuario.id_rol==3" class="w-full" v-validate="'numeric'" name="puerto" label="Puerto" @keypress="solonumeros" v-model="puerto_correo" maxlength="5"/>
            <vs-input type="text" v-else disabled class="w-full" label="Puerto" v-bind:value="puerto_correo"/>
          <span class="text-danger text-sm" v-show="errors.has('puerto')">Este campo solo lleva numeros</span>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Capa de puerto de correo</label>
            <ul class="demo-alignment w-full">
              <li>
                <vs-radio v-if="usuario.id_rol==3" v-model="seguridad_correo" vs-value="ssl">Ssl</vs-radio>
                <vs-radio v-else disabled v-bind:value="seguridad_correo" vs-value="ssl">Ssl</vs-radio>
              </li>
              <li>
                <vs-radio v-if="usuario.id_rol==3" v-model="seguridad_correo" vs-value="tls">Tls</vs-radio>
                <vs-radio v-else disabled v-bind:value="seguridad_correo" vs-value="tls">Tls</vs-radio>
              </li>
              <li>
                <vs-radio v-if="usuario.id_rol==3" v-model="seguridad_correo" vs-value="ninguno">Ninguno</vs-radio>
                <vs-radio v-else disabled v-bind:value="seguridad_correo" vs-value="ninguno">Ninguno</vs-radio>
              </li>
            </ul>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-select
              placeholder="Tipo de emisión"
              class="selectExample w-full"
              label="Tipo de emisión"
              vs-multiple
              autocomplete
              v-model="tipoemision"
              v-if="usuario.id_rol==3"
            >
              <vs-select-item value="1" text="Emisor Normal" />
              <vs-select-item value="2" text="Indisponibilidad del SRI" />
            </vs-select>
            <vs-select
              placeholder="Tipo de emisión"
              class="selectExample w-full"
              label="Tipo de emisión"
              vs-multiple
              autocomplete
              v-bind:value="tipoemision"
              v-else
              disabled
            >
              <vs-select-item value="1" text="Emisor Normal" />
              <vs-select-item value="2" text="Indisponibilidad del SRI" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-select
              placeholder="Ambiente"
              class="selectExample w-full"
              label="Ambiente"
              vs-multiple
              autocomplete
              v-model="ambiente"
              v-if="usuario.id_rol==3"
            >
              <vs-select-item value="1" text="Produción" />
              <vs-select-item value="2" text="Pruebas" />
            </vs-select>
             <vs-select
              placeholder="Ambiente"
              class="selectExample w-full"
              label="Ambiente"
              vs-multiple
              autocomplete
              v-bind:value="ambiente"
              v-else
              disabled
            >
              <vs-select-item value="1" text="Produción" />
              <vs-select-item value="2" text="Pruebas" />
            </vs-select>
          </div>
        </div>
        <vs-divider border-style="solid" color="dark">Configuracion de Usuario Administrador</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input v-if="usuario.id_rol==3" class="w-full" label="Nombres" v-model="nombreusuario"/>
            <vs-input v-else disabled class="w-full" label="Nombres" v-bind:value="nombreusuario"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errornombreusuario" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input v-if="usuario.id_rol==3" class="w-full" label="Apellidos" v-model="apellidousuario"/>
            <vs-input v-else disabled class="w-full" label="Apellidos" v-bind:value="apellidousuario"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorapellidousuario" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input v-if="usuario.id_rol==3" class="w-full" label="Correo electrónico" v-model="emailusuario"/>
            <vs-input v-else disabled class="w-full" label="Correo electrónico" v-bind:value="emailusuario"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in erroremailusuario" :key="index" v-text="err"></span>
            </div>
          </div>
          <div v-if="!$route.params.id && usuario.id_rol==3" class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input v-if="usuario.id_rol==3" class="w-full" label="Contraseña" v-model="passusuario"/>
            <vs-input v-else disabled class="w-full" label="Contraseña" v-bind:value="passusuario"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorrepassusuario" :key="index" v-text="err"></span>
            </div>
          </div>
          <div v-if="!$route.params.id && usuario.id_rol==3" class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input v-if="usuario.id_rol==3" class="w-full" label="Confirmar contraseña" v-model="repassusuario"/>
            <vs-input v-else disabled class="w-full" label="Confirmar contraseña" v-bind:value="repassusuario"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorrepassusuario" :key="index" v-text="err"></span>
            </div>
          </div>
        </div>
        <vs-divider border-style="solid" color="dark">Imagen y Firma P12</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto" v-if="usuario.id_rol==3">
            <label class="vs-input--label">Subir logo</label>
            <div class="vx-col md:w-full w-full mb-6" v-if="!imagen.recupera">
              <div style="display:none">
                <input type="file" class="filepre" accept="image/*" @change="obtenerimagen" />
              </div>
              <div class="verimagen" v-if="imagen.obtener" @click="abrirfile">
                <img :src="imagen.visualizar" class="imagenpre" />
              </div>
              <div class="centimg vx-card input" v-else @click="abrirfile">
                <img src="/images/upload.png" />
                <div style="position:absolute;margin-top:60px;color:#000">Click para subir imagen</div>
              </div>
            </div>
            <div class="vx-col md:w-full w-full mb-6" v-else>
              <div style="display:none">
                <input type="file" class="filepre" accept="image/*" @change="obtenerimagen" />
              </div>
              <div class="verimagen" @click="abrirfile" v-if="!imagen.visualizar" >
                <img :src="imagen.recupera" class="imagenpre" >
              </div>
              <div class="verimagen" v-else @click="abrirfile" >
                <img :src="imagen.visualizar" class="imagenpre" >
              </div>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto" v-else>
            <label class="vs-input--label">Subir logo</label>
            <div class="vx-col md:w-full w-full mb-6" v-if="!imagen.recupera">
              <div style="display:none">
                <input type="file" class="filepre" accept="image/*" @change="obtenerimagen" />
              </div>
              <div class="verimagen" v-if="imagen.obtener">
                <img :src="imagen.visualizar" class="imagenpre" />
              </div>
              <div class="vx-card input" v-else>
                <img style="width: 70%;max-height: 165px;display: inline;" src="/images/sinimagen.png" />
              </div>
            </div>
            <div class="vx-col md:w-full w-full mb-6" v-else>
              <div style="display:none">
                <input type="file" class="filepre" accept="image/*" @change="obtenerimagen" />
              </div>
              <div class="verimagen" v-if="!imagen.visualizar" >
                <img :src="imagen.recupera" class="imagenpre" >
              </div>
              <div class="verimagen" v-else >
                <img :src="imagen.visualizar" class="imagenpre" >
              </div>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6" v-if="usuario.id_rol==3">
            <label class="vs-input--label">Subir firma solo formato p12</label>
            <div class="vx-col md:w-full w-full mb-6" v-if="!firma.recupera">
              <div style="display:none">
                <input type="file" class="fileprefirma" @change="obtenerfirma" />
              </div>
              <vs-button v-if="!firma.obtener" color="primary" type="filled" @click="abrirfirma">No ha ingresado una firma electrónica !Agregar una!</vs-button>
              <vs-button v-else color="primary" type="filled" @click="eliminarfirma">Firma agregado exitosamente con el nombre de: {{firma.nombre}}  !Click para eliminar¡</vs-button>
              <div class="vx-col sm:w-full w-full mb-6" v-if="firma.obtener">
                <label class="vs-input--label">Fecha de expiración</label>
                <flat-pickr :config="configdateTimePicker" class="w-full" placeholder="Fecha de expiración" v-model="fecha_expiracion_firma"/>
                <vs-input type="password" class="w-full" label="Contraseña Firma"  v-model="pass_firma" maxlength="15"/>
              </div>
            </div>
            <div class="vx-col md:w-full w-full mb-6" v-else>
              <div style="display:none">
                <input type="file" class="fileprefirma" @change="obtenerfirma" />
              </div>
              <h6 class="mb-5 mt-5">Nombre del archivo: {{firma.recupera}}</h6>
              <h6 class="mb-5">Ultima actualización: {{actualizacion_firma}}</h6>
              <h6 class="mb-5">Fecha de emisión: {{fecha_firma}}</h6>
              <h6 class="mb-5">Fecha de expiración: {{fecha_expiracion_firma}}</h6>
              <vs-button color="primary" type="filled" @click="eliminarfirma()">Eliminar firma electrónica!</vs-button>
            </div>
          </div>
        </div>
        <div class="vx-row">
        </div>
        <div class="vx-row">
        </div>
        <div class="vx-row" v-if="usuario.id_rol==3">
          <div class="vx-col w-full">
            <div class="vx-col w-full">
            <vs-button color="success" type="filled" @click="guardar()" v-if="!$route.params.id && usuario.id_rol==3">GUARDAR</vs-button>
            <vs-button color="success" type="filled" @click="editar()" v-else>GUARDAR</vs-button>
            <vs-button color="warning" type="filled" @click="borrar()">BORRAR</vs-button>
            <vs-button color="danger" type="filled" @click="cancelar()">CANCELAR</vs-button>
            </div>
          </div>
        </div>
        <vs-popup
          title='Plan Cuentas'
          :active.sync="activePrompt"
        >
          <div class="con-exemple-prompt">
            <vs-input
            class="mb-4 mr-4 w-full"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
            <vs-table stripe v-model="cuentaarray" @selected="handleSelected" :data="contenido">
              <template slot="thead">
                <vs-th>No.Cuenta</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].codcta">
                    {{ data[indextr].codcta  }}
                  </vs-td>
                  <vs-td :data="data[indextr].nomcta">
                    {{ data[indextr].nomcta  }}
                  </vs-td>
                  </vs-tr>
                </template>
              <template slot-scope="{data}">
                <vs-tr :data="datos" :key="index" v-for="(datos,index) in data">
                  <vs-td>{{datos.codcta}}</vs-td>
                  <vs-td v-if="datos.nomcta">{{datos.nomcta}}</vs-td>
                  <vs-td v-else>-</vs-td>
                </vs-tr>
              </template>
            </vs-table>

          </div>
        </vs-popup>
        <vs-popup
          title='Plan Cuentas'
          :active.sync="activePrompt2"
        >
          <div class="con-exemple-prompt">
            <vs-input
            class="mb-4 mr-4 w-full"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
            <vs-table stripe v-model="cuentaarray2" @selected="handleSelected2" :data="contenido">
              <template slot="thead">
                <vs-th>No.Cuenta</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

                  <vs-td :data="data[indextr].codcta">
                    {{ data[indextr].codcta  }}
                  </vs-td>

                  <vs-td :data="data[indextr].nomcta">
                    {{ data[indextr].nomcta  }}
                  </vs-td>
                  </vs-tr>
                </template>
            </vs-table>

          </div>
        </vs-popup>
        <vs-popup
          title='Plan Cuentas'
          :active.sync="activePrompt3"
        >
          <div class="con-exemple-prompt">
            <vs-input
            class="mb-4 mr-4 w-full"
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
                  <vs-td :data="data[indextr].codcta">
                    {{ data[indextr].codcta  }}
                  </vs-td>
                  <vs-td :data="data[indextr].nomcta">
                    {{ data[indextr].nomcta  }}
                  </vs-td>
                  </vs-tr>
                </template>
            </vs-table>

          </div>
        </vs-popup>
        <vs-popup title="Plan Cuentas"
          :active.sync="activePrompt4"
        >
          <div class="con-exemple-prompt">
            <vs-input
            class="mb-4 mr-4 w-full"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
            <vs-table stripe v-model="cuentaarray4" @selected="handleSelected4" :data="contenido">
              <template slot="thead">
                <vs-th>No.Cuenta</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].codcta">
                    {{ data[indextr].codcta  }}
                  </vs-td>
                  <vs-td :data="data[indextr].nomcta">
                    {{ data[indextr].nomcta  }}
                  </vs-td>
                  </vs-tr>
                </template>
            </vs-table>
          </div>
        </vs-popup>
        <vs-popup
          title='Plan Cuentas'
          :active.sync="activePrompt5"
        >
          <div class="con-exemple-prompt">
            <vs-input
            class="mb-4 mr-4 w-full"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
            <vs-table stripe v-model="cuentaarray5" @selected="handleSelected5" :data="contenido">
              <template slot="thead">
                <vs-th>No.Cuenta</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].codcta">
                    {{ data[indextr].codcta  }}
                  </vs-td>
                  <vs-td :data="data[indextr].nomcta">
                    {{ data[indextr].nomcta  }}
                  </vs-td>
                  </vs-tr>
                </template>
            </vs-table>
          </div>
        </vs-popup>
        <vs-popup
          title='Plan Cuentas'
          :active.sync="activePrompt6"
        >
          <div class="con-exemple-prompt">
            <vs-input
            class="mb-4 md:mb-0 mr-4 w-full"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
            <vs-table stripe v-model="cuentaarray6" @selected="handleSelected6" :data="contenido">
              <template slot="thead">
                <vs-th>No.Cuenta</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

                  <vs-td :data="data[indextr].codcta">
                    {{ data[indextr].codcta  }}
                  </vs-td>

                  <vs-td :data="data[indextr].nomcta">
                    {{ data[indextr].nomcta  }}
                  </vs-td>
                  </vs-tr>
                </template>
            </vs-table>

          </div>
        </vs-popup>
        <vs-popup
          title='Plan Cuentas'
          :active.sync="activePrompt7"
        >
          <div class="con-exemple-prompt">
            <vs-input
            class="mb-4 mr-4 w-full"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
            <vs-table stripe v-model="cuentaarray7" @selected="handleSelected7" :data="contenido">
              <template slot="thead">
                <vs-th>No.Cuenta</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

                  <vs-td :data="data[indextr].codcta">
                    {{ data[indextr].codcta  }}
                  </vs-td>

                  <vs-td :data="data[indextr].nomcta">
                    {{ data[indextr].nomcta  }}
                  </vs-td>
                  </vs-tr>
                </template>
            </vs-table>

          </div>
        </vs-popup>
        <vs-popup
          title='Plan Cuentas'
          :active.sync="activePrompt8"
        >
          <div class="con-exemple-prompt">
            <vs-input
            class="mb-4 mr-4 w-full"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
            <vs-table stripe v-model="cuentaarray8" @selected="handleSelected8" :data="contenido">
              <template slot="thead">
                <vs-th>No.Cuenta</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].codcta">
                    {{ data[indextr].codcta  }}
                  </vs-td>
                  <vs-td :data="data[indextr].nomcta">
                    {{ data[indextr].nomcta  }}
                  </vs-td>
                  </vs-tr>
                </template>
            </vs-table>
          </div>
        </vs-popup>
      </vx-card>
    </div>
  </div>
</template>
<script>
import {FormWizard, TabContent} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import Datepicker from 'vuejs-datepicker';
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.min.css';
import {Spanish as SpanishLocale} from 'flatpickr/dist/l10n/es.js';
import { AgGridVue } from "ag-grid-vue";
const $ = require('jquery');
const axios = require("axios");
export default {
  components: {
    AgGridVue,
    Datepicker,
    flatPickr,
    FormWizard,
    TabContent
  },
  computed: {
    imagen2(){
      return this.resultImangen;
    },
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    }
  },
  data() {
    return {
      //datos empresa
      id_recupera: null,
      codigo_empresa: "",
      periodo_empresa: "",
      ruc_empresa: "",
      nombre_empresa: "",
      telefono: "",
      direccion_empresa: "",
      id_provincia: "",
      id_ciudad: "",
      ruc_contador: "",
      nombre_contador: "",
      tipo_identidicacion_empresa: "",
      identificacion_repr: "",
      nomb_representante: "",
      periodo_inicio: "",
            configdateTimePicker: {
              locale: SpanishLocale
            },
            configdateTimePickerPeriodo: {
              locale: SpanishLocale,
              dateFormat: "Y",
            },
      periodo_fin: "",
            configdateTimePicker: {
              locale: SpanishLocale
            },
      clave_duracion: "",
      id_moneda: "",

      mascara: "",
      ctaActivo: "",
      ctaPasivo: "",
      ctaPatrimonio: "",
      ctaIngreso: "",
      ctaCosto: "",
      ctaGasto: "",
      ctaOrden:"",
      ctaResultado:"",
      balance: "",
      empresa_asociada:"",
      //select4:[],
      fcierre: "",
            /*configdateTimePicker: {
              locale: SpanishLocale
            },*/
            configFromdateTimePicker: {
              minDate: new Date(),
              maxDate: null,
              locale: SpanishLocale
            },
      recalculo: "",
            configdateTimePicker: {
              locale: SpanishLocale
            },
      codigo_entidad: "",
      contribuyente: "",
      id_establecimiento: "",
      fresolucion: "",
            configdateTimePicker: {
              locale: SpanishLocale
            },
      noresolucion: "",
      nocontribuyente: "",
      email_empresa: "",
      password: "",
      servidor_correo: "",
      puerto_correo: "",
      seguridad_correo: "ssl",
      logo: "",
      pass_firma: "",
      ruta: "",
      fecha_expiracion_firma:"",
      nombre_comercial:"",
      contabilidad:0,
      options1: [
        { text: "Cedula", value: "Cedula" },
        { text: "Ruc", value: "Ruc" },
        { text: "Pasaporte", value: "Pasaporte" }
      ],
      options2: [{ text: "2020", value: "2020" },{ text: "2019", value: "2019" }, { text: "2018", value: "2018" },
      { text: "2017", value: "2017" }, { text: "2016", value: "2018" },
      { text: "2015", value: "2015" }, { text: "2014", value: "2014" },
      { text: "2013", value: "2013" }, { text: "2012", value: "2012" },
      { text: "2011", value: "2011" }, { text: "2010", value: "2010" },
      { text: "2009", value: "2009" }, { text: "2008", value: "2008" },
      { text: "2007", value: "2007" }, { text: "2006", value: "2006" },
      { text: "2005", value: "2005" }, { text: "2004", value: "2004" },
      { text: "2003", value: "2003" }, { text: "2002", value: "2002" },
      { text: "2001", value: "2001" }, { text: "2000", value: "2000" },
      { text: "1999", value: "1999" }, { text: "1998", value: "1998" }],
      /*options4: [{ text: "Iva", value: 1 }],
      options5: [{ text: "Si", value: 1 }, { text: "No", value: 2 }],*/
      resultImangen:"",
      //variables para ventanas modales
      activePrompt: false,
      activePrompt2: false,
      activePrompt3: false,
      activePrompt4: false,
      activePrompt5: false,
      activePrompt6: false,
      activePrompt7: false,
      activePrompt8: false,
      val: "",
      datas:[],
      //variables formulario plan cuentas
      grupo:[],
      codCta:"",
      nombCta:"",
      moneda2:[],
      grupo2:[],
      codCta2:"",
      nombCta2:"",
      moneda3:[],
      grupo3:[],
      codCta3:"",
      nombCta3:"",
      moneda4:[],
      grupo4:[],
      codCta4:"",
      nombCta4:"",
      moneda5:[],
      grupo5:[],
      codCta5:"",
      nombCta5:"",
      moneda6:[],
      grupo6:[],
      codCta6:"",
      nombCta6:"",
      moneda7:[],
      grupo7:[],
      codCta7:"",
      nombCta7:"",
      moneda8:[],
      grupo8:[],
      codCta8:"",
      nombCta8:"",
      moneda9:[],
      //variables paginacion de las tablas
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
      cantidadp:50,
      offset: 3,
      //buscador
      buscar: "",
      criterio: "codcta",
      //otros valores
      gridApi: null,
      contenido: [],
      //lenguaje
      i18nbuscar:this.$t("i18nbuscar"),
      //variables para traer una columna plan ctas
      cuentaarray:[],
      cuentaarray2:[],
      cuentaarray3:[],
      cuentaarray4:[],
      cuentaarray5:[],
      cuentaarray6:[],
      cuentaarray7:[],
      cuentaarray8:[],
      idResultado:"",
      idOrden:"",
      idActivo:"",
      idPasivo:"",
      idPatrimonio:"",
      idIngreso:"",
      idCosto:"",
      idGasto:"",

      //errores
      error:0,
      errorcodigo: [],
      errorperiodo: [],
      errorruc: [],
      errornombre: [],
      errortelefono: [],
      errordireccion: [],
      errorprovincia: [],
      errorciudad: [],
      errorrucContador: [],
      errornombreContador: [],
      errortipoIdent: [],
      errorrucRepre: [],
      errornombRepre: [],
      errorperiodoInic: [],
      errorperiodoFin: [],
      errordurClave: [],
      errormoneda: [],
      errormascara: [],
      errorctaActivo: [],
      errorctaPasivo: [],
      errorctaPatrimonio: [],
      errorctaIngreso: [],
      errorctaCosto: [],
      errorctaGasto: [],
      errorctaOrden:[],
      errorctaResultado:[],
      errorbalance: [],
      errorfechCierre: [],
      errorultimoRecal: [],
      errorcodEntidad: [],
      errorcontribuyente: [],
      errorestablecimiento: [],
      errorfresolucion: [],
      errornoResolucion: [],
      errornoContribuyente: [],
      erroremail: [],
      errorpassword: [],
      errorservidorCorreo: [],
      errorpuerto: [],
      errorseguridad: [],
      errorlogo: [],
      errorfirma: [],
      errorpassFirma: [],
      errorruta: [],
      errornombre_comercial:[],
      //tabla moneda
      monedas:[],
      //tabla provincia
      provincias:[],
      //tabla ciudad
      ciudades:[],
      //tabla empresa
      empresas:[],
      provs:"",
      imagen:{
        obtener:"",
        nombre:"",
        visualizar:"",
        recupera:null,
      },
      imagenrecupera:[],
      archivorecupera:[],

      firma:{
        obtener:"",
        nombre:"",
        visualizar:"",
        recupera:null,
      },
      options3:[
        {value:'Si', text:'Si'},
        {value:"No",text:"No"}
      ],
      firmarecupera:[],
      archivofirmarecupera:[],
      pag_web:"",
      negativo:0,
      nombrepe:"",
      codigope:"0",
      secuencial_facturape:"0",
      secuencial_nota_creditope:"0",
      secuencial_nota_debitope:"0",
      secuencial_guia_remisionpe:"0",
      secuencial_retencionpe:"0",
      secuencial_liquidacion_comprape:"0",
      activope:1,

      errornombrepe: [],
      errorcodigope: [],
      errorsecuencial_facturape: [],
      errorsecuencial_nota_creditope: [],
      errorsecuencial_nota_debitope: [],
      errorsecuencial_guia_remisionpe: [],
      errorsecuencial_retencionpe: [],
      errorsecuencial_liquidacion_comprape:[],
      erroractivope: [],

      tipoemision: 1,
      ambiente: 1,
      id_establecimiento_id:0,
      id_punto_emision_id:0,

      //usuario
      nombreusuario: "",
      apellidousuario: "",
      emailusuario: "",
      passusuario: "",
      repassusuario: "",
      errornombreusuario: [],
      errorapellidousuario: [],
      erroremailusuario: [],
      errorpassusuario: [],
      errorrepassusuario: [],
      arrayroles:[],
      tiporol:null,
      errortiporol:[],
      id_user:null,
    };
  },
  mounted() {
    this.getProvincias();
    this.listar2();
    if(this.$route.params.id>=1){
      this.listar(1, this.buscar);
    }
    this.getMonedas();
    this.getProvincias()
    this.getCiudades();
    this.getEmpresas();
  },
  methods: {
    onFileSelected2(e){
      let file=e.target.files[0];
      this.firma=file;
      //console.log(file);
    },
    handleSelected(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      this.ctaResultado=`${tr.codcta}`,
      this.idResultado=`${tr.id_ctas}`,
      this.activePrompt=false
    },
    handleSelected2(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      this.ctaOrden=`${tr.codcta}`,
      this.idOrden=`${tr.id_ctas}`,
      this.activePrompt2=false
    },
    handleSelected3(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      this.ctaActivo=`${tr.codcta}`,
      this.idActivo=`${tr.id_ctas}`,
      this.activePrompt3=false
    },
    handleSelected4(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      this.ctaPasivo=`${tr.codcta}`,
      this.idPasivo=`${tr.id_ctas}`,
      this.activePrompt4=false
    },
    handleSelected5(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      this.ctaPatrimonio=`${tr.codcta}`,
      this.idPatrimonio=`${tr.id_ctas}`,
      this.activePrompt5=false
    },
    handleSelected6(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      this.ctaIngreso=`${tr.codcta}`,
      this.idIngreso=`${tr.id_ctas}`,
      this.activePrompt6=false
    },
    handleSelected7(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      this.ctaCosto=`${tr.codcta}`,
      this.idCosto=`${tr.id_ctas}`,
      this.activePrompt7=false
    },
    handleSelected8(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      this.ctaGasto=`${tr.codcta}`,
      this.idGasto=`${tr.id_ctas}`,
      this.activePrompt8=false
    },
    guardar(){
      if (this.validar()) {
        $('html, body').animate({scrollTop:0}, 'slow');
        return;
      }
      axios.post("/api/agregarempresa",{
        codigo_empresa: this.codigo_empresa,
        periodo_empresa: this.periodo_empresa,
        ruc_empresa: this.ruc_empresa,
        nombre_empresa: this.nombre_empresa,
        telefono: this.telefono,
        direccion_empresa: this.direccion_empresa,
        id_provincia: this.id_provincia,
        id_ciudad: this.id_ciudad,
        ruc_contador: this.ruc_contador,
        nombre_contador: this.nombre_contador,
        tipo_identidicacion_empresa: this.tipo_identidicacion_empresa,
        identificacion_repr: this.identificacion_repr,
        nomb_representante: this.nomb_representante,
        periodo_inicio: this.periodo_inicio,
        periodo_fin: this.periodo_fin,
        id_moneda: this.id_moneda,
        clave_duracion: this.clave_duracion,
        fcierre: this.fcierre,
        balance: this.balance,
        empresa_asociada:this.empresa_asociada,
        recalculo: this.recalculo,
        codigo_entidad: this.codigo_entidad,
        contribuyente: this.contribuyente,
        id_establecimiento: this.id_establecimiento,
        fresolucion: this.fresolucion,
        noresolucion: this.noresolucion,
        nocontribuyente: this.nocontribuyente,
        email_empresa: this.email_empresa,
        password: this.password,
        servidor_correo: this.servidor_correo,
        puerto_correo: this.puerto_correo,
        seguridad_correo: this.seguridad_correo,
        pass_firma: this.pass_firma,
        nombre_comercial : this.nombre_comercial,
        contabilidad: this.contabilidad,
        pag_web: this.pag_web,
        negativo: this.negativo,
        nombre: this.nombrepe,
        codigo: this.codigope,
        secuencial_factura: this.secuencial_facturape,
        secuencial_nota_credito: this.secuencial_nota_creditope,
        secuencial_nota_debito: this.secuencial_nota_debitope,
        secuencial_guia_remision: this.secuencial_guia_remisionpe,
        secuencial_retencion: this.secuencial_retencionpe,
        secuencial_liquidacion_compra:this.secuencial_liquidacion_comprape,
        activo: this.activope,
        tipoemision:this.tipoemision,
        ambiente:this.ambiente,
        id_establecimiento_id: this.id_establecimiento_id,
        id_punto_emision_id: this.id_punto_emision_id,

        nombreusuario: this.nombreusuario,
        apellidousuario: this.apellidousuario,
        emailusuario: this.emailusuario,
        passusuario: this.passusuario,
        repassusuario: this.repassusuario,
        id_rol: this.tiporol,
      }).then(res => {
        this.guardarimagen(res.data);
        this.guardarfirma(res.data);
        this.modal=false; 
        this.$vs.notify({
          text: "Registro guardado exitosamente",
          color: "primary"
        });
        this.$router.push('/administrar/empresa').catch(()=>{})
      });
    },
    editar() {
      if (this.validar()) {
        $('html, body').animate({scrollTop:0}, 'slow');
        return;
      }
      axios.put("/api/actualizarempresa", {
        id:this.$route.params.id,
        codigo_empresa: this.codigo_empresa,
        periodo_empresa: this.periodo_empresa,
        ruc_empresa: this.ruc_empresa,
        nombre_empresa: this.nombre_empresa,
        telefono: this.telefono,
        direccion_empresa: this.direccion_empresa,
        id_provincia: this.id_provincia,
        id_ciudad: this.id_ciudad,
        ruc_contador: this.ruc_contador,
        nombre_contador: this.nombre_contador,
        tipo_identidicacion_empresa: this.tipo_identidicacion_empresa,
        identificacion_repr: this.identificacion_repr,
        nomb_representante: this.nomb_representante,
        periodo_inicio: this.periodo_inicio,
        periodo_fin: this.periodo_fin,
        id_moneda: this.id_moneda,
        clave_duracion: this.clave_duracion,
        cta_activo: this.ctaActivo,
        cta_pasivo: this.ctaPasivo,
        cta_patrimonio: this.ctaPatrimonio,
        cta_ingreso: this.ctaIngreso,
        cta_costo: this.ctaCosto,
        cta_gasto: this.ctaGasto,
        cta_orden: this.ctaOrden,
        cta_resultado: this.ctaResultado,
        fcierre: this.fcierre,
        balance: this.balance,
        empresa_asociada:this.empresa_asociada,
        recalculo: this.recalculo,
        codigo_entidad: this.codigo_entidad,
        contribuyente: this.contribuyente,
        id_establecimiento: this.id_establecimiento,
        fresolucion: this.fresolucion,
        noresolucion: this.noresolucion,
        nocontribuyente: this.nocontribuyente,
        email_empresa: this.email_empresa,
        password: this.password,
        servidor_correo: this.servidor_correo,
        puerto_correo: this.puerto_correo,
        seguridad_correo: this.seguridad_correo,
        logo: this.imagen.obtener,
        firma: this.firma.obtener,
        pass_firma: this.pass_firma,
        nombre_comercial: this.nombre_comercial,
        contabilidad: this.contabilidad,
        pag_web: this.pag_web,
        negativo: this.negativo,
        nombre: this.nombrepe,
        codigo: this.codigope,
        secuencial_factura: this.secuencial_facturape,
        secuencial_nota_credito: this.secuencial_nota_creditope,
        secuencial_nota_debito: this.secuencial_nota_debitope,
        secuencial_guia_remision: this.secuencial_guia_remisionpe,
        secuencial_retencion: this.secuencial_retencionpe,
        secuencial_liquidacion_compra:this.secuencial_liquidacion_comprape,
        activo: this.activope,

        tipoemision:this.tipoemision,
        ambiente:this.ambiente,
        id_establecimiento_id: this.id_establecimiento_id,
        id_punto_emision_id: this.id_punto_emision_id,

        nombreusuario: this.nombreusuario,
        apellidousuario: this.apellidousuario,
        emailusuario: this.emailusuario,
        passusuario: this.passusuario,
        repassusuario: this.repassusuario,
        id_rol: this.tiporol,
        id_user: this.id_user,
      }
      ).then(res => {
        console.log(res.data);
        if(res.data.existe!= "existe"){
          if(!this.imagenrecupera.length==0){
          }else{
            this.guardarimagen(res.data);
          }
          if(!this.firmarecupera.length==0){
          }else{
            this.guardarfirma(res.data);
          }

          this.$vs.notify({
            text: "Registro editado exitosamente",
            color: "primary"
          });
          this.$router.push('/administrar/empresa').catch(()=>{})
       }else{
         this.$vs.notify({
            title:'Cuenta Incorrecta',
            text: 'La cuenta contable ' + res.data.error + ' no existe',
            color:'danger',
          })
       }
        /*
          if(res.data != "existe"){
          //if(this.imagenrecupera.length){
            this.guardarimagen(res.data);
         // }
          //if(this.firmarecupera.length){
            this.guardarfirma(res.data);
         // }
          this.$vs.notify({ 
            title:'Registro Editado',
            text: 'Registro Editado exitosamente',
            color:'success',
          })
          this.$router.push('/administrar/empresa').catch(()=>{})
       }else{
         this.$vs.notify({
            title:'Cuenta Incorrecta',
            text: 'Una Cuenta Contable esta incorrecta',
            color:'danger',
          })
       }*/
      }).catch(err => {
          console.log(err);
      });
    },
    validar(){
      this.error=0;
      this.errorperiodo= [];
      this.errorruc= [];
      this.errornombre= [];
      this.errortelefono= [];
      this.errordireccion= [];
      this.errorprovincia= [];
      this.errorciudad= [];
      this.errorrucContador= [];
      this.errornombreContador= [];
      this.errortipoIdent= [];
      this.errorrucRepre= [];
      this.errornombRepre= [];
      this.errorperiodoInic= [];
      this.errorperiodoFin= [];
      this.errordurClave= [];
      this.errormoneda= [];
      /*this.errormascara= [];
      this.errorctaActivo= [];
      this.errorctaPasivo= [];
      this.errorctaPatrimonio= [];
      this.errorctaIngreso= [];
      this.errorctaCosto= [];
      this.errorctaGasto= [];
      this.errorctaOrden=[];
      this.errorctaResultado=[];*/
      this.errorbalance= [];
      this.errorfechCierre= [];
      this.errorultimoRecal= [];
      this.errorcodEntidad= [];
      this.errorcontribuyente= [];
      this.errorestablecimiento= [];
      this.errorfresolucion= [];
      this.errornoResolucion= [];
      this.errornoContribuyente= [];
      this.erroremail= [];
      this.errorpassword= [];
      this.errorservidorCorreo= [];
      this.errorpuerto= [];
      this.errorseguridad= [];
      this.errorlogo= [];
      this.errorfirma= [];
      this.errorpassFirma= [];
      this.errorruta= [];
      this.errorrucRepre= [];
      this.errornombre_comercial = [];

      this.errorsecuencial_facturape = [];
      this.errorsecuencial_nota_creditope = [];
      this.errorsecuencial_nota_debitope = [];
      this.errorsecuencial_guia_remisionpe = [];
      this.errorsecuencial_retencionpe = [];
      this.errorsecuencial_liquidacion_comprape = [];
      this.erroractivope = [];

      this.errornombreusuario = [];
      this.errorapellidousuario = [];
      this.erroremailusuario = [];
      this.errorpassusuario = [];
      this.errorrepassusuario =[];

      if(!this.nombreusuario){
        this.errornombreusuario.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.apellidousuario){
        this.errorapellidousuario.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.emailusuario){
        this.erroremailusuario.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.$route.params.id){
        if(!this.passusuario){
          this.errorpassusuario.push("Este campo es obligatorio");
          this.error = 1;
        }
        if(this.passusuario != this.repassusuario){
          this.errorrepassusuario.push("Las contraseñas no coinciden");
          this.error = 1;
        }
      }

      if(!this.secuencial_liquidacion_comprape){
        this.errorsecuencial_liquidacion_comprape.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.nombre_comercial){
        this.errornombre_comercial.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.secuencial_facturape){
        this.errorsecuencial_facturape.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.secuencial_nota_creditope){
        this.errorsecuencial_nota_creditope.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.secuencial_nota_debitope){
        this.errorsecuencial_nota_debitope.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.secuencial_guia_remisionpe){
        this.errorsecuencial_guia_remisionpe.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.secuencial_retencionpe){
        this.errorsecuencial_retencionpe.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.activope){
        this.erroractivope.push("Este campo es obligatorio");
        this.error = 1;
      }

      if(!this.nombre_comercial){
        this.errornombre_comercial.push("Este campo es obligatorio");
        this.error = 1;
      }
      if(!this.ruc_empresa){
        this.errorruc.push("Campo es obligatorio");
        this.error=1;
      }
      if(this.contabilidad==1){
        if(!this.ruc_contador){
          this.errorrucContador.push("Este campo es obligatorio");
          this.error=1;
        }
      }

      if(!this.nombre_empresa){
        this.errornombre.push("Este campo es obligatorio");
        this.error=1;
      }
      if(!this.direccion_empresa){
        this.errordireccion.push("Este campo es obligatorio");
        this.error=1;
      }
      if(!this.id_provincia){
        this.errorprovincia.push("Este campo es obligatorio");
        this.error=1;
      }
      if(!this.id_ciudad){
        this.errorciudad.push("Este campo es obligatorio");
        this.error=1;
      }
      if(!this.tipo_identidicacion_empresa){
        this.errortipoIdent.push("Este campo es obligatorio");
        this.error=1;
      }
      return this.error;
    },
    cancelar(){
      this.$router.push("/administrar/empresa");
    },
    borrar(){
      this.codigo_empresa= "002",
      this.periodo_empresa= "",
      this.ruc_empresa= "",
      this.nombre_empresa= "",
      this.telefono= "",
      this.direccion_empresa= "",
      this.id_provincia= "",
      this.id_ciudad= "",
      this.ruc_contador= "",
      this.nombre_contador= "",
      this.tipo_identidicacion_empresa= "",
      this.identificacion_repr= "",
      this.nomb_representante= "",
      this.periodo_inicio= "",
      this.periodo_fin= "",
      this.clave_duracion= "",
      this.id_moneda= "",
      //this.mascara= "",
      this.ctaActivo= "",
      this.ctaPasivo= "",
      this.ctaPatrimonio= "",
      this.ctaIngreso= "",
      this.ctaCosto= "",
      this.ctaGasto= "",
      this.ctaOrden="",
      this.ctaResultado="",
      this.balance= "",
      this.empresa_asociada="",
     // this.//select4=[],
      this.fcierre= "",
      this.recalculo= "",
      this.codigo_entidad= "",
      this.contribuyente= "",
      this.id_establecimiento= "",
      this.fresolucion= "",
      this.noresolucion= "",
      this.nocontribuyente= "",
      this.email_empresa= "",
      this.password= "",
      this.servidor_correo= "",
      this.puerto_correo= "",
      this.seguridad_correo= "ssl",
      this.logo= "",
      this.firma= "",
      this.pass_firma= "",
      this.ruta= ""
      this.pag_web = "";
      this.negativo = 0;
    },
    listar(page, buscar) {
      let me = this;
      var url = "/api/cuentas/" + this.usuario.id_empresa + "?page=" +page +"&buscar=" +buscar;
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
    listar2(){
      if(this.$route.params.id){
        var url = "/api/abrirempresa/"+this.$route.params.id;
        axios.get(url).then( res => {
          let data = res.data[0];
          this.codigo_empresa = data.codigo_empresa;
          this.nombre_comercial=data.razon_social;
          this.periodo_empresa=data.periodo_empresa;
          this.ruc_empresa=data.ruc_empresa;
          this.nombre_empresa=data.nombre_empresa ;
          this.telefono=data.telefono;
          this.direccion_empresa=data.direccion_empresa;
          this.id_provincia=data.id_provincia;
          this.id_ciudad=data.id_ciudad;
          this.ruc_contador=data.ruc_contador;
          this.nombre_contador=data.nombre_contador;
          this.tipo_identidicacion_empresa=data.tipo_identidicacion_empresa;
          this.identificacion_repr=data.identificaion_rep;
          this.nomb_representante=data.nomb_representante;
          this.periodo_inicio=data.periodo_inicio;
          this.periodo_fin=data.periodo_fin;
          this.id_moneda=data.id_moneda;
          // mascara_empresa=data.mascara
          this.clave_duracion=data.clave_duracion;
          this.ctaActivo=data.cta_activo;
          //this.idActivo=data.cta_activo
          this.ctaPasivo= data.cta_pasivo;
          //this.idPasivo=data.cta_pasivo
          this.ctaPatrimonio= data.cta_patrimonio;
          //this.idPatrimonio=data.cta_patrimonio
          this.ctaIngreso= data.cta_ingreso;
          //this.idIngreso=data.cta_ingreso
          this.ctaCosto= data.cta_costo;
          //this.idCosto=data.cta_costo
          this.ctaGasto= data.cta_gasto;
          //this.idGasto=data.cta_gasto
          this.ctaOrden= data.cta_orden;
          //this.idOrden=data.cta_orden
          this.ctaResultado= data.cta_resultado;
          //this.idResultado=data.cta_resultado
          this.fcierre=data.fcierre;
          this.balance=data.balance;
          this.empresa_asociada=data.empresa_asociada;
          this.recalculo=data.recalculo;
          this.codigo_entidad=data.codigo_entidad;
          this.contribuyente=data.contribuyente;
          this.id_establecimiento=data.id_establecimiento;
          this.fresolucion=data.fresolucion;
          this.noresolucion=data.noresolucion;
          this.nocontribuyente=data.nocontribuyente;
          this.email_empresa=data.email_empresa;
          this.password=data.password;
          this.servidor_correo=data.servidor_correo;
          this.puerto_correo=data.puerto_correo;
          this.seguridad_correo=data.seguridad_correo;
          if(data.logo){
            this.imagen.recupera="/empresas/"+this.$route.params.id+"/imagen/"+data.logo ;
          }
          if(data.firma){
            this.firma.recupera=data.firma ;
          }
          this.pass_firma=data.pass_firma ;
          this.ruta=data.ruta;
          this.fecha_firma = data.fecha_firma;
          this.actualizacion_firma = data.actualizacion_firma;
          this.fecha_expiracion_firma = data.fecha_expiracion_firma;
          this.id_establecimiento_id = data.id_establecimiento_id;
          this.id_punto_emision_id = data.id_punto_emision_id;

          this.pag_web = data.urlweb;

          this.secuencial_facturape = data.secuencial_factura;
          this.secuencial_nota_creditope = data.secuencial_nota_credito;
          this.secuencial_nota_debitope = data.secuencial_nota_debito;
          this.secuencial_guia_remisionpe = data.secuencial_guia_remision;
          this.secuencial_retencionpe = data.secuencial_retencion;
          this.secuencial_liquidacion_comprape = data.secuencial_liquidacion_compra;

          this.nombreusuario = data.nombresuser;
          this.apellidousuario = data.apellidosuser;
          this.emailusuario = data.emailuser;
          this.id_user = data.iduser;
        });
      }else if(this.usuario.id_rol!==3){
        var url = "/api/abrirempresa/"+this.usuario.id_empresa;
        axios.get(url).then( res => {
          let data = res.data[0];
          this.codigo_empresa = data.codigo_empresa;
          this.nombre_comercial=data.razon_social;
          this.periodo_empresa=data.periodo_empresa;
          this.ruc_empresa=data.ruc_empresa;
          this.nombre_empresa=data.nombre_empresa ;
          this.telefono=data.telefono;
          this.direccion_empresa=data.direccion_empresa;
          this.id_provincia=data.id_provincia;
          this.id_ciudad=data.id_ciudad;
          this.ruc_contador=data.ruc_contador;
          this.nombre_contador=data.nombre_contador;
          this.tipo_identidicacion_empresa=data.tipo_identidicacion_empresa;
          this.identificacion_repr=data.identificaion_rep;
          this.nomb_representante=data.nomb_representante;
          this.periodo_inicio=data.periodo_inicio;
          this.periodo_fin=data.periodo_fin;
          this.id_moneda=data.id_moneda;
          // mascara_empresa=data.mascara
          this.clave_duracion=data.clave_duracion;
          this.ctaActivo=data.cta_activo;
          //this.idActivo=data.cta_activo
          this.ctaPasivo= data.cta_pasivo;
          //this.idPasivo=data.cta_pasivo
          this.ctaPatrimonio= data.cta_patrimonio;
          //this.idPatrimonio=data.cta_patrimonio
          this.ctaIngreso= data.cta_ingreso;
          //this.idIngreso=data.cta_ingreso
          this.ctaCosto= data.cta_costo;
          //this.idCosto=data.cta_costo
          this.ctaGasto= data.cta_gasto;
          //this.idGasto=data.cta_gasto
          this.ctaOrden= data.cta_orden;
          //this.idOrden=data.cta_orden
          this.ctaResultado= data.cta_resultado;
          //this.idResultado=data.cta_resultado
          this.fcierre=data.fcierre;
          this.balance=data.balance;
          this.empresa_asociada=data.empresa_asociada;
          this.recalculo=data.recalculo;
          this.codigo_entidad=data.codigo_entidad;
          this.contribuyente=data.contribuyente;
          this.id_establecimiento=data.id_establecimiento;
          this.fresolucion=data.fresolucion;
          this.noresolucion=data.noresolucion;
          this.nocontribuyente=data.nocontribuyente;
          this.email_empresa=data.email_empresa;
          this.password=data.password;
          this.servidor_correo=data.servidor_correo;
          this.puerto_correo=data.puerto_correo;
          this.seguridad_correo=data.seguridad_correo;
          if(data.logo){
            this.imagen.recupera="/empresaimagenes/"+this.$route.params.id+"/imagen/"+data.logo ;
          }
          if(data.firma){
            this.firma.recupera=data.firma ;
          }
          this.pass_firma=data.pass_firma ;
          this.ruta=data.ruta;
          this.fecha_firma = data.fecha_firma;
          this.actualizacion_firma = data.actualizacion_firma;
          this.fecha_expiracion_firma = data.fecha_expiracion_firma;
          this.id_establecimiento_id = data.id_establecimiento_id;
          this.id_punto_emision_id = data.id_punto_emision_id;

          this.pag_web = data.urlweb;

          this.secuencial_facturape = data.secuencial_factura;
          this.secuencial_nota_creditope = data.secuencial_nota_credito;
          this.secuencial_nota_debitope = data.secuencial_nota_debito;
          this.secuencial_guia_remisionpe = data.secuencial_guia_remision;
          this.secuencial_retencionpe = data.secuencial_retencion;
          this.secuencial_liquidacion_comprape = data.secuencial_liquidacion_compra;

          this.nombreusuario = data.nombresuser;
          this.apellidousuario = data.apellidosuser;
          this.emailusuario = data.emailuser;
          this.id_user = data.iduser;
        });
      }else{
        this.$route.params.id = null;
      }
    },
    getMonedas: function(){
      axios.get('/api/moneda')
      .then(function (response) {
          this.monedas = response.data;
      }.bind(this));

    },
    getEmpresas: function(){
      axios.get('/api/empresasasoc')
      .then(function (response) {
          this.empresas = response.data;
      }.bind(this));

    },
    getProvincias: function(){
      axios.get('/api/provincia')
      .then(function (response) {
          this.provincias = response.data;
          this.provs==this.id_provincia
      }.bind(this));
    },
    getCiudades: function(){
      axios.get('/api/ciudad',{
        params: {
            id_provincia: this.id_provincia
          }

      }).then(function (response) {
        console.log("hi");
        //console.log("provincia",this.id_provs,"s");
          this.ciudades = response.data;

      }.bind(this));
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    obtenerimagen(e) {
      let file = e.target.files[0];
      var allowedExtensions = /(.jpg|.jpeg|.png|.webp)$/i;
      if (!allowedExtensions.exec(file.name)) {
        this.$vs.notify({
          title: "Tipo de archivo no compatible",
          text: "Formatos aceptados: .jpg, .jpeg, .png",
          color: "danger"
        });
        return;
      }
      this.imagen.obtener = file;
      this.imagenrecupera = file;
      this.cargarimagen(file);
    },
    cargarimagen(file) {
      let reader = new FileReader();

      reader.onload = e => {
        this.imagen.visualizar = e.target.result;
      };

      reader.readAsDataURL(file);
    },
    abrirfile() {
      $(".filepre").click();
    },
    obtenerarchivo(e) {
      let file = e.target.files[0];
      var allowedExtensions = /(.jpg|.jpeg|.png)$/i;
      if (!allowedExtensions.exec(file.name)) {
        this.$vs.notify({
          title: "Tipo de archivo no compatible",
          text: "Formatos aceptados: .jpg, .jpeg, .png",
          color: "danger"
        });
        return;
      }
      this.archivo.obtener = file;
      this.archivorecupera = file;
      this.cargararchivo(file);
    },
    obtenerfirma(e) {
      let file = e.target.files[0];
      var allowedExtensions = /(.pfx|.p12)$/i;
      if (!allowedExtensions.exec(file.name)) {
        this.$vs.notify({
          title: "Tipo de archivo no compatible",
          text: "Solo se acepta archivos P12",
          color: "danger"
        });
        return;
      }
      this.firma.nombre = file.name;
      this.firma.obtener = file;
      this.firmarecupera = file;
    },
    cargararchivo(file) {
      let reader = new FileReader();

      reader.onload = e => {
        this.archivo.visualizar = e.target.result;
      };

      reader.readAsDataURL(file);
    },
    abrirfile2() {
      $(".filepre2").click();
    },
    abrirfirma() {
      $(".fileprefirma").click();
    },
    guardarimagen(id){
      if(this.imagenrecupera.length){
        let formData = new FormData();
        formData.append('id', id);
        formData.append('file', this.imagenrecupera);
        axios.post('/api/guardarimgempresa',formData);

        formData.append('id', id);
        formData.append('file', this.imagenrecupera);
        axios.post('/api/guardarimgempresa1',formData);
      }
    },
    guardarfirma(id){
      if(this.firmarecupera.length){
        let formData = new FormData();
        formData.append('id', id);
        formData.append('file', this.firmarecupera);
        formData.append('fecha', this.fecha_expiracion_firma);
        formData.append('pass_firma', this.pass_firma);
        axios.post('/api/guardarfirmaempresa',formData);
        axios.post('/api/guardarfirmaempresa1',formData);
      }
    },
    eliminarfirma(){
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Eliminar firma`,
        text: `¿Seguro desea eliminar esta firma electrónica?:`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.sieliminarfirma
      });
    },
    sieliminarfirma(){
      var id =this.$route.params.id
      axios.delete("/api/eliminarfirma/" + id);
       this.$vs.notify({
          color: "danger",
          title: "Firma eliminada",
          text: "Su firma electrónica fue eliminada exitosamente"
      });
      this.firma = {
        obtener:"",
        nombre:"",
        visualizar:"",
        recupera:null,
      };
    },
    solonumeros ($event) {
      let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
      if (keyCode < 48 || keyCode > 57) { // 46 is dot
          $event.preventDefault();
      }
    },
    solodecimales(){
      let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
          $event.preventDefault();
      }
    },
    validarruc($event){
      this.errorruc = [];
      var numero = this.ruc_empresa;
      var suma = 0;
      var residuo = 0;
      var pri = false;
      var pub = false;
      var nat = false;
      var numeroProvincias = 22;
      var modulo = 11;
      var ok=1;
      var d1 = numero.substr(0,1);
      var d2 = numero.substr(1,1);
      var d3 = numero.substr(2,1);
      var d4 = numero.substr(3,1);
      var d5 = numero.substr(4,1);
      var d6 = numero.substr(5,1);
      var d7 = numero.substr(6,1);
      var d8 = numero.substr(7,1);
      var d9 = numero.substr(8,1);
      var d10 = numero.substr(9,1);

      if (d3==7 || d3==8){
      this.errorruc.push("El tercer dígito ingresado es inválido");
      this.error=1;
      return false;
      }

      if (d3 < 6){
      nat = true;
      p1 = d1 * 2; if (p1 >= 10) p1 -= 9;
      p2 = d2 * 1; if (p2 >= 10) p2 -= 9;
      p3 = d3 * 2; if (p3 >= 10) p3 -= 9;
      p4 = d4 * 1; if (p4 >= 10) p4 -= 9;
      p5 = d5 * 2; if (p5 >= 10) p5 -= 9;
      p6 = d6 * 1; if (p6 >= 10) p6 -= 9;
      p7 = d7 * 2; if (p7 >= 10) p7 -= 9;
      p8 = d8 * 1; if (p8 >= 10) p8 -= 9;
      p9 = d9 * 2; if (p9 >= 10) p9 -= 9;
      modulo = 10;
      }

      else if(d3 == 6){
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
      }

      else if(d3 == 9) {
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

      var digitoVerificador = residuo==0 ? 0: modulo - residuo;

      if (pub==true){
      if (digitoVerificador != d9){
      this.errorruc.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if ( numero.substr(9,4) != '0001' ){
      this.errorruc.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      else if(pri == true){
      if (digitoVerificador != d10){
      this.errorruc.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if ( numero.substr(10,3) != '001' ){
      this.errorruc.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }

      else if(nat == true){
      if (digitoVerificador != d10){
      this.errorruc.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if (numero.length <14 && numero.substr(10,12) != '001' ){
      this.errorruc.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      return true;
    },
    validarrucrepresentante($event){
      this.errorrucRepre = [];
      var numero = this.identificacion_repr;
      var suma = 0;
      var residuo = 0;
      var pri = false;
      var pub = false;
      var nat = false;
      var numeroProvincias = 22;
      var modulo = 11;
      var ok=1;
      var d1 = numero.substr(0,1);
      var d2 = numero.substr(1,1);
      var d3 = numero.substr(2,1);
      var d4 = numero.substr(3,1);
      var d5 = numero.substr(4,1);
      var d6 = numero.substr(5,1);
      var d7 = numero.substr(6,1);
      var d8 = numero.substr(7,1);
      var d9 = numero.substr(8,1);
      var d10 = numero.substr(9,1);
      if (d3==7 || d3==8){
      this.errorrucRepre.push("El tercer dígito ingresado es inválido");
      this.error=1;
      return false;
      }
      if (d3 < 6){
      nat = true;
      p1 = d1 * 2; if (p1 >= 10) p1 -= 9;
      p2 = d2 * 1; if (p2 >= 10) p2 -= 9;
      p3 = d3 * 2; if (p3 >= 10) p3 -= 9;
      p4 = d4 * 1; if (p4 >= 10) p4 -= 9;
      p5 = d5 * 2; if (p5 >= 10) p5 -= 9;
      p6 = d6 * 1; if (p6 >= 10) p6 -= 9;
      p7 = d7 * 2; if (p7 >= 10) p7 -= 9;
      p8 = d8 * 1; if (p8 >= 10) p8 -= 9;
      p9 = d9 * 2; if (p9 >= 10) p9 -= 9;
      modulo = 10;
      }
      else if(d3 == 6){
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
      }
      else if(d3 == 9) {
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
      var digitoVerificador = residuo==0 ? 0: modulo - residuo;
      if (pub==true){
      if (digitoVerificador != d9){
      this.errorrucRepre.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if ( numero.substr(9,4) != '0001' ){
      this.errorrucRepre.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      else if(pri == true){
      if (digitoVerificador != d10){
      this.errorrucRepre.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if ( numero.substr(10,3) != '001' ){
      this.errorrucRepre.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      else if(nat == true){
      if (digitoVerificador != d10){
      this.errorrucRepre.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if (numero.length <14 && numero.substr(10,12) != '001' ){
      this.errorrucRepre.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      return true;
    },
    validarruccontador($event){
      this.errorrucContador = [];
      var numero = this.ruc_contador;
      var suma = 0;
      var residuo = 0;
      var pri = false;
      var pub = false;
      var nat = false;
      var numeroProvincias = 22;
      var modulo = 11;
      var ok=1;
      var d1 = numero.substr(0,1);
      var d2 = numero.substr(1,1);
      var d3 = numero.substr(2,1);
      var d4 = numero.substr(3,1);
      var d5 = numero.substr(4,1);
      var d6 = numero.substr(5,1);
      var d7 = numero.substr(6,1);
      var d8 = numero.substr(7,1);
      var d9 = numero.substr(8,1);
      var d10 = numero.substr(9,1);
      if (d3==7 || d3==8){
      this.errorrucContador.push("El tercer dígito ingresado es inválido");
      this.error=1;
      return false;
      }
      if (d3 < 6){
      nat = true;
      p1 = d1 * 2; if (p1 >= 10) p1 -= 9;
      p2 = d2 * 1; if (p2 >= 10) p2 -= 9;
      p3 = d3 * 2; if (p3 >= 10) p3 -= 9;
      p4 = d4 * 1; if (p4 >= 10) p4 -= 9;
      p5 = d5 * 2; if (p5 >= 10) p5 -= 9;
      p6 = d6 * 1; if (p6 >= 10) p6 -= 9;
      p7 = d7 * 2; if (p7 >= 10) p7 -= 9;
      p8 = d8 * 1; if (p8 >= 10) p8 -= 9;
      p9 = d9 * 2; if (p9 >= 10) p9 -= 9;
      modulo = 10;
      }
      else if(d3 == 6){
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
      }
      else if(d3 == 9) {
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
      var digitoVerificador = residuo==0 ? 0: modulo - residuo;
      if (pub==true){
      if (digitoVerificador != d9){
      this.errorrucContador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if ( numero.substr(9,4) != '0001' ){
      this.errorrucContador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      else if(pri == true){
      if (digitoVerificador != d10){
      this.errorrucContador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if ( numero.substr(10,3) != '001' ){
      this.errorrucContador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      else if(nat == true){
      if (digitoVerificador != d10){
      this.errorrucContador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if (numero.length <14 && numero.substr(10,12) != '001' ){
      this.errorrucContador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      return true;
    },
    validarrepresentante($event){
      this.errorrucRepre = [];
      var cad = this.identificacion_repr;
      var total = 0;
      var longitud = cad.length;
      var longcheck = longitud - 1;
      for(var i = 0; i < longcheck; i++){
        if (i%2 === 0) {
          var aux = cad.charAt(i) * 2;
          if (aux > 9) aux -= 9;
          total += aux;
        } else {
          total += parseInt(cad.charAt(i)); // parseInt o concatenará en lugar de sumar
        }
      }
      total = total % 10 ? 10 - total % 10 : 0;

      if (cad.substring(0,10).charAt(longitud-1) == total) {
        this.errorrucRepre=[];
      }else{
        this.errorrucRepre.push("Cédula inválida");
        this.error = 1;
        return;
      }
    },
  }
};
</script>
<style>
  input[type=”file”]#nuestroinput {
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
  background-color: #106BA0;
  display: inline-block;
  transition: all .5s;
  cursor: pointer;
  padding: 15px 40px !important;
  text-transform: uppercase;
  width: fit-content;
  text-align: center;
  }
  .imagenpre{
    height: 100%;
    cursor:pointer;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
  }
  .centimg{
    height: 225px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius:20px;
    background: rgba(255,255,255,.8)!important;
    -webkit-transition: all .3s ease;
  -moz-transition: all .3s ease;
  -ms-transition: all .3s ease;
  -o-transition: all .3s ease;
  transition: all .3s ease;
  }

  .verimagen{
      overflow: hidden;
      padding: 0px;
      height: 300px;
      height: 300px;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      border-radius: 20px;
      background: rgba(255,255,255,.8)!important;
      -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
    border: 5px solid rgba(0,0,0,.3);
  }

  .imagenpre:hover{
    -moz-transform: scale(1.03);
    -webkit-transform: scale(1.03);
    -o-transform: scale(1.03);
    -ms-transform: scale(1.03);
    transform: scale(1.03);
  }

  .centimg:hover{
    background: rgba(255,255,255,.6)!important;
    cursor: pointer;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
  }

  .centimg img{
    max-width: 100%;
    max-height: 100px;
    cursor: pointer;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
  }
  .demo-alignment > * {
      margin-right: 1.5rem;
      margin-top: 0.8rem;
  }
  .text-danger{
    font-size:13px;
  }
  .vs-dialog{
    max-width: 900px!important;
  }
</style>

