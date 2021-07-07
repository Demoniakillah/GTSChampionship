<?php

namespace App\Entity;

use App\Repository\TrafficRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrafficRepository::class)
 */
class Traffic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $uri;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $requestBody;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $requestHeaders;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $method;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $responseBody;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $responseHeaders;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $responseStatusCode;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * @param string|null $uri
     * @return $this
     */
    public function setUri(?string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface $date
     * @return $this
     */
    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestBody(): ?string
    {
        return $this->requestBody;
    }

    /**
     * @param string|null $requestBody
     * @return $this
     */
    public function setRequestBody(?string $requestBody): self
    {
        $this->requestBody = $requestBody;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestHeaders(): ?string
    {
        return $this->requestHeaders;
    }

    /**
     * @param string|null $requestHeaders
     * @return $this
     */
    public function setRequestHeaders(?string $requestHeaders): self
    {
        $this->requestHeaders = $requestHeaders;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @param string|null $method
     * @return $this
     */
    public function setMethod(?string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    /**
     * @param string|null $responseBody
     * @return $this
     */
    public function setResponseBody(?string $responseBody): self
    {
        $this->responseBody = $responseBody;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponseHeaders(): ?string
    {
        return $this->responseHeaders;
    }

    /**
     * @param string|null $responseHeaders
     * @return $this
     */
    public function setResponseHeaders(?string $responseHeaders): self
    {
        $this->responseHeaders = $responseHeaders;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getResponseStatusCode(): ?int
    {
        return $this->responseStatusCode;
    }

    /**
     * @param int|null $responseStatusCode
     * @return $this
     */
    public function setResponseStatusCode(?int $responseStatusCode): self
    {
        $this->responseStatusCode = $responseStatusCode;

        return $this;
    }
}
