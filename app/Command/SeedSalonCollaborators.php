<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Collaborators;
use App\Model\Salons;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;

#[Command]
class SeedSalonCollaborators extends HyperfCommand
{
    public function __construct()
    {
        parent::__construct('seed:salons-collaborators');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Seed saloons and collaborators.');
    }

    public function handle()
    {
        // Cria 5 salões
        for ($i = 1; $i <= 10; $i++) {
            $salon = Salons::create([
                'name' => "Salão $i",
                'address' => "Endereço $i",
                'phone' => "(11) 90000-00$i",
                'owner_id' => $i,
            ]);

            $this->output->writeln("Created Salon: {$salon->name}");

            for ($j = 1; $j <= 5; $j++) {
                $collaborator = Collaborators::create([
                    'salon_id' => $salon->id,
                    'name' => "Colaborador $j do Salão $i",
                    'email' => "colaborador{$j}_salao{$i}@example.com",
                    'phone' => "(11) 98888-00$j$i",
                    'role' => "Cabeleireiro",
                ]);

                $this->output->writeln("  -> Created Collaborator: {$collaborator->name}");
            }
        }

        $this->output->writeln('<info>Seeding completed successfully!</info>');
    }
}
