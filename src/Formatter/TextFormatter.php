<?php

/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

declare(strict_types=1);

namespace App\Formatter;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TextFormatter implements FormatterInterface
{
    /**
     * @var SymfonyStyle
     */
    private $style;

    public function __construct(InputInterface $input, OutputInterface $output, string $title)
    {
        $this->style = new SymfonyStyle($input, $output);
        $this->style->title($title);
    }

    public function display(float $value): void
    {
        $this->style->text(sprintf("%s %.6f", date('c'), $value));
    }
}
