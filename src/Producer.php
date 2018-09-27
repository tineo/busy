<?php
namespace Arqui\Esb;

use Amp\Http\Server\Request;
use Amp\Iterator;
use Amp\Promise;
use Amp\Success;
use Webgriffe\Esb\Model\Job;
use Webgriffe\Esb\HttpRequestProducerInterface;

class Producer implements HttpRequestProducerInterface{

    public function getAttachedRequestMethod(): string
    {
        // TODO: Implement getAttachedRequestMethod() method.
        return 'POST';
    }

    public function getAttachedRequestUri(): string
    {
        // TODO: Implement getAttachedRequestUri() method.
        return '/dummy';

    }

    /**
     * @return Promise
     */
    public function init(): Promise
    {
        // TODO: Implement init() method.

        return new Success(null);
    }

    /**
     * @param mixed $data
     * @return Iterator An Amp Iterator that must emit Jobs.
     */
    public function produce($data = null): Iterator
    {
        // TODO: Implement produce() method.
        return new \Amp\Producer(function (callable $emit) use ($data) {
            if (!$data instanceof Request) {
                throw new \InvalidArgumentException(
                    sprintf('Expected "%s" as data for "%s"', Request::class, __CLASS__)
                );
            }
            $body = json_decode(yield $data->getBody()->read(), true);
            $jobsData = $body['jobs'];
            foreach ($jobsData as $jobData) {
                yield $emit(new Job([$jobData]));
            }
        });
    }
}