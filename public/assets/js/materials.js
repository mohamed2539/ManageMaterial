// التعامل مع المواد عبر Ajax
const MaterialHandler = {
    baseUrl: '/MaterailManegmentT',

    // جلب كل المواد
    getAllMaterials: async function() {
        try {
            const response = await fetch(`${this.baseUrl}/materials/list`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            return await response.json();
        } catch (error) {
            console.error('Error fetching materials:', error);
            return { success: false, message: 'حدث خطأ أثناء جلب البيانات' };
        }
    },

    // إضافة مادة جديدة
    createMaterial: async function(formData) {
        try {
            const response = await fetch(`${this.baseUrl}/materials/add`, {
                method: 'POST',
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('Error creating material:', error);
            return { success: false, message: 'حدث خطأ أثناء إضافة المادة' };
        }
    },

    // تحديث مادة
    updateMaterial: async function(id, formData) {
        try {
            formData.append('_method', 'PUT');
            const response = await fetch(`${this.baseUrl}/materials/${id}`, {
                method: 'POST',
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('Error updating material:', error);
            return { success: false, message: 'حدث خطأ أثناء تحديث المادة' };
        }
    },

    // حذف مادة
    deleteMaterial: async function(id) {
        try {
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            
            const response = await fetch(`${this.baseUrl}/materials/${id}`, {
                method: 'POST',
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('Error deleting material:', error);
            return { success: false, message: 'حدث خطأ أثناء حذف المادة' };
        }
    },

    // تحديث الجدول
    updateTable: function(materials) {
        const tbody = document.querySelector('table tbody');
        if (!tbody) return;

        tbody.innerHTML = materials.map(material => `
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">${this.escapeHtml(material.code)}</td>
                <td class="py-2 px-4">${this.escapeHtml(material.name)}</td>
                <td class="py-2 px-4">${this.escapeHtml(material.unit)}</td>
                <td class="py-2 px-4">
                    <span class="${material.current_stock <= material.minimum_quantity ? 'text-red-600' : 'text-green-600'}">
                        ${Number(material.current_stock).toLocaleString()}
                    </span>
                </td>
                <td class="py-2 px-4">${Number(material.minimum_quantity).toLocaleString()}</td>
                <td class="py-2 px-4">${this.escapeHtml(material.category_name || 'غير مصنف')}</td>
                <td class="py-2 px-4">
                    <div class="flex space-x-2 space-x-reverse">
                        <button onclick="MaterialHandler.editMaterial(${material.id})" 
                                class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 rounded text-sm">
                            تعديل
                        </button>
                        <button onclick="MaterialHandler.confirmDelete(${material.id})" 
                                class="bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded text-sm">
                            حذف
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    },

    // عرض رسالة
    showMessage: function(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg ${
            type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
        }`;
        alertDiv.textContent = message;
        document.body.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 3000);
    },

    // تأكيد الحذف
    confirmDelete: function(id) {
        if (confirm('هل أنت متأكد من حذف هذه المادة؟')) {
            this.deleteMaterial(id).then(response => {
                if (response.success) {
                    this.showMessage(response.message);
                    this.getAllMaterials().then(data => {
                        if (data.success) this.updateTable(data.materials);
                    });
                } else {
                    this.showMessage(response.message, 'error');
                }
            });
        }
    },

    // تنظيف النص
    escapeHtml: function(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}; 