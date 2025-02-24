<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة فرع</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">إضافة فرع جديد</h2>
    <form id="addBranchForm" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <input type="text" name="name" placeholder="اسم الفرع" required class="border p-2 rounded">
            <input type="text" name="address" placeholder="العنوان" class="border p-2 rounded">
            <input type="text" name="phone" placeholder="رقم الهاتف" class="border p-2 rounded">
            <input type="email" name="email" placeholder="البريد الإلكتروني" class="border p-2 rounded">
            <input type="text" name="manager_name" placeholder="اسم المدير" class="border p-2 rounded">
            <textarea name="notes" placeholder="ملاحظات" class="border p-2 rounded"></textarea>
        </div>
        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">إضافة الفرع</button>
    </form>
</div>

<div class="max-w-2xl mx-auto mt-6">
    <h3 class="text-lg font-semibold mb-3">آخر الفروع المضافة</h3>

    <table class="w-full border-collapse shadow-lg rounded-lg overflow-hidden">
        <thead>
        <tr class="bg-gray-800 text-white">
            <th class="p-3">ID</th>
            <th class="p-3">الاسم</th>
            <th class="p-3">العنوان</th>
            <th class="p-3">الهاتف</th>
            <th class="p-3">الإيميل</th>
            <th class="p-3">المدير</th>
            <th class="p-3">ملاحظات</th>
            <th class="p-3">تعديل</th>
            <th class="p-3">حذف</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($branches)): ?>
        <?php foreach ($branches as $branch) : ?>
            <tr class="border-b border-gray-300 hover:bg-gray-100 transition duration-300">
                <td class="p-3 text-center"><?= htmlspecialchars($branch['id']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($branch['name']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($branch['address']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($branch['phone']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($branch['email']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($branch['manager_name']) ?></td>
                <td class="p-3 text-center"><?= htmlspecialchars($branch['notes']) ?></td>
                <td class="p-3 text-center">
                    <a href="../../../public/index.php?controller=branch&action=edit&id=<?= htmlspecialchars($branch['id']) ?>"
                       class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-300 shadow-md">
                        تعديل
                    </a>
                </td>
                <td class="p-3 text-center">
                    <a href="../../../public/index.php?controller=branch&action=delete&id=<?= htmlspecialchars($branch['id']) ?>"
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








    <!-------------------------------------------editModal CODE---------------------------------------------------------->
    <!----------------------------------------------------------------------------------------------------->
    <!-- المودال (Hidden by default) -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white p-5 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">تعديل الفرع</h2>
            <form id="editBranchForm">
                <input type="hidden" id="editBranchId">
                <label class="block mb-2">الاسم:</label>
                <input type="text" id="editName" class="w-full border p-2 rounded mb-2" required>

                <label class="block mb-2">العنوان:</label>
                <input type="text" id="editAddress" class="w-full border p-2 rounded mb-2" required>

                <label class="block mb-2">رقم الهاتف:</label>
                <input type="text" id="editPhone" class="w-full border p-2 rounded mb-2" required>

                <label class="block mb-2">الإيميل:</label>
                <input type="email" id="editEmail" class="w-full border p-2 rounded mb-2" required>

                <label class="block mb-2">اسم المدير:</label>
                <input type="text" id="editManager" class="w-full border p-2 rounded mb-2" required>

                <label class="block mb-2">ملاحظات:</label>
                <textarea id="editNotes" class="w-full border p-2 rounded mb-2"></textarea>

                <div class="flex justify-end mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">إلغاء</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

    <!----------------------------------------------------------------------------------------------------->
    <!----------------------------------------------------------------------------------------------------->






</div>


<script src="../../../public/assets/js/addBranch.js"></script>
</body>
</html>
