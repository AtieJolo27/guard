import fs from 'node:fs';
const c=JSON.parse(fs.readFileSync('docs/security/postman-security-smoke.json'));
for(const item of c.item){const code=item.event[0].script.exec.join('\n');new Function(code);console.log(item.name+': script syntax valid');}
