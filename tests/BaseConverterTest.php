<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Surrim\BaseConverter\BaseConverter;

class BaseConverterTest extends TestCase {
    const DECODED = '123456789876543212345678987654321';
    const VALUES = [
        BaseConverter::BASE2 => '11000010110001111100110011100010010111010111011101010100100111000111101011000101011010000011111010010110001',
        BaseConverter::BASE10 => self::DECODED,
        BaseConverter::BASE16 => '6163e6712ebbaa4e3d62b41f4b1',
        BaseConverter::BASE32_RFC4648_6 => 'DBMPTHCLV3VJHD2YVUD5FR',
        BaseConverter::ZBASE32 => 'dbcxu8nmi5ij8d4aiwd7ft',
        BaseConverter::BASE36 => '98hc05z8u8gqlb63y960h',
        BaseConverter::BASE64_RFC4648_4 => 'YWPmcS67qk49YrQfSx',
        BaseConverter::BASE64_RFC4648_5 => 'YWPmcS67qk49YrQfSx',
    ];

    public function testBinary(): void {
        $this->check(BaseConverter::BASE2);
    }

    public function check($alphabet) {
        $this->assertSame(BaseConverter::encode(self::DECODED, $alphabet), self::VALUES[$alphabet]);
        $this->assertSame(BaseConverter::decode(self::VALUES[$alphabet], $alphabet), self::DECODED);
    }

    public function testDecimal(): void {
        $this->check(BaseConverter::BASE10);
    }

    public function testHexadecimal(): void {
        $this->check(BaseConverter::BASE16);
    }

    public function testBase32(): void {
        $this->check(BaseConverter::BASE32_RFC4648_6);
    }

    public function testZBase32(): void {
        $this->check(BaseConverter::ZBASE32);
    }

    public function testBase36(): void {
        $this->check(BaseConverter::BASE36);
    }

    public function testBase64Rfc4648_4(): void {
        $this->check(BaseConverter::BASE64_RFC4648_4);
    }

    public function testBase64Rfc4648_5(): void {
        $this->check(BaseConverter::BASE64_RFC4648_5);
    }
}
