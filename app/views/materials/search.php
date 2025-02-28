<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بحث المواد</title>
    <link rel="stylesheet" href="../../../public/assets/Css/fonts.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6">بحث المواد</h2>

        <!-- قسم البحث والفلترة -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <input type="text" 
                   id="searchInput" 
                   placeholder="ابحث عن المواد..." 
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <select id="supplierFilter" class="w-full p-2 border rounded">
                <option value="">كل الموردين</option>
            </select>

            <select id="branchFilter" class="w-full p-2 border rounded">
                <option value="">كل الفروع</option>
            </select>

            <select id="sizeFilter" class="w-full p-2 border rounded">
                <option value="">كل المقاسات</option>
            </select>
        </div>

        <!-- جدول النتائج -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">الكود</th>
                        <th class="p-2 border">اسم المادة</th>
                        <th class="p-2 border">المقاس</th>
                        <th class="p-2 border">الوحدة</th>
                        <th class="p-2 border">الكمية</th>
                        <th class="p-2 border">الفرع</th>
                        <th class="p-2 border">المورد</th>
                        <th class="p-2 border">آخر تحديث</th>
                    </tr>
                </thead>
                <tbody id="searchResults">
                    <!-- النتائج ستظهر هنا -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="../../../public/assets/js/search.js"></script>
</body>
</html>

