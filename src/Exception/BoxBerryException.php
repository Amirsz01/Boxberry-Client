<?php

namespace Grokhotov\Boxberry\Exception;

use Exception;

class BoxBerryException extends Exception
{

    private ?string $raw_response;
    private ?string $raw_request;

    public function __construct($message = "", $code = 0, $raw_response = null, $raw_request = null, $previous = null)
    {
        $this->raw_request = $raw_request;
        $this->raw_response = $raw_response;
        parent::__construct($message, $code, $previous);
    }


    public function getRawResponse(): ?string
    {
        return $this->raw_response;
    }

    public function setRawResponse($raw_response): static
    {
        $this->raw_response = $raw_response;
        return $this;
    }

    public function getRawRequest(): ?string
    {
        return $this->raw_request;
    }

    public function setRawRequest($raw_request): static
    {
        $this->raw_request = $raw_request;
        return $this;
    }
}