<?php

namespace Itr\ResponseBuilderBundle\Tests;

use Itr\ResponseBuilderBundle\ResponseBuilder\ResponseBuilderFactory;
use Itr\ResponseBuilderBundle\ResponseBuilder\ParameterBag;
use Itr\ResponseBuilderBundle\Tests\Fixture\Entity\Account;
use Itr\ResponseBuilderBundle\Tests\Fixture\Entity\Session;
use Itr\ResponseBuilderBundle\Tests\Fixture\Entity\Profile;

class ParameterBagTest extends \PHPUnit_Framework_TestCase
{
    public function testInitial()
    {
        $default = array('user' => 'test', 'password' => '123456');
        $pb = new ParameterBag($default);

        $this->assertEquals($default, $pb->toArray()); // initial and result arrays must be the same
    }

    public function testSetSimple()
    {
        $pb = new ParameterBag();
        $path = 'first.second';
        $value = 'some value';
        $pb->set($path, $value);
        $result = $pb->toArray();

        // such path should be expanded as:
        //
        // first.second = "some value" =>
        // array("first" => array("second" => "some value"));

        $this->assertCount(1, $result);
        $this->assertArrayHasKey('first', $result);
        $this->assertInternalType('array', $result['first']);
        $this->assertCount(1, $result['first']);
        $this->assertArrayHasKey('second', $result['first']);
        $this->assertEquals($value, $result['first']['second']);

        $path = 'first.new_second';
        $value = 'some new value';
        $pb->set($path, $value);
        $result = $pb->toArray();

        // such path will add new value live this:
        //
        // here in "first" array added new key => value pair
        // first.new_second = "some value" =>
        // array("first" => array("second" => "some value", "new_second" => "some new value"));

        $this->assertArrayHasKey('new_second', $result['first']);
        $this->assertEquals($value, $result['first']['new_second']);
    }

    public function testGetSimple()
    {
        $pb = new ParameterBag();
        $path = 'first.second';
        $value = 'some value';
        $pb->set($path, $value);

        $this->assertTrue(isset($pb->$path));
        $this->assertFalse(empty($pb->$path));
        $this->assertTrue($pb->has($path));
        $this->assertEquals($pb->get($path), $value);
        $this->assertEquals($pb->$path, $value);

        unset($pb->$path);
        $this->assertEquals($pb->$path, null);

        $this->assertEquals($pb->getParameters(), $pb->toArray());

        $value = 'some new value';
        $this->$path = $value;

        $this->assertEquals($this->$path, $value);
    }

    public function testSetSimpleEntity()
    {
        $pb = new ParameterBag();
        $account = $this->setupAccountEntity();

        $pb->setEntity('account', $account);
        $result = $pb->toArray();

        // entity will be expanded with all properties that have a public access or getter

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('account', $result);
        $this->assertEquals($account->getBlocked(), $result['account']['blocked']);
        $this->assertEquals($account->getUsername(), $result['account']['username']);
        $this->assertEquals($account->getEmail(), $result['account']['email']);
        $this->assertEquals($account->getCreatedAt()->getTimestamp(), $result['account']['createdAt']);
        $this->assertEquals($account->getSalt(), $result['account']['salt']);
        $this->assertInternalType('null', $result['account']['salt']);

        $pb->{'account.username'} = 'new_user_name';
        $result = $pb->toArray();
        $this->assertEquals('new_user_name', $result['account']['username']);

        unset($pb->{'account.username'});
        $result = $pb->toArray();
        $this->assertEquals(null, $result['account']['username']);
        $this->assertFalse(isset($result['account']['username']));
        $this->assertFalse($pb->has('account.username'));
        $this->assertEquals(null, $pb->get('account.username'));
    }

