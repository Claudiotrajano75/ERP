// Service Worker para ERP PWA
const CACHE_NAME = 'erp-pwa-cache-v1';
const STATIC_ASSETS = [
    '/manifest.json',
    '/icons/icon.svg',
    '/assets/images/logo-sm.png'
];

// Instalação do Service Worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

// Ativação e limpeza de caches antigos
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Estratégia de Fetch: Network-First com fallback para cache
self.addEventListener('fetch', (event) => {
    // Ignorar requisições não-GET e requisições de API/POST
    if (event.request.method !== 'GET') return;

    // Apenas interceptar origens locais
    const url = new URL(event.request.url);
    if (url.origin !== location.origin) return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Se a resposta for válida, armazena em cache se for asset estático
                if (response && response.status === 200 && (
                    url.pathname.endsWith('.css') ||
                    url.pathname.endsWith('.js') ||
                    url.pathname.endsWith('.png') ||
                    url.pathname.endsWith('.svg') ||
                    url.pathname.endsWith('.woff2')
                )) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                return caches.match(event.request);
            })
    );
});
