<?php

namespace App\Helpers;

class SerialPortHelper
{
	/**
	 * La conexión con el puerto.
	 * 
	 * @var object
	 */
	private $serialPortResource;

	/**
	 * El nombre del puerto, en unix empiza con cu.usbserial, en windows empieza con
	 * comX: donde X es el número de puerto, muy importante los dos puntos al final.
	 * 
	 * @var string
	 */
	public $portName = 'com1:';

	/**
	 * El baud rate o velocidad del puerto, puede ser un valor entre:
	 * - 75, 110, 300, 1200, 2400, 4800, 9600, 19200, 38400, 57600 y 115200 bit/s
	 * 
	 * Leer mas aquí:
	 * https://en.wikipedia.org/wiki/Serial_port#Speed
	 * 
	 * @var integer
	 */
	public $baudRate = 9600;

	/**
	 * El número de bits de cada caracter de los datos, puede ser un valor entre:
	 * - 5, (para Baudot code)
	 * - 6, (poco común)
	 * - 7, (para true ASCII)
	 * - 8, (es el más común)
	 * - 9, (poco común)
	 *
	 * Leer mas aquí:
	 * https://en.wikipedia.org/wiki/Serial_port#Data_bits
	 * 
	 * @var integer
	 */
	public $bits = 8;

	/**
	 * Bits de parada a enviar al final de cada caracter, leer más aquí:
	 * https://en.wikipedia.org/wiki/Serial_port#Stop_bits
	 * 
	 * @var integer
	 */
	public $stopBits = 1;

	public function __construct($params)
	{
		$this->portName = $params[0];
		$this->baudRate = $params[1];
		$this->bits 	= $params[2];
		$this->stopBits = $params[3];
	}

	/**
	 * Revisa si la extención ha sido instalada correctamente. La extención puede ser
	 * descargada de aquí:
	 * http://pecl.php.net/package/dio
	 * 
	 * Documentación de la extención:
	 * http://php.net/manual/en/book.dio.php
	 *
	 * Un corto turotial sobre la instalación:
	 * http://www.brainboxes.com/faq/items/how-do-i-control-a-serial-port-using-php
	 *
	 * IMPORTANTE, se la extención no está siendo cargada pero los ficheros están en
	 * la carpeta de extenciones de PHP, verificar que se ha añadido la extención en
	 * el php.ini:
	 * 
	 * Unix -> extension=dio.so
	 * Windows -> extension=php_dio.dll
	 *
	 * He probado que en windows la extención es quitada en cada reinicio de la
	 * máquina, hay que estar añadiendo constantemente a php.ini la extención.
	 * 
	 * @return bool
	 */
	public function isTheExtentionInstaled()
	{
		return extension_loaded('dio');
	}

	/**
	 * Realiza la conexión con el puerto serial, si retorna true es porque la conexión
	 * está abierta y lista para recibir <<readData()>> o  envíar datos <<sendData()>>.
	 * 
	 * @return bool
	 */
	public function conectToSerialPort()
	{
		try {
			// si el sistema es windows
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){

				// abrimos la conexión
				$this->serialPortResource = \dio_open($this->portName, O_RDWR);
				// como estamos en windows, se ha de configurar el puerto desde la línea de comandos
				exec("mode {$this->portName} baud={$this->baudRate} data={$this->bits} stop={$this->stopBits} parity=n xon=on");

			}else{ // caso para 'nix

				// abrimos la conexión
				$this->serialPortResource = \dio_open($this->portName, O_RDWR | O_NOCTTY | O_NONBLOCK );
				dio_fcntl($this->serialPortResource, F_SETFL, O_SYNC);

				// al estar en 'nix configuramos COM directamente desde la función io de php
				dio_tcsetattr($this->serialPortResource, array(
					'baud' 		=> $this->baudRate,
					'bits' 		=> $this->bits,
					'stop'  	=> $this->stopBits,
					'parity' 	=> 0
				));

			}
			
			// si no se pudo conectar
			if(!$this->serialPortResource)
				return false;
			
		} catch (Exception $e) {
			var_dump($e->getMessage());
			return false;
		}

		return true;
	}

	/**
	 * Lee los datos el puerto serial.
	 * 
	 * @return mixed
	 */
	public function readData()
	{
		// tiempo límete para leer los datos, cinco segundos
		$runForSeconds = new \DateInterval("PT5S");
		$endTime = (new \DateTime())->add($runForSeconds);
		while(new \DateTime < $endTime){
			// leo los datos del puerto
			$data = dio_read($this->serialPortResource, 256); // llamada de bloque
			
			// si se han recibido datos, los devuelvo
			if ($data)
				return $data;
		}
		
		return null;
	}

	/**
	 * Envía datos al puerto serial
	 * 
	 * @return mixed
	 */
	public function sendData()
	{
		// los datos a enviar
		$dataToSend = "HELLO WORLD!";
		// enviamos los datos
		$bytesSent = dio_write($this->serialPortResource, $dataToSend);
		
		// devolvemos los datos envíados
		return $bytesSent;
	}

	/**
	 * Cierra la conexión con el puerto serial
	 */
	public function closeConexion()
	{
		dio_close($this->serialPortResource);
	}
}