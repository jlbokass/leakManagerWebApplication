<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\User;
use App\Repository\PostLikeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebCampaignController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     */
    #[Route('/campaigns', name: 'app_web_campaign')]
    public function index()
    {
        $response = $this->client->request(
            'GET',
            'https://127.0.0.1:8000/api/campaigns?page=1&limit=20'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();

        return $this->render('web_campaign/index.html.twig', [
            'campaigns' => $content
        ]);
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    #[Route('/campaigns/show/{id}', name: 'app_web_campaign_show')]
    public function show(int $id): Response
    {
        $response = $this->client->request(
            'GET',
            'https://127.0.0.1:8000/api/campaigns/'. $id, [
                'query' => ['id' => $id]
            ]
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();

        return $this->render('web_campaign/show.html.twig', [
            'campaign' => $content
        ]);
    }

    /**
     * @param int $id
     *
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('campaigns/delete/{id}', name: 'app_web_campaign_delete')]
    public function deleteCampaign(int $id): Response
    {
        $this->client->request(
            'DELETE',
            'https://127.0.0.1:8000/api/campaigns/'. $id, [
                'query' => ['id' => $id]
            ]
        );

        return new Response(null, 204);
    }

    #[Route('/campaigns/form', name: 'app_web_campaign_form')]
    public function newCampaignForm(): Response
    {
        return $this->render('web_campaign/new.html.twig');
    }

    #[Route('/campaigns/new', name: 'app_web_campaign_new')]
    public function newCampaign(HttpClientInterface $httpClient): Response
    {
        $societyName = $_POST['campaigns']['society_name'];
        $location = $_POST['campaigns']['location'];
        $kwhPrice = $_POST['campaigns']['kwh_price'];
        $nbrCompressorUseByYear = $_POST['campaigns']['nbr_compressor_use_by_year'];
        $electricityPrice = $_POST['campaigns']['electricity_price'];
        $description = $_POST['campaigns']['description'];

        $data = array(
            'societyName' => $societyName,
            'location' => $location,
            'kwhPrice' => floatval($kwhPrice),
            'nbrCompressorUseByYear' => floatval($nbrCompressorUseByYear),
            'electricityPrice' => floatval($electricityPrice),
            'description' => $description,
        );

        $this->client->request(
            'POST',
            'https://127.0.0.1:8001/api/campaigns',
            [
                'body' => json_encode($data),
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ],
        );

        $this->addFlash(
            'success',
            'Campagne ajoutée'
        );

        return $this->redirectToRoute('app_web_campaign');
    }

    #[Route('/campaigns/edit/{id}')]
    public function editCampaign(int $id)
    {
        $response = $this->client->request(
            'POST',
            'https://127.0.0.1:8001/api/campaigns/'. $id, [
                'query' => ['id' => $id]
            ]
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();

        return $this->render('web_campaign/show.html.twig', [
            'campaign' => $content
        ]);
    }
}
