<?php

abstract class Scand_Easy_GA_Toolkit_Base
{
	const TAB = "\t";
	const DOUBLE_TAB = "\t\t";

	protected function buildHrefString( $strFileExt )
	{
		$arr = explode( ',', $strFileExt );
		$selector = '';
		foreach ( $arr as $val ) {
			$ext = "." . trim( $val );
			$ahref = "a[href$=\\'{$ext}\\']";
			$selector .= $ahref . ", ";
		}
		return "'" . rtrim( $selector, ", " ) . "'";
	}

	protected function buildVars( $isDownload = false )
	{
		$s = self::DOUBLE_TAB . "var this_at = $(this);" . PHP_EOL;
		$s .= self::DOUBLE_TAB . "var href_at = this_at.prop('href').split('?')[0];" . PHP_EOL;
		if ( $isDownload ) {
			$s .= self::DOUBLE_TAB . "var ext_at = href_at.split('.').pop();" . PHP_EOL;
		}
		return $s;
	}

	public function buildFunctionArray( $arEvents, $isTestMode = false )
	{
		$data = array();
		foreach ( $arEvents as $key => $event ) {
			$data[ $key ] = $this->buildFunctionString( array( $event ), $isTestMode );
		}
		return $data;
	}

	public function buildHeadOfCustomEvent( $event )
	{
		$s = self::TAB . "$(document).on('{$event['event']}', '{$event['selector']}', function(event){" . PHP_EOL;
		if ( $event['prevent_default'] == 'yes' ) {
			$s .= self::DOUBLE_TAB . "event.preventDefault();" . PHP_EOL;
		} elseif ( ! empty( $event['prevent_default'] ) ) {
			if ( isset( $event['is_default'] ) ) {
				$s .= self::DOUBLE_TAB . $event['prevent_default'] . PHP_EOL;
			} else {
				$s .= '';
			}
		}
		if ( ! empty( $event['javascript'] ) ) {
			$s .= self::DOUBLE_TAB . str_replace( PHP_EOL, PHP_EOL . Scand_Easy_GA_Toolkit_Base::DOUBLE_TAB, stripslashes( $event['javascript'] ) ) . PHP_EOL;
		}
		return $s;
	}


	public function addConsoleForCommands( $command, $hittype, $param )
	{
		$s = "console.log('{$command}', {$hittype}";
		if ( $param != null ) {
			$s .= ", {$param}";
		}
		$s .= ");" . PHP_EOL;
		return $s;
	}

	public function buildHeadOfEvents( $strFileExt, $link, $eventType )
	{
		$s = '';
		if ( $strFileExt == null ) {
			if ( $eventType == 'error_404' ) {
				$s .= self::TAB . "if ($('body').hasClass('error404')) {" . PHP_EOL;
			} else {
				$s .= self::TAB . "$(document).on('click', {$link}, function(event){" . PHP_EOL;
				if ( $eventType == 'outbound' ) {
					$s .= self::DOUBLE_TAB . "if (location.hostname != this.hostname) {" . PHP_EOL;
				} else {
					$s .= $this->buildVars();
				}
			}
		} else {
			$sel = $this->buildHrefString( $strFileExt );
			$s = self::TAB . "$(document).on('click', {$sel}, function(event){" . PHP_EOL;
			$s .= $this->buildVars( true );
		}
		return $s;
	}

	abstract public function addScript( $id, $isTestMode );

	abstract public function buildFunctionString( $arEvents, $isTestMode );

	abstract public function addCreateCommand( $o, $isTestMode );

	abstract public function addSetCommand( $isTestMode );

	abstract public function addSendCommand( $isTestMode );

	abstract public function addEvent( $strFileExt, $link, $eventType, $eventParams, $isTestMode );

	abstract function buildEventConsole( $eventType, $eventParams );
}