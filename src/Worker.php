<?php
namespace Arqui\Esb;

use Amp\Promise;
use Webgriffe\Esb\Model\QueuedJob;
use Webgriffe\Esb\WorkerInterface;
use Amp\File;
use Amp\Success;
use function Amp\call;

class Worker implements WorkerInterface{


    /**
     * @var string
     */
    private $filename = '/home/tineo/pager.rb';

    /*public function __construct(string $filename)
    {
        //$this->filename = $filename;
    }*/

    /**
     * @return Promise
     */
    public function init(): Promise
    {
        // TODO: Implement init() method.
        return new Success(null);
    }

    /**
     * @param QueuedJob $job
     * @return Promise
     */
    public function work(QueuedJob $job): Promise
    {
        // TODO: Implement work() method.
        return call(function () use ($job) {
            $content = '';
            if (yield File\exists($this->filename)) {
                $content = yield \Amp\File\get($this->filename);
            }
            $content .= date('c') . ' - ' . serialize($job->getPayloadData()) . PHP_EOL;
            yield File\put($this->filename, $content);
        });
    }
}