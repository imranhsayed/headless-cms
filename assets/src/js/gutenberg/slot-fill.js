/**
 * Slot fill for preview button.
 *
 * @package headless-cms
 */

const {registerPlugin} = wp.plugins;
const {__} = wp.i18n;

const {
	PluginSidebar,
	PluginSidebarMoreMenuItem
} = wp.editPost;

const {Fragment} = wp.element;

/**
 * PluginSidebarMoreMenuItemTest
 *
 * @return {Object} Content
 */
const PluginSidebarMoreMenuItemTest = () => {

	if ( '1' !== frontendConfig?.isPreviewLinkActive ) {
		return null;
	}


	const frontendSiteUrl = frontendConfig?.frontendSiteUrl.replace( /\/$/, '' );
	const currentPost = wp.data.select( 'core/editor' ).getCurrentPost();
	const myPostStatus = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'status' );
	const previewURL = `${frontendSiteUrl}/api/preview/?postType=${currentPost?.type ?? ''}&postId=${currentPost?.id ?? ''}`;

	const frontendPreviewBtn = (
		<a href={previewURL} target={`wp-preview-${currentPost?.id ?? ''}`}
			style={{margin: '20px', boxShadow: '0 0 0 1.5px #ccc'}}
			className="components-button editor-post-preview is-button is-default is-large">
			{__( 'Preview on frontend', 'headless-cms' )}
			<span className="screen-reader-text">
				{__( '(opens in a new tab)', 'headless-cms' )}
			</span>
		</a>
	);

	return (
		<Fragment>
			<PluginSidebarMoreMenuItem
				target="sidebar-name"
				icon="visibility"
				title="Frontend Preview">
				{__( 'Frontend Preview', 'headless-cms' )}
			</PluginSidebarMoreMenuItem>
			<PluginSidebar
				name="sidebar-name"
				icon="visibility"
				title="Frontend Preview">
				{( 'draft' === myPostStatus || 'publish' === myPostStatus ) ? (
					frontendPreviewBtn
				) : ''}

			</PluginSidebar>
		</Fragment>
	);
};

registerPlugin( 'plugin-sidebar-expanded-test', {render: PluginSidebarMoreMenuItemTest} );
