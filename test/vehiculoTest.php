<?php
namespace Jpo\Prueba\Test;

use Jpo\Prueba\VehiculoController;


/* require_once __DIR__ . '/../src/vehiculoController.php'; */

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class vehiculoTest extends TestCase {
    private $dbMock;

    protected function setUp(): void {
        $this->dbMock = $this->createMock(\PDO::class);
    }

    public function testProcesarFormularioDatosValidosvigi() {

        $claseVehiculo = new VehiculoController($this->dbMock);

        $datosVehiculo = [
            'tipo_documento' => 'CC',
            'num_identificacion' => '1112149201',
            'nombres' => 'juan pablo',
            'apellidos' => 'osorio',
            'telefono' => '3023646789',
            'correo' => 'osoriogal@gmail.com',
            'rol_usuario' => 'jv',
            'tipo_vehiculo_vigilante' => 'MT',
            'placa_vehiculo_vigilante' => 'CAB879',
        ];

        $resultado = $claseVehiculo->registrarVehiculoControler($datosVehiculo);

        $this->assertEquals(
            '{"titulo":"Error","mensaje":"Lo sentimos, los datos necesarios para registrar el vehiculo son insuficientes."}',
            $resultado
        );
    }
}
