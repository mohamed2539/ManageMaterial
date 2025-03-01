document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addMaterialForm");
    const editForm = document.getElementById("editMaterialForm");
    const branchSelect = document.getElementById("branch_id");
    const supplierSelect = document.getElementById("supplier_id");
    const editModal = document.getElementById("editModal");
    
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
        
        fetch("/MaterailManegmentT/materials/store", {
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
        fetch("/MaterailManegmentT/materials/getSupplier")
        .then(response => response.json())
        .then(suppliers => {
            supplierSelect.innerHTML = '<option value="">اختر المورد (اختياري)</option>';
            document.getElementById("editSupplierId").innerHTML = '<option value="">اختر المورد (اختياري)</option>';
            
            suppliers.forEach(supplier => {
                const option = `<option value="${supplier.id}">${supplier.name}</option>`;
                supplierSelect.insertAdjacentHTML('beforeend', option);
                document.getElementById("editSupplierId").insertAdjacentHTML('beforeend', option);
            });
        })
        .catch(error => console.error("Error loading suppliers:", error));
    }

    function loadBranches() {
        fetch("/MaterailManegmentT/materials/getBranches")
        .then(response => response.json())
        .then(branches => {
            branchSelect.innerHTML = '<option value="">اختر الفرع</option>';
            document.getElementById("editBranchId").innerHTML = '<option value="">اختر الفرع</option>';
            
            branches.forEach(branch => {
                const option = `<option value="${branch.id}">${branch.name}</option>`;
                branchSelect.insertAdjacentHTML('beforeend', option);
                document.getElementById("editBranchId").insertAdjacentHTML('beforeend', option);
            });
        })
        .catch(error => console.error("Error loading branches:", error));
    }

    function loadMaterials() {
        fetch("/MaterailManegmentT/materials/getMaterials")
        .then(response => response.json())
        .then(materials => {
            const tbody = document.getElementById("materialsTableBody");
            tbody.innerHTML = "";
            
            if (materials.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="p-3 text-center">لا توجد مواد مسجلة</td>
                    </tr>
                `;
                return;
            }
            
            materials.forEach(material => {
                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50">
                        <td class="p-3">${material.code || ''}</td>
                        <td class="p-3">${material.name || ''}</td>
                        <td class="p-3">${material.size || ''}</td>
                        <td class="p-3">${material.unit || ''}</td>
                        <td class="p-3">${material.quantity || ''}</td>
                        <td class="p-3">${material.branch_name || ''}</td>
                        <td class="p-3">${material.supplier_name || '-'}</td>
                        <td class="p-3">${material.updated_at || ''}</td>
                        <td class="p-3">
                            <button type="button" class="edit-btn bg-yellow-500 text-white px-3 py-1 rounded"
                                    data-id="${material.id}"
                                    data-code="${material.code || ''}"
                                    data-name="${material.name || ''}"
                                    data-size="${material.size || ''}"
                                    data-unit="${material.unit || ''}"
                                    data-quantity="${material.quantity || ''}"
                                    data-branch="${material.branch_id || ''}"
                                    data-supplier="${material.supplier_id || ''}">
                                تعديل
                            </button>
                            <button type="button" class="delete-btn bg-red-500 text-white px-3 py-1 rounded"
                                    data-id="${material.id}">
                                حذف
                            </button>
                        </td>
                    </tr>
                `;
            });

            addButtonEvents();
        })
        .catch(error => console.error("Error loading materials:", error));
    }

    function addButtonEvents() {
        // أحداث أزرار التعديل
        document.querySelectorAll(".edit-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                try {
                    const data = this.dataset;
                    document.getElementById("editMaterialId").value = data.id;
                    document.getElementById("editCode").value = data.code;
                    document.getElementById("editName").value = data.name;
                    document.getElementById("editSize").value = data.size;
                    document.getElementById("editUnit").value = data.unit;
                    document.getElementById("editQuantity").value = data.quantity;
                    document.getElementById("editBranchId").value = data.branch;
                    document.getElementById("editSupplierId").value = data.supplier;
                    editModal.classList.remove("hidden");
                } catch (error) {
                    console.error("Error setting form values:", error);
                }
            });
        });

        // أحداث أزرار الحذف
        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                if (confirm("هل أنت متأكد من حذف هذه المادة؟")) {
                    const id = this.dataset.id;
                    fetch(`/MaterailManegmentT/materials/delete?id=${id}`)
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

    // إغلاق المودال
    document.getElementById("closeModal").addEventListener("click", function() {
        editModal.classList.add("hidden");
    });

    // تحديث المادة
    editForm.addEventListener("submit", function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch("/MaterailManegmentT/public/index.php?controller=material&action=update", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                editModal.classList.add("hidden");
                loadMaterials();
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء تحديث المادة");
        });
    });
});