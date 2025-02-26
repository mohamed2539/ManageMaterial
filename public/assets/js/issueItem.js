document.addEventListener('DOMContentLoaded', function() {
    const itemCodeInput = document.getElementById('itemCode');
    const quantityInput = document.getElementById('quantity');
    const issueButton = document.getElementById('issueButton');
    const materialInfoDiv = document.getElementById('materialInfo');
    const issueForm = document.getElementById('issueItemForm');
    const editModal = document.getElementById('editModal');
    const closeModal = document.getElementById('closeModal');
    const editForm = document.getElementById('editTransactionForm');

    let currentMaterial = null;

    // البحث عن المادة عند إدخال الكود
    itemCodeInput.addEventListener('blur', function() {
        const code = this.value.trim();
        if (code) {
            fetchMaterialByCode(code);
        } else {
            resetMaterialInfo();
        }
    });

    // البحث عن المادة باستخدام الكود
    async function fetchMaterialByCode(code) {
        try {
            const response = await fetch(`/MaterailManegmentT/public/index.php?controller=transaction&action=getMaterialByCode&code=${code}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();

            if (data.status === 'success') {
                currentMaterial = data.data;
                displayMaterialInfo(currentMaterial);
                enableInputs();
            } else {
                alert(data.message || 'حدث خطأ في جلب بيانات المادة');
                resetMaterialInfo();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء البحث عن المادة');
            resetMaterialInfo();
        }
    }

    // عرض معلومات المادة
    function displayMaterialInfo(material) {
        document.getElementById('materialName').textContent = material.name;
        document.getElementById('materialSize').textContent = material.size || '-';
        document.getElementById('materialUnit').textContent = material.unit;
        document.getElementById('branchName').textContent = material.branch_name;
        document.getElementById('availableQuantity').textContent = material.quantity;
        document.getElementById('minQuantity').textContent = material.min_quantity;

        document.getElementById('materialId').value = material.id;
        document.getElementById('branchId').value = material.branch_id;

        materialInfoDiv.classList.remove('hidden');
    }

    // تمكين حقول الإدخال
    function enableInputs() {
        quantityInput.disabled = false;
        quantityInput.value = ''; // تفريغ حقل الكمية
        quantityInput.focus(); // التركيز على حقل الكمية
        issueButton.disabled = true; // زر الصرف يظل معطلاً حتى إدخال كمية صحيحة
    }

    // التحقق من الكمية
    quantityInput.addEventListener('input', function() {
        if (!currentMaterial) return;

        const requestedQuantity = parseInt(this.value);
        const availableQuantity = parseInt(currentMaterial.quantity);

        if (isNaN(requestedQuantity) || requestedQuantity <= 0) {
            this.classList.add('border-red-500');
            issueButton.disabled = true;
            return;
        }

        if (requestedQuantity > availableQuantity) {
            this.classList.add('border-red-500');
            issueButton.disabled = true;
            return;
        }

        this.classList.remove('border-red-500');
        issueButton.disabled = false;
    });

    // إرسال نموذج الصرف
    issueForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch('/MaterailManegmentT/public/index.php?controller=transaction&action=issueItem', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                alert(data.message);
                resetForm();
                await loadTransactions();
            } else {
                alert('خطأ: ' + (data.message || 'حدث خطأ أثناء عملية الصرف'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء معالجة الطلب');
        }
    });

    // تحميل العمليات
    async function loadTransactions() {
        try {
            const response = await fetch('/MaterailManegmentT/public/index.php?controller=transaction&action=getRecentTransactions');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();

            if (data.status === 'success') {
                updateTransactionsTable(data.data);
            } else {
                console.error('Error loading transactions:', data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('transactionsTable').innerHTML = 
                '<tr><td colspan="9" class="p-3 text-center text-red-500">حدث خطأ أثناء تحميل البيانات</td></tr>';
        }
    }

    // تحديث جدول العمليات
    function updateTransactionsTable(transactions) {
        const tbody = document.getElementById('transactionsTable');
        tbody.innerHTML = '';

        if (!transactions || transactions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="p-3 text-center">لا توجد عمليات سابقة</td></tr>';
            return;
        }

        transactions.forEach(transaction => {
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-300 hover:bg-gray-100 transition duration-300';

            row.innerHTML = `
                <td class="p-3 text-center">${transaction.transaction_code || '-'}</td>
                <td class="p-3 text-center">${transaction.material_name || '-'}</td>
                <td class="p-3 text-center">${transaction.quantity || '0'}</td>
                <td class="p-3 text-center">${transaction.branch_name || '-'}</td>
                <td class="p-3 text-center">${transaction.user_name || '-'}</td>
                <td class="p-3 text-center">${formatDate(transaction.transaction_date)}</td>
                <td class="p-3 text-center">${transaction.notes || '-'}</td>
                <td class="p-3 text-center">
                    <button class="edit-btn bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-300"
                            data-id="${transaction.id}"
                            data-code="${transaction.transaction_code}"
                            data-material="${transaction.material_name}"
                            data-quantity="${transaction.quantity}"
                            data-branch="${transaction.branch_name}"
                            data-notes="${transaction.notes || ''}"
                            data-available-quantity="${transaction.available_quantity || '0'}">
                        تعديل
                    </button>
                </td>
                <td class="p-3 text-center">
                    <button class="delete-btn bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-300"
                            data-id="${transaction.id}">
                        حذف
                    </button>
                </td>
            `;

            tbody.appendChild(row);
        });

        addButtonEvents();
    }

    // تنسيق التاريخ
    function formatDate(dateString) {
        if (!dateString) return '-';
        try {
            const date = new Date(dateString);
            return date.toLocaleString('ar-EG');
        } catch (error) {
            console.error('Error formatting date:', error);
            return dateString;
        }
    }

    // إضافة أحداث للأزرار
    function addButtonEvents() {
        // أزرار التعديل
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const data = this.dataset;
                document.getElementById('editTransactionId').value = data.id;
                document.getElementById('editTransactionCode').value = data.code;
                document.getElementById('editMaterialName').value = data.material;
                document.getElementById('editQuantity').value = data.quantity;
                document.getElementById('editBranchName').value = data.branch;
                document.getElementById('editNotes').value = data.notes;
                document.getElementById('editAvailableQuantity').textContent = data.availableQuantity;

                editModal.classList.remove('hidden');
            });
        });

        // أزرار الحذف
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async function() {
                if (confirm('هل أنت متأكد من حذف هذه العملية؟')) {
                    const id = this.dataset.id;
                    try {
                        const response = await fetch(`/MaterailManegmentT/public/index.php?controller=transaction&action=deleteTransaction&id=${id}`);
                        const data = await response.json();

                        alert(data.message);
                        if (data.status === 'success') {
                            await loadTransactions();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء حذف العملية');
                    }
                }
            });
        });
    }

    // التحقق من الكمية في نموذج التعديل
    document.getElementById('editQuantity').addEventListener('input', function() {
        const availableQuantity = parseInt(document.getElementById('editAvailableQuantity').textContent);
        const requestedQuantity = parseInt(this.value);

        if (isNaN(requestedQuantity) || requestedQuantity <= 0 || requestedQuantity > availableQuantity) {
            this.classList.add('border-red-500');
            document.querySelector('#editTransactionForm button[type="submit"]').disabled = true;
        } else {
            this.classList.remove('border-red-500');
            document.querySelector('#editTransactionForm button[type="submit"]').disabled = false;
        }
    });

    // إغلاق المودال
    closeModal.addEventListener('click', function() {
        editModal.classList.add('hidden');
    });

    // تحديث العملية
    editForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('id', document.getElementById('editTransactionId').value);
        formData.append('quantity', document.getElementById('editQuantity').value);
        formData.append('notes', document.getElementById('editNotes').value);

        try {
            const response = await fetch('/MaterailManegmentT/public/index.php?controller=transaction&action=updateTransaction', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            alert(data.message);

            if (data.status === 'success') {
                editModal.classList.add('hidden');
                await loadTransactions();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحديث العملية');
        }
    });

    // إعادة تعيين النموذج
    function resetForm() {
        issueForm.reset();
        resetMaterialInfo();
    }

    // إعادة تعيين معلومات المادة
    function resetMaterialInfo() {
        materialInfoDiv.classList.add('hidden');
        currentMaterial = null;
        quantityInput.disabled = true;
        issueButton.disabled = true;
    }

    // تحميل العمليات عند بدء الصفحة
    loadTransactions();
});