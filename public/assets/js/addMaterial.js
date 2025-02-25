document.addEventListener("DOMContentLoaded", function () {
    loadBranches();
    loadSuppliers();

    const form = document.getElementById("addMaterialForm");
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        
        const formData = new FormData(form);
        
        fetch("/MaterailManegmentT/public/index.php?controller=material&action=store", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const responseMessage = document.getElementById("responseMessage");
            if (data.status === "success") {
                responseMessage.textContent = data.message;
                responseMessage.classList.remove("text-red-500");
                responseMessage.classList.add("text-green-500");
                form.reset();
            } else {
                responseMessage.textContent = "خطأ: " + data.message;
                responseMessage.classList.add("text-red-500");
            }
        })
        .catch(error => console.error("Error:", error));
    });
});

function loadBranches() {
    fetch("/MaterailManegmentT/public/index.php?controller=branch&action=getAll")
        .then(response => response.json())
        .then(data => {
            const branchSelect = document.getElementById("branch_id");
            branchSelect.innerHTML = "<option value=''>اختر الفرع</option>";
            data.forEach(branch => {
                const option = document.createElement("option");
                option.value = branch.id;
                option.textContent = branch.name;
                branchSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error loading branches:", error));
}

function loadSuppliers() {
    fetch("/MaterailManegmentT/public/index.php?controller=supplier&action=getAll")
        .then(response => response.json())
        .then(data => {
            const supplierSelect = document.createElement("select");
            supplierSelect.id = "supplier_id";
            supplierSelect.name = "supplier_id";
            supplierSelect.classList.add("w-full", "p-2", "border", "rounded", "mt-2");
            
            supplierSelect.innerHTML = "<option value=''>اختر المورد (اختياري)</option>";
            data.forEach(supplier => {
                const option = document.createElement("option");
                option.value = supplier.id;
                option.textContent = supplier.name;
                supplierSelect.appendChild(option);
            });
            
            const form = document.getElementById("addMaterialForm");
            form.insertBefore(supplierSelect, form.children[form.children.length - 2]);
        })
        .catch(error => console.error("Error loading suppliers:", error));
}
