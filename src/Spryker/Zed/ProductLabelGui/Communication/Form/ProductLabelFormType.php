<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\UniqueProductLabelNameConstraint;
use Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductLabelFormType extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_EXCLUSIVE_FLAG = 'isExclusive';
    const FIELD_STATUS_FLAG = 'isActive';
    const FIELD_VALID_FROM_DATE = 'validFrom';
    const FIELD_VALID_TO_DATE = 'validTo';
    const FIELD_FRONT_END_REFERENCE = 'frontEndReference';
    const FIELD_LOCALIZED_ATTRIBUTES = 'localizedAttributes';

    /**
     * @var \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface $queryContainer
     */
    public function __construct(ProductLabelGuiQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'productLabel';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ProductLabelTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addNameField($builder)
            ->addStatusFlagField($builder)
            ->addExclusiveFlagField($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder)
            ->addFontEndReferenceField($builder)
            ->addLocalizedAttributesSubForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_NAME,
            TextType::class,
            [
                'label' => 'Name *',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new UniqueProductLabelNameConstraint([
                        UniqueProductLabelNameConstraint::OPTION_QUERY_CONTAINER => $this->queryContainer,
                    ]),
                ]
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addExclusiveFlagField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_EXCLUSIVE_FLAG,
            CheckboxType::class,
            [
                'label' => 'Is Exclusive',
                'required' => false,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStatusFlagField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_STATUS_FLAG,
            CheckboxType::class,
            [
                'label' => 'Is Active',
                'required' => false,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_VALID_FROM_DATE,
            DateType::class,
            [
                'label' => 'Valid From',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'js-valid-from-date-picker',
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_VALID_TO_DATE,
            DateType::class,
            [
                'label' => 'Valid To',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'js-valid-to-date-picker',
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFontEndReferenceField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_FRONT_END_REFERENCE,
            TextType::class,
            [
                'label' => 'Front-end Reference',
                'required' => false,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedAttributesSubForm(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_LOCALIZED_ATTRIBUTES,
            CollectionType::class,
            [
                'entry_type' => new ProductLabelLocalizedAttributesType(),
                'property_path' => 'localizedAttributesCollection',
            ]
        );

        return $this;
    }

}
