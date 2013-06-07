<?php

namespace BoxUK\QasBundle\Controller;

use BoxUK\QasBundle\ClientFactory\ProWeb;
use BoxUK\QasBundle\Entity\EngineType;
use BoxUK\QasBundle\Entity\QASearch;
use BoxUK\QasBundle\Repository\ProWebRepository;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
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
     * @param EngineInterface $engine
     * @param ProWebRepository $repository
     */
    public function __construct(EngineInterface $engine, ProWebRepository $repository)
    {
        $this->engine = $engine;
        $this->repository = $repository;
    }

    /**
     * Searches for addresses matching a given query using the QAS ProWeb service
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $query = $request->get('query');
        $country = $request->get('country', 'GBR');

        $qaSearch = new QASearch();
        $qaSearch->Search = $query;
        $qaSearch->Country = $country;
        $qaSearch->Engine = new EngineType();

        $results = $this->repository->findAddressesMatchingQuery($qaSearch);

        return $this->engine->renderResponse(
            'BoxUKQasBundle:ProWeb:search.html.twig',
            array(
                'results' => $results
            )
        );
    }

}
