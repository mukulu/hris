<?php
/*
 *
 * Copyright 2012 Human Resource Information System
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */
namespace Hris\FormBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateResourceTableCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('hris:resourcetable:generate')
            ->setDescription('Generate Resource Table')
            ->addArgument('name', InputArgument::OPTIONAL, 'Resourcetable name')
            ->addOption('extensive', null, InputOption::VALUE_NONE, 'If set, then resource table will be generated with organisation unit hierarchy')
            ->addOption('standard', null, InputOption::VALUE_NONE,'If set, then resource table will be generated without organisation unit hierarchy')
            ->addOption('forced',null,InputOption::VALUE_NONE,'If set, then currently generating resource table will be destroyed and regenerated')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $this->resourceTableName = $name;
        } else {
            $this->resourceTableName = 'All Fields';
        }

        if ($input->getOption('extensive')) {
            $this->resourceTableNature = 'extensive';
        }elseif($input->getOption('standard')) {
            $this->resourceTableNature = 'standard';
        }else {
            $this->resourceTableNature = 'extensive';
        }

        $em = $this->getContainer()->get('doctrine')->getManager();
        $logger = $this->getContainer()->get('logger');

        // Find Resource table for generation
        $entity = $em->getRepository('HrisFormBundle:ResourceTable')->findOneBy(array('name'=>$this->resourceTableName));
        //Issue resource table generation command
        $success = $entity->generateResourceTable($em,$logger);

        if($entity->getIsgenerating()) {
            $output->writeln('This resource table is Currently Being Generated');
        }
        $this->messageLog = rtrim($entity->getMessageLog(),"\n");

        if($input->getOption('forced')) {
            $entity->setIsgenerating(false);
            $em->persist($entity);
            $em->flush();
        }

        $output->writeln($this->messageLog);
    }

    /**
     * @var string
     */
    private $resourceTableName;

    /**
     * @var string
     */
    private $resourceTableNature;

    /**
     * @var string
     */
    private $messageLog;
}