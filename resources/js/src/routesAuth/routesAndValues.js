const routesAndValue = [
    {
        nombre: "Empresa",
        value: 1,
        ver: {
            up: [
                {
                    path: "/administrar/empresa",
                    name: "empresa",
                    component: () =>
                        import(
                            "../views/Administrar/Empresas/EmpresaLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Administrar"
                            },
                            {
                                title: "Empresa",
                                active: true
                            }
                        ],
                        pageTitle: "Empresa",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            ups: [
                {
                    path: "/administrar/empresa",
                    name: "empresa",
                    component: () =>
                        import(
                            "../views/Administrar/Empresas/EmpresaAgregar.vue"
                        ),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    // path: "/app/agregarEmpresa/:id",
                    path: "/administrar/empresa/:id/editar",
                    name: "editarEmpresa",
                    component: () =>
                        import(
                            "../views/Administrar/Empresas/EmpresaAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    // path: "/app/agregarEmpresa",
                    path: "/administrar/empresa/agregar",
                    name: "agregarEmpresa",
                    component: () =>
                        import(
                            "../views/Administrar/Empresas/EmpresaAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: [
                {
                    path: "/administrar/empresa",
                    name: "editarEmpresados",
                    component: () =>
                        import(
                            "../views/Administrar/Empresas/EmpresaAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/administrar/empresa",
                    name: "editarEmpresatres",
                    component: () =>
                        import(
                            "../views/Administrar/Empresas/EmpresaAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ]
        }
    },
    {
        nombre: "Establecimiento",
        value: 2,
        ver: {
            up: [
                {
                    path: "/administrar/establecimiento",
                    name: "establecimiento",
                    component: () =>
                        import(
                            "../views/Administrar/Establecimiento/EstablecimientoLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Administrar"
                            },
                            {
                                title: "Establecimiento",
                                active: true
                            }
                        ],
                        pageTitle: "Establecimiento",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/administrar/establecimiento/:id/editar",
                    name: "editaragestable",
                    component: () =>
                        import(
                            "../views/Administrar/Establecimiento/EstablecimientoAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Administración"
                            },
                            {
                                title: "Establecimiento",
                                active: true
                            }
                        ],
                        pageTitle: "Editar agestable",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/administrar/establecimiento/agregar",
                    name: "agestable",
                    component: () =>
                        import(
                            "../views/Administrar/Establecimiento/EstablecimientoAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Administración"
                            },
                            {
                                title: "Establecimiento",
                                active: true
                            }
                        ],
                        pageTitle: "Nuevo Establecimiento",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Punto de emision",
        value: 3,
        ver: {
            up: [
                {
                    path: "/administrar/emision",
                    name: "emision",
                    component: () =>
                        import(
                            "../views/Administrar/Punto_de_emision/PuntoDeEmisionLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Administrar"
                            },
                            {
                                title: "Emision",
                                active: true
                            }
                        ],
                        pageTitle: "Puntos de Emision",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/administrar/punto-de-emision/agregar",
                    name: "puntosemision",
                    component: () =>
                        import(
                            "../views/Administrar/Punto_de_emision/PuntoDeEmisionAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Administración"
                            },
                            {
                                title: "Puntos de Emisión",
                                active: true
                            }
                        ],
                        pageTitle: "Nuevo Puntos de Emisión",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/administrar/punto-de-emision/:id/editar",
                    name: "editarptemision",
                    component: () =>
                        import(
                            "../views/Administrar/Punto_de_emision/PuntoDeEmisionAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Administración"
                            },
                            {
                                title: "Puntos de Emisión",
                                active: true
                            }
                        ],
                        pageTitle: "Editar Nuevo Puntos de Emisión",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Usuarios",
        value: 4,
        ver: {
            up: [
                {
                    path: "/administrar/usuarios",
                    name: "usuarios",
                    component: () =>
                        import(
                            "../views/Administrar/Usuarios/UsuariosList.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Administrar"
                            },
                            {
                                title: "Usuario",
                                active: true
                            }
                        ],
                        pageTitle: "Usuario",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/administrar/usuarios/:id/editar",
                    name: "agregarUsuarioid",
                    component: () =>
                        import(
                            "../views/Administrar/Usuarios/UsuariosAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/administrar/usuarios/agregar",
                    name: "agregarUsuario",
                    component: () =>
                        import(
                            "../views/Administrar/Usuarios/UsuariosAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Tipo comprobante",
        value: 6,
        ver: {
            up: [
                {
                    path: "/administrar/sri/tipo-comprobante",
                    name: "tipcomprobante",
                    component: () =>
                        import(
                            "../views/Administrar/SRI/Tipo_comprobante/TipocomprobanteLista.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Impuestos retenciones",
        value: 7,
        ver: {
            up: [
                {
                    path: "/administrar/sri/impuestos-rentenciones",
                    name: "imprentenciones",
                    component: () =>
                        import(
                            "../views/Administrar/SRI/Impuestos_retenciones/ImpuestosRetenciones.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Tipo sustento",
        value: 8,
        ver: {
            up: [
                {
                    path: "/administrar/sri/tipo-sustento",
                    name: "tiposustento",
                    component: () =>
                        import(
                            "../views/Administrar/SRI/Tipo_sustento/TiposustentoLista.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Plan cuentas",
        value: 9,
        ver: {
            up: [
                {
                    path: "/contabilidad/plan-cuentas",
                    name: "cuentas",
                    component: () =>
                        import(
                            "../views/Contabilidad/Plan_de_cuentas/PlanDeCuentaLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Contabilidad"
                            },
                            {
                                title: "Plan de cuentas",
                                active: true
                            }
                        ],
                        pageTitle: "Plan de cuentas",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Asientos contables",
        value: 10,
        ver: {
            up: [
                {
                    path: "/contabilidad/asientos-contables",
                    name: "asientos",
                    component: () =>
                        import(
                            "../views/Contabilidad/Asientos_contables/AsientosContablesLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Contabilidad"
                            },
                            {
                                title: "Asientos Contables",
                                active: true
                            }
                        ],
                        pageTitle: "Asientos Contables",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/plan/asientos",
                    name: "agregarasiento",
                    component: () =>
                        import(
                            "../views/Contabilidad/Asientos_contables/AsientosContablesLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Contabilidad"
                            },
                            {
                                title: "Asientos Contables",
                                active: true
                            }
                        ],
                        pageTitle: "Asientos Contables",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/plan/asientos/:id",
                    name: "asientosid",
                    component: () =>
                        import(
                            "../views/Contabilidad/Asientos_contables/AsientosContablesLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Contabilidad"
                            },
                            {
                                title: "Asientos Contables",
                                active: true
                            }
                        ],
                        pageTitle: "Asientos Contables",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/plan/asientos",
                    name: "agregarasiento",
                    component: () => import("../views/asientos/Asientos.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Contabilidad"
                            },
                            {
                                title: "Asientos Contables",
                                active: true
                            }
                        ],
                        pageTitle: "Asientos Contables",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/plan/asientos/:id",
                    name: "asientosid",
                    component: () => import("../views/asientos/Asientos.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Contabilidad"
                            },
                            {
                                title: "Asientos Contables",
                                active: true
                            }
                        ],
                        pageTitle: "Asientos Contables",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Nota de credito",
        value: 11,
        ver: {
            up: [
                {
                    path: "/facturacion/nota-credito",
                    name: "notacred",
                    component: () =>
                        import(
                            "../views/Facturacion/Nota_credito/NotaCreditoLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Nota Credito",
                                active: true
                            }
                        ],
                        pageTitle: "Nota Credito",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/facturacion/nota-credito/agregar",
                    name: "notacredito",
                    component: () =>
                        import(
                            "../views/Facturacion/Nota_credito/NotaCreditoAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Nota Credito",
                                active: true
                            }
                        ],
                        pageTitle: "Nuevo Nota Credito",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Facturas",
        value: 12,
        ver: {
            up: [
                {
                    path: "/facturacion/factura-venta",
                    name: "facturasventa",
                    component: () =>
                        import(
                            "../views/Facturacion/Factura_venta/FacturasLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Facturas",
                                active: true
                            }
                        ],
                        pageTitle: "Facturas",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/facturacion/factura-venta/:id/ver",
                    name: "editarfacturaver",
                    component: () =>
                        import(
                            "../views/Facturacion/Factura_venta/FacturasAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/facturacion/factura-venta/:id/editar",
                    name: "editarfactura",
                    component: () =>
                        import(
                            "../views/Facturacion/Factura_venta/FacturasAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/facturacion/factura-venta/agregar",
                    name: "factura",
                    component: () =>
                        import(
                            "../views/Facturacion/Factura_venta/FacturasAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Cliente",
                                active: true
                            }
                        ],
                        pageTitle: "Crear factura",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Proforma",
        value: 13,
        ver: {
            up: [
                {
                    path: "/facturacion/proforma",
                    name: "proforma",
                    component: () =>
                        import(
                            "../views/Facturacion/Proforma/ProformaLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            { title: "Facturacion" },
                            { title: "Proforma", active: true }
                        ],
                        pageTitle: "Proforma",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/facturacion/proforma/agregar",
                    name: "agregarproforma",
                    component: () =>
                        import(
                            "../views/Facturacion/Proforma/ProformaAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Proforma",
                                active: true
                            }
                        ],
                        pageTitle: "Proforma",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/facturacion/proforma/:id/editar",
                    name: "Editar Proforma",
                    component: () =>
                        import(
                            "../views/Facturacion/Proforma/ProformaAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Editar Proforma",
                                active: true
                            }
                        ],
                        pageTitle: "Editar Proforma",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Pago cliente",
        value: 14,
        ver: {
            up: [
                {
                    path: "/facturacion/pago-cliente",
                    name: "pago",
                    component: () =>
                        import(
                            "../views/Facturacion/Pago_cliente/PagoCliente.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Pago Clientes",
                                active: true
                            }
                        ],
                        pageTitle: "Pago Cliente",
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Cliente",
        value: 15,
        ver: {
            up: [
                {
                    path: "/facturacion/clientes",
                    name: "contacto",
                    component: () =>
                        import(
                            "../views/Facturacion/Clientes/ClientesLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Cliente",
                                active: true
                            }
                        ],
                        pageTitle: "Cliente",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/facturacion/cliente/agregar",
                    name: "cliente",
                    component: () =>
                        import(
                            "../views/Facturacion/Clientes/ClientesAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Cliente",
                                active: true
                            }
                        ],
                        pageTitle: "Nuevo Cliente",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/facturacion/cliente/:id/editar",
                    name: "clienteid",
                    component: () =>
                        import(
                            "../views/Facturacion/Clientes/ClientesAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Cliente",
                                active: true
                            }
                        ],
                        pageTitle: "Nuevo Cliente",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Vendedor",
        value: 16,
        ver: {
            up: [
                {
                    path: "/facturacion/vendedor",
                    name: "vendedor",
                    component: () =>
                        import(
                            "../views/Facturacion/Vendedor/VendedorLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Vendedor",
                                active: true
                            }
                        ],
                        pageTitle: "Vendedor",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/facturacion/vendedor/agregar",
                    name: "agregarvendedor",
                    component: () => import("../views/Facturacion/Vendedor/VendedorAgregar.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Vendedor",
                                active: true
                            }
                        ],
                        pageTitle: "Nuevo Vendedor",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/facturacion/vendedor/:id/editar",
                    name: "vendedorid",
                    component: () =>
                        import("../views/Facturacion/Vendedor/VendedorAgregar.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Vendedor",
                                active: true
                            }
                        ],
                        pageTitle: "Nuevo Vendedor",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Bodega",
        value: 17,
        ver: {
            up: [
                {
                    path: "/inventario/bodega",
                    name: "bodega",
                    component: () =>
                        import("../views/Inventario/BodegaLista.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Inventario"
                            },
                            {
                                title: "Bodega",
                                active: true
                            }
                        ],
                        pageTitle: "Bodega",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/inventario/bodega/:id/gestionar",
                    name: "gestionbodega",
                    component: () =>
                        import("../views/Inventario/BodegaGestionar.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Inventario"
                            },
                            {
                                title: "Bodega"
                            },
                            {
                                title: "Gestion Bodega",
                                active: true
                            }
                        ],
                        pageTitle: "Gestion Bodega",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Catalogo",
        value: 18,
        ver: {
            up: [
                {
                    path: "/inventario/catalogo",
                    name: "catalogo",
                    component: () =>
                        import("../views/Catalogo/CatalogoLista.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Inventario"
                            },
                            {
                                title: "Catalogo",
                                active: true
                            }
                        ],
                        pageTitle: "Catalogo",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/inventario/catalogo/agregar",
                    name: "agregarCatalogo",
                    component: () =>
                        import("../views/Catalogo/CatalogoAgregar.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Inventario"
                            },
                            {
                                title: "Catalogo",
                                active: true
                            }
                        ],
                        pageTitle: "Agregar Catalogo",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/inventario/catalogo/:id/editar",
                    name: "editarCatalogo",
                    component: () =>
                        import("../views/Catalogo/CatalogoAgregar.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Inventario"
                            },
                            {
                                title: "Catalogo",
                                active: true
                            }
                        ],
                        pageTitle: "Editar Catalogo",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Nomina",
        value: 19,
        ver: {
            up: [
                {
                    path: "/nomina/empleados",
                    name: "listar",
                    component: () => import("../views/Nomina/NominaLista.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Nomina"
                            },
                            {
                                title: "Listar Empleados",
                                active: true
                            }
                        ],
                        pageTitle: "Empleados",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/nomina/empleados/agregar",
                    name: "empleado",
                    component: () =>
                        import("../views/Nomina/NominaAgregar.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Nomina"
                            },
                            {
                                title: "Empleados",
                                active: true
                            }
                        ],
                        pageTitle: "Empleado",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/nomina/empleados/:id/editar",
                    name: "empleadoid",
                    component: () =>
                        import("../views/Nomina/NominaAgregar.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Nomina"
                            },
                            {
                                title: "Empleados",
                                active: true
                            }
                        ],
                        pageTitle: "Empleado",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Ingresos-Egresos",
        value: 29,
        ver: {
            up: [
                {
                    path: "/nomina/ingreso-egreso",
                    name: "listaringreso-egreso",
                    component: () =>
                        import(
                            "../views/Nomina/Ingresos-Egresos/Ingresos-egresos.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Ingresos"
                            },
                            {
                                title: "Egresos",  
                                active: true
                            },
                            {
                                rule: "editor"
                            }
                        ],
                        pageTitle: "Ingresos-Egresos",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/ingreso/agregar",
                    name: "ingresos-agregar",
                    component: () =>
                        import("../views/Nomina/Ingresos-Egresos/IngresosAgregar.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Ingresos"
                            },
                            {
                                title: "Egresos",
                                active: true
                            }
                        ],
                        pageTitle: "Ingresos",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/compras/orden-compra/:id/editar",
                    name: "editOrdencompra",
                    component: () =>
                        import(
                            "../views/Compras/Orden_compras/OrdenAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {        
    nombre: "Rol Pagos",
        value: 30,
        ver: {
            up: [
                {
                    path: "/nomina/rol-pagos",
                    name: "listarrol-pagos",
                    component: () =>
                        import(
                            "../views/Nomina/Rol-Pagos/RolPagoLista.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/" 
                            },
                            {
                                title: "Rol"
                            },
                            {
                                title: "Pagos",  
                                active: true
                            }
                        ],
                        pageTitle: "Ingresos-Egresos",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/rolpagos/agregar",
                    name: "Rolpagos-agregar",
                    component: () =>
                        import("../views/Nomina/Rol-Pagos/Rol-pagos-agregar.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Rol"
                            },
                            {
                                title: "Pagos",
                                active: true
                            }
                        ],
                        pageTitle: "Ingresos",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/compras/orden-compra/:id/editar",
                    name: "editOrdencompra",
                    component: () =>
                        import(
                            "../views/Compras/Orden_compras/OrdenAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Proveedor",
        value: 20,
        ver: {
            up: [
                {
                    path: "/compras/proveedor",
                    name: "listaProveedor",
                    component: () =>
                        import("../views/Compras/Proveedor/ProveedorLista.vue"),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/compras/proveedor/agregar",
                    name: "agregarProveedor",
                    component: () =>
                        import(
                            "../views/Compras/Proveedor/ProveedorAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/compras/proveedor/:id/editar",
                    name: "agregarProveedorid",
                    component: () =>
                        import(
                            "../views/Compras/Proveedor/ProveedorAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Facturas compra",
        value: 21,
        ver: {
            up: [
                {
                    path: "/compras/factura-compra",
                    name: "facturascompra",
                    component: () =>
                        import(
                            "../views/Compras/Facturas-compra/FacturaCompra.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Facturas",
                                active: true
                            }
                        ],
                        pageTitle: "Facturas",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/compras/factura-compra/agregar",
                    name: "Agregarcompra",
                    component: () =>
                        import(
                            "../views/Compras/Facturas-compra/FacturaCompraAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/compras/factura-compra/:id/editar",
                    name: "editcompra",
                    component: () =>
                        import(
                            "../views/Compras/Facturas-compra/FacturaCompraAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Orden compra",
        value: 28,
        ver: {
            up: [
                {
                    path: "/compras/orden-compra",
                    name: "compra",
                    component: () =>
                        import(
                            "../views/Compras/Orden_compras/OrdenCompra.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Orden"
                            },
                            {
                                title: "Compra",
                                active: true
                            }
                        ],
                        pageTitle: "Orden compra",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/compras/orden-compra/agregar",
                    name: "Ordencompra",
                    component: () =>
                        import(
                            "../views/Compras/Orden_compras/OrdenAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/compras/orden-compra/:id/editar",
                    name: "editOrdencompra",
                    component: () =>
                        import(
                            "../views/Compras/Orden_compras/OrdenAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Importacion",
        value: 22,
        ver: {
            up: [
                {
                    path: "/compras/importacion",
                    name: "importacion",
                    component: () =>
                        import(
                            "../views/Compras/Importacion/ImportacionLista.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/compras/liquidacion",
                    name: "liquidacion",
                    component: () =>
                        import(
                            "../views/Compras/Liquidacion/LiquidacionLista.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/compras/importacion/agregar",
                    name: "AgregarImportacion",
                    component: () =>
                        import(
                            "../views/Compras/Importacion/ImportacionAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/compras/importacion/:id/editar",
                    name: "editImportacion",
                    component: () =>
                        import(
                            "../views/Compras/Importacion/ImportacionAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/compras/liquidacion/:id",
                    name: "editliquidacion",
                    component: () =>
                        import(
                            "../views/Compras/Liquidacion/LiquidacionVer.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Cuentas por pagar",
        value: 23,
        ver: {
            up: [
                {
                    path: "/compras/cuentas-por-pagar",
                    name: "CtaxPagar",
                    component: () =>
                        import(
                            "../views/Compras/Cuentas_por_pagar/CuentasPorPagar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/compras/cuentas-por-pagar/agregar",
                    name: "AgregarCtaxPagar",
                    component: () =>
                        import(
                            "../views/Compras/Cuentas_por_pagar/CuentasPorPagarAgregar.vue"
                        ),
                    meta: {
                        rule: "editor",
                       // secure: true
                    }
                }
            ],
            down: []
        },
    },
    {
        nombre: "Formula",
        value: 24,
        ver: {
            up: [
                {
                    path: "/produccion/formula",
                    name: "Formula",
                    component: () =>
                        import("../views/Produccion/Formula/Formula.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Fórmula"
                            }
                        ],
                        pageTitle: "Fórmula",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [
                {
                    path: "/produccion/formula/agregar",
                    name: "Agregar Formula",
                    component: () =>
                        import(
                            "../views/Produccion/Formula/FormulaAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Pruducción"
                            },
                            {
                                title: " Fórmula"
                            },
                            {
                                title: "Agregar Fórmula"
                            }
                        ],
                        pageTitle: "Aregar Fórmula",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/produccion/formula/:id/editar",
                    name: "editarFormula",
                    component: () =>
                        import(
                            "../views/Produccion/Formula/FormulaAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Pruducción"
                            },
                            {
                                title: " Fórmula"
                            },
                            {
                                title: "Editar Fórmula"
                            }
                        ],
                        pageTitle: "Editar Fórmula",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Proceso produccion",
        value: 25,
        ver: {
            up: [
                {
                    path: "/produccion/proceso-produccion",
                    name: "Proceso-produccion",
                    component: () =>
                        import(
                            "../views/Produccion/Proceso_produccion/ProcesoProduccion.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Proceso de Produccion"
                            }
                        ],
                        pageTitle: "Proceso",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        editar: {
            up: [
                {
                    path: "/produccion/proceso-produccion/:id/gestion",
                    name: "Gestión-Proceso-Produccion",
                    component: () =>
                        import(
                            "../views/Produccion/Proceso_produccion/ProcesoProduccionGestion.vue"
                        ),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Proceso de Produccion"
                            },
                            {
                                title: "Gestión Proceso de Produccion"
                            }
                        ],
                        pageTitle: "Gestión Proceso de Producción",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Activos fijos",
        value: 26,
        ver: {
            up: [
                {
                    path: "/activos-fijos",
                    name: "fijos",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Activos"
                            },
                            {
                                title: "Activo Fijo",
                                active: true
                            }
                        ],
                        pageTitle: "Activos Fijos",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Calendario",
        value: 27,
        ver: {
            up: [
                {
                    path: "/calendario",
                    name: "calendario",
                    component: () =>
                        import("../views/Calendario/Calendario.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    }
];

module.exports = {
    routesAndValue
};
