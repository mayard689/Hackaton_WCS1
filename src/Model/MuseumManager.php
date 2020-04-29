<?php

namespace App\Model;

use Symfony\Component\HttpClient\HttpClient;

/**
 *
 */
class MuseumManager
{

    protected $client;

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        $this->client = HttpClient::create();
    }


    /*
     * get data from the object with the given id
     * use var dump to check available keys
     */
    public function getObject(int $objectId): array
    {
        $content=null;

        $response = $this->client->request(
            'GET',
            'https://collectionapi.metmuseum.org/public/collection/v1/objects/'.$objectId
        );

        $statusCode = $response->getStatusCode(); // get Response status code 200

        if ($statusCode === 200) {
            $content = $response->getContent();
            // get the response in JSON format

            $content = $response->toArray();
            // convert the response (here in JSON) to an PHP array
        }

        return $content;
    }


    /**
     * get random objects id from the given department
     * if department is not provided, defaut is 10 (egypt)
     * @param int $number : number iof Id we want
     * @param int $dptId
     * @return array
     */
    public function getIdFromDpt(int $number, int $dptId = 10): array
    {
        $content=null;
        $objectIds=array();

        $response = $this->client->request(
            'GET',
            'https://collectionapi.metmuseum.org/public/collection/v1/objects?departmentIds='.$dptId
        );

        $statusCode = $response->getStatusCode(); // get Response status code 200

        if ($statusCode === 200) {
            $content = $response->getContent();
            // get the response in JSON format

            $content = $response->toArray();
            $idList=$content['objectIDs'];
            // convert the response (here in JSON) to an PHP array

            $randKeys = array_rand($idList, $number);

            foreach ($randKeys as $key) {
                $objectIds[]=$idList[$key];
            }
        }

        return $objectIds;
    }
}
