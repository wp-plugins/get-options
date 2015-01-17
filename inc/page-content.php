
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php esc_html_e('All Settings'); ?></h2>


<div style="margin-top:10px;padding:10px;font-weight:bold;" class="info">
	<?php _e("See each WP serialized option by clicking on it !", 'get-options'); ?>
</div>
<?php
global $wpdb;
$options = $wpdb->get_results( "SELECT * FROM $wpdb->options ORDER BY option_name" );
$nb_options               = count($options);
$nb_options_serialized    = 0;
$nb_options_no_serialized = 0;
foreach ( (array) $options as $option ) {
	if ( is_serialized( $option->option_value ) ) {
		$nb_options_serialized++;
	}
	else {
		$nb_options_no_serialized++;
	}
}
?>
<form name="switch_display" id="switch_display" action="">
	<div class="div_radio selected" id="div_radio_tout">
		<label for="d0"><?php _e("Display all types of data", 'get-options'); ?> (<?php echo $nb_options; ?>)</label>
	</div>
	<div class="div_radio" id="div_radio_not_serialized">
		<label for="d1"><?php _e("Display only non serialized data", 'get-options'); ?> (<?php echo $nb_options_no_serialized; ?>)</label>
	</div>
	<div class="div_radio" id="div_radio_serialized">
		<label for="d2"><?php _e("Display only serialized data", 'get-options'); ?> (<?php echo $nb_options_serialized; ?>)</label>
	</div>
</form>


	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('.show_data_serialized').click(function(){
				jQuery(this).siblings('pre').slideToggle();
			});
			
			jQuery('.div_radio').click(function(){
				jQuery('.div_radio').removeClass('selected');
				jQuery(this).addClass('selected');
				
				if (jQuery(this).attr('id') == 'div_radio_tout') {
					// console.log(jQuery(this).attr('id'));
					jQuery('.td_not_serialized').show();
					jQuery('.td_serialized').show();
				}
				else if (jQuery(this).attr('id') == 'div_radio_not_serialized') {
					// console.log(jQuery(this).attr('id'));
					jQuery('.td_not_serialized').show();
					jQuery('.td_serialized').hide();					
				}
				else if (jQuery(this).attr('id') == 'div_radio_serialized') {
					// console.log(jQuery(this).attr('id'));
					jQuery('.td_not_serialized').hide();
					jQuery('.td_serialized').show();
				} 
			});
		});
	</script>
	<style type="text/css">
		.show_data_serialized {
			cursor:pointer;
			background:LightSkyBlue;
			padding:2px 0;
			width: 25em;
			display:inline-block;
			/* border:1px #555 solid; */
			/* -webkit-border-radius: 3px;
			-moz-border-radius: 3px;
            border-radius: 3px; */
            -moz-box-shadow: 0px 0px 12px 1px #c0c0c0;
            -webkit-box-shadow: 0px 0px 12px 1px #c0c0c0;
            -o-box-shadow: 0px 0px 12px 1px #c0c0c0;
            box-shadow: 0px 0px 12px 1px #c0c0c0;
            filter:progid:DXImageTransform.Microsoft.Shadow(color=#c0c0c0, Direction=NaN, Strength=12);
}		
		/*=======================
		 form de choix de données à voir
		=======================*/
		form#switch_display {
			padding:12px 10px;
			height:32px;
		}
		form#switch_display input {
			margin:0 10px 0 3px;
		}
		form#switch_display .div_radio {
			float:left;
			margin-right:14px;
			padding:9px 32px 9px 9px;
			background-color:#eee;
         box-shadow: 1px 1px 8px #aaa;
		}
			form#switch_display .div_radio.selected {
				background:url(<?php echo plugins_url('get-options/img/check.png'); ?>) no-repeat right;
				background-color:#bbb;
			}
	</style>
	
<form name="form" action="options.php" method="post" id="all-options">
	<?php wp_nonce_field('options-options') ?>
	<input type="hidden" name="action" value="update" />
	<input type='hidden' name='option_page' value='options' />


	<table class="form-table">

		<?php
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
			
			
			if ($data_serialize == true) {
				echo '<tr class="td_serialized">';
			}
			else {
				echo '<tr class="td_not_serialized">';
			}
				echo "<th scope='row'><label for='$name'>" . esc_html( $option->option_name ) . "</label></th>";
				echo '<td>';
					if ($data_serialize == true) {						
						echo "<span class='regular-text $class show_data_serialized' id='$name' title='".__('Click to view this data !', 'get-options')."'>".$value."</span>";
						echo '<pre name="'.$name.'" id="'.$name.'" style="display:none;">';
						print_r($my_value);
						echo '</pre>';
					}
					elseif ( strpos( $value, "\n" ) !== false ) {
						echo "<textarea class='$class' name='$name' id='$name' cols='30' rows='5'>" . esc_textarea( $value ) . "</textarea>";
					}
					else {
						echo "<input class='regular-text $class' type='text' name='$name' id='$name' value='".esc_attr($value)."'".disabled($disabled, true, false)." />";
					}			
				echo "</td>";
			echo "</tr>";
			
		endforeach;
		?>
	</table>


	<input type="hidden" name="page_options" value="<?php echo esc_attr( implode( ',', $options_to_update ) ); ?>" />

	<?php submit_button( __( 'Save Changes' ), 'primary', 'Update' ); ?>

</form>
</div>

