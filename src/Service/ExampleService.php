<?php
// src/Service/ExampleService.php
// ...

//this is an example directly from the symfony tutorial. It serves no purtpose aside from being a reference

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;



class ExampleService
{
    public function __construct(
        // Avoid calling getFirewallConfig() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        private Security $security,
        RequestStack $requestStack,
    ) {
    }

    public function someMethod()
    {
        $request = $this->requestStack->getCurrentRequest();
        $firewallName = $this->security->getFirewallConfig($request)?->getName();

        // ...
    }
}