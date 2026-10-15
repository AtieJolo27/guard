import fs from 'node:fs';
import { createCanvas, DOMMatrix, ImageData, Path2D } from './pdf-qa/node_modules/@napi-rs/canvas/index.js';
Object.assign(globalThis, {DOMMatrix, ImageData, Path2D});
const pdfjs=await import('./pdf-qa/node_modules/pdfjs-dist/legacy/build/pdf.mjs');
const pdf=await pdfjs.getDocument({data:new Uint8Array(fs.readFileSync('tmp/original-letterhead.pdf')),useSystemFonts:true}).promise;
console.log('Pages:', pdf.numPages);
for(let i=1;i<=pdf.numPages;i++){
 const page=await pdf.getPage(i); const viewport=page.getViewport({scale:2});const canvas=createCanvas(viewport.width,viewport.height);
 await page.render({canvasContext:canvas.getContext('2d'),viewport}).promise;
 fs.writeFileSync(`tmp/original-page-${i}.png`,canvas.toBuffer('image/png'));
 console.log((await page.getTextContent()).items.map(x=>x.str).join(' '));
}
