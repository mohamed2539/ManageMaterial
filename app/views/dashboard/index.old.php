<?php
// التعامل مع القيم الفارغة
$totalMaterials = $totalMaterials ?? 0;
$totalBranches = $totalBranches ?? 0;
$totalSuppliers = $totalSuppliers ?? 0;
$totalUsers = $totalUsers ?? 0;
$recentActivities = $recentActivities ?? [];
$lowStockItems = $lowStockItems ?? [];
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="../../../public/assets/Css/fonts.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-2">إجمالي المواد</h3>
                <p class="text-3xl font-bold text-blue-600"><?= $totalMaterials ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-2">الفروع النشطة</h3>
                <p class="text-3xl font-bold text-green-600"><?= $totalBranches ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-2">الموردين</h3>
                <p class="text-3xl font-bold text-purple-600"><?= $totalSuppliers ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-2">المستخدمين النشطين</h3>
                <p class="text-3xl font-bold text-yellow-600"><?= $totalUsers ?></p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-4">توزيع المواد حسب الفروع</h3>
                <canvas id="branchDistributionChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-4">نشاط المخزون</h3>
                <canvas id="stockActivityChart"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h2 class="text-2xl mb-4">آخر النشاطات</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">النوع</th>
                            <th class="px-4 py-2">المادة</th>
                            <th class="px-4 py-2">الكمية</th>
                            <th class="px-4 py-2">الفرع</th>
                            <th class="px-4 py-2">المستخدم</th>
                            <th class="px-4 py-2">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentActivities)): ?>
                            <?php foreach ($recentActivities as $activity): ?>
                                <tr>
                                    <td class="border px-4 py-2">
                                        <?= $activity['type'] === 'dispense' ? 'صرف' : 'إضافة' ?>
                                    </td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($activity['material_name']) ?></td>
                                    <td class="border px-4 py-2"><?= $activity['quantity'] ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($activity['branch_name']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($activity['user_name']) ?></td>
                                    <td class="border px-4 py-2">
                                        <?= date('Y-m-d H:i', strtotime($activity['created_at'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="border px-4 py-2 text-center">لا توجد نشاطات حديثة</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl mb-4">تنبيهات المخزون</h2>
            <div id="stockAlerts">
                <?php if (!empty($lowStockItems)): ?>
                    <?php foreach ($lowStockItems as $item): ?>
                        <div class="bg-red-100 p-4 rounded mb-2">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold"><?= htmlspecialchars($item['name']) ?></h3>
                                <span class="text-red-600">نقص في المخزون</span>
                            </div>
                            <p class="mt-2">الكمية الحالية: <?= $item['quantity'] ?></p>
                            <p>الفرع: <?= htmlspecialchars($item['branch_name']) ?></p>
                            <p class="text-sm text-gray-600">الحد الأدنى: <?= $item['min_quantity'] ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-gray-600">لا توجد تنبيهات حالياً</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="/MaterailManegmentT/public/assets/js/dashboard.js"></script>
</body>
</html>