window.Views = window.Views || {};
Views.squad = {
  render() {
    const positions = ["SVE", "GK", "DF", "MF", "FW"];
    return `
<section class="grid gap-4">
<div class="flex flex-wrap items-center gap-3">
<input id="playerSearch" class="field max-w-xs" placeholder="Pretraga igrača..."/>
<select id="posFilter" class="field max-w-[160px]">${positions
      .map((p) => `<option value="${p}">${p}</option>`)
      .join("")}</select>
</div>
<div id="playersGrid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
</section>`;
  },
  async mount() {
    const res = await fetch("data/players.json");
    const players = await res.json();
    const $grid = $("#playersGrid");
    function render() {
      const q = ($("#playerSearch").val() || "").toLowerCase();
      const pf = $("#posFilter").val();
      const data = players.filter(
        (p) =>
          (pf === "SVE" || p.position === pf) &&
          (p.name.toLowerCase().includes(q) || ("" + p.number).includes(q))
      );
      $grid.html(
        data
          .map(
            (p) => `
<div class="card flex items-center gap-4">
<div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 font-bold">${p.number}</div>
<div>
<div class="font-semibold">${p.name}</div>
<div class="text-xs text-slate-600">Pozicija: ${p.position}</div>
</div>
</div>`
          )
          .join("")
      );
    }
    $("#playerSearch, #posFilter").on("input change", render);
    render();
  },
};
