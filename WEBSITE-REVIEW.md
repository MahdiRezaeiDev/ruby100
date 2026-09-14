# Ruby100 website review

Implemented: response security headers, JSON-LD script escaping, removal of executable review HTML in favour of validated Google Maps embed URLs, server-side service/phone validation, queued email notifications with retries, prevention of seeded administrator password resets, duplicate migration fix, four non-destructive service guides, smaller blog list queries, lazy images and priority hero loading, readable WhatsApp button contrast, and content visibility when JavaScript is unavailable.

The four guides cover booking a tow, unwanted vehicle collection, SUV transport and machinery transport enquiries. Run `php artisan db:seed --class=ServiceGuidesSeeder` on other environments. Existing matching posts are preserved. Do not run the general DatabaseSeeder on a live database: it contains demo content and replaces gallery entries.

## Remaining deployment requirements

- All seven supplied photos are installed as local WebP images with responsive 640px and 960px variants. Original JPEG files are preserved. Hero, about, service images, gallery and blog covers use the supplied photographs.
- The local environment uses `MAIL_MAILER=log`. Configure a real mail transport and run a supervised `php artisan queue:work --tries=3` worker for delivery; queued notifications are not proof of delivered email. Saved enquiries remain available to administrators.
- Set `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true` and the real HTTPS `APP_URL` on the production host. Keep the document root at `public`, enable TLS and PHP OPcache, then run `php artisan optimize`. Never expose the project root, database, logs or .env over HTTP.
- The earlier general seeder used a known default administrator password. Existing credentials have not been changed to avoid locking out the owner; rotate that password before publication if it was used.
- Validate HTTPS, mail delivery, mobile layout and real PageSpeed metrics on the actual deployed site. No production security guarantee or measured PageSpeed score is claimed.

Authorization review reference: https://filamentphp.com/docs/5.x/advanced/security
