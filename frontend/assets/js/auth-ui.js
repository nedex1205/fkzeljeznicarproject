function isValidEmail(v) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test((v || "").trim());
}

async function handleLogin(e) {
  if (e) e.preventDefault();

  const email = document.getElementById("loginEmail")?.value?.trim() || "";
  const password = document.getElementById("loginPassword")?.value || "";

  const errEl = document.getElementById("loginError");
  if (errEl) errEl.textContent = "";

  if (!email || !password) {
    if (errEl) errEl.textContent = "Email i lozinka su obavezni.";
    return;
  }
  if (!isValidEmail(email)) {
    if (errEl) errEl.textContent = "Unesi ispravan email.";
    return;
  }
  if (password.length < 6) {
    if (errEl) errEl.textContent = "Lozinka mora imati najmanje 6 karaktera.";
    return;
  }

  try {
    const res = await AuthAPI.login(email, password);

    const data = res?.data || res;

    const token = data?.token || res?.token;

    const user = data?.user || data;

    if (!token) throw new Error("Token missing in response");

    setAuth(user, token);

    window.location.hash = "#dashboard";
  } catch (err) {
    console.error("LOGIN ERROR:", err);
    if (errEl) errEl.textContent = err.message || "Pogrešan email ili lozinka.";
  }
}

async function handleRegister(e) {
  if (e) e.preventDefault();

  const email = document.getElementById("regEmail")?.value?.trim() || "";
  const password = document.getElementById("regPassword")?.value || "";

  const errEl = document.getElementById("regError");
  const okEl = document.getElementById("regOk");
  if (errEl) errEl.textContent = "";
  if (okEl) okEl.textContent = "";

  if (!email || !password) {
    if (errEl) errEl.textContent = "Email i lozinka su obavezni.";
    return;
  }
  if (!isValidEmail(email)) {
    if (errEl) errEl.textContent = "Unesi ispravan email.";
    return;
  }
  if (password.length < 6) {
    if (errEl) errEl.textContent = "Lozinka mora imati najmanje 6 karaktera.";
    return;
  }

  try {
    await AuthAPI.register(email, password);

    if (okEl) okEl.textContent = "Registracija uspješna. Prijavi se.";
    window.location.hash = "#login";
  } catch (err) {
    console.error("REGISTER ERROR:", err);
    if (errEl) errEl.textContent = err.message || "Registracija nije uspjela.";
  }
}

$(document).on("click", "#btnLogin", handleLogin);
$(document).on("click", "#btnRegister", handleRegister);
