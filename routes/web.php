<?php

use Illuminate\Support\Facades\Route;
//Route::group(['middleware'=>['auth']],function(){//cambio
//productos
Route::get('/api/productos/{id}', 'ProductoController@index');
Route::post('/api/guardarproductos', 'ProductoController@guardar');
Route::put('/api/abrirproductos', 'ProductoController@abrir');
Route::put('/api/actualizarproductos', 'ProductoController@actualizar');
Route::delete('/api/eliminarproductos/{id}', 'ProductoController@eliminar');
Route::get('/api/camposadicionales', 'ProductoController@camposadicionales');
Route::post('/api/guardarimgproducto', 'ProductoController@guardarimagen');

//productos-lineaproducto
Route::get('/api/lineaproductos/{id}', 'LineaproductoController@index');
Route::post('/api/guardarlinea', 'LineaproductoController@store');
Route::post('/api/editarlinea', 'LineaproductoController@editar');
Route::delete('/api/eliminarlinea/{id}', 'LineaproductoController@eliminar');
Route::get('/api/lineaproductosall/{id}', 'LineaproductoController@todo');

//productos-tipoaproducto
Route::get('/api/tipoproductos/{id}', 'TipoproductoController@index');
Route::post('/api/guardartipo', 'TipoproductoController@store');
Route::post('/api/editartipo', 'TipoproductoController@editar');
Route::delete('/api/eliminartipo/{id}', 'TipoproductoController@eliminar');
Route::get('/api/tipoproductosall', 'TipoproductoController@todo');
Route::get('/api/tipoproductosallr/{id}', 'TipoproductoController@todor');

//marcas
Route::get('/api/marca/{id}', 'MarcaController@index');
Route::post('/api/guardarmarca', 'MarcaController@store');
Route::post('/api/editarmarca', 'MarcaController@editar');
Route::delete('/api/eliminarmarca/{id}', 'MarcaController@eliminar');
Route::get('/api/marcaall/{id}', 'MarcaController@todo');

//modelos
Route::get('/api/modelo/{id}', 'ModeloController@index');
Route::post('/api/guardarmodelo', 'ModeloController@store');
Route::post('/api/editarmodelo', 'ModeloController@editar');
Route::delete('/api/eliminarmodelo/{id}', 'ModeloController@eliminar');
Route::get('/api/modeloall/{id}', 'ModeloController@todo');

//bodegas
Route::get('/api/bodega/{id}/{ide}', 'BodegaController@index');
Route::post('/api/guardarbodega', 'BodegaController@store');
Route::post('/api/editarbodega', 'BodegaController@editar');
Route::put('/api/abrirbodega', 'ProductoController@abrir');
Route::delete('/api/eliminarbodega/{id}', 'BodegaController@eliminar');
Route::get('/api/bodegaall/{id}', 'BodegaController@todo');
//Producto_Bodega
Route::get('/api/abrirproductosbodega/{id}', 'ProductoBodegaController@productos');
//Bodega_Ingreso
Route::post('/api/guardarbodegaingreso', 'BodegaIngresoController@store');
//Bodega_Egreso
Route::post('/api/guardarbodegaegreso', 'BodegaEgresoController@store');
//Bodega_Transferencia
Route::post('/api/guardarbodegatranse', 'BodegaTransferenciaController@store');
Route::post('/api/guardarbodegatransr', 'BodegaTransferenciaController@recibida');
Route::get('/api/abrirbodegatransr/{id}', 'BodegaTransferenciaController@gettransr');
Route::get('/api/abrirbodegatransrp/{id}', 'BodegaTransferenciaController@gettransrp');
Route::get('/api/recuperarproductostr/{id}', 'BodegaTransferenciaController@gettransrpproductos');
//ubicacion fisica
Route::get('/api/ubicacion_fisica', 'Ubicacion_fisicaController@index');
Route::post('/api/guardarubicacion_fisica', 'Ubicacion_fisicaController@store');
Route::post('/api/editarubicacion_fisica', 'Ubicacion_fisicaController@editar');
Route::delete('/api/eliminarubicacion_fisica/{id}', 'Ubicacion_fisicaController@eliminar');
Route::get('/api/ubicacion_fisicaall', 'Ubicacion_fisicaController@todo');
Route::get('/api/ubicacion_fisicaallr', 'Ubicacion_fisicaController@todor');

