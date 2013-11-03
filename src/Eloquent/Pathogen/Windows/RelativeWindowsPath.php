<?php

/*
 * This file is part of the Pathogen package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Pathogen\Windows;

use Eloquent\Pathogen\Exception\InvalidPathAtomCharacterException;
use Eloquent\Pathogen\Exception\PathAtomContainsSeparatorException;
use Eloquent\Pathogen\FileSystem\AbstractRelativeFileSystemPath;

/**
 * Represents a relative Windows path.
 */
class RelativeWindowsPath extends AbstractRelativeFileSystemPath implements
    RelativeWindowsPathInterface
{
    /**
     * Validates a single path atom.
     *
     * This method is called internally by the constructor upon instantiation.
     * It can be overridden in child classes to change how path atoms are
     * validated.
     *
     * @param string $atom The atom to validate.
     *
     * @throws Exception\EmptyPathAtomException             If the path atom is empty.
     * @throws Exception\PathAtomContainsSeparatorException If the path atom contains a separator.
     */
    protected function validateAtom($atom)
    {
        parent::validateAtom($atom);

        if (false !== strpos($atom, '\\')) {
            throw new PathAtomContainsSeparatorException($atom);
        } elseif (preg_match('/([\x00-\x1F<>:"|?*])/', $atom, $matches)) {
            throw new InvalidPathAtomCharacterException($atom, $matches[1]);
        }
    }

    /**
     * Creates a new path instance of the most appropriate type.
     *
     * This method is called internally every time a new path instance is
     * created as part of another method call. It can be overridden in child
     * classes to change which classes are used when creating new path
     * instances.
     *
     * @param mixed<string> $atoms                The path atoms.
     * @param boolean       $isAbsolute           True if the new path should be absolute.
     * @param boolean|null  $hasTrailingSeparator True if the new path should have a trailing separator.
     *
     * @return PathInterface The newly created path instance.
     */
    protected function createPath(
        $atoms,
        $isAbsolute,
        $hasTrailingSeparator = null
    ) {
        if ($isAbsolute) {
            return $this->createPathWithDrive(
                $atoms,
                null,
                $hasTrailingSeparator
            );
        }

        return static::factory()->createFromAtoms(
            $atoms,
            false,
            $hasTrailingSeparator
        );
    }

    /**
     * Create a new absolute Windows path with a drive specifier.
     *
     * This method is called internally every time a new path instance with a
     * drive specifier is created as part of another method call. It can be
     * overridden in child classes to change which classes are used when
     * creating new path instances.
     *
     * @param mixed<string> $atoms                The path atoms.
     * @param string|null   $drive                The drive specifier.
     * @param boolean|null  $hasTrailingSeparator True if the new path should have a trailing separator.
     *
     * @return AbsoluteWindowsPathInterface The newly created path instance.
     */
    protected function createPathWithDrive(
        $atoms,
        $drive,
        $hasTrailingSeparator = null
    ) {
        return static::factory()->createFromDriveAndAtoms(
            $atoms,
            $drive,
            true,
            $hasTrailingSeparator
        );
    }

    /**
     * Get the most appropriate path factory for this type of path.
     *
     * @return PathFactoryInterface The path factory.
     */
    protected static function factory()
    {
        return Factory\WindowsPathFactory::instance();
    }
}
