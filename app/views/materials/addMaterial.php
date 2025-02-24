<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Material</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">Add New Material</h2>
    <form id="addMaterialForm" class="mt-4">
        <div class="mb-4">
            <label class="block text-gray-700">Material Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Size:</label>
            <input type="text" id="size" name="size" class="w-full p-2 border rounded">
        </div>

        <div id="loading" class="hidden text-center mt-4">Loading branches...</div>
        <select id="branch_id" name="branch_id" class="w-full p-2 border rounded mt-2" required>
            <!-- Branches will be loaded dynamically -->
        </select>

        <div class="mb-4">
            <label class="block text-gray-700">Unit:</label>
            <input type="text" id="unit" name="unit" class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Add Material</button>
    </form>
    <p id="responseMessage" class="text-center mt-4 text-red-500"></p>
</div>
<script src="../../../public/assets/js/addMaterial.js"></script>
</body>
</html>