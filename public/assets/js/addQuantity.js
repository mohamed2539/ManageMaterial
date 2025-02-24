document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchMaterial");
    const searchResult = document.getElementById("searchResult");
    const materialDetails = document.getElementById("materialDetails");
    const quantityInput = document.getElementById("quantityInput");
    const addQuantityBtn = document.getElementById("addQuantityBtn");
    const successMessage = document.getElementById("successMessage");

    searchInput.addEventListener("input", function () {
        const query = searchInput.value.trim();
        if (query.length > 2) {
            fetch(`index.php?controller=material&action=liveSearch&query=${query}`)
                .then(response => response.json())
                .then(data => {
                    searchResult.innerHTML = data.map(material => `
                        <div class="cursor-pointer p-2 bg-gray-100 hover:bg-gray-200 border-b selectMaterial" 
                            data-id="${material.id}" 
                            data-code="${material.code}" 
                            data-name="${material.name}" 
                            data-quantity="${material.quantity}">
                            ${material.name} (${material.code}) - الكمية: ${material.quantity}
                        </div>
                    `).join('');
                });
        } else {
            searchResult.innerHTML = "";
            materialDetails.classList.add("hidden");
        }
    });

    searchResult.addEventListener("click", function (e) {
        if (e.target.classList.contains("selectMaterial")) {
            const material = e.target.dataset;
            document.getElementById("materialName").innerText = material.name;
            document.getElementById("materialCode").innerText = material.code;
            document.getElementById("materialQuantity").innerText = material.quantity;
            materialDetails.classList.remove("hidden");
        }
    });

    addQuantityBtn.addEventListener("click", function () {
        const newQuantity = parseInt(quantityInput.value, 10);
        const materialCode = document.getElementById("materialCode").innerText;
        if (newQuantity > 0 && materialCode) {
            let formData = new FormData();
            formData.append("materialCode", materialCode);
            formData.append("newQuantity", newQuantity);

            fetch("index.php?controller=material&action=addQuantity", {
                method: "POST",
                body: formData
            })

                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        successMessage.classList.remove("hidden");
                        document.getElementById("materialQuantity").innerText =
                            parseInt(document.getElementById("materialQuantity").innerText, 10) + newQuantity;
                        quantityInput.value = "";
                        setTimeout(() => successMessage.classList.add("hidden"), 3000);
                    } else {
                        alert("خطأ في تحديث الكمية! تأكد من صحة البيانات.");
                    }
                });
        } else {
            alert("من فضلك أدخل كمية صالحة!");
        }
    });
});
