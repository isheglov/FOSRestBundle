<?php

/*
 * This file is part of the FOSRestBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\RestBundle\Controller\Annotations;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

/**
 * Represents a file that must be present.
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @author Ener-Getick <egetick@gmail.com>
 */
class FileParam extends AbstractParam
{
    /** @var bool */
    public $strict = true;

    /** @var mixed */
    public $requirements = null;

    /** @var bool */
    public $image = false;

    /** @var bool */
    public $map = false;

    /**
     * {@inheritdoc}
     */
    public function getConstraints()
    {
        $constraints = parent::getConstraints();
        if ($this->requirements instanceof Constraint) {
            $constraints[] = $this->requirements;
        }

        $options = is_array($this->requirements) ? $this->requirements : [];
        if ($this->image) {
            $constraints[] = new Image($options);
        } else {
            $constraints[] = new File($options);
        }

        // If the user wants to map the value
        if ($this->map) {
            $constraints = [
                new All(['constraints' => $constraints]),
            ];
        }

        return $constraints;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(Request $request, $default = null)
    {
        return $request->files->get($this->getKey(), $default);
    }
}
