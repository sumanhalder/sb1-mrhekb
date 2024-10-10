<?php

class ProjectSummaryPlugin
{
    public $title = 'Project Summary Plugin';
    public $version = '1.0';
    public $developer = 'Your Name';

    function __construct()
    {
        // Register hooks
        app_hooks::register_on_render_menu('ProjectSummaryPlugin::render_menu');
    }

    static function render_menu($menu)
    {
        $menu[] = array(
            'title' => 'Project Summary',
            'url' => url_for('plugins/ProjectSummaryPlugin/project_summary'),
            'class' => 'fa-file-text-o'
        );

        return $menu;
    }
}

// Initialize plugin
new ProjectSummaryPlugin();