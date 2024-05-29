<?php

use App\Enums\OutputTypeEnum;
use App\Services\NotificationHandlerService;
use PHPUnit\Framework\TestCase;

class NotificationTxtTest extends TestCase
{
    private string $outputTxtFile = __DIR__ . '/../../public/output.txt';
    private string $header = 'date_time status total';
    private int $dataLinesQty = 3;
    private string $requiredStatus = 'paid';

    function setUp(): void
    {
        //(new NotificationHandlerService(OutputTypeEnum::TXT))->handle();
    }

    public function test_txt_output_must_exit(): void
    {
        //ARRANGE
        //ACT

        //ASSERT
        $this->assertFileExists($this->outputTxtFile);
    }

    public function test_txt_output_must_have_header(): void
    {
        //ARRANGE
        $file = fopen($this->outputTxtFile, 'r');

        //ACT
        $header = str_replace(["\r", "\n"], '', fgets($file));

        //ASSERT
        $this->assertEquals($header, $this->header);
    }

    public function test_txt_output_data_must_have_3_lines(): void
    {
        //ARRANGE
        $data = $this->loadTxtFile();

        //ACT

        //ASSERT
        $this->assertIsArray($data);
        $this->assertCount($this->dataLinesQty, $data);
    }

    public function test_txt_output_data_must_have_valid_date_time(): void
    {
        //ARRANGE
        $data = $this->loadTxtFile();

        //ACT

        //ASSERT
        foreach ($data as $line) {
            $columns = explode(' ', $line);

            $this->assertCount(4, $columns);

            $dateTime = "$columns[0] $columns[1]";

            $this->assertIsString($dateTime);
            $this->assertInstanceOf(
                DateTimeImmutable::class,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateTime)
            );
        }
    }

    public function test_txt_output_data_must_have_valid_status(): void
    {
        //ARRANGE
        $data = $this->loadTxtFile();

        //ACT

        //ASSERT
        foreach ($data as $line) {
            $this->assertIsString($line[1]);
            $columns = explode(' ', $line);

            $this->assertEquals($this->requiredStatus, $columns[2]);
        }
    }

    public function test_txt_output_data_must_have_valid_total(): void
    {
        //ARRANGE
        $data = $this->loadTxtFile();

        //ACT

        //ASSERT
        foreach ($data as $line) {
            $this->assertIsString($line[2]);

            $columns = explode(' ', $line);

            $this->assertIsFloat((float) $columns[3]);
        }
    }

    public function test_output_must_be_equals_to_expected(): void
    {
        //ARRANGE
        $file = fopen(__DIR__ . '/../fixtures/notifications.txt', 'r');
        fgets($file);
        $expected = [];
        while (!feof($file)) {
            $line = fgets($file);
            if (is_string($line) && !empty($line)) {
                $expected[] = str_replace(["\r", "\n"], '', $line);
            }
        }

        $data = $this->loadTxtFile();

        //ACT

        //ASSERT
        $this->assertEquals($expected, $data);
    }

    private function loadTxtFile(): array
    {
        $file = fopen($this->outputTxtFile, 'r');

        // Skip header
        fgets($file);

        $data = [];
        while (!feof($file)) {
            $line = fgets($file);
            if (is_string($line) && !empty($line)) {
                $data[] = str_replace(["\r", "\n"], '', $line);
            }
        }

        return $data;
    }
}
