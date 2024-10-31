<?php

class Scand_Easy_GA_Toolkit_Gtag extends Scand_Easy_GA_Toolkit_Base
{

	public function addScript( $id, $isTestMode = false )
	{
		$str = "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$id}\"></script>" . PHP_EOL;
		$str .= "<script  type=\"text/javascript\">" . PHP_EOL . "window.dataLayer = window.dataLayer || [];" . PHP_EOL;
		$str .= "function gtag(){dataLayer.push(arguments)};" . PHP_EOL;
		$gtagJS = "%sgtag('js', new Date());" . PHP_EOL;
		$consoleJS = $this->addConsoleForCommands( 'js', 'new Date()', null );
		$gtagConfig = "%sgtag('config', '{$id}');" . PHP_EOL;
		$consoleConfig = $this->addConsoleForCommands( 'config', "'{$id}'", null );
		if ( $isTestMode ) {
			$str .= $consoleJS . sprintf( $gtagJS, "//" ) . $consoleConfig . $gtagConfig = sprintf( $gtagConfig, "//" );
		} else {
			$str .= sprintf( $gtagJS, "" );
			$str .= sprintf( $gtagConfig, "" );
		}
		return $str;
	}

	public function buildFunctionString( $arEvents, $isTestMode = false )
	{
		$output = '';
		foreach ( $arEvents as $event ) {
			$s = $this->buildHeadOfCustomEvent( $event );
			$ga = '';
			$ga .= self::DOUBLE_TAB . "%sgtag('{$event['hittype']}'";
			$ga .= ", '{$event['action']}'";
			$category = $event['category'] == '' ? 'general' : $event['category'];
			$ga .= ", {'event_category': '{$category}'";

			if ( isset( $event['label'] ) && strlen( $event['label'] ) > 0 ) {
				if ( isset( $event['is_default'] ) ) {
					$ga .= ", {{EMPTY_LABEL}}";
				} else {
					$ga .= ( $event['label_type'] == 'var' ) ? ", 'event_label': {$event['label']}" : ", 'event_label': '{$event['label']}'";
				}
			}

			if ( isset( $event['value'] ) && strlen( $event['value'] ) > 0 ) {
				if ( isset( $event['is_default'] ) ) {
					$ga .= ", {{EMPTY_VALUE}}";
				} else {
					$ga .= ( $event['value_type'] == 'var' ) ? ", 'value': {$event['value']}" : ", 'value': '{$event['value']}'";
				}
			}

			if ( isset( $event['is_default'] ) ) {
				$ga .= ", nonInteraction: {{NON_INTER_GTAG}}";
			} elseif ( $event['non_inter'] == 'on' ) {
				$ga .= ", nonInteraction: true";
			} else {
				$ga .= ", nonInteraction: false";
			}
			$ga .= "});" . PHP_EOL;

			if ( ! $isTestMode ) {
				$s .= sprintf( $ga, '' );
			} else {
				$s .= $this->buildEventConsole( 'custom_event', $event );
				$s .= sprintf( $ga, '//' );
			}

			$s .= self::TAB . "});" . PHP_EOL;
			$output .= $s;
		}
		return $output;
	}

	public function addCreateCommand( $o, $isTestMode = false )
	{
		return "";
	}

	public function addSetCommand( $isTestMode )
	{
		$s = '';
		$ga = "%sgtag('set', 'forceSSL', true);" . PHP_EOL;
		if ( $isTestMode ) {
			$s .= $this->addConsoleForCommands( 'set', "'forceSSL'", 'true' );
			$s .= sprintf( $ga, "//" );
		} else {
			$s .= sprintf( $ga, "" );
		}
		return $s;
	}

	public function addSendCommand( $isTestMode = false )
	{
		return "";
	}

	public function addEvent( $strFileExt, $link, $eventType, $eventParams, $isTestMode = false )
	{
		$s = $this->buildHeadOfEvents( $strFileExt, $link, $eventType );
		$gtag = self::DOUBLE_TAB . "%sgtag('event', {$eventParams['action']}, {'event_category': '{$eventParams['category']}'";
		if ( $eventParams['label'] !== null ) {
			$gtag .= ", 'event_label': {$eventParams['label']}";
		}
		if ( isset( $eventParams['non_inter'] ) ) {
			$gtag .= ", 'nonInteraction': {$eventParams['non_inter']}";
		}
		$gtag .= "});" . PHP_EOL;

		if ( ! $isTestMode ) {
			$s .= sprintf( $gtag, '' );
		} else {
			$s .= $this->buildEventConsole( $eventType, $eventParams );
			$s .= sprintf( $gtag, '//' );
		}
		if ( $eventType == 'outbound' ) {
			$s .= self::DOUBLE_TAB . "}" . PHP_EOL;
		}
		if ( $eventType == 'error_404' ) {
			$s .= self::TAB . "}" . PHP_EOL;
		} else {
			$s .= self::TAB . "});" . PHP_EOL;
		}
		return $s;
	}

	public function buildEventConsole( $eventType, $eventParams )
	{
		$console = '';
		if ( $eventType == 'custom_event' ) {
			$nonInter = $eventParams['non_inter'] == 'on' ? 'on' : 'off';
			if ( isset( $eventParams['is_default'] ) ) {
				$nonInter = '{{NON_INTER_VAL}}';
			}
			$label = "''";
			$value = "''";
			if ( ! empty( $eventParams['label'] ) ) {
				$label = ( $eventParams['label_type'] == 'var' ) ? "{$eventParams['label']}" : "'{$eventParams['label']}'";
			}
			if ( ! empty( $eventParams['value'] ) ) {
				$value = ( $eventParams['value_type'] == 'var' ) ? "{$eventParams['value']}" : "'{$eventParams['value']}'";
			}
			$category = $eventParams['category'] == '' ? 'general' : $eventParams['category'];
			$console = self::DOUBLE_TAB . "console.log('{$eventParams['hittype']}',  '{$eventParams['action']}', "
				. "{'event_category': '{$category}', 'event_label': {$label}, 'value': {$value}, 'non_interaction': '{$nonInter}'});" . PHP_EOL;
		} else {
			$console = self::DOUBLE_TAB . "console.log('event', {$eventParams['action']}, {'event_category': '{$eventParams['category']}'";
			if ( $eventParams['label'] != null ) {
				$console .= ", 'event_label': {$eventParams['label']}";
			}
			if ( $eventType == 'error_404' ) {
				$console .= ", 'non_interaction': {$eventParams['non_inter']}";
			}
			$console .= "});" . PHP_EOL;
		}
		return $console;
	}
}