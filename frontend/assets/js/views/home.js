window.Views = window.Views || {};
Views.home = {
  render() {
    return `
<section class="grid gap-6">
<div class="bg-white rounded-2xl shadow overflow-hidden">
<div class="bg-[url('https://images.unsplash.com/photo-1508606572321-901ea443707f?q=80&w=2000&auto=format&fit=crop')] bg-cover bg-center">
<div class="bg-blue-900/70 px-6 py-16 sm:py-24">
<h2 class="text-3xl sm:text-4xl font-bold text-white">Dobrodošli na Grbavicu!</h2>
<p class="mt-3 text-blue-100 max-w-2xl">FK Željezničar — fan SPApp: tim, utakmice, shop i više. Bez reload-a, sve na jednoj stranici.</p>
<div class="mt-6 flex gap-3">
<a href="#/squad" class="btn btn-primary">Pogledaj Tim</a>
<a href="#/shop" class="btn btn-outline">Kupi opremu</a>
</div>
</div>
</div>
</div>
<div class="grid sm:grid-cols-3 gap-4">
<div class="card">
<h3 class="font-semibold text-lg">Brza navigacija</h3>
<p class="text-sm text-slate-600">SPApp rute: home, tim, utakmice, shop, korpa, prijava, kontakt.</p>
</div>
<div class="card">
<h3 class="font-semibold text-lg">LocalStorage</h3>
<p class="text-sm text-slate-600">Korpa i korisnik se pamte lokalno — nema backenda.</p>
</div>
<div class="card">
<h3 class="font-semibold text-lg">Čisti UI</h3>
<p class="text-sm text-slate-600">Tailwind + custom CSS za moderan izgled.</p>
</div>
</div>
</section>`;
  },
  mount() {},
};
