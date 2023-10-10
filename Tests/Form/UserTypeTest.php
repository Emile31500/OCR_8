<?php 

namespace Tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase {


    public function testValidation(){

        $password = "password";

        $formData = [
            'username'=> 'Username PHPU',
            'email' => 'email@php.unit',
            'password' => [
                'first' => $password,
                'second' => $password,
            ],
        ];

        $model = new User();
        $form = $this->factory->create(UserType::class, $model);

        $expected = new User();
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($formData['username'], $model->getUsername());
        $this->assertEquals($formData['email'], $model->getEmail());
        $this->assertEquals($formData['password']['first'], $model->getPassword());

        // $this->assertEquals($expected, $model);

    }

    /* public function testCustomFormView()
    {
        $formData = new User();

        $view = $this->factory->create(UserType::class, $formData)
            ->createView();
        
        $this->assertArrayHasKey('email', $view->vars);
        //$this->assertSame('expected value', $view->vars['title']);
    }*/

}