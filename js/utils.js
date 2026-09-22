/* ============================================================
   MobiShopy — Utility Functions
   ============================================================ */

/* ── Toast Notifications ─────────────────────────────────────── */
function showToast(message, type = 'info') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

/* ── Format Currency (INR) ───────────────────────────────────── */
function formatCurrency(amount) {
  return '₹' + parseFloat(amount).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

/* ── Format Date ─────────────────────────────────────────────── */
function formatDate(dateStr) {
  if (!dateStr) return '—';
  const d = new Date(dateStr);
  return d.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

/* ── Stock Badge ─────────────────────────────────────────────── */
function stockBadge(stock) {
  if (stock === 0)  return `<span class="badge badge-danger">Out of Stock</span>`;
  if (stock <= 5)   return `<span class="badge badge-warning">Low Stock (${stock})</span>`;
  return `<span class="badge badge-success">In Stock (${stock})</span>`;
}

/* ── Render Navbar User Info ─────────────────────────────────── */
function renderNavUser(selector = '#nav-user') {
  const u = session.get();
  const el = document.querySelector(selector);
  if (el && u) el.textContent = `👤 ${u.name} (${u.role})`;
}

/* ── Prevent Back Button Navigation ─────────────────────────── */
function preventBack() {
  window.history.pushState(null, "", window.location.href);
  window.onpopstate = function () {
    window.history.pushState(null, "", window.location.href);
  };
}

/* ── Logout Button ───────────────────────────────────────────── */
function setupLogout(selector = '#btn-logout') {
  const btn = document.querySelector(selector);
  if (btn) {
    btn.addEventListener('click', () => {
      session.clear();
      window.location.replace(getRoot() + 'index.html');
    });
  }
}

/* ── Open / Close Modal ──────────────────────────────────────── */
function openModal(id)  { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

/* Close modal on overlay click */
document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('active');
  }
});
