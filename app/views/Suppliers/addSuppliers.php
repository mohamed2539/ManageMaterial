<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الموردين</title>
    <link rel="stylesheet" href="../../../public/assets/Css/fonts.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-4">إدارة الموردين</h2>

    <!-- ✅ نموذج إضافة مورد جديد -->
    <form id="addSupplierForm" class="space-y-4 p-4 border rounded-lg shadow-md bg-white">
        <div class="grid grid-cols-2 gap-4">
            <input type="text" name="name" placeholder="اسم المورد" class="w-full p-2 border" required>
            <input type="text" name="address" placeholder="عنوان المورد" class="p-2 border rounded">
            <input type="text" name="phone" placeholder="رقم الهاتف" class="p-2 border rounded">
            <input type="email" name="email" placeholder="البريد الإلكتروني" class="p-2 border rounded">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded hover:bg-blue-600 transition">
            إضافة مورد
        </button>
    </form>

    <!-- ✅ جدول عرض الموردين -->
    <div class="overflow-x-auto">
        <table class="w-full mt-4 border-collapse border border-gray-300">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">المسلسل</th>
                <th class="p-2 border">الاسم</th>
                <th class="p-2 border">الهاتف</th>
                <th class="p-2 border">البريد الإلكتروني</th>
                <th class="p-2 border">العنوان</th>
                <th class="p-2 border">تمت الإضافة بواسطة</th>
                <th class="p-2 border">وقت ألاضافة</th>
                <th class="p-2 border">تعديل</th>
                <th class="p-2 border">حذف</th>
            </tr>
            </thead>
            <tbody id="suppliersTableBody">
            <?php if (!empty($suppliers)): ?>
                <?php foreach ($suppliers as $supplier) : ?>
                    <tr class="hover:bg-slate-50">
                        <td class="p-3"><?= htmlspecialchars($supplier['id']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($supplier['name']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($supplier['phone']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($supplier['email']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($supplier['address']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($supplier['created_by']) ?></td>
                        <td class="p-3">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded edit-btn"
                                    data-id="<?= $supplier['id'] ?>"
                                    data-name="<?= htmlspecialchars($supplier['name']) ?>"
                                    data-address="<?= htmlspecialchars($supplier['address']) ?>"
                                    data-phone="<?= htmlspecialchars($supplier['phone']) ?>"
                                    data-email="<?= htmlspecialchars($supplier['email']) ?>"
                                    data-notes="<?= htmlspecialchars($supplier['notes']) ?>">
                                تعديل
                            </button>
                        </td>
                        <td class=" p-4 border-b border-slate-200 py-5">
                            <button class="bg-red-500 text-white px-3 py-1 rounded delete-btn"
                                    data-id="<?= $supplier['id'] ?>">
                                حذف
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" class="p-3 text-center  border-b border-slate-200 py-5">لا توجد بيانات</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ مودال تعديل المورد -->
<div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h3 class="text-xl font-bold mb-4">تعديل المورد</h3>
        <form id="editSupplierForm">
            <input type="hidden" id="editSupplierId">
            <input type="text" id="editName" placeholder="اسم المورد" class="w-full p-2 border rounded mb-2">
            <input type="text" id="editAddress" placeholder="العنوان" class="w-full p-2 border rounded mb-2">
            <input type="text" id="editPhone" placeholder="رقم الهاتف" class="w-full p-2 border rounded mb-2">
            <input type="email" id="editEmail" placeholder="البريد الإلكتروني" class="w-full p-2 border rounded mb-2">
            <div class="flex justify-between">
                <button type="button" id="closeModal" class="bg-gray-400 text-white px-4 py-2 rounded">إغلاق</button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>

    <script src="../../../public/assets/js/addSuppliers.js"></script>
</body>
</html>