//presentacion
Route::get('/api/presentacion/{id}', 'PresentacionController@index');
Route::post('/api/guardarpresentacion', 'PresentacionController@store');
Route::post('/api/editarpresentacion', 'PresentacionController@editar');
Route::delete('/api/eliminarpresentacion/{id}', 'PresentacionController@eliminar');
Route::get('/api/presentacionall/{id}', 'PresentacionController@todo');

//tipo_medida
Route::get('/api/tipomedida', 'Tipo_medidaController@todo');

//unidad_medida
Route::get('/api/unidadmedida', 'Unidad_medidaController@todo');
Route::get('/api/unidadmedidar', 'Unidad_medidaController@todor');

//iva
Route::get('/api/iva', 'IvaController@todo');

//ice
Route::get('/api/ice', 'IceController@todo');

//cliente
Route::get('/api/clientes/{id}', 'ClienteController@index');
Route::post('/api/cliente/guardar', 'ClienteController@store');
Route::put('/api/cliente/vercliente', 'ClienteController@vercliente');
Route::put('/api/cliente/editar', 'ClienteController@update');
Route::get('/api/ciudad', 'ClienteController@getCiudad');
Route::get('api/provincia', 'ClienteController@getProvincia');
Route::get('/api/parroquia', 'ClienteController@getParroquia');
Route::delete('/api/eliminarCliente/{id}', 'ClienteController@destroy');
Route::get('/api/verificarcliente/{id}', 'ClienteController@verificarcliente');

Route::get('/api/grupo_cliente', 'ClienteController@getGrupoClientes');
//grupo cliente
Route::get('/api/grupocliente/{id}', 'GrupoclienteController@index');
Route::post('/api/guardargrupo', 'GrupoclienteController@store');
Route::post('/api/editargrupo', 'GrupoclienteController@editar');
Route::delete('/api/eliminargrupo/{id}', 'GrupoclienteController@eliminar');
Route::get('/api/grupoclienteall/{id}', 'GrupoclienteController@todo');
Route::get('/api/grupo_cliente/{id}', 'ClienteController@getGrupoClientes');

///*******tipo cliente
Route::delete('/api/eliminartipocliente/{id}', 'TipoclienteController@eliminar');
Route::post('/api/guardartipocliente', 'TipoclienteController@store');
Route::post('/api/editartipocliente', 'TipoclienteController@editar');
Route::get('/api/listartipocliente/{id}', 'TipoclienteController@index');
Route::get('/api/grupotipocliente', 'ClienteController@getTipoCliente');

///*******vendedor en cliente


Route::delete('/api/eliminarvendedorcliente/{id}', 'VendedorclienteController@eliminar');
Route::post('/api/guardarvendedorcliente', 'VendedorclienteController@store');
Route::put('/api/editarvendedorcliente', 'VendedorclienteController@editar');
Route::get('/api/listarvendedorcliente/{id}', 'VendedorclienteController@index');

Route::get('/api/grupo_vendedor/{id}', 'ClienteController@getGrupoVendedor');
Route::put('/api/vendedor/vercliente', 'VendedorclienteController@vervendedor');


//***asientos contables */
Route::get('/api/asientoscontables', 'AsientosController@index');
Route::delete('/api/eliminarasientos/{id}', 'AsientosController@eliminar');
Route::post('/api/guardarasiento', 'AsientosController@store');
Route::put('/api/editarasiento', 'AsientosController@editar');
Route::put('/api/asiento/verasiento', 'AsientosController@verasiento');

//asientos tabla proyecto

Route::get('/api/listarproyecto/{id}', 'ProyectoController@index');
Route::delete('/api/eliminarproyecto/{id}', 'ProyectoController@eliminar');
Route::post('/api/guardarproyecto', 'ProyectoController@store');
Route::post('/api/editarproyecto', 'ProyectoController@editar');

//asientos detalle tabla proyecto

Route::get('/api/listarasientodetalle/{id}', 'AsientoDetalleController@index');








//import clientes
Route::post('/api/importarexcel', 'ImportController@import');
//importar plan cuentas
Route::post('/api/importarplancuentaexcel', 'ImportController@importPlanCuentas');

