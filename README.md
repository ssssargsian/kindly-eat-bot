# Локальное окружение

## Подготовка локального окружения

Генерируем ssl-сертификат через [mkcert](https://github.com/FiloSottile/mkcert):

```bash
mkcert \
  -key-file docker/traefik/certs/key.pem \
  -cert-file docker/traefik/certs/cert.pem \
  kindly.localhost '*.kindly.localhost'
```