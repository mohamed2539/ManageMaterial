document.addEventListener('DOMContentLoaded', () => {
    const materialCodeInput = document.getElementById('material_code');
    const materialNameInput = document.getElementById('material_name');
    const sizeInput = document.getElementById('size');
    const unitInput = document.getElementById('unit');
    const quantityInput = document.getElementById('quantity');
    const branchSelect = document.getElementById('branch_id');
    const responseMessage = document.getElementById('responseMessage');

    // Load branches dynamically
    fetch('/getBranches')
        .then(response => response.json())
        .then(data => {
            data.forEach(branch => {
                const option = document.createElement('option');
                option.value = branch.id;
                option.textContent = branch.name;
                branchSelect.appendChild(option);
            });
        });

    // Auto-fill material details when code is entered
    materialCodeInput.addEventListener('input', (e) => {
        const code = e.target.value.trim();

        if (code.length > 0) {
            fetch(`/getMaterialByCode?code=${code}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        materialNameInput.value = data.material.name;
                        sizeInput.value = data.material.size;
                        unitInput.value = data.material.unit;
                    } else {
                        materialNameInput.value = '';
                        sizeInput.value = '';
                        unitInput.value = '';
                        responseMessage.textContent = 'Invalid Material Code!';
                    }
                });
        } else {
            materialNameInput.value = '';
            sizeInput.value = '';
            unitInput.value = '';
            responseMessage.textContent = '';
        }
    });

    // Handle form submission via AJAX
    document.getElementById('dispenseForm').addEventListener('submit', (e) => {
        e.preventDefault();

        const code = materialCodeInput.value;
        const quantity = quantityInput.value;
        const branchId = branchSelect.value;

        if (!code || !quantity || !branchId) {
            responseMessage.textContent = 'Please fill in all fields!';
            return;
        }

        fetch('/dispenseMaterial', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ code, quantity, branchId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    responseMessage.textContent = 'Material dispensed successfully!';
                    responseMessage.classList.remove('text-red-500');
                    responseMessage.classList.add('text-green-500');
                } else {
                    responseMessage.textContent = data.message || 'Failed to dispense material!';
                    responseMessage.classList.remove('text-green-500');
                    responseMessage.classList.add('text-red-500');
                }
            });
    });
});