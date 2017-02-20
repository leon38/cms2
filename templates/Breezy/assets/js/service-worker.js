self.addEventListener('install', e => {
    e.waitUntil(
        caches.open('choupdoune').then(cache => {
            return cache.addAll([
                '/',
                '/index.html',
                '/index',
                '/css/breezy.css',
                '/js/breezy.js',
            ])
            .then(() => self.skipWaiting())
        })
    )
});

addEventListener('activate', event => {
    event.waitUntil(async function() {
        // Feature-detect
        if (self.registration.navigationPreload) {
            // Enable navigation preloads!
            await self.registration.navigationPreload.enable();
        }
    }());
});

addEventListener('fetch', event => {
    event.respondWith(async function() {
        console.log('fetch');
        // Respond from the cache if we can
        const cachedResponse = await caches.match(event.request);
        if (cachedResponse) return cachedResponse;

        // Else, use the preloaded response, if it's there
        const response = await event.preloadResponse;
        if (response) return response;

        // Else try the network.
        return fetch(event.request);
    }());
});