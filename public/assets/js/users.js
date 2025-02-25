document.addEventListener('DOMContentLoaded', function() {
    loadBranches();
    
    const userForm = document.getElementById('userForm');
    if (userForm) {
        userForm.addEventListener('submit', handleSubmit);
    }
});

function loadBranches() {
    fetch('/MaterailManegmentT/public/index.php?controller=branch&action=getBranches')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(branches => {
            const select = document.getElementById('branch_id');
            if (select) {
                select.innerHTML = '<option value="">اختر الفرع</option>';
                branches.forEach(branch => {
                    select.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error loading branches:', error);
            alert('حدث خطأ في تحميل الفروع');
        });
}

function showCreateModal() {
    document.getElementById('modalTitle').textContent = 'إضافة مستخدم جديد';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    document.getElementById('password').required = true;
    document.getElementById('userModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

function editUser(userId) {
    document.getElementById('modalTitle').textContent = 'تعديل بيانات المستخدم';
    document.getElementById('userId').value = userId;
    document.getElementById('password').required = false;
    
    fetch(`/MaterailManegmentT/public/index.php?controller=user&action=edit&id=${userId}`)
        .then(response => response.json())
        .then(user => {
            document.getElementById('username').value = user.username;
            document.getElementById('full_name').value = user.full_name;
            document.getElementById('branch_id').value = user.branch_id;
            document.getElementById('role').value = user.role;
            document.getElementById('status').value = user.status;
            document.getElementById('userModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في تحميل بيانات المستخدم');
        });
}

function handleSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const userId = formData.get('id');
    const url = userId 
        ? '/MaterailManegmentT/public/index.php?controller=user&action=update'
        : '/MaterailManegmentT/public/index.php?controller=user&action=store';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            closeModal();
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في العملية');
    });
}