function initDashboard() {
  const user = getUser();
  if (!user || !getToken()) {
    window.location.hash = "#login";
    return;
  }

  document.getElementById("dashEmail").textContent = user.email || "-";
  document.getElementById("dashRole").textContent = user.role || "user";

  const adminPanel = document.getElementById("adminPanel");
  if (adminPanel) {
    adminPanel.style.display = user.role === "admin" ? "block" : "none";
  }

  const btnLogout = document.getElementById("btnLogout");
  if (btnLogout) {
    btnLogout.onclick = () => {
      clearAuth();
      window.location.hash = "#login";
    };
  }
}
