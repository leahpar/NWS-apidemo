<?php

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CouvertureApiTest extends WebTestCase
{

    // ./bin/phpunit
    // option '--testdox' pour un affichage joli des résultats


    public $prefix = "/api";

    public function data()
    {
        $idVoiture = 99999;
        return [
            "get voitures" => [ "GET",        "/voitures",                200 ],
            "post empty voiture" => [ "POST",       "/voitures/new",            400 ],
            "get unknown voiture" => [ "GET",        "/voitures/$idVoiture",     404 ],
            "patch unknown voiture" => [ "PATCH",      "/voitures/$idVoiture",     404 ],
            "delete unknown voiture" => [ "DELETE",     "/voitures/$idVoiture",     404 ],
        ];
    }

    /**
     * @dataProvider data
     */
    public function test_some_basic_url($method, $route, $code)
    {
        $client = static::createClient();

        $client->request($method, $this->prefix . $route);

        $this->assertEquals(
            $client->getResponse()->getStatusCode(),
            $code,
            $method . $route . $code
        );
    }

    public function test_get_invalid_voiture()
    {
        $client = static::createClient();
        $client->request("GET", $this->prefix . "/voitures/99999");
        $this->assertEquals(
            $client->getResponse()->getStatusCode(),
            "404"
        );
    }

    public function test_create_a_new_voiture()
    {
        $client = static::createClient();
        $client->request(
            "POST",
            $this->prefix . "/voitures/new",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                  "marque": "Peugeot",
                  "modele": "208",
                  "annee": 2018,
                  "couleur": "FF1100"
            }'
        );
        $this->assertEquals(
            $client->getResponse()->getStatusCode(),
            "200"
        );

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotFalse($response);
        $this->assertGreaterThan(0, $response["id"]);

        return $response["id"];
    }

    /**
     * @depends test_create_a_new_voiture
     */
    public function test_get_created_voiture($id)
    {
        $client = static::createClient();
        $client->request("GET", $this->prefix . "/voitures/$id");
        $this->assertEquals(
            $client->getResponse()->getStatusCode(),
            "200"
        );
    }

}

