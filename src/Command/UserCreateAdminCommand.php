<?php

namespace App\Command;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserCreateAdminCommand extends Command
{
    protected static $defaultName = 'app:user:create-admin';
    protected static $defaultDescription = 'Add a short description for your command';

    private UserPasswordHasherInterface $passwordHasher;
    private ObjectManager $manager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, ManagerRegistry $registry)
    {
        parent::__construct(self::$defaultName);
        $this->passwordHasher = $passwordHasher;
        $this->manager = $registry->getManager();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'user email')
            ->addArgument('password', InputArgument::REQUIRED, 'user password')
            ->addArgument('firstname', InputArgument::REQUIRED, 'user firstname')
            ->addArgument('name', InputArgument::REQUIRED, 'user name')
            ;
    }

    public function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = $this->getHelper('question');

        $email = new Question('Your email :');
        $input->setArgument('email', $questions->ask($input, $output, $email));

        $password = new Question('Your password :');
        $input->setArgument('password', $questions->ask($input, $output, $password));
        
        $firstname = new Question('Your firstname :');
        $input->setArgument('firstname', $questions->ask($input, $output, $firstname));

        $name = new Question('Your name :');
        $input->setArgument('name', $questions->ask($input, $output, $name));   
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $firstname = $input->getArgument('firstname');
        $name = $input->getArgument('name');

        $user = new User();
        $user
            ->addRole('ROLE_ADMIN')
            ->setEmail($email)
            ->setFirstname($firstname)
            ->setName($name)
        ;
            
        $pwdHash = $this->passwordHasher->hashPassword($user, $password);

        $user->setPassword($pwdHash);

        $entityManager = $this->manager;
        $entityManager->persist($user);
        $entityManager->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

}
