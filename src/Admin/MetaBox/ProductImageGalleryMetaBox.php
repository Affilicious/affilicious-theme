<?php
namespace ProjektAffiliateTheme\Admin\MetaBox;

if(!defined('ABSPATH')) exit('Not allowed to access pages directly.');

/**
 * This meta box is responsible for displaying the product image gallery
 * Inspired by the Woocommerce aquivalent:
 * https://github.com/woothemes/woocommerce/blob/master/includes/admin/meta-boxes/class-wc-meta-box-product-images.php
 *
 */
class ProductImageGalleryMetaBox implements MetaBoxInterface
{
    /**
     * The stored meta key in the database
     */
    const META_KEY = 'product_image_gallery';

    /**
     * @inheritdoc
     */
    public static function render(\WP_Post $post, $args)
    {
        $product_image_gallery = get_post_meta($post->ID, '_' . self::META_KEY, true);
        $attachment_ids = array_filter(explode(',', $product_image_gallery));

        ?>
        <div id="product_images_container">
            <ul class="product_images">
                <?php
                if (!empty($attachment_ids)) {
                    $update_meta = false;
                    $updated_attachment_ids = array();

                    foreach ($attachment_ids as $attachment_id) {
                        $attachment = wp_get_attachment_image($attachment_id, 'thumbnail');

                        if (empty($attachment)) {
                            $update_meta = true;
                            continue;
                        }

                        ?>
                        <li class="image" data-attachment_id="<?php echo esc_attr($attachment_id); ?>">
                            <?php echo $attachment; ?>
                            <ul class="actions">
                                <li><a href="#" class="delete tips"
                                       data-tip="<?php esc_attr__('Delete image', 'projektaffiliatetheme'); ?>">
                                        <?php __('Delete', 'projektaffiliatetheme'); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php

                        $updated_attachment_ids[] = $attachment_id;
                    }

                    // Update new image gallery IDs
                    if ($update_meta) {
                        update_post_meta( $post->ID, '_' . self::META_KEY, implode(',', $updated_attachment_ids));
                    }
                }
                ?>
            </ul>
            <input type="hidden" id="<?php echo self::META_KEY; ?>"
                   name="<?php echo self::META_KEY; ?>"
                   value="<?php echo esc_attr($product_image_gallery); ?>" />
        </div>
        <p class="add_product_images hide-if-no-js">
            <a href="#"
               data-choose="<?php esc_attr_e('Add images to product image gallery', 'projektaffiliatetheme'); ?>"
               data-update="<?php esc_attr_e('Add image', 'projektaffiliatetheme'); ?>"
               data-delete="<?php esc_attr_e('Delete image', 'projektaffiliatetheme'); ?>"
               data-text="<?php esc_attr_e('Delete image', 'projektaffiliatetheme'); ?>">
                <?php _e('Add images to product image gallery', 'projektaffiliatetheme'); ?>
            </a>
        </p>
        <?php
    }

    /**
     * @inheritdoc
     */
    public static function update($post_id, \WP_Post $post)
    {
        $attachment_ids = array();
        if (isset($_POST[self::META_KEY])) {
            $attachment_ids = array_filter(explode(',', $_POST[self::META_KEY]));
        }

        update_post_meta($post_id, '_' . self::META_KEY, implode(',', $attachment_ids));
    }
}
