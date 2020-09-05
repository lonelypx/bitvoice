<?php
/**
 * WooCommerce thumbnail template (classic mode).
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.1.0
 */

global $product, $woocommerce;

$items_in_cart = [];

if ( $woocommerce->cart && $woocommerce->cart->get_cart() && is_array( $woocommerce->cart->get_cart() ) ) {
	foreach ( $woocommerce->cart->get_cart() as $cart ) {
		$items_in_cart[] = $cart['product_id'];
	}
}

$id      = get_the_ID(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
$in_cart = in_array( $id, $items_in_cart );
$size    = 'shop_catalog';

$attachment_image = '';
if ( Avada()->settings->get( 'woocommerce_disable_crossfade_effect' ) ) {
	$gallery = get_post_meta( $id, '_product_image_gallery', true );

	if ( ! empty( $gallery ) ) {
		$gallery          = explode( ',', $gallery );
		$first_image_id   = $gallery[0];
		$attachment_image = wp_get_attachment_image(
			$first_image_id,
			$size,
			false,
			[
				'class' => 'hover-image',
			]
		);
	}
}
$thumb_image = get_the_post_thumbnail( $id, $size );

if ( ! $thumb_image && wc_placeholder_img_src() ) {
	$thumb_image = wc_placeholder_img( $size );
}

$classes = 'featured-image';
if ( $attachment_image ) {
	$classes = 'crossfade-images';
}
?>
<div class="<?php echo esc_attr( $classes ); ?>">
	<?php echo $attachment_image; // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<?php echo $thumb_image; // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<?php if ( $in_cart ) : ?>
		<div class="cart-loading"><i class="fusion-icon-check-square-o"></i></div>
	<?php else : ?>
		<div class="cart-loading"><i class="fusion-icon-spinner"></i></div>
	<?php endif; ?>
</div>
