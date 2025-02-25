<?php if (!empty($suppliers)): ?>
    <ul class="mt-2 bg-gray-50 p-4 rounded">
        <?php foreach ($suppliers as $supplier): ?>
            <li class="border-b py-2"><?php echo htmlspecialchars($supplier['name']); ?> - <?php echo htmlspecialchars($supplier['phone']); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="text-gray-500 mt-2">لا يوجد موردين حتى الآن.</p>
<?php endif; ?>
