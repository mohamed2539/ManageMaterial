<?php

class WorkingFineCode{
    /*    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->branchModel->createBranch($_POST);
            $this->redirect(BASE_URL . 'index.php?controller=branch&action=index'); // ✅ مسار صحيح
        }
    }*/


    /*public function getBranchesAjax() {
        header('Content-Type: application/json');
        $branches = $this->branchModel->getAllBranches();
        echo json_encode($branches);
        exit;
    }*/



    /*    public function updateBranch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'manager_name' => $_POST['manager_name'],
                'notes' => $_POST['notes']
            ];
            $this->branchModel->updateBranch($id, $data);
            echo json_encode(["status" => "success"]);
            exit;
        }
        echo json_encode(["status" => "error"]);
        exit;
    }*/



/*
   <table class="w-full bg-white rounded shadow">
        <thead>
        <tr class="bg-gray-200">
            <th class="p-2">ID</th>
            <th class="p-2">الاسم</th>
            <th class="p-2">العنوان</th>
            <th class="p-2">الهاتف</th>
            <th class="p-2">الإيميل</th>
            <th class="p-2">المدير</th>
            <th class="p-2">ملاحظات</th>
            <th class="p-2">تعديل</th>
            <th class="p-2">حذف</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($branches)): */?><!--
            <?php /*foreach ($branches as $branch): */?>
                <tr class="border-t">
                    <td class="p-2"><?php /*= htmlspecialchars($branch['id']) */?></td>
                    <td class="p-2"><?php /*= htmlspecialchars($branch['name']) */?></td>
                    <td class="p-2"><?php /*= htmlspecialchars($branch['address']) */?></td>
                    <td class="p-2"><?php /*= htmlspecialchars($branch['phone']) */?></td>
                    <td class="p-2"><?php /*= htmlspecialchars($branch['email']) */?></td>
                    <td class="p-2"><?php /*= htmlspecialchars($branch['manager_name']) */?></td>
                    <td class="p-2"><?php /*= htmlspecialchars($branch['notes']) */?></td>
                    <td class="p-2">
                        <a href="../../../public/index.php?controller=branch&action=edit&id=<?php /*= htmlspecialchars($branch['id']) */?>"
                           class="bg-yellow-500 text-white px-2 py-1 rounded">تعديل</a>
                    </td>
                    <td class="p-2">
                        <a href="../../../public/index.php?controller=branch&action=delete&id=<?php /*= htmlspecialchars($branch['id']) */?>"
                           class="bg-red-500 text-white px-2 py-1 rounded"
                           onclick="return confirm('هل أنت متأكد من حذف هذا الفرع؟');">حذف</a>
                    </td>
                </tr>
            <?php /*endforeach; */?>
        <?php /*else: */?>
            <tr><td colspan="7" class="p-2 text-center">لا توجد بيانات</td></tr>
        <?php /*endif; */?>
        </tbody>
    </table>-->





}




