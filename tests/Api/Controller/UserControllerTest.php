<?php

namespace Tests\Api\Controller;

use AppBundle\Entity\User;
use AppBundle\Test\ResponseAsserter;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserControllerTest extends KernelTestCase
{
    /**
     * @var Client
     */
    private $client;

    private $responseAsserter;

    protected function setUp()
    {
        $baseUrl = getenv('TEST_BASE_URL');
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'defaults' => [
                'http_errors' => false
            ]
        ]);
        self::bootKernel();
        $this->purgeDatabase();
        $this->createUser('admin');
    }

    public function testPOST()
    {
        $data = array(
            'username' => 'test_user',
            'plainPassword' => 'test_pass',
            'roles' => 'ROLE_PAGE_1'
        );

        $response = $this->client->post('/api/user', [
            'query' => $data,
            'auth' => ['admin', 'admin']
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testGETUser()
    {
        $this->createUser('Pepe', 'foo', 'ROLE_PAGE_2');

        $response = $this->client->get('/api/user/Pepe', ['auth' => ['admin', 'admin']]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'username',
            'roles'
        ));
    }

    public function testPUTUser()
    {
        $this->createUser('Pepe', 'foo', 'ROLE_PAGE_2');

        $data = [
            'username' => 'test_user',
            'roles' => 'ROLE_ADMIN'
        ];

        $response = $this->client->put('/api/user/Pepe', [
            'query' => $data,
            'auth' => ['admin', 'admin']
        ]);
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testPATCHUser()
    {
        $this->createUser('Pepe', 'foo', 'ROLE_PAGE_2');

        $data = [
            'username' => 'test_user'
        ];

        $response = $this->client->patch('/api/user/Pepe', [
            'query' => $data,
            'auth' => ['admin', 'admin']
        ]);
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testDELETEUser()
    {
        $this->createUser('Pepe', 'foo', 'ROLE_PAGE_2');

        $response = $this->client->delete('/api/user/Pepe', ['auth' => ['admin', 'admin']]);
        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function createUser($username, $plainPassword = 'admin', $roles = 'ROLE_ADMIN')
    {
        $user = new User();
        $user->setUsername($username);
        $user->setRoles([$roles]);
        $password = $this->getService('security.password_encoder')
            ->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    protected function getService($id)
    {
        return self::$kernel->getContainer()
            ->get($id);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getService('doctrine.orm.entity_manager');
    }

    /**
     * @return ResponseAsserter
     */
    protected function asserter()
    {
        if ($this->responseAsserter === null) {
            $this->responseAsserter = new ResponseAsserter();
        }

        return $this->responseAsserter;
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getService('doctrine')->getManager());
        $purger->purge();
    }
}