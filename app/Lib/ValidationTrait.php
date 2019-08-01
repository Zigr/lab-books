<?php

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

namespace App\Lib;

/**
 *
 * @date Jun 23, 2019
 * @encoding UTF-8
 * @since 0.1
 */
trait ValidationTrait
{

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        foreach (static::$rules as $fieldName => $constraints)
        {
            foreach ($constraints['contraints'] as $class => $params)
            {
                $class = '\\Symfony\\Component\\Validator\\Constraints\\' . $class;
                $contraint = new $class($params);
                /** @var string $fieldName */
                $metadata->addPropertyConstraint($fieldName, $contraint);
            }
        }
    }

    public function validateWith($className, $fieldName, $locale = 'en')
    {

        $builder = \Symfony\Component\Validator\Validation::createValidatorBuilder();
        $builder->setConstraintValidatorFactory(new \Symfony\Component\Validator\ConstraintValidatorFactory())
                ->addMethodMapping('loadValidatorMetadata')
                ->setTranslator(\Application::get('Translator'))
                ->setTranslationDomain('validations')
        ;

        $metadata = new \Symfony\Component\Validator\Mapping\ClassMetadata(get_class($this));
        $contraints = [];
        $value = $this->$fieldName;
        foreach (static::$rules[$fieldName]['contraints'] as $class => $params)
        {
            $class = '\\Symfony\\Component\\Validator\\Constraints\\' . $class;
            if (isset($params['message']))
            {
                $mes = ['message' => $params['message']];
                unset($params['message']);
            }
            $contraint = new $class($params);
            //$metadata->addPropertyConstraint($fieldName,new $class($params));
            $contraints[] = $contraint;
        }

        $validator = $builder->getValidator();
        $violations = $validator->validate($value, $contraints);

        $messages = [];
        if (0 !== count($violations))
        {
            // there are errors, now you can show them
            foreach ($violations as $violation)
            {
                echo $violation->getMessage() . '<br>';
                $messages[] = \Application::get('Translator')->trans($violation->getMessage());
            }
        }
        return $messages;
    }
}
