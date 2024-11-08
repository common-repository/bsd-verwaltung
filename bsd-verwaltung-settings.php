<?php

//Add settings to menu
add_action( 'admin_menu', 'bsd_options_add_page' );
function bsd_options_add_page() {
	add_submenu_page( 'edit.php?post_type=bsds', 'Einstellungen', 'Einstellungen', 'manage_options', basename( __FILE__ ), 'bsd_options_do_page' );

	//call register settings function
	add_action( 'admin_init', 'bsd_register_plugin_settings' );
}

function bsd_register_plugin_settings() {
	//register our settings
	register_setting( 'bsd-plugin-settings-group-mail', 'agree_on_bsd', 'bsd_mail_agree_on_bsd_validate' );
	register_setting( 'bsd-plugin-settings-group-mail', 'reject_on_bsd_by_admin', 'bsd_mail_reject_on_bsd_by_admin_validate' );
	register_setting( 'bsd-plugin-settings-group-mail', 'reject_on_bsd_by_user', 'bsd_mail_reject_on_bsd_by_user_validate' );
	register_setting( 'bsd-plugin-settings-group-style', 'color_picker_panel_header', 'bsd_color_picker_panel_header_validate' );
	register_setting( 'bsd-plugin-settings-group-style', 'color_picker_panel_header_active', 'bsd_color_picker_panel_header_active_validate' );
	register_setting( 'bsd-plugin-settings-group-misc', 'access_for_frontend_panels' );
	register_setting( 'bsd-plugin-settings-group-misc', 'bsd_enable_daily_mail_notification' );
	register_setting( 'bsd-plugin-settings-group-misc', 'show_attended_users' );

}

function bsd_options_do_page() {

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'misc_settings';

	if( isset( $_GET[ 'tab' ] ) ) {
		$active_tab = $_GET[ 'tab' ];
	} // end if

	?>
    <div class="wrap">

        <h2>Einstellungen</h2>

        <h2 class="nav-tab-wrapper">
            <a href="edit.php?post_type=bsds&page=bsd-verwaltung-settings.php&tab=misc_settings" class="nav-tab <?php echo $active_tab == 'misc_settings' ? 'nav-tab-active' : ''; ?>">Allgemein</a>
            <a href="edit.php?post_type=bsds&page=bsd-verwaltung-settings.php&tab=style_settings" class="nav-tab <?php echo $active_tab == 'style_settings' ? 'nav-tab-active' : ''; ?>">Styling</a>
            <a href="edit.php?post_type=bsds&page=bsd-verwaltung-settings.php&tab=mail_settings" class="nav-tab <?php echo $active_tab == 'mail_settings' ? 'nav-tab-active' : ''; ?>">Mail-Texte</a>
        </h2>

        <br><br>

		<?php

		switch ( $active_tab ) {

			case 'misc_settings':
				echo bsd_misc_settings();
				break;

			case 'style_settings':
				echo bsd_style_settings();
				break;

			case 'mail_settings':
				echo bsd_mail_settings();
				break;

		}

		?>

    </div>

	<?php

}

