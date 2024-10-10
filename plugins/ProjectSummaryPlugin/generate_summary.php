<?php

require('../../config/config.php');
require('../../includes/functions/general.php');
require('../../includes/classes/model/entities/entities.php');

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$project_id = $input['projectId'] ?? null;

if (!$project_id) {
    echo json_encode(['error' => 'Project ID is required']);
    exit;
}

$project_data = fetch_project_data($project_id);

if (!$project_data) {
    echo json_encode(['error' => 'Project not found or you do not have permission to access it']);
    exit;
}

$summary = generate_summary($project_data);
echo json_encode(['summary' => $summary]);

function fetch_project_data($project_id) {
    $project = new entities_projects($project_id);
    
    if (!$project->has_access()) {
        return false;
    }
    
    $data = [
        'name' => $project->get_name(),
        'description' => $project->get_description(),
        'status' => $project->get_status_name(),
        'created_at' => $project->get_date_added(),
        'tasks' => []
    ];
    
    $tasks_query = db_query("SELECT * FROM app_entity_1 WHERE parent_id = " . $project_id);
    while ($task = db_fetch_array($tasks_query)) {
        $data['tasks'][] = [
            'name' => $task['field_6'],
            'status' => $task['field_5'],
            'description' => $task['field_7']
        ];
    }
    
    return $data;
}

function generate_summary($project_data) {
    // Replace 'your-api-key' with your actual OpenAI API key
    $api_key = 'your-api-key';
    
    $prompt = "Summarize the following project information:\n\n";
    $prompt .= "Project Name: " . $project_data['name'] . "\n";
    $prompt .= "Description: " . $project_data['description'] . "\n";
    $prompt .= "Status: " . $project_data['status'] . "\n";
    $prompt .= "Created At: " . $project_data['created_at'] . "\n\n";
    $prompt .= "Tasks:\n";
    
    foreach ($project_data['tasks'] as $task) {
        $prompt .= "- " . $task['name'] . " (Status: " . $task['status'] . ")\n";
        $prompt .= "  Description: " . $task['description'] . "\n";
    }
    
    $prompt .= "\nPlease provide a concise summary of the project, including its current status and key tasks.";
    
    $data = [
        'model' => 'text-davinci-002',
        'prompt' => $prompt,
        'max_tokens' => 150,
        'temperature' => 0.7
    ];
    
    $ch = curl_init('https://api.openai.com/v1/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if (isset($result['choices'][0]['text'])) {
        return nl2br(trim($result['choices'][0]['text']));
    } else {
        return "Error generating summary. Please try again.";
    }
}