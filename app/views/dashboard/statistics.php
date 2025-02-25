<?php
// التعامل مع القيم الفارغة
$materialUsage = $materialUsage ?? [];
$branchDistribution = $branchDistribution ?? [];
$supplierActivity = $supplierActivity ?? [];

// التأكد من هيكل البيانات المتوقع
if (!isset($materialUsage['daily_average'])) {
    $materialUsage['daily_average'] = 0;
}
if (!isset($materialUsage['most_requested'])) {
    $materialUsage['most_requested'] = 'لا توجد بيانات';
}
if (!isset($materialUsage['data'])) {
    $materialUsage['data'] = [];
}

if (!isset($branchDistribution['most_active'])) {
    $branchDistribution['most_active'] = 'لا توجد بيانات';
}
if (!isset($branchDistribution['data'])) {
    $branchDistribution['data'] = [];
}
?>



<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الإحصائيات - لوحة التحكم</title>
    <link rel="stylesheet" href="/MaterailManegmentT/public/assets/css/tailwindStyle.css">
    <link rel="stylesheet" href="../../../public/assets/Css/fonts.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-2">معدل الاستهلاك اليومي</h3>
                <p class="text-3xl font-bold text-blue-600">
                    <?= number_format($materialUsage['daily_average'], 2) ?>
                </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-2">أكثر المواد طلباً</h3>
                <p class="text-3xl font-bold text-green-600">
                    <?= $materialUsage['most_requested'] ?>
                </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-2">أكثر الفروع نشاطاً</h3>
                <p class="text-3xl font-bold text-purple-600">
                    <?= $branchDistribution['most_active'] ?>
                </p>
            </div>
        </div>

        <!-- Detailed Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-4">استهلاك المواد (آخر 30 يوم)</h3>
                <canvas id="materialUsageChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl mb-4">توزيع المخزون حسب الفروع</h3>
                <canvas id="branchDistributionChart"></canvas>
            </div>
        </div>

        <!-- Supplier Activity -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h3 class="text-xl mb-4">نشاط الموردين</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">المورد</th>
                            <th class="px-4 py-2">عدد التوريدات</th>
                            <th class="px-4 py-2">إجمالي الكميات</th>
                            <th class="px-4 py-2">آخر توريد</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($supplierActivity)): ?>
                            <?php foreach ($supplierActivity as $supplier): ?>
                                <tr>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($supplier['supplier_name']) ?></td>
                                    <td class="border px-4 py-2"><?= $supplier['order_count'] ?></td>
                                    <td class="border px-4 py-2"><?= $supplier['total_spent'] ?></td>
                                    <td class="border px-4 py-2">
                                        <?= $supplier['last_order_date'] ? date('Y-m-d', strtotime($supplier['last_order_date'])) : 'لا يوجد' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="border px-4 py-2 text-center">لا توجد بيانات للموردين</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="/MaterailManegmentT/public/assets/js/statistics.js"></script>
</body>
</html>