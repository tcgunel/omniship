<?php

namespace App;

use Omniship\Common\CarrierInterface;
use Omniship\Omniship;

class CarrierTester
{
    private \PDO $db;

    public function __construct(string $dbPath)
    {
        $this->db = new \PDO("sqlite:{$dbPath}");
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->initDatabase();
    }

    private function initDatabase(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS shipments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                carrier TEXT NOT NULL,
                tracking_number TEXT,
                barcode TEXT,
                status TEXT DEFAULT 'created',
                request_data TEXT,
                response_data TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS tracking_logs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                shipment_id INTEGER NOT NULL,
                status TEXT NOT NULL,
                location TEXT,
                description TEXT,
                event_date DATETIME,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (shipment_id) REFERENCES shipments(id)
            )
        ");
    }

    public function getAvailableCarriers(): array
    {
        return [
            'Yurtici' => 'Yurtiçi Kargo (SOAP)',
            'KolayGelsin' => 'Kolay Gelsin (REST)',
            'Aras' => 'Aras Kargo (SOAP/REST)',
            'Surat' => 'Sürat Kargo (SOAP)',
            'HepsiJet' => 'HepsiJet (REST)',
            'UPS' => 'UPS Kargo (SOAP)',
            'MNG' => 'MNG Kargo (SOAP/REST)',
            'PTT' => 'PTT Kargo (SOAP)',
            'Horoz' => 'Horoz Lojistik (REST)',
            'FedEx' => 'FedEx (REST/OAuth)',
            'DHL_Express' => 'DHL Express (REST)',
        ];
    }

    public function createCarrier(string $name, array $parameters = []): CarrierInterface
    {
        $carrier = Omniship::create($name);
        $carrier->initialize($parameters);
        return $carrier;
    }

    public function saveShipment(string $carrier, array $requestData, array $responseData): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO shipments (carrier, tracking_number, barcode, status, request_data, response_data)
            VALUES (:carrier, :tracking, :barcode, :status, :request, :response)
        ");

        $stmt->execute([
            'carrier' => $carrier,
            'tracking' => $responseData['tracking_number'] ?? null,
            'barcode' => $responseData['barcode'] ?? null,
            'status' => 'created',
            'request' => json_encode($requestData, JSON_UNESCAPED_UNICODE),
            'response' => json_encode($responseData, JSON_UNESCAPED_UNICODE),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function getShipments(int $limit = 20): array
    {
        $stmt = $this->db->query("SELECT * FROM shipments ORDER BY created_at DESC LIMIT {$limit}");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getShipment(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM shipments WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function updateShipmentStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE shipments SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function saveTrackingLog(int $shipmentId, string $status, ?string $location, ?string $description, ?string $eventDate): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO tracking_logs (shipment_id, status, location, description, event_date)
            VALUES (:shipment_id, :status, :location, :description, :event_date)
        ");

        $stmt->execute([
            'shipment_id' => $shipmentId,
            'status' => $status,
            'location' => $location,
            'description' => $description,
            'event_date' => $eventDate,
        ]);
    }

    public function getTrackingLogs(int $shipmentId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM tracking_logs WHERE shipment_id = :id ORDER BY event_date DESC");
        $stmt->execute(['id' => $shipmentId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
