// frontend/assets/js/api.js
const API_BASE = "http://localhost/fkzeljeznicarproject/backend/rest/";

function getToken() {
  return localStorage.getItem("token");
}

function getUser() {
  try {
    return JSON.parse(localStorage.getItem("user"));
  } catch {
    return null;
  }
}

function setAuth(user, token) {
  localStorage.setItem("user", JSON.stringify(user));
  localStorage.setItem("token", token);
}

function clearAuth() {
  localStorage.removeItem("user");
  localStorage.removeItem("token");
}

async function apiFetch(path, options = {}) {
  const token = getToken();

  const headers = {
    "Content-Type": "application/json",
    ...(options.headers || {}),
  };

  // TI KORISTIŠ "Authentication" header u middleware-u
  if (token) headers["Authentication"] = token;

  const cleanPath = path.replace(/^\//, ""); // makni leading /
  const res = await fetch(`${API_BASE}${cleanPath}`, { ...options, headers });

  // pokušaj JSON, ali ako server vrati text, ne ruši
  const text = await res.text();
  let data;
  try {
    data = JSON.parse(text);
  } catch {
    data = text;
  }

  if (!res.ok) {
    const msg =
      data && data.error
        ? data.error
        : typeof data === "string"
        ? data
        : "Request failed";
    throw new Error(`${res.status} ${msg}`);
  }

  return data;
}
