<?php
/**
 * Template Name: WPDB Col Example
 *
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 1/22/2016
 * Time: 9:35 AM
 */


$meta_key1		= 'model';
$meta_key2		= 'year';
$meta_key3		= 'manufacturer';
$meta_key3_value	= 'Ford';

$postids=$wpdb->get_col( $wpdb->prepare(
	"
	SELECT      key3.post_id
	FROM        $wpdb->postmeta key3
	INNER JOIN  $wpdb->postmeta key1
	            ON key1.post_id = key3.post_id
	            AND key1.meta_key = %s
	INNER JOIN  $wpdb->postmeta key2
	            ON key2.post_id = key3.post_id
	            AND key2.meta_key = %s
	WHERE       key3.meta_key = %s
	            AND key3.meta_value = %s
	ORDER BY    key1.meta_value, key2.meta_value
	",
	$meta_key1,
	$meta_key2,
	$meta_key3,
	$meta_key3_value
) );

get_header(); ?>

	<div id="content">
	<div class="spacer"></div>
<?php if ( $postids )
{
	echo "List of {$meta_key3_value}(s), sorted by {$meta_key1}, {$meta_key2}";
	foreach ( $postids as $id )
	{
		$post = get_post( intval( $id ) );
		setup_postdata( $post );
		?>
		<p>
			<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
				<?php the_title(); ?>
			</a>
		</p>
		<?php
	}
}
