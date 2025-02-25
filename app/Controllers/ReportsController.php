<?php

namespace app\Controllers;
use app\models\Report;

class ReportsController extends BaseController {
    private $reportModel;

    public function __construct() {
        $this->reportModel = new Report();
    }

    public function generate() {
        try {
            $type = $_GET['report_type'] ?? 'inventory';
            $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
            $endDate = $_GET['end_date'] ?? date('Y-m-d');
            $branchId = $_GET['branch_id'] ?? null;

            $data = $this->reportModel->generateReport($type, $startDate, $endDate, $branchId);
            $this->jsonResponse($data);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function export() {
        try {
            $type = $_GET['report_type'] ?? 'inventory';
            $exportType = $_GET['export_type'] ?? 'pdf';
            $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
            $endDate = $_GET['end_date'] ?? date('Y-m-d');
            $branchId = $_GET['branch_id'] ?? null;

            $data = $this->reportModel->generateReport($type, $startDate, $endDate, $branchId);
            
            if ($exportType === 'pdf') {
                $this->exportToPDF($data, $type);
            } else {
                $this->exportToExcel($data, $type);
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    private function exportToPDF($data, $reportType) {
        require_once '../vendor/autoload.php'; // تأكد من تثبيت مكتبة TCPDF
        
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // إعداد معلومات PDF
        $pdf->SetCreator('نظام إدارة المخزون');
        $pdf->SetAuthor('النظام');
        $pdf->SetTitle('تقرير ' . $reportType);
        
        // إعداد الهوامش
        $pdf->SetMargins(15, 15, 15);
        
        // إضافة صفحة
        $pdf->AddPage();
        
        // إضافة المحتوى
        $html = $this->generatePDFContent($data, $reportType);
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // تصدير الملف
        $pdf->Output('report_' . date('Y-m-d') . '.pdf', 'D');
    }

    private function exportToExcel($data, $reportType) {
        require_once '../vendor/autoload.php'; // تأكد من تثبيت مكتبة PhpSpreadsheet
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // إضافة العناوين
        $columns = array_keys($data['headers']);
        foreach ($columns as $index => $column) {
            $sheet->setCellValueByColumnAndRow($index + 1, 1, $data['headers'][$column]);
        }
        
        // إضافة البيانات
        foreach ($data['rows'] as $rowIndex => $row) {
            foreach ($columns as $columnIndex => $column) {
                $sheet->setCellValueByColumnAndRow(
                    $columnIndex + 1,
                    $rowIndex + 2,
                    $row[$column]
                );
            }
        }
        
        // تصدير الملف
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="report_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    private function generatePDFContent($data, $reportType) {
        $html = '<h1>تقرير ' . $reportType . '</h1>';
        $html .= '<table border="1" cellpadding="5">';
        
        // إضافة العناوين
        $html .= '<tr>';
        foreach ($data['headers'] as $header) {
            $html .= '<th>' . $header . '</th>';
        }
        $html .= '</tr>';
        
        // إضافة البيانات
        foreach ($data['rows'] as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . $cell . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }
}