<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة كمية</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl">
    <h2 class="text-center text-2xl font-bold mb-4">إضافة كمية إلى المادة</h2>

    <div class="mb-4">
        <label for="searchInput" class="block text-gray-700 font-medium">ابحث عن المادة بالكود أو الاسم:</label>
        <input type="text" id="searchInput" class="mt-2 w-full p-2 border rounded-lg" placeholder="أدخل كود المادة أو اسمها">
        <div id="searchResult" class="mt-3"></div>
    </div>

    <div id="materialDetails" class="hidden bg-gray-50 p-4 rounded-lg shadow-md">
        <h5 class="text-lg font-semibold" id="materialName"></h5>
        <p class="text-sm text-gray-600"><strong>الكود:</strong> <span id="materialCode"></span></p>
        <p class="text-sm text-gray-600"><strong>الكمية الحالية:</strong> <span id="materialQuantity"></span></p>

        <div class="mt-3">
            <label for="quantityInput" class="block text-gray-700 font-medium">أدخل الكمية الجديدة:</label>
            <input type="number" id="quantityInput" class="mt-2 w-full p-2 border rounded-lg" min="1">
            <button id="addQuantityBtn" class="mt-3 w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">إضافة الكمية</button>
        </div>
    </div>

    <div id="successMessage" class="hidden mt-4 p-3 text-green-700 bg-green-200 border border-green-400 rounded-lg text-center">
        تم تحديث الكمية بنجاح!
    </div>
</div>


<script src="../../../public/assets/js/addQuantity.js" defer></script>
</body>
</html>
