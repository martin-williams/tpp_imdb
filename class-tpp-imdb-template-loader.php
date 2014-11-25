<?php

/**
 * Template loader for TPP IMDB plugin
 *
 * Only need to specify class properties here.
 *
 */
class TPP_IMDB_Template_Loader extends Gamajo_Template_Loader {

    /**
     * Prefix for filter names.
     *
     * @since 1.0.0
     * @type string
     */
    protected $filter_prefix = 'tpp-imdb';

    /**
     * Directory name where custom templates for this plugin should be found in the theme.
     *
     * @since 1.0.0
     * @type string
     */
    protected $theme_template_directory = 'tpp-imdb-templates';

    /**
     * Reference to the root directory path of this plugin.
     *
     * @since 1.0.0
     * @type string
     */
    protected $plugin_directory = TPP_IMDB_PLUGIN_DIR;

    /**
     * Return the path to the templates directory in this plugin.
     *
     * @since 1.0.0
     * @return string
     */
    protected function get_templates_dir() {
        return $this->plugin_directory . 'includes';
    }
}