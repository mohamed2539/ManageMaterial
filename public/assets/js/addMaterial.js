document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addMaterialForm");
    const branchSelect = document.getElementById("branch_id");
    const supplierSelect = document.getElementById("supplier_id");
    
    // تحميل البيانات الأولية
    loadInitialData();

    function loadInitialData() {
        loadBranches();
        loadSuppliers();
        loadMaterials();
    }

    // إضافة مادة جديدة
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(form);
        
        fetch("/MaterailManegmentT/public/index.php?controller=material&action=store", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                form.reset();
                loadMaterials();
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء إضافة المادة");
        });
    });

    function loadSuppliers() {
        fetch("/MaterailManegmentT/public/index.php?controller=material&action=getSupplier")
        .then(response => response.json())
        .then(suppliers => {
            supplierSelect.innerHTML = '<option value="">اختر المورد (اختياري)</option>';
            suppliers.forEach(supplier => {
                supplierSelect.innerHTML += `
                    <option value="${supplier.id}">${supplier.name}</option>
                `;
            });
        })
        .catch(error => console.error("Error loading suppliers:", error));
    }

    function loadBranches() {
        fetch("/MaterailManegmentT/public/index.php?controller=branch&action=getBranches")
        .then(response => response.json())
        .then(branches => {
            branchSelect.innerHTML = '<option value="">اختر الفرع</option>';
            branches.forEach(branch => {
                branchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
            });
        })
        .catch(error => console.error("Error loading branches:", error));
    }

    function loadMaterials() {
        fetch("/MaterailManegmentT/public/index.php?controller=material&action=getMaterials")
        .then(response => response.json())
        .then(materials => {
            const tbody = document.querySelector("tbody");
            tbody.innerHTML = "";
            
            materials.forEach(material => {
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-100">
                        <td class="p-2 border">${material.code || ''}</td>
                        <td class="p-2 border">${material.name || ''}</td>
                        <td class="p-2 border">${material.size || ''}</td>
                        <td class="p-2 border">${material.unit || ''}</td>
                        <td class="p-2 border">${material.quantity || ''}</td>
                        <td class="p-2 border">
                            <button class="edit-btn bg-yellow-500 text-white px-2 py-1 rounded" 
                                    data-id="${material.id}">تعديل</button>
                            <button class="delete-btn bg-red-500 text-white px-2 py-1 rounded" 
                                    data-id="${material.id}">حذف</button>
                        </td>
                    </tr>
                `;
            });

            addButtonEvents();
        })
        .catch(error => console.error("Error loading materials:", error));
    }

    function addButtonEvents() {
        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                if (confirm("هل أنت متأكد من حذف هذه المادة؟")) {
                    const id = this.dataset.id;
                    fetch(`/MaterailManegmentT/public/index.php?controller=material&action=delete&id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === "success") {
                            loadMaterials();
                        }
                    })
                    .catch(error => console.error("Error:", error));
                }
            });
        });
    }
});