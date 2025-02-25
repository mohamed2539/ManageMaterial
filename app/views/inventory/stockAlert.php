<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تنبيهات المخزون</title>
    <link rel="stylesheet" href="/MaterailManegmentT/public/assets/css/tailwindStyle.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Low Stock Alerts -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h2 class="text-2xl mb-4 text-red-600">تنبيهات النقص في المخزون</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">المادة</th>
                            <th class="px-4 py-2">الفرع</th>
                            <th class="px-4 py-2">الكمية الحالية</th>
                            <th class="px-4 py-2">الحد الأدنى</th>
                            <th class="px-4 py-2">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStock as $item): ?>
                        <tr class="bg-red-50">
                            <td class="border px-4 py-2"><?= $item['name'] ?></td>
                            <td class="border px-4 py-2"><?= $item['branch_name'] ?></td>
                            <td class="border px-4 py-2"><?= $item['quantity'] ?></td>
                            <td class="border px-4 py-2"><?= $item['min_quantity'] ?></td>
                            <td class="border px-4 py-2">
                                <button onclick="createOrder(<?= $item['id'] ?>)" 
                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                    طلب توريد
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Over Stock Alerts -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl mb-4 text-yellow-600">تنبيهات الزيادة في المخزون</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">المادة</th>
                            <th class="px-4 py-2">الفرع</th>
                            <th class="px-4 py-2">الكمية الحالية</th>
                            <th class="px-4 py-2">الحد الأقصى</th>
                            <th class="px-4 py-2">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($overStock as $item): ?>
                        <tr class="bg-yellow-50">
                            <td class="border px-4 py-2"><?= $item['name'] ?></td>
                            <td class="border px-4 py-2"><?= $item['branch_name'] ?></td>
                            <td class="border px-4 py-2"><?= $item['quantity'] ?></td>
                            <td class="border px-4 py-2"><?= $item['max_quantity'] ?></td>
                            <td class="border px-4 py-2">
                                <button onclick="transferStock(<?= $item['id'] ?>)" 
                                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                    نقل مخزون
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="/MaterailManegmentT/public/assets/js/stockAlert.js"></script>
</body>
</html>