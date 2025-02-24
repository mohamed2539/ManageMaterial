<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispense Material</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">Dispense Material</h2>
    <form id="dispenseForm" class="mt-4">
        <div class="mb-4">
            <label class="block text-gray-700">Material Code:</label>
            <input type="text" id="material_code" name="material_code" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Material Name:</label>
            <input type="text" id="material_name" name="material_name" class="w-full p-2 border rounded" readonly>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Size:</label>
            <input type="text" id="size" name="size" class="w-full p-2 border rounded" readonly>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Unit:</label>
            <input type="text" id="unit" name="unit" class="w-full p-2 border rounded" readonly>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Quantity to Dispense:</label>
            <input type="number" id="quantity" name="quantity" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Branch:</label>
            <select id="branch_id" name="branch_id" class="w-full p-2 border rounded" required>
                <!-- Branches will be loaded dynamically -->
            </select>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Dispense</button>
    </form>
    <p id="responseMessage" class="text-center mt-4 text-red-500"></p>
</div>
<script src="../../../public/assets/js/dispenseMaterial.js"></script>
</body>
</html>