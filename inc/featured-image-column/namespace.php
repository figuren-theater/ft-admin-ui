<?php
/**
 * Featured Images in an Admin Column and in Quick Edit
 *
 * Inspiration:
 *
 * @see https://rudrastyh.com/wordpress/quick-edit-featured-image.html
 *
 * Figuren_Theater Admin_UI Featured_Image_Column.
 *
 * @package figuren-theater/ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI\Featured_Image_Column;

use function add_action;
use function add_filter;
use function did_action;
use function esc_url;
use function get_post_thumbnail_id;
use function get_post_types_by_support;
use function get_post_type_labels;
use function get_post_type_object;
use function has_post_thumbnail;
use function is_admin;
use function is_network_admin;
use function is_user_admin;
use function wp_enqueue_media;
use function wp_get_attachment_image_url;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() {

	// Hook onto 'admin_init' to make sure AJAX is available.
	add_action( 'admin_init', __NAMESPACE__ . '\\load' );
}
/**
 * Load all modifications.
 *
 * @return void
 */
function load() :void {
	global $pagenow;

	if ( ! is_admin() || is_network_admin() || is_user_admin() ) {
		return;
	}

	// only load on admin list views
	if ( 'edit.php' !== $pagenow ) {
		return;
	}

	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\include_wp_enqueue_media' );

	add_action( 'quick_edit_custom_box', __NAMESPACE__ . '\\featured_image_quick_edit', 10, 2 );

	array_map(
		function ( $post_type ) {
			// This action hook allows to add a new empty column
			add_filter( "manage_{$post_type}_posts_columns", __NAMESPACE__ . '\\featured_image_column', 20, 2 );
			// This hook fills our column with data
			add_action( "manage_{$post_type}_posts_custom_column", __NAMESPACE__ . '\\render_the_column', 10, 2 );
		},
		get_post_types_by_support( 'thumbnail' )
	);

}

/**
 * Filters the columns displayed in the Posts list table for a specific post type.
 *
 * The dynamic portion of the hook name, `$post_type`, refers to the post type slug.
 *
 * Possible hook names include:
 *
 *  - `manage_post_posts_columns`
 *  - `manage_page_posts_columns`
 *
 * @since 3.0.0
 *
 * @param string[] $post_columns An associative array of column headings.
 */
function featured_image_column( array $cols ) : array {

	// load css & js
	add_action( 'admin_footer', __NAMESPACE__ . '\\admin_footer' );

	// get
	global $post_type;
	$post_type_obj = get_post_type_object( $post_type );

	if ( empty( $post_type_obj ) ) {
		return $cols;
	}

	$_post_type_labels = get_post_type_labels( $post_type_obj );

	$cols = array_slice( $cols, 0, 1, true )
	+ [ 'featured_image' => $_post_type_labels->featured_image ] // our new column
	+ array_slice( $cols, 1, null, true );

	return $cols;
}

function admin_footer() {
	// get
	global $post_type;
	$post_type_obj = get_post_type_object( $post_type );

	if ( empty( $post_type_obj ) ) {
		return;
	}

	$_post_type_labels = get_post_type_labels( $post_type_obj );
	?>
	<style type="text/css">
		a.ft-upload-img{
			display: inline-block;
		}
		a.ft-upload-img,
		#featured_image{
			width:80px;
		}
		a.ft-upload-img img,
		td.featured_image.column-featured_image img{
			max-width: 100%;
			height: auto;
		}
		.fixed .column-tags {
			width: unset;
		}
	</style>
	<script type="text/javascript">
		jQuery(function($){
			const ft_fi_qe_set = '<?php echo $_post_type_labels->set_featured_image ?>';

			// add image
			$('body').on( 'click', '.ft-upload-img', function( event ) {
				event.preventDefault();

				const button = $(this);
				const customUploader = wp.media({
					title: ft_fi_qe_set,
					library : { type : 'image' },
					button: { text: ft_fi_qe_set },
				}).on( 'select', () => {
					const attachment = customUploader.state().get('selection').first().toJSON();
					button.removeClass('button').html( '<img src="' + attachment.url + '" />').next().val(attachment.id).parent().next().show();
				}).open();

			});

			// remove image

			$('body').on('click', '.ft-remove-img', function( event ) {
				event.preventDefault();
				$(this).hide().prev().find( '[name="_thumbnail_id"]').val('-1').prev().html(ft_fi_qe_set).addClass('button' );
			});

			const $wp_inline_edit = inlineEditPost.edit;

			inlineEditPost.edit = function( id ) {
				$wp_inline_edit.apply( this, arguments );
				let postId = 0;
				if( typeof( id ) == 'object' ) {
					postId = parseInt( this.getId( id ) );
				}

				if ( postId > 0 ) {
					const editRow = $( '#edit-' + postId )
					const postRow = $( '#post-' + postId )
					const featuredImage = $( '.column-featured_image', postRow ).html()
					const featuredImageId = $( '.column-featured_image', postRow ).find('img').data('id')

					if( featuredImageId != -1 ) {

						$( ':input[name="_thumbnail_id"]', editRow ).val( featuredImageId ); // ID
						$( '.ft-upload-img', editRow ).html( featuredImage ).removeClass( 'button' ); // image HTML
						$( '.ft-remove-img', editRow ).show(); // the remove link

					}
				}
			}
		});
	</script>
	<?php
}

/**
 * Fires for each custom column of a specific post type in the Posts list table.
 *
 * The dynamic portion of the hook name, `$post->post_type`, refers to the post type.
 *
 * Possible hook names include:
 *
 *  - `manage_post_posts_custom_column`
 *  - `manage_page_posts_custom_column`
 *
 * @since 3.1.0
 *
 * @param string $column_name The name of the column to display.
 * @param int    $post_id     The current post ID.
 */
function render_the_column( string $column_name, int $post_id ) {

	if ( 'featured_image' !== $column_name ) {
		return;
	}

	// if there is no featured image for this post, print the placeholder
	if ( has_post_thumbnail( $post_id ) ) {

		// I know about get_the_post_thumbnail() function but we need data-id attribute here
		$id = get_post_thumbnail_id( $post_id );
		$url = esc_url( wp_get_attachment_image_url( $id ) );
		?>
		<img data-id="<?php echo $id ?>" src="<?php echo $url ?>" />
		<?php

	} else {
		// data-id should be "-1" I will explain below
		?>
		<img data-id="-1" src="" style="display:none;" />
		<?php
	}
}

function include_wp_enqueue_media() {
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
}

function featured_image_quick_edit( string $column_name, string $post_type ) {

	// add it only if we have featured image column
	if ( 'featured_image' !== $column_name ) {
		return;
	}

	// get
	$post_type_obj = get_post_type_object( $post_type );

	if ( empty( $post_type_obj ) ) {
		return;
	}

	$_post_type_labels = get_post_type_labels( $post_type_obj );

	?>
	<fieldset id="ft_featured_image" class="inline-edit-col-left">
		<div class="inline-edit-col">
			<span class="title"><?php echo $_post_type_labels->featured_image; ?></span>
			<div>
				<a href="#" class="button ft-upload-img"><?php echo $_post_type_labels->set_featured_image; ?></a>
				<input type="hidden" name="_thumbnail_id" value="" />
			</div>
			<a href="#" class="ft-remove-img"><?php echo $_post_type_labels->remove_featured_image; ?></a>
		</div>
	</fieldset>
	<?php
}


