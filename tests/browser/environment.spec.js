import { test, expect } from '@playwright/test';
import fs from 'node:fs';
import { PNG } from 'pngjs';
import pixelmatch from 'pixelmatch';

const environments=[['local',process.env.E2E_LOCAL_URL],['test',process.env.E2E_TEST_URL]];
async function login(page,url,username,password){
    await page.goto(url+'/login');
    await page.locator('[name=email]').fill(username);
    await page.locator('[name=password]').fill(password);
    await page.getByRole('button',{name:'Sign in to SDVRS'}).click();
    await expect(page).toHaveURL(/\/admin\/dashboard$/);
    await expect(page.getByRole('heading',{name:'Discipline Office Dashboard'})).toBeAttached();
}
async function ready(page){
    await page.waitForLoadState('networkidle');
    await page.evaluate(()=>document.fonts.ready);
    await expect.poll(()=>page.evaluate(()=>getComputedStyle(document.body).fontFamily)).toContain('Lato');
    await expect.poll(()=>page.evaluate(()=>document.documentElement.scrollWidth<=window.innerWidth+1)).toBe(true);
}

for(const [environment,url] of environments){
    test(`${environment}: login layout, assets, CSRF and unauthorized access`,async({page,request},testInfo)=>{
        const errors=[];page.on('pageerror',error=>errors.push(error.message));
        const response=await page.goto(url+'/login');expect(response.status()).toBe(200);
        await expect(page).toHaveTitle('SDVRS | Sign in');
        expect(response.headers()['content-security-policy']).toContain("frame-ancestors 'none'");
        for(const viewport of [{width:1440,height:1000},{width:390,height:844}]){
            await page.setViewportSize(viewport);await ready(page);
            const submit=page.getByRole('button',{name:'Sign in to SDVRS'});await expect(submit).toBeVisible();
            const icon=submit.locator('svg');expect((await icon.boundingBox()).width).toBeLessThan(50);
            await testInfo.attach(`${environment}-login-${viewport.width}`,{body:await page.screenshot({animations:'disabled',fullPage:true}),contentType:'image/png'});
        }
        expect((await request.get(url+'/admin/dashboard')).status()).toBe(403);
        expect((await request.post(url+'/login',{form:{email:'unused@example.test',password:'unused'},headers:{Accept:'application/json'}})).status()).toBe(419);
        expect(errors).toEqual([]);
    });

    test(`${environment}: admin pages, chart, insights and validation`,async({page},testInfo)=>{
        const prefix=environment==='local'?'E2E_LOCAL':'E2E_TEST';
        const username=process.env[prefix+'_LOGIN'],password=process.env[prefix+'_PASSWORD'];
        test.skip(!username||!password,`Provide ${prefix}_LOGIN and ${prefix}_PASSWORD to test this environment's authenticated pages.`);
        const errors=[];page.on('pageerror',error=>errors.push(error.message));
        await login(page,url,username,password);await ready(page);
        const chart=page.locator('[data-monthly-chart]');await expect(chart).toBeVisible();
        const bars=chart.locator('[data-month-label]');await expect(bars).toHaveCount(6);
        await bars.first().click();await expect(bars.first()).toHaveAttribute('aria-pressed','true');
        await bars.first().press('ArrowRight');await expect(bars.nth(1)).toHaveAttribute('aria-pressed','true');
        await expect(chart.locator('[data-chart-link]')).toHaveAttribute('href',/month=\d{4}-\d{2}/);
        await chart.locator('[data-chart-link]').click();await expect(page).toHaveURL(/month=/);
        await page.goto(url+'/admin/dashboard');
        await page.locator('.insights-menu > summary').click();await expect(page.locator('.insights-dropdown')).toBeVisible();
        await expect(page.locator('.insights-dropdown').getByRole('heading',{name:/Cases Awaiting Review/})).toBeVisible();
        await page.keyboard.press('Escape');await expect(page.locator('.insights-dropdown')).not.toBeVisible();
        await testInfo.attach(`${environment}-dashboard`,{body:await page.screenshot({fullPage:true,animations:'disabled',mask:[page.locator('[data-office-clock]')]}),contentType:'image/png'});
        for(const route of ['/admin/cases','/admin/office/students','/admin/office/categories','/admin/office/reports','/admin/office/audit','/admin/office/settings','/admin/reports/create']){
            const response=await page.goto(url+route);expect(response.status(),route).toBe(200);await ready(page);
        }
        const select=page.locator('[name=violation_id]');await select.selectOption('other');
        await expect(page.locator('[name=other_violation]')).toBeVisible();await expect(page.locator('[name=other_violation]')).toHaveAttribute('required','');
        const max=await page.locator('[name=incident_at]').getAttribute('max');expect(max).toMatch(/^\d{4}-\d{2}-\d{2}T23:59$/);
        await page.setViewportSize({width:390,height:844});await ready(page);
        await page.getByRole('button',{name:'Menu',exact:true}).click();await expect(page.locator('#office-sidebar')).toBeVisible();
        expect(errors).toEqual([]);
    });
}

test('compare local and test login appearance at desktop and mobile sizes',async({browser},testInfo)=>{
    for(const viewport of [{width:1440,height:1000},{width:390,height:844}]){
        const captures=[];
        for(const [name,url] of environments){
            const context=await browser.newContext({viewport,locale:'en-US',timezoneId:'Asia/Singapore'});
            const page=await context.newPage();await page.goto(url+'/login');await ready(page);
            const image=await page.screenshot({animations:'disabled'});captures.push(PNG.sync.read(image));
            await testInfo.attach(`${name}-${viewport.width}`,{body:image,contentType:'image/png'});await context.close();
        }
        const [local,target]=captures;expect(target.width).toBe(local.width);expect(target.height).toBe(local.height);
        const diff=new PNG({width:local.width,height:local.height});
        const changed=pixelmatch(local.data,target.data,diff.data,local.width,local.height,{threshold:0.15});
        const ratio=changed/(local.width*local.height);
        await testInfo.attach(`difference-${viewport.width}`,{body:PNG.sync.write(diff),contentType:'image/png'});
        await testInfo.attach(`comparison-${viewport.width}`,{body:Buffer.from(JSON.stringify({viewport,differentPixels:changed,differencePercent:ratio*100,local:process.env.E2E_LOCAL_URL,test:process.env.E2E_TEST_URL},null,2)),contentType:'application/json'});
        expect(ratio,'Local and test login layout should visually match (2% tolerance).').toBeLessThanOrEqual(Number(process.env.E2E_VISUAL_TOLERANCE||0.02));
    }
});
