<?php
/**
 * Common functions
 */
namespace UCFResearchPublication\Common;

/**
 * Returns basic markup for publications
 * @author Jim Barnes
 * @since 1.0.0
 * @param WP_Post $publication The publication
 * @return string
 */
function get_publication_markup( $publication ) {
	switch( $publication->publication_type ) {
		case 'book':
			return get_book_markup( $publication );
		case 'journal':
			return get_journal_markup( $publication );
		case 'digital':
			return get_digital_markup( $publication );
		default:
			return '';
	}
}

function get_book_markup( $publication ) {
	$authors = get_field( 'publication_authors', $publication->ID );
	$author_string = implode( ', ', array_column( $authors, 'post_title' ) );
	$publisher = get_field( 'publication_publisher', $publication->ID );
	$advanced = get_field( 'publication_advanced_info', $publication->ID );
	$published_year = get_field( 'publication_year', $publication->ID );

	$details = $advanced;
	$details .= ! empty( $publisher ) ? ' ' . $publisher : '';
	$details .= ! empty( $published_year ) ? ', ' . $published_year : '';

	ob_start();
?>
	<div class="publication book">
		<h3 class="h5 publication-title font-italic"><?php echo $publication->post_title; ?></h3>
		<p class="publication-authors"><?php echo $author_string; ?></p>
		<?php if ( ! empty( $details ) ) : ?>
		<p class="publication-details"><?php echo $details; ?></p>
		<?php endif; ?>
	</div>
<?php
	return apply_filters( 'ucf_research_book_markup', ob_get_clean(), $publication );
}

function get_journal_markup( $publication ) {
	$authors = get_field( 'publication_authors', $publication->ID );
	$author_string = implode( ', ', array_column( $authors, 'post_title' ) );
	$journal = get_field( 'journal_title', $publication->ID );
	$advanced = get_field( 'publication_advanced_info', $publication->ID );
	$published_date = get_field( 'publication_date', $publication->ID );

	$details = '<span class="publication-journal font-italic">' . $journal . '</span>';
	$details .= ! empty( $advanced ) ? ' ' . $advanced : '';
	$details .= ! empty( $published_date ) ? ': ' . $published_date : '';

	ob_start();
?>
	<div class="publication journal">
		<h3 class="h5 publication-title">&ldquo;<?php echo $publication->post_title; ?>&ldquo;</h3>
		<p class="publication-authors"><?php echo $author_string; ?></p>
		<p class="publication-details"><?php echo $details; ?></p>
	</div>
<?php
	return apply_filters( 'ucf_research_journal_markup', ob_get_clean(), $publication );
}

function get_digital_markup( $publication ) {
	$authors = get_field( 'publication_authors', $publication->ID );
	$author_string = implode( ', ', array_column( $authors, 'post_title' ) );
	$website = get_field( 'website_name', $publication->ID );
	$url = get_field( 'publication_url', $publication->ID );
	$published_date = get_field( 'publication_date', $publication->ID );

	$details = "$website, $published_date <a href=\"$url\" target=\"_blank\">$url</a>";

	ob_start();
?>
	<div class="publication digital">
		<h3 class="h5 publication-title">&ldquo;<?php echo $publication->post_title; ?>&rdquo;</h3>
		<p class="publication-authors"><?php echo $author_string; ?></p>
		<p class="publication-details"><?php echo $details; ?></p>
	</div>
<?php
	return apply_filters( 'ucf_research_journal_markup', ob_get_clean(), $publication );
}
