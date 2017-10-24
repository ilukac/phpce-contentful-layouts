<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Contentful\Delivery\DynamicEntry;
use Contentful\Delivery\Query;

class SyncCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('contentful:sync')
            ->setDescription('Syncing space and content type cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $info = $this->getContainer()->getParameter('contentful.clients');
        $cacheDir = $this->getContainer()->getParameter('kernel.cache_dir') . "/contentful";

        if (count($info) === 0) {
            $output->writeln('<comment>There are no Contentful clients configured.</comment>');
            return;
        }

        $fs = new Filesystem();

        foreach ($info as $client) {
            $clientService = $this->getContainer()->get($client["service"]);
            $space = $clientService->getSpace();

            $spacePath = $cacheDir . '/' . $space->getId();
            if (!$fs->exists($spacePath)) {
                $fs->mkdir($spacePath);
            }
            $fs->dumpFile($spacePath . '/space.json', json_encode($space));

            $contentTypes = $clientService->getContentTypes(new Query());
            foreach ($contentTypes as $contentType) {
                $fs->dumpFile($spacePath . '/ct-' . $contentType->getId() . '.json', json_encode($contentType));
            }
        }
    }
}
