<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المواد</title>
    <link rel="stylesheet" href="/MaterailManegmentT/public/assets/Css/fonts.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-4">إدارة المواد</h2>

    <!-- نموذج إضافة مادة جديدة -->
    <form id="addMaterialForm" class="space-y-4 p-4 border rounded-lg shadow-md bg-white">
        <div class="grid grid-cols-3 gap-4">
            <input type="text" name="code" placeholder="كود المادة" class="w-full p-2 border rounded" required>
            <input type="text" name="name" placeholder="اسم المادة" class="w-full p-2 border rounded" required>
            <input type="text" name="size" placeholder="المقاس" class="w-full p-2 border rounded">
            <input type="text" name="unit" placeholder="الوحدة" class="w-full p-2 border rounded">
            <input type="number" name="quantity" placeholder="الكمية" class="w-full p-2 border rounded" required>
            
            <!-- قائمة الفروع -->
            <div class="relative">
                <select name="branch_id" id="branch_id" class="w-full p-2 border rounded" required>
                    <option value="">اختر الفرع</option>
                </select>
            </div>

            <!-- قائمة الموردين -->
            <div class="relative">
                <select name="supplier_id" id="supplier_id" class="w-full p-2 border rounded">
                    <option value="">اختر المورد (اختياري)</option>
                </select>
            </div>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            إضافة مادة
        </button>
    </form>

    <!-- جدول عرض المواد -->
    <div class="overflow-x-auto mt-6">
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
                <th class="p-2 border">الإجراءات</th>
            </tr>
            </thead>
            <tbody id="materialsTableBody">
            <?php if (!empty($materials)): ?>
                <?php foreach ($materials as $material) : ?>
                    <tr class="hover:bg-slate-50">
                        <td class="p-3"><?= htmlspecialchars($material['code']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($material['name']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($material['size']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($material['unit']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($material['quantity']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($material['branch_name']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($material['supplier_name'] ?? '-') ?></td>
                        <td class="p-3"><?= htmlspecialchars($material['updated_at']) ?></td>
                        <td class="p-3">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded edit-btn"
                                    data-id="<?= $material['id'] ?>"
                                    data-code="<?= htmlspecialchars($material['code']) ?>"
                                    data-name="<?= htmlspecialchars($material['name']) ?>"
                                    data-size="<?= htmlspecialchars($material['size']) ?>"
                                    data-unit="<?= htmlspecialchars($material['unit']) ?>"
                                    data-quantity="<?= htmlspecialchars($material['quantity']) ?>"
                                    data-branch="<?= htmlspecialchars($material['branch_id']) ?>"
                                    data-supplier="<?= htmlspecialchars($material['supplier_id'] ?? '') ?>">
                                تعديل
                            </button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded delete-btn"
                                    data-id="<?= $material['id'] ?>">
                                حذف
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" class="p-3 text-center">لا توجد مواد مسجلة</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- مودال تعديل المادة -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">تعديل المادة</h3>
            <form id="editMaterialForm" class="mt-4">
                <input type="hidden" id="editMaterialId" name="id">
                <div class="space-y-4">
                    <input type="text" id="editCode" name="code" placeholder="كود المادة" class="w-full p-2 border rounded" required>
                    <input type="text" id="editName" name="name" placeholder="اسم المادة" class="w-full p-2 border rounded" required>
                    <input type="text" id="editSize" name="size" placeholder="المقاس" class="w-full p-2 border rounded">
                    <input type="text" id="editUnit" name="unit" placeholder="الوحدة" class="w-full p-2 border rounded">
                    <input type="number" id="editQuantity" name="quantity" placeholder="الكمية" class="w-full p-2 border rounded" required>
                    <select id="editBranchId" name="branch_id" class="w-full p-2 border rounded" required>
                        <option value="">اختر الفرع</option>
                    </select>
                    <select id="editSupplierId" name="supplier_id" class="w-full p-2 border rounded">
                        <option value="">اختر المورد (اختياري)</option>
                    </select>
                </div>
                <div class="flex justify-between mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded">إغلاق</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/MaterailManegmentT/public/assets/js/addMaterial.js"></script>
</body>
</html> 