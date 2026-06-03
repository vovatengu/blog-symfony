<?php

namespace App\Command;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand(
    name: 'app:post:deactivate-expired',
    description: 'Деактивирует все публикации, у которых истек срок публикации',
)]
#[AsCronTask('0 1 * * *')]
class DeactivateExpiredPostsCommand extends Command
{
    private SymfonyStyle $io;
    private const int DEFAULT_DAYS = 3;

    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $threshold = (new \DateTimeImmutable())->modify(\sprintf('-%d days', self::DEFAULT_DAYS));
        $count = $this->em->getRepository(Post::class)->deactivateOlderPosts($threshold);
        $this->io->success(\sprintf('Deactivated %d posts.', $count));

        return Command::SUCCESS;
    }
}
