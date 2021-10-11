<?php

namespace App\Entity;

use App\Repository\TrafficRepository;
use DateTime;
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
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $uri = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $date = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $requestBody = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $requestHeaders = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $method = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $responseBody = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $responseHeaders = '';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $responseStatusCode = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $creationDate = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updateDate = null;

    /**
     * @ORM\PrePersist()
     * @return $this
     **/
    public function setCreationDateOnPrePersist(): self
    {
        $this->creationDate = new DateTime;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @return $this
     **/
    public function setUpdateDateOnUpdate(): self
    {
        $this->updateDate = new DateTime;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param DateTimeInterface|null $creationDate
     * @return $this
     */
    public function setCreationDate(?DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdateDate(): ?DateTimeInterface
    {
        return $this->updateDate;
    }

    /**
     * @param DateTimeInterface|null $updateDate
     * @return $this
     */
    public function setUpdateDate(?DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

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
