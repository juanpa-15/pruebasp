<?php
    namespace Jpo\Prueba;
    
    use Jpo\Prueba\mainModel;

    class VehiculoController extends mainModel{

        public function registrarVehiculoControler(array $dataVehiculo){
			if (!isset($dataVehiculo['placa_vehiculo_visitante'],$dataVehiculo['tipo_vehiculo_visitante'],$dataVehiculo['num_documento_visitante']) || $dataVehiculo['placa_vehiculo_visitante'] == "" || $dataVehiculo['num_documento_visitante'] == "" || $dataVehiculo['tipo_vehiculo_visitante'] == "" ) {
				$mensaje=[
					"titulo"=>"Error",
					"mensaje"=>"Lo sentimos, los datos necesarios para registrar el vehiculo son insuficientes."
				];
				return json_encode($mensaje);
				exit();
			}else {
				

				$campos_invalidos = [];
				if ($this->verificarDatos('[0-9]{6,15}',$dataVehiculo['num_documento_visitante'])) {
					array_push($campos_invalidos, 'NUMERO DE DOCUMENTO');	
				}else {

					$num_documento_ps = $this->limpiarDatos($dataVehiculo['num_documento_visitante']); 
				}

				if ($this->verificarDatos('[A-Z]{2,}',$dataVehiculo['tipo_vehiculo_visitante'])) {
					array_push($campos_invalidos, 'TIPO DE VEHICULO');
				}else{
					$tipo_vehiculo_ps = $this->limpiarDatos($dataVehiculo['tipo_vehiculo_visitante']);
				}
				if ($this->verificarDatos('[A-Z0-9]{6,7}',$dataVehiculo['placa_vehiculo_visitante'])) {
					array_push($campos_invalidos, 'PLACA DE VEHICULO');
				}else {
					$placa_vehiculo_ps = $this->limpiarDatos($dataVehiculo['placa_vehiculo_visitante']);
				}


				
				if (count($campos_invalidos) > 0)  {
					$invalidos = "";
					foreach ($campos_invalidos as $campos) {
						if ($invalidos == "") {
							$invalidos = $campos;
						}else {
							$invalidos = $invalidos.", ".$campos;
						}
					}
					$mensaje=[
						"titulo"=>"Campos incompletos",
						"mensaje"=>"Lo sentimos, los campos ".$invalidos." no cumplen con el formato solicitado.",
						"icono"=> "error",
						"tipoMensaje"=>"normal"
					];
					return json_encode($mensaje);
					exit();
				}else {

					for ($i=0; $i < 5; $i++) { 
						

						switch ($i) {
							case 0:
								$tipo_persona = 'Visitante';
								$consultar_persona ="SELECT num_identificacion FROM `visitantes` WHERE num_identificacion = '$num_documento_ps';";
								break;
							case 1:
								$tipo_persona = 'Visitante';
								$consultar_persona ="SELECT num_identificacion FROM `visitantes` WHERE num_identificacion = '$num_documento_ps';";
								break;
							case 2:
								$tipo_persona = 'Funcionario';
								$consultar_persona ="SELECT num_identificacion FROM `funcionarios` WHERE num_identificacion = '$num_documento_ps';";
								break;
								
							case 3:
								$tipo_persona = "Vigilante";
								$consultar_persona ="SELECT num_identificacion FROM `vigilantes` WHERE num_identificacion = '$num_documento_ps';";
								break;
							
							case 4:
								$tipo_persona =  "Aprendiz";
								$consultar_persona ="SELECT num_identificacion FROM `aprendices` WHERE num_identificacion = '$num_documento_ps';";
								break;
								
							default:
								$mensaje=[
									"titulo"=>"No lo encontramos!",
									"mensaje"=>"Lo sentimos, no locagramos encontralo.",
									"icono"=> "error",
									"tipoMensaje"=>"normal"
								];
								return json_encode($mensaje);
							
							}
						$buscar_persona = $this->ejecutarConsulta($consultar_persona);

						if (!$buscar_persona) {
							$mensaje=[
								"titulo"=>"Error de Conexion",
								"mensaje"=>"Lo sentimos, algo salio mal con la conexion por favor intentalo de nuevo mas tarde.",
								"icono"=> "error",
								"tipoMensaje"=>"normal"
							];
							return json_encode($mensaje);
							break;
						}else {
							if ($buscar_persona->num_rows > 0) {
								break;
							}
						}
						
					}

					if (!$buscar_persona) {
						$mensaje=[
							"titulo"=>"Error de Conexion",
							"mensaje"=>"Lo sentimos, algo salio mal con la conexion por favor intentalo de nuevo mas tarde.",
							"icono"=> "error",
							"tipoMensaje"=>"normal"
						];
						return json_encode($mensaje);
					}else {
						if ($buscar_persona->num_rows < 1) {
							$mensaje=[
								"titulo"=>"Usuario No Registrado.<br> Lo sentimos",
								"mensaje"=>"El usuario con número de documento $num_documento_ps se encuentra registrado en Cerberus.  ¿Deseas Registrarlo como VISITANTE?",
								"icono"=> "info",
								"tituloModal"=>"Registro Visitante",
								"adaptar"=>"none",
								"url"=> "..app/views/inc/modales/modal-registro-visitante.php",
								"tipoMensaje"=>"normal_redireccion"
							];
							return json_encode($mensaje);
							exit();	
						}else {
			
							$registrar_vehiculo = $this->registrarNuevoVehiculo($dataVehiculo['placa_vehiculo_visitante'],$dataVehiculo['tipo_vehiculo_visitante'],$dataVehiculo['num_documento_visitante'], $_SESSION['datos_usuario']['num_identificacion']);
							if (!$registrar_vehiculo) {
								$mensaje=[
									"titulo"=>"Error",
									"mensaje"=>"Lo sentimos, no nos pudimos conectar a la base de datos intentalo de nuevo mas tarde.",
									"icono"=> "error",
									"tipoMensaje"=>"normal"
								];
								return json_encode($registrar_vehiculo);
								exit();
							}else { 
								$mensaje=[
									"titulo"=>"Registro Exitoso",
									"mensaje"=>"Genial el registro a sido exitoso.",
									"icono"=> "success",
									"tipoMensaje"=>"normal"
								];
								return json_encode($registrar_vehiculo);
								exit();
							}
						}
					}

				}
				
			}
		}
    }