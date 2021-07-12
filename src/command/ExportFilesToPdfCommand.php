<?php

namespace App\Command;

use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExportFilesToPdfCommand
 */
class ExportFilesToPdfCommand extends Command
{
    /** @var array - search extensions */
    protected $extensions = ['php', 'html', 'js'];

    /**
     * Command configuration
     */
    protected function configure()
    {
        $this->setName('export')
            ->setDescription('Search files in directory and export founded files with specific extensions')
            ->setHelp('This command open directory recursively, find specific files and export it to pdf')
            ->addArgument('dir', InputArgument::REQUIRED, 'Search directory');
    }


    /**
     * Get files data and process export
     *
     * @param string $dir - search directory
     * @throws MpdfException
     */
    protected function process(string $dir)
    {
        $files = [];
        $d = opendir($dir);
        while (($name = readdir($d)) !== false) {
            if ($name == '.' || $name == '..') continue;

            if (is_dir($dir . '/' . $name)) {
                $this->process($dir . '/' . $name);
            } else {
                $file = realpath($dir . '/' . basename($name));
                $info = pathinfo($file);
                if (isset($info['extension']) && in_array($info['extension'], $this->extensions)) {
                    $files[] = $file;
                }
            }
        }
        closedir($d);

        $this->exportFilesToPdf($files);
    }


    /**
     * Export found files to pdf
     *
     * @param $files - found files
     * @throws MpdfException
     */
    protected function exportFilesToPdf($files)
    {
        foreach ($files as $file) {
            $mpdf = new Mpdf();
            // open file and write code to pdf
            $f = fopen($file,'r');
            while ($line = fgets($f)) {
                $mpdf->WriteHTML($line);
            }
            fclose($f);

            $mpdf->Output($file . '.pdf', 'F');
        }
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws MpdfException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->process($input->getArgument('dir'));
        $output->writeln("Directory structure was successfully exported!");
    }
}