import fs from 'node:fs';
const path='docs/security/postman-security-smoke.json'; const c=JSON.parse(fs.readFileSync(path));
for(const item of c.item) for(const event of item.event) event.script.exec=event.script.exec.flatMap(line=>line.split('\\n'));
// Convert escaped newline sequences left by the collection generator.
for(const item of c.item) for(const event of item.event) { event.script.exec=event.script.exec.flatMap(line=>line.split('\n')); new Function(event.script.exec.join('\n').replaceAll('\\n','\n')); }
fs.writeFileSync(path,JSON.stringify(c,null,2));
const lock=JSON.parse(fs.readFileSync('package-lock.json'));lock.packages[''].engines=JSON.parse(fs.readFileSync('package.json')).engines;fs.writeFileSync('package-lock.json',JSON.stringify(lock,null,2)+'\n');
