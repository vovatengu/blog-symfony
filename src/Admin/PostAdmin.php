<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class PostAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('body')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('name', null, ['label' => 'Title'])
            ->add('body', null, [
                'label'    => 'Body',
                'template' => '@SonataAdmin/CRUD/list_html.html.twig',
                'truncate' => ['length' => 100],
                'strip'    => true,
            ])
            ->add('id', null, ['label' => 'ID'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'translation_domain' => 'SonataAdminBundle',
                'actions'            => [
                    'show'   => [],
                    'edit'   => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->tab('Post')
                ->with('Content', ['class' => 'col-md-12'])
                    ->add('name', TextType::class, ['label' => 'Title'])
                    ->add('body', CKEditorType::class, [
                        'label'       => 'Body',
                        'config_name' => 'description',
                        'required'    => false,
                    ])
                ->end()
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id', null, ['label' => 'ID'])
            ->add('name', null, ['label' => 'Title'])
            ->add('body', null, ['label' => 'Body'])
        ;
    }
}
