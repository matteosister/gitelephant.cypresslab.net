<?php
/**
 * User: matteo
 * Date: 30/11/12
 * Time: 0.06
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Git;

use Cypress\GitElephantHostBundle\Git\Base\GitBaseService;
use GitElephant\Objects\Object;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Cypress\PygmentsElephantBundle\PygmentsElephant\Pygmentize;
use Doctrine\Common\Persistence\ObjectManager;
use GitElephant\Objects\Diff\DiffChunk;

/**
 * Git Content
 */
class Content extends GitBaseService
{
    const JPG = '\xFF\xD8\xFF';
    const GIF  = 'GIF';
    const PNG  = '\x89\x50\x4e\x47\x0d\x0a\x1a\x0a';

    /**
     * @var Pygmentize
     */
    private $pygmentize;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * Class constructor
     *
     * @param Request                        $request       request
     * @param ObjectManager                  $objectManager document manager
     * @param Pygmentize                     $pygmentize    pygmentize
     * @param \Symfony\Bridge\Monolog\Logger $logger        logger
     */
    public function __construct(Request $request, ObjectManager $objectManager, Pygmentize $pygmentize, Logger $logger)
    {
        $this->request = $request;
        $this->objectManager = $objectManager;
        $this->pygmentize = $pygmentize;
        $this->logger = $logger;
    }

    /**
     * output git Treeobject content
     *
     * @param \GitElephant\Objects\Object $treeObject tree object
     * @param string                      $ref        reference
     *
     * @return string
     */
    public function outputContent(Object $object, $ref = 'HEAD')
    {
        $output = $this->pygmentize->format($this->getGit()->outputRawContent($object, $ref), $object->getName());
        $this->logger->info($output);
        $matches = array();
        preg_match("'<div class=\"highlight\"><pre>(.*)\n</pre></div>'si", $output, $matches);
        $arrContent = preg_split('/\n/', $matches[1]);
        $arrOutput = array();
        $arrNumbers = array();
        foreach ($arrContent as $i => $line) {
            $arrNumbers[] = '<div class="number">'.($i + 1).'</div>';
            $arrOutput[] = '<div class="ln">'.$line.'</div>';
        }

        return array(
            'line_numbers' => implode($arrNumbers),
            'content' => implode($arrOutput)
        );
    }

    /**
     * output git DiffChunkLine content
     *
     * @param \GitElephant\Objects\Diff\DiffChunk $diffChunk
     *
     * @return string
     */
    public function outputChunk(DiffChunk $diffChunk)
    {
        //var_dump($diffChunkLine);
        return implode($diffChunk->getLines(), "\n");
    }
}
