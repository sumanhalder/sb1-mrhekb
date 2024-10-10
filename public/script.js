document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('projectForm');
    const addTaskButton = document.getElementById('addTask');
    const tasksContainer = document.getElementById('tasksContainer');
    const summaryContainer = document.getElementById('summaryContainer');
    const summaryText = document.getElementById('summaryText');

    let taskCount = 0;

    addTaskButton.addEventListener('click', () => {
        taskCount++;
        const taskDiv = document.createElement('div');
        taskDiv.innerHTML = `
            <h4>Task ${taskCount}</h4>
            <div class="form-group">
                <label for="taskName${taskCount}">Task Name:</label>
                <input type="text" id="taskName${taskCount}" required>
            </div>
            <div class="form-group">
                <label for="taskStatus${taskCount}">Status:</label>
                <input type="text" id="taskStatus${taskCount}" required>
            </div>
            <div class="form-group">
                <label for="taskDescription${taskCount}">Description:</label>
                <textarea id="taskDescription${taskCount}" required></textarea>
            </div>
        `;
        tasksContainer.insertBefore(taskDiv, addTaskButton);
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const projectData = {
            name: document.getElementById('projectName').value,
            description: document.getElementById('projectDescription').value,
            status: document.getElementById('projectStatus').value,
            createdAt: document.getElementById('projectCreatedAt').value,
            tasks: []
        };

        for (let i = 1; i <= taskCount; i++) {
            projectData.tasks.push({
                name: document.getElementById(`taskName${i}`).value,
                status: document.getElementById(`taskStatus${i}`).value,
                description: document.getElementById(`taskDescription${i}`).value
            });
        }

        try {
            const response = await fetch('/generate-summary', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ projectData })
            });

            const data = await response.json();

            if (data.summary) {
                summaryText.textContent = data.summary;
                summaryContainer.classList.remove('hidden');
            } else {
                throw new Error('Failed to generate summary');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to generate summary. Please try again.');
        }
    });
});