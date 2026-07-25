# Blade Contract

- Active views live under `resources/views` with lowercase directories.
- Layouts use `@vite`.
- Bootstrap is bundled through Vite.
- No Bootstrap CDN in active layouts.
- No jQuery unless a reviewed issue proves it is required.
- No Razor/C# markers:
  - `@using`
  - `Html.BeginForm`
  - `@Html.`
  - `Model.`
  - `ViewBag`
  - `ViewData`
- Forms that change data must include `@csrf`.
- Update/delete forms must include `@method`.
- Blade must render prepared data; queries and workflow logic belong in Services.