//importar proveedor
Route::post('/api/importarproveedorexcel', 'ImportproveedorController@import');
//impor producto
Route::post('/api/importarproductosexcel', 'ImportProductosController@import');
//impor lineas de productos
Route::post('/api/importarlineaproductosexcel', 'ImportProductosController@importLineasProducto');

//impor tipos de productos
Route::post('/api/importartipoproductosexcel', 'ImportProductosController@importTipoProducto');

//impor marca de productos
Route::post('/api/importarmarcaproductosexcel', 'ImportProductosController@imporMarcoProducto');
//impor Modelos de productos
Route::post('/api/importarmodelosproductosexcel', 'ImportProductosController@imporModelosProducto');

//impor Presentacion de productos
Route::post('/api/importarpresentacionproductosexcel', 'ImportProductosController@imporPresentacionProducto');


//punto de emision
Route::get('/api/ptoemision/{id}', 'PtoemisionController@index');
Route::post('/api/ptoemisiong', 'PtoemisionController@store');
Route::delete('/api/eliminarpt/{id}', 'PtoemisionController@eliminar');
Route::put('/api/abrirpt', 'PtoemisionController@abrir');
Route::put('/api/actualizarpt', 'PtoemisionController@actualizar');
Route::get('/api/abrirestablecimiento/{id}', 'PtoemisionController@abrire');
Route::get('/api/pttodo/{id}', 'PtoemisionController@todo');

//Establecimiento
Route::get('/api/establecimiento/{id}', 'EstableciemtoController@index');
Route::post('/api/establecimientog', 'EstableciemtoController@store');
Route::delete('/api/establecimientoeliminar/{id}', 'EstableciemtoController@destroy');
Route::get('/api/listaremisor', 'EstableciemtoController@abriremisor');
Route::put('/api/establecimientoact', 'EstableciemtoController@update');
Route::get('/api/esttodo/{id}', 'EstableciemtoController@todo');
//listas
Route::get('/api/listarcuentas/{id}', 'CuentacontableController@cuentas');

//*****fin cliente
//****FACTURA
//agregar empresa a factura
Route::get('/api/traerEmpresa/{id}', 'FacturaController@indexEmpresa');
Route::post('/api/crearfactura', 'FacturaController@store');
Route::post('/api/crearfacturap', 'FacturaController@storeprof');

//envio al sri las facturas
Route::post('/api/leerFacturaphp', 'FacturacionController@leerFactura');
Route::post('/api/firmaphp', 'FacturacionController@firmaphp');
Route::post('/api/validarComprobantephp', 'FacturacionController@validarComprobantephp');
Route::post('/api/autorizacionComprobantephp', 'FacturacionController@autorizacionComprobantephp');
Route::post('/api/validarFechaCertificadophp', 'FacturacionController@validarFechaCertificadophp');
Route::post('/api/respfactura', 'FacturacionController@respfactura');

//agregar nota de credito
Route::get('/api/notacredito', 'NotacreditoController@index');

//punto de emision
Route::get('/api/ptoemision', 'PtoemisionController@index');
Route::post('/api/ptoemisiong', 'PtoemisionController@store');
Route::delete('/api/eliminarpt/{id}', 'PtoemisionController@eliminar');
Route::put('/api/abrirpt', 'PtoemisionController@abrir');
Route::put('/api/actualizarpt', 'PtoemisionController@actualizar');

//listas codigo pais
Route::get('/api/listarcodigopais', 'CodigopaisController@Codigop');

//Establecimiento
Route::get('/api/establecimiento', 'EstableciemtoController@index');
Route::post('/api/establecimientog', 'EstableciemtoController@store');
Route::delete('/api/establecimientoeliminar/{id}', 'EstableciemtoController@destroy');
Route::put('/api/establecimientoabrir', 'EstableciemtoController@abrir');
Route::put('/api/listaremisor', 'EstableciemtoController@abriremisor');
Route::put('/api/establecimientoact', 'EstableciemtoController@update');

