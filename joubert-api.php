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



defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_shortcode('joubert_api', 'joubertApiShortcode');
function joubertApiShortcode($atts) {

    if (isset($atts['metal']) && in_array($atts['metal'], ["XAU","XAG","XPT","XPD"])) {
        wp_enqueue_script( 'chart-js', plugins_url( '/assets/js/chart.js' , __FILE__ ), '', '4.4.1', true );
        wp_enqueue_script( 'joubert-api-chart', plugins_url( '/assets/js/joubert-api-chart.js' , __FILE__ ), '', '1.0.0', true );

        ob_start(); ?>
        <div>
            <canvas id="joubert-api-chart">Loading...</canvas>
        </div>

        <?php return preg_replace('~>\\s+<~m', '><', ob_get_clean());
    } else {
        return null;
    }
}
