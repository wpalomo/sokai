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
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/facturacion/factura-venta/agregar">Agregar</vs-button>
          </div>
        </div>
      </div>
      <vs-table stripe :data="contenido">
        <template slot="thead">
          <vs-th>No.</vs-th>
          <vs-th>Cliente</vs-th>
          <vs-th>Fecha de Emisión</vs-th>
          <vs-th>Fecha de Autorización</vs-th>
          <vs-th class="text-center">Valor Total</vs-th>
          <vs-th class="text-center">Estado</vs-th>
          <vs-th class="text-center">Opciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="(datos,index) in data" :key="index">
            <vs-td v-if="datos.id_factura">{{datos.id_factura}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fecha_emision">{{datos.fecha_emision | fecha}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fecha_autorizacion && datos.respuesta=='Enviado'">{{datos.fecha_autorizacion | fechayhora}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.valor_total" class="text-center">{{datos.valor_total | currency}}</vs-td>
            <vs-td v-else class="text-center">-</vs-td>
            <vs-td v-if="datos.respuesta=='Enviado'" style="background: rgba(27, 255, 0, 0.19)" class="text-center">Autorizado</vs-td>
            <vs-td v-else style="background: rgba(255, 0, 0, 0.24)" class="text-center">No autorizado</vs-td>
            <vs-td class="whitespace-no-wrap text-center" >
              <vx-tooltip text="Enviar comprobantes al cliente" position="left" style="display: inline-flex;">
                <feather-icon
                  v-if="datos.respuesta=='Enviado' && editarrol"
                  icon="MailIcon"
                  svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                  @click.stop="facturaenvio(datos)"
                />
              </vx-tooltip>
              <vx-tooltip text="Reenviar Factura" position="top" style="display: inline-flex;">
                <feather-icon
                  v-if="datos.respuesta!='Enviado' && editarrol"
                  icon="SendIcon"
                  svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                  @click.stop="facturaenvio(datos)"
                />
              </vx-tooltip>
              <vx-tooltip text="Visualizar Facutra" position="top" style="display: inline-flex;" class="ml-1">
                <feather-icon
                  v-if="editarrol"
                  icon="EyeIcon"
                  svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                  @click="editar(datos.id_factura)"
                />
              </vx-tooltip>
              <vx-tooltip text="Cancelar factura" position="top" style="display: inline-flex;" class="ml-1">
                <feather-icon
                  v-if="eliminarrol"
                  icon="TrashIcon"
                  svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                  @click="editar(datos.id_factura)"
                />
              </vx-tooltip>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vx-card>
  </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
import $ from "jquery";
const axios = require("axios");
export default {
  components: {
    AgGridVue
  },
  filters: {
    fecha(data) {
      return moment(data).format("LL");
    },
    fechayhora(data) {
      return moment(data).format("LLL");
    }
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
        res = this.$store.state.Roles[11].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[11].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[11].eliminar;
      }
      return res;
    }
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
        count: 0
      },
      pagina: 1,
      cantidadp: 10,
      offset: 3,
      //buscador
      buscar: "",
      criterio: "secuencial",
      //otros valores
      gridApi: null,
      contenido: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      claveacceso:null,
      tipofactura:"factura",
    };
  },
  methods: {
    listar(page, buscar) {
      var url = "/api/facturas";
      var datos = {
        page: page,
        buscar: buscar,
        datos: this.usuario
      };
      axios.post(url, datos).then(res => {
        var respuesta = res.data;
        this.contenido = respuesta.recupera;
      });
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    editar(id) {
      this.$router.push(`/facturacion/factura-venta/${id}/ver`);
    },
    facturaenvio(dat) {
      this.$vs.notify({
        time:8000,
        title: "Validando factura al SRI",
        text: "Por favor Espere...",
        color: "primary",
      });
      this.recueidfact = dat.id_factura;
      this.claveacceso = dat.clave_acceso;
      var url = "/api/factura/xml_factura";
      axios.post(url, dat).then(res => {
        var firma = res.data.recupera.pass_firma;
        var claveacc = res.data.recupera.firma;
        var ruta_factura = "../server/" + this.usuario.id_empresa + "/comprobantes/factura/" + this.claveacceso + ".xml";
        var ruta_certificado = "/empresas/" + this.usuario.id_empresa + "/firma/" + claveacc;
        this.obtenerComprobanteFirmado_sri(ruta_certificado, firma, ruta_factura,this.tipofactura);
      });
    },
    facturacomprobantes(dat){
      this.$vs.notify({
        time:8000,
        title: "Enviando Comprobantes",
        text: "Por favor Espere...",
        color: "primary",
      });
      var service = 'Autorizacion Comprobante';
      this.recueidfact = dat.id_factura;
      var ca = this.claveacceso = dat.clave_acceso;
      axios.post("/api/autorizacionComprobantephp",{'service': service, 'claveAcceso': ca,"id_empresa": this.usuario.id_empresa,tipo:this.tipofactura}).then(respuestaAutorizacionComprobante => {
        this.$vs.notify({
          time:8000,
          title: "Factura Enviada",
          text: "La factura fue autorizada y enviada al correo exitosamente",
          color: "success"
        });
      }).catch(err =>{
        this.$vs.notify({
          time:8000,
          title: "Error en factura",
          text: "La autorización de la factura fue inválida, Intente mas tarde o revise si la factura fue borrada",
          color: "danger"
        });
      });
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
                                console.log(this.recueidfact);
                                console.log(tipofactura);
                                var enviourl = {estado: "Enviado",id: this.recueidfact,tipo:tipofactura};
                                $.ajax({
                                    type: 'POST',
                                    url: envioestado,
                                    data: enviourl,
                                    context: document.body
                                }).done(respuesta => {
                                  console.log(respuesta.data);
                                  this.$vs.notify({
                                      tithis: 8000,
                                      title: "Factura Enviada",
                                      text:"La factura se generó exitosamente",
                                      color: "success"
                                  });
                                }).catch( err => {
                                  console.log(err);
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
        }).catch( () => {
            this.$vs.notify({
                tithis: 8000,
                title: "Factura Erronea",
                text:"La factura no pudo ser validada, verifique la factura e Intente mas tarde",
                color: "danger"
            });
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
.text-center .vs-table-text{
    text-align: center;
  }
  .vs-table-text{
    display:block!important;
  }
</style>