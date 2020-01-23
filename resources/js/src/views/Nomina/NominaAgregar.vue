<template>
  <vx-card title="Agregar Empleado">
    <vs-tabs alignment="fixed" v-model="Tabnav">
      <vs-tab title="BasicInfo" label="Información Básica" icon-pack="feather" icon="icon-user">
        <div class="vx-row">
          <vs-divider>Datos del Empleado</vs-divider>
          <div class="vx-col md:w-2/3 w-full mb-6">
            <div class="vx-row">
              <div class="vx-col sm:w-1/2 w-full mb-6">
                <vs-select
                  placeholder="--Seleccione--"
                  class="selectExample w-full"
                  label="Tipo de Identificación:"
                  vs-multiple
                  autocomplete
                  v-model="tipo_dni"
                >
                  <vs-select-item
                    :key="index"
                    :value="item.text"
                    :text="item.text"
                    v-for="(item,index) in tipo_ident_array"
                  />
                </vs-select>
                <div v-show="error">
                  <span class="text-danger" v-for="err in errortipo_dni" :key="err" v-text="err"></span>
                </div>
              </div>

              <div class="vx-col sm:w-1/2 w-full mb-6">
                <vs-input
                  class="w-full"
                  label="No. Identificación:"
                  v-model="dni"
                  @keyup="validarcedula"
                  @keypress="solonumeros($event)"
                  maxlength="10"
                />
                <div v-show="error">
                  <span class="text-danger" v-for="err in errordni" :key="err" v-text="err"></span>
                </div>
              </div>
              <div class="vx-col sm:w-1/2 w-full mb-6">
                <vs-input
                  class="w-full"
                  label="Primer Nombre:"
                  @keypress="sololetras($event)"
                  v-model="primer_nombre"
                />
                <div v-show="error">
                  <span
                    class="text-danger"
                    v-for="err in errorprimer_nombre"
                    :key="err"
                    v-text="err"
                  ></span>
                </div>
              </div>
              <div class="vx-col sm:w-1/2 w-full mb-6">
                <vs-input
                  class="w-full"
                  label="Segundo Nombre:"
                  @keypress="sololetras($event)"
                  v-model="segundo_nombre"
                />
                <div v-show="error">
                  <span
                    class="text-danger"
                    v-for="err in errorsegundo_nombre"
                    :key="err"
                    v-text="err"
                  ></span>
                </div>
              </div>
              <div class="vx-col sm:w-1/2 w-full mb-6">
                <vs-input
                  class="w-full"
                  label="Apellido Paterno:"
                  @keypress="sololetras($event)"
                  v-model="apellido_paterno"
                />
                <div v-show="error">
                  <span
                    class="text-danger"
                    v-for="err in errorapellido_paterno"
                    :key="err"
                    v-text="err"
                  ></span>
                </div>
              </div>
              <div class="vx-col sm:w-1/2 w-full mb-6">
                <vs-input
                  class="w-full"
                  label="Apellido Materno:"
                  @keypress="sololetras($event)"
                  v-model="apellido_materno"
                />
                <div v-show="error">
                  <span
                    class="text-danger"
                    v-for="err in errorapellido_materno"
                    :key="err"
                    v-text="err"
                  ></span>
                </div>
              </div>

              <div class="vx-col w-1/2 mb-6">
                <label class="vs-input--label">Fecha Nacimiento:</label>
                <flat-pickr
                  :config="configdateTimePicker"
                  class="w-full"
                  placeholder="--Seleccione--"
                  v-model="fecha_nacimiento"
                  @change="calcularEdad(fecha_nacimiento)"
                />
                <div v-show="error">
                  <span
                    class="text-danger"
                    v-for="err in errorfecha_nacimiento"
                    :key="err"
                    v-text="err"
                  ></span>
                </div>
              </div>
              <div class="vx-col sm:w-1/2 w-full mb-6">
                <vs-input
                  class="w-full"
                  label="Edad:"
                  v-model="edad"
                  @keypress="solonumeros($event)"
                  maxlength="2"
                />
              </div>
            </div>
          </div>
          <div class="vx-col md:w-1/3 w-full mb-6" v-if="!imagen.recupera">
            <label class="vs-input--label">Foto:</label>

            <div style="display:none">
              <input type="file" class="filepre" @change="obtenerimagen" />
            </div>
            <div class="verimagen" v-if="imagen.obtener" @click="abrirfile">
              <img :src="imagen.visualizar" class="imagenpre" />
            </div>
            <div class="centimg vx-card input" v-else @click="abrirfile">
              <img src="/images/upload.png" height="200px" />
              <div style="position:absolute;margin-top:60px;color:#000">Click para subir imagen</div>
            </div>
          </div>
          <div class="vx-col md:w-1/3 w-full mb-6" v-else>
            <label class="vs-input--label">Foto:</label>

            <div style="display:none">
              <input type="file" class="filepre" @change="obtenerimagen" />
            </div>
            <div class="verimagen" @click="abrirfile" v-if="!imagen.visualizar">
              <img :src="'/images/empleados/'+imagen.recupera" class="imagenpre" />
            </div>
            <div class="verimagen" v-else @click="abrirfile">
              <img :src="imagen.visualizar" class="imagenpre" />
            </div>
          </div>

          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Provincia</label>
            <vs-select
              placeholder="--Seleccione--"
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
            <div v-show="error">
              <span class="text-danger" v-for="err in errorprovincia" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Cantón</label>
            <vs-select
              placeholder="--Seleccione--"
              autocomplete
              class="selectExample w-full"
              v-model="canton"
              @change="getParroquias"
            >
              <vs-select-item
                v-for="data in ciudades2"
                :key="data.id_ciudad"
                :value="data.id_ciudad"
                :text="data.nombre"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errorcanton" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Parroquia</label>
            <vs-select
              placeholder="--Seleccione--"
              autocomplete
              class="selectExample w-full"
              v-model="lugar_nacimiento"
              @change="getParroquias()"
            >
              <vs-select-item
                v-for="data in parroquias2"
                :key="data.id_parroquia"
                :value="data.nombre_parroquia"
                :text="data.nombre_parroquia"
              />
            </vs-select>
            <!--
            <div v-show="error">
              <span class="text-danger" v-for="err in errorparroquia" :key="err" v-text="err"></span>
            </div>
            -->
          </div>

          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="Lugar de Nacimiento:"
              v-model="lugar_nacimiento"
              disabled
            />
            <!--
            <div v-show="error">
              <span
                class="text-danger"
                v-for="err in errorlugar_nacimiento"
                :key="err"
                v-text="err"
              ></span>
            </div>
            -->
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Nacionalidad:"
              vs-multiple
              autocomplete
              v-model="nacionalidad"
            >
              <vs-select-item
                v-for="data in nacionalidad2"
                :key="data.id_nacionalidad"
                :value="data.GENTILICIO_NAC"
                :text="data.GENTILICIO_NAC"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errornacionalidad" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Estado Civil:"
              vs-multiple
              autocomplete
              v-model="estado_civil"
            >
              <vs-select-item
                v-for="(item,index) in estado_civil_array"
                :key="index"
                :value="item.text"
                :text="item.text"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errorestado_civil" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Sexo:"
              vs-multiple
              autocomplete
              v-model="sexo"
            >
              <vs-select-item
                :key="index"
                :value="item.text"
                :text="item.text"
                v-for="(item,index) in sexo_array"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errorsexo" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="Dirección Residencia:" v-model="direccion_residencia" />
            <div v-show="error">
              <span
                class="text-danger"
                v-for="err in errordireccion_residencia"
                :key="err"
                v-text="err"
              ></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="Teléfono:"
              v-model="telefono"
              @keypress="solonumeros($event)"
              maxlength="9"
            />
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="Celular:"
              v-model="celular"
              @keypress="solonumeros($event)"
              maxlength="10"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in errorcelular" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="Email:" v-model="email" />
            <div v-show="error">
              <span class="text-danger" v-for="err in erroremail" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Tipo de Sangre:"
              vs-multiple
              autocomplete
              v-model="tipo_sangre"
            >
              <vs-select-item
                :key="index"
                :value="item.text"
                :text="item.text"
                v-for="(item,index) in tipo_sangre_array"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errortipo_sangre" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="Profesion:" v-model="profesion" />
            <div v-show="error">
              <span class="text-danger" v-for="err in errorprofesion" :key="err" v-text="err"></span>
            </div>
          </div>
          <!--prueba-->
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Discapacidad:"
              vs-multiple
              autocomplete
              v-model="discapacidad"
              @change="cambio(discapacidad)"
            >
              <vs-select-item
                :key="index"
                :value="item.text"
                :text="item.text"
                v-for="(item,index) in discapacidad_array"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errordiscapacidad" :key="err" v-text="err"></span>
            </div>
          </div>
          <div v-if="tipodiscap" class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="% Discapacidad:"
              v-model="discap_porcentaje"
              @keypress="solonumeros($event)"
              maxlength="9"
            />
            <!--
            <div v-show="error">
              <span
                class="text-danger"
                v-for="err in errordiscap_porcentaje"
                :key="err"
                v-text="err"
              ></span>
            </div>
            -->
          </div>
          <div v-if="tipodiscap" class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              class="selectExample w-full"
              label="Documento de Discapacidad:"
              placeholder="--Seleccione--"
              vs-multiple
              autocomplete
              v-model="tipo_iden_discap"
            >
              <vs-select-item
                :key="index"
                :value="item.text"
                :text="item.text"
                v-for="(item,index) in ident_discap_array"
              />
            </vs-select>
            <!--
            <div v-show="error">
              <span
                class="text-danger"
                v-for="err in errortipo_iden_discap"
                :key="err"
                v-text="err"
              ></span>
            </div>
            -->
          </div>
          <div v-if="tipodiscap" class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="No. Identificación Discap.:"
              v-model="num_iden_discap"
              @keypress="solonumeros($event)"
              maxlength="10"
            />
            <!--
            <div v-show="error">
              <span class="text-danger" v-for="err in errornum_iden_discap" :key="err" v-text="err"></span>
            </div>
            -->
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="No. IESS:"
              v-model="num_iess"
              @keypress="solonumeros($event)"
              maxlength="10"
            />
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="No. Libreta Militar:"
              v-model="num_libreta_militar"
              @keypress="solonumeros($event)"
              maxlength="10"
            />
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Banco:"
              vs-multiple
              autocomplete
              v-model="banco"
            >
              <vs-select-item
                v-for="data in banco2"
                :key="data.id_banco"
                :value="data.nombre_banco"
                :text="data.nombre_banco"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errorbanco" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Tipo de Cuenta:"
              vs-multiple
              autocomplete
              v-model="tipo_cuenta"
            >
              <vs-select-item
                :key="index"
                :value="item.text"
                :text="item.text"
                v-for="(item,index) in tipo_cuenta_array"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errortipo_cuenta" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="No. Cuenta:"
              v-model="num_cuenta"
              @keypress="solonumeros($event)"
              maxlength="10"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in errornum_cuenta" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="No. Cargas:"
              v-model="num_cargas"
              @keypress="solonumeros($event)"
              maxlength="2"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in errornum_cargas" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Estado:"
              vs-multiple
              autocomplete
              v-model="estado"
            >
              <vs-select-item
                :key="index"
                :value="item.text"
                :text="item.text"
                v-for="(item,index) in estado_array"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errorestado" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-full w-full mb-6">
            <div>
              <label class="vs-input--label">Observaciones:</label>
              <vs-textarea v-model="observaciones" rows="3" />
            </div>
          </div>

          <!--BOTONES

          <div class="vx-col w-full">
            <vs-button
              color="success"
              type="filled"
              @click="editarEmpleado()"
              v-if="$route.params.id"
            >Siguitente</vs-button>
            <vs-button color="success" type="filled" @click="guardarEmpleado()" v-else>Siguitente</vs-button>
          </div>
          -->
        </div>
      </vs-tab>
      <!--
      -->

      <vs-tab
        title="JobInfo"
        id="JobInfo"
        label="Detalles del Cargo"
        icon-pack="feather"
        icon="icon-briefcase"
      >
        <div class="vx-col w-full mb-base">
          <div class="vx-row">
            <!--Segunda tabla-->

            <vs-divider>Datos del Cargo</vs-divider>

            <div class="vx-col sm:w-1/3 w-full mb-6">
              <label class="vs-input--label">Departamento</label>
              <vs-select
                placeholder="--Seleccione--"
                autocomplete
                class="selectExample w-full"
                v-model="departamento"
                @change="getArea();area_trabajo='';cargo='';"
              >
                <vs-select-item
                  v-for="data in departamento2"
                  :key="data.id_departamento"
                  :value="data.id_departamento"
                  :text="data.dep_nombre"
                />
              </vs-select>
              <div v-show="error">
                <span class="text-danger" v-for="err in errordepartamento" :key="err" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <label class="vs-input--label">Área de Trabajo</label>
              <vs-select
                placeholder="--Seleccione--"
                autocomplete
                class="selectExample w-full"
                v-model="area_trabajo"
                @change="getCargo"
              >
                <vs-select-item
                  v-for="data in area2"
                  :key="data.id_area"
                  :value="data.id_area"
                  :text="data.are_nombre"
                />
              </vs-select>
              <div v-show="error">
                <span class="text-danger" v-for="err in errorarea_trabajo" :key="err" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <label class="vs-input--label">Cargo</label>
              <vs-select
                placeholder="--Seleccione--"
                autocomplete
                class="selectExample w-full"
                v-model="idcargo"
                @change="getCargo()"
              >
                <vs-select-item
                  v-for="data in cargo2"
                  :key="data.id_cargo"
                  :value="data.id_cargo"
                  :text="data.car_nombre"
                />
              </vs-select>
              <div v-show="error">
                <span class="text-danger" v-for="err in errorcargo" :key="err" v-text="err"></span>
              </div>
            </div>
            <!--
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-input class="w-full" label="Sueldo:" disabled v-model="sueldo" />
              <div v-show="error">
                <span class="text-danger" v-for="err in errorsueldo" :key="err" v-text="err"></span>
              </div>
            </div>
            -->
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <label class="vs-input--label">Fecha Ingreso:</label>
              <flat-pickr
                :config="configdateTimePicker"
                class="w-full"
                placeholder="--Seleccione--"
                v-model="fecha_ingreso"
              />
              <div v-show="error">
                <span class="text-danger" v-for="err in errorfecha_ingreso" :key="err" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <label class="vs-input--label">Fecha Salida:</label>
              <flat-pickr
                :config="configdateTimePicker"
                class="w-full"
                placeholder="--Seleccione--"
                v-model="fecha_salida"
              />
              <div v-show="error">
                <span class="text-danger" v-for="err in errorfecha_salida" :key="err" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="--Seleccione--"
                class="selectExample w-full"
                label="Grupo Ocupacional:"
                vs-multiple
                autocomplete
                v-model="idgrupo"
              >
                <vs-select-item
                  v-for="data in grupo_ocupacional2"
                  :key="data.id_grupo"
                  :value="data.id_grupo"
                  :text="data.grup_nombre"
                />
              </vs-select>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="--Seleccione--"
                class="selectExample w-full"
                label="Tipo Horario:"
                vs-multiple
                autocomplete
                v-model="tipo_horario"
              >
                <vs-select-item
                  :key="index"
                  :value="item.text"
                  :text="item.text"
                  v-for="(item,index) in tipo_horario_array"
                />
              </vs-select>
              <div v-show="error">
                <span class="text-danger" v-for="err in errortipo_horario" :key="err" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="--Seleccione--"
                class="selectExample w-full"
                label="Tipo Contrato:"
                vs-multiple
                autocomplete
                v-model="tipo_contrato"
              >
                <vs-select-item
                  :key="index"
                  :value="item.text"
                  :text="item.text"
                  v-for="(item,index) in tipo_contrato_array"
                />
              </vs-select>
              <div v-show="error">
                <span class="text-danger" v-for="err in errortipo_contrato" :key="err" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-input
                class="w-full"
                label="Bonos:"
                @keypress="solonumeros($event)"
                v-model="bonos"
              />
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="--Seleccione--"
                class="selectExample w-full"
                label="Aporte IESS Mensual:"
                vs-multiple
                autocomplete
                v-model="aporte_iess"
              >
                <vs-select-item
                  :key="index"
                  :value="item.text"
                  :text="item.text"
                  v-for="(item,index) in aporte_iess_array"
                />
              </vs-select>
              <div v-show="error">
                <span class="text-danger" v-for="err in erroraporte_iess" :key="err" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="--Seleccione--"
                class="selectExample w-full"
                label="Fondo Reserva Mensual:"
                vs-multiple
                autocomplete
                v-model="fondo_reserva"
              >
                <vs-select-item
                  :key="index"
                  :value="item.text"
                  :text="item.text"
                  v-for="(item,index) in fondo_reserva_array"
                />
              </vs-select>
              <div v-show="error">
                <span class="text-danger" v-for="err in errorfondo_reserva" :key="err" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="--Seleccione--"
                class="selectExample w-full"
                label="Décimo Tercero Mensual:"
                vs-multiple
                autocomplete
                v-model="decimo_tercero"
              >
                <vs-select-item
                  :key="index"
                  :value="item.text"
                  :text="item.text"
                  v-for="(item,index) in decimo_tercero_array"
                />
              </vs-select>
              <div v-show="error">
                <span
                  class="text-danger"
                  v-for="err in errordecimo_tercero"
                  :key="err"
                  v-text="err"
                ></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-select
                placeholder="--Seleccione--"
                class="selectExample w-full"
                label="Décimo Cuarto Mensual:"
                vs-multiple
                autocomplete
                v-model="decimo_cuarto"
              >
                <vs-select-item
                  :key="index"
                  :value="item.text"
                  :text="item.text"
                  v-for="(item,index) in decimo_cuarto_array"
                />
              </vs-select>
              <div v-show="error">
                <span class="text-danger" v-for="err in errordecimo_cuarto" :key="err" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-input
                class="w-full"
                label="Cuenta Contable:"
                @keypress="solonumeros($event)"
                v-model="cuenta_contable"
              />
              <div v-show="error">
                <span
                  class="text-danger"
                  v-for="err in errorcuenta_contable"
                  :key="err"
                  v-text="err"
                ></span>
              </div>
            </div>
            <!--
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-input class="w-full" label="ID:" v-model="id_empleado"/>
            </div>-->
            <div class="vx-col sm:w-full w-full mb-6">
              <div>
                <label class="vs-input--label">Observaciones:</label>
                <vs-textarea v-model="observaciones_dos" rows="3" />
              </div>
            </div>
          </div>
        </div>
      </vs-tab>
      <!--
      -->
      <vs-tab title="cargas" label="Cargas" icon-pack="feather" icon="icon-users">
        <!--
        <vs-divider>Datos de las Cargas</vs-divider>
        <div class="vx-col sm:w-full w-full mb-6">
          <vx-card>
            <div class="vx-row">
              <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                <div class="flex flex-wrap justify-between items-center mb-3">
                   ITEMS PER PAGE 
                </div>
                <vs-table stripe :data="contenidocargas">
                  <template slot="thead">
                    <vs-th>Código</vs-th>
                    <vs-th>Cédula</vs-th>
                    <vs-th>Nombre</vs-th>
                    <vs-th>Ocupación</vs-th>
                    <vs-th>Parentezco</vs-th>
                    <vs-th>Opciones</vs-th>
                  </template>
                  <template slot-scope="{data}">
                    <vs-tr :key="datos.id_carga" v-for="datos in data">
                      <vs-td v-if="datos.id_carga">{{datos.id_carga}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.car_dni">{{datos.car_dni}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.car_nombre">{{datos.car_nombre}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.car_ocupacion">{{datos.car_ocupacion}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td v-if="datos.car_parentezco">{{datos.car_parentezco}}</vs-td>
                      <vs-td v-else>-</vs-td>
                      <vs-td class="whitespace-no-wrap">
                        <feather-icon
                          icon="EditIcon"
                          svgClasses="w-5 h-5 hover:text-primary stroke-current"
                          class="cursor-pointer"
                          @click.stop="editarCarga(datos.id_carga)"
                        />
                        <feather-icon
                          icon="TrashIcon"
                          svgClasses="w-5 h-5 hover:text-danger stroke-current"
                          class="ml-2 cursor-pointer"
                          @click.stop="eliminarCarga(datos.id_carga)"
                        />
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>
          </vx-card>
        </div>
        -->
        <div class="vx-row">
          <!--<vs-divider>
            <vs-button
              color="primary"
              style="margin-left: 9px;padding: 8px 20px;"
              type="border"
              @click="guardarCarga()"
            >agregar</vs-button>
          </vs-divider>-->
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="No. Identificación:"
              v-model="car_dni"
              @keypress="solonumeros($event)"
              maxlength="10"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in errorcar_dni" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="Nombre:"
              v-model="car_nombre"
              @keypress="sololetras($event)"
              maxlength="10"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in errorcar_nombre" :key="err" v-text="err"></span>
            </div>
          </div>
          <!---->
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Fecha Nacimiento:</label>
            <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="--Seleccione--"
              v-model="car_fecha_nacimiento"
            />
            <div v-show="error">
              <span
                class="text-danger"
                v-for="err in errorcar_fecha_nacimiento"
                :key="err"
                v-text="err"
              ></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="Edad:"
              v-model="car_edad"
              @keypress="solonumeros($event)"
              maxlength="2"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in errorcar_edad" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="Ocupación:"
              v-model="car_ocupacion"
              @keypress="sololetras($event)"
              maxlength="10"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in errorcar_ocupacion" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Parentezco:"
              vs-multiple
              autocomplete
              v-model="car_parentezco"
            >
              <vs-select-item
                :key="index"
                :value="item.text"
                :text="item.text"
                v-for="(item,index) in car_parentezco_array"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errorcar_parentezco" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Discapacidad:"
              vs-multiple
              autocomplete
              v-model="car_discapacidad"
              @change="cambio(car_discapacidad)"
            >
              <vs-select-item
                :key="index"
                :value="item.text"
                :text="item.text"
                v-for="(item,index) in car_discapacidad_array"
              />
            </vs-select>
            <div v-show="error">
              <span
                class="text-danger"
                v-for="err in errorcar_discapacidad"
                :key="err"
                v-text="err"
              ></span>
            </div>
          </div>
          <div v-if="tipodiscap" class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input
              class="w-full"
              label="% Discapacidad:"
              v-model="car_discap_porcentaje"
              @keypress="solonumeros($event)"
              maxlength="9"
            />
          </div>
        </div>
      </vs-tab>

      <vs-tab title="documentacion" label="Documentación" icon-pack="feather" icon="icon-file-text">
        <div class="vx-row">
          <vs-divider>Documentos Entregables</vs-divider>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Documento:"
              vs-multiple
              autocomplete
              v-model="doc_descripcion"
            >
              <vs-select-item
                v-for="data in doc_descripcion2"
                :key="data.id_documento"
                :value="data.id_documento"
                :text="data.doc_descripcion"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errordoc_descripcion" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select
              placeholder="--Seleccione--"
              class="selectExample w-full"
              label="Estado:"
              vs-multiple
              autocomplete
              v-model="doc_estado"
            >
              <vs-select-item
                :key="index"
                :value="item.text"
                :text="item.text"
                v-for="(item,index) in doc_estado_array"
              />
            </vs-select>
            <div v-show="error">
              <span class="text-danger" v-for="err in errordoc_estado" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Subir Archivo</label>
            <vx-input-group class="mb-base">
              <vs-input class="w-full" v-model="doc_url" @keypress="solonumeros($event) " />
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary" @click="popupActive=true">Elegir</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <!--BOTONES-->

          <div class="vx-col w-full">
            <vs-button
              color="success"
              type="filled"
              @click="editarEmpleado()"
              v-if="$route.params.id"
            >Siguitente</vs-button>
            <vs-button color="success" type="filled" @click="guardarEmpleado()" v-else>Guardar</vs-button>
            <vs-button color="danger" type="filled" @click="cancelar()">Cancelar</vs-button>
          </div>
        </div>
      </vs-tab>

      <!--
      <vs-tab title="calendario" label="Inasistencias" icon-pack="feather" icon="icon-calendar">
        <div class="vx-row">
          <vs-divider>Historial de Inasistencia</vs-divider>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Fecha Inicio:</label>
            <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="--Seleccione--"
              v-model="fecha_inicio"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in errorfecha_inicio" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Fecha Fin:</label>
            <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="--Seleccione--"
              v-model="fecha_fin"
            />
            <div v-show="error">
              <span class="text-danger" v-for="err in errorfecha_fin" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input class="w-full" label="Razón:" v-model="razon" @keypress="sololetras($event)" />
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Subir Archivo</label>
            <vx-input-group class="mb-base">
              <vs-input class="w-full" v-model="doc_url" @keypress="solonumeros($event) " />
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary" @click="popupActive=true">Elegir</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
        </div>
      </vs-tab>
      -->
    </vs-tabs>
  </vx-card>
</template>

<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { concat } from "bytebuffer";
import Datepicker from "vuejs-datepicker";
import VueUploadMultipleImage from "vue-upload-multiple-image";

const axios = require("axios");
export default {
  data() {
    return {
      Tabnav: 0,

      agregar_campo: "",
      //tabla empleado
      tipo_dni: "",
      dni: "",
      primer_nombre: "",
      segundo_nombre: "",
      apellido_paterno: "",
      apellido_materno: "",
      fecha_nacimiento: "",
      edad: "",
      foto: "",
      lugar_nacimiento: "",
      nacionalidad: "",
      estado_civil: "",
      sexo: "",
      direccion_residencia: "",
      telefono: "",
      celular: "",
      email: "",
      tipo_sangre: "",
      profesion: "",
      discapacidad: "",
      discap_porcentaje: "",
      tipo_iden_discap: "",
      num_iden_discap: "",
      num_iess: "",
      num_libreta_militar: "",
      banco: "",
      tipo_cuenta: "",
      num_cuenta: "",
      num_cargas: "",
      estado: 0,
      observaciones: "",
      nombre_global: "",
      apellido_global: "",

      //campos segunda tabla cargo-empleado
      id_emp_cargo: "",
      fecha_ingreso: "",
      fecha_salida: "",
      tipo_horario: "",
      tipo_contrato: "",
      bonos: "",
      aporte_iess: "",
      fondo_reserva: "",
      decimo_tercero: "",
      decimo_cuarto: "",
      cuenta_contable: "",
      observaciones_dos: "",
      idcargo: "",
      idgrupo: "",

      //campos tabla cargas-empleado
      car_dni: "",
      car_nombre: "",
      car_fecha_nacimiento: "",
      car_edad: "",
      car_ocupacion: "",
      car_parentezco: "",
      car_discapacidad: "",
      car_discap_porcentaje: "",
      idempleado: "",

      //campos tabla documento-empleado
      id_docu_emp: "",
      doc_url: "",
      doc_estado: "",
      iddocu: "",
      idemple: "",

      //campos tabla calendario-empleado
      id_calen_emp: "",
      fecha_inicio: "",
      fecha_fin: "",
      razon: "",
      idemplead: "",

      //campos desde base
      departamento: "",
      area_trabajo: "",
      cargo: "",
      sueldo: "",
      doc_descripcion: "",
      provincia: "",
      canton: "",
      parroquia: "",

      //object
      imagen: {
        obtener: "",
        nombre: "",
        visualizar: "",
        recupera: null
      },
      imagenrecupera: [],

      //otras variables
      tipodiscap: 0,

      //arrays

      provincias2: [],
      ciudades2: [],
      parroquias2: [],
      banco2: [],
      nacionalidad2: [],
      grupo_ocupacional2: [],
      departamento2: [],
      area2: [],
      cargo2: [],
      doc_descripcion2: [],
      configdateTimePicker: {
        locale: SpanishLocale
      },

      ident_discap_array: [
        { text: "Carnet CONADIS", value: 1 },
        { text: "Otro", value: 2 }
      ],
      tipo_ident_array: [
        { text: "Cédula", value: 1 },
        { text: "Pasaporte", value: 2 }
      ],

      discapacidad_array: [
        { text: "Ninguna", value: 1 },
        { text: "Visual", value: 2 },
        { text: "Física", value: 3 },
        { text: "Auditiva", value: 4 },
        { text: "Mental", value: 5 },
        { text: "Verbal", value: 6 }
      ],

      tipo_sangre_array: [
        { text: "A+", value: 1 },
        { text: "A-", value: 2 },
        { text: "B+", value: 3 },
        { text: "B-", value: 4 },
        { text: "O+", value: 5 },
        { text: "O-", value: 6 },
        { text: "AB+", value: 7 },
        { text: "AB-", value: 8 }
      ],

      estado_civil_array: [
        { text: "Soltero", value: 1 },
        { text: "Casado", value: 2 },
        { text: "Viudo", value: 3 },
        { text: "Divorciado", value: 4 },
        { text: "Unión Libre", value: 5 },
        { text: "Unión de Hecho", value: 6 }
      ],
      decision: [{ text: "No", value: 1 }, { text: "Sì", value: 2 }],
      sexo_array: [
        { text: "Masculino", value: 1 },
        { text: "Femenino", value: 2 }
      ],
      estado_array: [
        { text: "Activo", value: 1 },
        { text: "Inactivo", value: 2 }
      ],
      estado_dos_array: [
        { text: "Activo", value: 1 },
        { text: "Inactivo", value: 2 }
      ],
      parentezco_array: [
        { text: "Hermano/a", value: 1 },
        { text: "Tío/a", value: 2 },
        { text: "Sobrino/a", value: 3 },
        { text: "Hijo/a", value: 4 },
        { text: "Abuelo/a", value: 5 },
        { text: "Primo/a", value: 6 }
      ],
      banco_array: [
        { text: "PROAMÉRICA", value: 1 },
        { text: "PICHINCHA", value: 2 },
        { text: "GUAYAQUIL", value: 3 }
      ],
      tipo_cuenta_array: [
        { text: "AHORROS", value: 1 },
        { text: "CORRIENTE", value: 2 }
      ],
      nacionalidad_array: [
        { text: "ECUATORIANA", value: 1 },
        { text: "VENEZOLANA", value: 2 },
        { text: "COLOMBIANA", value: 3 }
      ],
      porcentaje_array: [{ text: "10%", value: 1 }, { text: "50%", value: 2 }],

      //arrays segunda tabla
      tipo_horario_array: [
        { text: "Jornada Completa", value: 1 },
        { text: "Diurno", value: 2 },
        { text: "Vespertino", value: 3 },
        { text: "Nocturno", value: 4 }
      ],
      tipo_contrato_array: [
        { text: "Infefinido", value: 1 },
        { text: "Por horas", value: 2 },
        { text: "Definido", value: 3 },
        { text: "Obra Cierta", value: 4 }
      ],
      aporte_iess_array: [{ text: "No", value: 1 }, { text: "Sì", value: 2 }],
      fondo_reserva_array: [{ text: "No", value: 1 }, { text: "Sì", value: 2 }],
      decimo_tercero_array: [
        { text: "No", value: 1 },
        { text: "Sì", value: 2 }
      ],
      decimo_cuarto_array: [{ text: "No", value: 1 }, { text: "Sì", value: 2 }],

      //
      car_parentezco_array: [
        { text: "Hermano/a", value: 1 },
        { text: "Tío/a", value: 2 },
        { text: "Sobrino/a", value: 3 },
        { text: "Hijo/a", value: 4 },
        { text: "Abuelo/a", value: 5 },
        { text: "Primo/a", value: 6 }
      ],
      car_discapacidad_array: [
        { text: "Ninguna", value: 1 },
        { text: "Visual", value: 2 },
        { text: "Física", value: 3 },
        { text: "Auditiva", value: 4 },
        { text: "Mental", value: 5 },
        { text: "Verbal", value: 6 }
      ],

      //arrays para base
      departamento_array: [
        { text: "Sistemas", value: 1 },
        { text: "Financiero", value: 2 }
      ],
      area_trabajo_array: [
        { text: "Infraestructura", value: 1 },
        { text: "Contabilidad", value: 2 }
      ],
      cargo_array: [
        { text: "Técnico Mantenimeinto", value: 1 },
        { text: "Técnico Hardware", value: 2 }
      ],
      doc_descripcion_array: [
        { text: "Acta de Matrimonio", value: 1 },
        { text: "Certificado Médico", value: 2 }
      ],
      doc_estado_array: [
        { text: "Entregado", value: 1 },
        { text: "No Entregado", value: 2 }
      ],

      //errores
      error: 0,
      errortipo_dni: [],
      errordni: [],
      errorprimer_nombre: [],
      errorsegundo_nombre: [],
      errorapellido_paterno: [],
      errorapellido_materno: [],
      errorfecha_nacimiento: [],
      erroredad: [],
      errorlugar_nacimiento: [],
      errornacionalidad: [],
      errorestado_civil: [],
      errorsexo: [],
      errordireccion_residencia: [],
      errorcelular: [],
      erroremail: [],
      errortipo_sangre: [],
      errorprofesion: [],
      errordiscapacidad: [],
      errordiscap_porcentaje: [],
      errortipo_iden_discap: [],
      errornum_iden_discap: [],
      errorbanco: [],
      errortipo_cuenta: [],
      errornum_cuenta: [],
      errornum_cargas: [],
      errorestado: [],
      errorprovincia: [],
      errorcanton: [],
      errorparroquia: [],

      //tabla empleado - cargo
      errorfecha_ingreso: [],
      errorfecha_salida: [],
      errortipo_horario: [],
      errortipo_contrato: [],
      erroraporte_iess: [],
      errorfondo_reserva: [],
      errordecimo_tercero: [],
      errordecimo_cuarto: [],
      errorcuenta_contable: [],
      errordepartamento: [],
      errorarea_trabajo: [],
      errorcargo: [],

      //tabla empleado - cargas
      errorcar_dni: "",
      errorcar_nombre: "",
      errorcar_fecha_nacimiento: "",
      errorcar_edad: "",
      errorcar_ocupacion: "",
      errorcar_parentezco: "",
      errorcar_discapacidad: "",
      errorcar_discap_porcentaje: "",

      //campos tabla calendario-empleado
      errorfecha_inicio: "",
      errorfecha_fin: "",
      errorrazon: "",

      //error para campos desde base
      errordepartamento: "",
      errorarea_trabajo: "",
      errorcargo: "",
      errorsueldo: "",
      errordoc_descripcion: "",
      errordoc_estado: "",

      pagination4: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      cantidadp4: 100000,

      contenidocargas: []
    };
  },
  //importa calendario español
  components: {
    flatPickr,
    FormWizard,
    TabContent,
    Datepicker
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
    cancelar() {
      this.$router.push("/nomina/empleados").catch(() => {});
    },
    successUpload() {
      this.$vs.notify({
        color: "success",
        title: "Upload Success",
        text: "Lorem ipsum dolor sit amet, consectetur"
      });
    },
    guardarEmpleado() {
      /*if (this.validarE()) {
        return;
      }*/
      axios.post("/api/empleado/agregar", {
          //tabla empleado
          tipo_dni: this.tipo_dni,
          dni: this.dni,
          primer_nombre: this.primer_nombre,
          segundo_nombre: this.segundo_nombre,
          apellido_paterno: this.apellido_paterno,
          apellido_materno: this.apellido_materno,
          fecha_nacimiento: this.fecha_nacimiento,
          edad: this.edad,
          foto: this.foto,
          lugar_nacimiento: this.lugar_nacimiento,
          nacionalidad: this.nacionalidad,
          estado_civil: this.estado_civil,
          sexo: this.sexo,
          direccion_residencia: this.direccion_residencia,
          telefono: this.telefono,
          celular: this.celular,
          email: this.email,
          tipo_sangre: this.tipo_sangre,
          profesion: this.profesion,
          discapacidad: this.discapacidad,
          discap_porcentaje: this.discap_porcentaje,
          tipo_iden_discap: this.tipo_iden_discap,
          num_iden_discap: this.num_iden_discap,
          num_iess: this.num_iess,
          num_libreta_militar: this.num_libreta_militar,
          banco: this.banco,
          tipo_cuenta: this.tipo_cuenta,
          num_cuenta: this.num_cuenta,
          num_cargas: this.num_cargas,
          estado: this.estado,
          observaciones: this.observaciones,
          //tabla empleado - cargo
          fecha_ingreso: this.fecha_ingreso,
          fecha_salida: this.fecha_salida,
          tipo_horario: this.tipo_horario,
          tipo_contrato: this.tipo_contrato,
          bonos: this.bonos,
          aporte_iess: this.aporte_iess,
          fondo_reserva: this.fondo_reserva,
          decimo_tercero: this.decimo_tercero,
          decimo_cuarto: this.decimo_cuarto,
          cuenta_contable: this.cuenta_contable,
          observaciones: this.observaciones_dos,
          idcargo: this.idcargo,
          idgrupo: this.idgrupo,
          //tabla empleado - cargas
          car_dni: this.car_dni,
          car_nombre: this.car_nombre,
          car_fecha_nacimiento: this.car_fecha_nacimiento,
          car_edad: this.car_edad,
          car_ocupacion: this.car_ocupacion,
          car_discapacidad: this.car_discapacidad,
          car_discap_porcentaje: this.car_discap_porcentaje,
          //tabla empleado - documentacion
          doc_url: this.doc_url,
          doc_estado: this.doc_estado,
          iddocu: this.iddocu,
          id_empresa:this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito.",
            color: "success"
          });
          this.$router.push("/nomina/empleados");
        })
        .catch(err => {
          this.$vs.notify({
            title: "Registro no Realizado",
            text: "Registro no se guardó, revise los campos.",
            color: "danger"
          });
        });
    },
    listarEmpleado(id) {
      axios
        .put("/api/empleado/verempleado/", { id: id, id_empresa: this.usuario.id_empresa })
        .then(res => {
          let data = res.data[0];
          this.tipo_dni = data.tipo_dni;
          this.dni = data.dni;
          this.primer_nombre = data.primer_nombre;
          this.segundo_nombre = data.segundo_nombre;
          this.apellido_paterno = data.apellido_paterno;
          this.apellido_materno = data.apellido_materno;
          this.fecha_nacimiento = data.fecha_nacimiento;
          this.edad = data.edad;
          this.lugar_nacimiento = data.lugar_nacimiento;
          this.nacionalidad = data.nacionalidad;
          this.estado_civil = data.estado_civil;
          this.sexo = data.sexo;
          this.direccion_residencia = data.direccion_residencia;
          this.telefono = data.telefono;
          this.celular = data.celular;
          this.email = data.email;
          this.tipo_sangre = data.tipo_sangre;
          this.profesion = data.profesion;
          this.discapacidad = data.discapacidad;
          this.discap_porcentaje = data.discap_porcentaje;
          this.tipo_iden_discap = data.tipo_iden_discap;
          this.num_iden_discap = data.num_iden_discap;
          this.num_iess = data.num_iess;
          this.num_libreta_militar = data.num_libreta_militar;
          this.banco = data.banco;
          this.tipo_cuenta = data.tipo_cuenta;
          this.num_cuenta = data.num_cuenta;
          this.num_cargas = data.num_cargas;
          this.estado = data.estado;
          this.observaciones = data.observaciones;
          //tabla empleado - cargo
          this.fecha_ingreso = data.fecha_ingreso;
          this.fecha_salida = data.fecha_salida;
          this.tipo_horario = data.tipo_horario;
          this.tipo_contrato = data.tipo_contrato;
          this.bonos = data.bonos;
          this.aporte_iess = data.aporte_iess;
          this.fondo_reserva = data.fondo_reserva;
          this.decimo_tercero = data.decimo_tercero;
          this.decimo_cuarto = data.decimo_cuarto;
          this.cuenta_contable = data.cuenta_contable;
          this.observaciones_dos = data.observaciones;
          this.idcargo = data.idcargo;
          this.idgrupo = data.idgrupo;
          //tabla empleado - cargas
          this.car_dni = data.car_dni;
          this.car_nombre = data.car_nombre;
          this.car_fecha_nacimiento = data.car_fecha_nacimiento;
          this.car_edad = data.car_edad;
          this.car_ocupacion = data.car_ocupacion;
          this.car_parentezco = data.car_parentezco;
          this.car_discapacidad = data.car_discapacidad;
          this.car_discap_porcentaje = data.car_discap_porcentaje;
          //tabla empleado - documento
          this.doc_url = data.doc_url;
          this.doc_estado = data.doc_estado;
          this.iddocu = data.iddocu;
        })
        .catch(err => {
          console.log(err);
        });
    },
    editarEmpleado() {
      /*
      if (this.validarE()) {
        return;
      }*/
      axios
        .put("/api/empleado/editar/", {
          id: this.$route.params.id,
          //tabla empleado
          tipo_dni: this.tipo_dni,
          dni: this.dni,
          primer_nombre: this.primer_nombre,
          segundo_nombre: this.segundo_nombre,
          apellido_paterno: this.apellido_paterno,
          apellido_materno: this.apellido_materno,
          fecha_nacimiento: this.fecha_nacimiento,
          edad: this.edad,
          foto: this.foto,
          lugar_nacimiento: this.lugar_nacimiento,
          nacionalidad: this.nacionalidad,
          estado_civil: this.estado_civil,
          sexo: this.sexo,
          direccion_residencia: this.direccion_residencia,
          telefono: this.telefono,
          celular: this.celular,
          email: this.email,
          tipo_sangre: this.tipo_sangre,
          profesion: this.profesion,
          discapacidad: this.discapacidad,
          discap_porcentaje: this.discap_porcentaje,
          tipo_iden_discap: this.tipo_iden_discap,
          num_iden_discap: this.num_iden_discap,
          num_iess: this.num_iess,
          num_libreta_militar: this.num_libreta_militar,
          banco: this.banco,
          tipo_cuenta: this.tipo_cuenta,
          num_cuenta: this.num_cuenta,
          num_cargas: this.num_cargas,
          estado: this.estado,
          observaciones: this.observaciones,
          //tabla empleado - cargo
          fecha_ingreso: this.fecha_ingreso,
          fecha_salida: this.fecha_salida,
          tipo_horario: this.tipo_horario,
          tipo_contrato: this.tipo_contrato,
          bonos: this.bonos,
          aporte_iess: this.aporte_iess,
          fondo_reserva: this.fondo_reserva,
          decimo_tercero: this.decimo_tercero,
          decimo_cuarto: this.decimo_cuarto,
          cuenta_contable: this.cuenta_contable,
          observaciones: this.observaciones_dos,
          idcargo: this.idcargo,
          idgrupo: this.idgrupo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            text: "Registro actualizado exitosamente",
            color: "primary"
          });
          this.$router.push("/nomina/empleados");
        })
        .catch(err => {
          console.log(err);
        });
    },
    //metodos para segunda tabla
    guardarCargo() {
      /*if (this.validarC()) {
        return;
      }*/
      axios
        .post("/api/cargo/agregar", {
          //tabla empleado
          //campos segunda tabla - empleado-cargo
          fecha_ingreso: this.fecha_ingreso,
          fecha_salida: this.fecha_salida,
          tipo_horario: this.tipo_horario,
          tipo_contrato: this.tipo_contrato,
          bonos: this.bonos,
          aporte_iess: this.aporte_iess,
          fondo_reserva: this.fondo_reserva,
          decimo_tercero: this.decimo_tercero,
          decimo_cuarto: this.decimo_cuarto,
          cuenta_contable: this.cuenta_contable,
          observaciones: this.observaciones_dos,
          id_empleado: this.id_empleado,
          idcargo: this.idcargo,
          idgrupo: this.idgrupo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
        })
        .catch(err => {});
    },
    /*
    listarCargo(id) {
      axios.put("/api/cargo/abrir", { id: id }).then(res => {
        let data = res.data[0];
        this.id_empleado = data.id_empleado;
        this.apellido_paterno = data.apellido_paterno;
      });
    },
    */
    editarCargo() {
      /*if (this.validarC()) {
        return;
      }*/
      axios
        .put("/api/cargo/editar", {
          id: this.$route.params.id,
          //campos segunda tabla - empleado-cargo
          fecha_ingreso: this.fecha_ingreso,
          fecha_salida: this.fecha_salida,
          tipo_horario: this.tipo_horario,
          tipo_contrato: this.tipo_contrato,
          bonos: this.bonos,
          aporte_iess: this.aporte_iess,
          fondo_reserva: this.fondo_reserva,
          decimo_tercero: this.decimo_tercero,
          decimo_cuarto: this.decimo_cuarto,
          cuenta_contable: this.cuenta_contable,
          observaciones: this.observaciones_dos,
          id_empleado: this.id_empleado,
          idcargo: this.idcargo,
          idgrupo: this.idgrupo
        })
        .then(res => {
          this.$vs.notify({
            text: "Registro actualizado exitosamente",
            color: "primary"
          });
          this.$router.push("/nomina/empleados");
        })
        .catch(err => {
          console.log(err);
        });
    },
    //metodos para tercera tabla
    guardarCarga() {
      /*if (this.validar()) {
        return;
      }*/
      axios
        .post("/api/carga/agregar", {
          car_dni: this.car_dni,
          car_nombre: this.car_nombre,
          car_fecha_nacimiento: this.car_fecha_nacimiento,
          car_edad: this.car_edad,
          car_ocupacion: this.car_ocupacion,
          car_discapacidad: this.car_discapacidad,
          car_discap_porcentaje: this.car_discap_porcentaje,
          idempleado: this.id_empleado
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
        })
        .catch(err => {});
    },
    listarCarga(id) {
      axios.put("/api/carga/abrir", { id: id }).then(res => {
        let data = res.data[0];
        this.id_empleado = data.id_empleado;
      });
    },
    /*listarCargas(page4, buscar4) {
      let me = this;
      var url =
        "/api/carga?page=" +
        page4 +
        "&buscar=" +
        buscar4 +
        "&cantidadp=" +
        cantidadp4;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenidocargas = respuesta.recupera.data;
          me.pagination4 = respuesta.pagination;
          if (cantidadp4 > me.pagination4.total) {
            cantidadp4 = me.pagination4.total;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },*/
    editarCarga() {
      /*if (this.validar()) {
        return;
      }*/
      axios
        .put("/api/carga/editar", {
          id: this.$route.params.id,
          //campos segunda tabla - empleado-cargo
          car_dni: this.car_dni,
          car_nombre: this.car_nombre,
          car_fecha_nacimiento: this.car_fecha_nacimiento,
          car_edad: this.car_edad,
          car_ocupacion: this.car_ocupacion,
          car_discapacidad: this.car_discapacidad,
          car_discap_porcentaje: this.car_discap_porcentaje,
          idempleado: this.id_empleado
        })
        .then(res => {
          this.$vs.notify({
            text: "Registro actualizado exitosamente",
            color: "primary"
          });
          this.$router.push("/nomina/empleados");
        })
        .catch(err => {
          console.log(err);
        });
    },
    eliminarCarga(id) {
      axios
        //Envia id
        .delete("/api/empleado/eliminar/" + id);
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: "¿Desea Elimnar este registro?",
        accept: this.acceptAlert
      });
    },
    //metodos para cuarta tabla - documentacion
    guardarDocumento() {
      /*if (this.validar()) {
        return;
      }*/
      axios
        .post("/api/documento/agregar", {
          doc_url: this.doc_url,
          doc_estado: this.doc_estado,
          iddocu: this.iddocu,
          idemple: this.id_empleado
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
        })
        .catch(err => {});
    },
    listarDocumento(id) {
      axios.put("/api/documento/abrir", { id: id }).then(res => {
        let data = res.data[0];
        this.id_empleado = data.id_empleado;
        this.apellido_paterno = data.apellido_paterno;
      });
    },
    editarDocumento() {
      /*if (this.validar()) {
        return;
      }*/
      axios
        .put("/api/documento/editar", {
          id: this.$route.params.id,
          //campos segunda tabla - empleado-cargo
          doc_url: this.doc_url,
          doc_estado: this.doc_estado,
          iddocu: this.iddocu,
          idemple: this.id_empleado
        })
        .then(res => {
          this.$vs.notify({
            text: "Registro actualizado exitosamente",
            color: "primary"
          });
          this.$router.push("/nomina/empleados");
        })
        .catch(err => {
          console.log(err);
        });
    },
    //metodos para cuarta tabla - inasistencias
    guardarFalta() {
      if (this.validar()) {
        return;
      }
      axios
        .post("/api/calendario/agregar", {
          doc_url: this.doc_url,
          doc_estado: this.doc_estado,
          iddocu: this.iddocu,
          idemple: this.id_empleado
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
        })
        .catch(err => {});
    },
    listarFalta(id) {
      axios.put("/api/calendario/abrir", { id: id }).then(res => {
        let data = res.data[0];
        this.id_empleado = data.id_empleado;
        this.apellido_paterno = data.apellido_paterno;
      });
    },
    editarFalta() {
      /*if (this.validar()) {
        return;
      }*/
      axios
        .put("/api/calendario/editar", {
          id: this.$route.params.id,
          //campos segunda tabla - empleado-cargo
          doc_url: this.doc_url,
          doc_estado: this.doc_estado,
          iddocu: this.iddocu,
          idemple: this.idemple
        })
        .then(res => {
          this.$vs.notify({
            text: "Registro actualizado exitosamente",
            color: "primary"
          });
          this.$router.push("/nomina/empleados");
        })
        .catch(err => {
          console.log(err);
        });
    },
    //validaciones
    validarE() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.error = 0;
      this.errortipo_dni = [];
      this.errordni = [];
      this.errorprimer_nombre = [];
      this.errorsegundo_nombre = [];
      this.errorapellido_paterno = [];
      this.errorapellido_materno = [];
      this.errorfecha_nacimiento = [];
      this.erroredad = [];
      this.errorlugar_nacimiento = [];
      this.errornacionalidad = [];
      this.errorestado_civil = [];
      this.errorsexo = [];
      this.errordireccion_residencia = [];
      this.errorcelular = [];
      this.erroremail = [];
      this.errortipo_sangre = [];
      this.errorprofesion = [];
      this.errordiscapacidad = [];
      this.errordiscap_porcentaje = [];
      this.errortipo_iden_discap = [];
      this.errorbanco = [];
      this.errortipo_cuenta = [];
      this.errornum_cuenta = [];
      this.errornum_cargas = [];
      this.errorestado = [];
      this.errorprovincia = [];
      this.errorcanton = [];
      this.errorparroquia = [];

      if (!this.tipo_dni) {
        this.errortipo_dni.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      /*
      if (!this.dni) {
        this.errordni.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      */
      if (!this.primer_nombre) {
        this.errorprimer_nombre.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.segundo_nombre) {
        this.errorsegundo_nombre.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.apellido_paterno) {
        this.errorapellido_paterno.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.apellido_materno) {
        this.errorapellido_materno.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.fecha_nacimiento) {
        this.errorfecha_nacimiento.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.edad) {
        this.erroredad.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.lugar_nacimiento) {
        this.errorlugar_nacimiento.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.nacionalidad) {
        this.errornacionalidad.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.estado_civil) {
        this.errorestado_civil.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.sexo) {
        this.errorsexo.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.direccion_residencia) {
        this.errordireccion_residencia.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.celular) {
        this.errorcelular.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.validaremail(this.email)) {
        this.erroremail.push("Email no valido");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.tipo_sangre) {
        this.errortipo_sangre.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.profesion) {
        this.errorprofesion.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.discapacidad) {
        this.errordiscapacidad.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.discap_porcentaje) {
        this.errordiscap_porcentaje.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.tipo_iden_discap) {
        this.errortipo_iden_discap.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.banco) {
        this.errorbanco.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.tipo_cuenta) {
        this.errortipo_cuenta.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.num_cuenta) {
        this.errornum_cuenta.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.num_cargas) {
        this.errornum_cargas.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.estado) {
        this.errorestado.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.provincia) {
        this.errorprovincia.push("Campo obligatorio");
        this.error = 1;
      }

      if (!this.canton) {
        this.errorcanton.push("Campo obligatorio");
        this.error = 1;
      }

      if (!this.parroquia) {
        this.errorparroquia.push("Campo obligatorio");
        this.error = 1;
      }
      if (
        this.errortipo_dni.length ||
        this.errordni.length ||
        this.errorprimer_nombre.length ||
        this.errorsegundo_nombre.length ||
        this.errorapellido_paterno.length ||
        this.errorapellido_materno.length ||
        this.errorfecha_nacimiento.length ||
        this.erroredad.length ||
        this.errorlugar_nacimiento.length ||
        this.errornacionalidad.length ||
        this.errorestado_civil.length ||
        this.errorsexo.length ||
        this.errordireccion_residencia.length ||
        this.errorcelular.length ||
        this.erroremail.length ||
        this.errortipo_sangre.length ||
        this.errorprofesion.length ||
        this.errordiscapacidad.length ||
        this.errordiscap_porcentaje.length ||
        this.errortipo_iden_discap.length ||
        this.errorbanco.length ||
        this.errortipo_cuenta.length ||
        this.errornum_cuenta.length ||
        this.errornum_cargas.length ||
        this.errorestado.length ||
        this.errorprovincia.length ||
        this.errorcanton.length ||
        this.errorparroquia.length
      )
        return this.error;
    },
    validaremail(email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
    successUpload() {
      this.$vs.notify({
        color: "success",
        title: "Upload Success",
        text: "Lorem ipsum dolor sit amet, consectetur"
      });
    },
    validarcedula($event) {
      this.error = 0;
      this.errordni = [];
      if (this.dni.length < 10) {
        this.errordni.push("Cedula invalida");
        this.error = 1;
        return;
      }
      if (
        typeof this.dni == "string" &&
        this.dni.length == 10 &&
        /^\d+$/.test(this.dni)
      ) {
        var digitos = this.dni.split("").map(Number);
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
            this.errordni = [];
          } else {
            this.errordni.push("Cédula inválida");
            this.error = 1;
            return;
          }
        } else {
          this.errordni.push("Cédula inválida");
          this.error = 1;
          return;
        }
      }
      return false;
    },
    //validaciones para cargo
    validarC() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.error = 0;

      //errores para cargo - empleado
      this.errorcar_dni = [];
      this.errortipo_horario = [];
      this.errortipo_contrato = [];
      this.erroraporte_iess = [];
      this.errorfondo_reserva = [];
      this.errordecimo_tercero = [];
      this.errordecimo_cuarto = [];
      this.errorcuenta_contable = [];
      this.errordepartamento = [];
      this.errorarea_trabajo = [];
      this.errorcargo = [];

      //condiciones para error

      if (!this.car_dni) {
        this.errorcar_dni.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.tipo_horario) {
        this.errortipo_horario.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.tipo_contrato) {
        this.errortipo_contrato.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.aporte_iess) {
        this.erroraporte_iess.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.fondo_reserva) {
        this.errorfondo_reserva.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.decimo_tercero) {
        this.errordecimo_tercero.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.decimo_cuarto) {
        this.errordecimo_cuarto.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.cuenta_contable) {
        this.errorcuenta_contable.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      return this.error;
    },
    /*
    validarCarga() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.error = 0;

      //errores para cargo - empleado
      this.errorfecha_ingreso = [];
      this.errortipo_horario = [];
      this.errortipo_contrato = [];
      this.erroraporte_iess = [];
      this.errorfondo_reserva = [];
      this.errordecimo_tercero = [];
      this.errordecimo_cuarto = [];
      this.errorcuenta_contable = [];

      //condiciones para error

      if (!this.fecha_ingreso) {
        this.errorfecha_ingreso.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.tipo_horario) {
        this.errortipo_horario.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.tipo_contrato) {
        this.errortipo_contrato.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.aporte_iess) {
        this.erroraporte_iess.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.fondo_reserva) {
        this.errorfondo_reserva.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.decimo_tercero) {
        this.errordecimo_tercero.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.decimo_cuarto) {
        this.errordecimo_cuarto.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.cuenta_contable) {
        this.errorcuenta_contable.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      return this.error;
    },
    validarCal() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.error = 0;

      //errores para cargo - empleado
      this.errorfecha_ingreso = [];
      this.errortipo_horario = [];
      this.errortipo_contrato = [];
      this.erroraporte_iess = [];
      this.errorfondo_reserva = [];
      this.errordecimo_tercero = [];
      this.errordecimo_cuarto = [];
      this.errorcuenta_contable = [];

      //condiciones para error

      if (!this.fecha_ingreso) {
        this.errorfecha_ingreso.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.tipo_horario) {
        this.errortipo_horario.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.tipo_contrato) {
        this.errortipo_contrato.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.aporte_iess) {
        this.erroraporte_iess.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.fondo_reserva) {
        this.errorfondo_reserva.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.decimo_tercero) {
        this.errordecimo_tercero.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.decimo_cuarto) {
        this.errordecimo_cuarto.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      if (!this.cuenta_contable) {
        this.errorcuenta_contable.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }

      return this.error;
    },
    */
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
    sololetras: function($event) {
      var letra = /^\d*\.?\d*$/;
      if (
        $event.charCode === 0 ||
        !letra.test(String.fromCharCode($event.charCode))
      ) {
        return true;
      } else {
        $event.preventDefault();
      }
    },
    calcularEdad: function(fecha) {
      if (typeof fecha != "string" && fecha && esNumero(fecha.getTime())) {
        fecha = formatDate(fecha, "yyyy-MM-dd");
      }

      var values = fecha.split("-");
      var dia = values[2];
      var mes = values[1];
      var ano = values[0];

      // cogemos los valores actuales
      var fecha_hoy = new Date();
      var ahora_ano = fecha_hoy.getYear();
      var ahora_mes = fecha_hoy.getMonth() + 1;
      var ahora_dia = fecha_hoy.getDate();

      // realizamos el calculo
      var edad = ahora_ano + 1900 - ano;
      if (ahora_mes < mes) {
        edad--;
      }
      if (mes == ahora_mes && ahora_dia < dia) {
        edad--;
      }
      if (edad > 1900) {
        edad -= 1900;
      }

      // calculamos los meses
      var meses = 0;

      if (ahora_mes > mes && dia > ahora_dia) meses = ahora_mes - mes - 1;
      else if (ahora_mes > mes) meses = ahora_mes - mes;
      if (ahora_mes < mes && dia < ahora_dia) meses = 12 - (mes - ahora_mes);
      else if (ahora_mes < mes) meses = 12 - (mes - ahora_mes + 1);
      if (ahora_mes == mes && dia > ahora_dia) meses = 11;

      // calculamos los dias
      var dias = 0;
      if (ahora_dia > dia) dias = ahora_dia - dia;
      if (ahora_dia < dia) {
        var ultimoDiaMes = new Date(ahora_ano, ahora_mes - 1, 0);
        dias = ultimoDiaMes.getDate() - (dia - ahora_dia);
      }

      var edad_string = edad + " años, " + meses + " meses y " + dias + " días";
      return alert(edad_string);
    },
    valida_fecha: function($event) {
      var patron = new RegExp(
        "^(19|20)+([0-9]{2})([-])([0-9]{1,2})([-])([0-9]{1,2})$"
      );

      if (fecha.search(patron) == 0) {
        var values = fecha.split("-");
        if (isValidDate(values[2], values[1], values[0])) {
          return true;
        }
      }
      return false;
    },
    cambio(c) {
      this.tipodiscap = 0;

      if (c == "Visual") {
        this.tipodiscap = 1;
      } else if (c == "Física") {
        this.tipodiscap = 1;
      } else if (c == "Auditiva") {
        this.tipodiscap = 1;
      } else if (c == "Mental") {
        this.tipodiscap = 1;
      } else if (c == "Verbal") {
        this.tipodiscap = 1;
      } else if (c == "Ninguna") {
        this.discap_porcentaje = null;
        this.tipo_iden_discap = null;
        this.num_iden_discap = null;
      }
    },
    octl() {
      if (this.ocult == true) {
        this.ocult = false;
      } else {
        this.ocult = true;
      }
    },
    getGrupoOcu: function() {
      axios.get("/api/grupo_ocupacional/"+this.usuario.id_empresa).then(res => {
        this.grupo_ocupacional2 = res.data;
      });
    },
    getBanco: function() {
      axios.get("/api/banco").then(
        function(response) {
          this.banco2 = response.data;
        }.bind(this)
      );
    },
    getNacionalidad: function() {
      axios.get("/api/nacionalidad").then(
        function(response) {
          this.nacionalidad2 = response.data;
        }.bind(this)
      );
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
    getDocumento: function() {
      axios.get("/api/documento/"+ this.usuario.id_empresa).then( res => {
        this.doc_descripcion2 = res.data;
      });
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
    getDepartamento: function() {
      axios.get("/api/departamento/"+this.usuario.id_empresa).then( res =>{
          this.departamento2 = res.data; 
      });
    },
    getArea: function() {
      //this.canton = ""; coment
      axios.get("/api/area/"+this.usuario.id_empresa).then( res => {
        this.area2 = res.data;
      });
    },
    getCargo: function() {
      axios
        .get("/api/cargo", {
          params: {
            id_area: this.area_trabajo
          }
        })
        .then(
          function(response) {
            this.cargo2 = response.data;
          }.bind(this)
        );
    }
  },
  mounted() {
    if (this.$route.params.id) {
      var id = this.$route.params.id;
      this.listarEmpleado(id);
    }
    this.getProvincias();
    this.getCiudades();
    this.getParroquias();
    this.getDepartamento();
    this.getArea();
    this.getCargo();
    this.getBanco();
    this.getNacionalidad();
    this.getGrupoOcu();
    this.getDocumento();
  }
};
</script>
<style scoped>
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
</style>