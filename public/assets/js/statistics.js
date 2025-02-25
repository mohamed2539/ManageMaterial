document.addEventListener('DOMContentLoaded', function() {
    // طلب بيانات الرسوم البيانية
    fetch('/MaterailManegmentT/public/index.php?controller=dashboard&action=getChartData')
        .then(response => response.json())
        .then(data => {
            initializeMaterialUsageChart(data.materialUsage);
            initializeBranchDistributionChart(data.branchDistribution);
        })
        .catch(error => console.error('Error fetching chart data:', error));
});

function initializeMaterialUsageChart(data) {
    if (!data || !data.length) {
        document.getElementById('materialUsageChart').parentElement.innerHTML = '<p class="text-center text-gray-500 my-4">لا توجد بيانات للعرض</p>';
        return;
    }
    
    const ctx = document.getElementById('materialUsageChart').getContext('2d');
    
    // اختيار أعلى 5 مواد استهلاكاً
    const topMaterials = data.slice(0, 5);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: topMaterials.map(item => item.name),
            datasets: [{
                label: 'الكمية المستهلكة',
                data: topMaterials.map(item => item.total_quantity),
                backgroundColor: 'rgba(59, 130, 246, 0.8)'
            }]
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
}

function initializeBranchDistributionChart(data) {
    if (!data || !data.length) {
        document.getElementById('branchDistributionChart').parentElement.innerHTML = '<p class="text-center text-gray-500 my-4">لا توجد بيانات للعرض</p>';
        return;
    }
    
    const ctx = document.getElementById('branchDistributionChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.map(item => item.branch_name),
            datasets: [{
                data: data.map(item => item.total_quantity),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
}