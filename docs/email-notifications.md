# Violation email notifications

Confirming a violation queues a notice to `<sr-code>@g.batstate-u.edu.ph`.
The notice includes the student's name, SR code, violation, severity, recording date, and record number.

Configure a working mail provider in `.env` (the default `log` mailer only writes messages to logs):

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_FROM_ADDRESS=your-authorized-sender@example.com
MAIL_FROM_NAME="Guard System"
```

Use your provider's required scheme and port (`smtps` with port 465 for implicit TLS).
After changing configuration, run `php artisan config:clear`.

With `QUEUE_CONNECTION=database`, ensure the jobs tables exist by running
`php artisan migrate`, then keep `php artisan queue:work --tries=3` running.
`composer run dev` also starts a queue listener. Restart long-running workers
with `php artisan queue:restart` after deployment or configuration changes.

For local use without a persistent worker, set `QUEUE_CONNECTION=sync`.
Notifications then send during the save request, which may take a few seconds.
Automatic queue retries require the database queue and a running worker.

Delivery failures are retried up to three times. Inspect exhausted jobs with
`php artisan queue:failed` and retry them with `php artisan queue:retry <id>`
after correcting the mail configuration. A failure to queue the notification
is reported to the guard without discarding the saved violation.
