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
        fetch('/MaterailManegmentT/public/index.php?controller=search&action=getFilters')
        .then(response => response.json())
        .then(data => {
            // تحميل الموردين
            data.suppliers.forEach(supplier => {
                supplierFilter.innerHTML += `<option value="${supplier.id}">${supplier.name}</option>`;
            });
            
            // تحميل الفروع
            data.branches.forEach(branch => {
                branchFilter.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
            });
            
            // تحميل المقاسات
            data.sizes.forEach(size => {
                sizeFilter.innerHTML += `<option value="${size}">${size}</option>`;
            });
        })
        .catch(error => console.error('Error loading filters:', error));
    }

    function performSearch() {
        const searchData = new FormData();
        searchData.append('searchTerm', searchInput.value);
        searchData.append('supplier_id', supplierFilter.value);
        searchData.append('branch_id', branchFilter.value);
        searchData.append('size', sizeFilter.value);

        fetch('/MaterailManegmentT/public/index.php?controller=search&action=liveSearch', {
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
                        <td class="p-2 border">${material.quantity || ''}</td>
                        <td class="p-2 border">${material.branch_name || ''}</td>
                        <td class="p-2 border">${material.supplier_name || ''}</td>
                        <td class="p-2 border">${material.updated_at || ''}</td>
                    </tr>
                `;
            });
        })
        .catch(error => console.error('Error performing search:', error));
    }
});