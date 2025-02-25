<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة المواد</title>
    <link rel="stylesheet" href="../../../public/assets/Css/fonts.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center">تسجيل الدخول</h2>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/MaterailManegmentT/public/index.php?controller=auth&action=login" 
                  class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">اسم المستخدم</label>
                    <input type="text" name="username" required 
                           class="w-full p-3 border rounded focus:outline-none focus:border-blue-500"
                           placeholder="أدخل اسم المستخدم">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">كلمة المرور</label>
                    <input type="password" name="password" required 
                           class="w-full p-3 border rounded focus:outline-none focus:border-blue-500"
                           placeholder="أدخل كلمة المرور">
                </div>

                <button type="submit" 
                        class="w-full bg-blue-500 text-white py-3 rounded hover:bg-blue-600 
                               transition duration-300 font-bold">
                    دخول
                </button>
            </form>
        </div>
    </div>
</body>
</html>