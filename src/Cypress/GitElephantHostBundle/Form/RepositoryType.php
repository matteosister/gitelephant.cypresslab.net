<?php
/**
 * User: matteo
 * Date: 09/12/12
 * Time: 0.26
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Form;

use Cypress\GitElephantHostBundle\Github\User;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation\FormType;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('name', null, array(
                'attr' => array(
                    'placeholder' => 'Repository name',
                    'class' => 'input-xlarge'
                )
            ))
            ->add('gitUrl', null, array(
                'label' => 'Repository url',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Repository url',
                    'class' => 'input-xxlarge'
                )
            ))
            ->add('recaptcha', 'ewz_recaptcha', array(
                'attr' => array(
                    'options' => array(
                        'theme' => 'clean'
                    )
                ),
                'mapped' => false,
                'constraints' => array(
                    new True()
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => 'Cypress\GitElephantHostBundle\Entity\Repository'
        ));
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
