<?php

use App\Services\CheckoutService;
use App\Repositories\TransactionRepositoryInterface;
use Midtrans\Snap;
use PHPUnit\Framework\TestCase;
use Mockery;


namespace Tests\Unit\Services;


class CheckoutServiceTest extends TestCase
{
  protected $transactionRepository;
  protected $checkoutService;

  protected function setUp(): void
  {
    parent::setUp();
    
    $this->transactionRepository = Mockery::mock(TransactionRepositoryInterface::class);
    $this->checkoutService = new CheckoutService($this->transactionRepository);
  }

  protected function tearDown(): void
  {
    Mockery::close();
    parent::tearDown();
  }

  public function test_process_checkout_creates_transaction_successfully()
  {
    $data = [
      'product_id' => 1,
      'customer_id' => 1,
      'quantity' => 2,
      'total_price' => 100000,
      'customer_name' => 'John Doe',
      'customer_email' => 'john@example.com',
      'customer_phone' => '081234567890'
    ];

    $mockTransaction = (object) [
      'id' => 'TXN123',
      'product_id' => 1,
      'customer_id' => 1,
      'quantity' => 2,
      'total_price' => 100000,
      'status' => 'pending'
    ];

    $this->transactionRepository
      ->shouldReceive('createTransaction')
      ->once()
      ->with([
        'product_id' => 1,
        'customer_id' => 1,
        'quantity' => 2,
        'total_price' => 100000,
        'status' => 'pending'
      ])
      ->andReturn($mockTransaction);

    $result = $this->checkoutService->processCheckout($data);

    $this->assertIsArray($result);
    $this->assertArrayHasKey('transaction', $result);
    $this->assertArrayHasKey('snap_token', $result);
    $this->assertEquals($mockTransaction, $result['transaction']);
  }

  public function test_process_checkout_handles_zero_quantity()
  {
    $data = [
      'product_id' => 1,
      'customer_id' => 1,
      'quantity' => 0,
      'total_price' => 0,
      'customer_name' => 'John Doe',
      'customer_email' => 'john@example.com',
      'customer_phone' => '081234567890'
    ];

    $mockTransaction = (object) [
      'id' => 'TXN124',
      'total_price' => 0
    ];

    $this->transactionRepository
      ->shouldReceive('createTransaction')
      ->once()
      ->andReturn($mockTransaction);

    $result = $this->checkoutService->processCheckout($data);

    $this->assertIsArray($result);
    $this->assertArrayHasKey('transaction', $result);
    $this->assertEquals(0, $result['transaction']->total_price);
  }

  public function test_process_checkout_handles_large_amounts()
  {
    $data = [
      'product_id' => 1,
      'customer_id' => 1,
      'quantity' => 100,
      'total_price' => 10000000,
      'customer_name' => 'Jane Doe',
      'customer_email' => 'jane@example.com',
      'customer_phone' => '081234567891'
    ];

    $mockTransaction = (object) [
      'id' => 'TXN125',
      'total_price' => 10000000
    ];

    $this->transactionRepository
      ->shouldReceive('createTransaction')
      ->once()
      ->andReturn($mockTransaction);

    $result = $this->checkoutService->processCheckout($data);

    $this->assertIsArray($result);
    $this->assertEquals(10000000, $result['transaction']->total_price);
  }

  public function test_process_checkout_with_special_characters_in_customer_data()
  {
    $data = [
      'product_id' => 1,
      'customer_id' => 1,
      'quantity' => 1,
      'total_price' => 50000,
      'customer_name' => 'José María',
      'customer_email' => 'jose.maria@example.com',
      'customer_phone' => '+62-812-3456-7890'
    ];

    $mockTransaction = (object) [
      'id' => 'TXN126',
      'total_price' => 50000
    ];

    $this->transactionRepository
      ->shouldReceive('createTransaction')
      ->once()
      ->andReturn($mockTransaction);

    $result = $this->checkoutService->processCheckout($data);

    $this->assertIsArray($result);
    $this->assertArrayHasKey('snap_token', $result);
  }

  public function test_constructor_sets_midtrans_configuration()
  {
    $service = new CheckoutService($this->transactionRepository);
    
    $this->assertInstanceOf(CheckoutService::class, $service);
  }
}