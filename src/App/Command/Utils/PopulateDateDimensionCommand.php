<?php
declare(strict_types=1);
/**
 * /src/App/Command/Utils/PopulateDateDimensionCommand.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Command\Utils;

use App\Entity\DateDimension;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class PopulateDateDimensionCommand
 *
 * @package App\Command\Utils
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class PopulateDateDimensionCommand extends ContainerAwareCommand
{
    const YEAR_MIN = 1970;
    const YEAR_MAX = 2047; // This should be the year when I'm officially retired

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    protected function configure()
    {
        // Configure command
        $this
            ->setName('utils:populateDateDimension')
            ->setDescription('Console command to populate \'date_dimension\' table.')
        ;
    }

    /**
     * Executes the current command.
     *
     * @throws  \LogicException
     * @throws  \RuntimeException
     * @throws  OptimisticLockException
     * @throws  ORMInvalidArgumentException
     * @throws  ServiceCircularReferenceException
     * @throws  ServiceNotFoundException
     *
     * @param   InputInterface $input An InputInterface instance
     * @param   OutputInterface $output An OutputInterface instance
     *
     * @return  void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Create output decorator helpers for the Symfony Style Guide.
        $this->io = new SymfonyStyle($input, $output);

        // Set title
        $this->io->title($this->getDescription());

        // Determine start and end years
        $yearStart = $this->getYearStart();
        $yearEnd = $this->getYearEnd($yearStart);

        // Create actual entities
        $this->createEntities($yearStart, $yearEnd);

        $this->io->success('All done - have a nice day!');
    }

    /**
     * Method to get start year value from user.
     *
     * @throws \RuntimeException
     *
     * @return int
     */
    private function getYearStart(): int
    {
        /**
         * Lambda validator function for start year io question.
         *
         * @param mixed $year
         *
         * @return int
         */
        $validator = function ($year) {
            $year = (int)$year;

            if ($year < self::YEAR_MIN || $year > self::YEAR_MAX) {
                throw new \RuntimeException('Start year must be between 1970 and 2037');
            }

            return $year;
        };

        return (int)$this->io->ask('Give a year where to start', self::YEAR_MIN, $validator);
    }

    /**
     * Method to get end year value from user.
     *
     * @throws \RuntimeException
     *
     * @param int $yearStart
     *
     * @return int
     */
    private function getYearEnd(int $yearStart): int
    {
        /**
         * Lambda validator function for end year io question.
         *
         * @param mixed $year
         *
         * @return int
         */
        $validator = function ($year) use ($yearStart) {
            $year = (int)$year;

            if ($year < self::YEAR_MIN || $year > self::YEAR_MAX) {
                throw new \RuntimeException('End year must be between 1970 and 2037');
            }

            if ($year < $yearStart) {
                throw new \RuntimeException('End year cannot be before given start year');
            }

            return $year;
        };

        return (int)$this->io->ask('Give a year where to end', self::YEAR_MAX, $validator);
    }

    /**
     * Method to create DateDimension entities to database.
     *
     * @throws \LogicException
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     *
     * @param int $yearStart
     * @param int $yearEnd
     */
    private function createEntities(int $yearStart, int $yearEnd)
    {
        $dateStart = new \DateTime($yearStart . '-01-01', new \DateTimeZone('UTC'));
        $dateEnd = new \DateTime($yearEnd . '-12-31', new \DateTimeZone('UTC'));

        $progress = $this->getProgressBar(
            (int)$dateEnd->diff($dateStart)->format('%a') + 1,
            sprintf('Creating DateDimension entities between years %d and %d...', $yearStart, $yearEnd)
        );

        // Get repository
        $repository = $this->getContainer()->get('repository.date_dimension');

        // Remove existing entities
        $repository->reset();

        // Get entity manager for _fast_ database handling.
        $em = $repository->getEntityManager();

        // Initialize used temp variable
        $currentYear = $yearStart;

        // You spin me round (like a record... er like a date)
        while (true) {
            // All done break the loop
            if ((int)$dateStart->format('Y') > $yearEnd) {
                break;
            }

            // Flush whole year of entities at one time
            if ($currentYear !== (int)$dateStart->format('Y')) {
                $em->flush();
                $em->clear();
            }

            // Persist entity, advance progress bar and move to next date
            $em->persist(new DateDimension(clone $dateStart));
            $progress->advance();
            $dateStart->add(new \DateInterval('P1D'));
        }

        // Finally flush remaining entities
        $em->flush();
        $em->clear();
    }

    /**
     * Helper method to get progress bar for console.
     *
     * @param   int     $steps
     * @param   string  $message
     *
     * @return  ProgressBar
     */
    private function getProgressBar(int $steps, string $message): ProgressBar
    {
        $format = <<<FORMAT
%message%
 %current%/%max% [%bar%] %percent:3s%%
 Time elapsed:   %elapsed:-6s%
 Time remaining: %remaining:-6s%
 Time estimated: %estimated:-6s%
 Memory usage:   %memory:-6s%


FORMAT;

        $progress = $this->io->createProgressBar($steps);
        $progress->setFormat($format);
        $progress->setMessage($message);

        return $progress;
    }
}
