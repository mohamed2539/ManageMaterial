<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>التقارير - لوحة التحكم</title>
    <link rel="stylesheet" href="../../../public/assets/Css/fonts.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/MaterailManegmentT/public/assets/css/tailwindStyle.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Report Filters -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h2 class="text-2xl mb-4">تصفية التقارير</h2>
            <form id="reportFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">نوع التقرير</label>
                    <select name="report_type" class="w-full p-2 border rounded">
                        <option value="inventory">المخزون</option>
                        <option value="dispensing">الصرف</option>
                        <option value="activity">نشاط المستخدمين</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">من تاريخ</label>
                    <input type="date" name="start_date" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">إلى تاريخ</label>
                    <input type="date" name="end_date" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">الفرع</label>
                    <select name="branch_id" class="w-full p-2 border rounded">
                        <option value="">كل الفروع</option>
                        <!-- سيتم تعبئة الفروع عبر JavaScript -->
                    </select>
                </div>
                <div class="md:col-span-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        عرض التقرير
                    </button>
                    <button type="button" id="exportPDF" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 mr-2">
                        PDF تصدير
                    </button>
                    <button type="button" id="exportExcel" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mr-2">
                        Excel تصدير
                    </button>
                </div>
            </form>
        </div>

        <!-- Report Content -->
        <div id="reportContent" class="bg-white p-6 rounded-lg shadow">
            <!-- سيتم تحميل محتوى التقرير هنا -->
            <div id="reportTable" class="overflow-x-auto">
                <!-- جدول التقرير سيتم تحميله هنا -->
            </div>
        </div>
    </div>

    <script src="/MaterailManegmentT/public/assets/js/reports.js"></script>
</body>
</html>