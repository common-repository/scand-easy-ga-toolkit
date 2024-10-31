<div id="scand-preview-area-<?php echo $key ?>" class="scand-preview-area">
	<textarea name="" id="" cols="30" rows="12" readonly>
		<?php
		$isTestMode = $event['mode'];
		if ( $event["is_default"] ) {
			$previewData = Scand_Easy_GA_Toolkit_Admin::getDefaultPreviewData();
		} else {
			$previewData = array(
				$key => $event,
			);
		}
		$strategy = $event['tracking'] == '0' ? new Scand_Easy_GA_Toolkit_Analytics() : new Scand_Easy_GA_Toolkit_Gtag();
		echo $strategy->buildFunctionString( $previewData, $isTestMode );
		?>
	</textarea>
</div>