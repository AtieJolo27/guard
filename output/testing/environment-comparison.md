# SDVRS Playwright environment comparison

Run: October 15, 2026.

Local: http://127.0.0.1:8003, existing SDVRS configuration and database.

Test: http://127.0.0.1:8010, isolated temporary SQLite database, generated QA account, built frontend assets, email delivery disabled. The test server was stopped after the run.

Result: 4 passed, 1 skipped, 0 failed.

| Login viewport | Different pixels | Difference |
|---|---:|---:|
| 1440 x 1000 | 0 | 0.0000% |
| 390 x 844 | 0 | 0.0000% |

Passed: login layout and assets in both environments; CSRF and unauthenticated-access checks; isolated admin navigation, monthly chart interaction, insights dropdown, Other violation field, date restrictions and mobile navigation.

Skipped: authenticated local checks because E2E_LOCAL_LOGIN / E2E_LOCAL_PASSWORD were not supplied. No deployed staging URL was supplied, so this is a local-versus-isolated-test comparison, not a staging or production validation. No incident reports were submitted and no meeting emails were sent.

Detailed HTML report: playwright-report/index.html. Setup: docs/security/PLAYWRIGHT.md.
