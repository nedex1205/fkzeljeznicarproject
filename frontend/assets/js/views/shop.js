window.Views = window.Views || {};
Views.shop = {
  async mount() {
    const products = await (await fetch("data/products.json")).json();

    const $grid = $("#productsGrid");
    const $q = $("#shopSearch");
    const $cat = $("#shopCategory");
    const $sort = $("#shopSort");
    const $reset = $("#shopReset");

    function applyFilters() {
      const query = ($q.val() || "").toLowerCase().trim();
      const cat = $cat.val();
      let data = products.filter((p) => {
        const matchesQuery =
          p.name.toLowerCase().includes(query) ||
          (p.description || "").toLowerCase().includes(query);
        const matchesCat = cat === "Sve" || p.category === cat;
        return matchesQuery && matchesCat;
      });

      // sort
      switch ($sort.val()) {
        case "name_asc":
          data.sort((a, b) => a.name.localeCompare(b.name));
          break;
        case "name_desc":
          data.sort((a, b) => b.name.localeCompare(a.name));
          break;
        case "price_asc":
          data.sort((a, b) => a.price - b.price);
          break;
        case "price_desc":
          data.sort((a, b) => b.price - a.price);
          break;
      }
      return data;
    }

    function render() {
      const data = applyFilters();
      if (!data.length) {
        $grid.html(
          `<div class="col"><div class="alert alert-secondary">Nema proizvoda za prikazane filtere.</div></div>`
        );
        return;
      }
      $grid.html(data.map((p) => card(p)).join(""));
    }

    function card(p) {
      const badge = p.badge
        ? `<span class="badge text-bg-primary ms-2">${p.badge}</span>`
        : "";
      return `
      <div class="col">
        <div class="card card-zeljo h-100">
          <img src="${p.img}" class="card-img-top" alt="${p.name}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <h6 class="card-title mb-1">${p.name}${badge}</h6>
              <div class="text-primary fw-semibold">${p.price.toFixed(
                2
              )} KM</div>
            </div>
            <small class="text-secondary d-block">${p.category}</small>
          </div>
          <div class="card-footer bg-white border-0 d-grid gap-2">
            <button class="btn btn-outline-primary" data-detail="${
              p.id
            }">Detalji</button>
            <button class="btn btn-primary" data-add="${
              p.id
            }">Dodaj u korpu</button>
          </div>
        </div>
      </div>`;
    }

    // Handleri
    $grid.on("click", "[data-add]", function () {
      const id = +$(this).data("add");
      Cart.add(id);
    });

    $grid.on("click", "[data-detail]", function () {
      const id = +$(this).data("detail");
      const p = products.find((x) => x.id === id);
      if (!p) return;

      const sizes =
        p.sizes && p.sizes.length
          ? `<div class="mb-2"><strong>Veličine:</strong> ${p.sizes.join(
              ", "
            )}</div>`
          : "";
      const desc = p.description ? `<p class="mb-2">${p.description}</p>` : "";
      const specs = p.specs
        ? `
        <ul class="list-group list-group-flush mb-2">
          ${Object.entries(p.specs)
            .map(
              ([k, v]) =>
                `<li class="list-group-item d-flex justify-content-between"><span>${k}</span><strong>${v}</strong></li>`
            )
            .join("")}
        </ul>`
        : "";

      const body = `
        <div class="vstack gap-2">
          <img src="${p.img}" class="img-fluid rounded" alt="${p.name}">
          ${desc}
          ${sizes}
          ${specs}
          <div class="d-flex justify-content-between align-items-center">
            <div class="h5 m-0 text-primary">${p.price.toFixed(2)} KM</div>
            <button class="btn btn-primary" data-add="${
              p.id
            }">Dodaj u korpu</button>
          </div>
        </div>`;
      App.showModal(p.name, body);
    });

    $q.on("input", render);
    $cat.on("change", render);
    $sort.on("change", render);
    $reset.on("click", function () {
      $q.val("");
      $cat.val("Sve");
      $sort.val("name_asc");
      render();
    });

    render();
  },
};
