document.addEventListener('DOMContentLoaded', function() {
    loadBranches();
    initializeReportForm();
});

function loadBranches() {
    fetch('/MaterailManegmentT/public/index.php?controller=branch&action=getBranches')
        .then(response => response.json())
        .then(branches => {
            const select = document.querySelector('select[name="branch_id"]');
            branches.forEach(branch => {
                const option = document.createElement('option');
                option.value = branch.id;
                option.textContent = branch.name;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading branches:', error));
}

function initializeReportForm() {
    const form = document.getElementById('reportFilters');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        generateReport(new FormData(form));
    });

    document.getElementById('exportPDF').addEventListener('click', () => exportReport('pdf'));
    document.getElementById('exportExcel').addEventListener('click', () => exportReport('excel'));
}

function generateReport(formData) {
    const params = new URLSearchParams(formData);
    
    fetch(`/MaterailManegmentT/public/index.php?controller=reports&action=generate&${params}`)
        .then(response => response.json())
        .then(data => {
            displayReport(data);
        })
        .catch(error => {
            console.error('Error generating report:', error);
            alert('حدث خطأ أثناء إنشاء التقرير');
        });
}

function displayReport(data) {
    const tableContainer = document.getElementById('reportTable');
    let html = '<table class="min-w-full bg-white">';
    
    // إنشاء رأس الجدول
    html += '<thead><tr>';
    Object.keys(data.headers).forEach(header => {
        html += `<th class="px-4 py-2 border">${data.headers[header]}</th>`;
    });
    html += '</tr></thead>';

    // إضافة البيانات
    html += '<tbody>';
    data.rows.forEach(row => {
        html += '<tr>';
        Object.keys(data.headers).forEach(key => {
            html += `<td class="px-4 py-2 border">${row[key]}</td>`;
        });
        html += '</tr>';
    });
    html += '</tbody></table>';

    tableContainer.innerHTML = html;
}

function exportReport(type) {
    const formData = new FormData(document.getElementById('reportFilters'));
    formData.append('export_type', type);
    
    const params = new URLSearchParams(formData);
    window.location.href = `/MaterailManegmentT/public/index.php?controller=reports&action=export&${params}`;
}