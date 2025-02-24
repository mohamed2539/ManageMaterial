document.addEventListener('DOMContentLoaded', () => {
    const branchSelect = document.getElementById('branch_id');

    // Load branches dynamically
    fetch('/getAllBranches')
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = branch.name;
                    branchSelect.appendChild(option);
                });
            } else {
                console.error('No branches found or invalid response:', data);
            }
        })
        .catch(error => {
            console.error('Error loading branches:', error);
        });
});

    // Load branches when the page loads
    loadBranches();

    // Handle form submission via AJAX
    document.getElementById('addMaterialForm').addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = {
            name: document.getElementById('name').value,
            size: document.getElementById('size').value,
            unit: document.getElementById('unit').value,
            quantity: document.getElementById('quantity').value,
            branch_id: branchSelect.value
        };

        fetch('/addMaterial', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    responseMessage.textContent = data.message;
                    responseMessage.classList.remove('text-red-500');
                    responseMessage.classList.add('text-green-500');
                    document.getElementById('addMaterialForm').reset();
                } else {
                    responseMessage.textContent = data.message;
                    responseMessage.classList.remove('text-green-500');
                    responseMessage.classList.add('text-red-500');
                }
            });
    });
});