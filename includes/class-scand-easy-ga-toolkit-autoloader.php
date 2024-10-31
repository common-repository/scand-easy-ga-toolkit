<?php

/**
 * @package Scand_Easy_GA_Toolkit
 * @version 1.0.0
 *
 * Scand_Easy_GA_Toolkit_Autoloader class
 *
 * Take care of loading classes only from Scand_Easy_GA_Toolkit plugin
 */
class Scand_Easy_GA_Toolkit_Autoloader
{
	/**
	 * @param bool $prepend
	 */
	public static function register( $prepend = false )
	{
		if ( version_compare( phpversion(), '5.3.0', '>=' ) ) {
			spl_autoload_register( array( new self, 'autoload' ), true, $prepend );
		} else {
			spl_autoload_register( array( new self, 'autoload' ) );
		}
	}

	/**
	 * Handles autoloading of Scand Easy Google Analytics Toolkit plugin classes.
	 *
	 * @param string $class
	 */
	public static function autoload( $class )
	{
		if ( 0 !== strpos( $class, 'Scand_Easy_GA_Toolkit' ) ) {
			return;
		}
		$formattedClassName = strtolower( str_replace( array( '_', "\0" ), array( '-', '' ), $class ) . '.php' );
		if ( is_file( $file = dirname( __FILE__ ) . '/class-' . $formattedClassName ) ) {
			require_once $file;
		}
	}
}