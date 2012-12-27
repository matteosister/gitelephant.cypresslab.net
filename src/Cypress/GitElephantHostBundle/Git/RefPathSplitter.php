<?php
/**
 * User: matteo
 * Date: 14/12/12
 * Time: 23.48
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Git;

use JMS\DiExtraBundle\Annotation\Service;
use GitElephant\Repository;

/**
 * split the ref from the path
 *
 * @Service("ref_path.splitter")
 */
class RefPathSplitter
{
    /**
     * split
     *
     * @param \GitElephant\Repository $repository repo
     * @param string                  $ref        reference
     * @param string                  $path       path
     *
     * @throws \RuntimeException
     * @return array
     */
    public function split(Repository $repository, $ref, $path)
    {
        if (null !== $repository->getBranchOrTag($ref)) {
            return array($ref, $path == '' ? null : $path);
        }
        $parts = explode('/', $ref);
        $newRef = '';
        for ($i = 0; $i < count($parts) - 1; $i++) {
            $newRef = ltrim($newRef.'/'.$parts[$i], '/');
            if (null !== $repository->getBranchOrTag($newRef)) {
                $newPath = '' === $path ? ltrim(str_replace($newRef, '', $ref), '/') : $path;

                return array($newRef, $newPath);
            }
        }
        throw new \RuntimeException(sprintf('ref %s and path %s are not splittable', $ref, $path));
    }
}