//empresa
Route::get('/api/empresa', 'EmpresaController@index');
Route::get('/api/abrirempresa/{id}', 'EmpresaController@abrir');
Route::put('/api/actualizarempresa', 'EmpresaController@update');
Route::delete('/api/eliminarempresa/{id}', 'EmpresaController@eliminar');
Route::post('/api/agregarempresa', 'EmpresaController@store');
Route::get('/api/moneda', 'EmpresaController@getMoneda');
Route::get('/api/provincia', 'EmpresaController@getProvincia');
Route::get('/api/ciudad', 'EmpresaController@getCiudad');
Route::get('/api/empresausu', 'EmpresaController@indexUsuario');
Route::get('/api/empresasasoc', 'EmpresaController@getEmpresas');
Route::post('/api/guardarimagen', 'EmpresaController@guardarimagen');
Route::post('/api/guardarimgempresa', 'EmpresaController@guardarimagen');
Route::post('/api/guardarfirmaempresa', 'EmpresaController@guardarfirma');
Route::post('/api/guardarimgempresa1', 'EmpresaController@guardarimagen1');
Route::post('/api/guardarfirmaempresa1', 'EmpresaController@guardarfirma1');
Route::delete('/api/eliminarfirma/{id}', 'EmpresaController@eliminarfirma');

//cuentas por cobrar
Route::get('/api/pago', 'CuentaporcobrarController@index');
Route::post('/api/agregarpago', 'CuentaporcobrarController@store');
Route::put('/api/abrirpago/{id}', 'CuentaporcobrarController@abrir');
Route::put('/api/actualizarpago', 'CuentaporcobrarController@update');
Route::delete('/api/eliminarpago/{id}', 'CuentaporcobrarController@eliminar');
Route::get('/api/traercliente', 'CuentaporcobrarController@getCliente');

//plan cuentas
Route::get('/api/cuentas/{id}', 'PlancuentasController@index');
Route::get('/api/traerempresa', 'PlancuentasController@getEmpresa');
Route::get('/api/traermoneda', 'PlancuentasController@getMoneda');
Route::post('/api/agregarcuentas', 'PlancuentasController@store');
Route::put('/api/abrircta/{id}', 'PlancuentasController@abrir');
Route::put('/api/actualizarcta', 'PlancuentasController@update');
Route::delete('/api/eliminarcta/{id}', 'PlancuentasController@eliminar');
Route::get('/api/traergrupos', 'GrupoController@getgrupos');

//caja chica
Route::get('/api/caja/{id}', 'CajaController@index');
Route::post('/api/agregarcaja', 'CajaController@store');
Route::put('/api/abrircaja/{id}', 'CajaController@abrir');
Route::put('/api/actualizarcaja', 'CajaController@update');
Route::delete('/api/eliminarcaja/{id}', 'CajaController@eliminar');


//proveedor
Route::get('/api/proveedor/{id}', 'ProveedorController@index');
Route::put('/api/abrirproveedor', 'ProveedorController@abrir');
Route::put('/api/actualizarproveedor', 'ProveedorController@update');
Route::post('/api/agregarproveedor', 'ProveedorController@store');
Route::delete('/api/eliminarproveedor/{id}', 'ProveedorController@eliminar');
Route::get('/api/traerprovinciaprov', 'ProveedorController@getProvincia');
Route::get('/api/traerciudadprov', 'ProveedorController@getCiudad');
Route::get('/api/traerbancoprov', 'ProveedorController@getBanco');
//Route::get('/api/traerctaprov', 'ProveedorController@busqueda');
Route::get('/api/traergruprov', 'ProveedorController@getGrupo');
Route::get('/api/traerimpfuente', 'ProveedorController@getImpFuente');
Route::get('/api/traerimpiva', 'ProveedorController@getImpIva');
Route::get('/api/traertipcomprob', 'ProveedorController@getTipComprob');
Route::get('/api/traerretfuente', 'ProveedorController@getRetencionFuente');
Route::get('/api/traerretiva', 'ProveedorController@getRetencionIva');
Route::get('/api/codigo', 'ProveedorController@codigo');






//grupo-proveedor
Route::get('/api/grupoprov/{id}', 'GrupoProveedorController@index');
Route::post('/api/agregargrupoprov', 'GrupoProveedorController@store');
Route::put('/api/abrirgrupoprov/{id}', 'GrupoProveedorController@abrir');
Route::put('/api/actualizargrupoprov', 'GrupoProveedorController@update');
Route::delete('/api/eliminargrupoproveedor/{id}', 'GrupoProveedorController@eliminar');

