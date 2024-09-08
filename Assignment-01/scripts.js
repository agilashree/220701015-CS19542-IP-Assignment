document.addEventListener("DOMContentLoaded", () => {
    const taskForm = document.getElementById('taskForm');
    const tasksContainer = document.getElementById('tasksContainer');
    const filterAll = document.getElementById('filterAll');
    const filterPending = document.getElementById('filterPending');
    const filterCompleted = document.getElementById('filterCompleted');
    const feedback = document.getElementById('feedback');
    const loadingSpinner = document.getElementById('loadingSpinner');

    let tasks = [];

    // Function to show feedback messages
    function showFeedback(message, type) {
        feedback.textContent = message;
        feedback.className = type;
        feedback.style.display = 'block';

        setTimeout(() => {
            feedback.style.display = 'none';
        }, 3000);
    }

    // Function to show loading spinner
    function showLoadingSpinner(show) {
        loadingSpinner.style.display = show ? 'inline-block' : 'none';
    }

    // Function to render tasks based on filter
    function renderTasks(filter = 'all') {
        tasksContainer.innerHTML = ''; // Clear the current task list

        tasks.forEach(task => {
            // Filter tasks based on the selected filter option
            if (filter === 'pending' && task.completed) return; // Show only pending tasks
            if (filter === 'completed' && !task.completed) return; // Show only completed tasks

            const taskItem = document.createElement('li');
            taskItem.innerHTML = `
                <span class="${task.completed ? 'completed' : ''}">
                    <input type="checkbox" ${task.completed ? 'checked' : ''} class="complete-task">
                    <strong>${task.title}</strong><br>${task.description}<br>Due: ${task.dueDate}
                </span>
                <div>
                    <button class="edit">Edit</button>
                    <button class="delete">Delete</button>
                </div>
            `;

            // Toggle completion
            const checkbox = taskItem.querySelector('.complete-task');
            checkbox.addEventListener('change', () => {
                task.completed = checkbox.checked;
                renderTasks(filter); // Re-render the list with the same filter
            });

            // Delete task
            taskItem.querySelector('.delete').addEventListener('click', () => {
                tasks = tasks.filter(t => t !== task); // Remove task from array
                showFeedback("Task deleted successfully!", "success");
                renderTasks(filter); // Re-render with same filter
            });

            // Edit task
            taskItem.querySelector('.edit').addEventListener('click', () => {
                // Prefill the form with task data and remove task from array
                document.getElementById('taskTitle').value = task.title;
                document.getElementById('taskDescription').value = task.description;
                document.getElementById('dueDate').value = task.dueDate;
                tasks = tasks.filter(t => t !== task);
                renderTasks(filter); // Re-render with same filter
            });

            tasksContainer.appendChild(taskItem);
        });
    }

    // Add task event
    taskForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form from submitting traditionally

        const title = document.getElementById('taskTitle').value.trim();
        const description = document.getElementById('taskDescription').value.trim();
        const dueDate = document.getElementById('dueDate').value.trim();

        if (!title || !description || !dueDate) {
            showFeedback("All fields are required!", "error");
            return;
        }

        // Show loading spinner while "processing"
        showLoadingSpinner(true);

        setTimeout(() => {
            // Create task object and add it to the array
            tasks.push({
                title: title,
                description: description,
                dueDate: dueDate,
                completed: false
            });

            // Reset form
            taskForm.reset();
            showFeedback("Task added successfully!", "success");
            showLoadingSpinner(false);
            renderTasks(); // Re-render tasks
        }, 1000); // Simulate a delay for task processing
    });

    // Filters
    filterAll.addEventListener('click', (e) => {
        e.preventDefault();
        renderTasks('all');  // Show all tasks
    });

    filterPending.addEventListener('click', (e) => {
        e.preventDefault();
        renderTasks('pending');  // Show only pending tasks
    });

    filterCompleted.addEventListener('click', (e) => {
        e.preventDefault();
        renderTasks('completed');  // Show only completed tasks
    });

    // Initial rendering
    renderTasks();
});
