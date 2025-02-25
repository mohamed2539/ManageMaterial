<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين</title>
    <link rel="stylesheet" href="../../../public/assets/Css/fonts.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">إدارة المستخدمين</h2>
                <button onclick="showCreateModal()" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    إضافة مستخدم جديد
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-right">اسم المستخدم</th>
                            <th class="p-3 text-right">الاسم الكامل</th>
                            <th class="p-3 text-right">الفرع</th>
                            <th class="p-3 text-right">الصلاحية</th>
                            <th class="p-3 text-right">الحالة</th>
                            <th class="p-3 text-right">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($user['full_name']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($user['branch_name']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($user['role']) ?></td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-sm <?= $user['status'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $user['status'] ? 'نشط' : 'غير نشط' ?>
                                    </span>
                                </td>
                                <td class="p-3">
                                    <button onclick="editUser(<?= $user['id'] ?>)" 
                                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        تعديل
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
    </div>

    <!-- مودال إضافة/تعديل مستخدم -->
    <div id="userModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 id="modalTitle" class="text-xl font-bold mb-4">إضافة مستخدم جديد</h3>
            <form id="userForm" class="space-y-4">
                <input type="hidden" id="userId" name="id">
                
                <div>
                    <label class="block text-gray-700 mb-2">اسم المستخدم</label>
                    <input type="text" id="username" name="username" required 
                           class="w-full p-2 border rounded">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">كلمة المرور</label>
                    <input type="password" id="password" name="password" 
                           class="w-full p-2 border rounded">
                    <small class="text-gray-500">اتركها فارغة للإبقاء على كلمة المرور الحالية</small>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">الاسم الكامل</label>
                    <input type="text" id="full_name" name="full_name" required 
                           class="w-full p-2 border rounded">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">الفرع</label>
                    <select id="branch_id" name="branch_id" required 
                            class="w-full p-2 border rounded">
                        <!-- سيتم تحميل الفروع بواسطة JavaScript -->
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">الصلاحية</label>
                    <select id="role" name="role" required class="w-full p-2 border rounded">
                        <option value="admin">مدير النظام</option>
                        <option value="branch_manager">مدير فرع</option>
                        <option value="user">مستخدم عادي</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">الحالة</label>
                    <select id="status" name="status" class="w-full p-2 border rounded">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" 
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../../public/assets/js/users.js"></script>
</body>
</html>