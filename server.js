require('dotenv').config();
const express = require('express');
const fetch = (...args) => import('node-fetch').then(({default: fetch}) => fetch(...args));
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());
app.use(express.static('public'));

app.post('/generate-summary', async (req, res) => {
  const { projectData } = req.body;

  if (!projectData) {
    return res.status(400).json({ error: 'Project data is required' });
  }

  try {
    const summary = await generateSummary(projectData);
    res.json({ summary });
  } catch (error) {
    console.error('Error generating summary:', error);
    res.status(500).json({ error: 'Failed to generate summary' });
  }
});

async function generateSummary(projectData) {
  const prompt = `Summarize the following project information:

Project Name: ${projectData.name}
Description: ${projectData.description}
Status: ${projectData.status}
Created At: ${projectData.createdAt}

Tasks:
${projectData.tasks.map(task => `- ${task.name} (Status: ${task.status})
  Description: ${task.description}`).join('\n')}

Please provide a concise summary of the project, including its current status and key tasks.`;

  const response = await fetch('https://api.openai.com/v1/engines/text-davinci-002/completions', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${process.env.OPENAI_API_KEY}`
    },
    body: JSON.stringify({
      prompt,
      max_tokens: 150,
      temperature: 0.7
    })
  });

  const data = await response.json();

  if (data.choices && data.choices[0] && data.choices[0].text) {
    return data.choices[0].text.trim();
  } else {
    throw new Error('Failed to generate summary');
  }
}

app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});