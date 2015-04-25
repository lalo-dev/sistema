<?php
//instanciar un objeto global a todos los php que incluyan este archivo
$bd = new BD;

class BD{
	//datos de conexion
	var $host = "localhost";
	var $user = "root";
	var $password = "";
	var $database = "cargayex";
	var $conn;
	
	//Abre la base de datos
	
	function  open(){
		$this->conn = mysql_connect($this->host, $this->user, $this->password) or die(mysql_error());
		mysql_select_db($this->database, $this->conn) or die (mysql_error());
		//mysql_query("SET NAMES 'utf8'");
	}
	
	// cierra la base de datos
	function close() {
		mysql_close($this->conn);
	}
	
	//De Aqui en adelante se definen los tipos de consultas que pueden haber
	
	/* Ejecuta una consulta que no devuelve resultados
	*
	*	@param string $sql  consulta SQL
	*/
	
	public function ExecuteNonQuery($sql){
		$this->open();
		$rs = mysql_query($sql, $this->conn);
		settype($rs, "null");
		$error = mysql_errno($this->conn);
		return $error;
	}

	/* Regresa el numero de regsitros de una consulta
	*
	*	@param query  consulta SQL
	*/
	public function numRows($query){
		$this->open();
		$rs = mysql_query($query, $this->conn);
		$numRegistros = mysql_num_rows($rs);
		return $numRegistros;
		$this->close();
	}

	public function get_select($table,$campo_id,$campo_des,$selected,$extra){

		$sql="SELECT $campo_id AS id,$campo_des AS descripcion FROM $table $extra;";
		$res = $this->Execute($sql);
		foreach ($res as $tipo)
		{
			$opciones.="<option value='".$tipo[0]."'>".$tipo[1]."</option>";	
		}
		return $opciones;
	}
	
	/*Ejecuta una consulta SQL
	*
	*	@param string $query Consulta SQL
	*	@return un array de registros, cada uno siendo un array asociativo de campos
	*/
	
	function Execute($query){
	$this->open();
	$rs = mysql_query($query, $this->conn);
	//se pasa el recordset a un array asociativo
	$registros = array();
	while($reg=mysql_fetch_array($rs)){
	$registros[] = $reg;
	}
	
	return $registros;
	$this->close();
	}
	
	/* Ejecuta una consulta devolviendo una fila (registro) con todos sus campos
	*	@param string $tableName Nombre de la tabla
	*	@param string $filter Filtro SQL para el where
	*	@return un array asociativo de campos
	*/
	
	function ExecuteRecord($tableName, $filter){
	$todos = $this->Execute("SELECT * FROM $tableName WHERE $filter");
	return $todos[0];
	}
	
	/*Ejecuta una consulta que devuelve una columna con todos sus registros
	*	@param string $tableName Nombre de la tabla
	*	@param string $field Nombre de campo a traer
	*	@param string $filter Filtro del where (por lo menos debe ser 1=1)
	*	@return un array asociativo de valores de cada registro
	*/
	
	function ExecuteField($tableName, $field,  $filter){
		$sql="SELECT DISTINCT $field FROM $tableName WHERE $filter";
		$todos = $this->Execute($sql);
		$aux = array();
		foreach ($todos as $uno){
		$aux[] = $uno[0];
		}
		return $aux;
	}
	function ExecuteFieldND($tableName, $field,  $filter){
		$sql="SELECT DISTINCT $field FROM $tableName WHERE $filter";
		$todos = $this->Execute($sql);
		$aux = array();
		foreach ($todos as $uno){
		$aux[] = $uno[0];
		}
		return $aux;
	}
	
	function ExecuteFieldf($sql){
		$todos = $this->Execute($sql);
		$aux = array();
		foreach ($todos as $uno){
		$aux[] = $uno[0];
		}
		return $aux;
	}
	
	function ExecuteFieldfn($sql){
		$todos = $this->Execute($sql);
		$aux = array();
		foreach ($todos as $uno){
		$aux[] = $uno;
		}
		return $aux;
	}

	//Toma varios campos
	function ExecuteFieldn($tableName, $fields,  $filter){
		$sql="SELECT DISTINCT $fields FROM $tableName WHERE $filter";
		$todos = $this->Execute($sql);
		$aux = array();
		foreach ($todos as $uno){
		$aux[] = $uno;
		}
		return $aux;
	}
	
		//Toma varios campos
	function ExecuteFieldInner($tableName, $fields,  $filter){
		$sql="SELECT DISTINCT $fields FROM $tableName $filter";
		//echo $sql;
		$todos = $this->Execute($sql);
		$aux = array();
		foreach ($todos as $uno){
		$aux[] = $uno;
		}
		return $aux;
	}
	function ExecuteFieldInner2($tableName, $fields,  $filter){
		$sql="SELECT $fields FROM $tableName $filter";
		$todos = $this->Execute($sql);
		$aux = array();
		foreach ($todos as $uno){
		$aux[] = $uno;
		}
		return $aux;
	}

	function minMax($minMax,$tabla){
		if($tabla!="")
			$queryM = "SELECT ".$minMax." AS minmax FROM ".$tabla.";";
		else
			$queryM = "SELECT ".$minMax." AS minmax FROM cguias WHERE cguias.estatus=1;";
		$dato = $this->soloUno($queryM);
		return $dato;
	}
	
	/* Trae todos los registros de una tabla
	*	@param string $tableName Nombre de la tabla
	*	@param string $orden Campo por el cual ordenar (opcional)
	*	@return un array de registros, cada uno un array asociativos
	*/
	
	function ExecuteTable($tableName, $orden = ""){
		if($orden!="") 
			return $this->Execute("SELECT * FROM ".$tableName." WHERE estatus='1' ORDER BY ". $orden);
		else
			return $this->Execute("SELECT * FROM ".$tableName." WHERE estatus='1'");
	}
	
	/* Trae un solo valor de la base de datos
	*	@param string $query Consulta SQL (1x1)
	*	@return el valor devuelto por la consulta
	*/
	
	public function ExecuteScalar($query){
	if ($this->status==BD::CERRADA) $this->open();
	$rs = mysql_query($query, $this->conn) or die(mysql_error());
	$reg = mysql_fetch_array($rs);
	return $reg[0];
	}
	
	/* Devuelve la cantidad de registros de una tabla
	*	@param string $tableName Nombre de la  tabla
	*	@return Cantidad de registros
	*/
	
	function RecordCount($tableName, $filter){
	return $this->ExecuteScalar("SELECT COUNT(*) FROM ".$tableName." WHERE ".$filter);
	}
    	function soloUno($sql){
    		$exists = $this->Execute($sql);
			$respuesta = array();
			foreach ($exists as $existe){
			$respuesta[]=$existe[0];
				}
			return $respuesta[0];
                 
	}
	//Obtiene un id Nuevo
	function get_Id($table,$campo){

	$sql="SELECT IFNULL(MAX($campo),0)+1 AS id FROM $table;";
	$todos = $this->Execute($sql);
	$id=$todos[0];

	return $id[0];
	}
}

?>
