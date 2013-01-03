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
use Symfony\Component\HttpFoundation\Request;
use GitElephant\Objects\TreeObject;
use PygmentsElephant\Pygmentize;
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
     * Class constructor
     *
     * @param Request       $request       request
     * @param ObjectManager $objectManager document manager
     * @param Pygmentize    $pygmentize    pygmentize
     */
    public function __construct(Request $request, ObjectManager $objectManager, Pygmentize $pygmentize)
    {
        $this->request = $request;
        $this->objectManager = $objectManager;
        $this->pygmentize = $pygmentize;
    }

    /**
     * output git treeobject content
     *
     * @param \GitElephant\Objects\TreeObject $treeObject
     *
     * @return string
     */
    public function outputContent(TreeObject $treeObject)
    {
        $rawContent = implode("\n", $this->getGit()->outputContent($treeObject, 'HEAD'));
        $output = $this->pygmentize->format($rawContent, $treeObject->getName());
        $startPos = strpos($output, '<pre>') + 5;
        $closePos = strrpos($output, '</pre>');
        $content = substr($output, $startPos, $closePos - $startPos);
        $arrContent = explode("\n", $content);
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
