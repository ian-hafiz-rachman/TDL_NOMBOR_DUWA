<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scroll">
                <form id="editTaskForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="edit_title" class="form-label">Task Title</label>
                        <input type="text" class="form-control form-control-lg" id="edit_title" name="title" required 
                               placeholder="Enter task title">
                    </div>
                    <div class="mb-4">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" 
                                  rows="4" placeholder="Enter task description"></textarea>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_end_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="edit_end_date" 
                                   name="end_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_priority" class="form-label">Priority</label>
                            <select class="form-select" id="edit_priority" name="priority" required>
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editTaskForm" class="btn btn-primary px-4">Update Task</button>
            </div>
        </div>
    </div>
</div>

<script>
function openEditTaskModal(taskId) {
    // Fetch task data
    fetch(`/tasks/${taskId}/edit`)
        .then(response => response.json())
        .then(task => {
            // Set form action
            document.getElementById('editTaskForm').action = `/tasks/${taskId}`;
            
            // Format the date properly (remove time part and format as YYYY-MM-DD)
            const date = new Date(task.end_date);
            const formattedDate = date.toISOString().split('T')[0];
            
            // Fill form fields
            document.getElementById('edit_title').value = task.title;
            document.getElementById('edit_description').value = task.description;
            document.getElementById('edit_end_date').value = formattedDate;
            document.getElementById('edit_priority').value = task.priority;
            document.getElementById('edit_status').value = task.status;
            
            // Show modal
            new bootstrap.Modal(document.getElementById('editTaskModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading task data');
        });
}

// Handle form submission
document.getElementById('editTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide modal
            bootstrap.Modal.getInstance(document.getElementById('editTaskModal')).hide();
            
            // Show success message
            alert(data.message);
            
            // Reload page to show updated data
            window.location.reload();
        } else {
            alert(data.message || 'Error updating task');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating task');
    });
});
</script> 