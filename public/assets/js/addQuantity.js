document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const supplierFilter = document.getElementById('supplierFilter');
    const branchFilter = document.getElementById('branchFilter');
    const sizeFilter = document.getElementById('sizeFilter');
    
    // تحميل الفلاتر
    loadFilters();
    
    // إضافة مستمعي الأحداث
    searchInput.addEventListener('input', performSearch);
    supplierFilter.addEventListener('change', performSearch);
    branchFilter.addEventListener('change', performSearch);
    sizeFilter.addEventListener('change', performSearch);

    function loadFilters() {
        fetch('/MaterailManegmentT/public/index.php?controller=addQuantity&action=getFilters')
        .then(response => response.json())
        .then(data => {
            // تحميل الفلاتر كما في المثال السابق
        })
        .catch(error => console.error('Error loading filters:', error));
    }

    function performSearch() {
        const searchData = new FormData();
        searchData.append('searchTerm', searchInput.value);
        searchData.append('supplier_id', supplierFilter.value);
        searchData.append('branch_id', branchFilter.value);
        searchData.append('size', sizeFilter.value);

        fetch('/MaterailManegmentT/public/index.php?controller=addQuantity&action=liveSearch', {
            method: 'POST',
            body: searchData
        })
        .then(response => response.json())
        .then(results => {
            const tbody = document.getElementById('searchResults');
            tbody.innerHTML = '';
            
            results.forEach(material => {
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">${material.code || ''}</td>
                        <td class="p-2 border">${material.name || ''}</td>
                        <td class="p-2 border">${material.size || ''}</td>
                        <td class="p-2 border">${material.unit || ''}</td>
                        <td class="p-2 border">${material.quantity || '0'}</td>
                        <td class="p-2 border">${material.branch_name || ''}</td>
                        <td class="p-2 border">${material.supplier_name || ''}</td>
                        <td class="p-2 border">
                            <input type="number" 
                                   class="quantity-input w-20 p-1 border rounded" 
                                   min="1" 
                                   placeholder="الكمية">
                        </td>
                        <td class="p-2 border">
                            <button onclick="addQuantity(${material.id}, this)" 
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                إضافة
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => console.error('Error performing search:', error));
    }
});

function addQuantity(materialId, button) {
    const row = button.closest('tr');
    const quantityInput = row.querySelector('.quantity-input');
    const quantity = quantityInput.value;

    if (!quantity || quantity <= 0) {
        alert('الرجاء إدخال كمية صحيحة');
        return;
    }

    const formData = new FormData();
    formData.append('material_id', materialId);
    formData.append('quantity', quantity);

    fetch('/MaterailManegmentT/public/index.php?controller=addQuantity&action=updateQuantity', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            quantityInput.value = '';
            performSearch(); // تحديث الجدول
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث الكمية');
    });
}