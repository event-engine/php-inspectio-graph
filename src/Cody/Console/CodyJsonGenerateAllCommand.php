<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Cody\Console;

use EventEngine\InspectioGraph\Cody\CodeGenerator\WorkflowConfigFactory;
use OpenCodeModeling\CodeGenerator\Console\WorkflowContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CodyJsonGenerateAllCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('inspectio:cody:json:generate-all')
            ->setDescription('Converts InspectIO Cody JSON into Event Engine code')
            ->addArgument(WorkflowConfigFactory::SLOT_JSON, InputArgument::REQUIRED, 'Cody JSON string');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $json = $input->getArgument(WorkflowConfigFactory::SLOT_JSON);

        /** @var \OpenCodeModeling\CodeGenerator\Workflow\WorkflowContext $workflowContext */
        $workflowContext = $this->getHelper(WorkflowContext::class)->context();
        $workflowContext->put(WorkflowConfigFactory::SLOT_JSON, $json);

        $command = $this->getApplication()->find('ocmcg:workflow:run');
        $command->run(new ArrayInput([]), $output);

        return 0;
    }
}
