<?php
/**
 * Defines the Research Publications custom post type
 */
namespace UCFResearchPublication\PostTypes;

class ResearchPublication {
	private
		/**
		 * @var string The singular label for the post type
		 */
		$singular,
		/**
		 * @var string The lowercase variant of $singular
		 */
		$singular_lower,
		/**
		 * @var string The plural label for the post type
		 */
		$plural,
		/**
		 * @var string The lowercase variant of $plural
		 */
		$plural_lower,
		/**
		 * @var string The text domain to use for translation
		 */
		$text_domain;

	/**
	 * Constructs the Research object.
	 * Adds any initial filters/actions as needed
	 * @author Jim Barnes
	 * @since 1.0.0
	 */
	public function __construct() {
		// Always set the labels on construct
		$this->set_labels();

		// Register the post type on init
		add_action( 'init', array( $this, 'register' ), 10, 0 );
		add_action( 'init', array( $this, 'register_fields' ), 10, 0 );
	}

	/**
	 * Helper function that initializes the labels
	 * @author Jim Barnes
	 * @since 1.0.0
	 */
	private function set_labels() {
		$defaults = array(
			'singular'    => 'Research Publication',
			'plural'      => 'Research Publications',
			'text_domain' => 'ucf_research_publications'
		);

		$labels = apply_filters( 'ucf_research_project_label_defaults', $defaults );

		$this->singular       = $labels['singular'];
		$this->singular_lower = strtolower( $this->singular );
		$this->plural         = $labels['plural'];
		$this->plural_lower   = strtolower( $this->plural );
		$this->text_domain    = $labels['text_domain'];
	}

	/**
	 * Registers the post type
	 * @author Jim Barnes
	 * @since 1.0.0
	 */
	public function register() {
		register_post_type( 'research_publication', $this->args() );
	}

	/**
	 * Generates the labels used to register
	 * the custom post type
	 * @author Jim Barnes
	 * @since 1.0.0
	 * @return array The array of labels
	 */
	private function labels() {
		$retval = array(
			"name"                  => _x( $this->plural, "Post Type General Name", $this->text_domain ),
			"singular_name"         => _x( $this->singular, "Post Type Singular Name", $this->text_domain ),
			"menu_name"             => __( $this->plural, $this->text_domain ),
			"name_admin_bar"        => __( $this->singular, $this->text_domain ),
			"archives"              => __( "$this->singular Archives", $this->text_domain ),
			"parent_item_colon"     => __( "Parent $this->singular:", $this->text_domain ),
			"all_items"             => __( "All $this->plural", $this->text_domain ),
			"add_new_item"          => __( "Add New $this->singular", $this->text_domain ),
			"add_new"               => __( "Add New", $this->text_domain ),
			"new_item"              => __( "New $this->singular", $this->text_domain ),
			"edit_item"             => __( "Edit $this->singular", $this->text_domain ),
			"update_item"           => __( "Update $this->singular", $this->text_domain ),
			"view_item"             => __( "View $this->singular", $this->text_domain ),
			"search_items"          => __( "Search $this->plural", $this->text_domain ),
			"not_found"             => __( "Not found", $this->text_domain ),
			"not_found_in_trash"    => __( "Not found in Trash", $this->text_domain ),
			"featured_image"        => __( "Featured Image", $this->text_domain ),
			"set_featured_image"    => __( "Set featured image", $this->text_domain ),
			"remove_featured_image" => __( "Remove featured image", $this->text_domain ),
			"use_featured_image"    => __( "Use as featured image", $this->text_domain ),
			"insert_into_item"      => __( "Insert into $this->singular_lower", $this->text_domain ),
			"uploaded_to_this_item" => __( "Uploaded to this $this->singular_lower", $this->text_domain ),
			"items_list"            => __( "$this->plural list", $this->text_domain ),
			"items_list_navigation" => __( "$this->plural list navigation", $this->text_domain ),
			"filter_items_list"     => __( "Filter $this->plural_lower list", $this->text_domain ),
		);

		$retval = apply_filters( 'ucf_research_publication_labels', $retval );

		return $retval;
	}

	/**
	 * Returns the args array used in
	 * registering the custom post type
	 * @author Jim Barnes
	 * @since 1.0.0
	 * @return array The argument array
	 */
	private function args() {
		$taxonomies = $this->taxonomies();

		$args = array(
			'label'               => __( $this->singular, $this->text_domain ),
			'description'         => __( $this->plural, $this->text_domain ),
			'labels'              => $this->labels(),
			'supports'            => array( 'title', 'editor', 'excerpt', 'revisions' ),
			'taxonomies'          => $taxonomies,
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'rest_base'           => 'research-publications',
			'menu_position'       => 8,
			'menu_icon'           => 'dashicons-book-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post'
		);

		$args = apply_filters( 'ucf_research_publication_args', $args );

		return $args;
	}

	/**
	 * Function that returns the taxonomies
	 * used by the custom post type
	 * @author Jim Barnes
	 * @since 1.0.0
	 * @return array The array of taxonomies
	 */
	public function taxonomies() {
		return apply_filters(
			'ucf_research_publication_taxonomies',
			array()
		);
	}

