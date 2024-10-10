<?php

class CustomFieldPlugin
{
    public $title = 'Project Summary Plugin';
    public $version = '1.1';
    public $developer = 'Your Name';

    function __construct()
    {
        // Register hooks
        app_hooks::register_on_render_menu('CustomFieldPlugin::render_menu');
        app_hooks::register_on_render_custom_fields('CustomFieldPlugin::render_custom_field');
    }

    static function render_menu($menu)
    {
        $menu[] = array(
            'title' => 'Project Summary',
            'url' => url_for('plugins/CustomFieldPlugin/project_summary'),
            'class' => 'fa-file-text-o'
        );

        return $menu;
    }

    static function render_custom_field($type, $entity_id, $field_value)
    {
        if ($type == 'projects')
        {
            $html = '
            <div class="form-group">
              <label>Custom Field</label>
              <input type="text" name="custom_field" value="' . $field_value . '" class="form-control">
            </div>';

            return $html;
        }
    }
}

// Initialize plugin
new CustomFieldPlugin();