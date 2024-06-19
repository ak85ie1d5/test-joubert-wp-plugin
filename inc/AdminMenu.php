<?php

namespace JoubertApi\inc;

class AdminMenu {
    /**
     * Holds the values to be used in the fields callbacks.
     *
     * @var array $options
     * @access protected
     * @since 1.0
     */
    private array $options;

    /**
     * cl_admin_menu constructor.
     *
     * @since 1.0
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page under **Settings**.
     *
     * @since 1.0
     */
    public function add_plugin_page()
    {
        add_options_page(
            'Joubert API',
            'Joubert API',
            'manage_options',
            'joubert_api',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback.
     *
     * @since 1.0
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'joubert_api_options' );
        ?>
        <div class="wrap">
            <h1><?php echo get_admin_page_title();?></h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'ja_option_group' );
                do_settings_sections( 'ja-setting-admin' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings.
     *
     * @since 1.0
     */
    public function page_init()
    {
        register_setting(
            'ja_option_group', // Option group
            'joubert_api_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            '', // Title
            '',
            'ja-setting-admin' // Page
        );


        add_settings_field(
            'api_link', // ID
            esc_attr__('Link to Joubert API', 'joubert'), // Title
            array( $this, 'api_link_callback' ), // Callback
            'ja-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'frequency_api_call', // ID
            esc_attr__('Frequency of API calls (in second)', 'joubert'), // Title
            array( $this, 'frequency_api_call_callback' ), // Callback
            'ja-setting-admin', // Page
            'setting_section_id' // Section
        );
    }


    /**
     * Sanitize each setting field as needed.
     *
     * @param array $input Contains all settings fields as array keys
     * @return array $new_input
     *
     * @since 1.0
     */
    public function sanitize(array $input)
    {
        $new_input = array();

        $new_input['frequency_api_call'] = ( isset( $input['frequency_api_call']) ? esc_html( $input['frequency_api_call']) : null );
        $new_input['api_link'] = (isset($input['api_link']) ? esc_url($input['api_link']) : null);

        return $new_input;
    }

    /**
     * Get the settings option array and print one of its values.
     *
     * @since 1.3
     */
    public function api_link_callback()
    {
        printf(
            '<input type="text" id="api_link" class="regular-text" name="joubert_api_options[api_link]" value="%s" />',
            isset( $this->options['api_link'] ) ? esc_attr( $this->options['api_link']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values.
     *
     * @since 1.0
     */
    public function frequency_api_call_callback()
    {
        printf(
            '<input type="number" id="frequency_api_call" class="regular-text" name="joubert_api_options[frequency_api_call]" value="%s" />',
            isset( $this->options['frequency_api_call'] ) ? esc_attr( $this->options['frequency_api_call']) : ''
        );
    }
}
