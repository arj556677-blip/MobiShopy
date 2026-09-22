/* ============================================================
   MobiShopy — API Helper (Fetch Wrapper + Session)
   ============================================================ */

// Detect if we're in a subdirectory (admin/, customer/, supplier/) or at root
const _path = window.location.pathname;
const BASE = (_path.includes('/admin/') || _path.includes('/customer/') || _path.includes('/supplier/'))
  ? '../php/' : 'php/';

const api = {
  async get(file, params = {}) {
    const qs = new URLSearchParams(params).toString();
    const res = await fetch(`${BASE}${file}?${qs}`);
    return res.json();
  },
  async post(file, action, body = {}) {
    const res = await fetch(`${BASE}${file}?action=${action}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });
    return res.json();
  }
};

/* ── Session helpers ─────────────────────────────────────────── */
const session = {
  save(data) { localStorage.setItem('ms_user', JSON.stringify(data)); },
  get()      { try { return JSON.parse(localStorage.getItem('ms_user')); } catch { return null; } },
  clear()    { localStorage.removeItem('ms_user'); },
  require(role) {
    const u = this.get();
    const target = getRoot() + 'index.html';
    if (!u) {
      window.location.replace(target);
      return null;
    }
    if (role && u.role !== role) {
      window.location.replace(target);
      return null;
    }
    return u;
  }
};

function getRoot() {
  const path = window.location.pathname;
  if (path.includes('/admin/') || path.includes('/customer/') || path.includes('/supplier/')) {
    return '../';
  }
  return '';
}
