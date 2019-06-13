<?php 
/**
 * Import pack data package demo 
 * 
 * @package Ametex 
 * @author BePlus 
 */

$plugin_includes = [
    [
        'name' => esc_html__('Advanced Custom Fields', 'ametex'),
        'slug' => 'advanced-custom-fields',
    ],
    [
        'name' => esc_html__('Custom Post Type UI', 'ametex'),
        'slug' => 'custom-post-type-ui',
    ],
    [
        'name' => esc_html__('Yoast SEO', 'ametex'),
        'slug' => 'wordpress-seo',
    ],
    [
        'name' => esc_html__('Slider Revolution', 'ametex'),
        'slug' => 'revslider',
        'source' => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'revslider.zip',
    ],
    [
        'name' => esc_html__('Newsletter', 'ametex'),
        'slug' => 'newsletter',
    ],
    [
        'name' => esc_html__('Elementor', 'ametex'),
        'slug' => 'elementor',
    ],
    [
        'name' => esc_html__('Elementor Pro', 'ametex'),
        'slug' => 'elementor-pro',
        'source' => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'elementor-pro.zip',
    ],
    [
        'name' => esc_html__('Essential Addons for Elementor - Pro', 'ametex'),
        'slug' => 'essential-addons-elementor',
        'source' => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'essential-addons-elementor.zip',
    ],
    [
        'name' => esc_html__('Ele Custom Skin', 'ametex'),
        'slug' => 'ele-custom-skin',
    ],
    [
        'name' => esc_html__('Sticky Header Effects for Elementor', 'ametex'),
        'slug' => 'sticky-header-effects-for-elementor',
    ],
    [
        'name' => esc_html__('Custom Icons for Elementor', 'ametex'),
        'slug' => 'custom-icons-for-elementor',
    ],
];

return apply_filters( 'ametex/import_pack/package_demo', [
    [
        'package_name' => 'ametex-main',
        'preview' => IMPORT_URI . '/images/ametex-main-preview.jpg', // image size 680x475
        'url_demo' => 'https://bearsthemespremium.com/theme/ametex/', 
        'title' => __( 'Ametex Main', 'ametex' ),
        'description' => __( 'Ametex main demo, include 5 home demos & full inner page (Contact, About, Company, blog, etc.).' ),
        'plugins' => $plugin_includes,
    ],
] );