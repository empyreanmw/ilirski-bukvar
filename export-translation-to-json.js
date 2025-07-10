// scripts/export-lang-to-laravel.js
import fs from 'fs';
import path from 'path';
import lang from './resources/js/lang/rs.ts'; // adjust if needed

function flatten(obj, prefix = '', res = {}) {
    for (const [key, value] of Object.entries(obj)) {
        const fullKey = prefix ? `${prefix}.${key}` : key;
        if (typeof value === 'object' && value !== null) {
            flatten(value, fullKey, res);
        } else {
            res[fullKey] = value;
        }
    }
    return res;
}

const flat = flatten(lang);

const outputDir = path.resolve('resources/lang');
const outputPath = path.join(outputDir, 'rs.json');

// ✅ Make sure lang directory exists
if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
}

// ✅ Now write the file
fs.writeFileSync(outputPath, JSON.stringify(flat, null, 2), 'utf8');

console.log('✅ Translation file exported to Laravel:', outputPath);
