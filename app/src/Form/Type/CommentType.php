<?php

/**
 * Comment type.
 */

namespace App\Form\Type;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class CommentType.
 */
class CommentType extends AbstractType
{

    private Security $security;

    /**
     * Constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $author */
        $author = $options['author'];

        if (!$this->security->getUser()) {
            $builder
                ->add(
                    'guestNickname',
                    TextType::class,
                    [
                        'label' => 'label.nickname',
                        'required' => true,
                        'attr' => ['maxlength' => 50],
                    ]
                )
                ->add(
                    'guestEmail',
                    TextType::class,
                    [
                        'label' => 'label.email',
                        'required' => true,
                        'attr' => ['maxlength' => 50],
                    ]
                );
        }

        $builder->add(
            'content',
            TextType::class,
            [
                'label' => 'label.content',
                'required' => true,
                'attr' => ['maxlength' => 65535],
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'author' => null,
        ]);
        $resolver->setRequired('author');
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'comment';
    }
}
