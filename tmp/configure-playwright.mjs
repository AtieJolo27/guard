import fs from 'node:fs';
const path='package.json';const pkg=JSON.parse(fs.readFileSync(path));
pkg.scripts['test:e2e']='playwright test';pkg.scripts['test:e2e:report']='playwright show-report';
fs.writeFileSync(path,JSON.stringify(pkg,null,4)+'\n');
