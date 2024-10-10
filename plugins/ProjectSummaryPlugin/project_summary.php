<?php

require('../../config/config.php');
require('../../includes/functions/general.php');
require('../../includes/classes/model/entities/entities.php');

$app_user = app_session_check();

$html = '
<h3>Project Summary Generator</h3>
<form id="projectForm">
    <div class="form-group">
        <label>Enter Project ID:</label>
        <input type="number" id="projectId" class="form-control" required>
    </div>
    <button type="button" id="generateSummary" class="btn btn-primary">Generate Summary</button>
</form>
<div id="summaryContainer" style="display:none; margin-top: 20px;">
    <h4>Project Summary</h4>
    <div id="summaryText" class="well"></div>
</div>

<script>
document.getElementById("generateSummary").addEventListener("click", async function() {
    const projectId = document.getElementById("projectId").value;
    if (!projectId) {
        alert("Please enter a project ID");
        return;
    }

    try {
        const response = await fetch("plugins/ProjectSummaryPlugin/generate_summary.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ projectId: projectId }),
        });

        const data = await response.json();

        if (data.summary) {
            document.getElementById("summaryText").innerHTML = data.summary;
            document.getElementById("summaryContainer").style.display = "block";
        } else {
            throw new Error("Failed to generate summary");
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Failed to generate summary. Please try again.");
    }
});
</script>';

echo $html;