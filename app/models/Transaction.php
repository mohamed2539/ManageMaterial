<?php
namespace app\models;
use PDO;
use config\database;

class Transaction {
    private $pdo;

    public function __construct() {
        $this->pdo = (new database())->getConnection();
    }




    public function checkUserExists($userId) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch() !== false;
    }



    // جلب المادة بواسطة الكود
    public function getMaterialByCode($code) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, b.name as branch_name 
            FROM materials m 
            LEFT JOIN branches b ON m.branch_id = b.id 
            WHERE m.code = ?
        ");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // إنشاء عملية صرف جديدة
    public function createTransaction($data) {
        try {
            $this->pdo->beginTransaction();

            // جلب كود المادة
            $stmt = $this->pdo->prepare("SELECT code FROM materials WHERE id = ?");
            $stmt->execute([$data['material_id']]);
            $materialCode = $stmt->fetchColumn();

            if (!$materialCode) {
                throw new \Exception("لم يتم العثور على كود المادة");
            }

            // إدخال العملية في جدول material_transactions
            $stmt = $this->pdo->prepare("
            INSERT INTO material_transactions 
            (material_id, user_id, branch_id, transaction_type, quantity, notes, transaction_code)
            VALUES (?, ?, ?, 'ISSUE', ?, ?, ?)
        ");

            $stmt->execute([
                $data['material_id'],
                $data['user_id'],
                $data['branch_id'],
                $data['quantity'],
                $data['notes'] ?? null,
                $materialCode  // استخدام كود المادة نفسه
            ]);

            // تحديث الكمية في جدول materials
            $stmt = $this->pdo->prepare("
            UPDATE materials 
            SET quantity = quantity - ? 
            WHERE id = ? AND quantity >= ?
        ");

            $success = $stmt->execute([
                $data['quantity'],
                $data['material_id'],
                $data['quantity']
            ]);

            if (!$success || $stmt->rowCount() === 0) {
                throw new \Exception("الكمية المطلوبة غير متوفرة");
            }

            $this->pdo->commit();
            return ['status' => 'success', 'message' => 'تم صرف المادة بنجاح', 'code' => $materialCode];
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // جلب آخر العمليات
    public function getRecentTransactions() {


        $stmt = $this->pdo->query("SELECT * FROM material_transactions  LIMIT 20");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

        /*
        $limit = 10
        $stmt = $this->pdo->prepare("
            SELECT 
                t.*,
                m.name as material_name,
                b.name as branch_name,
                u.full_name as user_name
            FROM material_transactions t
            JOIN materials m ON t.material_id = m.id
            JOIN branches b ON t.branch_id = b.id
            JOIN users u ON t.user_id = u.id
            WHERE t.transaction_type = 'ISSUE'
            ORDER BY t.transaction_date DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        */
    }

    // تحديث عملية
    public function updateTransaction($id, $data) {
        try {
            $this->pdo->beginTransaction();

            // جلب معلومات العملية القديمة
            $stmt = $this->pdo->prepare("SELECT quantity, material_id FROM material_transactions WHERE id = ?");
            $stmt->execute([$id]);
            $oldTransaction = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$oldTransaction) {
                throw new \Exception("العملية غير موجودة");
            }

            // تحديث الكمية في المخزون
            $quantityDiff = $data['quantity'] - $oldTransaction['quantity'];

            $stmt = $this->pdo->prepare("
                UPDATE materials 
                SET quantity = quantity - ? 
                WHERE id = ? AND quantity >= ?
            ");

            $success = $stmt->execute([
                $quantityDiff,
                $oldTransaction['material_id'],
                $quantityDiff
            ]);

            if (!$success || $stmt->rowCount() === 0) {
                throw new \Exception("الكمية المطلوبة غير متوفرة");
            }

            // تحديث العملية
            $stmt = $this->pdo->prepare("
                UPDATE material_transactions 
                SET quantity = ?, notes = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $data['quantity'],
                $data['notes'],
                $id
            ]);

            $this->pdo->commit();
            return ['status' => 'success', 'message' => 'تم تحديث العملية بنجاح'];
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // حذف عملية
    public function deleteTransaction($id) {
        try {
            $this->pdo->beginTransaction();

            // جلب معلومات العملية
            $stmt = $this->pdo->prepare("
                SELECT quantity, material_id 
                FROM material_transactions 
                WHERE id = ?
            ");
            $stmt->execute([$id]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$transaction) {
                throw new \Exception("العملية غير موجودة");
            }

            // إعادة الكمية للمخزون
            $stmt = $this->pdo->prepare("
                UPDATE materials 
                SET quantity = quantity + ? 
                WHERE id = ?
            ");

            $stmt->execute([
                $transaction['quantity'],
                $transaction['material_id']
            ]);

            // حذف العملية
            $stmt = $this->pdo->prepare("DELETE FROM material_transactions WHERE id = ?");
            $stmt->execute([$id]);

            $this->pdo->commit();
            return ['status' => 'success', 'message' => 'تم حذف العملية بنجاح'];
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    /*
    // توليد كود عملية جديد
    private function generateTransactionCode() {
        return 'TRX-' . date('Ymd') . '-' . rand(1000, 9999);
    }
    */
}