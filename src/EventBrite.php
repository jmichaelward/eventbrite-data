<?php
namespace BGW\EventBrite;

/**
 * Class EventBrite
 *
 * @package BGW\EventBrite
 */
class EventBrite {
	/**
	 * EventBrite settings.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * EventBrite user id.
	 *
	 * @var string (Numeric)
	 */
	private $user_id;

	/**
	 * EventBrite API key.
	 *
	 * @var string
	 */
	private $key;

	/**
	 * EventBrite API secret.
	 *
	 * @var string
	 */
	private $secret;

	/**
	 * EventBrite API token.
	 *
	 * @var string
	 */
	private $token;

	/**
	 * EventBrite anonymous token.
	 *
	 * @var string
	 */
	private $anon_token;

	/**
	 * EventBrite constructor.
	 */
	public function __construct() {
		require_once plugin_dir_path( __FILE__ ) . 'Settings.php';

		$this->settings = new Settings();
		$this->hydrate();
	}

	/**
	 * Hydrate the object with data.
	 */
	private function hydrate() {
		$data             = $this->settings->get_data();
		$this->user_id    = $data['eventbrite-user-id'];
		$this->key        = $data['eventbrite-api-key'];
		$this->secret     = $data['eventbrite-api-secret'];
		$this->token      = $data['eventbrite-api-token'];
		$this->anon_token = $data['eventbrite-api-anon-token'];
	}

	/**
	 * Setup WordPress hooks.
	 */
	public function run() {
		$this->settings->hooks();
	}

	/**
	 * Event request.
	 */
	public function get_events() {
		$data = get_transient( 'eventbrite_events' );

		if ( ! $data ) {
			$request = wp_remote_get( "https://www.eventbriteapi.com/v3/users/$this->user_id/owned_events/?status=live&token=$this->token" );
			$data    = json_decode( $request['body'] );

			set_transient( 'eventbrite_events', $data, 1 * MINUTE_IN_SECONDS );
		}

		return $data;
	}

	/**
	 * @return array
	 */
	public function get_next_event() {
		$data = get_transient( 'eventbrite_next_data' );

		if ( ! $data ) {
			$event_list = $this->get_events();
			$next       = array_shift( $event_list->events );

			$request   = wp_remote_get( 'https://www.eventbriteapi.com/v3/events/' . $next->id . '/attendees/?token=' . $this->token );
			$attendees = $this->parse_attendees( json_decode( $request['body'], true ) );
			$data      = array_merge( [ $next ], $attendees );

			set_transient( 'eventbrite_next_event', $data, 1 * MINUTE_IN_SECONDS );
		}

		return $data;
	}

	/**
	 * Parse the list of attendees.
	 *
	 * @param array $data Data from event request.
	 *
	 * @return array
	 */
	private function parse_attendees( $data ) {
		$list = [
			'attendees' => [],
		];

		foreach ( $data['attendees'] as $attendee ) {
			$list['attendees'][] = $attendee['profile']['name'];
		}

		return $list;
	}
}
