# Security testing and deployment

This application uses cookie sessions and Laravel's web CSRF protection. It does not expose a bearer-token API. Do not disable CSRF to make Postman requests work.

## Postman authentication

1. GET /login and retain the session cookie in Postman's cookie jar.
2. Read the meta[name="csrf-token"] value (or the hidden _token input).
3. POST /login with email, password and _token. Alternatively send the token as X-CSRF-TOKEN. Keep cookies enabled.
4. After login, GET /admin/dashboard and read the new CSRF token: successful authentication regenerates the session.
5. Include that token and session cookie on every POST/PATCH/DELETE. Logout invalidates the session.

Use a dedicated QA database and a test mailer (log/array or a mail sandbox). Scheduling a meeting with real SMTP sends actual student and guard emails. Never run destructive automation against live student records.

## Expected security results

| Check | Expected |
|---|---|
| Missing/incorrect CSRF on writes | 419 |
| No admin session or guard accessing admin pages/actions | 403 |
| Repeated login attempts | 429 after 5 per account/IP per minute; also 20/IP/minute |
| Student lookup enumeration | 429 after 30 requests per user/IP/minute |
| Report writes/review actions | 429 after 15 requests per user/IP/minute |
| Invalid arrays, oversized fields, bad dates/IDs | 422 with Accept: application/json |
| Non-numeric case URL ID or malformed SR-Code URL | 404 |
| Write on closed case | 422 |
| Simultaneous update while case lock held | 409 |
| Replay same report submission token | One report, record and audit entry |
| Replay confirmed case action | One disciplinary record |
| Replay same meeting date after successful notices | No duplicate emails |
| Submitted HTML/JavaScript | Escaped in pages/PDF, never executed |
| Requests larger than 128 KiB Content-Length | 413 |
| Source/.env/storage paths through Apache | Denied; web root must be public/ |

Send X-Forwarded-For from several values: it must not bypass rate limits. Trust only the actual reverse proxy, never all user-supplied proxies. Invalid credentials return the same message regardless of whether the account exists.

Security regression suite: php artisan test --compact
Dependency checks: composer audit and npm audit
Frontend build: npm run build

## Production configuration

- Point Apache/Nginx's document root at this project's public/ directory, never the repository root. The root .htaccess denies source access when using Apache with overrides enabled. Nginx requires equivalent server configuration. Block dotfiles and never serve storage/, vendor/, tmp/, output/ or .env files.
- Use HTTPS. Set APP_ENV=production, APP_DEBUG=false, APP_URL to the HTTPS origin, SESSION_SECURE_COOKIE=true, SESSION_HTTP_ONLY=true, SESSION_SAME_SITE=lax, SESSION_ENCRYPT=true. Keep APP_KEY secret. Do not rotate an existing key without planning session/data invalidation.
- Use persistent database/Redis session and cache stores. Multiple application nodes must share the cache so rate limits and case locks are enforced across all nodes. File cache is single-host only; array/null stores are not suitable for production.
- Stop the Vite development server, remove public/hot, run npm run build, then php artisan config:cache. The production CSP only allows same-origin scripts and per-response nonces. Styles currently allow inline CSS for existing dynamic layouts.
- Use a currently supported Node runtime compatible with package.json (Node 24.11+ for the updated build tool). Do not serve node_modules/ publicly.
- Use least-privilege database and SMTP credentials; restrict access to backups and log files. No secrets should be committed to Git. Rotate any previously exposed credentials.
- Set SMTP delivery appropriately; a mail-service acceptance is not proof of inbox delivery. Delivery errors shown in the UI are generic. Details remain in protected server logs.
- Restrict signup/account creation to authorized administrators, use strong unique administrator passwords, and expire sessions according to campus policy. Login is still administrator-only; guards have routes but no public login flow.
- Configure web-server request limits, PHP post_max_size/upload_max_filesize, TLS and server patching. Application middleware rejects declared large request bodies, but the web server must enforce streamed/chunked body limits as well.

## Scope and remaining work

These changes reduce verified risks; they do not certify the system as vulnerability-free. Validate TLS, file access, cookie flags, proxy trust, concurrent writes and SMTP behavior on the actual QA deployment. MFA, centralized security monitoring, account lifecycle controls and backups require a separate operational setup. Only send an email to a real student when intentionally scheduling an actual meeting.

Import docs/security/postman-security-smoke.json into Postman for a non-destructive smoke run. Set base_url, admin_login and admin_password locally (use Postman Vault for the password). It only logs in/out and checks access/validation; it does not create cases or send mail. The collection's variables contain no real credentials. Keep the cookie jar enabled and run the requests in order.
