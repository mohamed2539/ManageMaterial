<?php
session_start();

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صرف المواد</title>
    <link rel="stylesheet" href="../../../public/assets/Css/fonts.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">صرف المواد</h2>

    <form id="issueItemForm" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <!-- حقل إدخال الكود -->
            <div class="col-span-1">
                <label class="block text-gray-700 mb-1">كود المادة</label>
                <input type="text" id="itemCode" name="code" class="w-full border p-2 rounded focus:border-blue-500" required>
            </div>

            <!-- حقل الكمية -->
            <div class="col-span-1">
                <label class="block text-gray-700 mb-1">الكمية</label>
                <input type="number" id="quantity" name="quantity" class="w-full border p-2 rounded focus:border-blue-500" required min="1" disabled>
            </div>

            <!-- معلومات المادة -->
            <div id="materialInfo" class="col-span-2 bg-gray-50 p-4 rounded-lg hidden">
                <h3 class="text-lg font-semibold mb-2">معلومات المادة</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p><span class="font-medium">اسم المادة:</span> <span id="materialName"></span></p>
                        <p><span class="font-medium">المقاس:</span> <span id="materialSize"></span></p>
                        <p><span class="font-medium">الوحدة:</span> <span id="materialUnit"></span></p>
                    </div>
                    <div>
                        <p><span class="font-medium">الفرع:</span> <span id="branchName"></span></p>
                        <p><span class="font-medium">الكمية المتاحة:</span> <span id="availableQuantity"></span></p>
                        <p><span class="font-medium">الحد الأدنى:</span> <span id="minQuantity"></span></p>
                    </div>
                </div>
                <input type="hidden" id="materialId" name="material_id">
                <input type="hidden" id="branchId" name="branch_id">
                <input type="hidden" id="userId" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
            </div>

            <!-- حقل الملاحظات -->
            <div class="col-span-2">
                <label class="block text-gray-700 mb-1">ملاحظات</label>
                <textarea name="notes" class="w-full border p-2 rounded" rows="2"></textarea>
            </div>
        </div>

        <button type="submit" id="issueButton" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition-colors disabled:bg-gray-400" disabled>
            صرف المادة
        </button>
    </form>
</div>

<!-- جدول العمليات -->
<div class="max-w-4xl mx-auto mt-6">
    <h3 class="text-lg font-semibold mb-3">آخر عمليات الصرف</h3>
    <table class="w-full border-collapse shadow-lg rounded-lg overflow-hidden">
        <thead>
        <tr class="bg-gray-800 text-white">
            <th class="p-3">كود العملية</th>
            <th class="p-3">المادة</th>
            <th class="p-3">الكمية</th>
            <th class="p-3">الفرع</th>
            <th class="p-3">المستخدم</th>
            <th class="p-3">التاريخ</th>
            <th class="p-3">ملاحظات</th>
            <th class="p-3">تعديل</th>
            <th class="p-3">حذف</th>
        </tr>
        </thead>
        <tbody id="transactionsTable">

        <?php if (!empty($recentTransactions)): ?>
        <?php foreach ($recentTransactions as $recentTransaction) : ?>
            <tr class="border-b border-gray-300 hover:bg-gray-100 transition duration-300">
                <td class="p-3 text-center"><?= htmlspecialchars($recentTransaction['id']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($recentTransaction['material_id']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($recentTransaction['user_id']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($recentTransaction['branch_id']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($recentTransaction['transaction_type']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($recentTransaction['quantity']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($recentTransaction['transaction_date']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($recentTransaction['notes']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($recentTransaction['transaction_code']) ?></td>
                <td class="p-3 text-center">
                    <a href="../../../public/index.php?controller=branch&action=edit&id=<?= htmlspecialchars($recentTransaction['id']) ?>"
                       class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-300 shadow-md">
                        تعديل
                    </a>
                </td>
                <td class="p-3 text-center">
                    <a href="../../../public/index.php?controller=branch&action=delete&id=<?= htmlspecialchars($recentTransaction['id']) ?>"
                       class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-300 shadow-md"
                       onclick="return confirm('هل أنت متأكد من حذف هذا الفرع؟');">
                        حذف
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr><td colspan="7" class="p-2 text-center">لا توجد بيانات</td></tr>
        <?php endif; ?>








        </tbody>
    </table>
</div>

<!-- Modal التعديل -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-5 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">تعديل عملية الصرف</h2>
        <form id="editTransactionForm">
            <input type="hidden" id="editTransactionId">

            <!-- كود المادة - للعرض فقط -->
            <div class="mb-3">
                <label class="block mb-1">كود العملية:</label>
                <input type="text" id="editTransactionCode" class="w-full border p-2 rounded" readonly>
            </div>

            <!-- اسم المادة - للعرض فقط -->
            <div class="mb-3">
                <label class="block mb-1">المادة:</label>
                <input type="text" id="editMaterialName" class="w-full border p-2 rounded" readonly>
            </div>

            <!-- الكمية -->
            <div class="mb-3">
                <label class="block mb-1">الكمية:</label>
                <input type="number" id="editQuantity" class="w-full border p-2 rounded" required min="1">
                <small class="text-gray-500">الكمية المتاحة: <span id="editAvailableQuantity"></span></small>
            </div>

            <!-- الفرع - للعرض فقط -->
            <div class="mb-3">
                <label class="block mb-1">الفرع:</label>
                <input type="text" id="editBranchName" class="w-full border p-2 rounded" readonly>
            </div>

            <!-- الملاحظات -->
            <div class="mb-3">
                <label class="block mb-1">ملاحظات:</label>
                <textarea id="editNotes" class="w-full border p-2 rounded" rows="2"></textarea>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">إلغاء</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>

<script src="../../../public/assets/js/issueItem.js"></script>
</body>
</html>