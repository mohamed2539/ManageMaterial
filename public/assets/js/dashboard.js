document.addEventListener('DOMContentLoaded', function() {
    loadStockAlerts();
    initializeCharts();
    loadChartData();
    setInterval(loadStockAlerts, 300000); // تحديث كل 5 دقائق
});

function loadStockAlerts() {
    fetch('/MaterailManegmentT/public/index.php?controller=inventory&action=getLatestAlerts')
        .then(response => response.json())
        .then(alerts => {
            const alertsContainer = document.getElementById('stockAlerts');
            alertsContainer.innerHTML = '';
            
            alerts.forEach(alert => {
                const alertElement = createAlertElement(alert);
                alertsContainer.appendChild(alertElement);
            });
        })
        .catch(error => console.error('Error loading alerts:', error));
}

function createAlertElement(alert) {
    const div = document.createElement('div');
    const isLowStock = alert.quantity <= alert.min_quantity;
    
    div.className = `p-4 mb-4 rounded ${isLowStock ? 'bg-red-100' : 'bg-yellow-100'}`;
    div.innerHTML = `
        <div class="flex justify-between items-center">
            <h3 class="font-bold">${alert.name}</h3>
            <span class="text-sm ${isLowStock ? 'text-red-600' : 'text-yellow-600'}">
                ${isLowStock ? 'نقص في المخزون' : 'زيادة في المخزون'}
            </span>
        </div>
        <p class="mt-2">الكمية الحالية: ${alert.quantity}</p>
        <p>الفرع: ${alert.branch_name}</p>
        <p class="text-sm text-gray-600">
            ${isLowStock ? `الحد الأدنى: ${alert.min_quantity}` : `الحد الأقصى: ${alert.max_quantity}`}
        </p>
    `;
    return div;
}

function initializeCharts() {
    // رسم بياني لتوزيع المواد حسب الفروع
    fetch('/MaterailManegmentT/public/index.php?controller=dashboard&action=getBranchDistribution')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('branchDistributionChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.map(item => item.branch_name),
                    datasets: [{
                        data: data.map(item => item.total_materials),
                        backgroundColor: [
                            '#4B5563', '#EF4444', '#F59E0B', '#10B981',
                            '#3B82F6', '#6366F1', '#8B5CF6', '#EC4899'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'توزيع المواد حسب الفروع'
                        }
                    }
                }
            });
        });

    // رسم بياني لنشاط المخزون
    fetch('/MaterailManegmentT/public/index.php?controller=dashboard&action=getStockActivity')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('stockActivityChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [
                        {
                            label: 'عمليات الصرف',
                            data: data.dispense_counts,
                            borderColor: '#EF4444',
                            fill: false
                        },
                        {
                            label: 'عمليات الإضافة',
                            data: data.addition_counts,
                            borderColor: '#10B981',
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });



        async function loadChartData() {
            try {
                const response = await fetch('/MaterailManegmentT/public/index.php?controller=dashboard&action=getChartData');
                const data = await response.json();
                
                if (data.materialUsage) {
                    createMaterialUsageChart(data.materialUsage);
                }
                
                if (data.branchDistribution) {
                    createBranchDistributionChart(data.branchDistribution);
                }
            } catch (error) {
                console.error('Error loading chart data:', error);
            }
        }
        
        // دوال إنشاء الرسوم البيانية
        function createMaterialUsageChart(data) {
            // ... كود الرسم البياني
        }
        
        function createBranchDistributionChart(data) {
            // ... كود الرسم البياني
        }





}