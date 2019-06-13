<?php 
/** 
 * Import pack demo install package template 
 * 
 * @package Ametex 
 * @author BePlus
 */

// echo '<pre>'; print_r( $package_demos ); echo '</pre>';
?>

<?php do_action( 'ametex/import_pack/demo_install_package/before' ); ?>

<div class="ip-package-demo-list">
    <?php foreach( $package_demos as $index => $package_item ) : ?>
    <div class="ip-package-demo-item">
        <div class="preview-image">
            <img src="<?php echo esc_url( IMPORT_URI . '/images/browser-header-mockup.png' ); ?>" alt="<?php _e( '#' ) ?>">
            <img src="<?php echo esc_url( $package_item['preview'] ); ?>" alt="<?php echo esc_attr( $package_item['title'] ); ?>">
        
            <div class="actions">
                <a href="<?php echo esc_url( $package_item['url_demo'] ) ?>" target="_blank" class="__action-preview-demo" title="<?php echo esc_attr( 'Preview demo', 'ametex' ); ?>">
                    <?php _e( 'Preview', 'ametex' ); ?>
                </a>
                <a href="javascript:" class="__action-import-demo" title="<?php echo esc_attr( 'Import demo', 'ametex' ); ?>" data-package-id="<?php echo esc_attr( trim( $package_item['package_name'] ) ); ?>">
                    <?php _e( 'Import', 'ametex' ); ?>
                </a>
            </div>
        </div>

        <div class="entry">
            <h4 class="title">
                <a href="<?php echo esc_url( $package_item['url_demo'] ) ?>" target="_blank">
                    <span>
                        <?php echo "{$package_item['title']}"; ?>
                    </span>
                    <svg style="width: 20px;" x="0px" y="0px" viewBox="0 0 476.213 476.213" style="enable-background:new 0 0 476.213 476.213;" xml:space="preserve"> <polygon points="345.606,107.5 324.394,128.713 418.787,223.107 0,223.107 0,253.107 418.787,253.107 324.394,347.5 345.606,368.713 476.213,238.106 "/> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>
                </a>
            </h4>
        </div>
    </div>
    <?php endforeach; ?>
</div> <!-- .ip-package-demo-list -->

<?php do_action( 'ametex/import_pack/demo_install_package/after' ); ?>