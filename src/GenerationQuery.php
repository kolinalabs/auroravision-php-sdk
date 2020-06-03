<?php

/*
 * This file is part of the KolinaLabs/SolarUtils project.
 *
 * (c) Claudinei Machado <claudinei@kolinalabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KolinaLabs\SolarUtils\AuroraVision;

/**
 * GenerationQuery provides common utility methods for parameter
 * manipulation to query generation on aurora vision api.
 *
 * @author Claudinei Machado <cjchamado@gmail.com>
 */
abstract class GenerationQuery
{
    const SAMPLE_SIZE_MIN = 'Min5';
    const SAMPLE_SIZE_HOUR = 'Hour';
    const SAMPLE_SIZE_DAY = 'Day';
    const SAMPLE_SIZE_MONTH = 'Month';
    const SAMPLE_SIZE_YEAR = 'Year';

    const SAMPLE_SIZES = [
        self::SAMPLE_SIZE_MIN,
        self::SAMPLE_SIZE_HOUR,
        self::SAMPLE_SIZE_DAY,
        self::SAMPLE_SIZE_MONTH,
        self::SAMPLE_SIZE_YEAR
    ];

    const TIME_ZONE = 'UTC';

    /**
     * @var \DateTime|null
     */
    protected $startDate;

    /**
     * @var \DateTime|null
     */
    protected $endDate;

    /**
     * @var string
     */
    protected $sampleSize = self::SAMPLE_SIZE_MONTH;

    /**
     * @var string
     */
    protected $timeZone = self::TIME_ZONE;

    /**
     * @var string
     */
    protected $uriPrefix = null;

    /**
     * @var string
     */
    protected $uriSuffix = null;

    /**
     * Constructor default
     */
    protected function __construct(
        \DateTime $startDate = null,
        \DateTime $endDate = null,
        ?string $sampleSize = self::SAMPLE_SIZE_MONTH,
        ?string $timeZone = self::TIME_ZONE
    ) {
        $this->startDate = $startDate ?? new \DateTime();
        $this->endDate = $endDate ?? new \DateTime('1 month ago');
        $this->sampleSize = $sampleSize;
        $this->timeZone = $timeZone;
    }

    /**
     * @param \DateTime $startDate
     * @return $this
     */
    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $endDate
     * @return $this
     */
    public function setEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    /**
     * @param string $timeZone
     * @return $this
     */
    public function setTimeZone(string $timeZone): self
    {
        if (!\in_array($timeZone, \DateTimeZone::listIdentifiers(), true)) {
            throw new \InvalidArgumentException('Invalid timeZone.');
        }

        $this->timeZone = $timeZone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timeZone;
    }

    /**
     * @param string $sampleSize
     * @return $this
     */
    public function setSampleSize(string $sampleSize): self
    {
        if (!\in_array($sampleSize, self::SAMPLE_SIZES, true)) {
            throw new \InvalidArgumentException('Invalid sampleSize.');
        }

        $this->sampleSize = $sampleSize;
        return $this;
    }

    /**
     * @return string
     */
    public function getSampleSize(): string
    {
        return $this->sampleSize;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return [
            'sampleSize' => $this->sampleSize,
            'timeZone' => $this->timeZone,
            'startDate' => $this->startDate->format('Ymd'),
            'endDate' => $this->endDate->format('Ymd')
        ];
    }

    /**
     * Format URI with prefix/identity/suffix
     *
     * @param string $identity
     * @return string $uri
     */
    public function getUri($identity): string
    {
        return sprintf('%s/%s/%s', $this->uriPrefix, $identity, $this->uriSuffix);
    }
}
