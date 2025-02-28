<?php

namespace app\Controllers;
use app\models\Dashboard;

class DashboardController extends BaseController {
    private $dashboardModel;

    public function __construct() {
        parent::__construct(); // تأكد من استدعاء constructor الأب
        $this->dashboardModel = new Dashboard();
    }

    public function index() {
        try {
            $data = [
                'totalMaterials' => $this->dashboardModel->getTotalMaterials(),
                'totalBranches' => $this->dashboardModel->getTotalBranches(),
                'totalSuppliers' => $this->dashboardModel->getTotalSuppliers(),
                'totalUsers' => $this->dashboardModel->getTotalActiveUsers(),
                'recentActivities' => $this->dashboardModel->getRecentActivities(),
                'lowStockItems' => $this->dashboardModel->getLowStockItems()
            ];
            
            // للتأكد من البيانات
            error_log('Dashboard Data: ' . print_r($data, true));
            
            $this->renderView('dashboard/index', $data);
        } catch (\Exception $e) {
            error_log('Dashboard Error: ' . $e->getMessage());
            $this->renderView('dashboard/index', [
                'error' => $e->getMessage(),
                'totalMaterials' => 0,
                'totalBranches' => 0,
                'totalSuppliers' => 0,
                'totalUsers' => 0,
                'recentActivities' => [],
                'lowStockItems' => []
            ]);
        }
    }

    public function statistics() {
        try {
            $materialUsageData = $this->dashboardModel->getMaterialUsageStats();
            $branchDistributionData = $this->dashboardModel->getBranchDistribution();
            $supplierActivityData = $this->dashboardModel->getSupplierActivity();
            
            // إعداد البيانات بالشكل المتوقع في العرض
            $data = [
                'materialUsage' => [
                    'daily_average' => $this->calculateDailyAverage($materialUsageData),
                    'most_requested' => $this->getMostRequestedMaterial($materialUsageData),
                    'data' => $materialUsageData
                ],
                'branchDistribution' => [
                    'most_active' => $this->getMostActiveBranch($branchDistributionData),
                    'data' => $branchDistributionData
                ],
                'supplierActivity' => $supplierActivityData
            ];
            
            $this->renderView('dashboard/statistics', $data);
        } catch (\Exception $e) {
            error_log('Statistics Error: ' . $e->getMessage());
            $this->renderView('dashboard/statistics', [
                'error' => $e->getMessage(),
                'materialUsage' => [
                    'daily_average' => 0,
                    'most_requested' => 'لا توجد بيانات',
                    'data' => []
                ],
                'branchDistribution' => [
                    'most_active' => 'لا توجد بيانات',
                    'data' => []
                ],
                'supplierActivity' => []
            ]);
        }
    }

    public function getBranchDistribution() {
        try {
            $data = $this->dashboardModel->getBranchDistribution();
            $this->jsonResponse($data);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getStockActivity() {
        try {
            $data = $this->dashboardModel->getStockActivity();
            $this->jsonResponse($data);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getChartData() {
        try {
            $data = [
                'materialUsage' => $this->dashboardModel->getMaterialUsageStats(),
                'branchDistribution' => $this->dashboardModel->getBranchDistribution()
            ];
            
            $this->jsonResponse($data);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    private function calculateDailyAverage($materialData) {
        if (empty($materialData)) {
            return 0;
        }
        
        $totalQuantity = 0;
        foreach ($materialData as $material) {
            $totalQuantity += ($material['total_quantity'] ?? 0);
        }
        
        return $totalQuantity / 30; // متوسط آخر 30 يوم
    }

    private function getMostRequestedMaterial($materialData) {
        if (empty($materialData)) {
            return 'لا توجد بيانات';
        }
        
        // افتراض أن المواد مرتبة بالفعل حسب الكمية المستهلكة
        return $materialData[0]['name'] ?? 'لا توجد بيانات';
    }

    private function getMostActiveBranch($branchData) {
        if (empty($branchData)) {
            return 'لا توجد بيانات';
        }
        
        // افتراض أن الفروع مرتبة بالفعل حسب النشاط
        return $branchData[0]['branch_name'] ?? 'لا توجد بيانات';
    }
}