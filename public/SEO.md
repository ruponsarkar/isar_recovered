# Search indexing setup

The public Laravel site serves a live `/sitemap.xml` sitemap index, batched journal, issue and published-article XML maps, and a static-pages map. No manual regeneration is needed when articles are added. Metadata includes canonical URLs, social cards, per-author Highwire citation tags, Dublin Core, and ScholarlyArticle JSON-LD. The PDF metadata URL is an inline same-directory `/viewArticle/{slug}/fulltext.pdf` endpoint; crawler visits do not increment downloads.

## Deployment

1. Set `APP_URL` in `.env` to the real public HTTPS domain (not localhost). Canonical and sitemap URLs use this value. Configure `ASSET_BASE_URL` correctly if PDFs/images live on the backend server. Prefer locally available PDFs for scholarly crawling.
2. From this project run `php artisan config:clear`, `php artisan view:clear`, and `php artisan seo:robots`. The last command updates the existing static robots.txt, because web servers normally serve that file before Laravel's robots route. Run it again when changing domains.
3. Point the web-server document root to this project's `public/` directory. Ensure `/sitemap.xml`, its child maps, article abstracts and PDFs are publicly reachable with no login, WAF challenge or maintenance page. Configure PHP fallback for XML paths. Keep React admin on its existing separate location.
4. Verify domain ownership in Google Search Console, submit `https://YOUR-DOMAIN/sitemap.xml`, and inspect representative journal/article URLs. Optionally submit the same sitemap in Bing Webmaster Tools. This change does not submit anything externally.
5. Check article title, individual author records, publication date, DOI, ISSN, volume/issue and page ranges in actual data. Legacy comma-separated names are used only if structured authors are absent. Do not put affiliations/degrees into author names. PDFs should contain searchable text, title/authors and references; Google Scholar recommends files no larger than 5 MB. Missing or unavailable PDFs must be fixed at the source.

Submission/payment pages are noindex; payment-result URLs are excluded from maps. Journal About/Archive/Editorial Board tabs have individual canonical URLs; the default/current issue tab uses the base journal URL. Unknown query parameters do not create new canonical URLs.

Sitemaps/metadata facilitate discovery but cannot promise indexing, inclusion in Google Scholar, indexing dates, DOI registration, or database accreditation. OJS-style metadata is compatibility, not installation of OJS itself.

References: https://scholar.google.com/intl/en/scholar/inclusion.html and https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap
