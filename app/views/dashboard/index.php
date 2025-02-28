<?php
// التحقق من الصلاحيات
if (!isset($currentUser) || !in_array($currentUser['role'], ['admin', 'manager', 'user'])) {
    header('Location: /MaterailManegmentT/public/index.php?controller=auth&action=login');
    exit;
}
?>


<head>
    <link rel="stylesheet" href="">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<div class="space-y-6">
    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- إجمالي المواد -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">إجمالي المواد</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($totalMaterials) ?></h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- إجمالي الفروع -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">إجمالي الفروع</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($totalBranches) ?></h3>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- إجمالي الموردين -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">إجمالي الموردين</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($totalSuppliers) ?></h3>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- إجمالي المستخدمين -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">المستخدمين النشطين</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($totalUsers) ?></h3>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- المواد منخفضة المخزون والأنشطة الأخيرة -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- المواد منخفضة المخزون -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">المواد منخفضة المخزون</h2>
            </div>
            <div class="p-4">
                <?php if (empty($lowStockItems)): ?>
                    <p class="text-gray-500 text-center py-4">لا توجد مواد منخفضة المخزون حالياً</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($lowStockItems as $item): ?>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-800"><?= htmlspecialchars($item['name']) ?></h4>
                                    <p class="text-sm text-gray-500">
                                        الكمية الحالية: <?= number_format($item['quantity']) ?>
                                        (الحد الأدنى: <?= number_format($item['min_quantity']) ?>)
                                    </p>
                                </div>
                                <div class="<?= $item['quantity'] <= $item['min_quantity'] ? 'text-red-500' : 'text-yellow-500' ?>">
                                    <?= number_format($item['quantity']) ?> / <?= number_format($item['min_quantity']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- الأنشطة الأخيرة -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">آخر الأنشطة</h2>
            </div>
            <div class="p-4">
                <?php if (empty($recentActivities)): ?>
                    <p class="text-gray-500 text-center py-4">لا توجد أنشطة حديثة</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="flex items-start space-x-4 space-x-reverse">
                                <div class="p-2 rounded-full <?= $activity['type'] === 'RECEIVE' ? 'bg-green-100' : 'bg-blue-100' ?>">
                                    <svg class="w-5 h-5 <?= $activity['type'] === 'RECEIVE' ? 'text-green-600' : 'text-blue-600' ?>" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <?php if ($activity['type'] === 'RECEIVE'): ?>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        <?php else: ?>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        <?php endif; ?>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">
                                        <?= htmlspecialchars($activity['material_name']) ?>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <?= $activity['type'] === 'RECEIVE' ? 'توريد' : 'صرف' ?>
                                        <?= number_format($activity['quantity']) ?>
                                        <?= htmlspecialchars($activity['unit']) ?>
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        <?= date('Y-m-d H:i', strtotime($activity['transaction_date'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- إضافة Chart.js -->

<script src="/MaterailManegmentT/public/assets/js/dashboard.js"></script>
