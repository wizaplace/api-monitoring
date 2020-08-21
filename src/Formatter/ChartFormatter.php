<?php

/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

declare(strict_types=1);

namespace App\Formatter;

use noximo\PHPColoredAsciiLinechart\Linechart;
use noximo\PHPColoredAsciiLinechart\Settings;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChartFormatter implements FormatterInterface
{
    /**
     * @var int[]
     */
    private $markers;

    /**
     * @var Linechart
     */
    private $linechart;

    public function __construct(InputInterface $input, OutputInterface $output, string $title)
    {
        $settings = new Settings();

        $settings
            ->setFPS(24)
            ->setHeight(30)
        ;

        $this->linechart = new Linechart();
        $this->linechart->setSettings($settings);
        $this->markers = [];
    }

    public function display(float $value)
    {

        $y = intval(1000 * $value);

        $this->markers[] = $y;

        $this->linechart->addMarkers(array_slice($this->markers, -100));

        $chart = $this->linechart->chart();
// does nothing, the bundle does not use these values
//            $chart->setMin(0);
//            $chart->setMax(300);
//            $chart->setWidth(100);

        $chart->clearScreen();
        $chart->print();
        $chart->wait();
        $this->linechart->clearAllMarkers();
    }

}