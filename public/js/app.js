// Usa sempre o mesmo origin (http/https)
const API_BASE = `${location.origin}/simple-otp-crud/api`;

async function api(path, method='GET', body) {
  const headers = { 'Content-Type': 'application/json' };

  const res = await fetch(API_BASE + path, {
    method,
    headers,
    credentials: 'include',   // 🔑 envia/recebe cookies (HttpOnly)
    body: (method === 'GET' || method === 'DELETE') ? undefined : JSON.stringify(body || {})
  });

  const txt = await res.text();
  let j;
  try { j = JSON.parse(txt); }
  catch (e) { throw new Error('Resposta não-JSON (HTTP ' + res.status + '): ' + txt); }

  if (!res.ok || (j.ok === false)) {
    throw new Error(j.error || j.detail || ('HTTP ' + res.status));
  }
  return j;
}