function bsd_misc_settings() {

	date_default_timezone_set( 'Europe/Berlin' );

	?>

    <div class="wrap">
        <form id="bsd-settings-form" method="post" action="options.php">

	        <?php settings_fields( 'bsd-plugin-settings-group-misc' ); ?>
	        <?php do_settings_sections( 'bsd-plugin-settings-group-misc' ); ?>

            <h2><?php _e( 'Allgemein', 'twentythirteen' ); ?></h2>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Homepage-Zugriff', 'twentythirteen' ); ?></th>
                    <td>
                        <label for="access_for_frontend_panels">
                            <input type="checkbox" name="access_for_frontend_panels" id="access_for_frontend_panels" value="1" <?php checked( 1, get_option( 'access_for_frontend_panels' ), true ); ?> />
					        <?php _e( 'Zugriff auf Dienste nur f&uuml;r angemeldete User?', 'twentythirteen' ); ?>
                        </label>
                    </td>
                </tr>
                </tbody>
            </table>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Teilnehmer anzeigen', 'twentythirteen' ); ?></th>
                    <td>
                        <label for="show_attended_users">
                            <input type="checkbox" name="show_attended_users" id="show_attended_users" value="1" <?php checked( 1, get_option( 'show_attended_users' ), true ); ?> />
					        <?php _e( 'Gesetzte User in Diensten anzeigen?', 'twentythirteen' ); ?>
                        </label>
                    </td>
                </tr>
                </tbody>
            </table>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'T&auml;gliche Benachrichtigungen', 'twentythirteen' ); ?></th>
                    <td>
                        <label for="bsd_enable_daily_mail_notification">
                            <input type="checkbox" name="bsd_enable_daily_mail_notification" id="bsd_enable_daily_mail_notification" value="1" <?php checked( 1, get_option( 'bsd_enable_daily_mail_notification' ), true ); ?> />
					        <?php
					        _e( 'T&auml;glicher Versand einer Benachrichtigungsmail an alle User, wenn neue Dienste eingetragen wurden. Beim Einschalten wird, sofern neue Dienste vorliegen, eine Mail an alle User verschickt. ', 'twentythirteen' );
					        if ( false === wp_next_scheduled( 'bsd_cron_hook' ) ) {

					        } else {
						        echo 'N&auml;chster Versand: ' . date( 'd.m.Y, H:i', wp_next_scheduled( 'bsd_cron_hook' ) ) . ' Uhr';
					        }
					        ?>
                        </label>
                    </td>
                </tr>
                </tbody>
            </table>

	        <?php settings_errors(); ?>
	        <?php submit_button(); ?>

        </form>
    </div>

    <?php
}

function bsd_style_settings() {

	?>

    <div class="wrap">
        <form id="bsd-settings-form" method="post" action="options.php">

			<?php settings_fields( 'bsd-plugin-settings-group-style' ); ?>
			<?php do_settings_sections( 'bsd-plugin-settings-group-style' ); ?>

            <h2><?php _e( 'Farbeinstellungen', 'twentythirteen' ); ?></h2>

            <p><?php _e( 'Hier kann die Farbe f&uuml;r die Panels im Frontend angepasst werden.', 'twentythirteen' ); ?></p>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Dienst Kopfzeile geschlossen', 'twentythirteen' ); ?></th>
                    <td><input type="text" id="color_picker_panel_header" name="color_picker_panel_header" value="<?php echo esc_attr( get_option( 'color_picker_panel_header' ) ); ?>" class="color-field" ></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Dienst Kopfzeile ge&ouml;ffnet', 'twentythirteen' ); ?></th>
                    <td><input type="text" id="color_picker_panel_header_active" name="color_picker_panel_header_active" value="<?php echo esc_attr( get_option( 'color_picker_panel_header_active' ) ); ?>" class="color-field" ></td>
                </tr>
                </tbody>
            </table>

	        <?php settings_errors(); ?>
	        <?php submit_button(); ?>

        </form>
    </div>

	<?php

}

