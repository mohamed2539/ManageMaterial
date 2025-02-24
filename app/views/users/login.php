<!DOCTYPE html><html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/assets/js/login.js" defer></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="w-full max-w-sm bg-white p-6 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold text-center mb-4">تسجيل الدخول</h1>
        <form id="loginForm">
            <input type="text" name="username" placeholder="اسم المستخدم" required class="w-full p-2 border rounded mb-4">
            <input type="password" name="password" placeholder="كلمة المرور" required class="w-full p-2 border rounded mb-4">
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">دخول</button>
            <p id="loginResult" class="text-center mt-4"></p>
        </form>
    </div>
</body>
</html>// assets/js/login.js document.addEventListener('DOMContentLoaded', () => { document.getElementById('loginForm').addEventListener('submit', function(event) { event.preventDefault(); const formData = new FormData(this); formData.append('action', 'login');
