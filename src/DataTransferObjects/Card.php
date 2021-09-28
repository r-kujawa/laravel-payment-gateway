<?php

namespace rkujawa\LaravelPaymentGateway\DataTransferObjects;

use rkujawa\LaravelPaymentGateway\Helpers\Sanitizer;

class Card extends DataTransferObject
{
    protected function properties(): array
    {
        return [
            'number',
            'expMonth',
            'expYear',
            'code',
            'type'
        ];
    }

    protected function getNumberProperty()
    {
        return $this->properties['number'] ? decrypt($this->properties['number']) : null;
    }

    protected function getExpMonthProperty()
    {
        return $this->properties['expMonth'] ? decrypt($this->properties['expMonth']) : null;
    }

    protected function getExpYearProperty()
    {
        return $this->properties['expYear'] ? decrypt($this->properties['expYear']) : null;
    }

    protected function getCodeProperty()
    {
        return $this->properties['code'] ? decrypt($this->properties['code']) : null;
    }

    protected function getLast4DigitsProperty()
    {
        return substr($this->number, -4);
    }

    protected function setNumberProperty(string $number)
    {
        $this->properties['number'] = encrypt(Sanitizer::compress($number));
    }

    protected function setExpMonthProperty(string $expMonth)
    {
        $this->properties['expMonth'] = encrypt(Sanitizer::compress($expMonth));
    }

    protected function setExpYearProperty(string $expYear)
    {
        $expYear = Sanitizer::compress($expYear);
        $expYear = (strlen($expYear) < 4) ?
            \str_pad($expYear, 4, '20', STR_PAD_LEFT) :
            $expYear;
        $this->properties['expYear'] = encrypt($expYear);
    }

    protected function setCodeProperty(string $code)
    {
        $expMonth = Sanitizer::compress($code);
        $expMonth = \str_pad($expMonth, 2, '0', STR_PAD_LEFT);
        $this->properties['code'] = encrypt($expMonth);
    }

    public function getExpirationDate(string $seperator = '')
    {
        return $this->expMonth . $seperator .  $this->expYear;
    }
}