//ctaxpagar
Route::get('/api/ctaxpagar', 'CuentaporpagarController@index');
Route::post('/api/agregargrupoprov', 'CuentaporpagarController@store');
Route::put('/api/abrirgrupoprov/{id}', 'CuentaporpagarController@abrir');
Route::put('/api/actualizargrupoprov', 'CuentaporpagarController@update');
Route::delete('/api/eliminargrupoproveedor/{id}', 'CuentaporpagarController@eliminar');
Route::get('/api/traerprovctaxpagar', 'CuentaporpagarController@getProveedor');

//tipo-comprobante
Route::get('/api/tipcomprob/{id}', 'TipocomprobanteController@index');
Route::post('/api/agregartipcomprob', 'TipocomprobanteController@store');
Route::put('/api/abrirtipcomprob/{id}', 'TipocomprobanteController@abrir');
Route::put('/api/actualizartipcomprob', 'TipocomprobanteController@update');
Route::delete('/api/eliminartipcomprob/{id}', 'TipocomprobanteController@eliminar');

//impuestos
Route::get('/api/impuesto/{id}', 'ImpuestoController@index');
Route::post('/api/agregarimpuesto', 'ImpuestoController@store');
Route::put('/api/abririmpuesto/{id}', 'ImpuestoController@abrir');
Route::put('/api/actualizarimpuesto', 'ImpuestoController@update');
Route::delete('/api/eliminarimpuesto/{id}', 'ImpuestoController@eliminar');
//Route::get('/api/impuestoexport','ImpuestoController@exportExcel');

//retenciones
Route::get('/api/retencion/{id}', 'RetencionController@index');
Route::post('/api/agregarretencion', 'RetencionController@store');
Route::put('/api/abrirretencion/{id}', 'RetencionController@abrir');
Route::put('/api/actualizarretencion', 'RetencionController@update');
Route::delete('/api/eliminarretencion/{id}', 'RetencionController@eliminar');
Route::get('/api/traermonedaret', 'RetencionController@getMoneda');
Route::get('/api/traerimpret', 'RetencionController@getImpuesto');

//tipo-sustento
Route::get('/api/tiposustento/{id}', 'TiposustentoController@index');
Route::post('/api/agregartiposustento', 'TiposustentoController@store');
Route::put('/api/abrirtiposustento/{id}', 'TiposustentoController@abrir');
Route::put('/api/actualizartiposustento', 'TiposustentoController@update');
Route::delete('/api/eliminartiposustento/{id}', 'TiposustentoController@eliminar');

//facturas varias
Route::post('/api/facturas', 'FacturaController@index');
Route::get('/api/establecimientos', 'EstablecimientoController@index');
Route::get('/api/ptoemision', 'PtoemisionController@index');
Route::get('/api/impuestos', 'ImpuestoController@index');
Route::post('/empresa/agregar', 'EmpresaController@store');
Route::get('/api/listarclave/{id}', 'FacturaController@clave');

//generar XML de facturas emitidas
//emision-venta
Route::post('/api/factura/xml_factura', 'XMLControler@efactura');
Route::post('/api/factura/xml_guia', 'XMLControler@e_guia');

Route::post('/api/factura/xml_nota_credito', 'XMLControler@enotacredito');
Route::post('/api/factura/xml_nota_debito', 'XMLControler@enotadebito');
Route::post('/api/factura/xml_compro_retenc', 'XMLControler@e_comproretenc');
//remision-compra
Route::post('/api/factura/xml_r_factura', 'XMLControler@r_factura');


//usuarios
Route::get('/api/usuarios/{id}', 'UserController@index');

//facturas
Route::get('/api/factproductos', 'FacturaController@verproductos');
Route::get('/api/listarretenciones', 'FacturaController@listarretenciones');
Route::get('/api/abrirretencionp/{id}', 'FacturaController@listarpretenciones');
Route::get('/api/abrircreditosp/{id}', 'FacturaController@abrircreditosp');
Route::get('/api/abrirpagosp/{id}', 'FacturaController@abrirpagosp');

