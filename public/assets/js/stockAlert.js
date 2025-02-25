document.addEventListener('DOMContentLoaded', function() {
    initializeAlerts();
    setInterval(refreshAlerts, 300000); // تحديث كل 5 دقائق
});

function initializeAlerts() {
    // إضافة أحداث للأزرار
    document.querySelectorAll('[data-action="create-order"]').forEach(button => {
        button.addEventListener('click', function() {
            const materialId = this.dataset.materialId;
            createOrder(materialId);
        });
    });

    document.querySelectorAll('[data-action="transfer-stock"]').forEach(button => {
        button.addEventListener('click', function() {
            const materialId = this.dataset.materialId;
            showTransferDialog(materialId);
        });
    });
}

function createOrder(materialId) {
    // فتح نافذة منبثقة لإنشاء طلب توريد
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center';
    modal.innerHTML = `
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl mb-4">إنشاء طلب توريد</h3>
            <form id="orderForm">
                <input type="hidden" name="material_id" value="${materialId}">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">الكمية المطلوبة</label>
                    <input type="number" name="quantity" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">المورد</label>
                    <select name="supplier_id" required class="w-full p-2 border rounded">
                        <!-- سيتم تعبئة الموردين عبر AJAX -->
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">ملاحظات</label>
                    <textarea name="notes" class="w-full p-2 border rounded"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal(this)" 
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        إنشاء الطلب
                    </button>
                </div>
            </form>
        </div>
    `;
    document.body.appendChild(modal);
    
    // تحميل الموردين
    loadSuppliers();
    
    // إضافة حدث للنموذج
    document.getElementById('orderForm').addEventListener('submit', handleOrderSubmit);
}

function showTransferDialog(materialId) {
    // فتح نافذة منبثقة لنقل المخزون
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center';
    modal.innerHTML = `
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl mb-4">نقل المخزون</h3>
            <form id="transferForm">
                <input type="hidden" name="material_id" value="${materialId}">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">الكمية المراد نقلها</label>
                    <input type="number" name="quantity" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">الفرع المستهدف</label>
                    <select name="target_branch_id" required class="w-full p-2 border rounded">
                        <!-- سيتم تعبئة الفروع عبر AJAX -->
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">ملاحظات</label>
                    <textarea name="notes" class="w-full p-2 border rounded"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal(this)" 
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        نقل المخزون
                    </button>
                </div>
            </form>
        </div>
    `;
    document.body.appendChild(modal);
    
    // تحميل الفروع
    loadBranches();
    
    // إضافة حدث للنموذج
    document.getElementById('transferForm').addEventListener('submit', handleTransferSubmit);
}

async function loadSuppliers() {
    try {
        const response = await fetch('/MaterailManegmentT/public/index.php?controller=supplier&action=getAll');
        const suppliers = await response.json();
        const select = document.querySelector('select[name="supplier_id"]');
        suppliers.forEach(supplier => {
            const option = document.createElement('option');
            option.value = supplier.id;
            option.textContent = supplier.name;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading suppliers:', error);
        alert('حدث خطأ في تحميل قائمة الموردين');
    }
}

async function loadBranches() {
    try {
        const response = await fetch('/MaterailManegmentT/public/index.php?controller=branch&action=getAll');
        const branches = await response.json();
        const select = document.querySelector('select[name="target_branch_id"]');
        branches.forEach(branch => {
            const option = document.createElement('option');
            option.value = branch.id;
            option.textContent = branch.name;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading branches:', error);
        alert('حدث خطأ في تحميل قائمة الفروع');
    }
}

async function handleOrderSubmit(e) {
    e.preventDefault();
    try {
        const formData = new FormData(e.target);
        const response = await fetch('/MaterailManegmentT/public/index.php?controller=inventory&action=createOrder', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            alert('تم إنشاء طلب التوريد بنجاح');
            closeModal(e.target);
            refreshAlerts();
        } else {
            alert(result.message || 'حدث خطأ في إنشاء الطلب');
        }
    } catch (error) {
        console.error('Error creating order:', error);
        alert('حدث خطأ في إنشاء الطلب');
    }
}

async function handleTransferSubmit(e) {
    e.preventDefault();
    try {
        const formData = new FormData(e.target);
        const response = await fetch('/MaterailManegmentT/public/index.php?controller=inventory&action=transferStock', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            alert('تم نقل المخزون بنجاح');
            closeModal(e.target);
            refreshAlerts();
        } else {
            alert(result.message || 'حدث خطأ في نقل المخزون');
        }
    } catch (error) {
        console.error('Error transferring stock:', error);
        alert('حدث خطأ في نقل المخزون');
    }
}

function closeModal(element) {
    const modal = element.closest('.fixed');
    modal.remove();
}

async function refreshAlerts() {
    try {
        const response = await fetch('/MaterailManegmentT/public/index.php?controller=inventory&action=getAlerts');
        const alerts = await response.json();
        updateAlertsTable(alerts);
    } catch (error) {
        console.error('Error refreshing alerts:', error);
    }
}

function updateAlertsTable(alerts) {
    // تحديث جداول التنبيهات
    // يمكن تنفيذ هذا حسب هيكل HTML الخاص بك
}