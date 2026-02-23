# Omniship

Multi-carrier shipping abstraction library for PHP. Like Omnipay, but for shipping.

## Project Overview

- **PHP 8.2+** — uses enums, readonly classes, constructor promotion, named arguments
- **Pest** for testing (not PHPUnit directly)
- **PSR-18** HTTP client (not Guzzle-coupled)
- **SOAP** support via PHP SoapClient for Turkish carriers
- **Separate packages** per carrier: `omniship/common`, `omniship/ups`, `omniship/yurtici`, etc.
- **Target audience**: SaaS e-commerce platforms

## Architecture

```
AbstractCarrier
  ├── AbstractHttpCarrier    → REST/JSON carriers (UPS, FedEx, DHL, HepsiJet)
  └── AbstractSoapCarrier    → SOAP/XML carriers (Yurtiçi, Aras, PTT, Sürat, MNG)
```

Each carrier is a separate namespace: `Omniship\UPS\Carrier`, `Omniship\Yurtici\Carrier`, etc.

## Commands

```bash
# Run tests
docker compose run --rm php vendor/bin/pest

# Run specific test
docker compose run --rm php vendor/bin/pest tests/Common/AddressTest.php

# Static analysis
docker compose run --rm php vendor/bin/phpstan analyse

# Code style fix
docker compose run --rm php vendor/bin/php-cs-fixer fix

# Install dependencies
docker compose run --rm php composer install
```

## Coding Standards

- **PSR-12 / PER-CS 2.0** coding style
- **PHPStan level 8** — strict typing
- All files start with `declare(strict_types=1);`
- Domain models use `readonly` properties with constructor promotion
- Use PHP enums (not constants) for fixed value sets
- Weights default to KG, dimensions to CM (metric-first for Turkish market)

## Testing

- Use **Pest** syntax (not PHPUnit class-based tests)
- Mock HTTP responses as JSON fixtures in `tests/{Carrier}/Mock/`
- Mock SOAP responses as XML fixtures in `tests/{Carrier}/Mock/`
- Integration tests gated behind carrier-specific env vars
- Use `php-http/mock-client` for HTTP mocking

## Carrier Package Pattern

Each carrier package follows this structure:

```
src/{Carrier}/
  Carrier.php                    → extends AbstractHttpCarrier or AbstractSoapCarrier
  Message/
    Abstract{Carrier}Request.php → shared helpers (address formatting, auth headers)
    CreateShipmentRequest.php
    CreateShipmentResponse.php
    GetTrackingStatusRequest.php
    GetTrackingStatusResponse.php
    CancelShipmentRequest.php
    CancelShipmentResponse.php

tests/{Carrier}/
  CarrierTest.php
  Message/
    CreateShipmentTest.php
    GetTrackingStatusTest.php
  Mock/
    CreateShipmentSuccess.json (or .xml for SOAP)
    CreateShipmentError.json
    ...
```

## Key Domain Concepts

- **`desi`**: Turkish volumetric weight = (L × W × H) / 3000. First-class field on Package.
- **`district`**: Turkish ilçe (district). Required by all Turkish carriers.
- **`barcode`**: Turkish carriers return a barcode string on shipment creation. Critical field.
- **PaymentType**: sender/receiver pays — standard in Turkish e-commerce.
- **COD (Cash on Delivery)**: `cashOnDelivery` flag + `codAmount` on Shipment.

## Implementation Plan

See `thoughts/shared/plans/omniship-implementation-plan.md` for the full phased plan.
