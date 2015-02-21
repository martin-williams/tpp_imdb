<?php

add_action( 'init', 'tppdb_create_taxonomies' );


function tppdb_create_taxonomies() {
	tppdb_create_age_divisions_tax();
	tppdb_create_stages_tax();
	tppdb_create_organizations_tax();
	tppdb_create_years_tax();
	tppdb_create_roles_tax();
	tppdb_create_expertise_tax();
}

$capabilities = array(
		'manage_terms'               => 'edit_pages',
		'edit_terms'                 => 'edit_pages',
		'delete_terms'               => 'edit_pages',
		'assign_terms'               => 'edit_pages',
	);

function tppdb_create_age_divisions_tax() {
	register_taxonomy( 'age-divisions', array('pageants','pageant-years'),
		array(
			'label' => __( 'Age Divisions' ),
			'rewrite' => array( 'slug' => 'age-divisions' ),
			'hierarchical' => true,
			'capabilities' => $capabilities,
		)
	);
}

function tppdb_create_stages_tax() {
	register_taxonomy( 'stages', array('pageants','pageant-years'),
		array(
			'label' => __( 'Stages' ),
			'rewrite' => array( 'slug' => 'competition' ),
			'hierarchical' => false,
						'capabilities' => $capabilities,

		)
	);
}


function tppdb_create_organizations_tax() {
	register_taxonomy( 'organizations', array('pageants'),
		array(
			'label' => __( 'Organizations' ),
			'rewrite' => array( 'slug' => 'organizations' ),
			'hierarchical' => true,
						'capabilities' => $capabilities,

		)
	);
}


function tppdb_create_years_tax() {
	register_taxonomy( 'years', array('pageant-years'),
		array(
			'label' => __( 'Year' ),
			'rewrite' => array( 'slug' => 'year' ),
			'hierarchical' => false,
						'capabilities' => $capabilities,

		)
	);
}

function tppdb_create_roles_tax() {
	register_taxonomy( 'roles', array('tpp_profiles'),
		array(
			'label' => __( 'Roles' ),
			'rewrite' => array( 'slug' => 'roles' ),
			'hierarchical' => false,
						'capabilities' => $capabilities,

		)
	);
}

function tppdb_create_expertise_tax() {
	register_taxonomy( 'areas-of-expertise', array('tpp_profiles'),
		array(
			'label' => __( 'Areas of Expertise' ),
			'rewrite' => array( 'slug' => 'expertise' ),
			'hierarchical' => false,
						'capabilities' => $capabilities,

		)
	);
}