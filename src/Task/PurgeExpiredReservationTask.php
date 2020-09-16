<?php

namespace Dynamic\Foxy\Inventory\Task;

use Dynamic\Foxy\Inventory\Model\CartReservation;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

/**
 * Class PurgeExpiredReservationTask
 * @package Dynamic\Foxy\Inventory\Task
 */
class PurgeExpiredReservationTask extends BuildTask
{
    /**
     * @var string
     */
    private static $segment = 'foxy-inventory-purge-expired-reservation-task';

    /**
     * @var string
     */
    protected $title = 'Foxy Inventory - Purge Expired Reservation Task';

    /**
     * @var string
     */
    protected $description = 'A task passing the expiration difference in minutes, purging anything older than that timeframe.';

    /**
     * @var string[]
     */
    private static $dependencies = [
        'logger' => '%$' . LoggerInterface::class . '.quiet',
    ];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var int
     */
    private $minutes;

    /**
     * PurgeExpiredReservationTask constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->logger = new NullLogger();
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param $minutes
     * @return $this
     */
    protected function setMinutes($minutes)
    {
        $this->minutes = $minutes;

        return $this;
    }

    /**
     * @return int
     */
    protected function getMinutes()
    {
        return $this->minutes;
    }

    /**
     * @param HTTPRequest $request
     */
    public function run($request)
    {
        if (!$minutes = $request->getVar('minutes')) {
            $this->logger->error("You must specify a timeframe in minutes to calculate deletion validity.");
        }

        $this->setMinutes($minutes);

        $this->processInventoryReservations();
    }

    /**
     *
     */
    protected function processInventoryReservations()
    {
        $expiration = date('Y-m-d H:i:s', strtotime("{$this->getMinutes()} minutes ago"));
        DB::prepared_query("DELETE FROM `FoxyCartReservation` WHERE `LastEdited` <= ?", [$expiration]);
    }
}
