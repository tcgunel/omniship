# Omniship Development Environment

Monorepo development environment for the Omniship multi-carrier shipping library.

## Structure

```
omniship/                     ← This repo (dev environment)
├── omniship/                 ← omniship-common (core abstractions)
├── omniship-yurtici/         ← Yurtiçi Kargo carrier (SOAP)
├── omniship-aras/            ← Aras Kargo carrier (HTTP/XML)
├── omniship-kolaygelsin/     ← KolayGelsin/Sendeo carrier (HTTP/JSON)
├── app/                      ← Test console (web UI)
├── public/                   ← Web root (nginx)
├── docker-compose.yml        ← PHP-FPM + nginx
└── Dockerfile
```

## Setup

```bash
# Start containers
docker compose up -d

# Install root dependencies (links all packages via path repositories)
docker compose run --rm php composer install

# Install per-package dependencies
docker compose run --rm php bash -c "cd omniship && composer install"
docker compose run --rm php bash -c "cd omniship-yurtici && composer install"
docker compose run --rm php bash -c "cd omniship-aras && composer install"
docker compose run --rm php bash -c "cd omniship-kolaygelsin && composer install"
```

## Running Tests

```bash
# All tests via root
docker compose run --rm php vendor/bin/pest

# Per-package tests
docker compose run --rm php bash -c "cd omniship-yurtici && vendor/bin/pest"
docker compose run --rm php bash -c "cd omniship-aras && vendor/bin/pest"
docker compose run --rm php bash -c "cd omniship-kolaygelsin && vendor/bin/pest"
```

## Test Console

The web test console is available at `http://localhost:8088` when Docker is running.

It provides a UI to test all carrier operations (create shipment, track, cancel) against test/production APIs.

## Packages

| Package | Carrier | API Type | Auth |
|---------|---------|----------|------|
| [omniship-common](omniship/) | Core | - | - |
| [omniship-yurtici](omniship-yurtici/) | Yurtiçi Kargo | SOAP (SoapClient) | Username/Password |
| [omniship-aras](omniship-aras/) | Aras Kargo | HTTP/XML | Username/Password |
| [omniship-kolaygelsin](omniship-kolaygelsin/) | KolayGelsin (Sendeo) | HTTP/JSON | API Token |

## Architecture

```
AbstractCarrier
  ├── AbstractHttpCarrier    → REST/JSON & HTTP/XML carriers
  │     ├── Aras\Carrier
  │     └── KolayGelsin\Carrier
  └── AbstractSoapCarrier    → SOAP/WSDL carriers
        └── Yurtici\Carrier
```

## Requirements

- PHP 8.2+
- Docker & Docker Compose
- ext-soap (for Yurtiçi)
- ext-simplexml, ext-libxml (for Aras)

## License

MIT
