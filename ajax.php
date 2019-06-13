<?php 
/**
 * Import pack ajax functions 
 * 
 * @package Ametex
 */

if( ! function_exists( 'ametex_import_pack_modal_import_body_template' ) ) {
    /** 
     * Modal import template 
     * 
     */
    function ametex_import_pack_modal_import_body_template() {

        set_query_var( 'package_id', $_POST['package_id'] );
        set_query_var( 'package_data', ametex_import_pack_get_package_data_by_id( $_POST['package_id'] ) );
        set_query_var( 'import_steps', ametex_import_pack_import_steps() );

        ob_start();
        load_template( IMPORT_DIR . '/templates/modal-body-by-package-id.php' );
        $content = ob_get_clean();

        wp_send_json( [
            'success' => true,
            'content' => $content,
        ] );
    }

    add_action( 'wp_ajax_ametex_import_pack_modal_import_body_template', 'ametex_import_pack_modal_import_body_template' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_modal_import_body_template', 'ametex_import_pack_modal_import_body_template' );
}

if( ! function_exists( 'ametex_import_pack_import_action_ajax_callback' ) ) {
    /**
     * Import action ajax callback 
     * 
     */
    function ametex_import_pack_import_action_ajax_callback() {
        
        extract( $_POST );

        if( ! isset( $data['form_data'] ) ) {
            wp_send_json( [
                'success' => true,
                'type' => 'error',
                'message' => __( 'Error: Form data not defined!' ),
            ] );
        }

        if( ! isset( $data['form_data'][$data['action_type']] ) ) {
            wp_send_json( [
                'success' => true,
                'type' => 'error',
                'message' => __( 'Error: Missing function ajax callback!' ),
            ] );
        }

        if( ! function_exists( $data['form_data'][$data['action_type']] ) ) {
            wp_send_json( [
                'success' => true,
                'type' => 'error',
                'message' => __( 'Error: Function ajax not defined!' ),
            ] );
        }

        $result = call_user_func( $data['form_data'][$data['action_type']] );

        wp_send_json( [
            'success' => true,
            'type' => 'success',
            'result' => $result,
        ] ); exit();
    }

    add_action( 'wp_ajax_ametex_import_pack_import_action_ajax_callback', 'ametex_import_pack_import_action_ajax_callback' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_import_action_ajax_callback', 'ametex_import_pack_import_action_ajax_callback' );
}

if( ! function_exists( 'ametex_import_pack_install_plugin' ) ) {
    /**
     * Install plugin 
     * 
     */
    function ametex_import_pack_install_plugin() {

        extract( $_POST );
        // wp_send_json( $_POST );

        $installer = false;
        $plugin = ['slug' => $data['plugin_slug']];
        if( ! empty( $data['plugin_source'] ) ) { $plugin['source'] = $data['plugin_source']; }

        if(! Import_Pack_Plugin_Installer_Helper::is_installed( $plugin )) {
            
            $install_response = Import_Pack_Plugin_Installer_Helper::install( $plugin );
            if( $install_response['success'] == true ) {
                // Install...
                $installer = true;
            } 
        } else {
            $installer = true;
        }

        if( false == $installer ) {
            wp_send_json( [
                'success' => true,
                'status' => false,
                'substep' => 'installer',
            ] );
        }

        $active_response = Import_Pack_Plugin_Installer_Helper::activate( $plugin );
        $activate = false;
        
        if( $active_response['success'] == true ) {
            $activate = true;
        }

        if( false == $activate ) {
            wp_send_json( [
                'success' => true,
                'status' => false,
                'substep' => 'activate',
            ] );
        }

        wp_send_json( [
            'success' => true,
            'status' => true,
            'substep' => 'activate',
        ] );
    }

    add_action( 'wp_ajax_ametex_import_pack_install_plugin', 'ametex_import_pack_install_plugin' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_install_plugin', 'ametex_import_pack_install_plugin' );
}

if( ! function_exists( 'ametex_import_pack_download_package' ) ) {
    /**
     * Download package 
     * 
     */
    function ametex_import_pack_download_package() {

        extract( $_POST );
        $package_name = $data['package_name'];
        $position = isset( $data['position'] ) ? $data['position'] : 0;
        $path_file_package = isset( $data['path_file_package'] ) ? $data['path_file_package'] : '';

        $result = ametex_import_pack_download_package_step( $package_name, $position, $path_file_package );

        wp_send_json( array(
            'success' => true,
            'result' => $result,
        ) );

        exit();
    }

    add_action( 'wp_ajax_ametex_import_pack_download_package', 'ametex_import_pack_download_package' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_download_package', 'ametex_import_pack_download_package' );
}

if( ! function_exists( 'ametex_import_pack_extract_package_demo' ) ) {
    /**
     * Extract (.zip) package demo 
     * 
     */
    function ametex_import_pack_extract_package_demo() {
        global $Bears_Backup;
        extract( $_POST );

        $package_name = $data['package_name'];
        $path_file_package = $data['path_file_package'];

        $backup_path = $Bears_Backup->upload_path();
        $extract_to = $backup_path . '/' . sprintf( 'package-install__%s', $package_name );

        if ( ! wp_mkdir_p( $extract_to ) ) {
            return array(
                'success' => true,
                'result' => array(
                    'extract_success' => false,
                )
            );
        }

        $zipFile = new \PhpZip\ZipFile();
        $zipFile
        ->openFile( $path_file_package )
        ->extractTo( $extract_to )
        ->close();

        // remove zip file
        wp_delete_file( $path_file_package );

        wp_send_json( array(
            'success' => true,
            'result' => array(
                'extract_success' => true,
                'extract_to' => $extract_to,
            )
        ) );

        exit();
    }

    add_action( 'wp_ajax_ametex_import_pack_extract_package_demo', 'ametex_import_pack_extract_package_demo' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_extract_package_demo', 'ametex_import_pack_extract_package_demo' );
}

if( ! function_exists( 'ametex_import_pack_restore_data' ) ) {
    /**
     * Restore data 
     * 
     */
    function ametex_import_pack_restore_data() {
        global $wp_filesystem;
        $package_path = $_POST['data']['package_path'];

        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

        do_action( 'ametex/import_pack/before_restore_package', $package_path );

        $result = BBACKUP_Restore_Data( array(
            'name' => basename( $package_path ),
            'backup_path_file' => $package_path,
        ), '' );

        do_action( 'ametex/import_pack/after_restore_package', $package_path, $result );

        // delete package folder
        $wp_filesystem->delete( $package_path , true );

        if( isset( $result['success'] ) && true == $result['success'] ) {

            wp_send_json( array(
                'success' => true,
                'result' => array(
                    'restore' => true
                )
            ) );
        } else {
            wp_send_json( array(
                'success' => true,
                'result' => array(
                    'restore' => false
                )
            ) );
        }
    }

    add_action( 'wp_ajax_ametex_import_pack_restore_data', 'ametex_import_pack_restore_data' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_restore_data', 'ametex_import_pack_restore_data' );
}

if( ! function_exists( 'ametex_import_pack_backup_site_substep_install_bears_backup_plg' ) ) {
    /**
     * Backup site step install Bears Backup plugin
     * 
     */
    function ametex_import_pack_backup_site_substep_install_bears_backup_plg() {

        // Install plugin Bears Backup
        $installer = false;
        $plugin = [
            'slug' => 'bears-backup',
            'source' => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . '/bears-backup.zip',
        ];

        if(! Import_Pack_Plugin_Installer_Helper::is_installed( $plugin )) {
            
            $install_response = Import_Pack_Plugin_Installer_Helper::install( $plugin );
            if( $install_response['success'] == true ) {
                // Install...
                $installer = true;
            } 
        } else {
            $installer = true;
        }

        if( false == $installer ) { 
            wp_send_json( [
                'success' => true,
                'result' => [
                    'status' => false,
                    'message' => __( 'Install plugin Bears Backup fail!', 'ametex' ),
                ]
            ] );

            exit();
        }

        $active_response = Import_Pack_Plugin_Installer_Helper::activate( $plugin );
        $activate = false;
        
        if( $active_response['success'] != true ) {
            wp_send_json( [
                'success' => true,
                'result' => [
                    'status' => false,
                    'message' => __( 'Active plugin Bears Backup fail!', 'ametex' ),
                ]
            ] );

            exit();
        }

        wp_send_json( [
            'success' => true,
            'result' => [
                'status' => true,
                'message' => __( 'Install plugin Bears Backup successful.', 'ametex' ),
            ]
        ] );

        exit();
    }

    add_action( 'wp_ajax_ametex_import_pack_backup_site_substep_install_bears_backup_plg', 'ametex_import_pack_backup_site_substep_install_bears_backup_plg' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_backup_site_substep_install_bears_backup_plg', 'ametex_import_pack_backup_site_substep_install_bears_backup_plg' );
}

if( ! function_exists( 'ametex_import_pack_backup_site_substep_backup_database' ) ) {
    /**
     * Backup site step backup database
     * 
     */
    function ametex_import_pack_backup_site_substep_backup_database() {

        // bbackup_backup_database
        $result = BBACKUP_Backup_Database( [], '' );

        if( $result['success'] == true ) {
            
            wp_send_json( [
                'success' => true,
                'result' => [
                    'status' => true,
                    'message' => __( 'Backup database successful.', 'ametex' ),
                    'next_step_data' => $result,
                ]
            ] );
        } else {
            wp_send_json( [
                'success' => true,
                'result' => [
                    'status' => false,
                    'message' => __( 'Backup database fail!', 'ametex' ),
                ]
            ] );
        }
    }

    add_action( 'wp_ajax_ametex_import_pack_backup_site_substep_backup_database', 'ametex_import_pack_backup_site_substep_backup_database' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_backup_site_substep_backup_database', 'ametex_import_pack_backup_site_substep_backup_database' );
}

if( ! function_exists( 'ametex_import_pack_backup_site_substep_create_file_config' ) ) {
    /**
     * Backup site step create file config
     * 
     */
    function ametex_import_pack_backup_site_substep_create_file_config() {

        $result = BBACKUP_Create_File_Config( $_POST['data']['next_step_data'], '' );

        if( $result['success'] == true ) {
            
            wp_send_json( [
                'success' => true,
                'result' => [
                    'status' => true,
                    'message' => __( 'Backup database successful.', 'ametex' ),
                    'next_step_data' => $result,
                ]
            ] );
        } else {
            wp_send_json( [
                'success' => true,
                'result' => [
                    'status' => false,
                    'message' => __( 'Backup database fail!', 'ametex' ),
                ]
            ] );
        }
    }

    add_action( 'wp_ajax_ametex_import_pack_backup_site_substep_create_file_config', 'ametex_import_pack_backup_site_substep_create_file_config' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_backup_site_substep_create_file_config', 'ametex_import_pack_backup_site_substep_create_file_config' );
}

if( ! function_exists( 'ametex_import_pack_backup_site_substep_backup_folder_upload' ) ) {
    /** 
     * Backup site step backup folder upload
     * 
     */
    function ametex_import_pack_backup_site_substep_backup_folder_upload() {

        $result = BBACKUP_Backup_Folder_Upload( $_POST['data']['next_step_data'], '' );

        if( $result['success'] == true ) {
            
            wp_send_json( [
                'success' => true,
                'result' => [
                    'status' => true,
                    'message' => __( 'Backup folder upload successful.', 'ametex' ),
                    'next_step_data' => $result,
                ]
            ] );
        } else {
            wp_send_json( [
                'success' => true,
                'result' => [
                    'status' => false,
                    'message' => __( 'Backup folder upload fail!', 'ametex' ),
                ]
            ] );
        }
    }

    add_action( 'wp_ajax_ametex_import_pack_backup_site_substep_backup_folder_upload', 'ametex_import_pack_backup_site_substep_backup_folder_upload' );
    add_action( 'wp_ajax_nopriv_ametex_import_pack_backup_site_substep_backup_folder_upload', 'ametex_import_pack_backup_site_substep_backup_folder_upload' );
}
