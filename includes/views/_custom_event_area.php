<table id="scand-custom-events-area">
	<tbody>
	<tr>
		<th colspan="4" scope="row" valign="bottom"
			class="scand-ga-table-label"><?php _e( 'JavaScript items', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></th>
	</tr>
	<tr class="row">
		<td>
			<span class="scand-required required"><?php _e( 'Bound event', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
			<input type="text"
				   name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][event]"
				   class="scand-input scand-disabled-field js-scand-easy-ga-event"
				   value="<?php echo Scand_Easy_GA_Toolkit_Admin::prepareInputValue( $event['event'] ); ?>"
				   required="required" <?php echo Scand_Easy_GA_Toolkit_Admin::printFieldDisabled( isset( $event['is_default'] ), true ); ?> />
		</td>
		<td>
			<span class="scand-required required"><?php _e( 'CSS selector', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
			<input type="text"
				   name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][selector]"
				   class="scand-input scand-disabled-field js-scand-easy-ga-selector"
				   value="<?php echo Scand_Easy_GA_Toolkit_Admin::prepareInputValue( $event['selector'] ); ?>"
				   required="required" <?php echo Scand_Easy_GA_Toolkit_Admin::printFieldDisabled( isset( $event['is_default'] ), true ); ?> />
		</td>
		<td>
			<span><?php _e( 'Prevent default', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
			<select class="scand-select"
					name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][prevent_default]">
				<option
						value="yes" <?php Scand_Easy_GA_Toolkit_Admin::printOptionSelected( $event['prevent_default'], 'yes' ) ?>>
					<?php _e( 'Yes', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
				</option>
				<option
						value="no" <?php Scand_Easy_GA_Toolkit_Admin::printOptionSelected( $event['prevent_default'], 'no' ) ?>>
					<?php _e( 'No', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
				</option>
			</select>
		</td>
	</tr>
	<tr>
		<th colspan="4" scope="row" valign="bottom"
			class="scand-ga-table-label scand-ga-table-label-border"><?php _e( 'GA items', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></th>
	</tr>
	<tr class="row">
		<td>
			<span><?php _e( 'Hit type', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
			<span>event</span>
			<input type="hidden"
				   name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][hittype]"
				   value="event" />
			<!--select name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][hittype]"
                    class="scand-select">
                <option value="<?php echo $event['hittype'] ?>"><?php echo $event['hittype'] ?></option>
                <option value="social" disabled="disabled">social</option>
                <option value="timing" disabled="disabled">timing</option>
            </select-->
		</td>
		<td>
            <span<?php echo $event['tracking'] == Scand_Easy_GA_Toolkit_Admin::TRACKING_GA ? ' class="scand-required required"' : '' ?>>
                <?php _e( 'Category', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
            </span>
			<br>
			<input type="text"
				   name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][category]"
				   class="scand-input scand-disabled-field"
				   value="<?php echo Scand_Easy_GA_Toolkit_Admin::prepareInputValue( $event['category'] ); ?>"
				<?php echo $event['tracking'] == Scand_Easy_GA_Toolkit_Admin::TRACKING_GA ? 'required="required"' : '' ?>
				<?php echo Scand_Easy_GA_Toolkit_Admin::printFieldDisabled( isset( $event['is_default'] ), true ); ?> />
		</td>
		<td>
			<span class="scand-required required"><?php _e( 'Action', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
			<input type="text"
				   name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][action]"
				   value="<?php echo Scand_Easy_GA_Toolkit_Admin::prepareInputValue( $event['action'] ); ?>"
				   required="required"
				<?php echo Scand_Easy_GA_Toolkit_Admin::printFieldDisabled( isset( $event['is_default'] ), true ); ?>
				   class="scand-input scand-disabled-field" />
		</td>
		<td>
			<span><?php _e( 'NI', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
			<input type="checkbox"
				   name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][non_inter]"
				   class="scand-input-optional" <?php Scand_Easy_GA_Toolkit_Admin::printCheckboxChecked( $event['non_inter'], 'on' ) ?> />
		</td>
	</tr>

	<tr class="row">
		<?php if ( isset( $event['label'] ) ) : ?>
			<td>
				<span><?php _e( 'Label', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
				<input type="text"
					   name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][label]"
					   value="<?php echo Scand_Easy_GA_Toolkit_Admin::prepareInputValue( $event['label'] ); ?>"
					   class="scand-input-optional" />
			</td>
			<td class="scand-dropdown-label">
				<span><?php _e( 'Label type', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
				<select name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][label_type]"
						class="scand-select js-scand-select-javascript-area">
					<option
							value="str" <?php Scand_Easy_GA_Toolkit_Admin::printOptionSelected( $event['label_type'], 'str' ) ?>>
						<?php _e( 'String', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
					</option>
					<option
							value="var" <?php Scand_Easy_GA_Toolkit_Admin::printOptionSelected( $event['label_type'], 'var' ) ?>>
						<?php _e( 'JavaScript instruction', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
					</option>
				</select>
			</td>
		<?php endif; ?>
		<?php if ( isset( $event['value'] ) ) : ?>
			<td>
				<span><?php _e( 'Value', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
				<input type="<?php echo $event['value_type'] == 'int' ? 'number' : 'text' ?>"
					   name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][value]"
					   value="<?php echo Scand_Easy_GA_Toolkit_Admin::prepareInputValue( $event['value'] ); ?>"
					   class="scand-input-optional scand-optional-value" />
			</td>
			<td class="scand-dropdown-value">
				<span><?php _e( 'Value type', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
				<select name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][value_type]"
						class="scand-select js-scand-select-javascript-area">
					<option
							value="int" <?php Scand_Easy_GA_Toolkit_Admin::printOptionSelected( $event['value_type'], 'int' ) ?>>
						<?php _e( 'Integer', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
					</option>
					<option
							value="var" <?php Scand_Easy_GA_Toolkit_Admin::printOptionSelected( $event['value_type'], 'var' ) ?>>
						<?php _e( 'JavaScript instruction', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
					</option>
				</select>
			</td>
		<?php endif; ?>
	</tr>
	<tr class="row area-variable<?php Scand_Easy_GA_Toolkit_Admin::printFieldHidden( ! empty( $event['javascript'] ) || ( $event['value_type'] == 'var' || $event['label_type'] == 'var' ), false ) ?>">
		<td colspan="4">
			<span><?php _e( 'Area for JavaScript', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span><br>
			<textarea
					name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[custom_event][<?php echo $key ?>][javascript]"
					class="scand-textarea"><?php echo trim( Scand_Easy_GA_Toolkit_Admin::prepareInputValue( $event['javascript'] ) ); ?></textarea>
		</td>
	</tr>
	<tr class="row4">
		<td>
			<span></span><br>
			<button type="button" class="scand-custom-event-remove button-secondary">
				<?php _e( 'Remove', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
			</button>
		</td>
	</tr>
	</tbody>
</table>

<?php include SCAND_EASY_GA_TOOLKIT_INCLUDE_DIR_PATH . 'views/_custom_event_preview.php' ?>