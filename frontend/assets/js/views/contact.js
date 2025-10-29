window.Views = window.Views || {};
Views.contact = {
  render() {
    return `
<section class="grid gap-4 max-w-2xl">
<form id="contactForm" class="card grid gap-3">
<h3 class="text-xl font-semibold">Kontakt</h3>
<input name="name" class="field" placeholder="Ime i prezime" required>
<input name="email" type="email" class="field" placeholder="Email" required>
<textarea name="msg" class="field" rows="4" placeholder="Poruka..." required></textarea>
<button class="btn btn-primary w-fit">Pošalji</button>
</form>
<div class="card">
<h4 class="font-semibold mb-2">Adresa</h4>
<p>Stadion Grbavica, Sarajevo</p>
</div>
</section>`;
  },
  mount() {
    $("#contactForm").on("submit", function (e) {
      e.preventDefault();
      const { name, email, msg } = Object.fromEntries(
        new FormData(this).entries()
      );
      if (!name || !email || !msg) return toastr.error("Popunite sva polja");
      toastr.success("Poruka poslana! (demo)");
      this.reset();
    });
  },
};