function bsd_mail_settings() {

	$breaks = array( "<br />","<br>","<br/>","&lt;br /&gt;" );

	?>

    <div class="wrap">
        <form id="bsd-settings-form" method="post" action="options.php">

			<?php settings_fields( 'bsd-plugin-settings-group-mail' ); ?>
			<?php do_settings_sections( 'bsd-plugin-settings-group-mail' ); ?>

            <h2><?php _e( 'E-Mail Texte', 'twentythirteen' ); ?></h2>
            <p>
                Hier k&ouml;nnen die Texte der vom Plugin versendeten E-Mails angepasst werden. Es stehen Platzhalter zur Verf&uuml;gung, die vor dem Versenden der E-Mail durch die korrekten Inhalte ausgetauscht werden.
                <br /><br />
                Platzhalter: [user_name] [bsd_titel] [bsd_ort] [bsd_datum] [bsd_uhrzeit] [bsd_anzahl_personen] [bsd_info] [bsd_wachf&uuml;hrer]
            </p>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Zusage des Dienstes an User', 'twentythirteen' ); ?></th>
                    <td><textarea rows="5" cols="50" id="agree_on_bsd" name="agree_on_bsd" ><?php echo str_ireplace( $breaks, "\r\n",  get_option('agree_on_bsd') ); ?></textarea></td>
                </tr>
                </tbody>
            </table>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Absage des Dienstes an bereits gesetzten User durch Admin', 'twentythirteen' ); ?></th>
                    <td><textarea rows="5" cols="50" id="reject_on_bsd_by_admin" name="reject_on_bsd_by_admin" ><?php echo str_ireplace( $breaks, "\r\n",  get_option('reject_on_bsd_by_admin') ); ?></textarea></td>
                </tr>
                </tbody>
            </table>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Absage des Dienstes an Admin durch User', 'twentythirteen' ); ?></th>
                    <td><textarea rows="5" cols="50" id="reject_on_bsd_by_user" name="reject_on_bsd_by_user" ><?php echo str_ireplace( $breaks, "\r\n",  get_option('reject_on_bsd_by_user') ); ?></textarea></td>
                </tr>
                </tbody>
            </table>

			<?php settings_errors(); ?>
			<?php submit_button(); ?>

        </form>
    </div>

	<?php


}

function bsd_mail_agree_on_bsd_validate( $input ) {

	$input = trim( $input );

	$input = sanitize_textarea_field( $input );

	if ( true === empty( $input ) ) {

		add_settings_error( 'agree_on_bsd', 'agree_on_bsd', 'Das Textfeld "Zusage des Dienstes an User" darf nicht leer sein.', 'error' );

		return get_option( 'agree_on_bsd' );
	}

	return $input;
}

function bsd_mail_reject_on_bsd_by_admin_validate( $input ) {

	$input = trim( $input );

	$input = sanitize_textarea_field( $input );

	if ( true === empty( $input ) ) {
		add_settings_error( 'reject_on_bsd_by_user', 'reject_on_bsd_by_user','Das Textfeld "Absage des Dienstes an Admin durch User" darf nicht leer sein.', 'error' );

		return get_option( 'reject_on_bsd_by_user' );
	}

	return $input;
}

function bsd_mail_reject_on_bsd_by_user_validate( $input ) {
	$input = trim( $input );

	$input = sanitize_textarea_field( $input );

	if ( true === empty( $input ) ) {
		add_settings_error( 'reject_on_bsd_by_user', 'reject_on_bsd_by_user', 'Das Textfeld "Absage des Dienstes an Admin durch User" darf nicht leer sein.', 'error' );

		return get_option( 'reject_on_bsd_by_user' );
	}

	return $input;
}

function bsd_color_picker_panel_header_validate( $input ) {

	$background = trim( $input );
	$background = strip_tags( stripslashes( $background ) );

	// Check if is a valid hex color
	if( false === check_color( $input ) ) {

		// Set the error message
		add_settings_error( 'color_picker_panel_header', 'color_picker_panel_header', 'Das Feld "BSD Kopfzeile geschlossen" muss einen g&uuml;ltigen Farbwert enthalten.', 'error' );

		// Get the previous valid value
		$background = get_option( 'color_picker_panel_header' );

	}

	return $background;
}

function bsd_color_picker_panel_header_active_validate( $input ) {

	$background = trim( $input );
	$background = strip_tags( stripslashes( $background ) );

	// Check if is a valid hex color
	if( false === check_color( $input ) ) {

		// Set the error message
		add_settings_error( 'color_picker_panel_header_active', 'color_picker_panel_header_active', 'Das Feld "BSD Kopfzeile ge&ouml;ffnet" muss einen g&uuml;ltigen Farbwert enthalten.', 'error' );

		// Get the previous valid value
		$background = get_option( 'color_picker_panel_header_active' );

	}

	return $background;
}

/**
 * Function that will check if value is a valid HEX color.
 */
function check_color( $value ) {

	if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
		return true;
	}

	return false;
}