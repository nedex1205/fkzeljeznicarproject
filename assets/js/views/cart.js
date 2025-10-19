window.Views = window.Views || {};
Views.cart = {
  render() {
    return `
<section class="grid gap-4">
<div class="card overflow-x-auto">
<table class="w-full text-sm">
<thead class="text-left text-slate-600">
<tr>
<th class="py-2">Proizvod</th>
<th>Cijena</th>
<th>Količina</th>
<th>Ukupno</th>
<th></th>
</tr>
</thead>
<tbody id="cartBody"></tbody>
</table>
</div>
<div class="flex items-center justify-between">
<button id="clearCart" class="btn btn-outline">Isprazni korpu</button>
<div class="text-right">
<div class="text-slate-600">Međuzbir: <span id="cartSubtotal" class="font-semibold text-slate-900">0.00 KM</span></div>
<button id="checkoutBtn" class="btn btn-primary mt-2">Kupi</button>
</div>
</div>
</section>`;
  },
  async mount() {
    const products = await (await fetch("data/products.json")).json();
    const $body = $("#cartBody");
    const $subtotal = $("#cartSubtotal");
    function render() {
      const cart = Cart.all();
      if (cart.length === 0) {
        $body.html(
          `<tr><td class="py-3" colspan="5">Korpa je prazna.</td></tr>`
        );
        $subtotal.text("0.00 KM");
        return;
      }
      let sum = 0;
      $body.html(
        cart
          .map((item) => {
            const p = products.find((x) => x.id === item.id);
            const line = p.price * item.qty;
            sum += line;
            return `
<tr class="border-t align-middle">
<td class="py-2">
<div class="flex items-center gap-3">
<img class="w-14 h-14 object-cover rounded-xl" src="${p.img}" alt="${p.name}">
<div>
<div class="font-semibold">${p.name}</div>
<div class="text-xs text-slate-600">ID: ${p.id}</div>
</div>
</div>
</td>
<td>${p.price.toFixed(2)} KM</td>
<td>
<div class="inline-flex items-center gap-2">
<button class="btn btn-outline px-2" data-dec="${p.id}">−</button>
<span class="min-w-[2ch] inline-block text-center">${item.qty}</span>
<button class="btn btn-outline px-2" data-inc="${p.id}">+</button>
</div>
</td>
<td>${line.toFixed(2)} KM</td>
<td><button class="btn btn-outline" data-del="${p.id}">Ukloni</button></td>
</tr>`;
          })
          .join("")
      );
      $subtotal.text(sum.toFixed(2) + " KM");
    }
    $("#clearCart")
      .off("click")
      .on("click", () => {
        Cart.clear();
        render();
        toastr.info("Korpa je ispražnjena");
      });
    $("#checkoutBtn")
      .off("click")
      .on("click", () => {
        if (Cart.all().length === 0) return toastr.warning("Korpa je prazna");
        if (!User.get()) return toastr.error("Prijavite se prije kupovine");
        Cart.clear();
        render();
        toastr.success("Kupovina uspješna! Hvala.");
      });
    $body
      .off("click")
      .on("click", "[data-inc]", function () {
        Cart.qty(+$(this).data("inc"), +1);
        render();
      })
      .on("click", "[data-dec]", function () {
        Cart.qty(+$(this).data("dec"), -1);
        render();
      })
      .on("click", "[data-del]", function () {
        Cart.remove(+$(this).data("del"));
        render();
        toastr.info("Proizvod uklonjen");
      });
    render();
  },
};
