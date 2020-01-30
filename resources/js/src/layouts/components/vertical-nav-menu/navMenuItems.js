import store from "../../../store/store";
import $ from "jquery";
if (store.state.Roles.length) {
    var pep = store.state.Roles;
    //empresa
    var empresa_ver = false;
    var empresa_array_ver = $.grep(pep, function(e) {
        return e.value == 1;
    });
    if (empresa_array_ver.length) {
        empresa_ver = empresa_array_ver[0].ver;
    }
    //Establecimiento
    var establecimiento_ver = false;
    var establecimiento_array_ver = $.grep(pep, function(e) {
        return e.value == 2;
    });
    if (establecimiento_array_ver.length) {
        establecimiento_ver = establecimiento_array_ver[0].ver;
    }
    //punto de emision
    var emision_ver = false;
    var emision_array_ver = $.grep(pep, function(e) {
        return e.value == 3;
    });
    if (emision_array_ver.length) {
        emision_ver = emision_array_ver[0].ver;
    }
    //usuarios
    var usuario_ver = false;
    var usuario_array_ver = $.grep(pep, function(e) {
        return e.value == 4;
    });
    if (usuario_array_ver.length) {
        usuario_ver = usuario_array_ver[0].ver;
    }
    //tipo comprobante
    var comprobante_ver = false;
    var comprobante_array_ver = $.grep(pep, function(e) {
        return e.value == 6;
    });
    if (comprobante_array_ver.length) {
        comprobante_ver = comprobante_array_ver[0].ver;
    }
    //impuestos retenciones
    var retenciones_ver = false;
    var retenciones_array_ver = $.grep(pep, function(e) {
        return e.value == 7;
    });
    if (retenciones_array_ver.length) {
        retenciones_ver = retenciones_array_ver[0].ver;
    }
    //tipo sustento
    var sustento_ver = false;
    var sustento_array_ver = $.grep(pep, function(e) {
        return e.value == 8;
    });
    if (sustento_array_ver.length) {
        sustento_ver = sustento_array_ver[0].ver;
    }
    //plan de cuentas
    var cuentas_ver = false;
    var cuentas_array_ver = $.grep(pep, function(e) {
        return e.value == 9;
    });
    if (cuentas_array_ver.length) {
        cuentas_ver = cuentas_array_ver[0].ver;
    }
    //asientos contable
    var asientos_ver = false;
    var asientos_array_ver = $.grep(pep, function(e) {
        return e.value == 10;
    });
    if (asientos_array_ver.length) {
        asientos_ver = asientos_array_ver[0].ver;
    }
    //nota de credito
    var credito_ver = false;
    var credito_array_ver = $.grep(pep, function(e) {
        return e.value == 11;
    });
    if (credito_array_ver.length) {
        credito_ver = credito_array_ver[0].ver;
    }
    var credito_crear = false;
    var credito_array_crear = $.grep(pep, function(e) {
        return e.value == 11;
    });
    if (credito_array_crear.length) {
        credito_crear = credito_array_crear[0].editar;
    }
    //facturas
    var factura_ver = false;
    var factura_array_ver = $.grep(pep, function(e) {
        return e.value == 12;
    });
    if (factura_array_ver.length) {
        factura_ver = factura_array_ver[0].ver;
    }
    //proforma
    var proforma_ver = false;
    var proforma_array_ver = $.grep(pep, function(e) {
        return e.value == 13;
    });
    if (proforma_array_ver.length) {
        proforma_ver = proforma_array_ver[0].ver;
    }
    //pago_Clinete
    var pago_cliente_ver = false;
    var pago_cliente_array_ver = $.grep(pep, function(e) {
        return e.value == 14;
    });
    if (pago_cliente_array_ver.length) {
        pago_cliente_ver = pago_cliente_array_ver[0].ver;
    }
    //cliente
    var cliente_ver = false;
    var cliente_array_ver = $.grep(pep, function(e) {
        return e.value == 15;
    });
    if (cliente_array_ver.length) {
        cliente_ver = cliente_array_ver[0].ver;
    }
    //vendedor
    var vendedor_ver = false;
    var vendedor_array_ver = $.grep(pep, function(e) {
        return e.value == 16;
    });
    if (vendedor_array_ver.length) {
        vendedor_ver = vendedor_array_ver[0].ver;
    }
    //bodega
    var bodega_ver = false;
    var bodega_array_ver = $.grep(pep, function(e) {
        return e.value == 17;
    });
    if (bodega_array_ver.length) {
        bodega_ver = bodega_array_ver[0].ver;
    }
    //catalogo
    var catalogo_ver = false;
    var catalogo_array_ver = $.grep(pep, function(e) {
        return e.value == 18;
    });
    if (catalogo_array_ver.length) {
        catalogo_ver = catalogo_array_ver[0].ver;
    }
    //nomina
    var nomina_ver = false;
    var nomina_array_ver = $.grep(pep, function(e) {
        return e.value == 19;
    });
    if (nomina_array_ver.length) {
        nomina_ver = nomina_array_ver[0].ver;
    }
    //proveedor
    var proveedor_ver = false;
    var proveedor_array_ver = $.grep(pep, function(e) {
        return e.value == 20;
    });
    if (proveedor_array_ver.length) {
        proveedor_ver = proveedor_array_ver[0].ver;
    }
    //facturas compra
    var facturas_compra_ver = false;
    var facturas_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 21;
    });
    if (facturas_compra_array_ver.length) {
        facturas_compra_ver = facturas_compra_array_ver[0].ver;
    }
    //importacion
    var importacion_compra_ver = false;
    var importacion_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 22;
    });
    if (importacion_compra_array_ver.length) {
        importacion_compra_ver = importacion_compra_array_ver[0].ver;
    }
    //cuentas por pagar
    var cuentas_pagar_compra_ver = false;
    var cuentas_pagar_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 23;
    });
    if (cuentas_pagar_compra_array_ver.length) {
        cuentas_pagar_compra_ver = cuentas_pagar_compra_array_ver[0].ver;
    }
    //formula
    var formula_compra_ver = false;
    var formula_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 24;
    });
    if (formula_compra_array_ver.length) {
        formula_compra_ver = formula_compra_array_ver[0].ver;
    }
    //proceso producion
    var proceso_produccion_compra_ver = false;
    var proceso_produccion_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 25;
    });
    if (proceso_produccion_compra_array_ver.length) {
        proceso_produccion_compra_ver =
            proceso_produccion_compra_array_ver[0].ver;
    }
    //activos fijos
    var activos_fijos_compra_ver = false;
    var activos_fijos_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 26;
    });
    if (activos_fijos_compra_array_ver.length) {
        activos_fijos_compra_ver = activos_fijos_compra_array_ver[0].ver;
    }
    //calendario
    var calendario_compra_ver = false;
    var calendario_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 27;
    });
    if (calendario_compra_array_ver.length) {
        calendario_compra_ver = calendario_compra_array_ver[0].ver;
    }
    //orden-compra
    var orden_compra_ver = false;
    var orden_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 28;
    });
    if (orden_compra_array_ver.length) {
        orden_compra_ver = orden_compra_array_ver[0].ver;
    }
    //ingresos
    var ingreso_egreso_ver = false;
    var ingreso_egreso_array_ver = $.grep(pep, function(e) {
        return e.value == 29;
    });
    if (ingreso_egreso_array_ver.length) {
        ingreso_egreso_ver = ingreso_egreso_array_ver[0].ver;
    }
    //rol-pago
    var rol_pago_ver = false;
    var rol_pago_array_ver = $.grep(pep, function(e) {
        return e.value == 30;
    });
    if (rol_pago_array_ver.length) {
        rol_pago_ver = rol_pago_array_ver[0].ver;
    }
}
var cont = [];
if(store.state.AppActiveUser.id_rol==3 && store.state.AppActiveUser.id_rol==3){
    cont.push({
        url: "/administrar/empresa",
        name: "AdministrarNegocio",
        icon: "EditIcon",
        i18n: "Empresas"
    });
}else{
    cont.push({
        url: "/",
        name: "Inicio",
        slug: "/",
        icon: "HomeIcon",
        i18n: "Inicio"
    });
    if (
        sustento_ver == 1 ||
        retenciones_ver == 1 ||
        comprobante_ver == 1 ||
        usuario_ver == 1 ||
        emision_ver == 1 ||
        establecimiento_ver == 1 ||
        empresa_ver == 1 ||
        store.state.AppActiveUser.id_rol == 1
    ) {
        cont.push({
            url: null,
            name: "AdministrarNegocio",
            icon: "EditIcon",
            i18n: "Administrar",
            submenu: [
                {
                    url: "/administrar/empresa",
                    name: "PerfilNeg",
                    slug: "empresa",
                    i18n: "Empresas"
                },
                {
                    url: "/administrar/establecimiento",
                    name: "Establecimeinto",
                    slug: "establecimiento",
                    i18n: "Establecimiento"
                },
                {
                    url: "/administrar/emision",
                    name: "PtoEmision",
                    slug: "emision",
                    i18n: "Puntos_de_Emision"
                },
                {
                    url: "/administrar/usuarios",
                    name: "Usuarios",
                    slug: "usuarios",
                    i18n: "Usuarios"
                },
                {
                    url: null,
                    name: "Sri",
                    // icon: "EditIcon",
                    i18n: "Sri",
                    submenu: [
                        {
                            url: "/administrar/sri/tipo-comprobante",
                            name: "Comprobantes",
                            slug: "tipcomprobante",
                            icon: "BriefcaseIcon",
                            i18n: "Tipo_Comprobante"
                        },
                        {
                            url: "/administrar/sri/impuestos-rentenciones",
                            name: "ImpRetencion",
                            slug: "imprentenciones",
                            icon: "BriefcaseIcon",
                            i18n: "Retenciones"
                        },
                        {
                            url: "/administrar/sri/tipo-sustento",
                            name: "Sustento",
                            slug: "tiposustento",
                            icon: "BriefcaseIcon",
                            i18n: "Tipo_Sustento"
                        }
                    ]
                }
            ]
        });
    }
    if (
        asientos_ver == 1 ||
        cuentas_ver == 1 ||
        store.state.AppActiveUser.id_rol == 1
    ) {
        cont.push({
            url: null,
            name: "Contabilidad",
            icon: "BriefcaseIcon",
            i18n: "Contabilidad",
            submenu: [
                {
                    url: "/contabilidad/plan-cuentas",
                    name: "PLanCuentas",
                    slug: "cuentas",
                    icon: "BriefcaseIcon",
                    i18n: "Plan_Cuentas"
                },
                {
                    url: "/contabilidad/asientos-contables",
                    name: "AsientosContables",
                    slug: "asientos",
                    icon: "BriefcaseIcon",
                    i18n: "Asientos_Contables"
                }
            ]
        });
    }
    if (
        vendedor_ver == 1 ||
        cliente_ver == 1 ||
        pago_cliente_ver == 1 ||
        proforma_ver == 1 ||
        factura_ver == 1 ||
        credito_ver == 1 ||
        store.state.AppActiveUser.id_rol == 1
    ) {
        cont.push({
            url: null,
            name: "Facturacion",
            icon: "EditIcon",
            i18n: "Facturacion",
            submenu: [
                {
                    url: "/facturacion/nota-credito",
                    name: "NotaCredito",
                    slug: "notacred",
                    i18n: "Nota_Credito"
                },
                {
                    url: "/facturacion/factura-venta",
                    name: "Factura",
                    slug: "facturasventa",
                    i18n: "Factura_Venta"
                },
                {
                    url: "/facturacion/proforma",
                    name: "Proforma",
                    slug: "proforma",
                    i18n: "Proforma"
                },
                {
                    url: "/facturacion/pago-cliente",
                    name: "PagoCliente",
                    slug: "pago",
                    icon: "CreditCardIcon",
                    i18n: "Pago_Cliente"
                },
                {
                    url: "/facturacion/clientes",
                    name: "Contactos",
                    slug: "contacto",
                    i18n: "Clientes"
                },
                {
                    url: "/facturacion/vendedor",
                    name: "Vendedor",
                    slug: "vendedor",
                    i18n: "Vendedor"
                }
            ]
        });
    }
    if (
        catalogo_ver == 1 ||
        bodega_ver == 1 ||
        store.state.AppActiveUser.id_rol == 1
    ) {
        cont.push({
            url: null,
            name: "Inventario",
            icon: "CheckSquareIcon",
            i18n: "Inventario",
            submenu: [
                {
                    url: "/inventario/bodega",
                    name: "Bodega",
                    slug: "Bodega",
                    i18n: "Bodega"
                },
                {
                    url: "/inventario/catalogo",
                    name: "Catalogo",
                    slug: "catalogo",
                    i18n: "Catalogo"
                }
            ]
        });
    }
    if (nomina_ver == 1 ||
         ingreso_egreso_ver ==1 ||
        rol_pago_ver == 1 || 
        store.state.AppActiveUser.id_rol == 1) {
        cont.push({
            url: null,
            name: "Nomina",
            icon: "BoxIcon",
            i18n: "Nomina",
            submenu: [
                {
                    url: "/nomina/empleados",
                    name: "listarempleados",
                    slug: "listarempleados",
                    i18n: "Empleados"
                },
                {
                    url: "/nomina/ingreso-egreso",
                    name: "listaringreso-egreso",
                    slug: "listaringreso-egreso",
                    i18n: "Ingresos_Egresos"
                },
                {
                    url: "/nomina/rol-pagos",
                    name: "listarrol-pagos",
                    slug: "listarrol-pagos",
                    i18n: "Rol_Pagos"
                }
            ]
        });
    }
    if (
        cuentas_pagar_compra_ver == 1 ||
        facturas_compra_ver == 1 ||
        proveedor_ver == 1 ||
        orden_compra_ver ==1 ||
        store.state.AppActiveUser.id_rol == 1
    ) {
        cont.push({
            url: null,
            name: "Proveedor",
            icon: "CreditCardIcon",
            slug: "lista",
            i18n: "Compras",
            submenu: [
                {
                    url: "/compras/proveedor",
                    name: "Proveedor",
                    //icon: "CreditCardIcon",
                    slug: "lista",
                    i18n: "Proveedor"
                },
                {
                    url: "/compras/orden-compra",
                    name: "Ordencompra",
                    slug: "compra",
                    i18n: "Orden_Compra"
                },
                {
                    url: "/compras/factura-compra",
                    name: "FacturaCompra",
                    slug: "facturascompra",
                    i18n: "Factura_Compra"
                },
                
                {
                    url: "/compras/cuentas-por-pagar",
                    name: "Cuentapagar",
                    slug: "pagar",
                    i18n: "Cuentas_Pagar"
                }
            ]
        });
    }
    if (
        importacion_compra_ver == 1 ||
        store.state.AppActiveUser.id_rol == 1
    ) {
        cont.push({
    
        url: null,
        name: "Importacion",
        slug: "importacion",
        icon: "PieChartIcon",
        i18n: "Importacion",
        submenu:[
            {
                url: "/compras/importacion",
                name: "RegImportacion",
                slug: "importacion",
                i18n: "Reguistro_Importacion"
            },
            {
                url: "/compras/liquidacion",
                name: "Liquidacion",
                slug: "Liquidacion",
                i18n: "Liquidacion",
            },
        ]
    });
}
    if (
        proceso_produccion_compra_ver == 1 ||
        formula_compra_ver == 1 ||
        store.state.AppActiveUser.id_rol == 1
    ) {
        cont.push({
            url: null,
            name: "Produccion",
            icon: "FileTextIcon",
            i18n: "Produccion",
            submenu: [
                {
                    url: "/produccion/formula",
                    name: "Formula",
                    slug: "formula",
                    i18n: "Formula"
                },
                {
                    url: "/produccion/proceso-produccion",
                    name: "Proceso-produccion",
                    slug: "proceso-produccion",
                    i18n: "Proceso_Produccion"
                }
            ]
        });
    }
    if (activos_fijos_compra_ver == 1 || store.state.AppActiveUser.id_rol == 1) {
        cont.push({
            url: "/activos-fijos",
            name: "ActivosFijos",
            icon: "LayoutIcon",
            slug: "fijos",
            i18n: "Activos_Fijos"
        });
    }
    if (calendario_compra_ver == 1 || store.state.AppActiveUser.id_rol == 1) {
        cont.push({
            url: "/calendario",
            name: "Calendario",
            slug: "calendario",
            icon: "CalendarIcon",
            i18n: "Calendario"
        });
    }
}
export default cont;
