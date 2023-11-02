<?php
namespace WPPF\Helpers;

/**
 * Helper. HTML Form Field
 *
 * @since 1.0.0
 */
class HTML
{

	/**
	 * Form fields to render
	 * @param array $args 	Array of arguments to generate the form field
	 * 						Accepted arguments:
	 * 							$args['label']: 'Field label'
	 * 							$args['meta']: 'field_meta'
	 * 							$args['type']: Input type: title|button|color|date|datetime-local|
	 * 							email|hidden|month|number|password|range|tel|text|time|url|week|
	 * 							textarea|checkbox|radio|select|media|file.
	 * 							If $args['type'] is equal to 'select' or 'input'
	 * 							Default 'text'
	 * 							$args['multiple']: Array used to define the values of field type
	 * 							'radio' or 'select'.
	 * 							$args['attr']: Array of attributes
	 *
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function field( $args, $value )
	{
		$defaults = array(
			'type'	=> 'text'
		);
		$args = wp_parse_args( $args, $defaults );

		echo '<div class="wppf-field field-'. $args['type'] .'" style="margin-bottom: .5rem">';
			static::title( $args );
			static::paragraph( $args );
			static::default( $args, $value );
			static::textarea( $args, $value );
			static::checkbox( $args, $value );
			static::radio( $args, $value );
			static::select( $args, $value );
			static::nonce( $args );
		echo '</div>';
	}

	/**
	 * Title
	 * @param array $args
	 *
	 * @since 1.0.0
	 */
	public static function title( $args )
	{
		if ( 'title' != $args['type'] )
			return;
		?>
			<h3><?php echo $args['label'] ?></h3>
		<?php
	}

	/**
	 * Paragraph
	 * @param array $args
	 *
	 * @since 1.0.0
	 */
	public static function paragraph( $args )
	{
		if ( 'paragraph' != $args['type'] )
			return;
		?>
			<p><?php echo $args['label'] ?></p>
		<?php
	}

	/**
	 * Defaults
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function default( $args, $value )
	{
		// Supported input types
		$types = array(
			'button',
			'color',
			'date',
			'datetime-local',
			'email',
			'hidden',
			'month',
			'number',
			'password',
			'range',
			'tel',
			'text',
			'time',
			'url',
			'week'
		);
		if ( ! in_array( $args['type'], $types ) )
			return;

		$default = $value ?? ( ( isset( $args['default'] ) ) ? $args['default'] : null );
		$value 	= ( 'button' != $args['type'] ) ? $default : $args['label'];
		$class 	= ( 'button' != $args['type'] ) ? 'regular-text' : 'button';
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<input
			id="<?php echo $args['meta'] ?>"
			class="<?php echo $class ?>"
			type="<?php echo $args['type'] ?>"
			name="<?php echo $args['meta'] ?>"
			value="<?php echo $value ?>"
			<?php
			if ( isset( $args['attr'] ) && is_array( $args['attr'] ) ) :
				foreach ( $args['attr'] as $k => $value ) :
					echo $k .'="'. $value .'" ';
				endforeach;
			endif;
			?>
		>
	<?php
	}

	/**
	 * Textarea
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function textarea( $args, $value )
	{
		if ( 'textarea' != $args['type'] )
			return;
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<textarea
			id="<?php echo $args['meta'] ?>"
			class="regular-text mb-3"
			name="<?php echo $args['meta'] ?>"
			cols="30"
			rows="5"
			<?php
			if ( isset( $args['attr'] ) && is_array( $args['attr'] ) ) :
				foreach ( $args['attr'] as $k => $value ) :
					echo $k .'="'. $value .'" ';
				endforeach;
			endif;
			?>
		>
			<?php echo $value ?>
		</textarea>
	<?php
	}

	/**
	 * Checkbox
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function checkbox( $args, $value )
	{
		if ( 'checkbox' != $args['type'] )
			return;
		?>
			<label>
				<input
					type="checkbox"
					name="<?php echo $args['meta'] ?>"
					value="1"
					<?php checked( $value, 1 ) ?>
					<?php
					if ( isset( $args['attr'] ) && is_array( $args['attr'] ) ) :
						foreach ( $args['attr'] as $k => $value ) :
							echo $k .'="'. $value .'" ';
						endforeach;
					endif;
					?>
				>
				<?php echo $args['label'] ?>
			</label>
		<?php
	}

	/**
	 * Checkbox
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function radio( $args, $value )
	{
		if ( 'radio' != $args['type'] )
			return;

		if ( is_array( $args['multiple'] ) ) :
			foreach ( $args['multiple'] as $k => $v ) :
		?>
		<label>
			<input
				type="radio"
				name="<?php echo $args['meta'] ?>"
				value="<?php echo $k ?>"
				<?php checked( $k, $value ) ?>
				<?php
				if ( isset( $args['attr'] ) && is_array( $args['attr'] ) ) :
					foreach ( $args['attr'] as $k => $value ) :
						echo $k .'="'. $value .'" ';
					endforeach;
				endif;
				?>
			>
			<?php echo $v ?>
		</label>
		<?php
			endforeach;
		endif;
	}

	/**
	 * Select
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function select( $args, $value )
	{
		if ( 'select' != $args['type'] )
			return;

		$option = ( ! is_array( $args['multiple'] ) ) ? __( 'No data available', 'wppf' ) : __( 'Select an option', 'wppf' );
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<select
			id="<?php echo $args['meta'] ?>"
			name="<?php echo $args['meta'] ?>"
			<?php
			if ( isset( $args['attr'] ) && is_array( $args['attr'] ) ) :
				foreach ( $args['attr'] as $k => $v ) :
					echo $k .'="'. $v .'" ';
				endforeach;
			endif;
			?>
		>
			<option value=""><?php echo $option ?></option>
			<?php
			if ( is_array( $args['multiple'] ) ) :
				foreach ( $args['multiple'] as $k => $v ) :
			?>
			<option value="<?php echo $k ?>" <?php selected( $k, $value ) ?>><?php echo $v ?></option>
			<?php
				endforeach;
			endif;
			?>
		</select>
	<?php
	}

	/**
	 * Nonce
	 * @param array $args 	Array of arguments
	 *
	 * @since 1.0.0
	 */
	public static function nonce( $args )
	{
		if ( 'nonce' != $args['type'] )
			return;
	?>
		<input
			id="<?php echo $args['meta'] ?>"
			type="hidden"
			name="<?php echo $args['meta'] ?>"
			value="<?php echo $args['default'] ?>"
		>
	<?php
	}
}
