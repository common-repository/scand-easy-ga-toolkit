<?php

/**
 * @package Scand_Easy_GA_Toolkit
 * @version 1.0.0
 *
 * Interface Scand_Easy_GA_Toolkit_Validator_Interface
 *
 * Validation interface
 */
interface Scand_Easy_GA_Toolkit_Validator_Interface
{
	public function processData( $data );

	public function validData();

	public function replaceByValid();
}