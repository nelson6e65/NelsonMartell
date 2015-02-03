<?php
/**
 * PHP class «Version»
 *
 * Copyright © 2015 Nelson Martell (http://fb.me/nelson6e65)
 *
 * Licensed under The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright  Copyright © 2015 Nelson Martell
 * @link       http://nelson6e65.github.io/php_nml/
 * @package    NelsonMartell
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License (MIT)
 *
 * */

namespace NelsonMartell {
	use \InvalidArgumentException;

	/**
	 * Representa el número de versión de un elemento o ensamblado, de la forma "1.0.0.0". Sólo
	 * siendo obligatorios el primer y segundo componente.
	 * No se puede heredar esta clase.
	 *
	 *
	 * @package  NelsonMartell
	 * @author   Nelson Martell (@yahoo.es: nelson6e65-dev)
	 * */
	final class Version extends Object implements IEquatable, IComparable {

		/**
		 * Crea una nueva instancia con los números principal, secundario, de compilación (opcional)
		 * y revisión (opcional).
		 *
		 * @param  int $major  Componente principal
		 * @param  int $minor  Componente secundario
		 * @param  mixed  $build  Componente de compilación
		 * @param  mixed  $revision  Componente de revisión
		 * @throw  InvalidArgumentException
		 * */
		function __construct($major = 0, $minor = 0, $build = 0, $revision = 0) {
			parent::__construct();
			unset($this->Major, $this->Minor, $this->Build, $this->Revision);

			if (!is_integer($major)) {
				throw new InvalidArgumentException(sprintf(_("Invalid argument type. '%s' (argument %s) must be an instance of '%s', '%s' given. Convert value or use the static method Version::Parse(string|mixed) to create a new instance from an string."), "major", 1, typeof(0), typeof($major)));
			}

			if (!is_integer($minor)) {
				throw new InvalidArgumentException(sprintf(_("Invalid argument type. '%s' (argument %s) must be an instance of '%s', '%s' given. Convert value or use the static method Version::Parse(string|mixed) to create a new instance from an string."), "minor", 2, typeof(0), typeof($major)));
			}

			if ($major < 0) {
				throw new InvalidArgumentException(sprintf(_("Invalid argument value. '%s' (argument %s) must be a positive number; '%s' given."), "major", 1, $major));
			}

			if ($minor < 0) {
				throw new InvalidArgumentException(sprintf(_("Invalid argument value. '%s' (argument %s) must be a positive number; '%s' given."), "minor", 2, $minor));
			}

			$this->_major = $major;
			$this->_minor = $minor;
			$this->_build = VersionComponent::Parse($build);
			$this->_revision = VersionComponent::Parse($revision);
		}

		/**
		 * Convierte una cadena a su representación del tipo Version
		 *
		 *
		 * @param   string  Cadena a convertir.
		 * @return  Version Objeto convertido desde $value.
		 * */
		public static function Parse($value) {
			if ($value instanceof Version) {
				return $value;
			}

			$version = (string) $value;

			$version = explode('.', $version);

			$c = count($version);

			if ($c > 4 || $c < 2) {
				//var_dump($version);
				throw new InvalidArgumentException(sprintf(_("Unable to parse. Argument passed has an invalid format: '%s'."), $value));
			}


			$major = (int) $version[0];
			$minor = (int) $version[1];
			$build = 0;
			$revision = 0;

			if(count($version) >= 3) {
				$build = VersionComponent::Parse($version[2]);

				if(count($version) == 4) {
					$revision = VersionComponent::Parse($version[3]);
				}
			}



			return new Version($major, $minor, $build, $revision);
		}

		/**
		 * Obtiene el valor del componente principal del número de versión del objeto actual.
		 *
		 *
		 * @var  int Componente principal del número de versión
		 * */
		public $Major;
		private $_major;

		public function get_Major() { return $this->_major; }


		/**
		 * Obtiene el valor del componente secundario del número de versión del objeto actual.
		 *
		 *
		 * @var  int Componente secundario del número de versión
		 * */
		public $Minor;
		private $_minor;

		public function get_Minor() { return $this->_minor; }

		/**
		 * Obtiene el valor del componente de compilación del número de versión del objeto actual.
		 *
		 *
		 * @var  VersionComponent  Componente de compilación del número de versión
		 * */
		public $Build;
		private $_build;

		public function get_Build() { return $this->_build; }

		/**
		 * Obtiene el valor del componente de revisión del número de versión del objeto actual.
		 *
		 *
		 * @var  VersionComponent  Componente de revisión del número de versión
		 * */
		public $Revision;
		private $_revision;

		public function get_Revision() { return $this->_revision; }


		/**
		 * Convierte la instancia actual en su representación en cadena.
		 * Por defecto, si no se especifica el número de revisión (o es menor a 1),
		 * no se incluye en la salida.
		 * Si tampoco se especifica el número de compilación (o es menor a 1),
		 * tampoco se incluye el número de revisión.
		 * Los componentes principal y secundario siempre se muestran, aunque sean cero (0).
		 *
		 *
		 * @return  string Representación de la versión en forma de cadena:
		 *   'major.minor[.build[.revision]]'
		 * */
		public function ToString() {
			$s = $this->Major . '.' . $this->Minor;
			//var_dump($this->Build);

			if ($this->Build->IntValue > 0) {
				$s .= '.' . $this->Build;

				if ($this->Revision->IntValue > 0) {
					$s .= '.' . $this->Revision;
				}

			}

			return $s;
		}

		/**
		 * Indica si la instancia actual es un número de versión válido.
		 * Al menos un atributo de la versión debe estar establecido.
		 *
		 *
		 * @return  boolean Un valor que indica si la instancia actual es válida.
		 * */
		public function IsValid() {
			if (!$this->Major){
				if (!$this->Minor) {
					if (!$this->Build->IntValue > 0) {
						if (!$this->Revision->IntValue > 0) {
							return false;
						}
					}
				}
			}

			return true;
		}

		/**
		 * Determina si el objeto $other especificado es igual a la instancia actual.
		 *
		 *
		 * @return  bool True si $other es igual esta instancia
		 * */
		public function Equals($other) {
			if ($other instanceof Version) {
				if ($this->Major == $other->Major && $this->Minor == $other->Minor) {
					if ($this->Build->Equals($other->Build)) {
						if ($this->Revision->Equals($other->Revision)) {
							return true;
						}
					}
				}
			}

			return false;
		}


		#region IComparable

		/**
		 * Determina la posición relativa del objeto especificado con respecto a esta instancia.
		 *
		 *
		 * @param   Version  $other
		 * @return  integer  0, si es igual; >0, si es mayor; <0, si es menor.
		 * */
		public function CompareTo($other){

			$r = $this->Equals($other) ? 0 : 9999;

			if ($r != 0) {
				$r = $this->Major - $other->Major;

				if ($r == 0) {
					$r = $this->Minor - $other->Minor;

					if ($r == 0) {
						$r = $this->Build->CompareTo($other->Build);

						if ($r == 0) {
							$r = $this->Revision->CompareTo($other->Revision);
						}
					}
				}
			}

			return $r;
		}

		#endregion

	}
}