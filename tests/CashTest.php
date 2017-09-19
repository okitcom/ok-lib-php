<?php
/**
 * Created by PhpStorm.
 * Date: 7/21/17
 */

namespace OK\Tests;

use OK\Builder\LineItemBuilder;
use OK\Builder\TransactionBuilder;
use OK\Model\Amount;
use OK\Model\Cash\LineItem;
use OK\Model\Cash\LineItems;
use OK\Model\Cash\Transaction;
use OK\Service\Cash;

class CashTest extends ServiceTest
{

    /** @var  Cash */
    protected $service;

    public function setUp() {
        parent::setUp();

        $this->service = new Cash($this->cashCredentials);
    }


    public function testGetStatus() {
        $this->markTestSkipped("Yet to find a valid transaction");
        $result = $this->service->status("XzPvkTBQQGy_eweAtKvQ2w");
        $this->assertEquals("ClosedAndCaptured", $result->state);
    }

    /**
     * @expectedException \OK\Model\Network\Exception\NetworkException
     */
    public function testGetGuidFail() {
        $this->service->status("XXX");
    }

    public function testGetTransaction() {
        $this->markTestSkipped("Yet to find a valid transaction");
        $result = $this->service->get("XzPvkTBQQGy_eweAtKvQ2w");
        $this->assertEquals("OK", $result->authorisationResult->result);
    }

    public function testInitiate() {
        $request = (new TransactionBuilder())
            ->setAmount(Amount::fromCents(1000))
            ->setReference("PHPUnit tx")
            ->build();

        $res = $this->service->request($request);
        $this->assertNotNull($res->guid);
        $this->assertEquals("NewPendingTrigger", $res->state);
    }

    public function testQR() {
        $request = (new TransactionBuilder())
            ->setAmount(Amount::fromCents(1000))
            ->setReference("PHPUnit tx")
            ->build();

        $res = $this->service->request($request);

        $qr = $this->service->qr($res->guid);
        $this->assertNotNull($qr);
    }

    public function testCancel() {
        $request = (new TransactionBuilder())
            ->setAmount(Amount::fromCents(1000))
            ->setReference("PHPUnit tx")
            ->build();

        $res = $this->service->request($request);
        $canceled = $this->service->cancel($res->guid);
        $this->assertTrue($canceled->success());
    }

    public function testCancelByReference() {
        $request = (new TransactionBuilder())
            ->setAmount(Amount::fromCents(1000))
            ->setReference("PHPUnit tx " . time())
            ->build();

        $res = $this->service->request($request);
        $canceled = $this->service->cancelByReference($res->reference);
        $this->assertTrue($canceled->success());
    }

    public function testGetByReference() {
        $request = (new TransactionBuilder())
            ->setAmount(Amount::fromCents(1000))
            ->setReference("PHPUnit tx ref " . time())
            ->build();

        $response = $this->service->request($request);
        $txs = $this->service->getByReference($request->reference);
        $this->assertEquals($response->guid, $txs->guid);
    }

    public function testGetLineItemsOne() {
        $request = (new TransactionBuilder())
            ->setAmount(Amount::fromCents(1000))
            ->setReference("PHPUnit tx")
            ->addLineItem(
                (new LineItemBuilder())
                    ->setAmount(Amount::fromEuro(10.00))
                    ->setVat(0)
                    ->setCurrency("EUR")
                    ->setQuantity(1)
                    ->setDescription("Beschrijving")
                    ->build()
            )
            ->build();

        $result = $this->service->request($request);

        $this->assertNotNull($result->lineItems->all());
    }

    public function testGetLineItemsTwo() {
        $request = new Transaction();
        $request->amount = 1500;
        $request->reference = "PHPUnit tx";

        $product1 = LineItem::create(1,
            null,
            "Beschrijving",
            Amount::fromEuro(10.00),
            0,
            "EUR");
        $product2 = LineItem::create(1,
            null,
            "Beschrijving 2",
            Amount::fromEuro(5.00),
            0,
            "EUR");

        $request = (new TransactionBuilder())
            ->setAmount(Amount::fromCents(1500))
            ->setReference("PHPUnit tx")
            ->addLineItem(
                (new LineItemBuilder())
                    ->setAmount(Amount::fromEuro(10.00))
                    ->setVat(0)
                    ->setCurrency("EUR")
                    ->setQuantity(1)
                    ->setDescription("Beschrijving")
                    ->build()
            )
            ->addLineItem(
                (new LineItemBuilder())
                    ->setAmount(Amount::fromEuro(5.00))
                    ->setVat(0)
                    ->setCurrency("EUR")
                    ->setQuantity(1)
                    ->setDescription("Beschrijving 2")
                    ->build()
            )
            ->build();

        $result = $this->service->request($request);

        $this->assertEquals(2, count($result->lineItems->all()));
    }

    public function testRefund() {
        $this->markTestSkipped("Infeasible to test");
    }


}
