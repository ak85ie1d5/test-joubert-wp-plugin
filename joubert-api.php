<?php
/**
 * Plugin Name:       Joubert API
 * Plugin URI:
 * Description:       Plugin to get data from Joubert API and display it in a chart.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0.2
 * Author:            Jeremy SPAETH
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


use JoubertApi\inc\AdminMenu;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_shortcode('joubert_api', 'joubertApiShortcode');
function joubertApiShortcode($atts) {

    if (isset($atts['metal']) && in_array($atts['metal'], ["XAU","XAG","XPT","XPD"])) {
        wp_enqueue_style( 'joubert-api', plugins_url( '/assets/css/joubert-api.css' , __FILE__ ), '', '1.0.0' );
        wp_enqueue_script( 'chart-js', plugins_url( '/assets/js/chart.js' , __FILE__ ), '', '4.4.1', true );
        wp_enqueue_script( 'joubert-api-chart', plugins_url( '/assets/js/joubert-api-chart.js' , __FILE__ ), '', '1.0.0', true );
        wp_localize_script( 'joubert-api-chart', 'joubertApi',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'metal' => $atts['metal'],
                'api_link' => get_option( 'joubert_api_options' )['api_link'],
                'frequency_api_call' => get_option( 'joubert_api_options' )['frequency_api_call']
            )
        );
        ob_start(); ?>
        <div class="joubert-api">
            <div id="joubert-api-rtl" class="joubert-api-rtl">
                <span id="joubert-api-name" class="joubert-api-name"></span>
                <span id="joubert-api-price" class="joubert-api-price"></span>
                <span id="joubert-api-change-percent" class="joubert-api-change-percent"></span>
            </div>
            <canvas id="joubert-api-chart" class="joubert-api-chart">Loading...</canvas>
            <fieldset>
                <legend>Period</legend>
                <label>
                    <span>Daily</span>
                    <input type="radio" name="period" value="daily" class="jouber-api-period" checked />
                </label>
                <label>
                    <span>Weekly</span>
                    <input type="radio" name="period" class="jouber-api-period" value="weekly" />
                </label>
                <label>
                    <span>Monthly</span>
                    <input type="radio" name="period" class="jouber-api-period" value="monthly" /">
                </label>
                <label>
                    <span>Yearly</span>
                    <input type="radio" name="period" class="jouber-api-period" value="yearly" />
                </label>
            </fieldset>
        </div>

        <?php return preg_replace('~>\\s+<~m', '><', ob_get_clean());
    } else {
        return null;
    }
}

if (is_admin()) {
    require_once 'inc/AdminMenu.php';
    new AdminMenu();
}
