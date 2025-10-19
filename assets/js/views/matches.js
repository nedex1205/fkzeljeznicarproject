window.Views = window.Views || {};
Views.matches = {
  render() {
    const comps = ["Sve", "Premijer liga", "Kup BiH"];
    return `
<section class="grid gap-4">
<div class="flex flex-wrap items-center gap-3">
<select id="compFilter" class="field max-w-[200px]">${comps
      .map((c) => `<option value="${c}">${c}</option>`)
      .join("")}</select>
</div>
<div class="card overflow-x-auto">
<table class="w-full text-sm">
<thead class="text-left text-slate-600">
<tr>
<th class="py-2">Datum</th>
<th>Protivnik</th>
<th>Stadion</th>
<th>Takmičenje</th>
</tr>
</thead>
<tbody id="matchesBody"></tbody>
</table>
</div>
</section>`;
  },
  async mount() {
    const res = await fetch("data/matches.json");
    const matches = await res.json();
    const $body = $("#matchesBody");
    function render() {
      const cf = $("#compFilter").val();
      const data = matches
        .filter((m) => cf === "Sve" || m.competition === cf)
        .sort((a, b) => a.date.localeCompare(b.date));
      $body.html(
        data
          .map(
            (m) => `
<tr class="border-t">
<td class="py-2">${m.date}</td>
<td>${m.opponent}</td>
<td>${m.venue}</td>
<td>${m.competition}</td>
</tr>`
          )
          .join("")
      );
    }
    $("#compFilter").on("change", render);
    render();
  },
};
