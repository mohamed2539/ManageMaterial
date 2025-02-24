<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بحث مباشر</title>
    <link rel="stylesheet" href="public/assets/Css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="container mx-auto mt-5 p-4">
    <h2 class="text-xl font-bold mb-4 text-center text-gray-800">البحث عن المواد</h2>

    <div class="relative">
        <input
            type="text"
            id="searchInput"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300"
            placeholder="ابحث عن مادة..."
        >
        <div id="searchResult" class="absolute w-full bg-white shadow-lg mt-2 rounded-lg hidden"></div>
    </div>

    <div id="materialDetails" class="hidden mt-4 p-4 bg-gray-100 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-700">تفاصيل المادة</h3>
        <p id="materialName" class="text-gray-600 mt-2"></p>
        <p id="materialQuantity" class="text-gray-600"></p>
    </div>
</div>

<script src="../../../public/assets/js/liveSearch.js"></script>
<script src="../../../public/assets/lib/jquery.min.js"></script>
</body>
</html>