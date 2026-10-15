import { defineConfig } from '@playwright/test';
import crypto from 'node:crypto';
if (process.env.E2E_REMOTE_TEST && !process.env.E2E_TEST_URL) throw new Error('Set E2E_TEST_URL for the deployed test environment.');
process.env.E2E_LOCAL_URL ||= 'http://127.0.0.1:8003';
process.env.E2E_TEST_URL ||= 'http://127.0.0.1:8010';
const isolated = !process.env.E2E_REMOTE_TEST;
process.env.E2E_TEST_LOGIN ||= isolated ? 'playwright@example.test' : '';
process.env.E2E_TEST_PASSWORD ||= isolated ? crypto.randomBytes(24).toString('hex') : '';
export default defineConfig({
    testDir: './tests/browser', timeout: 60000, workers: 1, retries: 0,
    outputDir: 'test-results',
    reporter: [['list'], ['html', { outputFolder: 'playwright-report', open: 'never' }], ['json', { outputFile: 'test-results/results.json' }]],
    use: { channel: 'chrome', viewport: { width: 1440, height: 1000 }, locale: 'en-US', timezoneId: 'Asia/Singapore', trace: 'retain-on-failure', screenshot: 'only-on-failure' },
    globalSetup: './tests/browser/global-setup.mjs',
});
