<?php
namespace app\Controllers;
use app\models\Dashboard;

class DashboardController extends BaseController {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new Dashboard();
    }

    public function index() {
        try {
            $data = [
                'pageTitle' => 'لوحة التحكم',
                'activeMenu' => 'dashboard',
                'totalMaterials' => $this->dashboardModel->getTotalMaterials(),
                'totalBranches' => $this->dashboardModel->getTotalBranches(),
                'totalSuppliers' => $this->dashboardModel->getTotalSuppliers(),
                'totalUsers' => $this->dashboardModel->getTotalActiveUsers(),
                'recentActivities' => $this->dashboardModel->getRecentActivities(),
                'lowStockItems' => $this->dashboardModel->getLowStockItems()
            ];
            
            $this->renderView('dashboard/index', $data);
        } catch (\Exception $e) {
            error_log('Dashboard Error: ' . $e->getMessage());
            $this->renderView('dashboard/index', [
                'pageTitle' => 'لوحة التحكم',
                'activeMenu' => 'dashboard',
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
            $materialUsageStats = $this->dashboardModel->getMaterialUsageStats();
            $branchDistribution = $this->dashboardModel->getBranchDistribution();
            
            $data = [
                'pageTitle' => 'إحصائيات النظام',
                'activeMenu' => 'statistics',
                'materialUsage' => [
                    'daily_average' => $this->calculateDailyAverage($materialUsageStats),
                    'most_requested' => $this->getMostRequestedMaterial($materialUsageStats),
                    'data' => $materialUsageStats
                ],
                'branchDistribution' => [
                    'most_active' => $this->getMostActiveBranch($branchDistribution),
                    'data' => $branchDistribution
                ],
                'supplierActivity' => $this->dashboardModel->getSupplierActivity()
            ];
            
            $this->renderView('dashboard/statistics', $data);
        } catch (\Exception $e) {
            error_log('Statistics Error: ' . $e->getMessage());
            $this->renderView('dashboard/statistics', [
                'pageTitle' => 'إحصائيات النظام',
                'activeMenu' => 'statistics',
                'error' => $e->getMessage(),
                'materialUsage' => ['daily_average' => 0, 'most_requested' => 'لا توجد بيانات', 'data' => []],
                'branchDistribution' => ['most_active' => 'لا توجد بيانات', 'data' => []],
                'supplierActivity' => []
            ]);
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
        if (empty($materialData)) return 0;
        $totalQuantity = array_sum(array_column($materialData, 'total_quantity'));
        return $totalQuantity / 30;
    }

    private function getMostRequestedMaterial($materialData) {
        return empty($materialData) ? 'لا توجد بيانات' : ($materialData[0]['name'] ?? 'لا توجد بيانات');
    }

    private function getMostActiveBranch($branchData) {
        return empty($branchData) ? 'لا توجد بيانات' : ($branchData[0]['branch_name'] ?? 'لا توجد بيانات');
    }
}