<?php

namespace app\models;
use PDO;
use config\database;

class Report {
    private $pdo;

    public function __construct() {
        $this->pdo = (new database())->getConnection();
    }

    public function generateReport($type, $startDate, $endDate, $branchId = null) {
        switch ($type) {
            case 'inventory':
                return $this->generateInventoryReport($startDate, $endDate, $branchId);
            case 'dispensing':
                return $this->generateDispensingReport($startDate, $endDate, $branchId);
            case 'activity':
                return $this->generateActivityReport($startDate, $endDate, $branchId);
            default:
                throw new \Exception('نوع التقرير غير صالح');
        }
    }

    private function generateInventoryReport($startDate, $endDate, $branchId = null) {
        try {
            $params = [];
            $sql = "
                SELECT 
                    m.name as material_name,
                    b.name as branch_name,
                    m.quantity as current_quantity,
                    m.min_quantity,
                    m.max_quantity,
                    COALESCE(d.total_dispensed, 0) as total_dispensed,
                    COALESCE(a.total_added, 0) as total_added
                FROM materials m
                JOIN branches b ON m.branch_id = b.id
                LEFT JOIN (
                    SELECT 
                        material_id,
                        SUM(quantity) as total_dispensed
                    FROM dispense_materials
                    WHERE created_at BETWEEN :start_date1 AND :end_date1
                    GROUP BY material_id
                ) d ON m.id = d.material_id
                LEFT JOIN (
                    SELECT 
                        material_id,
                        SUM(quantity) as total_added
                    FROM material_quantities
                    WHERE created_at BETWEEN :start_date2 AND :end_date2
                    GROUP BY material_id
                ) a ON m.id = a.material_id
            ";

            if ($branchId) {
                $sql .= " WHERE m.branch_id = :branch_id";
                $params[':branch_id'] = $branchId;
            }

            $sql .= " ORDER BY b.name, m.name";

            $stmt = $this->pdo->prepare($sql);
            $params[':start_date1'] = $startDate;
            $params[':end_date1'] = $endDate;
            $params[':start_date2'] = $startDate;
            $params[':end_date2'] = $endDate;
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'headers' => [
                    'material_name' => 'المادة',
                    'branch_name' => 'الفرع',
                    'current_quantity' => 'الكمية الحالية',
                    'min_quantity' => 'الحد الأدنى',
                    'max_quantity' => 'الحد الأقصى',
                    'total_dispensed' => 'إجمالي المصروف',
                    'total_added' => 'إجمالي المضاف'
                ],
                'rows' => $rows
            ];
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception('خطأ في إنشاء تقرير المخزون');
        }
    }

    private function generateDispensingReport($startDate, $endDate, $branchId = null) {
        try {
            $params = [];
            $sql = "
                SELECT 
                    d.created_at as date,
                    m.name as material_name,
                    b.name as branch_name,
                    d.quantity,
                    u.username as dispensed_by,
                    d.notes
                FROM dispense_materials d
                JOIN materials m ON d.material_id = m.id
                JOIN branches b ON m.branch_id = b.id
                JOIN users u ON d.user_id = u.id
                WHERE d.created_at BETWEEN :start_date AND :end_date
            ";

            if ($branchId) {
                $sql .= " AND m.branch_id = :branch_id";
                $params[':branch_id'] = $branchId;
            }

            $sql .= " ORDER BY d.created_at DESC";

            $stmt = $this->pdo->prepare($sql);
            $params[':start_date'] = $startDate;
            $params[':end_date'] = $endDate;
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'headers' => [
                    'date' => 'التاريخ',
                    'material_name' => 'المادة',
                    'branch_name' => 'الفرع',
                    'quantity' => 'الكمية',
                    'dispensed_by' => 'تم الصرف بواسطة',
                    'notes' => 'ملاحظات'
                ],
                'rows' => $rows
            ];
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception('خطأ في إنشاء تقرير الصرف');
        }
    }

    private function generateActivityReport($startDate, $endDate, $branchId = null) {
        try {
            $params = [];
            $sql = "
                SELECT 
                    activity_time as date,
                    activity_type,
                    material_name,
                    branch_name,
                    quantity,
                    username,
                    notes
                FROM (
                    SELECT 
                        d.created_at as activity_time,
                        'صرف' as activity_type,
                        m.name as material_name,
                        b.name as branch_name,
                        d.quantity,
                        u.username,
                        d.notes
                    FROM dispense_materials d
                    JOIN materials m ON d.material_id = m.id
                    JOIN branches b ON m.branch_id = b.id
                    JOIN users u ON d.user_id = u.id
                    
                    UNION ALL
                    
                    SELECT 
                        mq.created_at as activity_time,
                        'إضافة' as activity_type,
                        m.name as material_name,
                        b.name as branch_name,
                        mq.quantity,
                        u.username,
                        mq.notes
                    FROM material_quantities mq
                    JOIN materials m ON mq.material_id = m.id
                    JOIN branches b ON m.branch_id = b.id
                    JOIN users u ON mq.user_id = u.id
                ) activities
                WHERE activity_time BETWEEN :start_date AND :end_date
            ";

            if ($branchId) {
                $sql .= " AND branch_id = :branch_id";
                $params[':branch_id'] = $branchId;
            }

            $sql .= " ORDER BY activity_time DESC";

            $stmt = $this->pdo->prepare($sql);
            $params[':start_date'] = $startDate;
            $params[':end_date'] = $endDate;
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'headers' => [
                    'date' => 'التاريخ',
                    'activity_type' => 'نوع النشاط',
                    'material_name' => 'المادة',
                    'branch_name' => 'الفرع',
                    'quantity' => 'الكمية',
                    'username' => 'المستخدم',
                    'notes' => 'ملاحظات'
                ],
                'rows' => $rows
            ];
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception('خطأ في إنشاء تقرير النشاط');
        }
    }
}