Route::get('/api/traerclientefactura/{id}', 'FacturaController@traercliente');

//Proforma
Route::get('/api/proforma/{id}', 'FacturaController@indexp');
Route::put('/api/abrirproforma', 'FacturaController@abrirprof');
Route::put('/api/editarproforma', 'FacturaController@editarprof');
Route::delete('/api/eliminarproforma/{id}', 'FacturaController@eliminarprof');
Route::get('/api/abrirproformac/{id}', 'FacturaController@abrirprofc');
Route::get('/api/abrirproformap/{id}', 'FacturaController@abrirprofp');
Route::post('/api/crearproforma', 'FacturaController@storep');


//facturas-compra
Route::get('/api/factcompra/{id}', 'FacturacompraController@index');
Route::put('/api/abrirfactcompra', 'FacturacompraController@abrir');
Route::put('/api/abrircredfactcompra/{id}', 'FacturacompraController@abrirCredito');
Route::put('/api/actualfactcompra', 'FacturacompraController@update');
Route::put('/api/actprodfactcompra', 'FacturacompraController@actProducto');
Route::get('/api/abrirprodfactcompra/{id}', 'FacturacompraController@listarProduct');
Route::get('/api/abrirpagfactcompra/{id}', 'FacturacompraController@listPagos');
Route::post('/api/guardarfactura', 'FacturacompraController@store');
Route::post('/api/guardarprodfactcom', 'FacturacompraController@guardarProducto');
Route::post('/api/guardarpagfactcom', 'FacturacompraController@guardarPago');
Route::post('/api/guardarretffactcom', 'FacturacompraController@guardarRetencion');
Route::delete('/api/eliminarfactcomp/{id}', 'FacturacompraController@eliminar');
Route::get('/api/traersustento', 'FacturacompraController@getSustento');
Route::get('/api/traermonedafact', 'FacturacompraController@getMoneda');
Route::get('/api/traerimport', 'FacturacompraController@getImportacion');
Route::get('/api/traerprovinciafactcomp', 'FacturacompraController@getProvincia');
Route::get('/api/traerptoemfactcomp', 'FacturacompraController@getPtoemision');
Route::get('/api/traerretffactcomp', 'FacturacompraController@getRetencionFuente');
Route::get('/api/traerretivafactcomp', 'FacturacompraController@getRetencionIva');
Route::get('/api/abrirporcretfactcompra', 'FacturacompraController@getPorcentaje');
Route::get('/api/abrirporcivafactcompra', 'FacturacompraController@getPorcentajeIva');
Route::get('/api/traercajafactcomp', 'FacturacompraController@getCaja');
Route::get('/api/traerbancofactcomp', 'FacturacompraController@getBanco');
Route::get('/api/traerclientfactcomp', 'FacturacompraController@getCliente');

//orden compra
Route::get('/api/ordencompra/{id}', 'OrdencompraController@indexorden');
Route::post('/api/guardarorden', 'OrdencompraController@store');
Route::post('/api/guardarprodord', 'OrdencompraController@guardarProducto');
Route::put('/api/abrirorden/{id}', 'OrdencompraController@abrir');
Route::put('/api/actualizarorden', 'OrdencompraController@update');
Route::put('/api/actualizarprodorden', 'OrdencompraController@actProducto');
Route::delete('/api/eliminarorden/{id}', 'OrdencompraController@eliminar');
Route::get('/api/abrirprovorden/{id}', 'OrdencompraController@traerProveedores');
Route::get('/api/abrirgrupprovorden', 'OrdencompraController@traergrupoProvd');
Route::get('/api/abrirprodorden/{id}', 'OrdencompraController@traerProductos');

//importacion
Route::get('/api/importacion/{id}', 'ImportacionController@index');
Route::post('/api/agregarimportacion', 'ImportacionController@store');
Route::post('/api/agregarprodimportacion', 'ImportacionController@guardarProd');
Route::post('/api/agregarprovimportacion', 'ImportacionController@guardarProv');
Route::put('/api/abririmportacion/{id}', 'ImportacionController@abrir');
Route::put('/api/actualizarimportacion', 'ImportacionController@update');
Route::put('/api/actualizarprodimportacion', 'ImportacionController@actProducto');
Route::get('/api/actualizarprovimportacion/{id}', 'ImportacionController@traerProvedor');
Route::delete('/api/eliminarimportacion/{id}', 'ImportacionController@eliminar');

