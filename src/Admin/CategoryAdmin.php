<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class CategoryAdmin extends AbstractAdmin
{
    public function createNewInstance(): object
    {
        return Category::create('');
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        // Nos permite configurar los filtros que queremos mostrar cuando accedamos al panel
        $filter
            // ->add('id')
            ->add('name')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        // Para mostrar los campos que queremos que se vean en la tabla de mostrar todos los elementos.
        $list
            ->add('id')
            ->add('name')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        // Para especificar que campos pertenecen a los formularios.
        $form
            ->add('id', null, ['disabled' => true])
            ->add('name')
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        // Cuando pulsamos en mostrar en campos queremos mostrar.
        $show
            ->add('id')
            ->add('name')
            // ->add('name', null, ['label' => 'Nombre'])
            ;
    }
}
