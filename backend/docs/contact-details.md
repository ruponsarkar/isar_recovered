# Contact details

The React admin menu has a **Contact Details** section at `/admin/contact-details`.
Select **Default contact** to edit the home/footer contact. Select a journal to
supply overrides. Empty journal fields inherit the corresponding default field;
**Use default contact** removes that journal's overrides.

## Deploy

Run the migration against the backend database:

```sh
cd backend
php artisan migrate --path=database/migrations/2026_10_01_000001_create_contact_details_table.php
php artisan route:clear
```

Build/deploy the React frontend and deploy the changed public Laravel application.
Clear/rebuild its existing route and view caches using the normal deployment process.
The public application reads the backend's `contact-details` endpoint when
`services.public_api.base_url` is set; otherwise it reads `contact_details` from
its database. A standalone public database must receive the same migration.
Existing hardcoded contact details remain the initial default until edited.

The same saved contact renders in the footer and Contact Us page. `/contact-us` uses
the default. `/contact-us?journal=journal-slug` (or numeric journal ID) uses journal
contacts. Footer contact links preserve journal context; journal navigation links
configured with `/contact-us` or the legacy `/action` also preserve it. Journal, issue and article views use
the journal already associated with that page.

Admin reads/writes require existing `auth:api` authentication. Public endpoints
only return resolved contact fields. No production database migration is run by
the automated test suite; tests use in-memory SQLite.

The legacy `/action` URL redirects permanently to `/contact-us`.
