<?php
/**
 * User: matteo
 * Date: 09/12/12
 * Time: 0.26
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * repository type
 *
 * @FormType
 */
class RepositoryType extends AbstractType
{
    /**
     * build
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder builder
     * @param array                                        $options options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('gitUrl');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'repository';
    }
}
