
<template>
  <vx-card title="Agregar Producto">
    <div class="vx-row">
      <div class="vx-col md:w-2/3 w-full mb-6">
        <div class="vx-row">
          <div class="vx-col md:w-1/4 sm:w-full w-full mb-6">
            <vs-input class="w-full" label="Código Alterno:" v-model="cod_alterno" />
          </div>
          <div class="vx-col md:w-3/4 sm:w-full w-full mb-6">
            <vs-input class="w-full" label="Nombre:" v-model="nombre" />
            <div v-show="error" v-if="!nombre">
              <div v-for="err in errornombre" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          <div class="vx-col w-1/3">
            <vs-input class="w-full" label="Código de Barras:" v-model="cod_barras" />
          </div>
          <div class="vx-col w-1/3">
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class="mb-base">
              <vs-input class="w-full" v-model="cta_prod" @keypress="solonumeros($event) " />
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary" @click="popupActive=true">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col w-1/3">
            <label class="vs-input--label">Fórmula de Producción</label>
            <vx-input-group class="mb-base">
              <vs-input disabled class="w-full" v-model="form_prod" />
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary" @click="popupformprod=true">Añadir</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-3/3 w-full mb-3">
            <label class="vs-input--label">Descripción:</label>
            <vs-textarea v-model="descripcion" rows="2" />
          </div>
        </div>
      </div>
      <div class="vx-col md:w-1/3 w-full mb-6" v-if="!imagen.recupera">
        <div style="display:none">
          <input type="file" class="filepre" @change="obtenerimagen" />
        </div>
        <div class="verimagen" v-if="imagen.obtener" @click="abrirfile">
          <img :src="imagen.visualizar" class="imagenpre" />
        </div>
        <div class="centimg vx-card input" v-else @click="abrirfile">
          <img src="/images/upload.png" />
          <div style="position:absolute;margin-top:60px;color:#000">Click para subir imagen</div>
        </div>
      </div>
      <div class="vx-col md:w-1/3 w-full mb-6" v-else>
        <div style="display:none">
          <input type="file" class="filepre" @change="obtenerimagen" />
        </div>
        <div class="verimagen" @click="abrirfile" v-if="!imagen.visualizar">
          <img :src="'/images/productos/'+imagen.recupera" class="imagenpre" />
        </div>
        <div class="verimagen" v-else @click="abrirfile">
          <img :src="imagen.visualizar" class="imagenpre" />
        </div>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3">
        <label class="vs-input--label">Características:</label>
        <vs-textarea v-model="caracteristicas" rows="2" />
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3">
        <label class="vs-input--label">Normativa:</label>
        <vs-textarea v-model="normativa" rows="2" />
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-3">
        <label class="vs-input--label">Uso:</label>
        <vs-textarea v-model="uso" rows="2" />
      </div>
      <!--
      <vs-divider border-style="solid" color="dark">
        <span @click="octl()">Campos adicionales</span>
        <vs-button
          v-if="usuario.id_rol==1"
          color="primary"
          style="margin-left: 9px;padding: 8px 20px;"
          type="border"
          @click="agregarcampo()"
        >agregar</vs-button>
      </vs-divider>
      <div class="vx-col sm:w-full w-full mb-6">
        <div class="vx-row" v-if="contenidocamposadicionales.length>=1 && ocult==true">
          <div
            class="vx-col mb-3 relativo"
            :class="{'w-full':contenidocamposadicionales.length==1, 'md:w-1/2':contenidocamposadicionales.length==2, 'md:w-1/3':contenidocamposadicionales.length>=3}"
            v-for="(add,index) in contenidocamposadicionales"
            v-bind:key="index"
          >
            <vs-button
              v-if="usuario.id_rol==1"
              color="danger"
              type="gradient"
              class="iconelim"
              @click="quitarcampo(index)"
            >x</vs-button>
            <vs-input
              v-if="usuario.id_rol==1"
              class="w-full mb-2"
              label="Nombre del campo"
              v-model="add.nombre"
            />
            <vs-input
              v-else
              class="w-full mb-2"
              label="Nombre del campo"
              v-bind:value="add.nombre"
            />
            <label class="vs-input--label">Contenido del campo</label>
            <vs-textarea v-model="add.descripcion" rows="2" />
          </div>
        </div>
      </div>-->
      <vs-divider border-style="solid" color="dark">Datos generales</vs-divider>
      <div class="vx-col sm:w-1/2 w-full mb-6">
        <vs-select
          placeholder="Buscar línea de producto"
          class="selectExample w-full"
          label="Línea de Producto:"
          label-placeholder="Línea de Producto"
          vs-multiple
          autocomplete
          v-model="linea_producto"
          @change="listartipo(linea_producto);tipo_producto=''"
        >
          <vs-select-item
            :key="res.id_linea_producto"
            :value="res.id_linea_producto"
            :text="res.nombre"
            v-for="res in contenidolinea"
          />
        </vs-select>
        <div v-show="error" v-if="!linea_producto">
          <div v-for="err in errorlinea" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-6" v-if="linea_producto">
        <template v-if="contenidotipo.length!=0">
          <vs-select
            placeholder="Buscar tipo"
            class="selectExample w-full"
            label="Tipo de Producto:"
            label-placeholder="Tipo de Producto"
            vs-multiple
            autocomplete
            v-model="tipo_producto"
          >
            <vs-select-item
              :key="res.id_tipo_producto"
              :value="res.id_tipo_producto"
              :text="res.nombre"
              v-for="res in contenidotipo"
            />
          </vs-select>
        </template>
        <template v-else>
          <vs-input
            class="w-full"
            label="Tipo de Producto:"
            value="Esta linea de producto no contiene tipo"
            disabled
          ></vs-input>
        </template>
        <div v-show="error" v-if="!tipo_producto">
          <div v-for="err in errortipo" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-6" v-else>
        <vs-input
          class="w-full"
          label="Tipo de Producto:"
          value="Debes escoger linea de producto"
          disabled
        ></vs-input>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-6">
        <vs-select
          placeholder="Buscar marca"
          class="selectExample w-full"
          label="Marca:"
          label-placeholder="Marca"
          vs-multiple
          autocomplete
          v-model="marca"
        >
          <vs-select-item
            :key="res.id_marca"
            :value="res.id_marca"
            :text="res.nombre"
            v-for="res in contenidomarca"
          />
        </vs-select>
        <div v-show="error" v-if="!marca">
          <div v-for="err in errormarca" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-6">
        <vs-select
          placeholder="Buscar modelo"
          class="selectExample w-full"
          label="Modelo:"
          label-placeholder="Modelo"
          vs-multiple
          autocomplete
          v-model="modelo"
        >
          <vs-select-item
            :key="res.id_modelo"
            :value="res.id_modelo"
            :text="res.nombre"
            v-for="res in contenidomodelo"
          />
        </vs-select>
        <div v-show="error" v-if="!modelo">
          <div v-for="err in errormodelo" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-6">
        <vs-select
          placeholder="Buscar presentación"
          class="selectExample w-full"
          label="Presentación:"
          label-placeholder="Presentación"
          vs-multiple
          autocomplete
          v-model="presentacion"
        >
          <vs-select-item
            :key="res.id_presentacion"
            :value="res.id_presentacion"
            :text="res.nombre"
            v-for="res in contenidopresentacion"
          />
        </vs-select>
        <div v-show="error" v-if="!presentacion">
          <div v-for="err in errorpresentacion" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div
        class="vx-col w-full mb-6"
        :class="{'sm:w-1/3':sector != 'Servicio','sm:w-1/4':sector == 'Servicio'}"
      >
        <vs-select
          placeholder="Seleccione Sector"
          class="selectExample w-full"
          label="Producto o Servicio:"
          label-placeholder="Producto o Servicio"
          vs-multiple
          v-model="sector"
        >
          <vs-select-item value="Producto" text="Producto" />
          <vs-select-item value="Servicio" text="Servicio" />
        </vs-select>
        <div v-show="error" v-if="!sector">
          <div v-for="err in errorsector" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div
        class="vx-col w-full mb-6"
        :class="{'sm:w-1/3':sector != 'Servicio','sm:w-1/4':sector == 'Servicio'}"
        v-if="sector == 'Servicio'"
      >
        <vs-select
          placeholder="Seleccione tipo de servicio"
          class="selectExample w-full"
          label="Tipo de Servcio:"
          label-placeholder="Tipo de Servcio"
          vs-multiple
          v-model="tipo_servicio"
        >
          <vs-select-item value="Compra" text="Compra" />
          <vs-select-item value="Venta" text="Venta" />
        </vs-select>
        <div v-show="error" v-if="!tipo_servicio">
          <div v-for="err in errortipo_servicio" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div
        class="vx-col w-full mb-6"
        :class="{'sm:w-1/3':sector != 'Servicio','sm:w-1/4':sector == 'Servicio'}"
      >
        <vs-select
          placeholder="Seleccione estado:"
          class="selectExample w-full"
          label="Estado:"
          label-placeholder="Estado"
          vs-multiple
          autocomplete
          v-model="estado"
        >
          <vs-select-item
            :key="index"
            :value="item.value"
            :text="item.text"
            v-for="(item,index) in options1"
          />
        </vs-select>
        <div v-show="error" v-if="!estado">
          <div v-for="err in errorestado" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div
        class="vx-col w-full mb-6"
        :class="{'sm:w-1/3':sector != 'Servicio','sm:w-1/4':sector == 'Servicio'}"
      >
        <label class="vs-input--label">Vencimiento:</label>
        <flat-pickr
          class="w-full"
          :config="configdateTimePicker"
          v-model="vencimiento"
          placeholder="Elegir Fecha"
        />
      </div>
      <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-6">
        <label class="vs-input--label">Existencia Máxima:</label>
        <vs-input-number
          v-model="existencia_max"
          icon-inc="expand_less"
          icon-dec="expand_more"
          style="height: 36px;margin: 1px;"
        />
      </div>
      <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-6">
        <label class="vs-input--label">Existencia Mínima:</label>
        <vs-input-number
          v-model="existencia_min"
          icon-inc="expand_less"
          icon-dec="expand_more"
          style="height: 36px;margin: 1px;"
        />
      </div>
      <vs-divider border-style="solid" color="dark">Dimensiones del Producto</vs-divider>

      <div class="vx-col sm:w-1/2 w-full mb-6">
        <vs-select
          placeholder="Tipo de Medida"
          class="selectExample w-full"
          label="Tipo de Medida:"
          label-placeholder="Tipo de Medida"
          vs-multiple
          autocomplete
          v-model="tipo_medida"
          @change="listarunidadmedidar(tipo_medida);unidad_medida=''"
        >
          <vs-select-item
            :key="res.id_tipo_medida"
            :value="res.id_tipo_medida"
            :text="res.nombre"
            v-for="res in contenidotipomedida"
          />
        </vs-select>
        <div v-show="error" v-if="!tipo_medida">
          <div v-for="err in errortipo_medida" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-6" v-if="tipo_medida">
        <vs-select
          placeholder="Unidad de Medida"
          class="selectExample w-full"
          label="Unidad de Medida:"
          label-placeholder="Unidad de Medida"
          vs-multiple
          autocomplete
          v-model="unidad_medida"
        >
          <vs-select-item
            :key="res.id_unidad_medida"
            :value="res.id_unidad_medida"
            :text="res.nombre"
            v-for="res in contenidounidadmedidar"
          />
        </vs-select>
        <div v-show="error" v-if="!unidad_medida">
          <div v-for="err in errorunidad_medida" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-6" v-else>
        <vs-input
          class="w-full"
          label="Unidad de Medida:"
          value="Debes escoger un tipo de medida"
          disabled
        ></vs-input>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-6" v-if="tipo_medida ==1">
        <vs-input
          class="w-full"
          label="Ingrese Número de Unidad:"
          v-model="numero_unidad"
          maxlength="17"
        />
        <div v-show="error" v-if="!numero_unidad">
          <div v-for="err in errornumero_unidad" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-6" v-else>
        <vs-input
          class="w-full"
          label="Ingrese Número de Unidad:"
          v-model="numero_unidad"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
        <div v-show="error" v-if="!numero_unidad">
          <div v-for="err in errornumero_unidad" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-6" v-if="tipo_medida">
        <vs-select
          placeholder="Seleccione unidad"
          class="selectExample w-full"
          label="Unidad de Salida:"
          label-placeholder="Unidad de Salida:"
          vs-multiple
          v-model="unidad_salida"
        >
          <vs-select-item
            :key="res.id_unidad_medida"
            :value="res.id_unidad_medida"
            :text="res.nombre"
            v-for="res in contenidounidadmedidar"
          />
        </vs-select>
        <div v-show="error" v-if="!unidad_salida">
          <div v-for="err in errorunidad_salida" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-6" v-else>
        <vs-input
          class="w-full"
          label="Unidad de Salida:"
          value="Debes escoger un tipo de medida"
          disabled
        ></vs-input>
      </div>
      <div
        class="vx-col sm:w-1/3 w-full mb-6"
        style="margin-top: 20px; margin-bottom: 0.2rem !important;"
      >
        <vs-checkbox icon-pack="feather" icon="icon-check" v-model="vehiculo">
          <template v-if="vehiculo">
            <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
          </template>
          <template v-else>
            <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
          </template>
          | Vehiculo
        </vs-checkbox>
      </div>
      <template v-if="vehiculo">
        <vs-divider border-style="solid" color="dark">Datos de Vehiculo</vs-divider>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="Placa:" v-model="placa" />
          <div v-show="error" v-if="!placa">
            <div v-for="err in errorplaca" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="País Origen:" v-model="pais_origen" />
          <div v-show="error" v-if="!pais_origen">
            <div v-for="err in errorpais_origen" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="Año Fabricación:" v-model="ano_fabricacionv" />
          <div v-show="error" v-if="!ano_fabricacionv">
            <div v-for="err in errorano_fabricacionv" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="Color:" v-model="color" />
          <div v-show="error" v-if="!color">
            <div v-for="err in errorcolor" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="Carrocería:" v-model="carroceria" />
          <div v-show="error" v-if="!carroceria">
            <div v-for="err in errorcarroceria" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-select
            placeholder="Combustible"
            class="selectExample w-full"
            label="Combustible:"
            label-placeholder="Combustible"
            vs-multiple
            autocomplete
            v-model="combustible"
          >
            <vs-select-item
              :key="index"
              :value="item.value"
              :text="item.text"
              v-for="(item,index) in options3"
            />
          </vs-select>
          <div v-show="error" v-if="!combustible">
            <div v-for="err in errorcombustible" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="Motor:" v-model="motor" />
          <div v-show="error" v-if="!motor">
            <div v-for="err in errormotor" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="Cilindraje:" v-model="cilindraje" />
          <div v-show="error" v-if="!cilindraje">
            <div v-for="err in errorcilindraje" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="Chasis:" v-model="chasis" />
          <div v-show="error" v-if="!chasis">
            <div v-for="err in errorchasis" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="Clase:" v-model="clase" />
          <div v-show="error" v-if="!clase">
            <div v-for="err in errorclase" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <vs-input class="w-full" label="Subclase:" v-model="subclase" />
          <div v-show="error" v-if="!subclase">
            <div v-for="err in errorsubclase" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-6">
          <label class="vs-input--label">Número de Pasajeros</label>
          <vs-input-number
            v-model="numero_pasajeros"
            icon-inc="expand_less"
            icon-dec="expand_more"
            style="height: 36px;margin: 1px;"
          />
          <div v-show="error" v-if="!numero_pasajeros">
            <div v-for="err in errornumero_pasajeros" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
      </template>
      <vs-divider border-style="solid" color="dark">Rubros del Producto</vs-divider>
      <div class="vx-col sm:w-1/2 w-full mb-6">
        <vs-select
          placeholder="Seleccione IVA"
          class="selectExample w-full"
          label="IVA:"
          label-placeholder="IVA:"
          vs-multiple
          v-model="iva"
        >
          <vs-select-item
            :key="res.id_iva"
            :value="res.id_iva"
            :text="res.nombre"
            v-for="res in contenidoiva"
          />
        </vs-select>
        <div v-show="error" v-if="!iva">
          <div v-for="err in erroriva" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-6">
        <vs-select
          placeholder="Seleccione ICE"
          class="selectExample w-full"
          label="ICE:"
          label-placeholder="ICE:"
          vs-multiple
          v-model="ice"
        >
          <vs-select-item
            :key="res.id_ice"
            :value="res.id_ice"
            :text="res.nombre"
            v-for="res in contenidoice"
          />
        </vs-select>
        <div v-show="error" v-if="!ice">
          <div v-for="err in errorice" :key="err" v-text="err" class="text-danger"></div>
        </div>
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          label="Arancel AD-VALOREM:"
          v-model="arancel_advalorem"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          label="Arancel Especifico :"
          v-model="arancel_especifico"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          label="Arancel FODINFA :"
          v-model="arancel_fodinfa"
          placeholder
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          label="Comisión:"
          v-model="comision"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          label="Salvaguardia:"
          v-model="salvaguardia"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          type="text"
          label="PVP(Precio 1):"
          v-model="precio1"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          label="Precio 2:"
          v-model="precio2"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          label="Precio 3:"
          v-model="precio3"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          label="Precio 4:"
          v-model="precio4"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/5 w-full mb-6">
        <vs-input
          class="w-full"
          label="Precio 5 (Referencial):"
          v-model="precio5"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-6">
        <vs-input
          class="w-full"
          label="Descuento (%):"
          v-model="descuento"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-6">
        <vs-input
          class="w-full"
          label="Utilidad (%):"
          v-model="utilidad"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-6">
        <label class="vs-input--label">Fecha de Fabricación:</label>
        <flat-pickr
          class="w-full"
          :config="configdateTimePicker"
          v-model="fecha_fabricacion"
          placeholder="Elegir Fecha"
        />
      </div>
      <div class="vx-col sm:w-1/1 w-full mb-6">Historial de Costos</div>
      <div class="vx-col sm:w-1/4 w-full mb-6">
        <vs-input
          class="w-full"
          label="Ultimo Costo:"
          v-model="ultimo_costo"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/4 w-full mb-6">
        <vs-input
          class="w-full"
          label="Costo Promedio:"
          v-model="costo_promedio"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/4 w-full mb-6">
        <vs-input
          class="w-full"
          label="Costo Total:"
          v-model="costo_total"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col sm:w-1/4 w-full mb-6">
        <vs-input
          class="w-full"
          label="Existencia Total:"
          v-model="existencia_total"
          @keypress="solonumeros($event)"
          maxlength="17"
        />
      </div>
      <div class="vx-col w-full">
        <vs-button color="success" type="border" @click="guardar()" v-if="!idrecupera">Guardar</vs-button>
        <vs-button color="success" type="border" @click="editar()" v-else>Guardar</vs-button>
        <vs-button color="warning" type="border" @click="cerrar()">Borrar</vs-button>
        <vs-button color="danger" type="border" to="/inventario/catalogo">Cancelar</vs-button>
      </div>
    </div>
    <!-- Popup cuenta contable -->
    <vs-popup title="Seleccione una Cuenta Contable" :active.sync="popupActive">
      <div class="con-exemple-prompt">
        <vs-input
          class="mb-4 md:mb-0 mr-4 w-full"
          v-model="buscar1"
          @keyup="listarcuenta(1,buscar1)"
          v-bind:placeholder="i18nbuscar"
        />
        <vs-table stripe v-model="cuentaarray3" @selected="handleSelected" :data="contenidocuenta">
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
    <!-- Popup formula de Produccion -->
    <vs-popup title="Seleccione una Fórmula de Producción" :active.sync="popupformprod">
      <div class="con-exemple-prompt">
        <div class="flex mb-4">
          <div class="w-1/5 mr-2">
            <vs-input
              class="w-full"
              v-model="buscar"
              @keyup="listarformula(1,buscar)"
              v-bind:placeholder="i18nbuscar"
            />
          </div>
          <div class="w-2/5">
            <vs-button
              color="primary"
              type="border"
              @click="popucreaformprod=true"
            >Crear Nueva Fórmula</vs-button>
          </div>
        </div>
        <vs-table stripe @selected="selectformula" :data="contenidoformula">
          <template slot="thead">
            <vs-th>No.Fórmula</vs-th>
            <vs-th>Nombre Fórmula</vs-th>
          </template>
          <template slot-scope="{data}">
            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
              <vs-td :data="data[indextr].codigo_produccion">{{ data[indextr].codigo_produccion }}</vs-td>

              <vs-td :data="data[indextr].nombre_form">{{ data[indextr].nombre_form }}</vs-td>
            </vs-tr>
          </template>
        </vs-table>
      </div>
      <!-- Popup Crear Formula de Produccion -->
      <vs-popup title="Crear Nueva Fórmula de Producción" :active.sync="popucreaformprod">
        <div class="vx-row">
          <vs-divider position="left">Información de Fórmula</vs-divider>
          <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
            <vs-input
              disabled
              class="w-full txt-center"
              label="Código de Producción:"
              v-model="cod_pro"
            />
            <div v-show="error1" v-if="!cod_pro">
              <div v-for="err in errorcod_pro" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>

          <div class="vx-col md:w-3/5 sm:w-full w-full mb-6" id="sa">
            <vs-input class="w-full" label="Nombre de Fórmula:" v-model="nom_pro" />
            <div v-show="error1" v-if="!nom_pro">
              <div v-for="err in errornom_pro" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          <vs-divider />
          <vs-divider position="left">Productos a Producir</vs-divider>
          <!--Inbicio Agregar Productos-->
          <!-- INVOICE TASKS TABLE -->
          <a
            class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
            @click="abrirproductos()"
          >Añadir Productos</a>
          <div class="vx-col md:w-full sm:w-full w-full mb-6">
            <vs-table :data="continprod">
              <template slot="thead">
                <vs-th>Código Producto</vs-th>
                <vs-th class="text-center">Nombre Producto</vs-th>
                <vs-th class="text-center">
                  <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
                </vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td
                    style="width:30%!important;"
                    :data="data[indextr].cod_principal"
                  >{{ data[indextr].cod_principal }}</vs-td>

                  <vs-td
                    class="text-center"
                    style="width:50%!important;"
                    :data="data[indextr].nombre"
                  >{{ data[indextr].nombre }}</vs-td>

                  <vs-td class="text-center" style="width=20%!important;">
                    <vx-tooltip text="Eliminar" position="top" style="display: inline-flex;">
                      <feather-icon
                        icon="TrashIcon"
                        svgClasses="w-5 h-5 hover:text-danger stroke-current"
                        class="pointer"
                        @click="eliminarp(indextr)"
                      />
                    </vx-tooltip>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>

          <!-- Inicio Agregar Ingredientes-->
          <vs-divider />
          <vs-divider position="left">Ingredientes de Producto</vs-divider>
          <!-- INVOICE TASKS TABLE -->
          <a
            class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
            @click="abriringredientes()"
          >Añadir Ingredientes</a>
          <div v-show="error1" v-if="!contingred.length">
            <div v-for="err in erroringred" :key="err" v-text="err" class="text-danger"></div>
          </div>
          <div class="vx-col md:w-full sm:w-full w-full mb-6">
            <vs-table :data="contingred">
              <template slot="thead">
                <vs-th>Código Producto</vs-th>
                <vs-th class="text-center">Nombre Ingrediente</vs-th>
                <vs-th class="text-center">Catidad por Unidad de Formula</vs-th>
                <vs-th class="text-center">
                  <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
                </vs-th>
              </template>

              <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td
                    style="width:20%!important;"
                    :data="data[indextr].cod_principal"
                  >{{ data[indextr].cod_principal }}</vs-td>

                  <vs-td
                    class="text-center"
                    style="width:50%!important;"
                    :data="data[indextr].nombre"
                  >{{ data[indextr].nombre }}</vs-td>

                  <vs-td
                    class="text-center"
                    style="width:20%!important;"
                    :data="data[indextr].cant_form"
                  >
                    <vs-input
                      class="w-full txt-center"
                      placeholder="0.000000"
                      v-model="tr.cant_form"
                      onkeypress="return filterFloat(event, this)"
                    />
                    <div v-show="error1" v-if="!tr.cant_form">
                      <div
                        v-for="err in tr.errorcant_form"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                      ></div>
                    </div>
                  </vs-td>

                  <vs-td class="text-center" style="width=10!important;">
                    <vx-tooltip text="Eliminar" position="top" style="display: inline-flex;">
                      <feather-icon
                        icon="TrashIcon"
                        svgClasses="w-5 h-5 hover:text-danger stroke-current"
                        class="pointer"
                        @click="eliminari(indextr)"
                      />
                    </vx-tooltip>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
          <div class="vx-col w-full">
            <vs-button color="success" type="border" @click="guardarformula()">Guardar</vs-button>
            <vs-button color="danger" type="border" @click="cancelarformula()">Cancelar</vs-button>
          </div>
          <!-- Popup Agregar Producto-->
          <vs-popup
            classContent="popup-example"
            title="Seleccione el Producto"
            :active.sync="popupprod"
          >
            <div class="vx-col w-full" v-if="tipot==1">
              <vs-input
                class="mb-4 mr-4 w-full"
                v-model="buscarp"
                @keyup="listarp(1,buscarp)"
                v-bind:placeholder="i18nbuscar"
              />
              <vs-table
                stripe
                v-model="contenidoingred"
                @selected="handleSelectedp"
                :data="arrayingrediente"
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
            </div>
            <div class="vx-col w-full" v-if="tipot==2">
              <vs-input
                class="mb-4 mr-4 w-full"
                v-model="buscarp"
                @keyup="listarp(1,buscarp)"
                v-bind:placeholder="i18nbuscar"
              />
              <vs-table
                stripe
                v-model="contenidoingred"
                @selected="handleSelectedi"
                :data="arrayingrediente"
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
            </div>
          </vs-popup>
        </div>
      </vs-popup>
    </vs-popup>
  </vx-card>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import VueUploadMultipleImage from "vue-upload-multiple-image";
