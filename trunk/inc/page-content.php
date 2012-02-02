
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php esc_html_e('All Settings'); ?></h2>
<form name="form" action="options.php" method="post" id="all-options">
<?php wp_nonce_field('options-options') ?>
<input type="hidden" name="action" value="update" />
<input type='hidden' name='option_page' value='options' />

<div style="margin-top:20px;margin-bottom:12px; padding:10px;font-weight:bold;" class="info">
	<?php _e("See each WP serialized option by clicking on it !", 'get-options'); ?>
</div>


<table class="form-table">

	<?php
	global $wpdb;
	$options = $wpdb->get_results( "SELECT * FROM $wpdb->options ORDER BY option_name" );
	foreach ( (array) $options as $option ) :
		$disabled = false;
		$data_serialize = false;
		if ( $option->option_name == '' )
			continue;
		if ( is_serialized( $option->option_value ) ) {
			if ( is_serialized_string( $option->option_value ) ) {
				// this is a serialized string, so we should display it
				$value = maybe_unserialize( $option->option_value );
				$options_to_update[] = $option->option_name;
				$class = 'all-options';
			} else {
				$value = '&nbsp;SERIALIZED DATA'; // $value = $option->option_value;
				$my_value = unserialize($option->option_value); // pre($value);
				$disabled = true;
				$data_serialize = true;
				$class = 'all-options disabled';
			}
		}
		else {
			$value = $option->option_value;
			$options_to_update[] = $option->option_name;
			$class = 'all-options';
		}
		$name = esc_attr( $option->option_name );
		echo "
	<tr>
		<th scope='row'><label for='$name'>" . esc_html( $option->option_name ) . "</label></th>
	<td>";
		
		if ($data_serialize == true) {
			echo "<span class='regular-text $class show_data_serialized' id='$name' title='".__('Click to view this data !', 'get-options')."'>".$value."</span>";
			echo '<pre name="'.$name.'" id="'.$name.'" style="display:none;">';
			print_r($my_value);
			echo '</pre>';
		}
		elseif ( strpos( $value, "\n" ) !== false )
			echo "<textarea class='$class' name='$name' id='$name' cols='30' rows='5'>" . esc_textarea( $value ) . "</textarea>";
		else
			echo "<input class='regular-text $class' type='text' name='$name' id='$name' value='".esc_attr($value)."'".disabled($disabled, true, false)." />";
		echo "</td>
	</tr>";
	endforeach;
	?>
</table>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.show_data_serialized').click(function(){
			// alert('DONNEE SERIALISEE');
			jQuery(this).siblings('pre').slideToggle();
		});
	});
</script>
<style type="text/css">
	.show_data_serialized {
		cursor:pointer;
		background:gold;
		padding:2px 0;
		width: 25em;
		display:inline-block;
		border-color: #DFDFDF;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
	}
</style>

<input type="hidden" name="page_options" value="<?php echo esc_attr( implode( ',', $options_to_update ) ); ?>" />

<?php submit_button( __( 'Save Changes' ), 'primary', 'Update' ); ?>

  </form>
</div>

