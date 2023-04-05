<?php

namespace Surrim\BaseConverter;

use GMP;

class BaseConverter {
    const BASE2 = '01';
    const BASE10 = '0123456789';
    const BASE16 = '0123456789abcdef';
    const BASE32_RFC4648_6 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    const ZBASE32 = 'ybndrfg8ejkmcpqxot1uwisza345h769';
    const BASE36 = '0123456789abcdefghijklmnopqrstuvwxyz';
    const BASE64_RFC4648_4 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    const BASE64_RFC4648_5 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';

    public static function transcode(string $encoded, string $sourceAlphabet, string $targetAlphabet): ?string {
        $decoded = self::decode($encoded, $sourceAlphabet);
        if ($decoded === null) {
            return null;
        }
        return self::encode($decoded, $targetAlphabet);
    }

    public static function decode(string $encoded, string $alphabet): ?string {
        [$base, $alphabetChars] = self::decodeAlphabet($alphabet);
        $encodedChars = mb_str_split($encoded);
        $encodedSize = count($encodedChars);
        if ($base < 2 || $encodedSize === 0 || ($encodedSize > 1 && $encodedChars[0] === $alphabetChars[0])) {
            return null;
        }

        $decodedNumber = gmp_init(0);
        foreach ($encodedChars as $encodedChar) {
            $encodedCharValue = array_search($encodedChar, $alphabetChars);
            if ($encodedCharValue === false) {
                return null;
            }
            $decodedNumber *= $base;
            $decodedNumber += $encodedCharValue;
        }
        return gmp_strval($decodedNumber);
    }

    private static function decodeAlphabet(string $alphabet): array {
        $alphabetChars = mb_str_split($alphabet);
        $base = count($alphabetChars);
        return [$base, $alphabetChars];
    }

    public static function encode(GMP|string|int $decoded, string $alphabet): ?string {
        [$base, $alphabetChars] = self::decodeAlphabet($alphabet);
        $decodedNumber = gmp_init($decoded);
        if ($base < 2 || $decodedNumber < 0) {
            return null;
        }

        $encodedChars = [];
        while ($decodedNumber > 0) {
            $remainder = gmp_intval(gmp_mod($decodedNumber, $base));
            $encodedChars[] = $alphabetChars[$remainder];
            $decodedNumber /= $base;
        }
        if (count($encodedChars) === 0) {
            $encodedChars[] = $alphabetChars[0];
        }
        return implode(array_reverse($encodedChars));
    }
}
