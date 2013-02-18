API Response Builder Bundle
===========================
Symfony bundle that allows you easily create JSON/XML API response from the different types of objects.

Getting Started
============
### Composer

The best way to install ResponseBuilderBundle is through [Composer](http://getcomposer.org).

1. Add ``itr/responsebuilder-bundle`` as a dependency in your project's ``composer.json`` file:

        {
            "require": {
                "itr/responsebuilder-bundle": "dev-master"
            }
        }

2. Install your dependencies:

        php composer.phar install

### Configuration

You can specify default builder format in your config.yml file:

        itr_response_builder:
            default_format: json
            # default_format: json

Basic Usage
===========
### ResponseBuilder

You can get ResponseBuilderFactory class directly:

        // creates factory object
        $responseBuilderFactory = new ResponseBuilderFactory('json');
        // gets default builder (here default format is json)
        $responseBuilder = $responseBuilderFactory->getDefault();
        // gets builder by specified format
        $responseBuilder = $responseBuilder->getBuilderForFormat('yml');

Or from service container for example from symfony controller:

        $responseBuilderFactory = $this->get('response_builder_factory');
        // gets default builder (default format could be specified in the configurations file like described above)
        $responseBuilder = $responseBuilderFactory->getDefault();

### ParameterBag

1. Simple example:

        $pb = new ParameterBag();
        $pb->{'level.second.third'} = 'hi';
        $array = $pb->toArray();
        // array('level' => array('second' => array('third' => 'hi'));

        $responseBuilderFactory = $this->get('response_builder_factory');
        $responseBuilder = $responseBuilderFactory->getDefault();
        // return Response object with parameter bag processed into specified format (json or xml)
        $response = $responseBuilder->build($pb);

2. Simple Doctrine entity example:

        // let say we have account entity like this:
        class Account
        {
            private $id;

            private $username;

            private $email;

            ....

        $account = new Account();
        $account->setUsername('noname');
        $account->setEmail('test@example.com');
        $account->setPassword('123456');

        $pb = new ParameterBag();
        $pb->{'account'} = $account;
        $array = $pb->toArray();

        /*
            Account entity processed as array:

            array('account' => array(
                    'username' => 'noname',
                    'email' => 'test@example.com',
                    'password' => '123456',
            );
        */

        // then you can change any value by accessing it directly by its path:
        $pb->{'account.username'} = 'some new username';

        $responseBuilderFactory = $this->get('response_builder_factory');
        $responseBuilder = $responseBuilderFactory->getDefault();
        $response = $responseBuilder->build($pb);


2. Complex Doctrine entity example:

        /*
            If some of entity properties contain another entity (or collection of entities) it will be processed too:

            array('account' => array(
                    'username' => 'noname',
                    'email' => 'test@example.com',
                    'password' => '123456',
                    'profile' => array(
                        'fullname' => 'Jack Jonson',
                        'age' => 37,
                    )
            );
        */

TODO
====
-   ParameterBag code refactoring.