<?php
namespace BGW\EventBrite;

/**
 * Class EventBrite
 * @package BGW\EventBrite
 */
class EventBrite {

    /**
     *
     */
    public function run() {
        $this->hooks();
    }

    /**
     *
     */
    private function hooks() {
        add_action( 'init', [ $this, 'get_data' ] );
    }

    public function get_data() {
        $url =
        $curl = curl_init();
    }
}
