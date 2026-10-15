import fs from 'node:fs';
import { createCanvas, DOMMatrix, ImageData, Path2D } from './pdf-qa/node_modules/@napi-rs/canvas/index.js';
Object.assign(globalThis,{DOMMatrix,ImageData,Path2D});
const pdfjs=await import('./pdf-qa/node_modules/pdfjs-dist/legacy/build/pdf.mjs');
const pdf=await pdfjs.getDocument({data:new Uint8Array(fs.readFileSync('tmp/original-letterhead.pdf'))}).promise;
const page=await pdf.getPage(1);const scale=3;const viewport=page.getViewport({scale});const canvas=createCanvas(viewport.width,viewport.height);
await page.render({canvasContext:canvas.getContext('2d'),viewport}).promise;
for(const [name,y,height] of [['template-header',0,175],['template-footer',870,40]]){
 const out=createCanvas(viewport.width,height*scale);out.getContext('2d').drawImage(canvas,0,y*scale,viewport.width,height*scale,0,0,viewport.width,height*scale);
 fs.writeFileSync(`resources/notice-assets/${name}.png`,out.toBuffer('image/png'));
}
