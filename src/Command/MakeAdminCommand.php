<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// ecrit dans le terminal : php bin/console app:make-admin ton@email.com (remplace ton@email)
#[AsCommand(
    name: 'app:make-admin',
    description: 'Attribue le rôle admin à un utilisateur',
)]
class MakeAdminCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur à promouvoir admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $output->writeln('<error>Utilisateur non trouvé.</error>');
            return Command::FAILURE;
        }

        $roles = $user->getRoles();
        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_ADMIN';
            $user->setRoles($roles);
            $this->em->flush();
            $output->writeln('<info>L\'utilisateur '.$email.' est maintenant admin.</info>');
        } else {
            $output->writeln('<comment>L\'utilisateur '.$email.' est déjà admin.</comment>');
        }

        return Command::SUCCESS;
    }
}