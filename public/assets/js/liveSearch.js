document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const searchResults = document.getElementById("searchResults");

    searchInput.addEventListener("input", function () {
        const query = searchInput.value.trim();
        if (query.length > 2) {
            fetch(`index.php?controller=liveSearch&action=liveSearch&query=${query}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        searchResults.innerHTML = data.map(material => `
                            <div class='p-2 border-b cursor-pointer hover:bg-gray-200' data-id="${material.id}">
                                ${material.name} (${material.code}) - الكمية: ${material.quantity}
                            </div>
                        `).join('');
                        searchResults.classList.remove("hidden");
                    } else {
                        searchResults.innerHTML = "<p class='p-2 text-gray-500'>لا توجد نتائج</p>";
                        searchResults.classList.remove("hidden");
                    }
                });
        } else {
            searchResults.innerHTML = "";
            searchResults.classList.add("hidden");
        }
    });
});
