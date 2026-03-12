<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\CarrierTester;
use Omniship\Common\Address;
use Omniship\Common\Package;

$tester = new CarrierTester(__DIR__ . '/../database/omniship.sqlite');

$action = $_GET['action'] ?? 'home';
$carrier = $_GET['carrier'] ?? '';

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Omniship Test Console</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; color: #333; }
        .container { max-width: 960px; margin: 0 auto; padding: 20px; }
        h1 { margin-bottom: 20px; color: #1a1a1a; }
        h2 { margin-bottom: 15px; color: #333; }
        .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .carrier-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; }
        .carrier-card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #2563eb; }
        .carrier-card h3 { margin-bottom: 8px; }
        .carrier-card .type { font-size: 12px; color: #666; margin-bottom: 12px; }
        .carrier-card a { display: inline-block; padding: 6px 12px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 4px; font-size: 13px; margin-right: 5px; }
        .carrier-card a:hover { background: #1d4ed8; }
        .carrier-card a.secondary { background: #6b7280; }
        .carrier-card a.secondary:hover { background: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f9fafb; font-weight: 600; font-size: 13px; text-transform: uppercase; color: #666; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .badge-created { background: #dbeafe; color: #1e40af; }
        .badge-delivered { background: #d1fae5; color: #065f46; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-in_transit { background: #fef3c7; color: #92400e; }
        nav { margin-bottom: 20px; }
        nav a { display: inline-block; padding: 8px 16px; background: #fff; border-radius: 4px; text-decoration: none; color: #333; margin-right: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        nav a:hover { background: #f0f0f0; }
        nav a.active { background: #2563eb; color: #fff; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; font-size: 14px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .btn { padding: 10px 20px; background: #2563eb; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #1d4ed8; }
        .alert { padding: 12px 16px; border-radius: 4px; margin-bottom: 15px; }
        .alert-info { background: #dbeafe; color: #1e40af; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #d1fae5; color: #065f46; }
        pre { background: #1e293b; color: #e2e8f0; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 13px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Omniship Test Console</h1>

    <nav>
        <a href="/" class="<?= $action === 'home' ? 'active' : '' ?>">Carriers</a>
        <a href="/?action=shipments" class="<?= $action === 'shipments' ? 'active' : '' ?>">Shipments</a>
    </nav>

<?php

switch ($action) {
    case 'home':
        renderHome($tester);
        break;
    case 'test':
        renderTestForm($tester, $carrier);
        break;
    case 'create_shipment':
        handleCreateShipment($tester);
        break;
    case 'track':
        handleTrack($tester);
        break;
    case 'shipments':
        renderShipments($tester);
        break;
    case 'shipment':
        renderShipmentDetail($tester, (int) ($_GET['id'] ?? 0));
        break;
    default:
        renderHome($tester);
}

?>

</div>
</body>
</html>
<?php

function renderHome(CarrierTester $tester): void
{
    $carriers = $tester->getAvailableCarriers();
    echo '<h2>Available Carriers</h2>';
    echo '<div class="carrier-grid">';
    foreach ($carriers as $name => $description) {
        echo '<div class="carrier-card">';
        echo "<h3>{$name}</h3>";
        echo "<div class='type'>{$description}</div>";
        echo "<a href='/?action=test&carrier=" . urlencode($name) . "'>Create Shipment</a>";
        echo "<a href='/?action=track&carrier=" . urlencode($name) . "' class='secondary'>Track</a>";
        echo '</div>';
    }
    echo '</div>';

    if (empty($carriers)) {
        echo '<div class="alert alert-info">No carriers registered yet. Implement a carrier package and register it with CarrierFactory.</div>';
    }
}

function renderTestForm(CarrierTester $tester, string $carrierName): void
{
    echo "<h2>Create Shipment - {$carrierName}</h2>";
    echo '<div class="card">';
    echo '<form method="POST" action="/?action=create_shipment">';
    echo '<input type="hidden" name="carrier" value="' . htmlspecialchars($carrierName) . '">';

    echo '<h3 style="margin-bottom:15px">Carrier Credentials</h3>';

    $username = $password = $api_token = $address_id = $customer_id = '';

    if ($carrierName === 'Aras'){
        $username = 'neodyum';
        $password = 'nd2580';
    }else if ($carrierName === 'Yurtici'){
        $username = 'YKTEST';
        $password = 'YK';
    }else if ($carrierName === 'KolayGelsin'){
        $api_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6IjI5ODM5MjA1MTEwIiwiY2hhbm5lbCI6IkludGVncmF0aW9uIiwiZW52aXJvbm1lbnQiOiJpbnRlZ3JhdGlvbiIsImlzcyI6ImxvY2FsaG9zdCIsImF1ZCI6ImFsbCIsImV4cCI6MTgwMzQwNDkwMCwibmJmIjoxNzcxODY4OTAwfQ.NmPbi6dLesP4PJUwUqf-CYKTdE_4z_hIkYJH8cpcuos';
        $address_id = '754026';
        $customer_id = '789217';
    }

    if ($carrierName === 'KolayGelsin') {
        echo '<div class="form-group"><label>API Token</label><input type="text" name="api_token" placeholder="Bearer API Token" value="'.$api_token.'"></div>';
        echo '<div class="form-row">';
        echo '<div class="form-group"><label>Customer ID</label><input type="number" name="customer_id" placeholder="KolayGelsin Customer ID" value="'.$customer_id.'"></div>';
        echo '<div class="form-group"><label>Address ID</label><input type="number" name="address_id" placeholder="Sender Address ID" value="'.$address_id.'"></div>';
        echo '</div>';
    } else {
        echo '<div class="form-row">';
        echo '<div class="form-group"><label>Username</label><input type="text" name="username" placeholder="API Username" value="'.$username.'"></div>';
        echo '<div class="form-group"><label>Password</label><input type="password" name="password" placeholder="API Password" value="'.$password.'"></div>';
        echo '</div>';
    }

    echo '<div class="form-group"><label>Test Mode</label><select name="test_mode"><option value="1">Yes (Sandbox)</option><option value="0">No (Production)</option></select></div>';

    echo '<h3 style="margin:20px 0 15px">Shipment Reference</h3>';
    echo '<div class="form-row">';
    if ($carrierName === 'KolayGelsin') {
        echo '<div class="form-group"><label>Customer Specific Code</label><input type="text" name="customer_specific_code" placeholder="Your reference code (optional)"></div>';
        echo '<div class="form-group"><label>Package Type</label><select name="package_type"><option value="2">Koli (Box)</option><option value="1">Dosya (Document)</option></select></div>';
    } elseif ($carrierName === 'Aras') {
        echo '<div class="form-group"><label>Integration Code</label><input type="text" name="integration_code" placeholder="Order/integration code (required)"></div>';
        echo '<div class="form-group"><label>Invoice Number</label><input type="text" name="invoice_number" placeholder="Same as integration code if empty"></div>';
        echo '<div class="form-group"><label>Barcodes (comma-separated, one per piece)</label><input type="text" name="barcodes" placeholder="e.g. ABC123,ABC124"></div>';
    } else {
        echo '<div class="form-group"><label>Cargo Key</label><input type="text" name="cargo_key" placeholder="Auto-generated if empty"></div>';
        echo '<div class="form-group"><label>Invoice Key</label><input type="text" name="invoice_key" placeholder="Same as cargo key if empty"></div>';
    }
    echo '</div>';

    echo '<h3 style="margin:20px 0 15px">Sender</h3>';
    echo '<div class="form-row">';
    echo '<div class="form-group"><label>Name</label><input type="text" name="sender_name" value="Test Sender"></div>';
    echo '<div class="form-group"><label>Phone</label><input type="text" name="sender_phone" value="05551234567"></div>';
    echo '</div>';
    echo '<div class="form-group"><label>Address</label><input type="text" name="sender_address" value="Test Mah. Test Cad. No:1"></div>';
    echo '<div class="form-row">';
    echo '<div class="form-group"><label>City (Il)</label><input type="text" name="sender_city" value="Istanbul"></div>';
    echo '<div class="form-group"><label>District (Ilce)</label><input type="text" name="sender_district" value="Kadikoy"></div>';
    echo '</div>';
    echo '<div class="form-group"><label>Postal Code</label><input type="text" name="sender_postal" value="34700"></div>';

    echo '<h3 style="margin:20px 0 15px">Receiver</h3>';
    echo '<div class="form-row">';
    echo '<div class="form-group"><label>Name</label><input type="text" name="receiver_name" value="Test Receiver"></div>';
    echo '<div class="form-group"><label>Phone</label><input type="text" name="receiver_phone" value="05559876543"></div>';
    echo '</div>';
    echo '<div class="form-group"><label>Address</label><input type="text" name="receiver_address" value="Deneme Sok. No:5"></div>';
    echo '<div class="form-row">';
    echo '<div class="form-group"><label>City (Il)</label><input type="text" name="receiver_city" value="Ankara"></div>';
    echo '<div class="form-group"><label>District (Ilce)</label><input type="text" name="receiver_district" value="Cankaya"></div>';
    echo '</div>';
    echo '<div class="form-group"><label>Postal Code</label><input type="text" name="receiver_postal" value="06690"></div>';

    echo '<h3 style="margin:20px 0 15px">Package</h3>';
    echo '<div class="form-row">';
    echo '<div class="form-group"><label>Weight (kg)</label><input type="number" step="0.1" name="weight" value="1.5"></div>';
    echo '<div class="form-group"><label>Desi</label><input type="number" step="0.1" name="desi" value="2"></div>';
    echo '</div>';
    echo '<div class="form-row">';
    echo '<div class="form-group"><label>Length (cm)</label><input type="number" name="length" value="30"></div>';
    echo '<div class="form-group"><label>Width (cm)</label><input type="number" name="width" value="20"></div>';
    echo '</div>';
    echo '<div class="form-group"><label>Height (cm)</label><input type="number" name="height" value="15"></div>';

    echo '<h3 style="margin:20px 0 15px">Options</h3>';
    echo '<div class="form-group"><label>Payment Type</label><select name="payment_type"><option value="sender">Sender Pays</option><option value="receiver">Receiver Pays</option></select></div>';
    echo '<div class="form-group"><label>Description</label><input type="text" name="description" value="Test shipment"></div>';

    echo '<button type="submit" class="btn" style="margin-top:15px">Create Shipment</button>';
    echo '</form>';
    echo '</div>';
}

function handleCreateShipment(CarrierTester $tester): void
{
    $carrierName = $_POST['carrier'] ?? '';

    try {
        if ($carrierName === 'KolayGelsin') {
            $carrierParams = [
                'apiToken' => $_POST['api_token'] ?? '',
                'customerId' => (int) ($_POST['customer_id'] ?? 0),
                'addressId' => (int) ($_POST['address_id'] ?? 0),
                'testMode' => (bool) ($_POST['test_mode'] ?? true),
            ];
        } else {
            $carrierParams = [
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'testMode' => (bool) ($_POST['test_mode'] ?? true),
            ];
        }

        $carrier = $tester->createCarrier($carrierName, $carrierParams);

        $shipFrom = new Address(
            name: $_POST['sender_name'] ?? '',
            street1: $_POST['sender_address'] ?? '',
            city: $_POST['sender_city'] ?? '',
            district: $_POST['sender_district'] ?? '',
            postalCode: $_POST['sender_postal'] ?? '',
            country: 'TR',
            phone: $_POST['sender_phone'] ?? '',
        );

        $shipTo = new Address(
            name: $_POST['receiver_name'] ?? '',
            street1: $_POST['receiver_address'] ?? '',
            city: $_POST['receiver_city'] ?? '',
            district: $_POST['receiver_district'] ?? '',
            postalCode: $_POST['receiver_postal'] ?? '',
            country: 'TR',
            phone: $_POST['receiver_phone'] ?? '',
        );

        $packages = [
            new Package(
                weight: (float) ($_POST['weight'] ?? 1),
                desi: (float) ($_POST['desi'] ?? 1),
                length: (float) ($_POST['length'] ?? 0) ?: null,
                width: (float) ($_POST['width'] ?? 0) ?: null,
                height: (float) ($_POST['height'] ?? 0) ?: null,
                description: $_POST['description'] ?? '',
            ),
        ];

        if ($carrierName === 'KolayGelsin') {
            $requestData = [
                'shipTo' => $shipTo,
                'packages' => $packages,
                'customerSpecificCode' => $_POST['customer_specific_code'] ?? ('OMN-' . time()),
                'packageType' => (int) ($_POST['package_type'] ?? 2),
            ];
        } elseif ($carrierName === 'Aras') {
            $integrationCode = $_POST['integration_code'] ?? ('OMN-' . time());
            $invoiceNumber = $_POST['invoice_number'] ?: $integrationCode;
            $barcodes = array_filter(array_map('trim', explode(',', $_POST['barcodes'] ?? '')));
            $requestData = [
                'shipTo' => $shipTo,
                'packages' => $packages,
                'integrationCode' => $integrationCode,
                'invoiceNumber' => $invoiceNumber,
                'tradingWaybillNumber' => $integrationCode,
                'barcodes' => $barcodes,
            ];
        } else {
            $cargoKey = $_POST['cargo_key'] ?? ('OMN-' . time());
            $invoiceKey = $_POST['invoice_key'] ?? $cargoKey;
            $requestData = [
                'shipFrom' => $shipFrom,
                'shipTo' => $shipTo,
                'packages' => $packages,
                'cargoKey' => $cargoKey,
                'invoiceKey' => $invoiceKey,
            ];
        }

        $response = $carrier->createShipment($requestData)->send();

        $responseData = [
            'successful' => $response->isSuccessful(),
            'tracking_number' => method_exists($response, 'getTrackingNumber') ? $response->getTrackingNumber() : null,
            'barcode' => method_exists($response, 'getBarcode') ? $response->getBarcode() : null,
            'shipment_id' => method_exists($response, 'getShipmentId') ? $response->getShipmentId() : null,
            'message' => $response->getMessage(),
        ];

        if ($carrierName === 'KolayGelsin') {
            $savedRequestData = [
                'carrier' => $carrierName,
                'customerSpecificCode' => $requestData['customerSpecificCode'] ?? null,
                'shipTo' => $shipTo->toArray(),
            ];
        } elseif ($carrierName === 'Aras') {
            $savedRequestData = [
                'carrier' => $carrierName,
                'integrationCode' => $integrationCode,
                'invoiceNumber' => $invoiceNumber,
                'shipTo' => $shipTo->toArray(),
            ];
        } else {
            $savedRequestData = [
                'carrier' => $carrierName,
                'cargoKey' => $cargoKey,
                'invoiceKey' => $invoiceKey,
                'shipTo' => $shipTo->toArray(),
                'shipFrom' => $shipFrom->toArray(),
            ];
        }

        $id = $tester->saveShipment($carrierName, $savedRequestData, $responseData);

        if ($response->isSuccessful()) {
            echo '<div class="alert alert-success">Shipment created successfully!</div>';
        } else {
            echo '<div class="alert alert-error">Shipment failed: ' . htmlspecialchars($response->getMessage() ?? 'Unknown error') . '</div>';
        }

        echo '<div class="card"><h3>Response</h3><pre>' . htmlspecialchars(json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre></div>';
        echo '<p><a href="/?action=shipment&id=' . $id . '">View Shipment</a> | <a href="/">Back to Carriers</a></p>';

    } catch (\Throwable $e) {
        echo '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        echo '<div class="card"><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre></div>';
        echo '<p><a href="/?action=test&carrier=' . urlencode($carrierName) . '">Try Again</a></p>';
    }
}

function handleTrack(CarrierTester $tester): void
{
    $carrierName = $_GET['carrier'] ?? '';
    $trackingNumber = $_GET['tracking'] ?? '';

    $username = $password = $api_token = '';

    if ($carrierName === 'Aras'){
        $username = 'neodyum';
        $password = 'nd2580';
    }else if ($carrierName === 'Yurtici'){
        $username = 'YKTEST';
        $password = 'YK';
    }else if ($carrierName === 'KolayGelsin'){
        $api_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6IjI5ODM5MjA1MTEwIiwiY2hhbm5lbCI6IkludGVncmF0aW9uIiwiZW52aXJvbm1lbnQiOiJpbnRlZ3JhdGlvbiIsImlzcyI6ImxvY2FsaG9zdCIsImF1ZCI6ImFsbCIsImV4cCI6MTgwMzQwNDkwMCwibmJmIjoxNzcxODY4OTAwfQ.NmPbi6dLesP4PJUwUqf-CYKTdE_4z_hIkYJH8cpcuos';
    }

    if (empty($trackingNumber)) {
        echo "<h2>Track Shipment - {$carrierName}</h2>";
        echo '<div class="card">';
        echo '<form method="GET" action="/">';
        echo '<input type="hidden" name="action" value="track">';
        echo '<input type="hidden" name="carrier" value="' . htmlspecialchars($carrierName) . '">';
        if ($carrierName === 'KolayGelsin') {
            echo '<div class="form-group"><label>API Token</label><input type="text" name="api_token" placeholder="Bearer API Token" value="'.$api_token.'"></div>';
        } else {
            echo '<div class="form-row">';
            echo '<div class="form-group"><label>Username</label><input type="text" name="username" placeholder="API Username" value="'.$username.'"></div>';
            echo '<div class="form-group"><label>Password</label><input type="password" name="password" placeholder="API Password" value="'.$password.'"></div>';
            echo '</div>';
        }
        echo '<div class="form-group"><label>Tracking Number / Shipment ID</label><input type="text" name="tracking" placeholder="Enter tracking number or shipment ID" required></div>';
        echo '<button type="submit" class="btn">Track</button>';
        echo '</form></div>';
        return;
    }

    try {
        if ($carrierName === 'KolayGelsin') {
            $carrier = $tester->createCarrier($carrierName, [
                'apiToken' => $_GET['api_token'] ?? '',
                'testMode' => true,
            ]);
        } else {
            $carrier = $tester->createCarrier($carrierName, [
                'username' => $_GET['username'] ?? '',
                'password' => $_GET['password'] ?? '',
                'testMode' => true,
            ]);
        }

        $trackingParams = ($carrierName === 'KolayGelsin')
            ? ['shipmentId' => $trackingNumber]
            : ['trackingNumber' => $trackingNumber];
        $response = $carrier->getTrackingStatus($trackingParams)->send();

        echo "<h2>Tracking: {$trackingNumber}</h2>";

        if ($response->isSuccessful()) {
            $info = $response->getTrackingInfo();
            echo '<div class="card">';
            echo '<p><strong>Status:</strong> <span class="badge badge-' . strtolower($info->status->value) . '">' . $info->status->value . '</span></p>';
            echo '<table><thead><tr><th>Date</th><th>Status</th><th>Location</th><th>Description</th></tr></thead><tbody>';
            foreach ($info->events as $event) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($event->occurredAt->format('Y-m-d H:i')) . '</td>';
                echo '<td><span class="badge badge-' . strtolower($event->status->value) . '">' . $event->status->value . '</span></td>';
                echo '<td>' . htmlspecialchars($event->location ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($event->description) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';
        } else {
            echo '<div class="alert alert-error">Tracking failed: ' . htmlspecialchars($response->getMessage() ?? 'Unknown error') . '</div>';
        }

        echo '<div class="card"><h3>Raw Response</h3><pre>' . htmlspecialchars(json_encode($response->getData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre></div>';

    } catch (\Throwable $e) {
        echo '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        echo '<div class="card"><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre></div>';
    }
}

function renderShipments(CarrierTester $tester): void
{
    $shipments = $tester->getShipments();

    echo '<h2>Shipment History</h2>';

    if (empty($shipments)) {
        echo '<div class="alert alert-info">No shipments yet. Create one from the Carriers page.</div>';
        return;
    }

    echo '<div class="card"><table>';
    echo '<thead><tr><th>ID</th><th>Carrier</th><th>Tracking #</th><th>Barcode</th><th>Status</th><th>Created</th><th></th></tr></thead>';
    echo '<tbody>';
    foreach ($shipments as $s) {
        $badgeClass = 'badge-' . ($s['status'] ?? 'created');
        echo '<tr>';
        echo '<td>' . $s['id'] . '</td>';
        echo '<td>' . htmlspecialchars($s['carrier']) . '</td>';
        echo '<td>' . htmlspecialchars($s['tracking_number'] ?? '-') . '</td>';
        echo '<td>' . htmlspecialchars($s['barcode'] ?? '-') . '</td>';
        echo '<td><span class="badge ' . $badgeClass . '">' . htmlspecialchars($s['status']) . '</span></td>';
        echo '<td>' . $s['created_at'] . '</td>';
        echo '<td><a href="/?action=shipment&id=' . $s['id'] . '">View</a></td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
}

function renderShipmentDetail(CarrierTester $tester, int $id): void
{
    $shipment = $tester->getShipment($id);

    if (!$shipment) {
        echo '<div class="alert alert-error">Shipment not found.</div>';
        return;
    }

    echo '<h2>Shipment #' . $id . '</h2>';
    echo '<div class="card">';
    echo '<p><strong>Carrier:</strong> ' . htmlspecialchars($shipment['carrier']) . '</p>';
    echo '<p><strong>Tracking:</strong> ' . htmlspecialchars($shipment['tracking_number'] ?? '-') . '</p>';
    echo '<p><strong>Barcode:</strong> ' . htmlspecialchars($shipment['barcode'] ?? '-') . '</p>';
    echo '<p><strong>Status:</strong> <span class="badge badge-' . $shipment['status'] . '">' . htmlspecialchars($shipment['status']) . '</span></p>';
    echo '<p><strong>Created:</strong> ' . $shipment['created_at'] . '</p>';
    echo '</div>';

    echo '<div class="card"><h3>Request Data</h3><pre>' . htmlspecialchars(json_encode(json_decode($shipment['request_data'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre></div>';
    echo '<div class="card"><h3>Response Data</h3><pre>' . htmlspecialchars(json_encode(json_decode($shipment['response_data'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre></div>';

    $logs = $tester->getTrackingLogs($id);
    if (!empty($logs)) {
        echo '<div class="card"><h3>Tracking History</h3>';
        echo '<table><thead><tr><th>Date</th><th>Status</th><th>Location</th><th>Description</th></tr></thead><tbody>';
        foreach ($logs as $log) {
            echo '<tr>';
            echo '<td>' . $log['event_date'] . '</td>';
            echo '<td>' . htmlspecialchars($log['status']) . '</td>';
            echo '<td>' . htmlspecialchars($log['location'] ?? '-') . '</td>';
            echo '<td>' . htmlspecialchars($log['description'] ?? '-') . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }

    echo '<p><a href="/?action=shipments">Back to Shipments</a></p>';
}
