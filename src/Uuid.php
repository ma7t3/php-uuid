<?php

namespace ma7t3;

class Uuid {
    private string $data;

    private function __construct(string $data) {
        $this->data = $data;
    }

    public function data() {
        return $this->data;
    }

    public function toString() : string {
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($this->data), 4));
    }

    public function __toString() : string {
        return $this->toString();
    }

    static public function create() : self {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return new self($data);
    }

    static public function fromString(string $str) : self|null {
        if(!self::isValidString($str))
            return null;

        $hex = str_replace('-', '', $str);
        return new self(hex2bin($hex));
    }

    static public function fromData(string $data) : self|null {
        if(strlen($data) != 16)
            return null;
        return new self($data);
    }

    static protected function isValidString(?string $uuid): bool {
        return (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid ?? ""
        );
    }
}