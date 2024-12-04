let isEditing = false;

document.addEventListener('DOMContentLoaded', function() {
    loadStudents();
    
    document.getElementById('studentDataForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (isEditing) {
            updateStudent();
        } else {
            createStudent();
        }
    });
});

function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
}

function showAddForm() {
    isEditing = false;
    document.getElementById('formTitle').textContent = 'Add New Student';
    document.getElementById('studentDataForm').reset();
    document.getElementById('studentForm').classList.remove('hidden');
}

function hideForm() {
    document.getElementById('studentForm').classList.add('hidden');
    document.getElementById('studentDataForm').reset();
}

async function loadStudents() {
    try {
        showLoading();
        const response = await fetch('api.php');
        const students = await response.json();
        
        const tableBody = document.getElementById('studentTableBody');
        tableBody.innerHTML = '';
        
        students.forEach(student => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${student.nim}</td>
                <td>${student.name}</td>
                <td>${student.email}</td>
                <td>${student.phone}</td>
                <td>${student.major}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="editStudent(${student.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteStudent(${student.id})">Delete</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load students');
    } finally {
        hideLoading();
    }
}

async function createStudent() {
    try {
        showLoading();
        const studentData = {
            action: 'create',
            nim: document.getElementById('nim').value,
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            major: document.getElementById('major').value
        };

        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(studentData)
        });

        const result = await response.json();
        alert(result.message);
        hideForm();
        loadStudents();
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to create student');
    } finally {
        hideLoading();
    }
}

async function editStudent(id) {
    try {
        showLoading();
        const response = await fetch(`api.php?id=${id}`);
        const student = await response.json();
        
        document.getElementById('studentId').value = student.id;
        document.getElementById('nim').value = student.nim;
        document.getElementById('name').value = student.name;
        document.getElementById('email').value = student.email;
        document.getElementById('phone').value = student.phone;
        document.getElementById('major').value = student.major;
        
        isEditing = true;
        document.getElementById('formTitle').textContent = 'Edit Student';
        document.getElementById('studentForm').classList.remove('hidden');
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load student data');
    } finally {
        hideLoading();
    }
}

async function updateStudent() {
    try {
        showLoading();
        const studentData = {
            action: 'update',
            id: document.getElementById('studentId').value,
            nim: document.getElementById('nim').value,
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            major: document.getElementById('major').value
        };

        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(studentData)
        });

        const result = await response.json();
        alert(result.message);
        hideForm();
        loadStudents();
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update student');
    } finally {
        hideLoading();
    }
}

async function deleteStudent(id) {
    if (!confirm('Are you sure you want to delete this student?')) {
        return;
    }

    try {
        showLoading();
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'delete',
                id: id
            })
        });

        const result = await response.json();
        alert(result.message);
        loadStudents();
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete student');
    } finally {
        hideLoading();
    }
}