import fs from 'node:fs';
const script = code => ({listen:'test',script:{type:'text/javascript',exec:code.split('\n')}});
const token = `const match=pm.response.text().match(/name="csrf-token" content="([^"]+)"/);\nif(match) pm.collectionVariables.set('csrf_token',match[1]);`;
const request=(name,method,path,body,tests)=>({name,protocolProfileBehavior:{followRedirects:false},request:{method,header:body?[{key:'Content-Type',value:'application/x-www-form-urlencoded'},{key:'Accept',value:'application/json'}]:[],url:'{{base_url}}'+path,...(body?{body:{mode:'urlencoded',urlencoded:Object.entries(body).map(([key,value])=>({key,value,type:'text'}))}}:{})},event:[script(tests)]});
const collection={info:{name:'SDVRS security smoke checks',schema:'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',description:'Run only against an isolated QA database. Uses cookie sessions and CSRF. Does not submit incidents or send email. Set base_url, admin_login and admin_password locally.'},variable:[{key:'base_url',value:'http://localhost:8000'},{key:'admin_login',value:''},{key:'admin_password',value:'',type:'secret'},{key:'csrf_token',value:''}],item:[
request('1. Obtain session and CSRF','GET','/login',null,`pm.test('Login page',()=>pm.response.to.have.status(200));\n${token}\npm.test('CSRF token present',()=>pm.expect(pm.collectionVariables.get('csrf_token')).to.be.ok);`),
request('2. Reject invalid CSRF','POST','/login',{email:'qa@example.test',password:'unused',_token:'invalid'},`pm.test('CSRF rejected',()=>pm.response.to.have.status(419));`),
request('3. Authenticate QA admin','POST','/login',{email:'{{admin_login}}',password:'{{admin_password}}',_token:'{{csrf_token}}'},`pm.test('Authenticated redirect',()=>pm.response.to.have.status(302));`),
request('4. Refresh CSRF after login','GET','/admin/dashboard',null,`pm.test('Admin dashboard',()=>pm.response.to.have.status(200));\n${token}\npm.test('Private cache policy',()=>pm.expect(pm.response.headers.get('Cache-Control')).to.include('no-store'));\npm.test('Anti framing',()=>pm.expect(pm.response.headers.get('X-Frame-Options')).to.eql('DENY'));`),
request('5. Reject malformed case ID','GET','/admin/cases/not-a-number',null,`pm.test('Not found',()=>pm.response.to.have.status(404));`),
{...request('6. Reject array search input','GET','/admin/cases?search%5B%5D=bad',null,`pm.test('Validation error',()=>pm.response.to.have.status(422));`),request:{method:'GET',header:[{key:'Accept',value:'application/json'}],url:'{{base_url}}/admin/cases?search%5B%5D=bad'}},
request('7. Logout invalidates session','POST','/logout',{_token:'{{csrf_token}}'},`pm.test('Logout redirect',()=>pm.response.to.have.status(302));`),
request('8. Reject access after logout','GET','/admin/dashboard',null,`pm.test('Forbidden',()=>pm.response.to.have.status(403));`)
]};
fs.writeFileSync('docs/security/postman-security-smoke.json',JSON.stringify(collection,null,2));
const path='package.json';const pkg=JSON.parse(fs.readFileSync(path,'utf8'));pkg.engines={node:'^20.19.0 || ^22.18.0 || >=24.11.0'};fs.writeFileSync(path,JSON.stringify(pkg,null,4)+'\n');
