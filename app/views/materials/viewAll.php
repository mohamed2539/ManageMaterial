<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materials Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Materials List</h1>

    <div class="mb-4">
        <input type="text" id="search" placeholder="Search materials..." class="w-full px-4 py-2 border rounded-lg shadow-sm">
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full table-auto border-collapse">
            <thead>
            <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                <th class="px-4 py-3 text-left">#</th>
                <th class="px-4 py-3 text-left">Material Name</th>
                <th class="px-4 py-3 text-left">Size</th>
                <th class="px-4 py-3 text-left">Unit</th>
                <th class="px-4 py-3 text-left">Quantity</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
            </thead>
            <tbody id="materials-table">
            <!-- Dynamic Data Will Be Loaded Here via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<script src="../../../public/assets/js/liveSearch.js"></script>
</body>
</html>
