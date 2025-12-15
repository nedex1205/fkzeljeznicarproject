// frontend/assets/js/auth-ui.js

async function handleLogin(e) {
  if (e) e.preventDefault();

  const email = document.getElementById("loginEmail")?.value?.trim();
  const password = document.getElementById("loginPassword")?.value;

  const errEl = document.getElementById("loginError");
  if (errEl) errEl.textContent = "";

  try {
    const res = await apiFetch("/auth/login", {
      method: "POST",
      body: JSON.stringify({ email, password }),
    });

    console.log("LOGIN RESPONSE:", res);

    const token = res?.data?.token || res?.token;
    const user = res?.data?.user || res?.data || res?.user;

    if (!token) throw new Error("Token missing in response");

    setAuth(user, token);

    window.location.hash = "#dashboard";
  } catch (err) {
    console.error("LOGIN ERROR:", err);
    if (errEl) errEl.textContent = err.message;
  }
}

async function handleRegister(e) {
  if (e) e.prntDefault();

  const email = document.getElementById("regEmail")?.value?.trim();
  const password = document.getElementById("regPassword")?.value;

  const errEl = document.getElementById("regError");
  const okEl = document.getElementById("regOk");
  if (errEl) errEl.textContent = "";
  if (okEl) okEl.textContent = "";

  try {
    await apiFetch("/auth/register", {
      method: "POST",
      body: JSON.stringify({ email, password }),
    });

    if (okEl) okEl.textContent = "Registracija uspješna. Prijavi se.";
    window.location.hash = "#login";
  } catch (err) {
    console.error("REGISTER ERROR:", err);
    if (errEl) errEl.textContent = err.message;
  }
}

// ✅ Delegirani eventi (rade sa SPApp dinamičkim loadom)
$(document).on("click", "#btnLogin", handleLogin);
$(document).on("click", "#btnRegister", handleRegister);
