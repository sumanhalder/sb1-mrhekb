# Rukovoditel Project Summary Plugin

This plugin for Rukovoditel generates summaries of projects using OpenAI's GPT model.

## Features

- Adds a "Project Summary" menu item to the Rukovoditel interface
- Allows users to enter a project ID and generate a summary
- Fetches project data including tasks
- Uses OpenAI's API to generate a concise summary of the project

## Installation

1. Copy the `plugins/ProjectSummaryPlugin` directory to your Rukovoditel installation's `plugins` directory.
2. Activate the plugin in the Rukovoditel admin panel.

## Configuration

Before using the plugin, you need to add your OpenAI API key:

1. Open `plugins/ProjectSummaryPlugin/generate_summary.php`
2. Find the line `$api_key = 'your-api-key';`
3. Replace `'your-api-key'` with your actual OpenAI API key

## Usage

1. After installation and configuration, a new "Project Summary" menu item will appear in Rukovoditel.
2. Click on this menu item to access the summary generation page.
3. Enter a project ID and click "Generate Summary".
4. The plugin will fetch the project data and generate a summary using OpenAI's API.

## Security Note

This plugin sends project data to OpenAI's API. Ensure that this complies with your data handling policies and regulations.

## License

[MIT License](https://opensource.org/licenses/MIT)