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
 * GenerationEnergy provides utility methods for parameter
 * manipulation to query generation energy on aurora vision api.
 *
 * @author Claudinei Machado <cjchamado@gmail.com>
 */
class GenerationEnergy extends GenerationQuery
{
    /**
     * @var string
     */
    protected $uriPrefix = 'v1/stats/energy/timeseries';

    /**
     * @var string
     */
    protected $uriSuffix = 'GenerationEnergy/delta';

    /**
     * Make GenerationEnergy instance
     */
    public static function delta(
        \DateTime $startDate = null,
        \DateTime $endDate = null,
        ?string $sampleSize = self::SAMPLE_SIZE_MONTH,
        ?string $timeZone = self::TIME_ZONE
    ) {
        return new self($startDate, $endDate);
    }
}
