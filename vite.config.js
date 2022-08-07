import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

import fs from 'fs';
import { resolve } from 'path';
import { homedir } from 'os';

const host = 'hydrofon.test';

export default defineConfig({
    plugins: [
        laravel([
            'resources/sass/app.scss',
            'resources/js/app.js',
        ]),
    ],

    server: serverConfig(host)
});

function serverConfig (host) {
    let keyPath = resolve(homedir(), `.config/valet/Certificates/${host}.key`)
    let certificatePath = resolve(homedir(), `.config/valet/Certificates/${host}.crt`)

    if (!fs.existsSync(keyPath) || !fs.existsSync(certificatePath)) {
        return {}
    }

    return {
        host: host,
        hmr: {
            host: host
        },
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certificatePath),
        }
    }
}
