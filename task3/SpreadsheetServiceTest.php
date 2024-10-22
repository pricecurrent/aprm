<?php

namespace Tests;

use Tests\TestCase;

class SpreadsheetServiceTest extends TestCase
{
    #[Test]
    public function it_creates_data_out_of_imported_data()
    {
        Queue::fake();
        $products_data = [
            ['product_code' => '123', 'quantity' => 1],
            ['product_code' => '456', 'quantity' => 2],
        ];
        $filePath = 'test.csv';

        $mock = $this->mock('importer');
        $importer->shouldReceive('import')
            ->with($filePath)
            ->once()
            ->andReturn($products_data);

        resolve(SpreadsheetService::class)->processSpreadsheet($filePath);

        $this->assertCount(2, Product::all());
        $this->assertEquals('123', Product::first()->code);
        $this->assertEquals(1, Product::first()->quantity);
        $this->assertEquals('456', Product::find(2)->code);
        $this->assertEquals(2, Product::find(2)->quantity);

        Queue::assertPushed(ProcessProductImage::class, 2);
        Queue::assertPushed(ProcessProductImage::class, function ($job) {
            return in_array($job->product->code, ['123', '456']);
        });
    }

    #[Test]
    public function it_ignores_invalid_source_data_and_skips_product_creation()
    {
        Product::factory()->create(['product_code' => 'abc']);
        $products_data = [
            ['product_code' => 'abc', 'quantity' => 1],
            ['product_code' => '', 'quantity' => 1],
            ['product_code' => null, 'quantity' => 1],
            ['product_code' => '9090', 'quantity' => 0],
            ['product_code' => '9090', 'quantity' => null],
            ['product_code' => '9090', 'quantity' => 3.5],
        ];
        $filePath = 'test.csv';

        $mock = $this->mock('importer');
        $importer->shouldReceive('import')
            ->with($filePath)
            ->once()
            ->andReturn($products_data);

        resolve(SpreadsheetService::class)->processSpreadsheet($filePath);

        $this->assertCount(0, Product::all());
    }
}
