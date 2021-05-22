<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : FloatPHP
 * @subpackage: Kernel Component
 * @version   : 1.0.0
 * @category  : PHP framework
 * @copyright : (c) 2017 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://www.floatphp.com
 * @license   : MIT License
 *
 * This file if a part of FloatPHP Framework
 */

namespace FloatPHP\Kernel;

use FloatPHP\Classes\Filesystem\TypeCheck;
use FloatPHP\Classes\Filesystem\Json;
use FloatPHP\Exceptions\Kernel\ConfigException;
use JsonSchema\Validator as JsonValidator;

final class Validator
{
	/**
	 * @access public
	 * @var mixed $json
	 * @return mixed
	 * @throws ConfigException
	 */
	public static function checkConfig($json)
	{
		try {
			$error = self::isValidConfig($json,'config.schema.json');
			if ( TypeCheck::isString($error) ) {
				throw new ConfigException($error);
			} elseif ( $error === false ) {
				throw new ConfigException();
			}
		} catch (ConfigException $e) {
			die($e->get(1));
		}
		return $json;
	}

	/**
	 * @access public
	 * @var mixed $json
	 * @return mixed
	 * @throws ConfigException
	 */
	public static function checkModuleConfig($json)
	{
		try {
			$error = self::isValidConfig($json,'module.schema.json');
			if ( TypeCheck::isString($error) ) {
				throw new ConfigException($error);
			} elseif ( $error === false ) {
				throw new ConfigException();
			}
		} catch (ConfigException $e) {
			die($e->get(2));
		}
		return $json;
	}

	/**
	 * @access public
	 * @var mixed $json
	 * @return mixed
	 * @throws ConfigException
	 */
	public static function checkRouteConfig($json)
	{
		try {
			$error = self::isValidConfig($json,'route.schema.json');
			if ( TypeCheck::isString($error) ) {
				throw new ConfigException($error);
			} elseif ( $error === false ) {
				throw new ConfigException();
			}
		} catch (ConfigException $e) {
			die($e->get(3));
		}
		return $json;
	}

	/**
	 * @access public
	 * @var mixed $access
	 * @return void
	 * @throws ConfigException
	 */
	public static function checkDatabaseAccess($access)
	{
		try {
			if ( !isset($access['default']) || !isset($access['root']) ) {
				throw new ConfigException();
			}
		} catch (ConfigException $e) {
			die($e->get(4));
		}
	}

	/**
	 * @access private
	 * @var mixed $config
	 * @return mixed
	 */
	private static function isValidConfig(Json $config, $schema)
	{
		if ( $config->parse() && !empty($config->parse()) ) {
			$validator = new JsonValidator;
			$json = $config->parse();
			$validator->validate($json, (object)[
				'$ref' => 'file://' . dirname(__FILE__). '/bin/' . $schema
			]);
			if ( $validator->isValid() ) {
				return true;
			} else {
				$errors = [];
			    foreach ($validator->getErrors() as $error) {
			        $errors[] = sprintf("[%s] %s",$error['property'],$error['message']);
			    }
			    return implode("\n", $errors);
			}
		}
		return false;
	}
}
