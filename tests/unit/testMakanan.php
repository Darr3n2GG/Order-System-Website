<?php

use PHPUnit\Framework\TestCase;

final class GreeterTest extends TestCase {
    public function testprintMakanan(): void {
        $makanan = new Makanan;
        $arrayMakanan = $makanan->getAllMakananString();
        $this->markTestIncomplete(
            'This test has not been implemented yet.',
        );
    }
}
