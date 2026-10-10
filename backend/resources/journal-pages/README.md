# Journal pages and navigation

Journal content is owned by the backend's `journal_pages` table. The public Laravel application reads it from the configured backend API, or from its local database when no remote journal is used. Run the backend migrations against the database used by the site; do not run a second copy in `public` when the applications share a database.

## Deploy

From the repository root:

```sh
cd backend
php artisan migrate
php artisan route:clear
cd ../public
php artisan route:clear
cd ../frontend
npm run build
```

Set `REACT_APP_MAIN_API` to the backend API base URL (including `/api/`) before building React. It falls back to `http://127.0.0.1:8000/api/`. Set `PUBLIC_API_BASE_URL` on the public Laravel application to that API base when using a separate backend. Rebuild configuration caches after changing environment settings. Stale committed route caches were removed as part of this change so they cannot hide the new endpoints.

The first migration creates the table. The second copies defaults for existing journals, including each journal's own aim and scope. New journals created through the existing backend journal model receive defaults automatically. Defaults are never regenerated on page reads, so deleted pages stay deleted. Migrations were verified with isolated SQLite databases; the existing application database was not migrated during implementation.

## Edit pages

In React admin, open **All Journals → Pages & navigation**. The route is `/admin/journal-pages/{journal-slug}`.

- Content pages have an editable title, URL slug, and rich text body.
- Link pages use a destination URL instead of content. Choose **Link (external URL or internal path)** and enter an address such as `https://www.google.com` or `/Payment`. Navbar items, dropdown items, and journal tabs open the destination directly in the same tab. Internal paths are case-sensitive; the existing payment route is `/Payment`. Use the full `https://` prefix for external addresses, not `https:google.com`. Run the additional `2026_09_16_000003` migration on existing installations to add the destination column.
- Menu groups create one level of dropdown navigation. Assign pages to a group using the Menu group field.
- System sections display the journal's current issue, archive, or editorial panel using the existing records and management screens.
- Menu order sorts ascending, with ID as the tie-breaker. Navbar and journal-tab visibility are independent.
- Published pages are public. Draft pages return 404. A draft group also hides its children, including their direct URLs. A published page hidden from navigation is still accessible by URL and included in the sitemap.
- Move or delete a group's children before deleting the group or changing its type.

Public page URLs are `/journal/{journal-slug}/pages/{page-slug}`. Existing `?view=about`, `?view=current_issue`, `?view=archive`, and `?view=editorial_board` links resolve to published configured pages. Editing a URL slug changes its URL; update any manually authored links accordingly.

The navbar and tabs on journal pages use these records. Publisher/home navigation is unchanged. Application and payment pages in each journal have editable introductory content and link to the existing shared submission and checkout workflows. This feature does not change those workflows or their data models.

`default-content.json` is a snapshot of the existing publisher policy templates at implementation time. Each journal receives its own database copy. Later changes to publisher templates do not overwrite journal content. For journals without an existing standard publication fee, the initial page asks readers to contact the editorial office until an editor supplies the fee.

Rich text is sanitized on save and again on public rendering using the same `JournalPageHtml` helper in each Laravel application. Headings, lists, basic emphasis, tables, safe links, and raster images are supported. Scripts, event handlers, embeds, and arbitrary CSS are removed. Content is never evaluated as Blade/PHP.

## API

Public: `GET /api/journals/{slug}/pages` and `GET /api/journals/{slug}/pages/{page-slug}`.

Authenticated with the existing JWT `auth:api` guard:

- `GET /api/admin/journals/{slug}/pages`
- `POST /api/admin/journals/{slug}/pages`
- `PUT /api/admin/journals/{slug}/pages/{id}`
- `DELETE /api/admin/journals/{slug}/pages/{id}`

Writes enforce journal ownership, unique per-journal slugs, valid parent groups, field limits, and allowed system sections. Validation errors use Laravel's `422` response. There are no new public write endpoints.

## Verification

```sh
backend/vendor/bin/phpunit --do-not-cache-result --bootstrap backend/vendor/autoload.php backend/tests
cd public
vendor/bin/phpunit --do-not-cache-result --filter 'JournalPagesTest|SeoTest'
cd ../frontend
CI=true npm test -- --watchAll=false --runInBand
npm run build
```
