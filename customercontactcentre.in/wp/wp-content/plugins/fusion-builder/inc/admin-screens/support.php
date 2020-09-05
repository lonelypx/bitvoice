<?php
/**
 * Admin Screen markup (Support page).
 *
 * @package fusion-builder
 */

$avada_theme = wp_get_theme();
if ( $avada_theme->parent_theme ) {
	$template_dir = basename( get_template_directory() );
	$avada_theme  = wp_get_theme( $template_dir );
}
$avada_version    = $avada_theme->get( 'Version' );
$theme_fusion_url = 'https://theme-fusion.com/';
?>

<div class="wrap about-wrap fusion-builder-wrap">

	<?php Fusion_Builder_Admin::header(); ?>

	<div class="fusion-builder-important-notice">
		<p class="about-description">
			<?php
			printf( // phpcs:ignore WordPress.Security.EscapeOutput
				/* translators: link properties. */
				__( 'Avada comes with 6 months of free support for every license you purchase. Support can be <a %1$s>extended through subscriptions</a> via ThemeForest. All support for Avada is handled through our support center on our company site. To access it, you must first setup an account by <a %2$s>following these steps</a>. Below are all the resources we offer in our support center.', 'fusion-builder' ), // phpcs:ignore WordPress.Security.EscapeOutput
				'a href="https://help.market.envato.com/hc/en-us/articles/207886473-Extending-and-Renewing-Item-Support" target="_blank"',
				'href="https://theme-fusion.com/documentation/avada/getting-started/avada-theme-support/" target="_blank"'
			);
			?>
		</p>
		<p><a href="https://theme-fusion.com/documentation/avada/getting-started/avada-theme-support/" class="button button-large button-primary avada-large-button" target="_blank" rel="noopener noreferrer"><?php esc_attr_e( 'Create A Support Account', 'fusion-builder' ); ?></a></p>
	</div>

	<div class="avada-registration-steps">
		<div class="feature-section col three-col">
			<div class="col">
				<h3 class="title"><span class="dashicons dashicons-lightbulb"></span><?php esc_attr_e( 'Starter Guide', 'fusion-builder' ); ?></h3>
				<p><?php esc_attr_e( 'We understand that it can be a daunting process getting started with WordPress. In light of this, we have prepared a starter pack for you, which includes all you need to know.', 'fusion-builder' ); ?></p>
				<a href="<?php echo esc_url_raw( trailingslashit( $theme_fusion_url ) ) . 'support/starter-guide/'; ?>" class="button button-large button-primary avada-large-button" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Starter Guide', 'fusion-builder' ); ?></a>
			</div>
			<div class="col">
				<h3 class="title"><span class="dashicons dashicons-book"></span><?php esc_attr_e( 'Documentation', 'fusion-builder' ); ?></h3>
				<p><?php esc_attr_e( 'This is the place to go to reference different aspects of the Fusion Builder. Our online documentaiton is organized and provides the information to get you started.', 'fusion-builder' ); ?></p>
				<a href="<?php echo esc_url_raw( trailingslashit( $theme_fusion_url ) ) . 'support/'; ?>" class="button button-large button-primary avada-large-button" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Documentation', 'fusion-builder' ); ?></a>
			</div>
			<div class="col last-feature">
				<h3 class="title"><span class="dashicons dashicons-sos"></span><?php esc_attr_e( 'Submit A Ticket', 'fusion-builder' ); ?></h3>
				<p><?php esc_attr_e( 'We offer excellent support through our advanced ticket system. Make sure to register your purchase first to access our support services and other resources.', 'fusion-builder' ); ?></p>
				<a href="<?php echo esc_url_raw( trailingslashit( $theme_fusion_url ) ) . 'support/submit-a-ticket/'; ?>" class="button button-large button-primary avada-large-button" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Submit a ticket', 'fusion-builder' ); ?></a>
			</div>
			<div class="col">
				<h3 class="title"><span class="dashicons dashicons-format-video"></span><?php esc_attr_e( 'Video Tutorials', 'fusion-builder' ); ?></h3>
				<p><?php esc_attr_e( 'Nothing is better than watching a video to learn. We have a growing library of narrated HD video tutorials to help teach you the different aspects of using Avada.', 'fusion-builder' ); ?></p>
				<a href="<?php echo esc_url_raw( trailingslashit( $theme_fusion_url ) ) . 'documentation/fusion-builder/videos/"'; ?>" class="button button-large button-primary avada-large-button" target="_blank"><?php esc_html_e( 'Watch Videos', 'fusion-builder' ); ?></a>
			</div>
			<div class="col">
				<h3 class="title"><span class="dashicons dashicons-groups"></span><?php esc_attr_e( 'Community Forum', 'fusion-builder' ); ?></h3>
				<p><?php esc_attr_e( 'We also have a community forum for user to user interactions. Ask another Avada user! Please note that ThemeFusion does not provide product support here.', 'fusion-builder' ); ?></p>
				<a href="<?php echo esc_url_raw( trailingslashit( $theme_fusion_url ) ) . 'community/forum/'; ?>" class="button button-large button-primary avada-large-button" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Community Forum', 'fusion-builder' ); ?></a>
			</div>
			<div class="col last-feature">
				<h3 class="title"><span class="dashicons dashicons-facebook"></span><?php esc_attr_e( 'Facebook Group', 'fusion-builder' ); ?></h3>
				<p><?php esc_attr_e( 'We have an amazing Facebook Group! Share with other Avada users and help grow our community. Please note, ThemeFusion does not provide support here.', 'fusion-builder' ); ?></p>
				<a href="https://www.facebook.com/groups/AvadaUsers/" class="button button-large button-primary avada-large-button" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Facebook Group', 'fusion-builder' ); ?></a>
			</div>
		</div>
		<?php do_action( 'avada_admin_pages_support_after_list' ); ?>
	</div>
	<?php Fusion_Builder_Admin::footer(); ?>
</div>