import moment from "moment";
const $ = require("jquery");
const axios = require("axios");
export default {
  data() {
    return {
      //cuenta contable listar
      contenidocuenta: [],
      popupActive: false,
      contenidoformula: [],
      formu_prod: "",
      popupformprod: false,
      popupprod: false,
      popucreaformprod: false,
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
      cantidadp1: 1000000,
      offset: 3,
      gridApi: null,
      contenido: [],

      //buscador
      buscar: "",
      buscar1: "",
      criterio1: "codcta",
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //variables para traer una columna plan ctas
      cuentaarray3: [],

      idrecupera: null,
      //Agregar Producto
      mirar: "",
      cod_principal: "",
      cod_alterno: "",
      nombre: "",
      nombrec: "",
      cod_barras: "",
      cta_prod: "",
      form_prod: "",
      descripcion: "",
      caracteristicas: "",
      normativa: "",
      uso: "",
      //Línea de Producto:
      //Línea de Producto: fk
      linea_producto: "",
      //tipo_producto fk listar
      tipo_producto: "",
      marca: "",
      modelo: "",
      presentacion: "",

      sector: "",
      tipo_servicio: "",
      unidad_salida: "",
      vencimiento: "",
      existencia_max: 1,
      existencia_min: 1,
      //Dimensiones del Producto:
      tipo_medida: "",
      unidad_medida: "",
      numero_unidad: "",
      estado: "",
      numero_serie: "",
      //estado controla si y no
      //VEHICULO
      vehiculo: false,
      placa: "",
      pais_origen: "",
      ano_fabricacionv: "",
      color: "",
      carroceria: "",
      combustible: "",
      motor: "",
      cilindraje: "",
      chasis: "",
      clase: "",
      subclase: "",
      numero_pasajeros: 1,
      //Rubros del Producto:
      iva: "",
      ice: "",
      arancel_advalorem: "",
      arancel_especifico: "",
      arancel_fodinfa: "",
      comision: "",
      salvaguardia: "",
      precio1: "",
      precio2: "",
      precio3: "",
      precio4: "",
      precio5: "",
      descuento: "",
      utilidad: "",
      fecha_fabricacion: moment().format("YYYY-M-D"),
      ultimo_costo: "",
      costo_promedio: "",
      costo_total: "",
      existencia_total: "",
      update: "",
      /**
       * Errores Validaciones variables
       * @variables
       */
      error: 0,
      errornombre: [],
      errorcodpri: [],
      errorcodalt: [],
      errorcodbarras: [],
      errorcodcta: [],
      errorlinea: [],
      errortipo: [],
      errormarca: [],
      errormodelo: [],
      errorpresentacion: [],

      errorsector: [],
      errortipo_servicio: [],
      errorunidad_salida: [],
      errortipo_medida: [],
      errorunidad_medida: [],
      errornumero_unidad: [],
      errorestado: [],

      errorplaca: [],
      errorpais_origen: [],
      errorano_fabricacionv: [],
      errorcolor: [],
      errorcarroceria: [],
      errorcombustible: [],
      errormotor: [],
      errorcilindraje: [],
      errorchasis: [],
      errorclase: [],
      errorsubclase: [],
      errornumero_pasajeros: [],

      erroriva: [],
      errorice: [],

      //establece calendario español
      configdateTimePicker: {
        locale: SpanishLocale
      },

      options1: [
        { text: "Activo", value: 1 },
        { text: "Inactivo", value: 2 }
      ],

      options2: [
        { text: "ubicacio1", value: 1 },
        { text: "ubicacion2", value: 2 }
      ],

      options3: [
        { text: "Gasolina 98", value: 1 },
        { text: "Gasolina 95", value: 2 },
        { text: "Bioetanol", value: 3 },
        { text: "Diésel normal", value: 4 },
        { text: "Diésel Plus", value: 5 },
        { text: "Biodiésel", value: 6 },
        { text: "Diésel 1D,2D, 4D", value: 7 },
        { text: "Gas Natural", value: 8 }
      ],

      //fotos: [],

      agregados: [],
      contenidoarray: [],
      contenidolinea: [],
      contenidotipo: [],
      contenidomarca: [],
      contenidomodelo: [],

      contenidopresentacion: [],
      contenidotipomedida: [],
      contenidounidadmedida: [],
      contenidounidadmedidar: [],
      contenidocamposadicionales: [],
      contenidoiva: [],
      contenidoice: [],
      ocult: false,
      imagen: {
        obtener: "",
        nombre: "",
        visualizar: "",
        recupera: null
      },
      imagenrecupera: [],
      contenidocamposadicionalesbet: [],

      //Variables para crear formula de produccion
      cod_pro: "",
      nom_pro: "",
      prod_produ: "",
      cant_form: "",
      //buscar
      buscarp: "",
      i18nbuscar: this.$t("i18nbuscar"),
      //arrays formula
      arrayingrediente: [],
      arrayprodprodu: [],
      contenidoingred: [],
      continprod: [],
      contingred: [],
      tipot: 0,

      //validacion formula produccion
      error1: 0,
      errorcod_pro: [],
      errornom_pro: [],

      erroringred: [],
      errorprod_produ: [],
      errorcant_form: []
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
  //importa calendario español
  components: {
    flatPickr,
    VueUploadMultipleImage
  },
  methods: {
    atras() {
      this.$router.push("/inventario/catalogo").catch(() => {});
    },
    agregarcampo() {
      this.ocult = true;
      if (this.contenidocamposadicionales.length < 6) {
        this.contenidocamposadicionales.push({ nombrec: "", contenido: "" });
      } else {
        this.$vs.notify({
          title: "Error al Agregar Campo",
          text: "No se puede agregar mas de 6 campos",
          color: "danger"
        });
      }
    },
    quitarcampo(x) {
      this.contenidocamposadicionales.splice(x, 1);
    },
    successUpload() {
      this.$vs.notify({
        color: "success",
        title: "Upload Success",
        text: "Lorem ipsum dolor sit amet, consectetur"
      });
    },
    /**
     * guardar los datos del formulario catalogo mediante
     * axios
     * @ funcion guardar
     * @metod
     *    axios envio de datos
     *    post
     */
    guardar() {
      if (this.validar()) {
        return;
      }
      axios
        .post("/api/guardarproductos", {
          //Agregar Producto
          cod_principal: this.cod_principal,
          cod_alterno: this.cod_alterno,
          nombre: this.nombre,
          cod_barras: this.cod_barras,
          cta_prod: this.cta_prod,
          form_prod: this.form_prod,
          descripcion: this.descripcion,
          caracteristicas: this.caracteristicas,
          normativa: this.normativa,
          uso: this.uso,
          // Campos adicionales
          agregados: this.contenidocamposadicionales,
          nombrec: this.nombrec,
          contenido: this.contenido,
          //Línea de Producto:
          //Línea de Producto: fk
          linea_producto: this.linea_producto,
          //tipo_producto fk listar
          tipo_producto: this.tipo_producto,
          marca: this.marca,
          modelo: this.modelo,
          presentacion: this.presentacion,
          sector: this.sector,
          tipo_servicio: this.tipo_servicio,
          unidad_salida: this.unidad_salida,
          vencimiento: this.vencimiento,
          existencia_max: this.existencia_max,
          existencia_min: this.existencia_min,
          //Dimensiones del Producto:
          tipo_medida: this.tipo_medida,
          unidad_medida: this.unidad_medida,
          numero_unidad: this.numero_unidad,
          estado: this.estado,
          numero_serie: this.numero_serie,

          //VEHICULO
          vehiculo: this.vehiculo,
          placa: this.placa,
          pais_origen: this.pais_origen,
          ano_fabricacion: this.ano_fabricacionv,
          color: this.color,
          carroceria: this.carroceria,
          combustible: this.combustible,
          motor: this.motor,
          cilindraje: this.cilindraje,
          chasis: this.chasis,
          clase: this.clase,
          subclase: this.subclase,
          numero_pasajeros: this.numero_pasajeros,
          //Rubros del Producto:
          iva: this.iva,
          ice: this.ice,
          arancel_advalorem: this.arancel_advalorem,
          arancel_especifico: this.arancel_especifico,
          arancel_fodinfa: this.arancel_fodinfa,
          comision: this.comision,
          salvaguardia: this.salvaguardia,
          precio1: this.precio1,
          precio2: this.precio2,
          precio3: this.precio3,
          precio4: this.precio4,
          precio5: this.precio5,
          descuento: this.descuento,
          utilidad: this.utilidad,
          fecha_fabricacion: this.fecha_fabricacion,
          ultimo_costo: this.ultimo_costo,
          costo_promedio: this.costo_promedio,
          costo_total: this.costo_total,
          existencia_total: this.existencia_total,
          id_empresa: this.usuario.id_empresa,
          id_rol: this.usuario.id_rol,

          //formula de produccion
          id_formu_prod: this.formu_prod
        })
        .then(res => {
          this.guardarimagen(res.data);
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.$router.push("/inventario/catalogo");
        })
        .catch(err => {
          console.log(err);
        });
    },
    editar() {
      if (this.validar()) {
        return;
      }
      axios
        .put("/api/actualizarproductos", {
          //Agregar Producto
          id: this.$route.params.id,
          //Agregar Producto
          cod_principal: this.cod_principal,
          cod_alterno: this.cod_alterno,
          nombre: this.nombre,
          cod_barras: this.cod_barras,
          cta_prod: this.cta_prod,
          form_prod: this.form_prod,
          descripcion: this.descripcion,
          caracteristicas: this.caracteristicas,
          normativa: this.normativa,
          uso: this.uso,
          // Campos adicionales
          agregados: this.contenidocamposadicionales,
          nombrec: this.nombrec,
          contenido: this.contenido,
          //Línea de Producto:
          //Línea de Producto: fk
          linea_producto: this.linea_producto,
          //tipo_producto fk listar
          tipo_producto: this.tipo_producto,
          marca: this.marca,
          modelo: this.modelo,
          presentacion: this.presentacion,

          sector: this.sector,
          tipo_servicio: this.tipo_servicio,
          unidad_salida: this.unidad_salida,
          vencimiento: this.vencimiento,
          existencia_max: this.existencia_max,
          existencia_min: this.existencia_min,
          //Dimensiones del Producto:
          tipo_medida: this.tipo_medida,
          unidad_medida: this.unidad_medida,
          numero_unidad: this.numero_unidad,
          estado: this.estado,
          numero_serie: this.numero_serie,

          //VEHICULO
          vehiculo: this.vehiculo,
          placa: this.placa,
          pais_origen: this.pais_origen,
          ano_fabricacion: this.ano_fabricacionv,
          color: this.color,
          carroceria: this.carroceria,
          combustible: this.combustible,
          motor: this.motor,
          cilindraje: this.cilindraje,
          chasis: this.chasis,
          clase: this.clase,
          subclase: this.subclase,
          numero_pasajeros: this.numero_pasajeros,
          //Rubros del Producto:
          iva: this.iva,
          ice: this.ice,
          arancel_advalorem: this.arancel_advalorem,
          arancel_especifico: this.arancel_especifico,
          arancel_fodinfa: this.arancel_fodinfa,
          comision: this.comision,
          salvaguardia: this.salvaguardia,
          precio1: this.precio1,
          precio2: this.precio2,
          precio3: this.precio3,
          precio4: this.precio4,
          precio5: this.precio5,
          descuento: this.descuento,
          utilidad: this.utilidad,
          fecha_fabricacion: this.fecha_fabricacion,
          ultimo_costo: this.ultimo_costo,
          costo_promedio: this.costo_promedio,
          costo_total: this.costo_total,
          existencia_total: this.existencia_total,
          id_empresa: this.usuario.id_empresa,
          id_rol: this.usuario.id_rol,

          //formula de produccion
          id_formu_prod: this.formu_prod
        })
        .then(res => {
          if (this.imagenrecupera != null) {
            this.guardarimagen(res.data);
          }
          this.$vs.notify({
            title: "Registro Actualizado",
            text: "Registro actualizado con éxito",
            color: "success"
          });
          this.$router.push("/inventario/catalogo").catch(() => {});
        })
        .catch(err => {});
    },
    /**
     * listar funcion para llamar datos, para editar
     *@ funcion listar
     */
    listar() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        var url = "/api/abrirproductos";
        axios
          .put(url, { id: this.idrecupera })
          .then(res => {
            let data = res.data.recupera[0];
            this.cod_principal = data.cod_principal;
            this.cod_alterno = data.cod_alterno;
            this.nombre = data.nombre;
            this.cod_barras = data.codigo_barras;
            this.descripcion = data.descripcion;
            this.caracteristicas = data.caracteristicas;
            this.normativa = data.normativa;
            this.uso = data.uso;

            this.cta_prod = data.cuenta_contable;
            this.form_prod = data.form_prod;
            this.imagen.recupera = data.imagen;
            axios.get("/api/camposadicionales").then(response => {
              var adicional = data.nombrec.split("||");
              var finalffcamposs = [];
              for (var f = 0; f < response.data.length; f++) {
                finalffcamposs.push(response.data[f].nombre);
              }
              var finalff = [];
              for (var i = 0; i < finalffcamposs.length; i++) {
                finalff.push({
                  nombre: adicional[i],
                  descripcion: finalffcamposs[i]
                });
              }
              this.contenidocamposadicionales = finalff;
            });

            //Línea de Producto:
            //Línea de Producto: fk
            this.linea_producto = data.id_linea_producto;
            //tipo_producto fk listar

            this.marca = data.id_marca;
            this.modelo = data.id_modelo;
            this.presentacion = data.id_presentacion;

            this.sector = data.sector;
            this.tipo_servicio = data.tipo_servicio;
            this.unidad_salida = data.unidad_salida;
            this.vencimiento = data.vencimiento;
            this.existencia_max = data.existencia_maxima;
            this.existencia_min = data.existencia_minima;
            //Dimensiones del Producto:
            this.tipo_medida = data.id_tipo_medida;
            setTimeout(() => {
              this.unidad_medida = data.id_unidad_medida;
              this.tipo_producto = data.id_tipo_producto;
            }, 500);
            this.numero_unidad = data.numero_unidad;
            this.estado = "1";
            this.numero_serie = data.numero_serie;
            //VEHICULO
            this.vehiculo = data.vehiculo;
            this.placa = data.placa;
            this.pais_origen = data.pais_origen;
            this.ano_fabricacionv = data.ano_fabricacionv;
            this.color = data.color;
            this.carroceria = data.carroceria;
            this.combustible = data.combustible;
            this.motor = data.motor;
            this.cilindraje = data.cilindraje;
            this.chasis = data.chasis;
            this.clase = data.clase;
            this.subclase = data.subclase;
            this.numero_pasajeros = data.numero_pasajeros;
            //Rubros del Producto:
            this.iva = data.iva;
            this.ice = data.ice;
            this.arancel_advalorem = data.arancel_advalorem;
            this.arancel_especifico = data.arancel_especifico;
            this.arancel_fodinfa = data.arancel_fodinfa;
            this.comision = data.comision;
            this.salvaguardia = data.salvaguardia;
            this.precio1 = data.pvp_precio1;
            this.precio2 = data.precio2;
            this.precio3 = data.precio3;
            this.precio4 = data.precio4;
            this.precio5 = data.precio5;
            this.descuento = data.descuento;
            this.utilidad = data.utilidad;
            this.fecha_fabricacion = data.fecha_fabricacion;
            this.ultimo_costo = data.ultimo_costo;
            this.costo_promedio = data.costo_promedio;
            this.costo_total = data.costo_total;
            this.existencia_total = data.existencia_total;
          })
          .catch(err => {
            console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
    },
    /**
     * validar, valida los campos definidos
     * @ funcion validar
     */
    validar() {
      this.error = 0;
      this.errornombre = [];
      this.errorcodpri = [];
      this.errorlinea = [];
      this.errortipo = [];
      this.errormarca = [];
      this.errormodelo = [];
      this.errorpresentacion = [];

      this.errorsector = [];
      this.errortipo_servicio = [];
      this.errorunidad_salida = [];
      this.errortipo_medida = [];
      this.errorunidad_medida = [];
      this.errornumero_unidad = [];
      this.errorestado = [];

      this.errorplaca = [];
      this.errorpais_origen = [];
      this.errorano_fabricacionv = [];
      this.errorcolor = [];
      this.errorcarroceria = [];
      this.errorcombustible = [];
      this.errormotor = [];
      this.errorcilindraje = [];
      this.errorchasis = [];
      this.errorclase = [];
      this.errorsubclase = [];
      this.errornumero_pasajeros = [];

      this.erroriva = [];
      this.errorice = [];

      if (!this.nombre) {
        this.errornombre.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.linea_producto) {
        this.errorlinea.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.tipo_producto) {
        this.errortipo.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.marca) {
        this.errormarca.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.modelo) {
        this.errormodelo.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.presentacion) {
        this.errorpresentacion.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.sector) {
        this.errorsector.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (this.sector == "Servicio") {
        if (!this.tipo_servicio) {
          this.errortipo_servicio.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
      }
      if (!this.unidad_salida) {
        this.errorunidad_salida.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.tipo_medida) {
        this.errortipo_medida.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.unidad_medida) {
        this.errorunidad_medida.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.numero_unidad) {
        this.errornumero_unidad.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.estado) {
        this.errorestado.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (this.vehiculo == 1) {
        if (!this.placa) {
          this.errorplaca.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.pais_origen) {
          this.errorpais_origen.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.ano_fabricacionv) {
          this.errorano_fabricacionv.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.color) {
          this.errorcolor.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.carroceria) {
          this.errorcarroceria.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.combustible) {
          this.errorcombustible.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.motor) {
          this.errormotor.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.cilindraje) {
          this.errorcilindraje.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.chasis) {
          this.errorchasis.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.clase) {
          this.errorclase.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.subclase) {
          this.errorsubclase.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.numero_pasajeros) {
          this.errornumero_pasajeros.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
      }

      if (!this.iva) {
        this.erroriva.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.ice) {
        this.errorice.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      return this.error;
    },
    /**
     * solonumeros pertime solo en ingreso de nuemeros en el formulario.
     * @  funcion solonumeros
     */
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
    /**
     * Cerrar borrar todos los campos del formulario.
     * @ funcion cerrar
     */
    cerrar() {
      this.cod_principal = "";
      this.cod_alterno = "";
      this.nombre = "";
      this.nombre = "";
      this.cod_barras = "";
      this.cta_prod = "";
      this.form_prod = "";
      this.descripcion = "";
      this.caracteristicas = "";
      this.normativa = "";
      this.uso = "";
      // Campos adicionales
      this.nombrec = "";
      this.contenido = "";
      //Línea de Producto:
      //Línea de Producto: fk
      this.linea_producto = "";
      //tipo_producto fk listar
      this.tipo_producto = "";
      this.marca = "";
      this.modelo = "";
      this.presentacion = "";

      this.sector = "";

      this.unidad_salida = "";
      this.vencimiento = "";
      this.existencia_max = "";
      this.existencia_min = "";
      //Dimensiones del Producto:
      this.tipo_medida = "";
      this.unidad_medida = "";
      this.numero_unidad = "";
      this.estado = "";
      this.numero_serie = "";
      /*VEHICULO*/
      this.vehiculo = false;
      this.placa = "";
      this.pais_origen = "";
      this.ano_fabricacionv = "";
      this.color = "";
      this.carroceria = "";
      this.combustible = "";
      this.motor = "";
      this.cilindraje = "";
      this.chasis = "";
      this.clase = "";
      this.subclase = "";
      this.numero_pasajeros = "";
      //Rubros del Producto:
      this.iva = "";
      this.ice = "";
      this.arancel_advalorem = "";
      this.arancel_especifico = "";
      this.arancel_fodinfa = "";
      this.comision = "";
      this.salvaguardia = "";
      this.precio1 = "";
      this.precio2 = "";
      this.precio3 = "";
      this.precio4 = "";
      this.precio5 = "";
      this.descuento = "";
      this.utilidad = "";
      this.fecha_fabricacion = "";
      this.ultimo_costo = "";
      this.costo_promedio = "";
      this.costo_total = "";
      this.existencia_total = "";
      //listados
      this.contenidolinea = [];
      this.contenidotipo = [];
      this.contenidomarca = [];
      this.contenidomodelo = [];

      this.contenidopresentacion = [];
      this.contenidotipomedida = [];
      this.contenidounidadmedida = [];
      this.contenidounidadmedidar = [];
      this.contenidoiva = [];
      this.contenidoice = [];
    },
    listarlinea() {
      var url = "/api/lineaproductosall/" + this.usuario.id_empresa;
      axios.get(url).then(res => {
        this.contenidolinea = res.data;
      });
    },
    listartipo(id) {
      var url =
        "/api/tipoproductosallr/" + this.usuario.id_empresa + "?id=" + id;
      axios.get(url).then(res => {
        this.contenidotipo = res.data;
      });
    },
    listarmarca() {
      var url = "/api/marcaall/" + this.usuario.id_empresa;
      axios.get(url).then(res => {
        this.contenidomarca = res.data;
      });
    },
    listarmodelo() {
      var url = "/api/modeloall/" + this.usuario.id_empresa;
      axios.get(url).then(res => {
        this.contenidomodelo = res.data;
      });
    },
    listarpresentacion() {
      var url = "/api/presentacionall/" + this.usuario.id_empresa;
      axios.get(url).then(res => {
        this.contenidopresentacion = res.data;
      });
    },
    listartipomedida() {
      let me = this;
      var url = "/api/tipomedida";
      axios
        .get(url)
        .then(function(response) {
          me.contenidotipomedida = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarunidadmedida() {
      let me = this;
      var url = "/api/unidadmedida";
      axios
        .get(url)
        .then(function(response) {
          me.contenidounidadmedida = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarunidadmedidar(id) {
      let me = this;
      var url = "/api/unidadmedidar?id=" + id;
      axios
        .get(url)
        .then(function(response) {
          me.contenidounidadmedidar = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarcamposadicionales() {
      if (this.$route.params.id) {
        let me = this;
        var url = "/api/camposadicionales";
        axios
          .get(url)
          .then(function(response) {
            me.contenidocamposadicionales = response.data;
            if (me.contenidocamposadicionales.length >= 1) {
              me.ocult = true;
            }
          })
          .catch(function(error) {
            console.log(error);
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
    listarcuenta(page1, buscar1) {
      axios
        .get(
          "/api/cuentas/" +
            this.usuario.id_empresa +
            "?page=" +
            page1 +
            "&buscar=" +
            buscar1
        )
        .then(res => {
          this.contenidocuenta = res.data.recupera;
        });
    },
    octl() {
      if (this.ocult == true) {
        this.ocult = false;
      } else {
        this.ocult = true;
      }
    },
    obtenerimagen(e) {
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
    guardarimagen(id) {
      let formData = new FormData();
      formData.append("id", id);
      formData.append("file", this.imagenrecupera);
      axios.post("/api/guardarimgproducto", formData);
    },
    handleSelected(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      this.popupActive = false;
      this.cta_prod = `${tr.codcta}`;
    },

    /* Funciones agregar formula de produccion*/
    listarformula(page, buscar) {
      var url =
        "/api/formula/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidoformula = respuesta.recupera;
      });
    },
    selectformula(tr) {
      this.popupformprod = false;
      this.form_prod = `${tr.nombre_form}`;
      this.formu_prod = tr.id_formula_produccion;
    },
    //crear fomula de produccion
    listarcodprod() {
      var url = "/api/codfomr/" + this.usuario.id_empresa;
      axios.get(url).then(res => {
        this.cod_pro = res.data.codigo_produccion;
      });
    },
    listarprod() {
      var url = "/api/traerformprod/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          this.continprod = res.data.datos;
        })
        .catch(err => {
          console.log(err);
        });
    },
    listaringred() {
      var url = "/api/traerformingred/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          this.contingred = res.data.datos;
        })
        .catch(err => {
          console.log(err);
        });
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
        this.arrayingrediente = respuesta.recupera;
        //this.arrayprodprodu = respuesta.recupera;
      });
    },
    abrirproductos() {
      this.popupprod = true;
      this.tipot = 1;
    },
    abriringredientes() {
      this.popupprod = true;
      this.tipot = 2;
    },
    handleSelectedp(tr) {
      this.popupprod = false;
      this.continprod.push({
        id: tr.id_producto,
        cod_principal: tr.cod_principal,
        nombre: tr.nombre
      });
    },
    handleSelectedi(tr) {
      this.popupprod = false;
      this.contingred.push({
        id: tr.id_producto,
        cod_principal: tr.cod_principal,
        nombre: tr.nombre,
        cant_form: null
      });
    },
    eliminarp(id) {
      this.continprod.splice(id, 1);
    },
    eliminari(id) {
      this.contingred.splice(id, 1);
    },
    guardarformula() {
      if (this.validarformula()) {
        return;
      }
      axios
        .post("/api/agregarformula", {
          //formula
          codigo_produccion: this.cod_pro,
          nombre_form: this.nom_pro,
          id_empresa: this.usuario.id_empresa,
          //productos
          productos: this.continprod,
          ingredientes: this.contingred
        })
        .then(res => {
          this.$vs.notify({
            title: "Fórmula Guardada",
            text: "Fórmula agregada con éxito",
            color: "success"
          });
          this.form_prod = this.nom_pro;
          this.formu_prod = res.data;
          this.popucreaformprod = false;
          this.popupformprod = false;
        });
    },
    validarformula() {
      this.error1 = 0;

      this.errorcod_pro = [];
      this.errornom_pro = [];
      this.erroringred = [];
      this.errorcant_form = [];

      if (!this.cod_pro) {
        this.errorcod_pro.push("Campo obligatorio");
        this.error1 = 1;
        $(".vs-popup--content").scrollTop(0);
      }
      if (!this.nom_pro) {
        this.errornom_pro.push("Campo obligatorio");
        this.error1 = 1;
        $(".vs-popup--content").scrollTop(0);
      }
      if (!this.contingred.length) {
        this.erroringred.push("Debe añadir almenos un ingrediente");
        this.error1 = 1;
        $(".vs-popup--content").scrollTop(0);
      }

      for (var i = 0; i < this.contingred.length; i++) {
        this.contingred[i].errorcant_form = [];
        if (!this.contingred[i].cant_form) {
          this.contingred[i].errorcant_form.push("Campo obligatorio");
          this.error1 = 1;
          $(".vs-popup--content").scrollTop(0);
        }
      }

      return this.error1;
    },
    cancelarformula() {
      this.popucreaformprod = false;
      this.nom_pro = "";
      this.continprod = [];
      this.contingred = [];
    }
  },
  mounted() {
    this.listartipomedida();
    this.listarlinea();
    this.listarmarca();
    this.listarmodelo();
    //this.listarbodega();
    // this.listarubicacionf();
    this.listarpresentacion();
    this.listarcamposadicionales();
    this.listarunidadmedida();
    this.listariva();
    this.listarice();
    this.listar();
    this.listarcuenta(1, this.buscar1);

    //metodos formula produccion
    this.listarformula(1, this.buscar);
    this.listarcodprod();
    this.listarp(1, this.buscarp);
  }
};
</script>


<style>
.txt-center > div > input {
  text-align: center;
}
.text-center > .vs-table-text {
  text-align: center !important;
  display: block;
}
.btn-upload-all {
  display: none;
}
.btn-upload-file {
  display: none;
}
.image-container[data-v-10e59822] {
  width: 100%;
  height: 270px;
  border: 1px dashed #d6d6d6;
  border-radius: 4px;
  background-color: #fff;
}

.centered[data-v-10e59822] {
  width: 100%;
  height: 100%;
}

.show-img[data-v-10e59822] {
  max-width: 100%;
  max-height: 185px;
  width: 100%;
}

.image-overlay[data-v-10e59822] {
  height: 170px;
}

.image-overlay-details[data-v-10e59822] {
  top: 62%;
}

.image-list-container .image-list-item .show-img[data-v-10e59822] {
  max-width: 30px;
  max-height: 33px;
  height: 30px;
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
  height: 225px;
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
  border: 5px solid rgba(0, 0, 0, 0.3);
}

.imagenpre:hover {
  -moz-transform: scale(1.03);
  -webkit-transform: scale(1.03);
  -o-transform: scale(1.03);
  -ms-transform: scale(1.03);
  transform: scale(1.03);
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
.relativo {
  position: relative;
}
.iconelim {
  float: none;
  position: absolute;
  right: 16px;
  padding: 1px !important;
  margin-top: -4px;
  width: 23px !important;
  height: 23px !important;
  cursor: pointer;
  z-index: 9;
}
.vs-popup {
  width: 900px !important;
}
</style>
