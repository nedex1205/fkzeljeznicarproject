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
    ...(options.headers || {}),
    "Content-Type": "application/json",
  };

  if (token) headers["Authentication"] = token;

  const base = API_BASE.replace(/\/+$/, ""); // skini sve slasheve na kraju
  const p = String(path || "").replace(/^\/+/, ""); // skini sve slasheve na početku
  const res = await fetch(`${base}/${p}`, { ...options, headers });

  const text = await res.text();
  let data;
  try {
    data = text ? JSON.parse(text) : null;
  } catch {
    data = text;
  }

  if (!res.ok) {
    const msg =
      (data && data.error) ||
      (data && data.message) ||
      (typeof data === "string" ? data : "") ||
      "Request failed";
    const err = new Error(`${res.status} ${msg}`.trim());
    err.status = res.status;
    err.data = data;
    throw err;
  }

  return data;
}

const AuthAPI = {
  async login(email, password) {
    return apiFetch("/auth/login", {
      method: "POST",
      body: JSON.stringify({ email, password }),
    });
  },

  async register(email, password) {
    return apiFetch("/auth/register", {
      method: "POST",
      body: JSON.stringify({ email, password }),
    });
  },

  logout() {
    clearAuth();
  },
};

const ProductsAPI = {
  getAll() {
    return apiFetch("/products", { method: "GET" });
  },
  getById(id) {
    return apiFetch(`/products/${id}`, { method: "GET" });
  },
  create(body) {
    return apiFetch("/products", {
      method: "POST",
      body: JSON.stringify(body),
    });
  },
  update(id, body) {
    return apiFetch(`/products/${id}`, {
      method: "PUT",
      body: JSON.stringify(body),
    });
  },
  remove(id) {
    return apiFetch(`/products/${id}`, { method: "DELETE" });
  },
};

const PlayersAPI = {
  getAll() {
    return apiFetch("/players", { method: "GET" });
  },
  getById(id) {
    return apiFetch(`/players/${id}`, { method: "GET" });
  },
};

const MatchesAPI = {
  getAll() {
    return apiFetch("/matches", { method: "GET" });
  },
  getById(id) {
    return apiFetch(`/matches/${id}`, { method: "GET" });
  },
};

const OrdersAPI = {
  getAll() {
    return apiFetch("/orders", { method: "GET" });
  },
  create(body) {
    return apiFetch("/orders", { method: "POST", body: JSON.stringify(body) });
  },
};

window.apiFetch = apiFetch;
window.AuthAPI = AuthAPI;
window.ProductsAPI = ProductsAPI;
window.PlayersAPI = PlayersAPI;
window.MatchesAPI = MatchesAPI;
window.OrdersAPI = OrdersAPI;
window.getUser = getUser;
window.setAuth = setAuth;
window.clearAuth = clearAuth;