	/**
	 * Register the ACF fields
	 * @author Jim Barnes
	 * @since 1.0.0
	 */
	public function register_fields() {

		if ( ! function_exists('acf_add_local_field_group') ) return;

		// Create the array to add fields to
		$fields = array();

		$fields[] = array(
			'key'          => 'publication_type',
			'label'        => 'Publication Type',
			'name'         => 'publication_type',
			'type'         => 'radio',
			'instructions' => 'Choose the type of publication.',
			'required'     => 1,
			'choices'      => array(
				'book'    => 'Book',
				'journal' => 'Journal',
				'digital' => 'Digital',
			),
			'allow_null'    => 0,
			'other_choice'  => 0,
			'default_value' => 'book',
			'layout'        => 'vertical',
			'return_format' => 'value'
		);

		$fields[] = array(
			'key'               => 'journal_title',
			'label'             => 'Journal',
			'name'              => 'journal_title',
			'type'              => 'text',
			'instructions'      => 'The name of the journal.',
			'required'          => 1,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'publication_type',
						'operator' => '==',
						'value'    => 'journal',
					),
				),
			)
		);

		$fields[] = array(
			'key'               => 'publication_url',
			'label'             => 'Publication URL',
			'name'              => 'publication_url',
			'type'              => 'url',
			'instructions'      => 'The URL to the publication, if available.',
			'required'          => 0,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'publication_type',
						'operator' => '!=empty',
					),
				),
			)
		);

		$fields[] = array(
			'key'               => 'website_name',
			'label'             => 'Website Name',
			'name'              => 'website_name',
			'type'              => 'text',
			'instructions'      => 'The website name of the digital publication.',
			'required'          => 1,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'publication_type',
						'operator' => '==',
						'value'    => 'digital',
					),
				),
			)
		);

		$fields[] = array(
			'key'               => 'publication_advanced_info',
			'label'             => 'Advanced Info',
			'name'              => 'publication_advanced_info',
			'type'              => 'text',
			'instructions'      => 'Additional information that identifies the source. This may include a volume number or page range.',
			'required'          => 0,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'publication_type',
						'operator' => '==',
						'value'    => 'book',
					),
				),
				array(
					array(
						'field'    => 'publication_type',
						'operator' => '==',
						'value'    => 'journal',
					),
				),
			)
		);

		$fields[] = array(
			'key'               => 'publication_publisher',
			'label'             => 'Publisher',
			'name'              => 'publication_publisher',
			'type'              => 'text',
			'instructions'      => 'Enter the publisher\'s information.',
			'required'          => 0,
			'conditional_logic' => array(
				array(
					'field'    => 'publication_type',
					'operator' => '==',
					'value'    => 'book'
				)
			)
		);

		$fields[] = array(
			'key'               => 'publication_authors',
			'label'             => 'Authors',
			'name'              => 'publication_authors',
			'type'              => 'relationship',
			'instructions'      => 'Select the authors that are affiliated with UCF.',
			'required'          => 1,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'publication_type',
						'operator' => '!=empty',
					),
				),
			),
			'post_type' => array(
				0 => 'person',
			),
			'taxonomy' => '',
			'filters' => array(
				0 => 'search',
			),
			'min'           => 1,
			'max'           => 5,
			'return_format' => 'object',
		);

		$fields[] = array(
			'key'               => 'publication_contributors',
			'label'             => 'Contributors',
			'name'              => 'publication_contributors',
			'type'              => 'repeater',
			'instructions'      => 'Add publication contributors.',
			'required'          => 0,
			"min"				=> 0,
			"max"				=> 10,
			"layout"			=> "table",
			'sub_fields'		=> array(
				array(
					'key'               => 'publication_contributor',
					'label'             => 'Contributor',
					'name'              => 'publication_contributor',
					'type'              => 'text',
					'instructions'      => 'Add publication contributor.',
					'required'          => 0,
				),
			),
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'publication_type',
						'operator' => '!=empty',
					),
				),
			),
		);

		$fields[] = array(
			'key'            => 'publication_year',
			'label'          => 'Publication Year',
			'name'           => 'publication_year',
			'type'           => 'date_picker',
			'instructions'   => 'Choose the date of the publication.',
			'required'       => 1,
			'display_format' => 'Y',
			'return_format'  => 'Y',
			'first_day'      => 0,
			'conditional_logic' => array(
				array(
					'field'    => 'publication_type',
					'operator' => '==',
					'value'    => 'book'
				)
			)
		);

		$fields[] = array(
			'key'            => 'publication_date',
			'label'          => 'Publication Date',
			'name'           => 'publication_date',
			'type'           => 'date_picker',
			'instructions'   => 'Choose the date of the publication.',
			'required'       => 1,
			'display_format' => 'M, Y',
			'return_format'  => 'M, Y',
			'first_day'      => 0,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'publication_type',
						'operator' => '==',
						'value'    => 'journal',
					),
				),
				array(
					array(
						'field'    => 'publication_type',
						'operator' => '==',
						'value'    => 'digital',
					),
				),
			)
		);

		$fields = apply_filters( 'research_publications_fields', $fields );

		//Setup the args
		$args = array(
			'key'      => 'research_publications_fields',
			'title'    => 'Research Publication Fields',
			'fields'   => $fields,
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'research_publication',
					),
				),
			)
		);

		$args = apply_filters( 'research_publication_field_args', $args );

		acf_add_local_field_group( $args );
	}
}

new ResearchPublication();
