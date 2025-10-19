window.Views = window.Views || {};
Views.auth = {
  render() {
    const u = User.get();
    if (u) {
      return `
<section class="card grid gap-3 max-w-md">
<h3 class="text-xl font-semibold">Profil</h3>
<p class="text-sm">Prijavljeni ste kao <span class="font-medium">${u.email}</span>.</p>
<button id="logoutBtn" class="btn btn-outline w-fit">Odjava</button>
</section>`;
    }
    return `
<section class="grid md:grid-cols-2 gap-6">
<form id="loginForm" class="card grid gap-3">
<h3 class="text-xl font-semibold">Prijava</h3>
<input name="email" type="email" class="field" placeholder="Email" required>
<input name="password" type="password" class="field" placeholder="Lozinka" required>
<button class="btn btn-primary w-full">Prijavi se</button>
</form>
<form id="registerForm" class="card grid gap-3">
<h3 class="text-xl font-semibold">Registracija</h3>
<input name="email" type="email" class="field" placeholder="Email" required>
<input name="password" type="password" class="field" placeholder="Lozinka (min 6)" minlength="6" required>
<button class="btn btn-outline w-full">Kreiraj nalog</button>
</form>
</section>`;
  },
  mount() {
    const u = User.get();
    if (u) {
      $("#logoutBtn").on("click", User.logout);
      return;
    }
    $("#loginForm").on("submit", function (e) {
      e.preventDefault();
      const email = this.email.value.trim();
      const pass = this.password.value;
      if (!email || pass.length < 3) return toastr.error("Provjerite podatke");
      User.set({ email });
      toastr.success("Dobrodošli!");
      location.hash = "#/home";
    });
    $("#registerForm").on("submit", function (e) {
      e.preventDefault();
      const email = this.email.value.trim();
      const pass = this.password.value;
      if (pass.length < 6)
        return toastr.warning("Lozinka mora imati min 6 znakova");
      User.set({ email });
      toastr.success("Registrovani!");
      location.hash = "#/home";
    });
  },
};
