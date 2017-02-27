<?php
namespace BGW\EventBrite;

/**
 * Class Settings
 *
 * @package BGW\EventBrite
 */
class Settings {
	/**
	 * Settings fields.
	 *
	 * @var array
	 */
	private $fields = [];

	/**
	 * Settings data.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		$this->fields = [
			'eventbrite-user-id'        => __( 'User ID', 'bgw' ),
			'eventbrite-api-key'        => __( 'API Key', 'bgw' ),
			'eventbrite-api-secret'     => __( 'API Secret', 'bgw' ),
			'eventbrite-api-token'      => __( 'API Token', 'bgw' ),
			'eventbrite-api-anon-token' => __( 'API Anonymous Token', 'bgw' ),
		];

		$this->data = get_option( 'eventbrite-settings' );
	}

	/**
	 * Register WordPress hooks.
	 */
	public function hooks() {
		add_action( 'admin_menu', [ $this, 'create_admin_page' ] );
		add_action( 'admin_init', [ $this, 'add_section' ] );
		add_action( 'admin_init', [ $this, 'add_fields' ] );
		add_action( 'admin_init', [ $this, 'register' ] );
	}

	/**
	 * Set up the EventBrite settings page.
	 */
	public function create_admin_page() {
		add_options_page(
			__( 'EventBrite API', 'bgw' ),
			__( 'EventBrite API', 'bgw' ),
			'manage_options',
			'eventbrite-api',
			[ $this, 'admin_callback' ]
		);
	}

	/**
	 * Create the settings section.
	 */
	public function add_section() {
		add_settings_section(
			'eventbrite-settings',
			'EventBrite API Settings',
			null,
			'eventbrite-settings'
		);
	}

	/**
	 * Add input fields to the settings section.
	 */
	public function add_fields() {
		foreach ( $this->fields as $id => $name ) {
			add_settings_field(
				$id,
				$name,
				[ $this, 'render_text_input' ],
				'eventbrite-settings',
				'eventbrite-settings',
				[
					'id' => $id,
				]
			);
		}
	}

	/**
	 * Register form settings.
	 */
	public function register() {
		register_setting( 'eventbrite-settings', 'eventbrite-settings', [] );
	}

	/**
	 * Output form fields.
	 *
	 * @param array $args Input field arguments.
	 */
	public function render_text_input( $args ) {
		echo '<input type="text" id="' . esc_attr( $args['id'] )
		     . '" name="eventbrite-settings[' . esc_attr( $args['id'] ) . ']" value="'
		     . esc_attr( $this->data[ $args['id'] ] ) . '" />';
	}

	/**
	 * Get the template view file.
	 */
	public function admin_callback() {
		include dirname( plugin_dir_path( __FILE__ ) ) . '/views/settings.php';
	}

	/**
	 * Getter for Settings data.
	 *
	 * @return array
	 */
	public function get_data() {
		return $this->data;
	}
}
