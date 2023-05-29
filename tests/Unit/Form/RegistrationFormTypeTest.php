<?php
// tests/Form/Type/TestedTypeTest.php
namespace App\Tests\Unit\Form;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validation;

class RegistrationFormTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidator();

        // or if you also need to read constraints from annotations
        /*$validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();*/

        return [
            new ValidatorExtension($validator),
        ];
    }


    public function testSubmitValidData()
    {
        $formData = [
            'first_name' => 'Martijn',
            'last_name' => 'Leemans',
            'username' => 'mijn35',
            'email' => 'zomaarivde@hotmail.com',
            'plainPassword' => 'secret',
        ];
        $model = new User();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(RegistrationFormType::class, $model);

        $expected = new User();
        // ...populate $expected properties with the data stored in $formData
        $expected->setFirstName('Martijn');
        $expected->setLastName('Leemans');
        $expected->setUsername('mijn35');
        $expected->setEmail('zomaarivde@hotmail.com');
        //password can't be checked this way since it is set after hashing through the controller
        //$expected->setPassword('secret');


        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }

    public function testCustomFormView()
    {
        $formData = new User();
        // ... prepare the data as you need

        // The initial data may be used to compute custom view variables
        $view = $this->factory->create(RegistrationFormType::class, $formData)
            ->createView();

        $this->assertArrayHasKey('custom_var', $view->vars);
        $this->assertSame('expected value', $view->vars['custom_var']);
    }
}