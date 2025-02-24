document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const editModal = document.getElementById("editModal");
    const closeModal = document.getElementById("closeModal");
    const editForm = document.getElementById("editBranchForm");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // ❌ منع إعادة تحميل الصفحة

        const formData = new FormData(form);

        fetch("/MaterailManegmentT/public/index.php?controller=branch&action=store", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert(data.message); // ✅ نجاح العملية
                    loadBranches(); // 🔄 تحديث الجدول بعد الإضافة
                    form.reset(); // 🔄 مسح البيانات بعد الإضافة
                } else {
                    alert("خطأ: " + data.message); // ❌ فشل الإضافة
                }
            })
            .catch(error => console.error("Error:", error));
    });

    // ✅ وظيفة جلب الفروع وعرضها في الجدول بدون إعادة تحميل
    function loadBranches() {
        fetch("/MaterailManegmentT/public/index.php?controller=branch&action=getBranches")
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector("tbody");
                tbody.innerHTML = ""; // 🔄 تنظيف الجدول قبل إعادة تحميله

                data.forEach(branch => {
                    tbody.innerHTML += `
                    <tr class="border-t">
                        <td class="p-2">${branch.id}</td>
                        <td class="p-2">${branch.name}</td>
                        <td class="p-2">${branch.address}</td>
                        <td class="p-2">${branch.phone}</td>
                        <td class="p-2">${branch.email}</td>
                        <td class="p-2">${branch.manager_name}</td>
                        <td class="p-2">${branch.notes}</td>
                        <td class="p-2">
                  <button class="bg-yellow-500 text-white px-2 py-1 rounded edit-btn" data-id="${branch.id}" 
                        data-name="${branch.name}" data-address="${branch.address}" 
                        data-phone="${branch.phone}" data-email="${branch.email}" 
                        data-manager="${branch.manager_name}" data-notes="${branch.notes}">
                        تعديل
                    </button></td>
                            <td>
                            <button class="bg-red-500 text-white px-2 py-1 rounded delete-btn" data-id="${branch.id}">
                            حذف</button>
                        </td>
                    </tr>
                `;
                });

                // ✅ إضافة أحداث للحذف بعد تحديث الجدول
                addDeleteEvent();
            })
            .catch(error => console.error("Error:", error));
    }

    // ✅ تحميل البيانات عند تحميل الصفحة
    loadBranches();



    /*============================================================================================*/
    /*============================================================================================*/
    /*============================================================================================*/

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("edit-btn")) {
            const button = event.target;
            document.getElementById("editBranchId").value = button.dataset.id;
            document.getElementById("editName").value = button.dataset.name;
            document.getElementById("editAddress").value = button.dataset.address;
            document.getElementById("editPhone").value = button.dataset.phone;
            document.getElementById("editEmail").value = button.dataset.email;
            document.getElementById("editManager").value = button.dataset.manager;
            document.getElementById("editNotes").value = button.dataset.notes;

            editModal.classList.remove("hidden"); // ✅ إظهار المودال
        }
    });

    // عند الضغط على زر إغلاق المودال
    closeModal.addEventListener("click", function () {
        editModal.classList.add("hidden"); // ✅ إخفاء المودال
    });

    // إرسال النموذج عبر AJAX لتحديث الفرع
    editForm.addEventListener("submit", function (event) {
        event.preventDefault();
        console.log("🔹 تم الضغط على زر حفظ التعديلات!");
        const formData = new FormData();
        formData.append("id", document.getElementById("editBranchId").value);
        formData.append("name", document.getElementById("editName").value);
        formData.append("address", document.getElementById("editAddress").value);
        formData.append("phone", document.getElementById("editPhone").value);
        formData.append("email", document.getElementById("editEmail").value);
        formData.append("manager_name", document.getElementById("editManager").value);
        formData.append("notes", document.getElementById("editNotes").value);

        fetch("/MaterailManegmentT/public/index.php?controller=branch&action=update", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    editModal.classList.add("hidden"); // ✅ إغلاق المودال
                    loadBranches(); // 🔄 تحديث الجدول
                }
            })
            .catch(error => console.error("Error:", error));
    });




    /*============================================================================================*/
    /*============================================================================================*/
    /*============================================================================================*/





    // ✅ وظيفة حذف فرع
    function addDeleteEvent() {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                const id = this.dataset.id;

                if (confirm("هل أنت متأكد من الحذف؟")) {
                    fetch(`/MaterailManegmentT/public/index.php?controller=branch&action=delete&id=${id}`, { method: "GET" })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            loadBranches(); // 🔄 إعادة تحميل القائمة بعد الحذف
                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        });
    }
});
