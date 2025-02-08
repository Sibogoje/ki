const CACHE_NAME = 'saeespace-v1';
const ASSETS = [
    '/',
    '/index.php',
    '/styles.css',
    '/app.js',
    '/icon.png',
    '/manifest.json'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS);
        })
    );
});

self.addEventListener('fetch', (event) => {
    if (event.request.url.includes('/login_process.php')) {
        // Bypass the service worker for login process
        return fetch(event.request);
    }

    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});