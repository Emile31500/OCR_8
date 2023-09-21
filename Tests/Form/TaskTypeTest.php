<?php 

namespace Tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase {


    public function testValidation(){

        $formData = [
            'title' => 'Title phpunit',
            'content' => 'Task added by phpunit',
        ];

        $model = new Task();
        $form = $this->factory->create(TaskType::class, $model);

        $expected = new Task();
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($formData['title'], $model->getTitle());
        $this->assertEquals($formData['content'], $model->getContent());
        $this->assertEquals($expected->isDone(), $model->isDone());
        $this->assertEquals($expected->getUser(), $model->getUser());

        // $this->assertEquals($expected, $model);

    }

    public function testCustomFormView()
    {
        $formData = new Task();

        $view = $this->factory->create(TaskType::class, $formData)
            ->createView();

        $this->assertArrayHasKey('title', $view->vars);
        //$this->assertSame('expected value', $view->vars['title']);
    }

}