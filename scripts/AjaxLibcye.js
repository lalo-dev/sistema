// JavaScript Document
/* AjaxLib, tomado de ISBN 978-970-15-1328-6 */

/** Especificando opciones para tipoRespuesta*/
var $tipo = {
	XML: 0,
	TEXTO: 1,
	JSON: 2
	}
	
/* especificando opciones para método */
var $metodo = {
	GET: "GET",
	POST: "POST"
	}
	
	/*	Realiza un nuevo requerimento AJAX basado en una url y opciones que se definan 
	*	@param {string} url. URL donde se realizara la petición.
	*	@param {object} opciones. Un objeto JSON con los atributos opcionales que queremos definir
	*
	*	Lista de opciones disponibles
	*		id: Un identificador interno para ser recibido junto a los datos
	*		metodo: $metodo.POST o metodo.GET
	*		tipoRespuesta: $tipo.TEXTO, $tipo.JSON o $tipo.XML
	*		parametros: un string en formato URL o un objeto Hash
	*		cache: true o false
	*		avisoCargando: define el id de un elemento que queremos usar como cartel de "Cargando" mientras se realiza la peticion
	*		onfinish: función a ejecutarse cuando se reciban los datos, recibe el Texto, JSON o XML pedido y el id de la peticion
	*		onerror: función a ejecutarse cuando se produzca un error, recibe un objeto con detalles del error y el id de la peticion
	*/
	
	function $Ajax(url, opciones){
			//definiendo uso de caché
			
		if(__$P(opciones, "cache", true)==false){
			//se agrega un parámetro random a la URL
			//según la presencia de parametros anteriores, se agrega ? o &
			var caracter = "?";
			if(url.indexOf("?")>0) caracter = "&";
			url += caracter + Math.random();
			}
			var metodo = __$P(opciones, "metodo", $metodo.GET);
			var parametros = __$P(opciones, "parametros");
			
			//Genera JSON de propiedades necesarias para prototype, puede reemplazarse por otra libreria
			var protoOpc = {
				method: metodo,
				
				onSuccess: __$AjaxRecibir.bind(this, opciones),
				onException: __$AjaxError.bind(this, opciones),
				onFailure: __$AjaxError.bind(this, opciones)
				}
				
				//Si se definieron los parámetros, es hora de agregarlos
				if(parametros!=undefined){
					protoOpc.parameters = parametros;
					
					}
					
					//Genera la nueva petición vía prototype
					var peticion = new Ajax.Request(url, protoOpc);
					
					//Muestra el cartel de Cargando si existiera
					if(__$P(opciones, "avisoCargando")!=undefined){
						__$AjaxCargando(opciones.avisoCargando, true);
						}
		}
		
		/**
		*Funcion que se encarga de recibir la petición lista desde Prototype y ejecutar el evento onfinish de la peticion
		*/
		
		function __$AjaxRecibir(opciones, xhr){
			// si se ejecuta éste método, estámos seguros de que readyState == 4 y status == 200
			
			//apaga el cartel de Cargando si existiera
			if(__$P(opciones, "avisoCargando")!=undefined){
				__$AjaxCargando(opciones.avisoCargando, false);
				}
			
				
				//Se recupera  la función onfinish si fue definida
				var funcionRetorno = __$P(opciones, "onfinish");
				//se recupera el identificador de la petición si fue definido
				var id = __$P(opciones, "id");
				
				if(funcionRetorno!=undefined){
			// Si el usuario indicó que quiere recuperar la respuesta, se supone TEXTO como tipo por defecto
					var tipoRespuesta = __$P(opciones, "tipoRespuesta", $tipo.TEXTO);
					switch(tipoRespuesta){
						case $tipo.TEXTO:
							funcionRetorno(xhr.responseText, id);
							
							break;
						case $tipo.XML:
							funcionRetorno(xhr.responseXML, id);
							
							break;
						case $tipo.JSON:
							//se evalúa el JSON por si no es válido
							var objeto;
							try{
								objeto = xhr.responseText.evalJSON();
								} catch (e){__$AjaxError(opciones, xhr, { code: -1, message: "JSON No válido"});
								return;
								}
							funcionRetorno(objeto, id);	
						}
						  
					}
					
			}
			
			/* Función que se encarga de prender o apagar le cartel de cargando si existe*/
			function __$AjaxCargando(cartel, prender){
				if(prender){
					$(cartel).show();
				} else{
					$(cartel).hide();
				}
			}
			
			/* función que se encarga de recibir la ejecución cuando se produzca algun error en la petición
			desde pototype*/
			
			
		function __$AjaxError(opciones, xhr, excepcion){
			//apagando cartel cargando
			if(__$P(opciones, "avisoCargando")!=undefined){
				__$AjaxCargando(opciones.avisoCargando, false);
			}
			//error de servidor
			if(excepcion==undefined){
				//suponemos error http
				excepcion = {code: xhr.status, message: "Error del servidor"}
			}
			//consultar si estaba definido el evento onerror
			var funcionError = __$P(opciones, "onerror");
			if (funcionError!=undefined){
				funcionError(excepcion, __$P(opciones, "id"));
			}
		}
		/* funcion que se encarga de entregar un parametro opcional desde una coleccion tipo JSON con un valor
		por defecto*/
		function __$P(coleccion, parametro, defecto){
				if(coleccion==undefined){
					return defecto;
					} else{
						if (coleccion[parametro]==undefined){
							return defecto;
							}else{
								return coleccion[parametro];
								}
						}
			}