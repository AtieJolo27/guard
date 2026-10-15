import fs from 'node:fs';
import path from 'node:path';
import crypto from 'node:crypto';
import { spawn, spawnSync } from 'node:child_process';

export async function startTestEnvironment() {
    if (process.env.E2E_REMOTE_TEST) return;
    const url = new URL(process.env.E2E_TEST_URL);
    if (!['127.0.0.1','localhost'].includes(url.hostname) || url.pathname !== '/') throw new Error('Automatic test server requires a loopback URL with no path.');
    if (fs.existsSync('bootstrap/cache/config.php')) throw new Error('Clear Laravel config cache before running an isolated test environment.');
    try { const response=await fetch(url.origin+'/up',{signal:AbortSignal.timeout(1000)}); if(response) throw new Error('Test port is already in use. Choose a different E2E_TEST_URL.'); } catch(error) { if(error.message.includes('already in use')) throw error; }
    const folder=path.resolve('tmp/playwright');fs.mkdirSync(folder,{recursive:true});
    const db=path.join(folder,`qa-${process.pid}.sqlite`);fs.writeFileSync(db,'');
    const env={...process.env,APP_ENV:'testing',APP_DEBUG:'false',APP_URL:url.origin,APP_KEY:'base64:'+crypto.randomBytes(32).toString('base64'),DB_CONNECTION:'sqlite',DB_DATABASE:db,DB_URL:'',CACHE_STORE:'array',SESSION_DRIVER:'database',SESSION_ENCRYPT:'true',SESSION_SECURE_COOKIE:'false',MAIL_MAILER:'array',QUEUE_CONNECTION:'sync',LOG_CHANNEL:'stderr'};
    for(const key of ['MAIL_PASSWORD','MAIL_USERNAME','MAIL_URL']) env[key]='';
    for(const args of [['artisan','migrate','--force','--no-interaction'],['tests/browser/seed.php']]){
        const result=spawnSync('php',args,{env,encoding:'utf8'});
        if(result.status!==0) throw new Error(result.stderr+result.stdout);
    }
    const log=fs.openSync(path.join(folder,'server.log'),'w');
    const server=spawn('php',['-S',url.host,'-t','public','tests/browser/router.php'],{env,stdio:['ignore',log,log],windowsHide:true});
    const cleanup=async()=>{
        server.kill();
        await Promise.race([new Promise(resolve=>server.once('exit',resolve)),new Promise(resolve=>setTimeout(resolve,3000))]);
        fs.closeSync(log);
        for(const suffix of ['','-wal','-shm'])try{fs.unlinkSync(db+suffix);}catch{}
    };
    try {
        const deadline=Date.now()+60000;
        while(Date.now()<deadline){
            if(server.exitCode!==null) throw new Error('Test server exited. Check tmp/playwright/server.log.');
            try { const response=await fetch(url.origin+'/login',{signal:AbortSignal.timeout(2000)}); if(response.ok) return cleanup; }catch{}
            await new Promise(resolve=>setTimeout(resolve,250));
        }
        throw new Error('Timed out starting isolated test server.');
    } catch(error){await cleanup();throw error;}
}
