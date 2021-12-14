<?php

namespace Cundd\Fleet\Command;

use Cundd\Fleet\Info\AllInformationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class InfoCommand extends Command
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setDescription('Print information')
            ->addOption('key', 'k', InputOption::VALUE_REQUIRED, 'Type of information to fetch');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $allInformation = $this->getAllInformationService()->getInformation();
        $key = $input->getOption('key');
        if ($key) {
            $information = ArrayUtility::getValueByPath($allInformation, $key, '.');
        } else {
            $information = $allInformation;
        }

        $output->write(json_encode($information, JSON_PRETTY_PRINT));

        return 0;
    }

    /**
     * @return AllInformationService
     */
    protected function getAllInformationService()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        return $objectManager->get(AllInformationService::class);
    }
}
