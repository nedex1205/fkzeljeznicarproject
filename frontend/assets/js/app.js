window.LS = {
  get(key, def) {
    try {
      return JSON.parse(localStorage.getItem(key)) ?? def;
    } catch {
      return def;
    }
  },
  set(key, val) {
    localStorage.setItem(key, JSON.stringify(val));
  },
  remove(key) {
    localStorage.removeItem(key);
  },
};

window.Keys = { CART: "zeljo_cart", USER: "zeljo_user" };

window.Cart = {
  all() {
    return LS.get(Keys.CART, []);
  },
  save(c) {
    LS.set(Keys.CART, c);
    App.updateCartBadge();
  },
  add(id) {
    const c = this.all();
    const ex = c.find((i) => i.id === id);
    ex ? ex.qty++ : c.push({ id, qty: 1 });
    this.save(c);
    toastr.success("Dodano u korpu");
  },
  remove(id) {
    this.save(this.all().filter((i) => i.id !== id));
  },
  qty(id, delta) {
    this.save(
      this.all().map((i) =>
        i.id === id ? { ...i, qty: Math.max(1, i.qty + delta) } : i
      )
    );
  },
  clear() {
    this.save([]);
  },
};

window.User = {
  get() {
    return LS.get(Keys.USER, null);
  },
  set(u) {
    LS.set(Keys.USER, u);
    App.updateNavUser();
  },
  logout() {
    LS.remove(Keys.USER);
    App.updateNavUser();
    toastr.info("Odjavljeni ste");
  },
};

window.App = {
  updateCartBadge() {
    const n = Cart.all().reduce((s, i) => s + i.qty, 0);
    $("#cartCount").text(n || "0");
  },
  updateNavUser() {
    const u = User.get();
    $("#navUser").text(u ? "Profil" : "Prijava");
  },
  setActiveNav(view) {
    $(".nav-link").removeClass("link-active");
    $(`a[href='#${view}']`).addClass("link-active");
  },
};

$(function () {
  $("#year").text(new Date().getFullYear());
  App.updateCartBadge();
  App.updateNavUser();

  const app = $.spapp({ defaultView: "home", templateDir: "" });

  function register(viewId, mountFn) {
    app.route({
      view: viewId,
      onCreate() {},
      onReady() {
        if (typeof mountFn === "function") mountFn();
      },
    });
  }

  // Views are defined on window.Views by separate files
  register("home", Views.home?.mount);
  register("squad", Views.squad?.mount);
  register("matches", Views.matches?.mount);
  register("shop", Views.shop?.mount);
  register("cart", Views.cart?.mount);
  register("login", Views.auth?.mount);
  register("contact", Views.contact?.mount);

  app.run();
});