Route::get('/api/traerproveedorimport/{id}', 'ImportacionController@getProveedor');
Route::get('/api/traerproductoimport/{id}', 'ImportacionController@abrirProducto');
Route::get('/api/abrirproveedorimport/{id}', 'ImportacionController@abrirProvedor');
Route::get('/api/traerorden/{id}', 'ImportacionController@getOrden');

//Route::get('/api/traerproveedor/{id}','ImportacionController@getProveedor');
//Route::get('/api/traerproducto/{id}','ImportacionController@getProducto');

//liquidacion
Route::get('/api/liquid/{id}', 'LiquidacionController@index');
Route::put('/api/verliquid', 'LiquidacionController@abrir');
Route::put('/api/liquidar', 'LiquidacionController@liquidar');
Route::get('/api/traerfactliquid/{id}', 'LiquidacionController@abrirFactura');

//Produccion
//formula de Produccion
Route::get('/api/formula/{id}', 'FormulaController@index');
//codigo produccion
Route::get('/api/codfomr/{id}', 'FormulaController@codform');
Route::post('/api/agregarformula', 'FormulaController@store');
Route::put('/api/editarformula', 'FormulaController@update');
Route::delete('/api/eliminarformula/{id}', 'FormulaController@delete');
Route::get('/api/traerformula/{id}', 'FormulaController@getform');
Route::get('/api/traerformprod/{id}', 'FormulaController@getformprod');
Route::get('/api/traerformingred/{id}', 'FormulaController@getformingred');
//proceso produccion
Route::get('/api/traerprocesprod/{id}/{ide}', 'ProcesoProduccionController@index');
//proceso produccion orden
Route::get('/api/traerprocesingred', 'ProcesoOrdenController@getingred');

///cristian
//Empleados
Route::get('/api/nomina/{id}', 'EmpleadoController@index');
Route::post('/api/empleado/agregar', 'EmpleadoController@store');
Route::delete('/api/empleado/eliminar/{id}', 'EmpleadoController@eliminar');
Route::put('/api/empleado/verempleado', 'EmpleadoController@verEmpleado');
Route::put('/api/empleado/editar', 'EmpleadoController@update');
Route::get('/api/nacionalidad', 'EmpleadoController@getNacionalidad');
Route::get('/api/banco', 'EmpleadoController@getBanco');
Route::get('/api/ciudad', 'EmpleadoController@getCiudad');
Route::get('/api/provincia', 'EmpleadoController@getProvincia');
Route::get('/api/parroquia', 'EmpleadoController@getParroquia');

Route::get('/api/obtenerid', 'EmpleadoController@buscarId');
//Cargo de Empleado
Route::post('/api/cargo/agregar', 'EmpleadoCargoController@store');
Route::delete('/api/cargo/eliminar/{id}', 'EmpleadoCargoController@eliminar');
Route::put('/api/cargo/abrir', 'EmpleadoCargoController@abrir');
Route::put('/api/cargo/editar', 'EmpleadoCargoController@update');
Route::get('/api/grupo_ocupacional/{id}', 'EmpleadoCargoController@getGrupoOcu');
Route::get('/api/area/{id}', 'EmpleadoCargoController@getArea');
Route::get('/api/departamento/{id}', 'EmpleadoCargoController@getDepartamento');
Route::get('/api/cargo', 'EmpleadoCargoController@getCargo');

