# Domain

Business logic grouped by area. Controllers, Filament resources and console
commands stay thin and call into these classes.

| Folder | Area |
|---|---|
| `Content/` | Shared content helpers (slugs, reading time, blog content) |
| `Pages/` | The fixed site pages (`SitePages`): routes, default titles and descriptions |
| `Tools/` | SEO data of the free tool pages |
| `Media/` | Importing existing images into the Curator library |
| `Settings/` | Site settings |
| `Site/` | Favicon, manifest, public assets, social channels |

Inside an area:

- `Actions/` — one class, one task, a single public `execute()` method.
- `Data/` — typed data objects passed between layers.
- `Rules/` — validation rules owned by the area.

`Modules/` is reserved for optional feature packs (Ecommerce, RealEstate…)
that can later be enabled per tenant plan.
