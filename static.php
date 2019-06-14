<?php 
/**
 * Import static func 
 * 
 * @package Import Pack
 */

if( ! function_exists( 'beplus_import_pack_scripts' ) ) {
    /** 
     * Import pack load scripts 
     * 
     */
    function beplus_import_pack_scripts() {

        wp_enqueue_style( 'import-pack-css', IMPORT_URI . '/dist/import-pack.css', false, IMPORT_VER );
        wp_enqueue_script( 'import-pack-js', IMPORT_URI . '/dist/import-pack.js', ['jquery'], IMPORT_VER, true );

        wp_localize_script( 'import-pack-js', 'import_pack_php_data', apply_filters( 'beplus/import_pack/localize_script_data', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        ] ) );
    }

    add_action( 'admin_enqueue_scripts', 'beplus_import_pack_scripts' );
}