//Cargas de Empleado
Route::get('/api/cargas/listar', 'EmpleadoCargasController@index');
Route::post('/api/carga/agregar', 'EmpleadoCargasController@store');
Route::delete('/api/carga/eliminar/{id}', 'EmpleadoCargasController@eliminar');
Route::put('/api/carga/abrir', 'EmpleadoCargasController@abrir');
Route::put('/api/carga/editar', 'EmpleadoCargasController@update');
//Documentacion de Empleado
Route::post('/api/documentacion/agregar', 'EmpleadoDocumentoController@store');
Route::delete('/api/documentacion/eliminar/{id}', 'EmpleadoDocumentoController@eliminar');
Route::put('/api/documentacion/abrir', 'EmpleadoDocumentoController@abrir');
Route::put('/api/documentacion/editar', 'EmpleadoDocumentoController@update');
Route::get('/api/documento/{id}', 'EmpleadoDocumentoController@getDocumento');
//Calendario de Empleado
Route::post('/api/calendario/listar/{id}', 'EmpleadoCalendarioController@index');
Route::post('/api/calendario/agregar', 'EmpleadoCalendarioController@store');
Route::delete('/api/calendario/eliminar/{id}', 'EmpleadoCalendarioController@eliminar');
Route::put('/api/calendario/abrir', 'EmpleadoCalendarioController@abrir');
Route::put('/api/calendario/editar', 'EmpleadoCalendarioController@update');
//Departamento
Route::get('/api/departamento/listar/{id}', 'DepartamentoController@index');
Route::post('/api/departamento/agregar', 'DepartamentoController@store');
Route::delete('/api/departamento/eliminar/{id}', 'DepartamentoController@eliminar');
Route::put('/api/departamento/abrir', 'DepartamentoController@abrir');
Route::put('/api/departamento/editar', 'DepartamentoController@update');
//Area de Trabajo
Route::get('/api/area_trabajo/listar/{id}', 'AreaTrabajoController@index');
Route::post('/api/area_trabajo/agregar', 'AreaTrabajoController@store');
Route::delete('/api/area_trabajo/eliminar/{id}', 'AreaTrabajoController@eliminar');
Route::put('/api/area_trabajo/abrir', 'AreaTrabajoController@abrir');
Route::put('/api/area_trabajo/editar', 'AreaTrabajoController@update');
//Cargo
Route::get('/api/cargo/listar/{id}', 'CargoController@index');
Route::post('/api/cargo/agregar', 'CargoController@store');
Route::delete('/api/cargo/eliminar/{id}', 'CargoController@eliminar');
Route::put('/api/cargo/abrir', 'CargoController@abrir');
Route::put('/api/cargo/editar', 'CargoController@update');
//Grupo Ocupacional
Route::get('/api/grupo_ocupacional/listar/{id}', 'GrupoOcupacionalController@index');
Route::post('/api/grupo_ocupacional/agregar', 'GrupoOcupacionalController@store');
Route::delete('/api/grupo_ocupacional/eliminar/{id}', 'GrupoOcupacionalController@eliminar');
Route::put('/api/grupo_ocupacional/abrir', 'GrupoOcupacionalController@abrir');
Route::put('/api/grupo_ocupacional/editar', 'GrupoOcupacionalController@update');
//Documento
Route::get('/api/documento/listar/{id}', 'DocumentacionController@index');
Route::post('/api/documento/agregar', 'DocumentacionController@store');
Route::delete('/api/documento/eliminar/{id}', 'DocumentacionController@eliminar');
Route::put('/api/documento/abrir', 'DocumentacionController@abrir');
Route::put('/api/documento/editar', 'DocumentacionController@update');

//Roles
Route::get('/api/rol/{id}', 'RolController@index');
//Usuario
Route::post('/api/regusuario', 'UserController@registro');
Route::post('/api/editarregusuario', 'UserController@editar');
Route::get('/api/recregusuario/{id}', 'UserController@listar');
Route::delete('/api/eliminarusuario/{id}', 'UserController@delete');
//modulos
Route::get('/api/listarmodulo/{id}', 'ModulosController@ver');
Route::post('/api/guardarmodulo', 'ModulosController@store');

Route::get('/empresas/{empresa}/firma/{filename}', function ($empresa, $filename) {
  $filePath = public_path() . '/empresas/' . $empresa . '/comprobantes/' . $filename;
  return $filePath;
  if (!File::exists($filePath) or (!$mimeType = getImageContentType($filePath))) {
    return Response::make("File does not exist.", 404);
  }

  $fileContents = File::get($filePath);

  return Response::make($fileContents, 200, array('Content-Type' => $mimeType));
});
// 
//});///
//Todas las rutas relativas de vuejs
Route::get('/{any}', 'ApplicationController')->where('any', '.*');
