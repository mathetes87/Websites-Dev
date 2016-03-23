SELECT DISTINCT  
  r.id_reclamo                                     "ID RECLAMO",
  r.rut_cliente                                    "RUT CLIENTE",
  r.fecha_apertura                                 "FECHA CREACIÓN TICKET",
  r.fecha_cierre                                   "FECHA CIERRE TICKET",
  ROUND(r.fecha_cierre - r.fecha_creacion, 2)      "DIAS DE CIERRE TICKET",
  tr.nombre_tiporeclamo                            "TIPO RECLAMO",
  r.subarea                                        "PREGUNTA/RECLAMO",
  r.activo                                         "ACTIVO",
  ts.tipo_servicio                                 "TIPO SERVICIO",
  d.comuna                                         "COMUNA",
  c.segmento_ctaserv                               "SEGMENTO",
  --g.giro_comercial                                 "GIRO COMERCIAL",
  r.estado	                                       "ESTADO TICKET",
  r.manifiesta_baja                                "MANIFIESTA BAJA",
  c.razon_social                                   "RAZÓN SOCIAL",
  c.nombre_fantasia                                "NOMBRE FANTASÍA",
  i.fecha_baja                                     "FECHA BAJA INSTANCIA"
FROM 
  sicret.reclamo r, 
  sicret.tipo_reclamo tr, 
  sicret.direccion d, 
  sicret.instancia i, 
  sicret.cta_servicio c, 
  sicret.tipo_servicio ts
  --sicret.giro_comercial g
WHERE 
  i.id_cta_servicio1 = c.id_cta_serv 
  AND c.id_direcc_serv = d.id_direcc_sicret 
  AND r.activo = i.activo 
  AND r.id_tipo_reclamo = tr.id_tipo_reclamo
  AND i.id_tipo_linea = ts.id_tipo_linea
  --AND c.cod_giro_comercial = g.id_registro
  --AND r.estado LIKE 'CERRADO'
  AND r.fecha_creacion > '01/01/2014'
  AND r.fecha_creacion < '01/01/2015'
  
