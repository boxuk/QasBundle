<?php

namespace BoxUK\QasBundle\Controller;

use BoxUK\QasBundle\ClientFactory\ProWeb;
use BoxUK\QasBundle\Entity\EngineType;
use BoxUK\QasBundle\Entity\QASearch;
use BoxUK\QasBundle\Repository\ProWebRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zend\Soap\Client;

class ProWebController extends ContainerAware
{
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var ProWebRepository
     */
    private $repository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $environment;

    /**
     * @param EngineInterface $engine
     * @param ProWebRepository $repository
     * @param string $environment
     */
    public function __construct(EngineInterface $engine, ProWebRepository $repository, $environment)
    {
        $this->engine = $engine;
        $this->repository = $repository;
        $this->environment = $environment;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Searches for addresses matching a given query using the QAS ProWeb service
     *
     * @param Request $request
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function searchAction(Request $request)
    {
        $query = $request->get('query');
        $country = $request->get('country', 'GBR');

        $qaSearch = new QASearch();
        $qaSearch->Search = $query;
        $qaSearch->Country = $country;
        $qaSearch->Engine = new EngineType();

        $results = array();

        try {
            $results = $this->repository->findAddressesMatchingQuery($qaSearch);
        } catch (\Exception $e) {
            if ($this->logger instanceof LoggerInterface) {
                $this->logger->error($e->getMessage());
            }

            if ($this->environment !== 'prod') {
                throw $e;
            }
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array('results' => $results));
        }

        return $this->engine->renderResponse(
            'BoxUKQasBundle:ProWeb:search.html.twig',
            array(
                'results' => $results
            )
        );
    }
}
