<?php
/**
 * Markup for the EventBrite API plugin settings page.
 *
 * @package BGW\EventBrite
 */

?>

<form method="POST" action="options.php">
	<?php settings_fields( 'eventbrite-settings' ); ?>
	<?php do_settings_sections( 'eventbrite-settings' ); ?>
	<?php submit_button(); ?>
</form>
