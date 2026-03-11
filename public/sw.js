const CACHE_NAME = 'hirehub-v1';

// عند تثبيت التطبيق
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll([
                '/',
                '/offline.html' // يمكنك إنشاء صفحة بسيطة لاحقاً تظهر عند انقطاع النت
            ]);
        })
    );
});

// عند طلب أي صفحة
self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        }).catch(() => {
            // إذا انقطع الإنترنت
            return caches.match('/offline.html');
        })
    );
});