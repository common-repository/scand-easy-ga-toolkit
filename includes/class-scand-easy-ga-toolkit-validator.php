<?php

/**
 * @package Scand_Easy_GA_Toolkit
 * @version 1.0.0
 *
 * Class Scand_Easy_GA_Toolkit_Validator
 *
 * Validation base class
 */
abstract class Scand_Easy_GA_Toolkit_Validator implements Scand_Easy_GA_Toolkit_Validator_Interface
{
	protected $error;
	protected $mandatory;

	/**
	 * Scand_Easy_GA_Toolkit_Validator constructor.
	 */
	public function __construct()
	{
		$this->mandatory = array();
		$this->error = array();
	}

	/**
	 * @param $data
	 */
	public function processData( $data )
	{
	}

	/**
	 * Main validation method
	 *
	 * @return array
	 */
	public function ValidData()
	{
		return $this->error;
	}

	/**
	 * Return processed data to caller method
	 *
	 * @return array
	 */
	public function replaceByValid()
	{
		return array();
	}

	protected function stripQuotes( $input )
	{
		$input = stripslashes( $input );
		$s = null;
		if ( substr( $input, 0, 1 ) == "'" || substr( $input, 0, 1 ) == "\"" ) {
			$s = substr( $input, 1 );
		}
		if ( substr( $input, -1 ) == "'" || substr( $input, -1 ) == "\"" ) {
			$s = is_null( $s ) ? substr( $input, 0, -1 ) : substr( $s, 0, -1 );
		}

		return ( is_null( $s ) ) ? $input : $s;
	}
}