<?php

use App\Enums\OutputTypeEnum;
use App\Services\NotificationHandlerService;
use PHPUnit\Framework\TestCase;

class NotificationCsvTest extends TestCase
{
    private string $outputCsvFile = __DIR__ . '/../../public/output.csv';
    private array $header = ['date_time', 'status', 'total'];
    private int $dataLinesQty = 3;
    private string $requiredStatus = 'paid';

    function setUp(): void
    {
        (new NotificationHandlerService(OutputTypeEnum::CSV))->handle();
    }

    public function test_csv_output_must_exit(): void
    {
        //ARRANGE
        //ACT

        //ASSERT
        $this->assertFileExists($this->outputCsvFile);
    }

    public function test_csv_output_must_have_header(): void
    {
        //ARRANGE
        $file = fopen($this->outputCsvFile, 'r');

        //ACT
        $header = fgetcsv($file);

        //ASSERT
        $this->assertEquals($header, $this->header);
    }

    public function test_csv_output_data_must_have_3_lines(): void
    {
        //ARRANGE
        $data = $this->loadCsvFile();

        //ACT

        //ASSERT
        $this->assertIsArray($data);
        $this->assertCount($this->dataLinesQty, $data);
    }

    public function test_csv_output_data_must_have_valid_date_time(): void
    {
        //ARRANGE
        $data = $this->loadCsvFile();

        //ACT

        //ASSERT
        foreach ($data as $line) {
            $this->assertIsString($line[0]);
            $this->assertInstanceOf(
                DateTimeImmutable::class,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $line[0])
            );
        }
    }

    public function test_csv_output_data_must_have_valid_status(): void
    {
        //ARRANGE
        $data = $this->loadCsvFile();

        //ACT

        //ASSERT
        foreach ($data as $line) {
            $this->assertIsString($line[1]);
            $this->assertEquals($this->requiredStatus, $line[1]);
        }
    }

    public function test_csv_output_data_must_have_valid_total(): void
    {
        //ARRANGE
        $data = $this->loadCsvFile();

        //ACT

        //ASSERT
        foreach ($data as $line) {
            $this->assertIsString($line[2]);
            $this->assertIsFloat((float) $line[2]);
        }
    }

    public function test_output_must_be_equals_to_expected(): void
    {
        //ARRANGE
        $file = fopen(__DIR__ . '/../fixtures/notifications.csv', 'r');
        fgetcsv($file);
        $expected = [];
        while (!feof($file)) {
            $line = fgetcsv($file);
            if (is_array($line) && !empty($line)) {
                $expected[] = $line;
            }
        }

        $data = $this->loadCsvFile();

        //ACT

        //ASSERT
        $this->assertEquals($expected, $data);
    }

    private function loadCsvFile(): array
    {
        $file = fopen($this->outputCsvFile, 'r');
        // Skip header
        fgetcsv($file);

        $data = [];
        while (!feof($file)) {
            $line = fgetcsv($file);
            if (is_array($line) && !empty($line)) {
                $data[] = $line;
            }
        }

        return $data;
    }
}
