// Builds resources/icons/icons.json: the icon set used by <x-icon> and the admin icon pickers.
// Outline icons come from lucide-static, brand logos from simple-icons.
// Add a name to one of the lists below and run: node scripts/build-icons.mjs
import { readFileSync, writeFileSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');

const outline = `
activity alarm-clock app-window archive arrow-down arrow-left arrow-right arrow-up arrow-up-right award
badge-check badge-dollar-sign badge-percent banknote bar-chart-3 bell book-open bookmark bot box boxes brain
briefcase briefcase-business building building-2 calculator calendar calendar-check calendar-days camera
chart-column chart-line chart-no-axes-combined chart-pie check check-check chevron-down chevron-left chevron-right
chevron-up circle-check circle-dollar-sign circle-help circle-minus circle-x clipboard-check clipboard-list clock
cloud cloud-download code cog coins copy cpu credit-card crown database download drafting-compass drama
external-link eye eye-off factory file-archive file-check file-pen file-pen-line file-question-mark
file-search file-text files filter flame flask-conical folder-open funnel funnel-x gauge gavel gem gift
git-branch globe graduation-cap grid-2x2 hand-coins handshake hard-drive headphones headset heart
hotel house image images info key-round landmark languages laptop layers layout-dashboard layout-grid
layout-template lightbulb link list list-checks loader-circle lock log-in log-out mail map map-pin megaphone
menu message-circle message-circle-more messages-square minus monitor monitor-smartphone moon mouse-pointer-click
network newspaper package package-open palette panels-top-left paperclip pen-tool pencil pencil-ruler percent
phone phone-call pie-chart plane play plug plus puzzle qr-code quote receipt refresh-cw repeat rocket rotate-cw
route scale school search search-check send server settings settings-2 share-2 shield shield-check shield-user
shopping-bag shopping-cart smartphone sofa sparkles sprout square-pen star stethoscope store sun tag tags
target test-tube-diagonal thumbs-up timer trending-up triangle-alert trophy truck undo-2 upload user user-check
user-cog user-pen user-plus user-round user-x users users-round utensils video wallet wand-sparkles wifi wrench
x zap
`.trim().split(/\s+/);

// Keys are the names used on the site, values the simple-icons slugs.
const brands = {
    facebook: 'facebook',
    messenger: 'messenger',
    youtube: 'youtube',
    zalo: 'zalo',
    telegram: 'telegram',
    whatsapp: 'whatsapp',
    tiktok: 'tiktok',
    instagram: 'instagram',
    google: 'google',
};

const inner = svg => svg
    .replace(/<!--[\s\S]*?-->/g, '')
    .replace(/^[\s\S]*?<svg[^>]*>/, '')
    .replace(/<\/svg>\s*$/, '')
    .replace(/<title>[\s\S]*?<\/title>/, '')
    .replace(/\s*\n\s*/g, '')
    .replace(/\s+\/>/g, '/>')
    .trim();

const icons = {};
for (const name of outline) {
    icons[name] = { body: inner(readFileSync(resolve(root, 'node_modules/lucide-static/icons', `${name}.svg`), 'utf8')) };
}
for (const [name, slug] of Object.entries(brands)) {
    icons[`brand-${name}`] = { body: inner(readFileSync(resolve(root, 'node_modules/simple-icons/icons', `${slug}.svg`), 'utf8')), brand: true };
}

const sorted = Object.fromEntries(Object.entries(icons).sort(([a], [b]) => a.localeCompare(b)));
writeFileSync(resolve(root, 'resources/icons/icons.json'), JSON.stringify(sorted, null, 1) + '\n');
console.log(`${Object.keys(sorted).length} icons written to resources/icons/icons.json`);
