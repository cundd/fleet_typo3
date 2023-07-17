<?php

declare(strict_types=1);

namespace Cundd\Fleet\Command;

use Cundd\Fleet\Info\AllInformationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class InfoCommand extends Command
{
    public function __construct(private readonly AllInformationService $allInformationService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Print information')
            ->addOption('key', 'k', InputOption::VALUE_REQUIRED, 'Type of information to fetch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $allInformation = $this->allInformationService->getInformation();
        $key = $input->getOption('key');
        if ($key) {
            $information = ArrayUtility::getValueByPath($allInformation, $key, '.');
        } else {
            $information = $allInformation;
        }

        $output->write(json_encode($information, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