    public function testComplexEntity()
    {
        $pb = new ParameterBag();
        $account = $this->setupAccountEntity();
        $profile = $this->setupProfileEntity();
        $profile->setAccount($account);

        // each subentity of the entity will be expanded

        $pb->setEntity('profile', $profile);
        $result = $pb->toArray();
        $this->assertArrayHasKey('account', $result['profile']);
        $this->assertEquals($account->getEmail(), $result['profile']['account']['email']);
        $this->assertEquals($account->getUsername(), $result['profile']['account']['username']);

        $pb->{'profile.account.id'} =  0;
        $result = $pb->toArray();

        $this->assertEquals(0, $result['profile']['account']['id']);
        $this->assertEquals($account->getEmail(), $result['profile']['account']['email']);
        $this->assertEquals($account->getUsername(), $result['profile']['account']['username']);

        unset($pb->{'profile.account'});
        $result = $pb->toArray();

        $this->assertEquals(null, $result['profile']['account']);
        $this->assertFalse(isset($result['profile']['account']));
        $this->assertFalse($pb->has('profile.account.username'));
    }

    public function testEntityWithCollection()
    {
        $pb = new ParameterBag();
        $session1 = $this->setupSessionEntity();
        $session2 = $this->setupSessionEntity();
        $session3 = $this->setupSessionEntity();
        $session4 = $this->setupSessionEntity();
        $sessions = array($session1, $session2, $session3, $session4);
        $account = $this->setupAccountEntity();
        $account->setSessions($sessions);

        // each entity with collection of entities will be expanded as array or arrays

        $pb->setEntity('account', $account);
        $result = $pb->toArray();
        $this->assertArrayHasKey('sessions', $result['account']);
        $this->assertInternalType('array', $result['account']['sessions']);
        $this->assertEquals($session1->getSession(), $result['account']['sessions'][0]['session']);
        $this->assertEquals($session2->getSession(), $result['account']['sessions'][1]['session']);
        $this->assertEquals($session4->getSession(), $result['account']['sessions'][3]['session']);

        unset($pb->{'account.sessions'});
        $result = $pb->toArray();
        $this->assertFalse(isset($result['account']['sessions']));
        $this->assertFalse($pb->has('account.sessions'));
    }

    public function testEntityWithComplexCollection()
    {
        $pb = new ParameterBag();
        $session1 = $this->setupSessionEntity();
        $session1->setAccount($this->setupAccountEntity());
        $session2 = $this->setupSessionEntity();
        $session2->setAccount($this->setupAccountEntity());
        $session3 = $this->setupSessionEntity();
        $session3->setAccount($this->setupAccountEntity());
        $session4 = $this->setupSessionEntity();
        $session4->setAccount($this->setupAccountEntity());

        $sessions = array($session1, $session2, $session3, $session4);
        $account = $this->setupAccountEntity();
        $account->setSessions($sessions);

        $pb->setEntity('account', $account);
        $result = $pb->toArray();
        $this->assertArrayHasKey('sessions', $result['account']);
        $this->assertInternalType('array', $result['account']['sessions']);

        // TODO: this part isn't working because of wrong injection point finding
        // TODO: here we have an account entity in the sessions array, a bit weird behavior
        $this->assertEquals($session1->getSession(), $result['account']['sessions'][0]['session']);
        $this->assertEquals($session2->getSession(), $result['account']['sessions'][1]['session']);
    }

    protected function setupAccountEntity()
    {
        $faker = \Faker\Factory::create();

        $account = new Account();
        $account->setUsername($faker->userName);
        $account->setEmail($faker->email);
        $account->setPassword($faker->sha1);
        $account->setBlocked($faker->boolean);
        $account->setCreatedAt($faker->dateTime);
        $account->setUpdatedAt($faker->dateTime);

        return $account;
    }

    protected function setupSessionEntity()
    {
        $faker = \Faker\Factory::create();

        $session = new Session();
        $session->setSession($faker->sha1);
        $session->setCreatedAt($faker->dateTime);

        return $session;
    }

    protected function setupProfileEntity()
    {
        $faker = \Faker\Factory::create();

        $profile = new Profile();
        $profile->setFullname($faker->name);
        $profile->setBio($faker->sentence);
        $profile->setPhone($faker->phoneNumber);
        $profile->setTall($faker->randomNumber);
        $profile->setWeight($faker->randomNumber);
        $profile->setWebsite($faker->url);
        $profile->setCreatedAt($faker->dateTime);
        $profile->setAccount($this->setupAccountEntity());

        return $profile;
    }
}
