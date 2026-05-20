function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
// Client-side table search
function initTableSearch(inputId, tableId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll(`#${tableId} tbody tr`).forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initTableSearch('student-search', 'students-table');
    initTableSearch('teacher-search', 'teachers-table');
});

window.toggleSidebar = toggleSidebar;