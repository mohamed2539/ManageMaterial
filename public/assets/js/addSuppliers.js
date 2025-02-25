document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addSupplierForm");
    const editModal = document.getElementById("editModal");
    const closeModal = document.getElementById("closeModal");
    const editForm = document.getElementById("editSupplierForm");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(form);

        fetch("/MaterailManegmentT/public/index.php?controller=supplier&action=store", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    loadSuppliers();
                    form.reset();
                }
            })
            .catch(error => console.error("Error:", error));
    });


    function loadSuppliers() {
        fetch("/MaterailManegmentT/public/index.php?controller=supplier&action=getSuppliers")
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector("tbody");
                tbody.innerHTML = "";

                data.forEach(supplier => {
                    tbody.innerHTML += `
                    <tr class="border-t">
                        <td class="p-2">${supplier.id}</td>
                        <td class="p-2">${supplier.name}</td>
                        <td class="p-2">${supplier.phone}</td>
                        <td class="p-2">${supplier.email}</td>
                        <td class="p-2">${supplier.address}</td>
                        <td class="p-2">${supplier.created_by}</td>
                        <td class="p-2">${supplier.created_at}</td>
                        <td class="p-2">
                            <button class="bg-yellow-500 text-white px-2 py-1 rounded edit-btn" 
                                data-id="${supplier.id}" data-name="${supplier.name}" 
                                data-address="${supplier.address}" data-phone="${supplier.phone}" 
                                data-email="${supplier.email}">
                                تعديل
                            </button>
                        </td>
                        <td class="p-2">
                            <button class="bg-red-500 text-white px-2 py-1 rounded delete-btn" 
                                data-id="${supplier.id}">
                                حذف
                            </button>
                        </td>
                    </tr>
                    `;
                });

                attachEventListeners(); // ✅ إعادة ربط الأحداث بعد تحديث الجدول
            })
            .catch(error => console.error("Error:", error));
    }

    loadSuppliers();

    function attachEventListeners() {
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("editSupplierId").value = this.dataset.id;
                document.getElementById("editName").value = this.dataset.name;
                document.getElementById("editPhone").value = this.dataset.phone;
                document.getElementById("editEmail").value = this.dataset.email;
                document.getElementById("editAddress").value = this.dataset.address;

                editModal.classList.remove("hidden");
            });
        });

        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                const id = this.dataset.id;
                if (confirm("هل أنت متأكد من الحذف؟")) {
                    fetch(`/MaterailManegmentT/public/index.php?controller=supplier&action=delete&id=${id}`, { method: "GET" })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            if (data.status === "success") {
                                loadSuppliers();
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        });
    }

    closeModal.addEventListener("click", function () {
        editModal.classList.add("hidden");
    });

    editForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData();
        formData.append("id", document.getElementById("editSupplierId").value);
        formData.append("name", document.getElementById("editName").value);
        formData.append("address", document.getElementById("editAddress").value);
        formData.append("phone", document.getElementById("editPhone").value);
        formData.append("email", document.getElementById("editEmail").value);
/*        formData.append("notes", document.getElementById("editNotes").value);*/

        fetch("/MaterailManegmentT/public/index.php?controller=supplier&action=update", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    editModal.classList.add("hidden");
                    loadSuppliers();
                }
            })
            .catch(error => console.error("Error:", error));
    });
});
