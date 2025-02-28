<!-- في ملف layout.php -->
<!DOCTYPE html>
<html>
<head>
    <!-- ... العناصر الأخرى ... -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/assets/Css/layout.css">
    <link rel="stylesheet" href="../../../public/assets/Css/fonts.css">
</head>
<body>

<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="/MaterailManegmentT/public/index.php?controller=dashboard&action=index" class="text-white font-bold">Dashboard</a>
        <div>
            <a href="/MaterailManegmentT/public/index.php?controller=dashboard&action=statistics" class="text-white hover:underline">Statistics</a>
            <a href="/MaterailManegmentT/public/index.php?controller=materials&action=AddBranch" class="text-white hover:underline">Add Branch</a>
            <a href="/MaterailManegmentT/public/index.php?controller=materials&action=addMaterial" class="text-white hover:underline">Add Material</a>
            <a href="/MaterailManegmentT/public/index.php?controller=materials&action=addQuantity" class="text-white hover:underline">Add Quantity</a>
            <a href="/MaterailManegmentT/public/index.php?controller=materials&action=dispenseMaterial" class="text-white hover:underline">Dispense Material</a>
            <a href="/MaterailManegmentT/public/index.php?controller=materials&action=liveSearch" class="text-white hover:underline">Live Search</a>
            <a href="/MaterailManegmentT/public/index.php?controller=materials&action=viewAll" class="text-white hover:underline">View All</a>
            <a href="/MaterailManegmentT/public/index.php?controller=Suppliers&action=addSuppliers" class="text-white hover:underline">Add Suppliers</a>
            <a href="/MaterailManegmentT/public/index.php?controller=Suppliers&action=supplierList" class="text-white hover:underline">Supplier List</a>
            <a href="/MaterailManegmentT/public/index.php?controller=users&action=createUser" class="text-white hover:underline">Create User</a>
            <a href="/MaterailManegmentT/public/index.php?controller=users&action=index" class="text-white hover:underline">User Index</a>
            <a href="/MaterailManegmentT/public/index.php?controller=users&action=login" class="text-white hover:underline">Login</a>
            <!-- ... الروابط الأخرى ... -->
        </div>
    </div>
</nav>

<!-- ... العناصر الأخرى ... -->

</body>
</html>