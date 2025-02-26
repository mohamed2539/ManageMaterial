<?php
namespace app\Controllers;
use app\models\Transaction;

class TransactionController extends BaseController {
    private $transactionModel;

    public function __construct() {
        $this->transactionModel = new Transaction();
    }

    // عرض صفحة الصرف
    public function index() {
        $recentTransactions = $this->transactionModel->getRecentTransactions();
        $this->renderView('issueItem', ['recentTransactions' => $recentTransactions]);
    }

    /*
    public function index() {
        $branches = $this->branchModel->getLast20Branches();

        $this->renderView('AddBranch', ['branches' => $branches]);
    }
*/

    // البحث عن مادة بالكود
    public function getMaterialByCode() {
        if (!isset($_GET['code'])) {
            echo json_encode(['status' => 'error', 'message' => 'الكود مطلوب']);
            exit;
        }

        $material = $this->transactionModel->getMaterialByCode($_GET['code']);

        if ($material) {
            echo json_encode(['status' => 'success', 'data' => $material]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'لم يتم العثور على المادة']);
        }
        exit;
    }

    // إنشاء عملية صرف جديدة
    public function issueItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'طريقة طلب غير صحيحة']);
            exit;
        }

        // طباعة البيانات المستلمة للتحقق
        error_log('Received POST data: ' . print_r($_POST, true));

        // التحقق من وجود user_id
        if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'معرف المستخدم مطلوب']);
            exit;
        }

        // التحقق من وجود المستخدم في قاعدة البيانات
        $userExists = $this->transactionModel->checkUserExists($_POST['user_id']);
        if (!$userExists) {
            echo json_encode(['status' => 'error', 'message' => 'المستخدم غير موجود']);
            exit;
        }

        $required = ['material_id', 'user_id', 'branch_id', 'quantity'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['status' => 'error', 'message' => "حقل {$field} مطلوب"]);
                exit;
            }
        }

        $result = $this->transactionModel->createTransaction($_POST);
        echo json_encode($result);
        exit;
    }

    // تحديث عملية
    public function updateTransaction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'طريقة طلب غير صحيحة']);
            exit;
        }

        if (!isset($_POST['id']) || !isset($_POST['quantity'])) {
            echo json_encode(['status' => 'error', 'message' => 'البيانات غير مكتملة']);
            exit;
        }

        $result = $this->transactionModel->updateTransaction($_POST['id'], $_POST);
        echo json_encode($result);
        exit;
    }

    // حذف عملية
    public function deleteTransaction() {
        if (!isset($_GET['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'معرف العملية مطلوب']);
            exit;
        }

        $result = $this->transactionModel->deleteTransaction($_GET['id']);
        echo json_encode($result);
        exit;
    }

    public function getRecentTransactions() {
        try {
            $transactions = $this->transactionModel->getRecentTransactions(10); // جلب آخر 10 عمليات
            echo json_encode([
                'status' => 'success',
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            error_log('Error in getRecentTransactions: ' . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب العمليات'
            ]);
        }
        exit;
    }


    /*
    // جلب آخر العمليات
    public function getRecentTransactions() {
        $transactions = $this->transactionModel->getRecentTransactions();
        echo json_encode(['status' => 'success', 'data' => $transactions]);
        exit;
    }
    */
}