<?php
/**
 * The toolbar template file.
 *
 * @since 2.0
 * @package fusion-library
 */

?>
<script type="text/template" id="fusion-app-front-end-toolbar">
	<div class="fusion-builder-live-toolbar fusion-top-frame">
		<ul class="fusion-toolbar-nav">
			<li class="fusion-branding has-submenu">
				<div class="fusion-builder-exit-wrapper has-tooltip trigger-submenu-toggling" aria-label="<?php esc_attr_e( 'Exit', 'fusion-builder' ); ?>" aria-label="<?php esc_attr_e( 'Exit Builder', 'fusion-builder' ); ?>">
					<div class="fusion-builder-exit">
						<span class="fusiona-arrow-left"></span>
					</div>
					<div class="fusion-builder-logo-wrapper">
						<img src="{{{ fusionAppConfig.fusion_library_url }}}/assets/images/fusion-builder-logo-trans.png">
					</div>
				</div>

				<ul class="fusion-exit-builder-list submenu-trigger-target" aria-expanded="false">
					<li class="exit-to-front-end">
						<a href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Exit to page front-end', 'fusion-builder' ); ?></a>
					</li>
					<li class="exit-to-back-end">
						<a href="<?php echo esc_url( admin_url( 'post.php' ) ); ?>"><?php esc_html_e( 'Exit to page back-end', 'fusion-builder' ); ?></a>
					</li>
					<li class="exit-to-dashboard">
						<a href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Exit to dashboard', 'fusion-builder' ); ?></a>
					</li>
				</ul>
			</li>

			<li class="admin-tools">
				<ul class="global-options">
					<li>
						<a class="has-tooltip open-panel<# if ( sidebarOpen ) { #> active<# } #>" id="fusion-frontend-builder-toggle-global-panel" data-context="global-settings" href="#" aria-label="<?php esc_attr_e( 'Toggle Sidebar', 'Avada' ); ?>"><i class="fusiona-sidebar-icon"></i></a>
					</li>
				</ul>
			</li>

			<li class="save-wrapper fb">
				<ul>
					<#
						// TODO: Questionable whether this should be here or in FB only.
						if ( true === FusionApp.data.is_singular && 'undefined' !== typeof postStatus ) {
					#>
					<li class="post-status">
						<div class="options">
							<span class="option">
								<# var checked = ( 'draft' !== postStatus ) ? 'checked' : ''; #>
								<input id="fusion-post-status-publish" type="radio" name="post-status" value="publish" {{ checked }}>
								<label class="has-tooltip" for="fusion-post-status-publish" aria-label="<?php esc_html_e( 'Publish', 'fusion-builder' ); ?>">
									<i class="fusiona-published"></i>
									<span class="screen-reader-text"><?php esc_html_e( 'Publish', 'fusion-builder' ); ?></span>
								</label>
							</span>
							<span class="option">
								<# var checked = ( 'draft' === postStatus ) ? 'checked' : ''; #>
								<input id="fusion-post-status-draft" type="radio" name="post-status" value="draft" {{ checked }}>
								<label class="has-tooltip" for="fusion-post-status-draft" aria-label="<?php esc_html_e( 'Draft', 'fusion-builder' ); ?>">
									<i class="fusiona-draft"></i>
									<span class="screen-reader-text"><?php esc_html_e( 'Draft', 'fusion-builder' ); ?></span>
								</label>
							</span>
						</div>
					</li>
					<# } #>
					<li>

						<# if ( postChanged ) {
							var disabledButton = 'false';
						} else {
							var disabledButton = 'true';
						}

						#>
						<a href="#" class="fusion-builder-save-page" data-disabled="{{{ disabledButton }}}">
							<span class="save-label"><?php esc_html_e( 'Save', 'fusion-builder' ); ?></span>
							<span class="success-icon"><i class="fusiona-check"></i></span>
							<span class="failed-icon"><i class="fusiona-exclamation-triangle"></i></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="additional-tools">
				<ul>
					<li class="toolbar-toggle">
						<a href="#" aria-label="<?php esc_html_e( 'Toggle Toolbar', 'Avada' ); ?>">
							<span class="screen-reader-text"><?php esc_html_e( 'Toggle Toolbar', 'Avada' ); ?></span>
							<span class="up">
								<i class="fusiona-arrow-up-alt"></i>
							</span>
							<span class="down">
								<i class="fusiona-arrow-down-alt"></i>
							</span>
						</a>
					</li>
					<li class="support has-submenu">
						<a href="#" class="fusion-builder-support trigger-submenu-toggling has-tooltip" aria-label="<?php esc_attr_e( 'Support', 'fusion-builder' ); ?>">
							<i class="fusiona-question-circle"></i>
						</a>
						<ul class="submenu-trigger-target" aria-expanded="false">
							<li>
								<a href="https://theme-fusion.com/support/starter-guide/" target="_blank">
									<span class="icon-big"><i class="fusiona-play-circle"></i></span>
									<span class="label"><?php esc_html_e( 'Get Started', 'Avada' ); ?></span>
								</a>
							</li>
							<li>
								<a href="https://theme-fusion.com/support/" target="_blank">
									<span class="icon-big"><i class="fusiona-file-alt-solid"></i></span>
									<span class="label"><?php esc_html_e( 'Help Center', 'Avada' ); ?></span>
								</a>
							</li>
							<li>
								<a href="#" class="fusion-builder-keyboard-shortcuts">
									<span class="icon-big"><i class="fusiona-keyboard"></i></span>
									<span class="label"><?php esc_html_e( 'Shortcuts', 'Avada' ); ?></span>
								</a>
							</li>
						</ul>
					</li>
					<li class="fusion-builder-preview-viewport has-submenu">
						<a class="viewport-indicator trigger-submenu-toggling has-tooltip" aria-label="<?php esc_attr_e( 'Responsive', 'Avada' ); ?>">
							<span class="active" data-indicate-viewport="desktop"><i class="fusiona-desktop"></i></span>
							<span class="portrait" data-indicate-viewport="tablet-portrait"><i class="fusiona-tablet"></i></span>
							<span class="landscape" data-indicate-viewport="tablet-landscape"><i class="fusiona-tablet"></i></span>
							<span class="portrait" data-indicate-viewport="mobile-portrait"><i class="fusiona-mobile"></i></span>
							<span class="landscape" data-indicate-viewport="mobile-landscape"><i class="fusiona-mobile"></i></span>
						</a>
						<ul class="submenu-trigger-target" aria-expanded="false">
							<li>
								<a href="#" class="toggle-viewport fusion-builder-preview-desktop" data-viewport="desktop" aria-label=<?php esc_attr_e( 'Preview Desktop', 'Avada' ); ?>><i class="fusiona-desktop"></i></a>
							</li>
							<li>
								<a href="#" class="toggle-viewport fusion-builder-preview-tablet portrait" data-viewport="tablet-portrait" aria-label=<?php esc_attr_e( 'Preview Tablet - Portrait Mode', 'Avada' ); ?>><i class="fusiona-tablet"></i></a>
							</li>
							<li>
								<a href="#" class="toggle-viewport fusion-builder-preview-tablet landscape" data-viewport="tablet-landscape" aria-label=<?php esc_attr_e( 'Preview Tablet - Landscape Mode', 'Avada' ); ?>><i class="fusiona-tablet"></i></a>
							</li>
							<li>
								<a href="#" class="toggle-viewport fusion-builder-preview-mobile portrait" data-viewport="mobile-portrait" aria-label=<?php esc_attr_e( 'Preview Mobile - Portrait Mode', 'Avada' ); ?>><i class="fusiona-mobile"></i></a>
							</li>
							<li>
								<a href="#" class="toggle-viewport fusion-builder-preview-mobile landscape" data-viewport="mobile-landscape" aria-label=<?php esc_attr_e( 'Preview Mobile - Landscape Mode', 'Avada' ); ?>><i class="fusiona-mobile"></i></a>
							</li>
						</ul>
					</li>

					<li class="preview">
						<a href="#" class="has-tooltip" aria-label="<?php esc_attr_e( 'Preview', 'Avada' ); ?>">
							<span class="on"><i class="fusiona-eye"></i></span>
							<span class="off"><i class="fusiona-eye-slash"></i></span>
						</a>
					</li>
				</ul>
			</li>
			<# if ( switcher ) {
				activeId    = 'undefined' !== typeof active ? active : false;
				activeData  = switcher[ activeId ];
				activeLabel = '<?php esc_html_e( 'Select Language', 'fusion-builder' ); ?>';

				if ( activeId ) {
					activeLabel = FusionApp.toolbarView.getLanguageLabel( activeData, activeId );
				}
				#>
				<li class="fusion-language-switcher has-submenu">
					<a href="#" class="trigger-submenu-toggling" data-language="{{ activeId }}">{{{ activeLabel }}}</a>
					<ul class="fusion-language-switcher-dropdown submenu-trigger-target" aria-expanded="false">
						<# _.each( switcher, function( language, languageCode ) {
							if ( languageCode !== activeId ) {
								languageLabel = FusionApp.toolbarView.getLanguageLabel( language, languageCode );
								languageLink  = FusionApp.toolbarView.getLanguageLink( language, languageCode ); #>
								<li data-language="{{ languageCode }}" data-link="{{ languageLink }}">{{{ languageLabel }}}</li>
							<# }
						} ); #>
					</ul>
				</li>
			<# } #>
		</ul>
	</div>
	<div id="fusion-builder-confirmation-modal-dark-overlay"></div>
	<div id="fusion-builder-confirmation-modal" style="display:none;">
		<div class="inner">
			<span class="icon"></i></span>
			<h3 class="title"></h3>
			<span class="content"></span>
		</div>
		<div class="actions"></div>
	</div>
</script>
