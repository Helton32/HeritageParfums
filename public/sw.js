/**
 * Service Worker pour Heritage Parfums
 * Cache intelligent pour une expérience Guerlain optimisée
 */

const CACHE_NAME = 'heritage-parfums-v1.0.0';
const STATIC_CACHE = 'heritage-static-v1';
const DYNAMIC_CACHE = 'heritage-dynamic-v1';

// Ressources essentielles à mettre en cache
const CORE_ASSETS = [
  '/',
  '/about',
  '/contact',
  '/css/guerlain-animations.css',
  '/manifest.json',
  // Fonts Google
  'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Montserrat:wght@200;300;400;500;600;700&display=swap',
  // Bootstrap & FontAwesome
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
];

// Ressources images importantes
const IMAGE_ASSETS = [
  // Images principales (à adapter selon vos vraies images)
  'https://images.unsplash.com/photo-1541643600914-78b084683601',
  'https://images.unsplash.com/photo-1588405748880-12d1d2a59d32',
  'https://images.unsplash.com/photo-1515377905703-c4788e51af15'
];

// Installation du Service Worker
self.addEventListener('install', event => {
  console.log('[SW] Installation en cours...');
  
  event.waitUntil(
    Promise.all([
      // Cache statique pour les ressources essentielles
      caches.open(STATIC_CACHE).then(cache => {
        console.log('[SW] Mise en cache des ressources statiques');
        return cache.addAll(CORE_ASSETS);
      }),
      // Cache des images importantes
      caches.open(DYNAMIC_CACHE).then(cache => {
        console.log('[SW] Mise en cache des images importantes');
        return cache.addAll(IMAGE_ASSETS.map(url => 
          url + '?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
        ));
      })
    ]).then(() => {
      console.log('[SW] Installation terminée avec succès');
      return self.skipWaiting();
    })
  );
});

// Activation du Service Worker
self.addEventListener('activate', event => {
  console.log('[SW] Activation en cours...');
  
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          // Supprimer les anciens caches
          if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
            console.log('[SW] Suppression ancien cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => {
      console.log('[SW] Activation terminée');
      return self.clients.claim();
    })
  );
});

// Stratégies de cache pour les requêtes
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Ignorer les requêtes non-HTTP
  if (!request.url.startsWith('http')) return;
  
  // Stratégie pour les pages HTML
  if (request.headers.get('accept').includes('text/html')) {
    event.respondWith(
      caches.match(request)
        .then(response => {
          if (response) {
            console.log('[SW] Page servie depuis le cache:', request.url);
            return response;
          }
          
          return fetch(request)
            .then(fetchResponse => {
              // Cloner la réponse car elle ne peut être lue qu'une fois
              const responseClone = fetchResponse.clone();
              
              // Mettre en cache si la réponse est valide
              if (fetchResponse.status === 200) {
                caches.open(DYNAMIC_CACHE)
                  .then(cache => cache.put(request, responseClone));
              }
              
              return fetchResponse;
            })
            .catch(() => {
              // Page hors ligne de fallback
              return new Response(`
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Heritage Parfums - Hors ligne</title>
                    <style>
                        body {
                            font-family: 'Montserrat', sans-serif;
                            background: #0d0d0d;
                            color: #ffffff;
                            text-align: center;
                            padding: 60px 20px;
                            margin: 0;
                        }
                        .offline-container {
                            max-width: 600px;
                            margin: 0 auto;
                        }
                        h1 {
                            font-family: 'Cormorant Garamond', serif;
                            font-size: 3rem;
                            color: #d4af37;
                            margin-bottom: 2rem;
                        }
                        p {
                            font-size: 1.1rem;
                            line-height: 1.6;
                            margin-bottom: 2rem;
                        }
                        .retry-btn {
                            background: #d4af37;
                            color: #0d0d0d;
                            border: none;
                            padding: 15px 30px;
                            font-size: 14px;
                            text-transform: uppercase;
                            letter-spacing: 2px;
                            cursor: pointer;
                            transition: all 0.3s ease;
                        }
                        .retry-btn:hover {
                            background: #b8941f;
                        }
                    </style>
                </head>
                <body>
                    <div class="offline-container">
                        <h1>Heritage Parfums</h1>
                        <p>Vous êtes actuellement hors ligne.</p>
                        <p>Veuillez vérifier votre connexion internet pour accéder à notre boutique de parfums de luxe.</p>
                        <button class="retry-btn" onclick="window.location.reload()">
                            Réessayer
                        </button>
                    </div>
                </body>
                </html>
              `, {
                headers: { 'Content-Type': 'text/html' }
              });
            });
        })
    );
  }
  
  // Stratégie pour les images
  else if (request.destination === 'image') {
    event.respondWith(
      caches.match(request)
        .then(response => {
          if (response) {
            return response;
          }
          
          return fetch(request)
            .then(fetchResponse => {
              // Mettre en cache les images
              if (fetchResponse.status === 200) {
                const responseClone = fetchResponse.clone();
                caches.open(DYNAMIC_CACHE)
                  .then(cache => cache.put(request, responseClone));
              }
              
              return fetchResponse;
            })
            .catch(() => {
              // Image placeholder en cas d'échec
              return new Response(
                '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f5f5f5"/><text x="50%" y="50%" font-family="Arial" font-size="16" fill="#666" text-anchor="middle" dy=".3em">Heritage Parfums</text></svg>',
                { headers: { 'Content-Type': 'image/svg+xml' } }
              );
            });
        })
    );
  }
  
  // Stratégie pour les CSS et JS
  else if (request.destination === 'style' || request.destination === 'script') {
    event.respondWith(
      caches.match(request)
        .then(response => {
          return response || fetch(request)
            .then(fetchResponse => {
              if (fetchResponse.status === 200) {
                const responseClone = fetchResponse.clone();
                caches.open(STATIC_CACHE)
                  .then(cache => cache.put(request, responseClone));
              }
              return fetchResponse;
            });
        })
    );
  }
  
  // Stratégie par défaut : réseau d'abord
  else {
    event.respondWith(
      fetch(request)
        .catch(() => caches.match(request))
    );
  }
});

// Gestion des messages du client
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

// Notification de mise à jour disponible
self.addEventListener('updatefound', () => {
  console.log('[SW] Mise à jour disponible');
});

// Synchronisation en arrière-plan (pour les formulaires hors ligne)
self.addEventListener('sync', event => {
  if (event.tag === 'newsletter-sync') {
    event.waitUntil(syncNewsletter());
  }
});

// Fonction pour synchroniser les inscriptions newsletter
async function syncNewsletter() {
  try {
    // Récupérer les données stockées localement
    const newsletterData = await getStoredNewsletterData();
    
    if (newsletterData.length > 0) {
      // Envoyer les données au serveur
      for (const data of newsletterData) {
        await fetch('/api/newsletter', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(data)
        });
      }
      
      // Nettoyer le stockage local
      await clearStoredNewsletterData();
      console.log('[SW] Newsletter synchronisée avec succès');
    }
  } catch (error) {
    console.error('[SW] Erreur lors de la synchronisation newsletter:', error);
  }
}

// Fonctions utilitaires pour IndexedDB (à implémenter si nécessaire)
async function getStoredNewsletterData() {
  // Retourner un tableau vide pour l'instant
  return [];
}

async function clearStoredNewsletterData() {
  // Nettoyer les données stockées
  return;
}

// Logging pour le développement
console.log('[SW] Service Worker Heritage Parfums chargé');
