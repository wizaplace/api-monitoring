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

interface FormatterInterface
{
    public function __construct(InputInterface $input, OutputInterface $output, string $title);

    public function display(float $value): void;
}
