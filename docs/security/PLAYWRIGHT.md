# Playwright environment comparison

Run `npm run test:e2e` with Node 24.11+ and Google Chrome installed. Start SDVRS at http://127.0.0.1:8003 first with `php artisan serve --host=127.0.0.1 --port=8003`. If reinstalling dependencies, use `npm ci --legacy-peer-deps` because Vite Plus is installed through a Vite alias. Build the application before comparisons with `npm run build`.

## Default run

- Local: http://127.0.0.1:8003 (your existing .env/database).
- Test: http://127.0.0.1:8010 (temporary SQLite database, temporary generated admin, built frontend assets, and MAIL_MAILER=array).
- The test server is started and stopped by the suite. Its database is separate from your application database and removed afterward.
- Your local database is not seeded or modified. Local login/CSRF checks do not submit incidents or send emails. Authenticated local checks are skipped unless you supply a QA account.

The suite checks desktop/mobile login rendering, CSS loading, oversized icons, horizontal overflow, CSRF and access restrictions. The isolated test account also checks admin pages, monthly chart clicks/keyboard navigation, insights dropdown behavior, Other violation input, date restrictions, and mobile navigation. The default suite does not submit reports or schedule meetings.

Screenshots of the same login screen are compared between the two environments at 1440x1000 and 390x844. A difference above 2% fails the comparison. The HTML report includes local/test images, diff images and the measured difference. Both captures use the same Chrome installation, locale, timezone and viewport. Authenticated dashboards are recorded separately, not compared pixel-for-pixel, because database content differs.

## Credentials and deployed test URL

Copy `.env.playwright.example` to `.env.playwright` (ignored by Git), fill QA credentials locally, and run:

```powershell
node --env-file=.env.playwright node_modules/@playwright/test/cli.js test
```

For authenticated local checks set E2E_LOCAL_LOGIN and E2E_LOCAL_PASSWORD. Do not use a production administrator's credentials for routine automation.

For an existing test/staging environment set E2E_REMOTE_TEST=1, E2E_TEST_URL, E2E_TEST_LOGIN and E2E_TEST_PASSWORD. This disables automatic database/server creation and only visits the supplied test environment. A deployed test environment needs its own QA database and safe mail configuration; the runner cannot change its server settings.

Optional E2E_VISUAL_TOLERANCE overrides the 0.02 default. Do not increase it to hide a real stylesheet or layout regression.

## Results

- `playwright-report/index.html`: interactive report (`npm run test:e2e:report`).
- `test-results/results.json`: machine-readable results.
- `test-results/`: screenshots, image differences and failure traces.
- `tmp/playwright/server.log`: isolated PHP server log.

Artifacts are ignored by Git and can contain account information or student data when authenticated local/staging checks are enabled. Keep them private. Playwright traces can include form input values; use disposable QA credentials and do not publish traces containing real passwords.

A skipped credential-dependent test is not a pass. A successful isolated test run does not prove the deployed staging environment or production SMTP works.

### Local URL selection

The comparison run used SDVRS at http://127.0.0.1:8003 because port 8000 was serving another project. If needed start SDVRS with `php artisan serve --host=127.0.0.1 --port=8003` and set E2E_LOCAL_URL accordingly. The suite verifies the login page title so it cannot silently compare the wrong project.
