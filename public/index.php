<?php

use App\PaymentGateway;
use App\PaymentReport;
use App\Infrastructure\Filesystem\LocalFilesystem;

require_once __DIR__ . '/../vendor/autoload.php';

$notificationsDirectory = __DIR__ . '/../payment-notifications';
$requests = [
    'soad' => file_get_contents($notificationsDirectory . '/soad-gateway.json'),
    'nirvana' => file_get_contents($notificationsDirectory . '/nirvana-gateway.json'),
    'rhcp' => file_get_contents($notificationsDirectory . '/rhcp-gateway.xml'),
];

$channelHandlers = [
    new PaymentGateway\Nirvana\NirvanaNotificationHandler(),
    new PaymentGateway\Rhcp\RhcpNotificationHandler(),
    new PaymentGateway\Soad\SoadNotificationHandler(),
];

$notificationProcessor = new PaymentGateway\NotificationProcessor($channelHandlers);

$transactions = [];

foreach ($requests as $channel => $request) {
    $transactions[] = $notificationProcessor->handle(
        payload: $request,
        channel: PaymentGateway\PaymentGatewayChannel::from($channel)
    );
}

$fileSystem = new LocalFilesystem(__DIR__ . '/report');

$csvReportBuilder = new PaymentReport\PaymentReportGenerator(
    new PaymentReport\Writer\CsvReportWriter(
        filesystem: $fileSystem,
        fileName: 'notifications.csv',
        delimiter: ','
    )
);

$textReportBuilder = new PaymentReport\PaymentReportGenerator(
    new PaymentReport\Writer\CsvReportWriter(
        filesystem: $fileSystem,
        fileName: 'notifications.txt',
        delimiter: ' '
    )
);

$csvReportBuilder->generate($transactions, true);
$textReportBuilder->generate($transactions, true);
